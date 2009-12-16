<?php

require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/messages.class.php");

/*
**
*/

define("SCHEDULER_DIRTY", 0x1);	

/*
** Run() : Run the current task
** Type() : Return the task type
** MaxInstances() : the max number of task type a user can own
** AlwaysVisible() : Task is visible, also if it is not active.
**
*/

interface ITask
{  
    public function Run($context_user, $arguments);
	public function Type();
	public function MaxInstances();
	public function AlwaysVisible();
}  

/*
**
*/

class CBonusInviteChips implements ITask
{
	public function Run($context_user, $arguments)
	{   
		$nchips = $context_user->Chips();
		$allocation = 5000;
		$context_user->chips = $nchips + $allocation;
		$context_user->Save();
		drupal_set_message(t("Invitation Bonus : 5000 Chips"));
	}
	
	public function Type()
	{
		return get_class($this);
	}
	
	public function MaxInstances()
	{
		return 1;
	}
	
	public function AlwaysVisible()
	{
		return FALSE;
	}
	
}


/*
**
*/

class CDailyChips implements ITask 
{   
	public function	GetChipsAttributions($invites, & $nextInvites = FALSE, & $nextChips = FALSE)
	{
		$allocation = 500;
		$attribs = array(
							50 => 5000,
							45 => 4500,
							40 => 4000,
							35 => 3500,	
							30 => 3000,
							25 => 2500,	
							20 => 2000,
							15 => 1500,
							10 => 1000,
							5 => 750,
						);
	
		foreach ($attribs as $iv => $cv)
		{
			if ($invites >= $iv)
			{
				$allocation = $cv;
				break;
			}
			
			$nextInvites = $iv;
			$nextChips = $cv;
		}
		
		return $allocation;
	}

	public function Run($context_user, $arguments)
	{   
		$nchips = $context_user->Chips();
		$invites = $context_user->Invites();
		$nInvites = count($invites["accepted"]);

		$allocation = $this->GetChipsAttributions($nInvites);
						
		$context_user->chips = $nchips + $allocation;
		$context_user->Save();
		CScheduler::instance()->RegisterTask($this, $context_user->uid, array('login', 'live'), "+1 day");
		drupal_set_message(t("Daily gift : !invites Invites = !nchips Chips allocated", array("!invites" => $nInvites, "!nchips" => $allocation)));
	}
	
	public function Type()
	{
		return get_class($this);
	}
	
	public function MaxInstances()
	{
		return 1;
	}
	
	public function AlwaysVisible()
	{
		return FALSE;
	}
}

//----------------------------------

class CItemExpiry implements ITask
{
	public function Run($context_user, $arguments)
	{   
		if ($arguments && isset($arguments["id_operation"]))
		{
			$sql = "DELETE FROM `{poker_operation}` WHERE `uid` = %d AND `id_operation` = %d";
			$res = db_query($sql, $context_user->uid, $arguments["id_operation"]);
			$sql = "DELETE FROM `{poker_user_ext}` WHERE `uid` = %d AND `id_operation` = %d";
			$res = db_query($sql, $context_user->uid, $arguments["id_operation"]);
			
			//drupal_set_message(t("Item instance !id has expired", array("!id" => $arguments["id_operation"])));
		}
	}
	
	public function Type()
	{
		return get_class($this);
	}
	
	public function MaxInstances()
	{
		return -1;
	}
	
	public function AlwaysVisible()
	{
		return FALSE;
	}
}

/*
**
*/

class CScheduler
{
	private static $_instance;
	
	private $_tasks = array();
	private $_user = NULL;
	
	/*
	**
	*/
	
	public static function instance()
	{
	    if (!self::$_instance instanceof self)
		{ 
			self::$_instance = new self;
	    }
	    return self::$_instance;
	}
	
	/*
	**
	*/
	
	protected function __construct()
	{
		//Load all active tasks
		$this->_user = CUserManager::instance()->CurrentUser();
		$this->ReloadTasks();
	}

	public function	DestroyTask($task_ids)
	{
		if (is_array($task_ids) && count($task_ids) > 0)
		{
			$toDestroy = array_fill(0, count($task_ids), "`id_task` = %d");
			
			$sql = "DELETE FROM `{poker_scheduler}` WHERE ";
			$sql .= implode(" OR ", $toDestroy);
			
			return db_query($sql, $task_ids);
		}
		return FALSE;
	}
	
	public function	Trigger($name)
	{
		$runTasks = 0;
		
		if (isset($this->_tasks[$name]))
		{
			$toDetroy = array();
			
			foreach ($this->_tasks[$name] as $key => $value)
			{
				try
				{
					$task = new $value->type();
					
					if ($task instanceof ITask && $value->active)
					{
						$task->Run($this->_user, json_decode($value->arguments, TRUE));
						$toDetroy [] = $value->id_task;
						++$runTasks;
					}
				}
				catch (Exception $e) { }
				
				foreach ($value->trigger as $t)
				{
					unset($this->_tasks[$t][$key]);
				}
			}

			$this->DestroyTask($toDetroy);
		}
		
		return ($runTasks);
	}
	
	private function	CanAddTask($task, $uid)
	{
		if ($task->MaxInstances() < 0)
		{
			return TRUE;
		}
		else
		{
			$sql = "SELECT COUNT(*) AS instances FROM `{poker_scheduler}` WHERE `uid` = %d AND `type` = '%s'";
			
			$res = db_query($sql, $uid, $task->Type());
			
			if ($res)
			{
				$obj = db_fetch_object($res);
				
				if ($obj)
				{
					if ($obj->instances < $task->MaxInstances())
					{
						return TRUE;
					}
					else if ($obj->instances > $task->MaxInstances())
					{
						throw new Exception(t('ERROR : there is more task (!task) than allowed for user !id',  array("!task" => $task->Type(), "!id" => $uid)));
					}
				}
			
			}
		}
		return FALSE;
	}
	
	/*
	** Defined triggers :
	** cron : depending on drupal cron (if set to default : 1h)
	** first_login : tiggered only once, at the first time the user log in
	** login : user logs in
	** logout : user logs out
	** live : triggered by ajax poll
	*/
	
	public function RegisterTask(ITask $task, $uid, $triggers, $moment = "-1 day" /* if moment ids undefined, task will run asap */, $arguments = NULL)
	{

		if ($this->CanAddTask($task, $uid))
		{
			$t = date("Y-m-d H:i:s", strtotime($moment));
			
			if ($t)
			{
				if ($triggers)
				{
					$sql = "INSERT INTO `{poker_scheduler}` (`uid`, `type`, `trigger`, `moment`, `visible`, `arguments`) VALUES(%d, '%s', '%s', '%s', %d";
					
					$trigg = (is_string($triggers)) ? array($triggers) : $triggers;
					$triggers = json_encode($trigg);
					
					if ($arguments == NULL)
					{
						$sql .= ", %s)";
						$arguments = "NULL";
					}
					else
					{
						$arguments = json_encode($arguments);
						$sql .= ", '%s')";
					}
					
					if (strlen($arguments) > 1024)
						throw new Exception(t('Warning !field parameter is too long (max 1024 chars)',  array("!field" => "arguments")));					
					if (strlen($triggers) > 256)
						throw new Exception(t('Warning !field parameter is too long (max 256 chars)',  array("!field" => "trigger")));					
					if ($task->Type() > 32)
						throw new Exception(t('Warning !field parameter is too long (max 32 chars)',  array("!field" => "type")));
					
				
					$res = db_query($sql, $uid, $task->Type(), $triggers, $t, $task->AlwaysVisible(), $arguments);
					
					if ($res)
					{
						$this->SetNewTask($uid);
						return db_last_insert_id("poker_scheduler", "id_task");
					}
				}
			}
		}
		
		return -1;
	}
	
	public function GetTriggers()
	{
		return array_keys($this->_tasks);
	}
	
	public function	GetTasks($trigger = NULL)
	{
		if ($trigger)
		{
			if (isset($this->_tasks[$trigger]))
			{
				return $this->_tasks[$trigger];
			}
			return array();
		}
		
		return $this->_tasks;
	}
	
	private	function	ClearNewTask()
	{
		$sql = "INSERT INTO `{poker_user_ext}` (`uid`, `dirty_flags`) VALUES (%d, 0) ON DUPLICATE KEY UPDATE `dirty_flags`= (`dirty_flags` & %d)";

		if (!db_query($sql, $this->_user->uid, ~SCHEDULER_DIRTY))
			throw new Exception(t('Failed to clear scheduler dirty flag'));
	}
	
	private function	SetNewTask($uid)
	{
		$sql = "INSERT INTO `{poker_user_ext}` (`uid`, `dirty_flags`) VALUES (%d, %d) ON DUPLICATE KEY UPDATE `dirty_flags`= (`dirty_flags` | %d)";
		
		if (!db_query($sql, $uid, SCHEDULER_DIRTY, SCHEDULER_DIRTY))
			throw new Exception(t('Failed to set scheduler dirty flag'));
	}
	
	public	function	IsNewTask()
	{
		$sql = "SELECT `dirty_flags` FROM `{poker_user_ext}` WHERE `uid` = %d LIMIT 1";
		$res = db_query($sql, $this->_user->uid);
		
		if ($res)
		{
			$flags = db_result($res);
			
			return (bool)(SCHEDULER_DIRTY & (int)$flags);
		}
		
		return FALSE;
	}

	public function ReloadTasks()
	{
		$this->_tasks = array();
	
		$sql = "SELECT `ps`.*, ISNULL(`a`.`id_task`) AS `active`
				FROM `{poker_scheduler}` AS `ps`
				LEFT JOIN (SELECT `id_task` FROM `{poker_scheduler}` WHERE `uid`= %d AND `moment` > NOW()) AS `a` USING(`id_task`)
				WHERE `uid`= %d AND (`moment` <= NOW() OR `visible` = 1) ORDER BY `moment` DESC";
		
		$res = db_query($sql, $this->_user->uid, $this->_user->uid);
		
		if ($res)
		{
			while (($task = db_fetch_object($res)))
			{
				$triggers = json_decode($task->trigger);
				
				if (is_array($triggers))
				{
					$task->trigger = $triggers;
					
					foreach($triggers as $trigger)
					{
						$this->_tasks[$trigger][$task->id_task] = $task;
					}
				}
			}
			
			$this->ClearNewTask();
		}
	}
	
	/*
	** Helpers, only for tests
	*/
	
	public static function GetUserTasks($uid) 
	{
		$utasks = array();
		
		if (!is_numeric($uid))
			throw new Exception(t('Error : bad parameter to CScheduler::GetUsertasks (wait only for numerics)'));	
		
		$sql = "SELECT `ps`.*, ISNULL(`a`.`id_task`) AS `active`
				FROM `{poker_scheduler}` AS `ps`
				LEFT JOIN (SELECT `id_task` FROM `{poker_scheduler}` WHERE `uid`= %d AND `moment` > NOW()) AS `a` USING(`id_task`)
				WHERE `uid`= %d AND (`moment` <= NOW() OR `visible` = 1) ORDER BY `moment` DESC";
		
		$res = db_query($sql, $uid, $uid);
		
		if ($res)
		{
			while (($task = db_fetch_object($res)))
			{
				$triggers = json_decode($task->trigger);
				
				if (is_array($triggers))
				{
					$task->trigger = $triggers;
					
					foreach($triggers as $trigger)
					{
						$utasks[$trigger][$task->id_task] = $task;
					}
				}
			}
			return $utasks;
		}
		
		return FALSE;
	}
	
	public static function GetRawUserTasks($uid) 
	{
		$utasks = array();
		
		if (!is_numeric($uid))
			throw new Exception(t('Error : bad parameter to CScheduler::GetUsertasks (wait only for numerics)'));	
		
		$sql = "SELECT `ps`.*, 1 AS `active`
				FROM `{poker_scheduler}` AS `ps`
				WHERE `uid`= %d ORDER BY `moment` DESC";
		
		$res = db_query($sql, $uid);
		
		if ($res)
		{
			while (($task = db_fetch_object($res)))
			{
				$triggers = json_decode($task->trigger);
				
				if (is_array($triggers))
				{
					$task->trigger = $triggers;
					
					foreach($triggers as $trigger)
					{
						$utasks[$trigger][$task->id_task] = $task;
					}
				}
			}
			return $utasks;
		}
		
		return FALSE;
	}
	
	public static function	TriggerHelper($name, $user, & $tasks)
	{
		$runTasks = 0;
		
		if (isset($tasks[$name]))
		{
			$toDetroy = array();
			
			foreach ($tasks[$name] as $key => $value)
			{

				$task = new $value->type();
				
				if ($task instanceof ITask && $value->active)
				{
					$task->Run($user, json_decode($value->arguments, TRUE));
					$toDetroy [] = $value->id_task;
					++$runTasks;
				}
				
				foreach ($value->trigger as $t)
				{
					unset($tasks[$t][$key]);
				}
			}

			CScheduler::instance()->DestroyTask($toDetroy);
		}
		
		return ($runTasks);
	}
	
	public static function	TaskFlush(ITask $task, $user)
	{
		$trigg = (is_string($triggers)) ? array($triggers) : $triggers;
		$triggers = json_encode($trigg);
		
		$sql = "DELETE FROM `{poker_scheduler}` WHERE `uid`= %d AND `type` = '%s'";
		
		return db_query($sql, $user, $task->Type());
	}
	
	
}
