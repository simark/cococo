<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// validation
	if (!is_numeric($id)) {
		echo "ERREUR d'ID";
		exit(1);
	}
	
	// transaction
	$txr = $g_be_dm->tx_get_zuser($id);
	if ($txr->content === false || $txr->err != CommonManager::INFO_TX_STARTED) {
		exit(2);
	}
	$vo = $txr->content['user'];
	$sf_vo = $vo->get_html_safe_copy();
	$is_fav = $txr->content['is_fav'];
	$img_fav = '';
	if ($is_fav) {
		$img_fav = '<img class="star" alt="" src="res/images/star_16.png">';
	}
	$sf_fn = $sf_vo->get_full_name();
	printf('<p class="fn"><img class="avatar" alt="" src="res/images/avatars/%s.png">%s%s</p>',
		$vo->avatar, $sf_fn, $img_fav);
	printf('<p class="infos">%s partage <strong>%d</strong> dette%s avec vous</p>',
		$sf_fn, $txr->content['debts'], $txr->content['debts'] > 1 ? 's' : '');
	$uid = $vo->get_unique_id();
	if ($is_fav) {
		$fav_but_txt = "retirer des favoris";
		$fav_but_id = "remove-from-favs-$uid";
	} else {
		$fav_but_txt = "ajouter aux favoris";
		$fav_but_id = "add-to-favs-$uid";
	}
	printf('<p class="toolbox"><button id="add-debt-%s">ajouter une dette</button><br /><button id="%s">%s</button></p>',
		$uid, $fav_but_id, $fav_but_txt);
?>