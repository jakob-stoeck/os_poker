<div id="poker-teaser">
  <h1 class="title"><?php print $title;?></h1>
  <div class="subtitle"><?php print $subtitle?></div>
  <?php print($table);?>
  <div class="info"><?php print $info ?></div>
  <div id="poker-teaser-text">
    <?php print $text; ?>
  </div>
  <?php print $girl;?>
  <div id="poker-tutorial">
    <div class="tb-fix-margin">
<a href="javascript:void(0);" onclick="javascript:parent.tb_remove();" class="LayerClose"> </a>
      <script src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" type="text/javascript"></script>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php print $tutorial['size'][0]?>" height="<?php print $tutorial['size'][1]?>">
        <param name="movie" value="<?php print $tutorial['file']?>" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="<?php print $tutorial['file']?>" width="<?php print $tutorial['size'][0]?>" height="<?php print $tutorial['size'][1]?>">
        <!--<![endif]-->
          <p></p>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
    </div>
  </div>
</div>
