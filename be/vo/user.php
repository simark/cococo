<?php
/**
 * VO d'utilisateur. 
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class UserVO extends CommonVO {
	public $id;	
	public $last_name;
	public $first_name;
	public $email;
	public $username;
	public $passwd;		// SHA-1 du mot de passe
	public $is_active;
	public $date_creation;	// Date
	public $avatar;
	public $locale;
	
	/**
	 * Obtenir le nom complet d'après le prénom et le nom de famille.
	 */
	public function get_full_name() {
		return "{$this->first_name} {$this->last_name}";
	}
	
	/**
	 * Crée un ID textuel unique.
	 */
	public function get_unique_id() {
		return sprintf('un-%s-id-%d', $this->username, $this->id);
	}
}
?>
