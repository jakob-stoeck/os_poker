<div class="message">
  <?php if ($message):?>
    <div class="type fleft">
      <?php print $symbol; ?>
    </div>
    <div class="body fleft">
      <?php print $text; ?>
      <?php if ($links):?>
      | <?php print $links ?>
      <?php endif;?>
    </div>
    <div class="sender fleft">
      <?php print $picture; ?>
    </div>
    <div class="clear"></div>
  <?php endif; ?>
</div>