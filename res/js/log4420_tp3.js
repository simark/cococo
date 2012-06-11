/**
 * Ce document est celui qui doit être corrigé concernant le TP3 de LOG4420
 * (automne 2011). Il contient l'implémentation des points demandés, notamment
 * la validation, l'utilisation du DOM Level 2 pour les événements ainsi que
 * des fonctions pratiques pour AJAX.
 *
 * Le reste du JavaScript du projet est réalisé plus facilement dans le fichier
 * "master.js", grâce au délicieux framework jQuery.
 */
 
// ajout d'écoute pour "load" (document chargé)
if (window.addEventListener) {
	// DOM Level 2
	window.addEventListener('load', init_load, true);
} else {
	// Internet Explorer...
	window.onload = init_load;
}

/**
 * Ajoute une écoute sur un événement à un élément (portable).
 *
 * @param elem		Élément
 * @param ev		Nom de l'événement ('click', par exemple)
 * @param listener(e)	Fonction d'écoute
 */
function add_listener_to(elem, ev, listener) {
	// DOM Level 2 (IE et autres fureteurs)
	if (elem.addEventListener) {
		elem.addEventListener(ev, listener, true);
	} else if (elem.attachEvent) {
		elem.attachEvent('on' + ev, listener);
	} else {
		throw "événements DOM Level 2 non supportés...";
	}
}

/**
 * Fonction qui est appelée lorsque le document est chargé.
 */
function init_load() {
	// notre formulaire
	var form = document.getElementById('form-signup');
	
	// ceci s'appliquera seulement si le formulaire est trouvé (page d'inscription)
	if (form) {
		// validation sur soumission du formulaire
		add_listener_to(form, 'submit', function(e) {
			if (!validate_signup(form)) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				return false;
			}
		});
		
		// vérification d'utilisateur déjà existant
		add_listener_to(form.username, 'keyup', function(e) {
			var val = form.username.value;
			
			// vérifier seulement si l'utilisateur semble valide de toute façon...
			if (val.length >= 5) {
				ajax_load(null, 'actions/a_username_exists.php?un=' + encodeURIComponent(val), false, function(data) {
					if (data == "1") {
						form.username.style.backgroundColor = '#f1bbbb';
					} else {
						form.username.style.backgroundColor = '#b3e6b3';
					}
				});
			} else {
				form.username.style.backgroundColor = '#fff';
			}
		});
	}
}

/**
 * Retourne une chaine trimée.
 *
 * @param str	Chaine d'entrée
 * @return	Chaine trimée
 */
function trim(str) {
	return str.replace(/^\s+|\s+$/g, "");
}

/**
 * Valide un nom/prénom.
 *
 * @param name		Nom
 * @return		Vrai si valide
 */
function val_name(name) {
	// le nom ne doit pas être trop long ni trop court
	if (name.length > 50 || name.length < 2) {
		return false;
	}
	
	// le nom doit contenir seulement certains caractères (nous nous limitons ici à la plage latine)
	if (!name.match(/^[A-Za-zÀàÁáÄäÂâÉéÈèËëÊêÌìÍíÎîÏïÒòÓóÔôÖöÙùÚúÛûÜüÿÇç -]+$/)) {
		return false;
	}
	
	return true;
}

/**
 * Validation du formulaire d'inscription.
 *
 * J'utilise ici jQuery exclusivement pour l'affichage, c'est-à-dire qu'il ne participe
 * pas à la validation, mais plutôt aux messages d'erreur. Sans jQuery, j'aurais utilisé
 * alert(), probablement.
 *
 * @param form		Élément du formulaire
 * @return		Vrai si valide
 */
function validate_signup(form) {
	var ret = true;
	
	// données du formulaire
	var first_name = form.first_name.value;
	var last_name = form.last_name.value;
	var email = form.email.value;
	var gender = form.gender.value;
	var username = form.username.value;
	var passwd = form.passwd.value;
	var passwd_conf = form.passwd_conf.value;
	
	// cacher les anciens messages d'erreur (utilise jQuery)
	$('span.info-invalid').hide();
	
	// validation : prénom et nom
	if (!val_name(first_name)) {
		// afficher le message d'erreur
		show_invalid_tip(form.first_name);	
		ret = false;
	}
	if (!val_name(last_name)) {
		// afficher le message d'erreur
		show_invalid_tip(form.last_name);
		ret = false;
	}
	
	// validation : courriel
	if (!email.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
		// afficher le message d'erreur
		show_invalid_tip(form.email);
		ret = false;
	}
	
	// validation : nom d'utilisateur
	if (!username.match(/^[A-Za-z0-9_]+$/) || username.length < 5 ||
	form.username.style.backgroundColor == '#f1bbbb') {
		// afficher le message d'erreur
		show_invalid_tip(form.username);
		ret = false;
	}
	
	// validation : tous les champs sont remplis
	if (trim(passwd) == "" || trim(passwd_conf) == "") {
		// afficher le message d'erreur
		show_invalid_tip(form.passwd);
		ret = false;
	}
	
	// validation : mot de passe confirmé
	if (passwd != passwd_conf) {
		show_invalid_tip(form.passwd_conf);
		ret = false;
	}
	
	return ret;
}

/**
 * Charge du contenu dynamique dans le HTML interne d'un élément.
 *
 * *TOUTES* les interactions AJAX du projet utilise cette fonction
 * unique. La plupart sont faites à partir de "master.js".
 *
 * @param elem		Élément DOM (null pour effectuer la requête sans chargement)
 * @param url		URL AJAX (paramètres GET seulement)
 * @param spinner	Vrai pour activer le spinner AJAX
 * @param cb(resp)	Fonction à rappeler lorsque la requête est terminée (ou null)
 */
function ajax_load(elem, url, spinner, cb) {
	var xmlhttp;
	
	// création de l'objet XMLHttpRequest
	if (window.XMLHttpRequest) {
		// IE7+, Firefox, Chrome, Opera, Safari => tous les fureteurs qui se respectent
		xmlhttp = new XMLHttpRequest();
	} else {
		// IE5, IE6
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// fonction de rappel pour le changement d'état AJAX
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var resp = xmlhttp.responseText;
			if (elem) {
				elem.innerHTML = resp;
			}
			if (cb) {
				cb(resp);	// appeler la fonction d'utilisateur
			}
			if (spinner) {
				ajax_spinner_off();
			}
		}
	}
	
	// ouvrir la connexion et envoyer la requête
	xmlhttp.open("GET", url, true);
	if (spinner) {
		ajax_spinner_on();
	}
	xmlhttp.send();
}