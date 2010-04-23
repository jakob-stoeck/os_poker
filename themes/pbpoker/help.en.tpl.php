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
<div id="ContainerContentHelp" class="tabs-window">
	<a href="javascript:void(0);" onclick="javascript:parent.tb_remove();" class="LayerClose">&nbsp;</a>
	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="tabs primary ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
      <li><a href="#help-tutorial">Tutorial</a></li>
			<li><a href="#help-rules">Hold'Em Rules</a></li>
			<li><a href="#help-tips">Tips</a></li>
			<li><a href="#help-pokerhands">Poker Hands</a></li>
			<li><a href="#help-guidelines">Guidelines</a></li>
			<li><a href="#help-security">Security Advice</a></li>
		</ul>
	</div>


	<div id="help-pokerhands" class="helpbox">
		<h4 class="helpbox-title block_title_bar">Poker Hands</h4>
		<dl class="helpbox-list">
			<dt>Royal Flush
				<span class="poker-hand royal-flush" title="Royal Flush">A,K,Q,J,10</span>
			</dt>
			<dd>A, K, Q, J, 10 all of the same suit.</dd>
			<dt>Straight Flush
				<span class="poker-hand straight-flush" title="Straight Flush">4,5,6,7,8</span>
			</dt>
			<dd>Any five card sequence in the same suit (Ex: 4, 5, 6, 7, 8). </dd>
    		<dt>4-Of-a-Kind
				<span class="poker-hand four-of-a-kind" title="4 Of A Kind">Q,Q,Q,Q</span>
			</dt>
			<dd>All four cards of the same index (Ex: Q, Q, Q, Q)</dd>
			<dt>Full House
				<span class="poker-hand full-house" title="Full House">A,A,A,4,4</span>
			</dt>
			<dd>Three of a kind combined with a pair (Ex: A, A, A, 4, 4). In the event of a draw with a Full House, the three of kind index determines the winner. </dd>
			<dt>Flush
				<span class="poker-hand flush" title="Flush">K,D,10,4,2</span>
			</dt>
			<dd>Any five cards of the same suit, but not in sequence. In the event of a draw, the index of the highest card counts. For example, if the first four cards have the same index, the fifth is counted as the highest card. If all five cards are the same, the pot is divided since all suits have the same value.</dd>
			<dt>Straight
				<span class="poker-hand straight" title="Straight">10,J,Q,K,A</span>
			</dt>
			<dd>Five cards in sequence, but not in the same suit. The Straight that ends in the highest card wins. If there is no difference in this regard, a draw is declared and the pot is divided. In a Straight, an ace counts as an ace or 1, thus resulting in the lowest possible Straight (A, 2, 3, 4, 5) and the highest possible Straight (10, J, Q, K, A). </dd>
			<dt>3-Of-a-Kind
				<span class="poker-hand three-of-a-kind" title="3 Of A Kind">K,K,K</span>
			</dt>
			<dd>Three cards of the same index. The highest Three of a Kind wins if two players have Three of a Kinds.</dd>
			<dt>Two Pairs
				<span class="poker-hand two-pairs" title="2 Pairs">A,A,9,9</span>
			</dt>
			<dd>Two separate pairs (e.g. A, A, 9, 9). In this case, the player with the highest pair wins if two or more players have Two Pairs. If the highest pairs are also the same, the second highest counts. If these are also the same, the fifth card counts (known as the kicker). </dd>
			<dt>One Pair
				<span class="poker-hand one-pair" title="1 Pair">A,A</span>
			</dt>
		    <dd>Two cards of the same index. In this case, the player with the highest pair wins if two or more players have a One Pair. </dd>
			<dt>High Card
				<span class="poker-hand high-card" title="High Card">A</span>
			</dt>
			<dd>The highest card wins if no player has Pair or a higher index card.</dd>
		</dl>
	</div>

	<div id="help-rules" class="helpbox">
		<h4 class="helpbox-title block_title_bar">Hold'em Rules</h4>
		<div class="content">
			<p>In Texas Hold’em poker, each player is initially dealt two cards face down (known as hole cards) and five community cards are placed face down in the middle of the table. Hole cards and community cards can be used in any combination with a view to obtaining the best possible five-card hand from the available seven cards. </p>
			<p><em>Dealer button</em>: The dealer button is the position from which the cards are dealt. Each round of poker begins at the dealer button, which shifts one position to the left after each hand. </p>
			<p><em>Small blind</em>: Refers to a small bet that the player immediately following the dealer is required to place at the beginning of the first round. </p>
			<p><em>Big blind</em>: Refers to a large bet that the player immediately following the small blind player is required to place at the beginning of the first round. </p>
			<ul class="helpbox-list">
				<li><strong>First round (hole cards) </strong><br />
  Once each player has been dealt two face-down cards, the player after the big blind player begins the first round, whereby the players can either call, raise, or fold. Each player must place the same chip value in the pot. If a player raises his bet, all other players must either do likewise, pass, or raise the ante. Any player who passes is automatically excluded from the round. </li>
				<li><strong>Second round (flop cards) </strong><br />
  Three cards are placed face down as community cards, which all players can use in combination with their two concealed cards. The remaining players now begin the second round, with the player next to the dealer starting the round. Inasmuch as, unlike in the first round, no bet has been placed, this first player has the option to check. All other players can exercise the check option so long as no bet has been placed. To remain in the game, a player must either call or raise. And of course a player can pass at any time, when it is their turn. </li>
				<li><strong>Third round (turn card) </strong><br />
  An additional community card known as the turn card is placed face down, with the result that the players now only have six cards available for realization of a five card hand. An additional round now begins, in accordance with the same rules as for the second round. </li>
				<li><strong>Fourth round (river card) </strong><br />
				The fifth and final community card, known as the river card, is now placed face down and the final round begins. </li>
  				<li><strong>Showdown</strong><br />
The winner with the best five-card hand is now determined from among the remaining players who have not folded. The number of hole cards a player has used has no bearing on whether they win the round. Also see: Poker hand rankings </li>
			</ul>
		</div>
	</div>

	<div id="help-tips" class="helpbox">
		<h4 class="helpbox-title block_title_bar">Tips</h4>
		<div class="content">
		<ol class="helpbox-list">
			<li><strong>Choose the hands you play wisely</strong><br />
			Patience is the key to success in poker. Poker professionals only look at the flop in 20 to 30 percent of cases. You will (and should!) increase your chances of getting a good hand by waiting until you get one. </li>
			<li><strong>There’s nothing wrong with folding</strong><br />
			Inexperienced players often let themselves be drawn into high bets. In many cases, the chips being played are not commensurate with the anticipated win and the value of your hand. Thus knowing when to fold is one of the keys to successful poker playing. </li>
			<li><strong>Observe your fellow players carefully</strong><br />
			You should always pay very close attention to what your fellow players are doing, particularly in cases where you yourself don’t call, because then you obtain a more objective view of what’s going on. Any information you glean from observing your fellow players is valuable. </li>
			<li><strong>Betting</strong><br />
			You should only bet when you have a good hand. There are various betting techniques that allow you to obtain information about your fellow players’ hands. For example, doing a re-raise allows you to determine whether or not your fellow players’ bets are merely bluffs.</li>
			<li><strong>Bluffing</strong><br />
			Bluffing can potentially be one of the most powerful weapons in the poker player’s arsenal. Used at an opportune moment, bluffing may allow you to win a around; and a failed bluff can induce an opponent to suspect a bluff in a later round and thus induce him to up the ante. However, such risky actions should only be taken in the presence of the right ratio between risk, bets and winnings. </li>
			<li><strong>Change it up!</strong><br />
			If you vary the actions you take at the poker table, it will be harder for your opponents to figure out what kind of hand you actually have. </li>
			<li><strong>Adapt your play to your table position </strong><br />
			You should take advantage of a good position at the poker table. For example, if you’re the dealer (also known as being “on the button”), you can gather information and make predictions regarding your fellow players’ bets, since you place your bets last. </li>
			<li><strong>The poker learning curve never ends </strong><br />
			Since poker rules are easy to learn, even total novices can prevail against highly experienced players. However, it’s also a lot of fun to improve your game based on experience. So the most important poker tip of all is to keep playing.</li>
		</ol>
		</div>
	</div>

	<div id="help-tutorial" class="helpbox"  style="overflow: hidden">
    <p style="text-align: center;">
      <a class="tab-iframe" href="<?php print $tutorial ?>" style="display: none"></a>
      <script language="javascript" type="text/javascript">
        /* Dirty hack to activate tutorial tab in help thickbox */
        function os_poker_show_tutorial_tab() {
          $('#ContainerContentHelp .tabs').tabs('select', '#help-tutorial');
        }
        $(document).ready(function(){
          //Function to add the video iframe
          var href = $('.tab-iframe').attr('href');
          function tutorial_iframe() {
            if(!$('iframe.tutorial').length) {
              $('<iframe class="tutorial" allowTransparency="allowTransparency" width="100%" scrolling="no" height="330" frameborder="no"/>').insertAfter('.tab-iframe').attr('src', href);
            }
          }
          var $tabs = $('#ContainerContentHelp .tabs');
          //Delay to let jQuery UI Tabs initilize itself
          setTimeout(function(){
            //If the selected tab is the tutorial one, add the video iframe
            if($tabs.tabs('option', 'selected') == 0) {
              tutorial_iframe();
            }
          }, 0);
          //When a tab is show, remove the video iframe
          $tabs.bind('tabsshow', function(event, ui){
            $('iframe.tutorial').remove();
            //Add the shown tab is the tutorial, add the the video iframe
            if($(ui.tab).attr('hash') == '#help-tutorial') {
              tutorial_iframe();
            }
          });
        });
      </script>
    </p>
	</div>

	<div id="help-guidelines" class="helpbox">
		<h4 class="helpbox-title block_title_bar">Guidelines</h4>
		<div class="content">
		<p>Fairness and mutual respect at the poker table are of supreme importance to us. We ask that you observe the following rules and regulations so as to ensure that all concerned derive maximum enjoyment from playing: </p>
		<ul class="helpbox-list">
			<li>Spamming is strictly prohibited, as is posting of affiliate codes and/or links from other Web sites. </li>
			<li>Posts are to be devoid of any unlawful content. No slander, insults, tirades, obscenity, or racism or any expression of prejudice or discrimination will be tolerated. Any linked or uploaded files such as pictures, audio files or videos are subject to these same rules and are not to violate copyright.</li>
			<li>Advertising is prohibited.</li>
			<li>The use of online poker bots is prohibited. Only human players are allowed.</li>
			<li>Fair play is an absolute must. Any use of software or cheats to gain an unfair advantage over other players is strictly prohibited.</li>
			<li>Any sale or attempted sale of chips to other players, as well as any purchase of chips from other players, is prohibited. </li>
		</ul>
		<p>
		Playboypoker.de reserves the right to delete any content that violates any of the aforementioned rules and regulations. Any such violation will result in a warning, and in a serious case to immediate deactivation of the relevant user account without prior warning.</p>
		<p>For reporting a breach of the rules, please use the "Report abuse" function, which can be found in every player profile. Simply click on the name or profile image of the player you want to report. There you'll find the "Report abuse" button.</p>
		</div>
	</div>

	<div id="help-security" class="helpbox">
		<h4 class="helpbox-title block_title_bar">Security Advice</h4>
		<div class="content">
		<h5>Don’t give Hackers, Spammers and Scammers a chance</h5>
		<p>We will never ask you for sensitive information via e-mail or in game chat, like</p>
		<ul>
			<li>your log-in email</li>
			<li>your password</li>
			<li>credit card numbers</li>
			<li>Don’t follow links to external sources regarding free chips.</li>
			<li>You should always check the URL in the address bar of your browser when entering personal information</li>
		</ul>
		<h5>Keep your password secure</h5>
		<ul>
			<li>Never share your password with anyone</li>
			<li>Change your password at least every six months</li>
			<li>Never send your password in email, even if the request looks official</li>
			<li>We recommend a password that is at least 8 characters in length. Each character you add to your password increases the protection it provides.</li>
			<li>A strong password contains uppercase and lowercase letters, numbers, and special characters</li>
		</ul>
		<h5>Avoid weak and easy-to-guess passwords</h5>
		<ul>
			<li>No sequences or repeated characters</li>
			<li>Don't use your login name</li>
			<li>No dictionary words in any language</li>
			<li>Don't use only one password for all your accounts</li>
		</ul>
		</div>
	</div>
</div>
