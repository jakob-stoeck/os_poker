<?php
// $Id: box.tpl.php,v 1.2 2008/09/14 11:56:34 johnalbin Exp $

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
 * @file box.tpl.php
 *
 * Theme implementation to display a box.
 *
 * Available variables:
 * - $title: Box title.
 * - $content: Box content.
 *
 * @see template_preprocess()
 */
?>
<div class="box"><div class="box-inner">

  <?php if ($title): ?>
    <h2 class="title"><?php print $title; ?></h2>
  <?php endif; ?>

  <div class="content">
    <?php print $content; ?>
  </div>

</div></div> <!-- /box-inner, /box -->
