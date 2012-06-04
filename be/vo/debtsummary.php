<?php
/**
 * VO de sommaire des dettes.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class DebtSummaryVO extends CommonVO {
	const DEBT_DIR_I_OWE_THEM = "src_owes_dst";
	const DEBT_DIR_THEY_OWE_ME = "dst_owes_src";
	
	public $user;		// utilisateur partiel (UserVO)
	public $amount;		// montant total
	public $direction;	// 'dst_owes_src' ou 'src_owes_dst'
}
?>