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
<div id="user_login">
	<div class="user_login_row">
		<div class="user_login_line">
			<p class="small_white_text">
				<?php print $f_remember_me ?>
			</p>
		</div>
		<div class="user_login_line">
			<?php print $f_name; ?>
		</div>
	</div>
	<div class="user_login_row">
		<div class="user_login_line">
			<p class="small_white_text">
				<?php  print $f_links; ?>
			</p>
		</div>
		<div class="user_login_line">
			<?php print $f_pass; ?>
		</div>
	</div>
	<div class="user_login_row">
		<div class="user_login_line">
			&nbsp;
		</div>
		<div class="user_login_line">
			<?php print $rendered; ?>
		</div>
	</div>
	<div class="user_login_clear"></div>
</div>
