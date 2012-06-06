/**
 * Chargé lorsque le document est prêt.
 */
$(document).ready(function() {
	// gestion des clics sur les étiquettes des options/crochets
	$('span.check_behind').click(function() {
		var input = $(this).prev('input');
		switch ($(input).attr('type')) {
			case 'checkbox':
			if ($(input).attr('checked')) {
				$(input).removeAttr('checked');
			} else {
				$(input).attr('checked', 'checked');
			}
			$(input).change();
			break;
		
			case 'radio':
			$(input).attr('checked', 'checked');
			$(input).change();
			break;
		}
	});
	
	// identification de l'étiquette en cours
	$('form.kf input, form.kf select').focus(function() {
		$(this).parents('td.fi').prev('td.infos').children('label').css('color', '#fff');
	});
	$('form.kf input, form.kf select').blur(function() {
		$(this).parents('td.fi').prev('td.infos').children('label').css('color', '#151C21');
	});
	
	// focus automatique pour certains formulaires
	$('.init-focus').focus();
	
	// fermer la boite d'information
	$('div#info-box').click(function() {
		$(this).slideUp(1000);
	});
	
	// action du logo
	$('div#nav-logo').click(function() {
		document.location.href = ".";
	});
	
	// tabulateur
	$('button[id^="sel-tab-"]').click(function() {
		var tab_id = $(this).attr('id').match(/sel-(tab-\w+)/)[1];
		var current;
		$('div[id^="tab-"]').each(function() {
			if ($(this).css('display') == 'block') {
				current = $(this);
			}
		});
		$(current).slideUp(400, 'easeInOutQuad', function() {
			$('#' + tab_id).slideDown(800, 'easeInOutQuad');
		});
	});
	
	// dataTable
	update_datatable();
	
	// boutons de confirmation
	$('button.confirm-button, button.invalidate-button').click(function() {
		var id = ($(this).attr('id'));
		var url;
		if (m = id.match(/^conf-(\d+)$/)) {
			url = "actions/a_confirm_debt.php?id_debt=" + encodeURIComponent(m[1]);
		} else if (m = id.match(/inv-(\d+)$/)) {
			url = "actions/a_invalidate_debt.php?id_debt=" + encodeURIComponent(m[1]);
		} else {
			return false;
		}
		var parent = $(this).parent();
		ajax_load(null, url, true, function(dat) {
			parent.slideUp(500, function() {
				var tot = 0;
				$("div#conf p.p-owe").each(function() {
					if ($(this).css('display') != 'none') {
						++tot;
					}
				});
				var txt = "";
				if (tot == 0) {
					$("p#conf-info").html('aucune dette à confirmer!');
				} else {
					var suffs = (tot > 1) ? 's' : '';
					$("span#x-debts-info").html(tot + ' dette' + suffs);
				}
				ajax_load($("div#cococo-tabs").get(0), "actions/a_content_cococo_tabs.php", true, function() {
					update_zusers();
					update_datatable();
				});
			});
		});
	});
	
	// slider pour montant
	$('#amount-slider').slider({
		min: 1,
		max: 100,
		slide: function(event, ui) {
			$('input[name="amount"]').val(ui.value);
			
			return true;
		}
	});
	
	// montrer/masquer mes favoris
	$('div#fav-users-box-sh').click(function() {
		$('div#fav-users-box').slideToggle(300);
	});
	
	// clique sur un utilisateur favoris dans l'ajout de dette
	$('div.fav-user-item').click(function() {
		var un = get_username($(this).attr('id'));
		if (un) {
			$('input[name="username"]').val(un);
			hst_fav_users_box(0);
			$('input[name="amount"]').focus();
		}
	});
	
	// remboursement
	$('#check-is-payback').change(function() {
		if ($(this).attr('checked') == 'checked') {
			$('span#ad-iowe-txt').html("l'autre me rembourse");
			$('span#ad-theyowe-txt').html("je rembourse l'autre");
		} else {
			$('span#ad-iowe-txt').html("je dois &agrave; l'autre");
			$('span#ad-theyowe-txt').html("l'autre me doit");
		}
	});
	
	// ajout de dette : recherche d'utilisateur
	$('#search-user').click(function() {
		pop_search_user_window($('input[name="username"]'), function() {
			$('input[name="amount"]').focus();
			hst_fav_users_box(0);
		});
	
		return false;
	});
	
	// sélection de date
	$(".datepick").datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: [2, 3],
		changeYear: true,
		dayNamesMin: ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
		monthNames: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin',
			'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre']
	});
	
	// ajouter un utilisateur aux favoris
	$('#add-user-to-favs').click(function() {
		var un = $('input[name="username"]').val();
		ajax_load(null, 'actions/a_add_to_my_favs.php?un=' + un, false, function() {
			ajax_load($('#fav-users-full').get(0), 'actions/a_content_favs_favs.php', true, function() {
				update_zusers();
			});
		});
		$('input[name="username"]').val('');
		
		return false;
	});
	
	// profil, modifications "inline"
	$('#form-my-infos').submit(function() {
		return false;
	});
	$('#form-my-infos input').change(function() {
		modify_profile_field(this);
	});
	$('#form-my-infos select').change(function() {
		modify_profile_field(this);
	});
	
	// zusers
	update_zusers();
});

// méthodes de tri additionnelles pour dataTables
jQuery.fn.dataTableExt.oSort['formatted-num-asc'] = function(x,y) {
	x = x.replace(/[^\d\-\.\/]/g,'');
	y = y.replace(/[^\d\-\.\/]/g,'');
	if(x.indexOf('/')>=0)x = eval(x);
	if(y.indexOf('/')>=0)y = eval(y);
	return x/1 - y/1;
}
jQuery.fn.dataTableExt.oSort['formatted-num-desc'] = function(x,y) {
	x = x.replace(/[^\d\-\.\/]/g,'');
	y = y.replace(/[^\d\-\.\/]/g,'');
	if(x.indexOf('/')>=0)x = eval(x);
	if(y.indexOf('/')>=0)y = eval(y);
	return y/1 - x/1;
}

/**
 * Met à jour le dataTable de l'historique.
 */
function update_datatable() {
	create_datatable($('#tab-history table.dt'), [
		[5, "desc"]
	], [
		{
			sType: 'formatted-num',
			aTargets: [2]
		}
	]);
}

/**
 * Met à jour les événements concernant les zusers.
 */
function update_zusers() {
	$('.user').click(function() {
		var id_user = get_user_id($(this).attr('id'));
		var username = get_username($(this).attr('id'));
		$('#zuser-window').hide();
		var os = $(this).offset();
		$('#zuser-window').css('top', os.top + 'px');
		$('#zuser-window').css('left', os.left + 'px');
		ajax_load($('#zuser-window').get(0), 'actions/a_zuser.php?id=' + id_user, true, function() {
			$('#zuser-window').stop();
			$('#zuser-window').fadeIn(300);
			$('button[id^="add-debt-"]').click(function() {
				var un = get_username($(this).attr('id'));
				document.location.href = '.?p=adddebt&in_username=' + un;
			});
			$('button[id^="add-to-favs-"]').click(function() {
				var un = get_username($(this).attr('id'));
				document.location.href = 'actions/r_add_to_my_favs.php?un=' + un;
			});
			$('button[id^="remove-from-favs-"]').click(function() {
				var id = get_user_id($(this).attr('id'));
				document.location.href = 'actions/r_remove_from_my_favs.php?id=' + id;
			});
		});
	});
	$('#zuser-window').mouseleave(function() {
		$(this).fadeOut(200);
	});
}

/**
 * Modifie dynamiquement (AJAX) un champ de profil et affiche un message.
 *
 * @param fi	Champ contenant la nouvelle information et un nom pertinent
 */
function modify_profile_field(fi) {
	var fn = $(fi).attr('name');
	var fv = $(fi).val();
	ajax_load(null, 'actions/a_modify_profile_field.php?fn=' + encodeURIComponent(fn) + '&fv=' + encodeURIComponent(fv), true, function(dat) {
		if (dat == "1") {
			show_modified_tip(fi);
		} else {
			show_invalid_tip(fi);
		}
	});
}

/**
 * Montre un message dans un formulaire.
 *
 * @param fi	Champ concerné
 * @param sel	Sélecteur du message
 * @param fade	Le message doit-il fondre
 */
function show_tip(fi, sel, fade) {
	var im = $(fi).parent().children(sel);
	im.stop(true);
	im.fadeTo(0, 1.0);
	if (fade) {
		im.fadeOut(2000, 'easeInExpo');
	}
}

/**
 * Fait apparaitre un message à côté d'un champ qui vient d'être modifié (mon profil).
 *
 * @param fi	Champ en question
 */
function show_modified_tip(fi) {
	show_tip(fi, 'span.info-modified', true);
}

/**
 * Fait apparaitre un message à côté d'un champ qui vient d'être modifié (mon profil).
 *
 * @param fi	Champ en question
 */
function show_invalid_tip(fi) {
	show_tip(fi, 'span.info-invalid', false);
}

/**
 * Effectue le regex pour les fonctions get_username et get_user_id.
 *
 * @param id	ID
 */
function get_ui(id) {
	return id.match(/un-([A-Za-z0-9_]+)-id-(\d+)$/);
}

/**
 * Obtenir le nom d'utilisateur d'après un ID.
 *
 * @param id		ID
 */
function get_username(id) {
	return get_ui(id)[1];
}

/**
 * Obtenir l'ID d'utilisateur d'après un ID.
 *
 * @param id		ID
 */
function get_user_id(id) {
	return get_ui(id)[2];
}

/**
 * Montre/masque mes favoris (ajout de dette).
 *
 * @param action		0 pour masquer, 1 pour montrer, 2 pour permuter
 */
function hst_fav_users_box(action) {
	switch (action) {
		case 0:
		$('div#fav-users-box').slideUp(300);
		break;
		
		case 1:
		$('div#fav-users-box').slideDown(300);
		break;
		
		case 2:
		$('div#fav-users-box').slideToggle(300);
		break;
	}
}

/**
 * Ouvre la fenêtre de recherche d'utilisateur assigne comme valeur à un
 * élément le nom d'utilisateur choisi.
 *
 * @param elem		Élément auquel assigner l'utilisateur choisi (non modifié si rien n'est choisie)
 * @param func		Fonction de rappel (après la fermeture de la fenêtre)
 */
function pop_search_user_window(elem, func) {
	// réinitialiser les éléments
	$('#search-user-window input').val('');
	$('#search-user-window div.results').html('<p class="empty">aucun résultat!</p>');
	
	// fenêtre ColorBox
	$.colorbox({
		inline: true,
		href: '#search-user-window',
		width: '500px',
		onComplete: function() {
			$('#search-user-window input').keyup(function() {
				var val = encodeURIComponent($('#search-user-window input').val());
				ajax_load($('#search-user-window div.results').get(0), 'actions/a_search_user.php?s=' + val, false, function() {
					$('a[id^="su-un-"]').click(function() {
						var id = $(this).attr('id').match(/su-un-([A-Za-z0-9_]+)-id-(\d+)/);
						if (id) {
							$(elem).val(id[1]);
							$('#search-user-window').colorbox.close();
							func();
						}
						
						return false;
					});
				});
			});
			$('#search-user-window input').focus();
		}
	});
	
}

/**
 * Crée une DataTable à partir de définitions de colonnes particulières.
 *
 * @param obj		Objet jQuery
 * @param sort		Tris initiaux
 * @param col_defs	Définitions de colonnes
 */
function create_datatable(obj, sort, col_defs) {
	$(obj).dataTable({
		bPaginate: false,
		bInfo: false,
		oLanguage: {
			sSearch: 'rechercher',
			sInfoEmpty: 'aucune entrée',
			sZeroRecords: 'aucune entrée trouvée...'
		},
		aaSorting: sort,
		aoColumnDefs: col_defs
	});
}

/**
 * Active le spinner en attendant la fin de la requête AJAX.
 */
function ajax_spinner_on() {
	$.blockUI({
		css: {
			border: 'none',
			background: 'transparent'
		},
		message: '<img src="res/images/ajax-loader.gif" alt="" />',
		fadeIn: 0,
		showOverlay: false
	});
}

/**
 * Désactive le spinner après une requête AJAX.
 */
function ajax_spinner_off() {
	$.unblockUI({
		fadeOut: 0
	});
}