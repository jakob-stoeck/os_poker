<script type="text/javascript" charset="utf-8">
	var user_logged_in =<?php echo $user->uid==0 ? 'false' : 'true'; ?>;
</script>

<?php if (function_exists('fb_canvas_is_iframe') && fb_canvas_is_iframe()): ?>
<style type="text/css">
	#header .language-bar {
	display: none;
	}
</style>
	<?php endif; ?>

<div id="header">
	<div id="header-inner" class="clear-block">
		<?php if ($logo): ?>
		<div id="logo">
			<a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
				<img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" id="logo-image" />
			</a>
		</div>
		<?php endif; ?>

		<?php if ($header): ?>
		<div id="header-blocks" class="region region-header">
		<?php if ((function_exists('fb_canvas_is_iframe') && !fb_canvas_is_iframe()) || $user->uid!=0):?>
		  <?php print $header; ?>
		<?php endif; ?>
		</div> <!-- /#header-blocks -->
		<?php endif; ?>
		
		<div style="position:absolute;left:210px;top:46px;">
			<fb:like href="poker.playboy.de" layout="button_count" show_faces="false" colorscheme="dark"></fb:like><br />
			<fb:bookmark type="<?php echo $user->fbu ? 'on' : 'off'; ?>-facebook"></fb:bookmark>
		</div>
		<?php if(!isset($user->fbu)): ?>
		<div style="position:absolute; top: 3px; right: 13px;"><?php print $facebook; ?></div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
</div> <!-- /#header-inner, /#header -->
