<div id="poker-footer">
	<?php 
		print theme("os_poker_languages", FALSE);
	?>
	<div class="footer-links">
		<?php 
			print 	l(t("Terms of service"), 'poker/tos', array(
          'attributes' => array(
            'class' => 'thickbox',
          ),
          'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE),
          'fragment' => 'help-terms-of-service',
        )) . " - " .
				l(t("Help"), "poker/help/&height=442&width=603&keepThis=true&TB_iframe=true", array('attributes' => array('class' => 'thickbox'))) . " - " .
				l(t("Editorial"), "node/7") . " - " .
				l(t("Sources"), "<front>");
		?>
	</div>
</div>