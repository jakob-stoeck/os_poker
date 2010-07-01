<?php
// $Id: box.tpl.php,v 1.2 2010/03/25 19:23:11 yogadex Exp $
/**
 * @file
 * FBML block template.
 */
?>
<div class="box">
  <?php if ($title) { ?>
    <h2 class="title"><?php print $title; ?></h2>
  <?php } ?>
  <div class="content"><?php print $content; ?></div>
</div>

