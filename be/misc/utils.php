<?php
	/**
	 * Ce fichier contient des fonctions utilitaires communes 
	 * à tous les scripts. Il peut être vu comme un ensemble
	 * d'extensions aux fonctions de la librairie standard PHP.
	 * 
	 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
	 */

	/**
	 * Renvoie la clef de la page selon la requête HTTP.
	 *
	 * @param array $valid_pages	Pages valides
	 * @return string		Clef actuelle
	 */
	function get_page_key($valid_pages) {
		$ret = array_keys($valid_pages);
		$ret = $ret[0];

		if (!isset($_GET['p'])) {
			return $ret;
		}
		$asked = trim ($_GET['p']);
		if ($found = array_search($asked, $valid_pages)) {
			$ret = $found;
		}
		
		return $ret;
	}

	/**
	 * Convertit une chaine vers une chaine HTML correcte avec l'encodage UTF-8.
	 *
	 * @param string $in	Chaine d'entrée (UTF-8)
	 * @return string	Chaine d'entités HTML (UTF-8)
	 */
	function hs($in) {
		return htmlentities($in, ENT_COMPAT, "UTF-8");
	}

	/**
	 * Obtenir les noms de mois.
	 * 
	 * @return array	Liste des mois
	 */
	function get_month_names() {
		return array(
			"janvier",
			"f&eacute;vrier",
			"mars",
			"avril",
			"mai",
			"juin",
			"juillet",
			"ao&ucirc;t",
			"septembre",
			"octobre",
			"novembre",
			"d&eacute;cembre"
		);
	}

	/**
	 * Convertit une date/temps MySQL en objet PHP DateTime.
	 * 
	 * @param string $mysql_dt	Date/temps MySQL
	 * @return Date			Date PHP
	 */
	function datetime_from_mysql_datetime($mysql_dt) {
		if (is_null($mysql_dt)) {
			return $mysql_dt;
		}
		
		return new DateTime($mysql_dt);
	}

	/**
	 * Convertit une date/temps MySQL en objet PHP DateTime "in-place".
	 * 
	 * @param string $mysql_dt	Date/temps MySQL à transformer
	 */
	function dtfmdt(&$mysql_dt) {
		$mysql_dt = datetime_from_mysql_datetime($mysql_dt);
	}
	
	/**
	 * Redirige de façon HTTP vers un autre document.
	 *
	 * @param string $url	Nouvel URL
	 */
	function redir($url) {
		header("Location: $url");
	}
	
	/**
	 * Effectue un méga-ET entre tous les éléments logiques d'un tableau.
	 * 
	 * @param array	$in	Tableau de valeurs logiques
	 * @return bool		Résultat d'un ET entre chaque valeur
	 */
	function mega_and($in) {
		$res = true;
		foreach ($in as $v) {
			$res = ($res && $v);
		}
		
		return $res;
	}
	
	/**
	 * Effectue un méga-OU entre tous les éléments logiques d'un tableau.
	 * 
	 * @param array	$in	Tableau de valeurs logiques
	 * @return bool		Résultat d'un OU entre chaque valeur
	 */
	function mega_or($in) {
		$res = false;
		foreach ($in as $v) {
			$res = ($res || $v);
		}
		
		return $res;
	}
	
	/**
	 * Formate un montant d'argent selon une valeur en sous.
	 * 
	 * @param int $cents	Valeur en sous
	 * @return string	Montant formaté
	 */
	function format_amount($cents) {
		return number_format($cents / 100, 2, ",", " ") . " $";
	}
	
	/**
	 * Formate un montant d'argent selon une valeur en sous (version légère).
	 * 
	 * @param int $cents	Valeur en sous
	 * @return string	Montant formaté
	 */
	function format_amount_light($cents) {
		if ($cents == 0) {
			return "rien";
		} else if ($cents % 100 == 0) {
			return ($cents / 100) . " $";
		} else {
			return number_format($cents / 100, 2, ",", " ") . " $";
		}
	}
	
	/**
	 * Remplace les espaces par des espaces insécables.
	 * 
	 * @param string $in	Chaine d'entrée
	 * @return string	Sortie
	 */
	function snbsp($in) {
		return str_replace(' ', '&nbsp;', $in);
	}
	
	/**
	 * Valeur booléenne en mot.
	 * 
	 * @param bool $bool	Valeur booléenne
	 * @return string	Mot
	 */
	function btw($bool) {
		return $bool ? 'oui' : 'non';
	}
	
	/**
	 * Renvoit la chaine NULL si vaut NULL ou la chaine entre guillemets simples sinon.
	 * 
	 * @param $val		Valeur à tester
	 * @return string	Résultat en chaine
	 */
	function norv($val) {
		return is_null($val) ? 'NULL' : "'$val'";
	}
	
	/**
	 * Rend une chaine vide nulle.
	 * 
	 * @param string $in	Chaine à rendre nulle
	 */
	function msn(&$in) {
		if (!is_null($in)) {
			if (strlen(trim($in)) == 0) {
				$in = NULL;
			}
		}
	}
	
	/**
	 * Renvoie une chaine vide si elle est nulle.
	 *
	 * @param string $in	Chaine
	 * @return string	Chaine vide ou chaine originale
	 */
	function gs($in) {
		return (is_null($in) ? "" : $in);
	}
	
	/**
	 * Approximation d'un intervalle en mots.
	 * 
	 * @param int $s	Nombre de secondes
	 * @return string	Intervalle en mots
	 */
	function inter_words($s) {
		if ($s < 60) {
			$o = $s;
			$w = "seconde" . (($s > 1) ? 's' : '');
		} else if ($s < 60 * 60) {
			$o = round($s / 60);
			$w = "minute" . (($o > 1) ? 's' : '');
		} else if ($s < 60 * 60 * 24) {
			$o = round($s / 60 / 60);
			$w = "heure" . (($o > 1) ? 's' : '');
		} else if ($s < 60 * 60 * 24 * 7) {
			$o = round($s / 60 / 60 / 24);
			$w = "jour" . (($o > 1) ? 's' : '');
		} else if ($s < 60 * 60 * 24 * 7 * 4) {
			$o = round($s / 60 / 60 / 24 / 7);
			$w = "semaine" . (($o > 1) ? 's' : '');
		} else {
			$o = round($s / 60 / 60 / 24 / 7 / 4);
			$w = "mois";
		}
		
		return "$o $w";
	}
	
	/**
	 * Approximation d'un intervalle en mots (selon le jour).
	 * 
	 * @param int $s	Nombre de secondes
	 * @return string	Intervalle en mots
	 */
	function inter_words_daily($s) {
		if ($s < 3600 * 24) {
			return "aujourd'hui";
		} else if ($s < 3600 * 24 * 2) {
			return "hier";
		} else if ($s < 3600 * 24 * 3) {
			return "avant-hier";
		} else {
			return "il y a " . inter_words($s);
		}
	}
?>