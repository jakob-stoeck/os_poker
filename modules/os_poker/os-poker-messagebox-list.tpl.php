<?php
//
//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
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
?>
<div class="inner-item-list messagebox-ajax-list">
	<?php
		$nmsg = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 4;
		
		if ($messages && count($messages) > 0)
		{
			$begin = $page * $perPage;
			$end = $begin + $perPage;

			foreach($messages as $m)
			{
				if ($nmsg >= $begin && $nmsg < $end)
				{
					$mbody = json_decode($m->arguments, TRUE);
					$mbody["body"]["timestamp"] = $m->timestamp;
					print theme('os_poker_message', $mbody["body"]);
				}
				++$nmsg;
			}
		}
		else
		{
			print t("You don't have any message.");
		}	
	?>
</div>
<div class="ajax-pager">
	<?php 
		if ($nmsg > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = (int)($nmsg / $perPage); // total number of pages
			if ($nmsg % $perPage)
				$GLOBALS['pager_total'][0] = $GLOBALS['pager_total'][0] + 1;
			print theme('pager', array(), $perPage, 0, array("ajax" => TRUE, "list" => "messages"), 5);
		}
	?>
</div>
<?php
			if (arg(0) == 'poker' && arg(1) == 'messagebox' && !empty($_GET['ajax'])) {
?>
<script type="text/javascript">
Drupal.attachBehaviors($(".messagebox-ajax-list").get(0));
</script>

<?php
					}
?>

