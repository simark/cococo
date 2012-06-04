<?php
	function content_favs_favs() {
		global $g_be_um, $g_be_user;
		$RET = "";
		
		$txr = $g_be_um->tx_get_my_favs();
		if (!is_array($txr->content)) {
			exit(1);
		}
		
		if (count($txr->content) == 0) {
			$RET .= '<p class="noth">&empty;</p>';
		} else {
			$RET .= '<table style="width: 100%; border-collapse: collapse; border: none; margin: 0px; padding: 0px;"><tbody>';
			$RET .= '<tr>';
			$x = 0;
			foreach ($txr->content as $vo) {
				if ($x % 3 == 0 && $x != 0) {
					$RET .= '</tr><tr>';
				}
				++$x;
				$sf_vo = $vo->get_html_safe_copy();
				$uid = $vo->get_unique_id();
				$RET .= sprintf('<td><div class="fav-user-fitem"><img src="res/images/avatars/%s.png" alt="" /><span class="user" id="zuser-%s">%s</span></div></td>',
					$vo->avatar, $uid, $sf_vo->get_full_name());
			}
			for ($i = 0; $i < (3 - ($x % 3)); ++$i) {
				$RET .= '<td></td>';
			}
			$RET .= '</tr></tbody></table>';
		}
		
		return $RET;
	}
?>