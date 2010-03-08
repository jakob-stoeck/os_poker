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

  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x250.swf'; ?>
        <script type="text/javascript">
    	 $(window).ready(function() {
    		 var id = 'list-banner';
    		 var att = {
    			 'wmode': 'transparent',
    			 'width' : 300,
    			 'height' : 250,
    			 'data' : '<?php print $swf?>'
             };
    		 var par = {
              'clickTag' : '<?php print url("poker/pages/tourneyinfo", array('absolute' => TRUE)); ?>'
             };
    
    		 swfobject.createSWF(att, par, id);
    		 });
      </script>

		</div>		
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>
