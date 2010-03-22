<div id="table_users">
	<div class="header block_title_bar block_title_text">
		<?php print t("At this table"); ?>
	</div>
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
			<div class="userlist">
			</div>
      <div id="list-banner">
  		</div>

  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x250.swf'; ?>
        <script type="text/javascript">
    	 $(window).ready(function() {
		swfobject.embedSWF('<?php print $swf?>', "list-banner", "300", "250", "9.0.0", undefined, {'clickTag' : '<?php print url("poker/pages/tourneyinfo", array('absolute' => TRUE)); ?>'}, {
	          'wmode': 'transparent'
        	});
	 });
      </script>

		</div>
		<div class="next"></div>
		<div class="clear"></div>
	</div>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="250">
				<param name="movie" value="<?php print drupal_get_path("theme", "pbpoker") ?>/swf/promotion/poker_300x250.swf" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="<?php print drupal_get_path("theme", "pbpoker") ?>/swf/promotion/poker_300x250.swf" width="300" height="250">
				<!--<![endif]-->
				<p></p>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
</div>
