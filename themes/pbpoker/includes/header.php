
<style type="text/css">
	#header .language-bar {
	display: none;
	}
</style>

<script type="text/javascript" charset="utf-8">
	var user_logged_in =<?php echo $user->uid==0 ? 'false' : 'true'; ?>;
	function os_poker_getVersion(a, b) {
		var t = navigator.userAgent.split(a)[1];
		return (t) ? t.split(b)[0] : false;
	}
	function os_poker_isSafari() {
		return (document.createCDATASection && document.createElementNS) ? os_poker_getVersion('AppleWebKit/', '(') : false;
	}
	
	function os_poker_is_in_iframe() {
		try { 
			var src = window.parent.src;
			return true; 
		} catch (e){
			return false; 
		}
	}
	window.fbAsyncInit = function() {
		if (os_poker_is_in_iframe()) { 
		  FB.Canvas.setAutoResize();
		}
	}
	if (os_poker_isSafari() && os_poker_is_in_iframe()) {
		alert('Sorry this application is not working in Safari. We are working on it. Please use another browser');
	}		
	
</script>



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
		
		<?php print $header; ?>
		<script>if (!os_poker_is_in_iframe()) { $("#header .language-bar").css("display","");}</script>
		</div> <!-- /#header-blocks -->
		<?php endif; ?>
		
		<?php global $user; if($user->uid): ?>
		<div style="position:absolute;left:210px;top:63px;"><fb:bookmark type="<?php echo $user->fbu ? 'on' : 'off'; ?>-facebook"></fb:bookmark></div>
		<div style="position:absolute;left:392px;top:63px;"><fb:like href="poker.playboy.de" layout="button_count" show_faces="false" colorscheme="dark"></fb:like></div>
		<?php elseif (drupal_is_front_page()): ?>
		<div style="position:absolute;left:210px;top:63px;"><?php print $facebook; ?></div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
</div> <!-- /#header-inner, /#header -->
