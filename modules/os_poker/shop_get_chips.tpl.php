<script type="text/javascript">
   function update_val()
{
$('#edit-item-name').val($("input[name='amount']:checked").parent().text().substring(0, $("input[name='amount']:checked").parent().text().lastIndexOf('for ')));
}
</script>

<div class="choose_package fleft">
<div class="block_title_bar block_title_text"><?php print t("1. Choose a Package:"); ?></div>
<div class="fleft chips_icon"></div>
<div class="fleft chips_text">
<span class="chips_title"><?php print t("Chips"); ?></span><br/>
<span class="chips_desc"><?php print t("Get Chips to play Playboy-Poker and buy basic items."); ?></span>
</div>
<div class="clear">
<?php print $form; ?>
</div>
</div>

<div class="choose_payment fleft">
<div class="block_title_bar block_title_text"><?php print t("Choose Payment Option:"); ?></div>
<div class="fleft button_paypal" onClick="javascript: update_val(); $('#chips-paypal-form').submit();">
 <div class="poker_submit medium">
  <div class="pre"> </div>
   <div class="label">Buy with Paypal</div>
 </div>
 <div class="logo_paypal"></div>
</div>

<div class="fleft button_text"><?php print t("Please select the amount of chips you want to buy.<br/>The first time, open https://sandbox.paypal.com/cgi-bin/webscr in a new tab and initialize the session with paypal sandbox using cmirey@persistant.fr / testtest then come back on this page, click on buy and use poker_1259758311_per@persistant.fr / testtest as sandbox paypal account."); ?></div>

</div>