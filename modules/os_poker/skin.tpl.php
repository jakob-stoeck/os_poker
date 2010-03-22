<?php
$params = "&height=442&width=603&keepThis=true&TB_iframe=true";
?>


<?php print l("Self Profile", "poker/profile/&" . $params, array('attributes' => array('class' => 'thickbox'))); ?>
<br/>
<?php print l("Medium Profile", "poker/profile/medium/100&" . $params, array('attributes' => array('class' => 'thickbox'))); ?>
<br/>
<?php
print drupal_get_form('os_poker_add_buddy_button', 100);
?>

<!--
<div id="TB_window" style="margin-left: -177px; width: 355px; margin-top: -94px; display: block;"><div style="width: 325px; height: 144px;" class="TB_modal messages-popup" id="TB_ajaxContent"><a href="#" class="close">close</a><div class="content"><div class="messages status os-poker-processed">
Your <em>buddy</em> request has been sent to <span class="thmr_call" id="thmr_10">
  HOHOHOILOVEWHISKY@drunkencaptain.com (not verified)</span>

.</div></div><div class="poker_submit close"><div class="pre"> </div><div class="label" style="width: 60px; text-align: center;">close</div><div class="user_login_clear"/></div></div></div>
	  -->

<div id="messagebox">
	<div style="width: 282px; top: 86px; left: 3px; position: fixed; display: block;" class="user_relationships_ui_popup_form" id="user_relationships_popup_form"><form class="confirmation" id="user-relationships-ui-pending-requested" method="post" accept-charset="UTF-8" action="/drupal6/?q=de/user/283/relationships/requested/101/disapprove&amp;destination=poker/messagebox&amp;ajax=1">
<div>Are you sure you want to disapprove the <em>buddy</em> relationship request from <a class="thickbox" href="/drupal6/?q=de/poker/profile/profile/289&amp;height=442&amp;width=603&amp;TB_iframe=true">player290</a>?<input type="hidden" value="1" id="edit-user-relationships-approve-confirm" name="user_relationships_approve_confirm"/>
<div class="container-inline"><div class="poker_submit form-submit form-submit" id="edit-submit">
<div class="pre"> </div>
<div class="label">
<input type="submit" value="Ja" name="op"/>
</div>
<div class="user_login_clear"/>
</div><div id="user_relationships_popup_form_saving"><p class="user_relationships_popup_form_saving">Saving...</p></div><a href="/drupal6/?q=de/user/283/relationships/requests">Nein</a></div><input type="hidden" value="form-9e728258cf65260073e8ba2c8a5b4bad" id="form-9e728258cf65260073e8ba2c8a5b4bad" name="form_build_id"/>
<input type="hidden" value="34ef5ea0e2831bcc8fe1e8562a89315f" id="edit-user-relationships-ui-pending-requested-form-token" name="form_token"/>
<input type="hidden" value="user_relationships_ui_pending_requested" id="edit-user-relationships-ui-pending-requested" name="form_id"/>

</div></form>
</div>
	<a href="javascript:void(0);" onclick="javascript:parent.tb_remove();" class="LayerClose"> </a>
	<div class="block_title_bar block_title_text">Nachrichten</div>
	<div id="message-list">
		<div class="inner-item-list">
	<div class="message">
      <div class="type fleft">
      <img height="50" width="50" title="" alt="" src="/drupal6/sites/all/modules/os_poker/images/msg_buddy_request.jpg"/>    </div>
    <div class="body fleft">
      player290 möchte Dein Buddy werden      <br/>
      vor 40 years 10 weeks            | <a class="user_relationships_popup_link" href="/drupal6/?q=de/user/283/relationships/requested/101/approve&amp;destination=poker/messagebox">Annehmen</a> / <a class="user_relationships_popup_link" href="/drupal6/?q=de/user/283/relationships/requested/101/disapprove&amp;destination=poker/messagebox">Ablehnen</a>          </div>
    <div class="sender fleft">
          </div>
    <div class="clear"/>
  </div><div class="message">
      <div class="type fleft">
      <img height="47" width="47" title="" alt="" src="/drupal6/sites/default/files/poker_rewards/reward1.gif"/>    </div>
    <div class="body fleft">
      You just won reward Newcomer : Player sits down at the table for the first time      <br/>
      vor 40 years 10 weeks          </div>
    <div class="sender fleft">
      <img height="73" width="80" title="" alt="" src="/drupal6/sites/default/files/pictures/picture-283.jpg"/>    </div>
    <div class="clear"/>
  </div></div>
<div class="ajax-pager">
	</div>
	</div>
</div>
