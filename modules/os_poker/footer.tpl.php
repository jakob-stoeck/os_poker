<div id="poker-footer">
	<?php 
		print theme("os_poker_languages", FALSE);
	?>
	<div class="footer-links">
		<?php 
			print 	l(t("Terms of service"), "node/6") . " - " .
					l(t("Help"), "<front>") . " - " .
					l(t("Editorial"), "node/7") . " - " .
					l(t("Sources"), "<front>");
		?>
	</div>
</div>