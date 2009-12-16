<div id="poker-first-profile">
	<div class="title">
		<?php print "<h1>" . t("Create your Profile") . "</h1>" . t("One last step to discover the fun, excitement and challenge of poker."); ?>
	</div>
	<div class="body">
		<?php print $form; ?>
	</div>

	<div class="find_new_buddies">
	   <p><strong>
   <?php print t("Find new Poker Buddies!"); ?>
	   </strong></p>
	   <div class="userlist">
<?php
   foreach ($userlist as $uid)
{
  $avatar = "sites/default/files/pictures/picture-".$uid.".png";
  if (!file_exists($avatar))
    $avatar = "sites/default/files/pictures/picture-default.png";
  print "<img class='first_profile_userlist_avatar' src='".$avatar."' />";
}
?>
	   </div>   
        </div>

</div>
