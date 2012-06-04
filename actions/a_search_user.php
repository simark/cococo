<?php
	require_once("../be/init.php");
	
	// formater l'entrée
	$s = trim(gs($_GET['s']));
	
	// agir si valides
	$txr = $g_be_um->tx_search_user($s);
	if (is_array($txr->content) && strlen($s) > 0) {
		foreach ($txr->content as $vo) {
			$sf_vo = $vo->get_html_safe_copy();
			printf('<p><img src="res/images/avatars/%s.png" alt="" /><a id="su-un-%s-id-%d" href="#">%s</a> (%s)</p>',
				$vo->avatar, $vo->username, $vo->id, $sf_vo->get_full_name(), $vo->username);
		}
	} else {
		echo '<p class="empty">aucun résultat!</p>';
	}
?>