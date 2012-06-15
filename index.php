<?php
	/* requis */
	require_once 'config.php';	// configuration du front-end
	require_once 'be/init.php';	// initialisation du back-end
	require_once 'lang/lang.php'; // locale
	
	/* clef de la page */
	$g_page_key = get_page_key($g_config['valid_pages']);
	
	// locale (cookies if not logged in, otherwise from user's settings)
	$g_locale = $g_config['valid_locales'][0];
	if ($g_be_session->is_user_logged()) {
		$g_locale = $g_be_user->locale->lang;
	} else if (isset($_COOKIE['locale'])) {
		$cl = $_COOKIE['locale'];
		if (in_array($cl, $g_config['valid_locales'])) {
			$g_locale = $cl;
		}
	}

	/* titre de la page */
	$g_title = _T($g_config['valid_pages'][$g_page_key]);
	
	/* suffixe du titre */
	$g_title .= $g_config['title_suffix'];
	
	/* page à inclure */
	$g_inc = './inc/'. $g_config['valid_pages'][$g_page_key] . '.inc.php';
	
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
					printf('<div id="nav-me">%s<span class="quit">(<a href="actions/r_logout.php">%s</a>)</span></div>',
						$g_be_user->get_full_name(), _T('logout'));
						
					$menu_items = array(
						'cococo' => _T('my-cococo'),
						'adddebt' => _T('add-debt'),
						'favs' => _T('my-friends'),
						'profile' => _T('my-profile')
					);
				} else {
					echo '<div id="nav-me">';
					$good = array();
					foreach ($g_config['valid_locales'] as $locale) {
						if ($g_locale != $locale) {
							$good[] = sprintf('<a href="./actions/r_set_cookies_locale.php?locale=%s">%s</a>',
								$locale, $locale);
						}
					}
					echo implode('&nbsp;/&nbsp;', $good);
					echo '</div>';

					$menu_items = array(
						'signup' => _T('signup')
					);
				}
			?>
			<div id="nav-items">
			<?php
				foreach ($menu_items as $k => $item) {
					$add_class = ($k == $g_page_key) ? " nav-item-selected " : "";
					$sf_item = hs($item);
					printf('<span class="nav-item %s"><a href="?p=%s" title="%s">%s</a></span>',
						$add_class, $k, $k, $sf_item);
				}
			?>
			</div>
		</div>
		
		<!-- structure -->
		<div class="struct-container">
			<!-- haut -->
			<div class="struct struct-top"></div>
			<div class="struct struct-body struct-body-spacer"></div>
			
			<!-- contenu dynamique -->
			<?php
				// messages spéciaux
				if (isset($_GET['smsg'])) {
					switch ($_GET['smsg']) {
						case 'timeout':
						$c = Config::instance();
						$exp = $c->get("sess_exp");
						$msg = _T('error-session-expired', array('exp' => $exp));
						break;
						
						case 'invalid_signup':
						$msg = _T('error-invalid-signup');
						break;
						
						case 'invalid_modify_user':
						$msg = _T('error-invalid-modify-user');
						break;
						
						case 'invalid_login':
						$msg = _T('error-invalid-login');
						break;
						
						case 'invalid_add_debt':
						$msg = _T('error-invalid-add-debt');
						break;
						
						case 'inactive_user':
						$msg = _T('error-inactive-user');
						break;
						
						case 'invalid_avatar':
						$msg = _T('error-invalid-avatar');
						break;
						
						case 'invalid_password':
						$msg = _T('error-invalid-password');
						break;
					}
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
					<p><?php T('page-not-found'); ?></p>
				<?php }
			?>
			
			<!-- bas -->
			<div class="struct struct-body struct-body-spacer"></div>
			<div class="struct struct-bottom"></div>
		</div>
	</div>
</body>
</html>
