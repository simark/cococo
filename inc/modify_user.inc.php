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
	$bd_year = $vo->birthday->format("Y");
	$bd_month = intval($vo->birthday->format("m"));
	$bd_day = intval($vo->birthday->format("d"));
	$gender = $vo->gender;
	$checked_male = ($vo->gender == 'male') ? ' checked="checked" ' : '';
	$checked_female = ($vo->gender == 'female') ? ' checked="checked" ' : '';
	$s_leaves = ($vo->theme == 'leaves') ? ' selected="selected" ' : '';
	$s_urban = ($vo->theme == 'urban') ? ' selected="selected" ' : '';
	$username = $sf_vo->username;
	$admin_mode = $g_be_user->has_feature("ADMIN");
	if ($admin_mode) {
		echo "<h2>administration : modification d'un utilisateur</h2>";
		$mode = 'admin';
	} else {
		echo "<h2>modifier mes informations</h2>";
		$mode = 'reg';
	}
?>
<form action="actions/r_modify_user.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
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
		<label><strong>date de naissance</strong></label><br />
		<select class="select-input" name="bd_day">
			<?php
				for ($i = 1; $i <= 31; ++$i) {
					$z = ($i == $bd_day) ? ' selected="selected" ' : '';
					$add = ($i == 1) ? "er" : "";
					echo "<option value=\"$i\" $z>$i$add</option>";
				}
			?>
		</select>
		<select class="select-input" name="bd_month">
			<?php
				foreach (get_month_names() as $k => $v) {
					++$k;
					$z = ($k == $bd_month) ? ' selected="selected" ' : '';
					echo "<option value=\"$k\" $z>$v</option>";
				}
			?>
		</select>
		<select class="select-input" name="bd_year">
			<?php
				for ($i = 1900; $i <= 2010; ++$i) {
					$z = ($i == $bd_year) ? ' selected="selected" ' : '';
					echo "<option $add value=\"$i\" $z>$i</option>";
				}
			?>
		</select>
	</p>
	<p>
		<label><strong>sexe</strong></label><br />
		<input type="radio" name="gender" value="male" <?php echo $checked_male; ?> />homme&nbsp;<input type="radio" name="gender" value="female" <?php echo $checked_female; ?> />femme
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
		<label><strong>thème</strong></label>
		<select class="select-input" name="theme">
			<option value="leaves" <?php echo $s_leaves; ?>>nature</option>
			<option value="urban" <?php echo $s_urban; ?>>urbain</option>
		</select>
	</p>
	<p>
		<label><strong>soumission/réinitialisation</strong></label><br />
		<input class="submit-input" type="submit" value="modifier" />&nbsp;<input class="submit-input" type="reset" value="réinitialisation" />
	</p>
</form>