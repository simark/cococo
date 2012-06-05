<?php
	// entrées initiales
	$in_username = hs(gs(isset($_GET['in_username']) ? $_GET['in_username']: ''));
	$add_dn_fu = (strlen($in_username) > 0) ? ' style="display: none;" ' : '';
	$in_amount = hs(gs(isset($_GET['in_amount']) ? $_GET['in_amount'] : ''));
	if (strlen($in_amount == 0)) {
		$in_amount = 1;
	}
?>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first"><?php echo _T('add-debt'); ?></h2>
		<p>
		<?php echo _T('add-debt-desc'); ?>
		</p>
	</div>
</div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content" id="important-thing">
		<div id="fav-users-box-sh">
			<?php echo _T('hide-show-favs'); ?>
		</div>
		<div id="fav-users-box" <?php echo $add_dn_fu; ?>>
			<?php
				$txr = $g_be_um->tx_get_my_favs();
				if (!is_array($txr->content)) {
					exit(5);
				}
				
				if (count($txr->content) > 0) {
					echo '<table style="width: 100%; border-collapse: collapse; border: none; margin: 0px; padding: 0px;"><tbody>';
					echo '<tr>';
					$x = 0;
					foreach ($txr->content as $vo) {
						if ($x % 3 == 0 && $x != 0) {
							echo '</tr><tr>';
						}
						++$x;
						$sf_vo = $vo->get_html_safe_copy();
						$uid = $vo->get_unique_id();
						echo sprintf('<td><div class="fav-user-item" id="fav-user-%s"><img src="res/images/avatars/%s.png" />%s</div></td>',
							$uid, $vo->avatar, $sf_vo->get_full_name());
					}
					for ($i = 0; $i < (3 - ($x % 3)); ++$i) {
						echo '<td></td>';
					}
					echo '</tr></tbody></table>';
				}
			?>
		</div>
		<form id="form-adddebt" action="actions/r_add_debt.php" method="post" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label><?php echo _T('other-user'); ?></label><br />
							<small><?php echo _T(''); ?>nom d'utilisateur</small>
						</td>
						<td class="fi">
							<input type="text" class="text-input init-focus" name="username" value="<?php echo $in_username; ?>" /><button class="button" id="search-user">rechercher</button>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php echo _T(''); ?>montant</label>
						</td>
						<td class="fi">
							<table style="width: 100%; border-collapse: collapse; margin: 0px; padding: 0px;">
								<tr>
									<td style="width: 150px; text-align: left; margin: 0px;">
										<div style="margin-bottom: 5px;"><input type="text" class="text-input amount-input" name="amount" value="<?php echo $in_amount; ?>" />&nbsp;$</div>
									</td>
									<td>
										<div id="amount-slider"></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php echo _T(''); ?>remboursement</label>
						</td>
						<td class="fi">
							<input type="checkbox" value="1" name="is_payback" id="check-is-payback" /><span class="check_behind"><?php echo _T(''); ?>il s'agit d'un remboursement officiel</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php echo _T(''); ?>direction</label>
						</td>
						<td class="fi">
							<input type="radio" name="direction" value="iowethem" checked="checked" />
							<span class="check_behind" id="ad-iowe-txt"><?php echo _T(''); ?>je dois à l'autre</span>&nbsp;
							<input type="radio" name="direction" value="theyoweme" />
							<span class="check_behind" id="ad-theyowe-txt"><?php echo _T(''); ?>l'autre me doit</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php echo _T(''); ?>description</label><br />
							<small><?php echo _T(''); ?>facultatif</small>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="descr" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php echo _T(''); ?>date officielle de la dette</label><br />
							<small><?php echo _T(''); ?>format <code>2011-08-26</code> ou rien</small>
						</td>
						<td class="fi">
							<input type="text" class="text-input datepick" name="date_real" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label></label>
						</td>
						<td>
							<input class="button" type="submit" value="ajouter" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
