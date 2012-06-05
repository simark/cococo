<?php
	/**
	 * Ce fichier contient la configuration statique du "front-end"
	 * du site Web (présentation).
	 */
	$g_config = array(
		/* pages valides */
		'valid_pages' => array(
			'cococo' => array(
				'title' => "mon cococo"
			),
			'adddebt' => array(
				'title' => "ajouter une dette"
			),
			'profile' => array(
				'title' => "mon profil"
			),
			'about' => array(
				'title' => "à propos"
			),
			'contact' => array(
				'title' => "nous joindre"
			),
			'signup' => array(
				'title' => "inscription"
			),
			'favs' => array(
				'title' => "mes favoris"
			)
		),
		
		/* suffixe du titre */
		'title_suffix' => " &mdash; cococo",
		
		/* langues supportées (codes) */
		'valid_locales' => array('fr', 'en')
	);
?>