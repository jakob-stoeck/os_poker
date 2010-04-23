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
<div id="table_users">
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
      <div class="header block_title_bar block_title_text">
        <?php print t("At this table"); ?>
      </div>
			<div class="inner-list">
			</div>
      <div id="list-banner">
  		</div>

  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x250.swf?1'; ?>
        <script type="text/javascript">
    	 $(window).ready(function() {
		swfobject.embedSWF('<?php print $swf?>', "list-banner", "300", "250", "9.0.0", undefined, {'clickTag' : 'http://www.playboy.de/cyberclub'}, {
	          'wmode': 'transparent'
        	});
	 });
      </script>

		</div>
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>
