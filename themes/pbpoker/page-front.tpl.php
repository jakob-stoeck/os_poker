<?php
// $Id: page.tpl.php,v 1.14.2.6 2009/02/13 16:28:33 johnalbin Exp $

//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
//
//    All Drupal code is Copyright 2001 - 2009 by the original authors.
//
//    This program is distributed in the hope that it will be useful, but
//    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
//    or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License
//    for more details.
//    
//    You should have received a copy of the GNU General Public License
//    along with this program as the file LICENSE.txt; if not, please see
//    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
/**
 * @file page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *   themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *   indicating the current layout (multiple columns, single column), the current
 *   path, whether the user is logged in, and so on.
 * - $body_classes_array: An array of the body classes. This is easier to
 *   manipulate then the string in $body_classes.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing primary navigation links for the
 *   site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links for
 *   the site, if they have been configured.
 *
 * Page content (in order of occurrance in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 *
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the view
 *   and edit tabs when displaying a node).
 *
 * - $content: The main content of the current Drupal page.
 *
 * - $right: The HTML for the right sidebar.
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print css_using_cdn($styles); ?>
  <?php print $special_scripts; ?>
  <?php print $analytic_scripts; ?>
</head>

<body class="<?php print $body_classes; ?>">
  <div id="page">
	<div id="page-inner">

        <?php
        require_once('sites/all/themes/pbpoker/includes/header.php');
        ?>
	<?php if ($navbar): ?>
	<div id="navbar">
		<div id="navbar-inner" class="clear-block region region-navbar">
			<a name="navigation" id="navigation"></a>

			<?php if ($navbar): ?>
				  <?php print $navbar; ?>
			<?php endif; ?>

		</div>
	</div> <!-- /#navbar-inner, /#navbar -->
	<?php endif; ?>
    <div id="main" <?php if ($logged_in) { print "class=\"logged_in\""; } ?> >
		<div id="tourney-notify-template">
	 	<?php print(t("The tournament, to which you have signed up, has begun. Go to the Table <span></span>. Good luck!")); ?>
		</div>
		<div id="main-inner" class="clear-block<?php if ($navbar) { print ' with-navbar'; } ?>">

			<div id="content">
				<div id="content-inner">

				<?php if ($mission): ?>
					<div id="mission"><?php print $mission; ?></div>
				<?php endif; ?>

				<?php if ($top_content): ?>
					<div id="top-content" class="region region-top-content">
					<?php print $top_content; ?>
					</div> <!-- /#content-top -->
				<?php endif; ?>

        <noscript>
          <div class="messages error">
            <p><?php print t('Attention to be able to play Playboy Poker you need to enable JavaScript!') ?></p>
            <p><?php print t('Internet Explorer: Go up in the browser menu: "Tools" then "Internet Options" - "Security". Set the security level for "Internet" to the default "Medium".') ?></p>
            <p><?php print t('How it works on in Firefox: Go up in the browser menu: "Tools," then to "Settings" - "content" - "Enable Javascript" (must be ticked).') ?></p>
          </div>
        </noscript>
        <!--[if lt IE 7]>
          <div class="messages error">
            <p>Du verwendest eine veraltete Version des Internet Explorers. Dieser Browser wird von Playboy Poker nicht unterstützt. Um Playboy Poker nutzen zu können aktualisiere dein System bitte auf einen modernen Browser, wie z.B.:</p>
            <p>Internet Explorer 8, Safari 4, Firefox 3.6, Google Chrome</p>
          </div>
        <![endif]-->

				<?php if ($messages) : ?>
				<div id="messages">
					<?php print $messages; ?>
				</div>
				<?php endif; ?>

			<?php if (!$is_front) : ?>

				<?php if ($title || $tabs || $help): ?>
					<div id="content-header">
						<?php if ($title): ?>
							<h1 class="title"><?php print $title; ?></h1>
						<?php endif; ?>
						<?php if ($tabs): ?>
							<div class="tabs">
								<?php print $tabs; ?>
							</div>
						<?php endif; ?>
						<?php print $help; ?>
					</div> <!-- /#content-header -->
				<?php endif; ?>

				<div id="content-area">
					<?php print $content; ?>
				</div>

				<?php if ($feed_icons): ?>
				<div class="feed-icons"><?php print $feed_icons; ?></div>
				<?php endif; ?>

			<?php else : ?>

				<?php if ($middle_content_left || $middle_content_right || (!$logged_in && $messages)) : ?>
				<div id="middle-content">
					<?php if ($middle_content_left) : ?>
					<div id="middle-content-left<?php if (!empty($pokerview)) print "-{$pokerview}"; ?>">
						<?php print $middle_content_left; ?>
					</div>
					<?php endif; ?>
					<?php if ($pokerview != "table" && ($middle_content_right || (!$logged_in && $messages))) : ?>
					<div id="middle-content-right">
            <?php if(!$logged_in) :?>
              <div id="registration-window">
              <a class="close closebutton">X</a>
            <?php endif; ?>
              <?php print $middle_content_right; ?>
              <?php if (!$logged_in) : ?>
                <?php if ($signup_terms) : ?>
                  <div id="messages">
                    <?php print $signup_terms; ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            <?php if(!$logged_in) :?>
              </div>
            <?php endif; ?>
					</div>
					<div class="clear"></div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if ($bottom_content): ?>
				<div id="content-bottom" class="region region-content_bottom">
				<?php print $bottom_content; ?>

				</div> <!-- /#content-bottom -->
				<?php endif; ?>

			<?php endif; ?>

			</div></div> <!-- /#content-inner, /#content -->
		</div>
	</div> <!-- /#main-inner, /#main -->

	<!-- seo text -->
  <?php if (!$logged_in): ?>
	<div id="seo">
	<h2><?php print t("Play online poker at Playboy Poker"); ?></h2>
		<p><strong><?php print t("If you are always playing online poker, or want to learn it, then Playboy Poker is the place for you. We not only offer exciting poker tournaments with hot prices, but also all information about the poker rules and poker strategies to be successful at the poker table."); ?> </strong></p>
		<p><strong><?php print t("On our free poker site you will perhaps become the next star of the poker world and win a date with one of our Playmates. You play on our poker tables the most popular poker variant Texas Hold'em No Limit."); ?></strong></p>
	</div>
  <?php endif; ?>
  <!-- end seo -->

    <?php if ($footer || $footer_message): ?>
      <div id="footer"><div id="footer-inner" class="region region-footer">
        <?php if ($footer_message): ?>
          <div id="footer-message"><?php print $footer_message; ?></div>
        <?php endif; ?>

        <?php print $footer; ?>

      </div></div> <!-- /#footer-inner, /#footer -->
    <?php endif; ?>

	<?php if ($left): ?>
		<div id="sidebar-left"><div id="sidebar-left-inner" class="region region-left">
			<?php print $left; ?>
		</div></div> <!-- /#sidebar-left-inner, /#sidebar-left -->
	<?php endif; ?>

	<?php if ($right): ?>
		<div id="sidebar-right"><div id="sidebar-right-inner" class="region region-right">
			<?php print $right; ?>
		</div></div> <!-- /#sidebar-right-inner, /#sidebar-right -->
	<?php endif; ?>

  </div></div> <!-- /#page-inner, /#page -->

  <?php if ($closure_region): ?>
    <div id="closure-blocks" class="region region-closure"><?php print $closure_region; ?></div>
  <?php endif; ?>

  <?php print $closure; ?>
   
  <?php print $scripts; ?>
  <?php print $footer_scripts; ?>
    <div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div>
<script>
</script>
 
  <script type="text/javascript">
  
  function fb_extended_login()  {
	  return ;
		FB.login(function(response) {
		  	  if (response.session) {
		  	    if (response.perms) {
		  	      // user is logged in and granted some permissions.
		  	      // perms is a comma separated list of granted permissions
		  		      
		  	    } else {
		  	    	
		  	      // user is logged in, but did not grant any permissions
		  	    }
		  	  } else {
		  		  //alert("user is not logged in");
		  	    // user is not logged in
		  	  }
		  	}, {perms:'email,read_stream,publish_stream,offline_access'});
  }
      
  <?php if (fb_canvas_is_iframe()):?>
    
    
  
    FB_RequireFeatures(["CanvasUtil"], function(){ FB.XdComm.Server.init('sites/all/modules/fb/fb_receiver.html'); FB.CanvasClient.startTimerToSizeToContent(); });

        
  
    <?php endif?>
    </script>
</body>
</html>
