<?php
//
//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
?>
<div id="home_promotion_2">
  <div class="panel">
    <div class="previous"></div>
      <div class="list splash">
        <div id="home_promotion_2-banner">
          <div class="header block_title_bar block_title_text">Video Tutorial</div>
          <?php print theme('image', drupal_get_path('theme', 'pbpoker') .'/images/home_promotion_2.jpg'); ?>
          <p>Noch nie gepokert? Wir zeigen Dir in wenigen Schritten wie es geht!</p>
          <div id="home_promotion_2-button" class="poker_submit">
            <div class="pre">&nbsp;</div>
            <div class="label">
              <a class="thickbox" href="<?php print url('poker/help', array(
                'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE))) ?>">zum Tutorial</a>
            </div>
            <div class="user_login_clear"></div>
          </div>
        </div>
    </div>
    <div class="next"></div>
    <div class="clear"></div>
  </div>
</div>
