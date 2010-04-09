<div id="home_promotion_1">
	<div class="panel">
		<div class="previous"></div>
      <div class="list splash">
        <div id="home_promotion_1-banner">
          <div class="header block_title_bar block_title_text">Freunde einladen</div>
          <?php print theme('image', drupal_get_path('theme', 'pbpoker') .'/images/home_promotion_1.jpg'); ?>
          Je mehr Freunde Du einlädst, umso mehr Chips bekommst Du von uns geschenkt!
          <div id="home_promotion_1-button" class="poker_submit">
            <div class="pre">&nbsp;</div>
            <div class="label">
              <a class="thickbox" href="<?php print url('poker/buddies/invite', array(
                'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE)))?>">Einladen</a>
            </div>
            <div class="user_login_clear"></div>
          </div>
        </div>
      </div>
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>
