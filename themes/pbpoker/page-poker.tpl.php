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
  <!--[if lt IE 7]>
    <script type="text/javascript">window.location = '<?php print url('<front>', array('absolute' => TRUE));?>'</script>
  <![endif]-->
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <?php print $analytic_scripts; ?>
</head>

<body class="iframe <?php print $body_classes; ?>">
  <div id="page">
	<div id="page-inner">

    <div id="main" <?php if ($logged_in) { print "class=\"logged_in\""; } ?> >
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
				
				<?php if (($logged_in || !$is_front) && $messages) : ?>
				<div id="messages">
					<?php print $messages; ?>
				</div>
				<?php endif; ?>

			<?php if (!$is_front) : ?>
			
				<?php if ($title || $tabs || $help): ?>
					<div id="content-header">
						<?php if ($title): ?>
            <div class="title-wrapper">
							<h1 class="title"><?php print $title; ?></h1>
              <a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
            </div>
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
						<?php print $middle_content_right; ?>
						<?php if (!$logged_in) : ?>
							<?php if ($messages || $signup_terms) : ?>
								<div id="messages">
									<?php if ($messages) : ?>
										<?php print $messages; ?>
									<?php elseif ($signup_terms) : ?>
										<?php print $signup_terms; ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
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

  <?php print $footer_scripts; ?>
  <?php print $closure; ?>
</body>
</html>
