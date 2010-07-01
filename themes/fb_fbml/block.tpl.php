<?php
// $Id: block.tpl.php,v 1.2 2010/03/25 19:23:11 yogadex Exp $
/**
 * @file
 * FBML block template.
 */
?>
<div class="block block-<?php print $block->module; ?>" id="block-<?php print $block->module; ?>-<?php print $block->delta; ?>">
  <h2 class="title"><?php print $block->subject; ?></h2>
  <div class="content"><?php print $block->content; ?></div>
</div>
