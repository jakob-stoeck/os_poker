<div id="home_promotion_1">
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="100">
				<param name="movie" value="<?php print drupal_get_path("theme", "pbpoker") ?>/swf/promotion/poker_300x100.swf" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="<?php print drupal_get_path("theme", "pbpoker") ?>/swf/promotion/poker_300x100.swf" width="300" height="100">
				<!--<![endif]-->
				<p></p>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
		<script language="JavaScript" type="text/javascript">
		  function pb_open_tutorial() {
		    if(typeof tb_show == 'function') {
		      tb_show(undefined, '<?php print url('poker/help', array(
		        'attributes' => array(
              'class' => 'thickbox close',
            ),
            'fragment' => 'help-tutorial',
            'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE))) ?>', false);
	      }
		  }
		</script>
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
