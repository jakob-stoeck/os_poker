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
<div id="home_promotion_1">
	<div class="panel">
		<div class="previous"></div>
		<div class="list splash">
      <div id="home_promotion_1-banner">
  		</div>
  		<?php $swf = drupal_get_path("theme", "pbpoker"). '/swf/promotion/poker_300x100.swf'; ?>
        <script type="text/javascript">
    	 $(window).ready(function() {
    		 var id = 'home_promotion_1-banner';
    		 var att = {
    			 'wmode': 'transparent',
    			 'width' : 300,
    			 'height' : 100,
    			 'data' : '<?php print $swf?>'
             };
    		 var par = {};
    
    		 swfobject.createSWF(att, par, id);
		 });
		function pb_open_tutorial() {
		      if(typeof tb_show == 'function') {
		        tb_show(undefined, '<?php print url('poker/help', array(
		          'attributes' => array(
        	          'class' => 'thickbox close',
		        ),
              'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE))) ?>', false);
	        }
	        $('#TB_iframeContent').one('load', function(){
	          var iframeDocument = $(this).contents().get(0);
	          var w = iframeDocument.parentWindow || iframeDocument.defaultView;
	          if(typeof w == 'object' && typeof w.$ == 'function') {
	            w.$('.tabs').tabs('select', 3);
	          }	          
          });
		    }
      </script>

		</div>
		<div class="next"></div>
		<div class="clear"></div>
	</div>
</div>



<!--
<div id="news">
	<div class="header block_title_bar block_title_text">
		<p>
			<a href="javascript:void(0);"><img alt="rss" src="<?php print drupal_get_path('module', 'os_poker') . "/images/feed-icon.png"; ?>"></a>
			News
		</p>
	</div>
	<div class="panel">
		<div class="previous fleft">&nbsp;</div>
		<div class="middle fleft">
			<div class="container">
				<?php
					if($news)
					{
						foreach ($news as $n)
						{
							print "<p>{$n} <a href='javascript:void(0);' class='yellow'>" . t("read more") . "</a></p>";
						}
					}
				?>
			</div>
			<div class="bottom">
				<a href="javascript:void(0);" class="yellow"><?php print t("more") ." &gt;&gt;"; ?></a>
			</div>
		</div>
		<div class="next fleft">&nbsp;</div>
		<div class="clear"></div>
	</div>
</div>

-->
