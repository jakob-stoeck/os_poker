<div id="table_users">
	<div class="header block_title_bar block_title_text">
		<?php print t("At this table"); ?>
	</div>
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
			<div class="inner-list">
			</div>
      <div id="list-banner">
  		</div>
<a href="<?php print url("poker/pages/tourneyinfo") ?>">
  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x250.swf'; ?>
			<script type="text/javascript">
        swfobject.embedSWF('<?php print $swf?>', "list-banner", "300", "250", "9.0.0", undefined, {'clickTag' : '<?php print url("poker/pages/tourneyinfo", array('absolute' => TRUE)); ?>'}, {
          'wmode': 'transparent'
        });
      </script>
</a>
		</div>		
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>
