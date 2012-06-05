<?php
if (!$g_be_session->is_user_logged()) { ?>
	<div class="struct struct-body">
		<div class="struct-inner-content">
			<div id="login-box">
				<form action="actions/r_login.php" method="post">
					<table>
						<tbody>
							<tr>
								<td><label><?php TE('username'); ?></label><input type="text" class="text-input init-focus" name="username" /></td>
								<td><label><?php TE('password'); ?></label><input type="password" class="text-input" name="passwd" /></td>
								<td><label></label><input type="submit" class="button" value="<?php TE('go_login'); ?>" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<h2><?php TE('welcome'); ?></h2>
			<p>
			<strong>cococo</strong> est un système de gestion de dettes interpersonnelles qui vous aide à
			vous rappeler qui vous doit combien depuis quand, et vice versa, combien vous devez à qui depuis
			quand. Commencez dès maintenant en quelques étapes&nbsp;:
			</p>
			<p>
			<strong>cococo</strong> is debt management system that helps you track who owes you some money and vice-versa.
			Start tracking your debts in only 3 easy steps:&nbsp;:
			</p>
		</div>
	</div>
	<div class="struct struct-ban-blue-top"></div>
	<div class="struct struct-ban-blue-body">
		<div class="struct-ban-blue-inner-content">
			<p>
			<img src="res/images/easysteps.png" alt="inscrivez-vous, ajoutez vos favoris, archivez vos dettes" />
			</p>
		</div>
	</div>
<?php } else { ?>
	<div class="struct struct-body">
		<div class="struct-inner-content">
			<h2 class="first"><?php echo _T('my-cococo'); ?></h2>
		</div>
</div>
	<?php
		$txr_hist = $g_be_dm->tx_get_my_history();
		if ($txr_hist->err !== CommonManager::INFO_TX_STARTED || is_null($txr_hist->content)) {
			exit(2);
		}
		$conf_vos = array();
		foreach ($txr_hist->content as $vo) {
			if (!$vo->is_confirmed) {
				if (($vo->creator == DebtVO::CREATOR_SRC && $g_be_user->id == $vo->user_dst_id) ||
				($vo->creator == DebtVO::CREATOR_DST && $g_be_user->id == $vo->user_src_id)) {
					array_push($conf_vos, $vo);
				}
			}
		}
		$tot = count($conf_vos);
		if ($tot > 0) {
			$pl = ($tot > 1) ? 's' : '';
			$debts = "dette$pl";
		?>
			<div class="struct struct-ban-orange-top"></div>
			<div class="struct struct-ban-orange-body">
				<div class="struct-ban-orange-inner-content" id="conf">
					<p id="conf-info">
					<strong>Attention</strong>! Vous avez <strong><?php echo "<span id=\"x-debts-info\">$tot $debts</span>"; ?></strong> à confirmer&nbsp;:
					</p>
					<?php
						foreach ($conf_vos as $vo) {
							$sf_vo = $vo->get_html_safe_copy();
							$inter = time() - $vo->date_creation->getTimestamp();
							if ($inter < 0) {
								$inter = 0;
							}
							$iw = inter_words_daily($inter);
							$amt = format_amount_light($vo->amount);
							$span_user = '<span class="user" id="zuser-un-%s-id-%d">%s</span>';
							if ($vo->user_dst_id == $g_be_user->id) {
								$oth_fn = $vo->user_src_full_name;
								$cla = "p-owe p-they-owe";
								if ($vo->is_payback) {
									$t = sprintf("j'ai remboursé %s à $span_user", $amt, $vo->username_src, $vo->user_src_id, $oth_fn);
								} else {
									$t = sprintf("$span_user me doit %s", $vo->username_src, $vo->user_src_id, $oth_fn, $amt);
								}
							} else {
								$oth_fn = $vo->user_dst_full_name;
								$cla = "p-owe p-i-owe";
								if ($vo->is_payback) {
									$t = sprintf("$span_user m'a remboursé %s", $vo->username_dst, $vo->user_dst_id, $oth_fn, $amt);
								} else {
									$t = sprintf("je dois %s à $span_user", $amt, $vo->username_dst, $vo->user_dst_id, $oth_fn);
								}
							}
							$txt = sprintf('%s (créé %s)', $t, $iw);
							printf('<p class="%s"><button id="conf-%d" class="confirm-button">confirmer</button><button id="inv-%d" class="invalidate-button">infirmer</button>%s</p>',
								$cla, $vo->id, $vo->id, $txt);
						}
					?>
				</div>
			</div>
		<?php }
	?>
	<div class="struct struct-body struct-body-spacer"></div>
	<div class="struct struct-body">
		<div class="struct-inner-content">
			<div id="select-tab-box">
				<button class="button" id="sel-tab-total"><?php echo _T('totals'); ?></button>
				<button class="button" id="sel-tab-summary"><?php echo _T('summary'); ?></button>
				<button class="button" id="sel-tab-history"><?php echo _T('history'); ?></button>
			</div>
		</div>
	</div>
	<div class="struct struct-ban-blue-top"></div>
	<div class="struct struct-ban-blue-body">
		<div class="struct-ban-blue-inner-content" id="cococo-tabs">
			<?php
				require_once("contents/cococo_tabs.php");
				
				echo content_cococo_tabs();
			?>
		</div>
	</div>
<?php }
?>
