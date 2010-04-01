<div id="poker-footer">
	<?php
		print theme("os_poker_languages", FALSE);
	?>
	<div class="footer-links">
		<a href="#">F.A.Q.</a> -
		<?php print l(t("Terms and Conditions"), 'poker/tos', array('attributes' => array('class' => 'thickbox',),
			'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE),
			'fragment' => 'help-terms-of-service',
		)); ?>
		-
		<?php print l(t("Imprint"), 'poker/tos', array('attributes' => array('class' => 'thickbox',), 'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE), )); ?>
		-
		<? print l(t("Privacy Policy"), 'poker/privacy', array('attributes' => array('class' => 'thickbox',), 'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE), )); ?>
		-
		<?php print l(t("Support"), "poker/help/&height=442&width=603&keepThis=true&TB_iframe=true", array('attributes' => array('class' => 'thickbox'))); ?>
		-
		<?php print l(t("Game Rules"), "poker/help/&height=442&width=603&keepThis=true&TB_iframe=true#help-rules", array('attributes' => array('class' => 'thickbox'))); ?>
		<?php //print l(t("Sources"), "<front>"); ?>
	</div>
</div>
