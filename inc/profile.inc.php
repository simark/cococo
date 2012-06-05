<?php
	$vo = $g_be_user;
	$sf_vo = $vo->get_html_safe_copy();
	$uid = $vo->get_unique_id();
	$first_name = $sf_vo->first_name;
	$last_name = $sf_vo->last_name;
	$email = $sf_vo->email;
	$bd_year = $vo->birthday->format("Y");
	$bd_month = intval($vo->birthday->format("m"));
	$bd_day = intval($vo->birthday->format("d"));
	$gender = $vo->gender;
	$checked_male = ($vo->gender == 'male') ? ' checked="checked" ' : '';
	$checked_female = ($vo->gender == 'female') ? ' checked="checked" ' : '';
	$locale_id = intval($vo->locale->id);
?>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first">mes informations</h2>
		<p>
		Les champs sont modifiés sur-le-champ lorsque vous cliquez à l'extérieur de ceux-ci.
		</p>
	</div>
</div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content" id="important-thing">
		<form id="form-my-infos" action="" method="post" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label>prénom</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="first_name" value="<?php echo $first_name; ?>" /><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>nom</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="last_name" value="<?php echo $last_name; ?>" /><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>courriel</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="email" value="<?php echo $email; ?>" /><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>date de naissance</label>
						</td>
						<td class="fi">
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
										echo "<option value=\"$i\" $z>$i</option>";
									}
								?>
							</select><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>sexe</label>
						</td>
						<td class="fi">
							<input type="radio" name="gender" value="male" <?php echo $checked_male; ?> /><span class="check_behind">homme</span>&nbsp;<input type="radio" name="gender" value="female" <?php echo $checked_female; ?> /><span class="check_behind">femme</span><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>langue</label>
						</td>
						<td class="fi">
							<select class="select-input" name="id_locale">
								<?php
									$vos = $g_be_lm->get_all_locales();
									foreach ($vos as $locale_vo) {
										if (!in_array($locale_vo->lang, $g_config['valid_locales'])) {
											continue;
										}
										$z = ($locale_vo->id == $locale_id) ? ' selected="selected" ' : '';
										$sf_vo = $locale_vo->get_html_safe_copy();
										printf('<option value="%d" %s>%s</option>', $locale_vo->id, $z, $sf_vo->name);
									}
								?>
							</select><span class="info info-modified">modifié!</span><span class="info info-invalid">valeur non valide...</span>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2>avatar</h2>
	</div>
</div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content" id="important-thing">
		<form id="form-avatar" action="actions/r_upload_avatar.php" method="post" class="kf" enctype="multipart/form-data">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label>avatar actuel</label>
						</td>
						<td class="fi">
							<img src="res/images/avatars/<?php echo $g_be_user->avatar; ?>.png" alt="" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>nouvelle image</label><br />
							<small>sera redimensionnée à 48&nbsp;px</small>
						</td>
						<td class="fi">
							<input type="file" name="file" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label></label>
						</td>
						<td>
							<input class="button" type="submit" value="envoyer" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2>mot de passe</h2>
	</div>
</div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content" id="important-thing">
		<form id="form-avatar" action="actions/r_modify_password.php" method="post" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label>nouveau mot de passe</label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="password" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>nouveau mot de passe (confirmation)</label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="password_conf" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label></label>
						</td>
						<td>
							<input class="button" type="submit" value="modifier" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>