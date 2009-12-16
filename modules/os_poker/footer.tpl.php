<div id="poker-footer">
	<?php 
		print theme("os_poker_languages", FALSE);
	?>
	<div class="footer-links">
		<?php 
			print 	l(t("Terms of service"), "<front>") . " - " .
					l(t("Help"), "<front>") . " - " .
					l(t("Editorial"), "<front>") . " - " .
					l(t("Sources"), "<front>");
		?>
	</div>
</div>