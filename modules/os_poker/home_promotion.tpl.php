<div id="home_promotion_2">
<div class="panel">
	<div class="previous"></div>
	<div class="list splash">
      <div id="home_promotion_2-banner">
  		</div>
  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/shop_300x100.swf'; ?>
        <script type="text/javascript">
    	$(window).ready(function() {
    		 var id = 'home_promotion_2-banner';
    		 var att = {
    			 'wmode': 'transparent',
	    		 'width' : 300,
		    	 'height' : 100,
			     'data' : '<?php print $swf?>'
             };
	    	 var par = {
              'clickTag' : 'http://www.bunnystore.de/playboy/?hnr=poker.playboy'
             };

	    	 swfobject.createSWF(att, par, id);
    	});
      </script>
	</div>
	<div class="next"></div>
	<div class="clear"></div>
</div>
</div>


<!--
<div id="home_promotion">
<div class="previous fleft">&nbsp;</div>
<div class="middle fleft">
	<div class="promotion">
	</div>
</div>
<div class="next fleft">&nbsp;</div>
<div class="clear"></div>
</div>
-->
