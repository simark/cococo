<?php
/**
 * VO commun.
 * 
 * Interface commune à tous les VO. Tous les VO devraient hériter de cette
 * classe abstraite afin de se conformer au design élaboré ici. 
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class CommonVO {	
	/**
	 * Obtenir une copie sécuritaire du VO pour affichage HTML.
	 */
	public function get_html_safe_copy() {
		$copy = clone $this;
		foreach ($copy as $k => &$v) {
			if (is_string($v)) {
				$v = hs($v);
			} elseif (is_object($v)) {
				if (is_subclass_of($v, "CommonVO")) {
					$v = $v->get_html_safe_copy();
				}
			}
		}
		
		return $copy;
	}
	
	/**
	 * Obtenir le nombre d'éléments dans un tableau attribut du VO.
	 * 
	 * @param string $attr		Nom de l'attribut
	 * @return int			Nombre d'éléments dans le tableau
	 */
	public function get_num_of($attr) {
		if (isset($this->$attr)) {
			if (is_array($this->$attr)) {
				return count($this->$attr);
			}
		}
		
		return 0;
	}
}
?>