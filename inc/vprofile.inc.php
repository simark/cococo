<?php
	$s_leaves = ($g_theme == 'leaves') ? ' selected="selected" ' : '';
	$s_urban = ($g_theme == 'urban') ? ' selected="selected" ' : '';
?>
<h2>mon profile de visiteur</h2>

<p>
Le formulaire suivant vous permet de modifier certains paramètres de votre
profil de visiteur. Ces options seront écrasées par celles de votre profil
d'utilisateur si vous vous connecté.
</p>

<form action="actions/r_update_vprofile.php" method="post">
	<p>
		<label><strong>thème</strong></label>
		<select class="select-input" name="theme">
			<option value="leaves" <?php echo $s_leaves; ?>>nature</option>
			<option value="urban" <?php echo $s_urban; ?>>urbain</option>
		</select>
	</p>
	<p>
		<label><strong>soumission/réinitialisation</strong></label><br />
		<input class="submit-input" type="submit" value="soumission" />&nbsp;<input class="submit-input" type="reset" value="réinitialisation" />
	</p>
</form>
