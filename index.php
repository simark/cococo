<?php
	/* requis */
	require_once("config.php");	// configuration du front-end
	require_once("be/init.php");	// initialisation du back-end
	
	/* clef de la page */
	$g_page_key = get_page_key($g_config['valid_pages']);
	
	/* titre de la page */
	$g_title = $g_config['valid_pages'][$g_page_key]['title'];
	
	/* suffixe du titre */
	$g_title .= $g_config['title_suffix'];
	
	/* page à inclure */
	$g_inc = "./inc/$g_page_key.inc.php";
	
	/* afficher le menu */
	function get_nav_item($key, $text) {
		global $g_page_key;
		
		if ($key == $g_page_key) {
			return sprintf('<span class="nav-item nav-item-selected">%s</span>', $text);
		} else {
			return sprintf('<span class="nav-item"><a href="./?p=%s">%s</a></span>', $key, $text);
		}
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo $g_title; ?></title>
	
	<!-- polices Web -->
	<link href='http://fonts.googleapis.com/css?family=Anton' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Kelly+Slab' rel='stylesheet' type='text/css'>
	
	<!-- visuel -->
	<link rel="stylesheet" type="text/css" href="res/css/master.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="res/css/jquery-ui-1.8.16.custom.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="res/css/colorbox.css" media="screen" />
	<link rel="icon" href="res/ico/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="res/ico/favicon.ico" type="image/x-icon" />
	
	<!-- scripts JS -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="res/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="res/js/spin.min.js"></script>
	<script type="text/javascript" src="res/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="res/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="res/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="res/js/jquery.colorbox-min.js"></script>
	<script type="text/javascript" src="res/js/master.js"></script>
	<script type="text/javascript" src="res/js/log4420_tp3.js"></script>
</head>
<body>
	<!-- conteneur principal -->
	<div id="content-main">
		<!-- navigation/logo -->
		<div id="nav">
			<div id="nav-logo"></div>
			<?php
				if ($g_be_session->is_user_logged()) {
					printf('<div id="nav-me">%s<span class="quit">(<a href="actions/r_logout.php">quitter</a>)</span></div>',
						$g_be_user->get_full_name());
				}
			?>
			<?php
				// paramètres
				$logged = ($g_be_session->is_user_logged()) ? 1 : 0;
				
				// chargement des documents
				$xsl = new DOMDocument();
				$xsl->load("res/xml_xsl/main_nav.xsl");
				$xml = new DOMDocument();
				$xml->load("res/xml_xsl/main_nav.xml");
				
				$proc = new XSLTProcessor();
				$proc->setParameter('', 'logged', $logged);
				$proc->setParameter('', 'selectedkey', $g_page_key);
				$proc->importStylesheet($xsl);
				echo $proc->transformToXML($xml);
			?>
		</div>
		
		<!-- structure -->
		<div class="struct-container">
			<!-- haut -->
			<div class="struct struct-top"></div>
			<div class="struct struct-body struct-body-spacer"></div>
			
			<!-- contenu dynamique -->
			<?php
				// messages spéciaux
				switch ($_GET['smsg']) {
					case 'timeout':
					$c = Config::instance();
					$exp = $c->get("sess_exp");
					$msg = "votre session a expiré après $exp secondes d'inactivité...";
					break;
					
					case 'invalid_signup':
					$msg = "il y a des champs invalides dans votre formulaire d'inscription...";
					break;
					
					case 'invalid_modify_user':
					$msg = "il y a des champs invalides dans votre formulaire de modification...";
					break;
					
					case 'invalid_login':
					$msg = "mauvais nom d'utilisateur ou mot de passe...";
					break;
					
					case 'invalid_add_debt':
					$msg = "il y a des champs invalides dans votre formulaire d'ajout de dette...";
					break;
					
					case 'inactive_user':
					$msg = "votre utilisateur n'est pas actif; veuillez consulter un administrateur...";
					break;
					
					case 'invalid_avatar':
					$msg = "avatar incompatible ou trop grand...";
					break;
					
					case 'invalid_password':
					$msg = "nouveau mot de passe non valide...";
					break;
				}
				if (isset($msg)) {
					$msg = hs($msg); ?>
					<div class="struct struct-body">
						<div class="struct-inner-content">
							<div id="info-box">
								<?php echo $msg; ?>
							</div>
						</div>
					</div>
					<?php
				}
				
				// inclure le contenu d'un fichier s'il existe
				if (file_exists($g_inc)) {
					include_once($g_inc);
				} else { ?>
					<p>Page introuvable...</p>
				<?php }
			?>
			<div class="struct struct-body struct-body-spacer"></div>
			<div class="struct struct-ban-blue-top"></div>
			<div class="struct struct-ban-blue-body">
				<div class="struct-ban-blue-inner-content">
					<h2>Quelques nouvelles importantes...</h2>
					<?php
						// obtenir le feed RSS de The Onion
						$rss_xml = file_get_contents("http://feeds.theonion.com/theonion/daily");
						
						// chargement des documents
						$xsl = new DOMDocument();
						$xsl->load("res/xml_xsl/the_onion_rss.xsl");
						$xml = new DOMDocument();
						$xml->loadXML($rss_xml);
						
						$proc = new XSLTProcessor();
						$proc->importStylesheet($xsl);
						echo $proc->transformToXML($xml);
					?>
				</div>
			</div>
			<!-- bas -->
			<div class="struct struct-body struct-body-spacer"></div>
			<div class="struct struct-bottom"></div>
		</div>
	</div>
	<div id="ajax-load">
		hello you donkey lol
	</div>
	<div style="display: none;">
		<div id="search-user-window">
			<h2>recherche d'utilisateur</h2>
			<p>
				<input class="text-input" type="text" />
			</p>
			<div class="results">
				<p class="empty">aucun résultat!</p>
			</div>
		</div>
	</div>
	<div id="zuser-window">
	</div>
</body>
</html>