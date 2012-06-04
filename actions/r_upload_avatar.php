<?php
	require_once("../be/init.php");
	
	// configuration
	$path = "../res/images/avatars";
	
	// vérifier le type et la taille
	$ok = false;
	$type = $_FILES["file"]["type"];
	if ((($type == "image/gif") ||
	($type == "image/jpeg") ||
	($type == "image/pjpeg") ||
	($type == "image/png")) &&
	($_FILES["file"]["size"] < (128 * 1024))) {
		if ($_FILES["file"]["error"] <= 0) {
			$ok = true;
		}
	}
	if (!$ok) {
		echo "lol";
		redir("../?p=profile&smsg=invalid_avatar");
		exit(1);
	}
		
	// prendre les informations
	$tmp_name = $_FILES["file"]["tmp_name"];
	
	// redimensionner l'image
	list($w, $h) = getimagesize($tmp_name);
	switch ($type) {
		case 'image/gif':
		$im = imagecreatefromgif($tmp_name);
		break;
		
		case 'image/jpeg':
		case 'image/pjpeg':
		$im = imagecreatefromjpeg($tmp_name);
		break;
		
		case 'image/png':
		$im = imagecreatefrompng($tmp_name);
		break;
		
		default:
		redir("../?p=profile&smsg=invalid_avatar");
		exit(3);
	}
	if (!$im) {
		redir("../?p=profile&smsg=invalid_avatar");
		exit(4);
	}
	$dst = imagecreatetruecolor(48, 48);
	imagecopyresampled($dst, $im, 0, 0, 0, 0, 48, 48, $w, $h);
	
	// créer un nom et créer l'image
	$rd = sha1(microtime()) . rand(10000, 99999);
	while (file_exists("$path/$rd.png")) {
		$rd = sha1(microtime()) . rand(10000, 99999);
	}
	$fp = "$path/$rd.png";
	if (imagepng($dst, $fp)) {
		// transaction
		$txr = $g_be_um->tx_modify_profile_field('avatar', $rd);
	}
	
	// rediriger
	redir("../?p=profile");
?>