<?php
	$txr = $g_be_um->tx_get_user($_GET['id']);
	if ($txr->err !== CommonManager::INFO_TX_STARTED || is_null($txr->content)) {
		exit(1);
	}
	$vo = $txr->content;
	$sf_vo = $vo->get_html_safe_copy();
	$id = $vo->id;
	$first_name = $sf_vo->first_name;
	$last_name = $sf_vo->last_name;
	$email = $sf_vo->email;
	$username = $sf_vo->username;
	
	echo "<h2>modifier mes informations</h2>";
?>
<form action="actions/r_modify_user.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<p>
		<label><strong>prénom</strong></label><br />
		<input type="text" class="text-input" name="first_name" value="<?php echo $first_name; ?>" />
	</p>
	<p>
		<label><strong>nom</strong></label><br />
		<input type="text" class="text-input" name="last_name" value="<?php echo $last_name; ?>" />
	</p>
	<p>
		<label><strong>courriel</strong></label><br />
		<input type="text" class="text-input" name="email" value="<?php echo $email; ?>" />
	</p>
	<p>
		<label><strong>utilisateur</strong></label><br />
		<input type="text" class="text-input" name="username" value="<?php echo $username; ?>" /><br />
		<small>caractères alphanumériques seulement</small>
	</p>
	<p>
		<label><strong>nouveau mot de passe</strong></label><br />
		<input type="password" class="text-input" name="passwd" /><br />
		<small>laisser vide pour ne pas modifier</small>
	</p>
	<p>
		<label><strong>confirmation du nouveau mot de passe</strong></label><br />
		<input type="password" class="text-input" name="passwd_conf" /><br />
		<small>laisser vide pour ne pas modifier</small>
	</p>
	<p>
		<label><strong>soumission/réinitialisation</strong></label><br />
		<input class="submit-input" type="submit" value="modifier" />&nbsp;<input class="submit-input" type="reset" value="réinitialisation" />
	</p>
</form>
