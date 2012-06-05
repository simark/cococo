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
	public $birthday;	// Date
	public $gender;		// 'male' ou 'female'
	public $username;
	public $passwd;		// SHA-1 du mot de passe
	public $groups;		// Groupes
	public $feature_names;	// Noms de fonctionnalités disponibles pour l'utilisateur
	public $is_active;
	public $date_creation;	// Date
	public $theme;
	public $avatar;
	public $locale;
	
	/**
	 * Indique si l'utilisateur possède une fonctionnalité.
	 * 
	 * @param string $name		Nom de la fonctionnalité
	 * @return bool			Vrai si l'utilisateur possède la fonctionnalité
	 */
	public function has_feature($name) {
		if (isset($this->feature_names)) {
			return in_array($name, $this->feature_names);
		} else {
			return false;
		}
	}
	
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