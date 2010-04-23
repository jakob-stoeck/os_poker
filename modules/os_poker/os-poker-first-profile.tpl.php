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
<div id="outter-poker-first-profile">&nbsp;
</div>

<div id="poker-first-profile">
  <a class="LayerClose" onclick="javascript:os_poker_submit(this, 'os-poker-first-profile-form');" href="javascript:void(0);">&nbsp;</a>
	<div class="title">
		<h1><?php print $title ?></h1> <?php print $subtitle ?>
	</div>
	<div class="body">
		<?php print $form; ?>
	</div>
	<div class="find_new_buddies">
		<p>
			<strong><?php print $find_new_buddies ?></strong>
		</p>
		<div class="userlist">
			<?php print $avatars ?>
		</div>   
	</div>
  <div class="footer">
		<?php print $footer ?>
	</div>
</div>

<script type="text/javascript">
   $("#messages").show();
   $(".error").show();
   $(".messages").addClass("first_profile_message");
</script>
