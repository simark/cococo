<?php
/**
 * Gestion de configuration statique (lecture seule).
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class Config {
	/**
	 * Nom du fichier de configuration (à partir du répertoire où se trouve
	 * cette définition de classe.
	 * 
	 * @var string
	 */
	const CONFIG_FILENAME = '../config.ini';
	
	/**
	 * Configuration.
	 * 
	 * @var array
	 */
	private $_config;
	
	/**
	 * Instance du singleton.
	 * 
	 * @var Config
	 */
	private static $_instance;
	
	/**
	 * Protection de construction du singleton.
	 */
	private function __construct() {
		$config_path = sprintf("%s/%s", __DIR__, self::CONFIG_FILENAME);
		$this->_config = parse_ini_file($config_path);
		if ($this->_config === false) {
			$err = sprintf("impossible d'ouvrir le fichier de configuration \"%s\"", $config_path);
			trigger_error($err, E_USER_ERROR);
		}
	}
	
	/**
	 * Obtenir l'instance du singleton s'il existe.
	 */
	public static function instance() {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c;
		}
		
		return self::$_instance;
	}
	
	/**
	 * Protection contre le clonage.
	 */
	public function __clone() {
		trigger_error("le clonage de cette classe n'est pas permis", E_USER_ERROR);
	}
	
	/**
	 * Obtenir une constante de configuration.
	 * 
	 * @param string $key		Clef de la constante dans la configuration
	 * @return string		Valeur de la constante ou NULL si non trouvée
	 */
	public function get($key) {
		if (!isset($this->_config[$key])) {			
			return NULL;
		} else {
			return $this->_config[$key];
		}
	}
}
?>