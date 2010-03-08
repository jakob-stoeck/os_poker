<div id="home_promotion_1">
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
      <div id="home_promotion_1-banner">
  		</div>
  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x100.swf'; ?>
        <script type="text/javascript">
    	 $(window).ready(function() {
    		 var id = 'home_promotion_1-banner';
    		 var att = {
    			 'wmode': 'transparent',
    			 'width' : 300,
    			 'height' : 100,
    			 'data' : '<?php print $swf?>'
             };
    		 var par = {};
    
    		 swfobject.createSWF(att, par, id);
		 });
      </script>

		</div>
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>



<!--
<div id="news">
	<div class="header block_title_bar block_title_text">
		<p>
			<a href="javascript:void(0);"><img alt="rss" src="<?php print drupal_get_path('module', 'os_poker') . "/images/feed-icon.png"; ?>"></a>
			News
		</p>
	</div>
	<div class="panel">
		<div class="previous fleft">&nbsp;</div>
		<div class="middle fleft">
			<div class="container">
				<?php
					if($news)
					{
						foreach ($news as $n)
						{
							print "<p>{$n} <a href='javascript:void(0);' class='yellow'>" . t("read more") . "</a></p>";
						}
					}
				?>
			</div>
			<div class="bottom">
				<a href="javascript:void(0);" class="yellow"><?php print t("more") ." &gt;&gt;"; ?></a>
			</div>
		</div>
		<div class="next fleft">&nbsp;</div>
		<div class="clear"></div>
	</div>
</div>

-->
