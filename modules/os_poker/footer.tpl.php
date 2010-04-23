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
				l(t("Help"), "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-rules')) . " - " .
				l(t("Imprint"), 'poker/tos', array(
          'attributes' => array(
            'class' => 'thickbox',
          ),
          'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE),
        )) . " - " .
				l(t("Sources"), "<front>");
		?>
	</div>
</div>
