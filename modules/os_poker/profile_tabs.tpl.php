<div id="profile-tabs">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="tabs">
		<ul class="tabs primary">
		<li <?php if ($active_tab == "profile" || $active_tab == NULL || $active_tab == "update") { print 'class="active"'; } ?>><?php print l(t("Profile"), "poker/profile"); ?></li>
		<li <?php if ($active_tab == "rewards") { print 'class="active"'; } ?>><?php print l(t("Rewards"), "poker/profile/rewards"); ?></li>
		<li <?php if ($active_tab == "ranking") { print 'class="active"'; } ?>><?php print l(t("Ranking"), "poker/profile/ranking"); ?></li>
		<?php if ($external == FALSE) { ?>
			<li <?php if ($active_tab == "settings") { print 'class="active"'; } ?>><?php print l(t("Settings"), "poker/profile/settings"); ?></li>
		<?php } ?>
		</ul>
	</div>
	<div class="content">
		<?php print $content; ?>
	</div>
</div>