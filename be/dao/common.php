<?php
/**
 * DAO commun.
 * 
 * Interface commune à tous les DAO. Tous les DAO devraient hériter de cette
 * classe abstraite afin de se conformer au design élaboré ici.
 * 
 * Nous déclarons ici le plus possible d'attributs protégés qui permettront
 * d'automatiser les tâches de sélection, entre autres, de VO à partir d'un
 * DAO donné. Le constructeur d'un DAO spécifique devrait initialiser ceux-ci
 * afin de pouvoir profiter de l'automatisation de sa classe mère. 
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
abstract class CommonDAO {
	/**
	 * Retient une connexion à une base de données.
	 * 
	 * @var resource
	 */
	protected $_conn;
	
	/**
	 * Liste des champs simples importants pour l'objet principal en base
	 * de données lié au DAO dérivé; le premier élément devrait être l'ID
	 * numérique.
	 *
	 * @var array
	 */
	protected $_fields;
	
	/**
	 * Nom de la table principale en base de données liée au DAO dérivé.
	 * 
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Nom de la classe du VO principal ciblé par le DAO dérivé.
	 * 
	 * @var string
	 */
	protected $_vo_class;
	
	/**
	 * Obtenir l'objet par ID numérique (version générique).
	 * 
	 * @param int $id	ID numérique de l'objet
	 * @return		Objet ou NULL si non trouvé
	 */
	public function get_by_id($id) {
		$sf_id = $this->escape_string($id);
		
		// construire la chaine SQL correspondante
		$fields = implode(", ", $this->_fields);
		$id_f = $this->_fields[0];
		$sql = sprintf("SELECT %s FROM %s WHERE %s = '%d'", $fields, $this->_table, $id_f, $sf_id);
		$row = $this->get_single_row($sql);
				
		// construire le VO
		if (!is_null($row)) {
			$vo = new $this->_vo_class;
			foreach ($row as $k => $v) {
				$vo->$k = $v;
			}
			
			return $vo;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Construit/initialise un DAO.
	 * 
	 * @param resource $conn	Connexion à la base de données
	 */
	protected function __construct($conn) {
		$this->_conn = $conn;
	}
	
	/**
	 * Rend une entrée sécuritaire pour la base de données.
	 * 
	 * @param @in		Valeur à sécuriser
	 * @return string	Valeur assainie
	 */
	protected function escape_string($in) {
		return mysql_real_escape_string($in, $this->_conn);
	}
	
	/**
	 * Obtenir une seule rangée à partir d'une requête.
	 * 
	 * @param string $query		Requête SQL complète
	 * @return			Rangée ou NULL si non trouvée
	 */
	protected function get_single_row($query) {
		$res = mysql_query($query, $this->_conn);

		// retourner NULL si aucun résultat n'est obtenu avec la requête
		if (mysql_num_rows($res) > 0) {
			return mysql_fetch_assoc($res);
		} else {
			return NULL;
		}
	}
	
	/**
	 * Obtenir toute les rangées d'un coup à partir d'une requête.
	 * 
	 * @param string $query		Requête SQL complète
	 * @return			Rangées ou NULL si non trouvées
	 */
	protected function get_all_rows($query) {
		$res = mysql_query($query, $this->_conn);
		
		// retourner NULL si aucun résultat n'est obtenu avec la requête
		if (mysql_num_rows($res) > 0) {
			$ret = array();
			while ($row = mysql_fetch_assoc($res)) {
				array_push($ret, $row);
			}
			
			return $ret;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Requête SQL simple.
	 * 
	 * @param string $query		Requête SQL complète
	 * @return resource		Résultat MySQL
	 */
	protected function query($query) {
		$res = mysql_query($query, $this->_conn);
		
		return $res;
	}
	
	/**
	 * Insertion simple.
	 * 
	 * @param array $fields		Tableau associatif des champs à insérer
	 * @return resource		Résultat MySQL
	 */
	protected function simple_insert($fields) {
		$sf_table = $this->_table;
		foreach ($fields as $k => $v) {
			if ($v === NULL) {
				$sf_v = "NULL";
			} else {
				$sf_v = sprintf("'%s'", $this->escape_string($v));
			}
			$sf_fields[$k] = $sf_v;
		}
		
		$fields_keys = array_keys($sf_fields);
		$fields_vals = array_values($sf_fields);
		$f_keys = implode(", ", $fields_keys);
		$f_vals = implode(", ", $fields_vals);
		
		$sql = "INSERT INTO $sf_table ($f_keys) VALUES($f_vals)";
		
		$res = $this->query($sql);
		if ($res === false) {
			return false;
		} else {
			return mysql_insert_id($this->_conn);
		}
	}
	
	/**
	 * Simple mise à jour.
	 * 
	 * @param int $id		ID numérique de la rangée à mettre à jour
	 * @param array $fields		Champs à mettre à jour
	 * @return resource		Résultat MySQL
	 */
	protected function simple_update($id, $fields) {
		$sf_table = $this->_table;
		$sf_id = $this->escape_string($id);
		foreach ($fields as $k => $v) {
			if ($v === NULL) {
				$sf_v = "NULL";
			} else {
				$sf_v = sprintf("'%s'", $this->escape_string($v));
			}
			$sf_fields[$k] = $sf_v;
		}
		
		$sets = array();
		foreach ($sf_fields as $k => $sf_v) {
			$set = sprintf("%s = %s", $k, $sf_v);
			array_push($sets, $set);
		}
		$sets_str = implode(", ", $sets);
		
		$sql = "UPDATE $sf_table SET $sets_str WHERE {$this->_fields[0]} = '$sf_id'";
		
		$res = $this->query($sql);
		
		return $res;
	}
	
	/**
	 * Débute une transaction de base de données.
	 */
	protected function start_db_tx() {
		mysql_query("SET AUTOCOMMIT = 0", $this->_conn);
		mysql_query("BEGIN", $this->_conn);
	}
	
	/**
	 * Commet une transaction de base de données.
	 */
	protected function commit_db_tx() {
		mysql_query("COMMIT", $this->_conn);
	}
	
	/**
	 * Annule une transaction de base de données en cours.
	 */
	protected function rollback_db_tx() {
		mysql_query("ROLLBACK", $this->_conn);
	}
	
	/**
	 * Obtenir toutes les rangées sous forme de tableau de VO.
	 * 
	 * @param bool|string $order_by		Classer selon un champ spécifique
	 * @param $method			Méthode à appeler pour obtenir un VO
	 * @return array			Tableau de VO ou NULL si rien trouvé
	 */
	protected function get_all($order_by = false, $method = NULL) {
		$table = $this->_table;
		$id_field = $this->_fields[0];
		
		if ($order_by !== false) {
			$order = "ORDER BY $order_by";
		} else {
			$order = "";
		}
		if (is_null($method)) {
			$method = "get_by_id";
		}
		
		$sql = "SELECT
				$id_field
			FROM
				$table
			$order";
		$resp = $this->get_all_rows($sql);
		
		if ($resp === NULL) {
			return NULL;
		} else {
			$ret = array();
			
			foreach ($resp as $item) {
				$vo = $this->$method($item[$id_field]);
				array_push($ret, $vo);
			}
			
			return $ret;
		}
	}
	
	/**
	 * Obtenir une rangée spécifique en tant que VO.
	 * 
	 * @param string $field_key		Nom du champ à vérifier
	 * @param string $field_val		Valeur du champ à vérifier
	 * @param bool $case_i			Sensible à la casse
	 * @return				VO ou NULL si rien trouvé
	 */
	protected function get_by_field($field_key, $field_val, $case_i = false) {
		$sf_field_key = $this->escape_string($field_key);
		$sf_field_val = $this->escape_string($field_val);
		
		if ($case_i) {
			$_f_left = "UPPER(";
			$_f_right = ")";
		} else {
			$_f_left = "";
			$_f_right = "";
		}
		
		$sql = "SELECT
				{$this->_fields[0]}
			FROM
				{$this->_table}
			WHERE
				$_f_left$sf_field_key$_f_right = $_f_left'$field_val'$_f_right";
		$resp = $this->get_single_row($sql);
		
		if ($resp === NULL) {
			return NULL;
		} else {
			$c = $this->get_by_id($resp[$this->_fields[0]]);

			return $c;
		}
	}
	
	/**
	 * Obtenir plusieurs rangées (jointure) en tant que tableau de VO.
	 * 
	 * @param string $many_table		Nom de l'autre table
	 * @param string $id_field		Nom du champ de l'ID numérique
	 * @param int $id			ID numérique
	 * @param string $many_id_field		Nom du champ de l'ID numérique de l'autre table
	 * @param unknown_type $many_dao_class
	 */
	protected function get_many($many_table, $id_field, $id, $many_id_field, $many_dao_class) {
		$sf_many_table = $this->escape_string($many_table);
		$sf_id_field = $this->escape_string($id_field);
		$sf_id = $this->escape_string($id);
		$sf_many_id_field = $this->escape_string($many_id_field);

		$sql = "SELECT
				$sf_many_table.$sf_many_id_field AS many_id
			FROM
				$sf_many_table
			WHERE
				$sf_many_table.$sf_id_field = '$sf_id'";
		$resp = $this->get_all_rows($sql);
		
		if ($resp === NULL) {
			return NULL;
		} else {
			$ret = array();
			$dao = new $many_dao_class($this->_conn);
			foreach ($resp as $row) {
				$item = $dao->get_by_id($row['many_id']);
				array_push($ret, $item);
			}
			
			return $ret;
		}
	}
}
?>