<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
<div class="tabs">
	<ul class="tabs primary">
		<li <?php if ($action == NULL) { print 'class="active"'; } ?>><a href="?q=poker/buddies" ><?php print t("Buddies"); ?></a></li>
		<li <?php if ($action == "search") { print 'class="active"'; } ?>><a href="?q=poker/buddies/search"><?php print t("Search"); ?></a></li>
		<li <?php if ($action == "invite" || $action == "invitedlist") { print 'class="active"'; } ?>><a href="?q=poker/buddies/invite"><?php print t("Invite Friends"); ?></a></li>
	</ul>
</div>
