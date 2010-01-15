<?php // -*- mode: php; tab-width: 2 -*-
//
//    Copyright (C) 2009, 2010 Pokermania
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//


/*
**	Item class
*/

class CItem
{
	private $_fields = array(
								"id_item" => array(
													"value" => NULL,
													"dirty" => TRUE,
													),
								"id_category" => array(
													"value" => NULL,
													"dirty" => FALSE,
													),
								"picture" => array(
													"value" => NULL,
													"dirty" => FALSE,
													"string" => TRUE,
													),
								"name" => array(
													"value" => NULL,
													"dirty" => FALSE,
													"string" => TRUE,
													),
								"price" => array(
													"value" => 0,
													"dirty" => FALSE,
													),
								"available" => array(
													"value" => FALSE,
													"dirty" => FALSE,
													),
								"ttl" => array(
												"value" => NULL,
												"dirty" => FALSE,
												"string" => TRUE,
												),
							);

	/*
	**
	*/

	public function __construct( $item = NULL )
	{
		if ($item !== NULL)
		{
			if (is_numeric($item))
			{
				$sql = "SELECT * FROM `{poker_item}` WHERE `id_item` = %d LIMIT 1";

				$res = db_query($sql, $item);

				if ($res)
				{
					$i = db_fetch_array($res);

					if ($i== FALSE)
					{
						throw new Exception(t('No item with id : !id',  array("!id" => $item)));
					}
					$item = $i;
				}
				else
				{
					throw new Exception(t('DB error : !message', array("!message" => db_error())));
				}
			}

			if (is_object($item))
			{
				$item = (array)$item;
			}

			if (is_array($item))
			{
				foreach ($item as $key => $value)
				{
					if (isset($this->_fields[$key]))
					{
						$this->_fields[$key]["value"] = $value;
					}
					else
					{
						throw new Exception(t('Object "!class" doesn\'t contains field "!field"', array("!class" =>  get_class($this), "!field" => $key)));
					}
				}
			}
			else
			{
				throw new Exception(t('Invalid parameter to "!class" ctor.', array("!class" =>  get_class($this))));
			}
		}
	}

	public function Save()
	{
		$cols = array();
		$vals = array();
		$filter = array();

		foreach ($this->_fields as $key => $value)
		{
			if ($value["dirty"] == TRUE)
			{
				$cols []= "`{$key}`";

				if (empty($value["string"]))
				{
					$vals []= $value["value"];
					$filter []= "%d";
				}
				else if (empty($value["value"]))
				{
					$vals []= "NULL";
					$filter []= "'%s'";
				}
				else
				{
					$vals []= $value["value"];
					$filter []= "'%s'";
				}
			}
		}

		if (count($cols) > 1)
		{
			$sql = "INSERT INTO `{poker_item}` ";
			$sql .= " (" . implode(", ", $cols) . ") ";
			$sql .= " VALUES (" . implode(", ", $filter) . ") ";
			$sql .= "ON DUPLICATE KEY UPDATE ";
			$i = 0;
			foreach ($cols as $keys)
			  {
			    if ($i > 0)
			      $sql .= ",";
			    $sql .= "$keys = '".$vals[$i]."' ";
			    $i++;
			  }
			$sql .= "  ;";

			$res = db_query($sql, $vals);

			if ($res == FALSE)
			{
				throw new Exception(t('DB error : !message', array("!message" => db_error())));
			}
		}
	}

	public function	FormatedPrice()
	{
		require_once(drupal_get_path('module', 'os_poker') . "/os_poker_toolkit.php");

		return _os_poker_format_chips($this->_fields["price"]["value"]);
	}

	public function __get( $key )
	{
		if (isset($this->_fields[$key]))
		{
			return $this->_fields[$key]["value"];
		}

		throw new Exception(t('Object "!class" doesn\'t contains field "!field"', array("!class" =>  get_class($this), "!field" => $key)));
	}

	public function __set( $key, $value )
	{
		if (isset($this->_fields[$key]))
		{
			if ($key != "id_item")
			{
				$this->_fields[$key]["value"] = $value;
				$this->_fields[$key]["dirty"] = TRUE;
			}
			return;
		}

		throw new Exception(t('Object "!class" doesn\'t contains field "!field"', array("!class" =>  get_class($this), "!field" => $key)));
	}
}

/*
**
*/

class CShop
{
	public static function ListCategories()
	{
		$sql = "SELECT * FROM `{poker_category}`";
		$res = db_query($sql, $params);
		$categories = array();

		if ($res)
		{
			while (($obj = db_fetch_object($res)))
			{
				$categories[$obj->id_category] = $obj->name;
			}
		}

		return $categories;
	}

	public static function BuyItem($item_id)
	{
		require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");
		require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");
		$user = CUserManager::instance()->CurrentUser();

		try
		{
      $item = new CItem($item_id); //check if the item really exists

      /*
			** User must pay !
			*/

			$nchips = $user->Chips();

			if ($nchips < $item->price)
				throw new Exception(t('User doesn\'t have enough money.'));

			$sql = "INSERT INTO `{poker_operation}`
				   (`id_item`, `uid`, `source_uid`, `tstamp`)
				   VALUES (%d, %d, %d, %s)";

			$res = db_query($sql, $item->id_item, $user->uid, $user->uid, "NOW()");

			if ($res == FALSE)
				throw new Exception(t('DB error : !message', array("!message" => db_error())));

			/*
			** Expiry
			*/

			$operation_id = db_last_insert_id("poker_operation", "id_operation");
			$ttl = $item->ttl;

			if ($operation_id && !empty($ttl))
			{
				CScheduler::instance()->RegisterTask(new CItemExpiry(), $user->uid, array('live'), $ttl, array("id_operation" => $operation_id));
			}

			/*
			** User must pay !
			*/

			$user->chips = $nchips - $item->price;
			$user->Save();
		}
		catch (Exception $e)
		{
			return FALSE;
		}

		return TRUE;
	}

	public static function GiveItem($item_id, $targets, $debug = FALSE)
	{
		require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");
		require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");


		$user = CUserManager::instance()->CurrentUser();

		try
		{
			if (!is_array($targets) || count($targets) == 0)
				throw new Exception(t('Bad parameter: !cause', array('!cause' => (is_array($targets) ? t('$target is empty') : t('$targer is not an array')))));

			$rawTargets = CUserManager::instance()->UserList($targets);
			$targets = array_filter($rawTargets, "_os_poker_user_accepts_gifts");

			$ntargets = count($targets);

			if ($ntargets == 0)
				throw new Exception(t('No one of targets accepts gifts'));

			$item = new CItem($item_id); //check if the item really exists

			/*
			** User must pay !
			*/

			$nchips = $user->Chips();
			$price = ($item->price * $ntargets);

			if ($nchips < $price)
				throw new Exception(t('User doesn\'t have enough money (!uc vs !cn needed).', array('!uc' => $nchips, '!cn' => $price)));


			$sql = "INSERT INTO `{poker_operation}`
				   (`id_item`, `uid`, `source_uid`, `tstamp`)
				   VALUES ";

			foreach ($targets as $target)
			{

				$fields []= "(%d, %d, %d, %s)";
				$values []= $item->id_item;
				$values []= $target->uid;
				$values []= $user->uid;
				$values []= "NOW()";
			}

			$sql .= implode(", ", $fields);

			$res = db_query($sql, $values);

			if ($res == FALSE)
				throw new Exception(t('DB error : !message', array("!message" => db_error())));

			/*
			** Expiry
			*/

			$operation_id = db_last_insert_id("{poker_operation}", "id_operation");
			$ttl = $item->ttl;

			if ($operation_id && !empty($ttl))
			{
			  //$operation_id -= (count($targets) - 1);

				foreach ($targets as $target)
				{
          $gift = array(
            'item' => $item->name,
            'receiver' => $target->uid,
            'sender' => $user->uid,
          );
					if ($target->ActiveItem() <= 0)
					{
						$target->ActivateItem($operation_id, $gift);
					}
          else {
            //Send gift notification, even if the item is not activated
            foreach($target->Tables() as $table) {
              foreach(CPoker::UsersAtTable($table->serial) as $notified_uid) {
                CScheduler::instance()->RegisterTask(new CGiftNotificationMessage(), $notified_uid, array('live'), "-1 day", $gift);
              }
            }
          }

					CScheduler::instance()->RegisterTask(new CItemExpiry(), $target->uid, 'live', $ttl, array("id_operation" => $operation_id));
					++$operation_id;

					$args["symbol"] = 'item';
					$args["text"] = t("You just receive a gift from !user", array("!user", $user->profile_nickname));

					if (_os_poker_user_accepts_gifts($user))
					{
						$args["links"] = l(t("Send a gift in return"), "poker/shop/shop/1/buddy/" . $user->uid);
					}

					CMessageSpool::instance()->SendMessage($target->uid, $args);
				}
			}

			/*
			** User must pay !
			*/

			$user->chips = $nchips - $price;
			$user->Save();
		}
		catch (Exception $e)
		{
			if ($debug == TRUE)
				throw $e;
			return FALSE;
		}

		return TRUE;
	}

	public static function ListItems($category = NULL)
	{
		$params = array();
		$ItemList = array();

		$sql = "SELECT * FROM `{poker_item}`";

		if ($category != NULL)
		{
			$sql .= " WHERE `id_category` = %d";
			$params []= $category;
		}

		$res = db_query($sql, $params);

		if ($res)
		{
			while (($item = db_fetch_array($res)))
			{
				$ItemList [] = new CItem($item);
			}
		}
		else
		{
			throw new Exception(t('DB error : !message', array("!message" => db_error())));
		}

		return $ItemList;
	}
}

?>