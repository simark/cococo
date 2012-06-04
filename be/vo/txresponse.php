<?php
/**
 * VO de réponse de transaction.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class TXResponseVO extends CommonVO {		
	public $err;		// chaine d'erreur (ou de succès)
	public $content;	// contenu (souvent un ou plusieurs VO)
}
?>