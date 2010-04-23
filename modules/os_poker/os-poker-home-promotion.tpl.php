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
  		</div>
  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/shop_300x100.swf'; ?>
        <script type="text/javascript">
    	$(window).ready(function() {
		swfobject.embedSWF('<?php print $swf?>', "home_promotion_2-banner", "300", "100", "9.0.0", undefined, {
	          'clickTag' : 'http://www.bunnystore.de/playboy/?hnr=poker.playboy'
        	}, {
	          'wmode': 'transparent'
        	});
    	});
      </script>
	</div>
	<div class="next"></div>
	<div class="clear"></div>
</div>
</div>


<!--
<div id="home_promotion">
<div class="previous fleft">&nbsp;</div>
<div class="middle fleft">
	<div class="promotion">
	</div>
</div>
<div class="next fleft">&nbsp;</div>
<div class="clear"></div>
</div>
-->
