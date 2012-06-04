<?php
	function content_cococo_tabs() {
		global $g_be_dm, $g_be_user;
		$RET = "";
		
		// totaux
		$RET .= '<div id="tab-total" style="display: block;">';
		$txr = $g_be_dm->tx_get_my_summary();
		if ($txr->err !== CommonManager::INFO_TX_STARTED || is_null($txr->content)) {
			exit(1);
		}
		$totals = $g_be_dm->get_totals_from_summary($txr->content);
		$iowe = snbsp(format_amount_light($totals->i_owe_them));
		$theyowe = snbsp(format_amount_light($totals->they_owe_me));
		$RET .= <<<LOL
			<table>
				<tbody>
					<tr>
						<td><img src="res/images/owe_up_arrow.png" /><span class="i-owe">je dois <strong>$iowe</strong></span></td>
						<td><img src="res/images/owe_down_arrow.png" /><span class="they-owe">on me doit <strong>$theyowe</strong></span></td>
					</tr>
				</tbody>
			</table>
			</div>
LOL;
			
		// sommaire
		$RET .= '<div id="tab-summary" style="display: none;">';
		if (count($txr->content) == 0) {
			$RET .= '<p class="noth">&empty;</p>';
		} else {
			foreach ($txr->content as $vo) {
				$sf_vo = $vo->get_html_safe_copy();
				$amount = snbsp(format_amount_light($vo->amount));
				$am = $vo->amount;
				$full_name = $sf_vo->user->get_full_name();
				$uid = $vo->user->get_unique_id();
				switch ($vo->direction) {
					case DebtSummaryVO::DEBT_DIR_I_OWE_THEM:
					$cla = "p-i-owe";
					$txt = "je dois <strong>$amount</strong> à <span class=\"user\" id=\"zuser-$uid\">$full_name</span>";
					break;
					
					case DebtSummaryVO::DEBT_DIR_THEY_OWE_ME:
					$cla = "p-they-owe";
					$txt = "<span class=\"user\" id=\"zuser-$uid\">$full_name</span> me doit <strong>$amount</strong>";
					break;
				}
				$RET .= "\n<p class=\"$cla\">$txt</p>";
			}
		}
		$RET .= '</div>';
		
		// historique
		$RET .= '<div id="tab-history" style="display: none;">';
		$txr_hist = $g_be_dm->tx_get_my_history();
		if (count($txr_hist->content) == 0) {
			$RET .= '<p class="noth">&empty;</p>';
		} else {
			$RET .= <<<LOL
				<table id="leg">
					<tbody>
						<tr>
							<td>nom en <strong>gras</strong> : utilisateur créateur de l'entrée</td>
							<td><img src="res/images/nc.png" alt="" /> dette non confirmée</td>
							<td><img src="res/images/owe_up_arrow_mini_blue.png" alt="" /> <img src="res/images/owe_down_arrow_mini_blue.png" alt="" /> remboursement</td>
						</tr>
					</tbody>
				</table>
				<table class="dt">
					<thead>
						<th class="vl">autre</th>
						<th class="vl">direction</th>
						<th class="vl">montant</th>
						<th class="vl">description</th>
						<th class="vl">date</th>
						<th>création</th>
					</thead>
					<tbody>
LOL;
			foreach ($txr_hist->content as $vo) {
				$sf_vo = $vo->get_html_safe_copy();
				$crea = false;
				$pb = ($vo->is_payback) ? "-pb" : "";
				if ($vo->user_dst_id == $g_be_user->id) {
					$oth_fn = $vo->user_src_full_name;
					$uid = sprintf('un-%s-id-%d', $vo->username_src, $vo->user_src_id);
					$crea = ($vo->creator == DebtVO::CREATOR_SRC);
					$dir_cla = "hist-dir-they-owe$pb hist-dir";
					if (!$vo->is_payback) {
						$dir_txt = "me doit";
					} else {
						$dir_txt = "j'ai remboursé";
					}
				} else {
					$oth_fn = $vo->user_dst_full_name;
					$uid = sprintf('un-%s-id-%d', $vo->username_dst, $vo->user_dst_id);
					$crea = ($vo->creator == DebtVO::CREATOR_DST);
					$dir_cla = "hist-dir-i-owe$pb hist-dir";
					if (!$vo->is_payback) {
						$dir_txt = "je dois à";
					} else {
						$dir_txt = "m'a remboursé";
					}
				}
				$oth_fn = sprintf('<span class="user" id="zuser-%s">%s</span>', $uid, $oth_fn);
				if ($crea) {
					$oth_fn = sprintf('<strong>%s</strong>', $oth_fn);
				}
				if (!$vo->is_confirmed) {
					$oth_fn .= '&nbsp;<img src="res/images/nc.png" alt="~" />';
				}
				$amount = format_amount($vo->amount);
				if ($vo->is_payback) {
					$amount = sprintf('<strong>%s</strong>', $amount);
				}
				if (is_null($vo->descr)) {
					$descr = '<span class="empty">&empty;</span>';
				} else {
					$descr = $sf_vo->descr;
				}
				$dr = $vo->date_real;
				if (is_null($dr)) {
					$dr = '<span class="empty">&empty;</span>';
				} else {
					$dr = $dr->format('Y/m/d');
				}
				$dc = $vo->date_creation->format('Y/m/d');
				$tr_add_c = "";
				if (!$vo->is_confirmed) {
					$tr_add_c = "to_be_confirmed"; 
				}
				$RET .= "<tr class=\"$tr_add_c\">";
				$RET .= sprintf('<td class="vl">%s</td><td class="vl"><div class="%s">%s</div></td><td class="vl amt">%s</td><td class="vl">%s</td><td class="vl">%s</td><td>%s</td>',
					$oth_fn, $dir_cla, $dir_txt, $amount, $descr, $dr, $dc);
				$RET .= '</tr>';
			}
			$RET .= '</tbody></table></div>';
		}
		
		return $RET;
	}
?>