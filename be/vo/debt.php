<?php
/**
 * VO de dette.
 * 
 * Les champs $user_src et $user_dst de ce VO seront le plus souvent
 * des utilisateurs UserVO restreints (sans que leurs attributs composés
 * ne soient définis) afin d'alléger la charge.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class DebtVO extends CommonVO {
	const CREATOR_SRC = "creator_src";
	const CREATOR_DST = "creator_dst";
	
	public $id;
	public $user_src_id;
	public $user_dst_id;
	public $user_src_full_name;
	public $user_dst_full_name;
	public $username_src;
	public $username_dst;
	public $creator;
	public $amount;		// montant de la dette (va toujours de la source vers la destination)
	public $is_payback;	// est un remboursement explicite
	public $descr;		// description
	public $is_confirmed;	// dette confirmée par l'autre
	public $date_real;	// date réelle de la dette
	public $date_creation;	// date de création de l'entrée en base de données
}
?>