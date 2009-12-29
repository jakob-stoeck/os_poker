<?php

$invites = $current_user->Invites();

$list_invited = array_merge($invites["pending"], $invites["accepted"]);
usort($list_invited, "_os_poker_sort_invites");

?>

<div id="buddies-invited_list">

 <div id="ContainerContentInvite">
  
  <!-- Table Left -->
  <table cellspacing="0" cellpadding="0" class="TableLeft">
  <tbody><tr>
  <th class="Column01">Email</th>
  <th class="Column02">Date</th>
  <th class="Column03">Status</th>
  <th class="Column04">Remind</th>
  <th style="border-right: medium none;" class="Column05">Delete</th>
  </tr>
<?php
$page = $_GET["page"];
//print "page : ".$page."<br/>";
$items_per_page = 15;
$page_total = ceil(count($list_invited) / $items_per_page) ;
for ($i = 0; $i < $items_per_page; $i++)
  {
	$value = $list_invited[$items_per_page * $page + $i];
	
	if ($value)
    {
		$list_item = array();

		if ($value->joined != 0)
		{
			$invitee = CUserManager::instance()->User($value->invitee);
			
			$list_item["name"] = $value->email;
			$list_item["status"] = 1;
			$list_item["remind"] = "-";
			$list_item["delete"] = "-";
		}
		else
		{
			$list_item["name"] = $value->email;
			$list_item["status"] = 0;
			
			$img = '<img alt="" src="sites/all/themes/poker/images/icon_buttonred.gif"/>';
			$list_item["remind"] = "<a href='javascript:void(0);' onclick=\"javascript:os_poker_submit(this, 'invite-action-remind-form-{$i}');\">{$img}</a>";
			$list_item["delete"] = "<a href='javascript:void(0);' onclick=\"javascript:os_poker_submit(this, 'invite-action-delete-form-{$i}');\">{$img}</a>";
		}
		
		$list_item["date"] = date("d.m.y", $value->created);
  
?>
		<tr>	
			<td class=<?php print '"'.($i % 2 ? "BgWhite" : "BgGrey").'"' ?>><p><?php print $list_item["name"]; ?></p></td>
			<td class=<?php print '"'.($i % 2 ? "BgWhite" : "BgGrey").'"' ?>><?php print $list_item["date"]; ?></td>
			<td class=<?php print '"'.($i % 2 ? "BgWhite Center" : "BgGrey Center").'"' ?>><?php print ($list_item["status"] == 1 ? '<img alt="" src="sites/all/themes/poker/images/icon_accepted.gif"/>' : '<img alt="" src="sites/all/themes/poker/images/icon_notconfirmed.gif"/>'); ?></td>
		
			<td class=<?php print '"'.($i % 2 ? "BgWhite Center" : "BgGrey Center").'"'?>>
				<form id="invite-action-remind-form-<?php print $i; ?>" method="post" action="<?php print url("<front>", array("query" => "q=poker/buddies/invitedlist")) ?>" >
					<input type="hidden" name="invite_target" value="<?php print $value->email; ?>" />
					<input type="hidden" name="invite_action" value="remind" />
					<?php print $list_item["remind"]; ?>
				</form>
			</td>
		
			<td style="border: medium none ;" class=<?php print '"'.($i % 2 ? "BgWhite Center" : "BgGrey Center").'"'?>>
				<form id="invite-action-delete-form-<?php print $i; ?>" method="post" action="<?php print url("<front>", array("query" => "q=poker/buddies/invitedlist")) ?>" >
					<input type="hidden" name="invite_target" value="<?php print $value->email; ?>" />
					<input type="hidden" name="invite_action" value="delete" />
					<?php print $list_item["delete"]; ?>
				</form>
			</td>
		</tr>
<?php
	}
  }
?>
  </tbody>
 </table>

<div class="PageNavPosition">
    
    <!-- Page Navigation -->

    <div class="Clear"></div><div class="PageNav">
<?php
$GLOBALS['pager_page_array'][] = $page; //what page you are on
$GLOBALS['pager_total'][] = $page_total; // total number of pages
print theme('pager', NULL, $items_per_page);
?>
 </div>
<!--
Page: <a href="#">1</a> <span class="Marked">2</span> <a href="#">3</a> <a href="#"><span>Â</span></a></div>
-->
    <!-- /Page Navigation -->


    <!-- Legend -->
    <div class="Legend">
    <p>Invitation accepted=</p>
    <div><img alt="" src="sites/all/themes/poker/images/icon_accepted.gif"/></div>
    <p style="margin-left: 10px;">Not yet confirmed=</p>
    <div><img alt="" src="sites/all/themes/poker/images/icon_notconfirmed.gif"/></div>
    <div class="Clear"/>
    </div>
    <!-- /Legend -->
    
    </div>
</div>



<table cellspacing="0" cellpadding="0" class="TableRight">
    <tbody><tr>
    <th colspan="2">Overview</th>
    </tr>
    
    <tr>
    <td colspan="2">You have invited<br/><span><?php print $invites["total"]; ?></span> friends</td>
    </tr>
    <tr><td colspan="2" class="Dotted"><div class="LineDotted"/></td></tr>
    
    <tr>
    <td>Accepted<br/>invitations:</td>
    <td class="Number"><br/><span><?php print count($invites["accepted"]); ?></span></td>
    </tr>
    <tr><td colspan="2" class="Dotted"><div class="LineDotted"/></td></tr>

    <tr>
    <td>Pending<br/>invitations:</td>
    <td class="Number"><br/><span><?php print count($invites["pending"]); ?></span></td>
    </tr>
    
    </tbody>
</table>

<div class="InviteMore">
    <a href="?q=poker/buddies/invite"><img src="sites/all/themes/poker/images/banner_invite_more_friends.jpg"/></a>
    </div>

 </div>



</div>