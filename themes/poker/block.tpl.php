<?php
// $Id: block.tpl.php,v 1.4 2008/09/14 12:41:20 johnalbin Exp $

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
 * @file block.tpl.php
 *
 * Theme implementation to display a block.
 *
 * Available variables:
 * - $block->subject: Block title.
 * - $block->content: Block content.
 * - $block->module: Module that generated the block.
 * - $block->delta: This is a numeric id connected to each module.
 * - $block->region: The block region embedding the current block.
 * - $classes: A set of CSS classes for the DIV wrapping the block.
     Possible values are: block-MODULE, region-odd, region-even, odd, even,
     region-count-X, and count-X.
 *
 * Helper variables:
 * - $block_zebra: Outputs 'odd' and 'even' dependent on each block region.
 * - $zebra: Same output as $block_zebra but independent of any block region.
 * - $block_id: Counter dependent on each block region.
 * - $id: Same output as $block_id but independent of any block region.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_block()
 */
?>
<div id="block-<?php print $block->module . '-' . $block->delta; ?>" class="<?php print $classes; ?>"><div class="block-inner">

  <?php if ($block->subject): ?>
    <!--<h2 class="title"><?php print $block->subject; ?></h2>-->
  <?php endif; ?>

  <div class="content">
    <?php print $block->content; ?>
  </div>

  <?php print $edit_links; ?>

</div></div> <!-- /block-inner, /block -->
