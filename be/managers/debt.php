<?php
/**
 * Gestionnaire de dettes.
 *
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class DebtManager extends CommonManager {	
	/**
	 * Instance du singleton.
	 * 
	 * @var DebtManager
	 */
	private static $_instance;
	
	/**
	 * Protection de construction du singleton.
	 */
	protected function __construct() {
		parent::__construct();
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
	 * Appelée par une transaction, cette méthode donne un tableau de DebtSummaryVO.
	 * 
	 * @return array	Tableau de DebtSummaryVO, possiblement vide, ou NULL si erreur
	 */
	private function get_summary($id_user) {
		$sql = "SELECT
				id_user_src,
				id_user_dst,
				amount
			FROM
				vu_debts_details
			WHERE
				(id_user_src = '$id_user' OR
				id_user_dst = '$id_user') AND
				is_confirmed = TRUE";
				die($sql);
		$res = $this->query($sql);
		if ($res === false) {
			return NULL;
		} else {
			// grosse logique applicative
			$a = array();
			while ($row = mysql_fetch_assoc($res)) {
				if ($row['id_user_src'] == $id_user) {
					// direction : l'utilisateur doit à la destination
					$dir = 1;
					$other = $row['id_user_dst'];
				} else {
					// direction : la source doit à l'utilisateur
					$dir = -1;
					$other = $row['id_user_src'];
				}
				if (!isset($a[$other])) {
					$a[$other] = 0;
				}
				$a[$other] += ($dir * $row['amount']);
			}
			$dao = new UserDAO(parent::get_conn());
			$ret = array();
			foreach ($a as $id_user_other => $amount) {
				if ($amount == 0) {
					// passer les comptes nuls
					continue;
				}
				$vo = new DebtSummaryVO;
				$vo->user = $dao->get_by_id_partial($id_user_other);
				$vo->amount = abs($amount);
				$vo->direction = ($amount > 0) ? DebtSummaryVO::DEBT_DIR_I_OWE_THEM : DebtSummaryVO::DEBT_DIR_THEY_OWE_ME;
				array_push($ret, $vo);
			}
		}
		
		return $ret;
	}
	
	/**
	 * Obtenir les totaux d'un tableau de sommaires de dettes.
	 * 
	 * @param array	$sum		Tableau de sommaires de dettes
	 * @return DebtTotalsVO		Totaux ou NULL
	 */
	public function get_totals_from_summary($sum) {
		$vo = new DebtTotalsVO;
		$vo->i_owe_them = 0;
		$vo->they_owe_me = 0;
		
		foreach ($sum as $svo) {
			if ($svo->direction == DebtSummaryVO::DEBT_DIR_I_OWE_THEM) {
				$vo->i_owe_them += $svo->amount;
			} else {
				$vo->they_owe_me += $svo->amount;
			}
		}
		
		return $vo;
	}
	
	/**
	 * Transaction : obtenir mes totaux.
	 * 
	 * @return	Réponse de transaction avec les totaux
	 */
	public function tx_get_my_totals() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$txr->content = $this->get_summary($id_user);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : obtenir le sommaire de mes dettes.
	 * 
	 * @return	Réponse de transaction avec le sommaire des dettes
	 */
	public function tx_get_my_summary() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$txr->content = $this->get_summary($id_user);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Appelée par une transaction, cette méthode donne l'historique de dettes
	 * d'un utilisateur et possiblement seulement celles qui ne sont pas confirmées.
	 * 
	 * @param int $id_user		ID numérique de l'utilisateur à rechercher
	 * @param bool $only_tbc	Sélectionner seulement les entrées non confirmées
	 * @return array		Tableau de DebtVO (possiblement vide) ou NULL si erreur
	 */
	private function get_history($id_user, $only_tbc) {
		$add = "";
		if ($only_tbc) {
			$where = "
				((id_user_src = $id_user AND creator <> 'src') OR
				(id_user_dst = $id_user AND creator <> 'dst')) AND
				is_confirmed = FALSE";
		} else {
			$where = "
				id_user_src = $id_user OR
				id_user_dst = $id_user";
		}
		$sql = "SELECT
				id_debt,
				id_user_src,
				id_user_dst,
				src_full_name,
				dst_full_name,
				src_username,
				dst_username,
				amount,
				creator,
				descr,
				is_payback,
				is_confirmed,
				date_real,
				DATE(date_creation) AS date_creation
			FROM
				vu_debts_details
			WHERE
				$where
			ORDER BY
				vu_debts_details.date_creation DESC";
		$res = $this->query($sql);
		if ($res === false) {
			$ret = NULL;
		} else {
			$ret = array();
			while ($row = mysql_fetch_assoc($res)) {
				$vo = new DebtVO;
				$vo->id = $row['id_debt'];
				$vo->user_dst_id = $row['id_user_dst'];
				$vo->user_src_id = $row['id_user_src'];
				$vo->user_dst_full_name = $row['dst_full_name'];
				$vo->user_src_full_name = $row['src_full_name'];
				$vo->username_src = $row['src_username'];
				$vo->username_dst = $row['dst_username'];
				$vo->amount = $row['amount'];
				switch ($row['creator']) {
					case 'src':
					$vo->creator = DebtVO::CREATOR_SRC;
					break;
					
					case 'dst':
					$vo->creator = DebtVO::CREATOR_DST;
					break;
				}
				$vo->descr = $row['descr'];
				$vo->is_confirmed = ($row['is_confirmed'] == true);
				$vo->is_payback = ($row['is_payback'] == true);
				$vo->date_creation = datetime_from_mysql_datetime($row['date_creation']);
				$vo->date_real = datetime_from_mysql_datetime($row['date_real']);
				array_push($ret, $vo); 
			}
		}
		
		return $ret;
	}
	
	/**
	 * Transaction : obtenir mon historique.
	 * 
	 * @return	Tableau de dettes (DebtVO), possiblement vide, ou NULL si erreur
	 */
	public function tx_get_my_history() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$txr->content = $this->get_history($id_user, false);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : obtenir mes dettes non confirmées.
	 * 
	 * @return	Tableau de dettes (DebtVO), possiblement vide, ou NULL si erreur 
	 */
	public function tx_get_my_to_be_confirmed() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$txr->content = $this->get_history($id_user, true);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Appelée par une transaction, cette méthode insère une nouvelle entrée
	 * dans la table des dettes.
	 * 
	 * @param int $id_user		Utilisateur concerné
	 * @param string $other_user	Information sur l'autre utilisateur
	 * @param string $descr		Description ou NULL
	 * @param int $amount		Montant
	 * @param string $creator	'src' ou 'dst'
	 * @param string $direction	Direction : 'iowethem' ou 'theyoweme'
	 * @param bool $is_payback	Est un remboursement officiel
	 * @param string $date_real	Date réelle au format MySQL (chaine) ou NULL
	 * @return bool			Succès de l'opération
	 */
	private function add_debt($id_user, $other_user, $descr, $amount, $creator, $direction, $is_payback, $date_real) {
		$sf_id_user = parent::escape_string_more($id_user);
		$sf_other_user = parent::escape_string_more($other_user);
		$sf_descr = parent::escape_string_more($descr);
		$sf_amount = parent::escape_string_more($amount);
		$sf_creator = parent::escape_string_more($creator);
		$sf_direction = parent::escape_string_more($direction);
		$sf_is_payback = parent::escape_string_more($is_payback);
		$sf_date_real = parent::escape_string_more($date_real);
		
		$sql = "CALL add_debt($sf_id_user, $sf_other_user, $sf_descr, $sf_amount, $sf_creator, $sf_direction, $sf_is_payback, $sf_date_real)";
		$res =  $this->query($sql);
		if ($res === false) {
			return false;
		}
		$row = mysql_fetch_assoc($res);
		
		return ($row['res'] == 1);
	}
	
	/**
	 * Transaction : ajouter une nouvelle dette.
	 * 
	 * @param string $other_user	Information sur l'autre utilisateur
	 * @param string $descr		Description ou NULL
	 * @param int $amount		Montant
	 * @param string $creator	'src' ou 'dst'
	 * @param string $direction	Direction : 'iowethem' ou 'theyoweme'
	 * @param string $date_real	Date réelle au format MySQL (chaine) ou NULL
	 * @return bool			Vrai si l'insertion a réussi (contenu de la réponse)
	 */
	public function tx_add_debt($other_user, $descr, $amount, $is_payback, $creator, $direction, $date_real) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$txr->content = $this->add_debt($id_user, $other_user, $descr, $amount, $creator, $direction, $is_payback, $date_real);

		$this->stop_tx();
		
		// envoyer le courriel
		$this->start_db();
		$dao = new UserDAO(parent::get_conn());
		$vo = $dao->get_by_username($other_user);
		$this->stop_db();
		$sess = Session::instance();
		$msg = sprintf("cococo\n\n%s a ajouté une dette de %s vous concernant.\n\nVeuillez la confirmer ou l'infirmer : <%s>.\n\nMerci,\nle système.",
			$sess->get_user()->get_full_name(), format_amount_light($amount), "http://cococococococococo.co/");

		// envoi d'un courriel pour la confirmation
		mail($vo->email, "cococo : confirmation de dette", $msg);

		
		return $txr;
	}
	
	/**
	 * Transaction : confirme une dette par l'utilisateur connecté.
	 * 
	 * @param int $id_debt		ID de la dette
	 * @return bool			Vrai si la confirmation a réussi (contenu de la réponse)
	 */
	public function tx_confirm_debt($id_debt) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$sf_id_debt = $this->escape_string_more($id_debt);
		$sql = "CALL confirm_debt($id_user, $sf_id_debt)";
		$res =  $this->query($sql);
		if ($res === false) {
			$txr->content = false;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content = ($row['res'] == 1);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : infirme (supprime) une dette par l'utilisateur connecté.
	 * 
	 * @param int $id_debt		ID de la dette
	 * @return bool			Vrai si la confirmation a réussi (contenu de la réponse)
	 */
	public function tx_invalidate_debt($id_debt) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$id_user = $sess->get_user()->id;
		$sf_id_debt = $this->escape_string_more($id_debt);
		$sql = "CALL invalidate_debt($id_user, $sf_id_debt)";
		$res =  $this->query($sql);
		if ($res === false) {
			$txr->content = false;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content = ($row['res'] == 1);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : zuser.
	 * 
	 * @param int $id	ID de l'autre utilisateur
	 * @return		Réponse transactionnelle avec infos. dans un tableau
	 */
	public function tx_get_zuser($id) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$my_id = $sess->get_user()->id;
		$sf_id = $this->escape_string_more($id);
		$txr->content = array();
		
		// nombre de dettes total
		$sql = "SELECT
				COUNT(*) AS cnt
			FROM
				debts
			WHERE
				(id_user_src = $my_id AND
				id_user_dst = $sf_id) OR
				(id_user_src = $sf_id AND
				id_user_dst = $my_id)";
		$res =  $this->query($sql);
		if ($res === false) {
			$txr->content = false;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content['debts'] = $row['cnt'];
		
		// dernière date de création de dette
		$sql = "SELECT
				date_creation AS dtc
			FROM
				debts
			WHERE
				(id_user_src = $my_id AND
				id_user_dst = $sf_id) OR
				(id_user_src = $sf_id AND
				id_user_dst = $my_id)
			ORDER BY
				date_creation DESC
			LIMIT
				1";
		$res =  $this->query($sql);
		if ($res === false) {
			$txr->content = false;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content['last_debt_date'] = $row['dtc'];
		
		// est favoris
		$sql = "SELECT
				COUNT(*) AS cnt
			FROM
				favs
			WHERE
				id_user = $my_id AND
				id_user_fav = $sf_id";
		$res =  $this->query($sql);
		if ($res === false) {
			$txr->content = false;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content['is_fav'] = ($row['cnt'] > 0);
		
		// VO d'utilisateur
		$u_dao = new UserDAO(parent::get_conn());
		$txr->content['user'] = $u_dao->get_by_id($id);
		
		$this->stop_tx();
		
		return $txr;
	}
}
?>
