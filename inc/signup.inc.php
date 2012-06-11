<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first">inscription</h2>
		<p>
		Remplissez <em>tous</em> les champs suivants afin de compléter votre inscription à
		<strong>cococo</strong>. Vous pourrez également bientôt vous connecter grâce à votre
		compte Facebook ou Google.
		</p>
	</div>
</div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content" id="important-thing">
		<form id="form-signup" action="actions/r_add_user.php" method="post" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label>prénom</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input init-focus" name="first_name" /><span class="info info-invalid">prénom non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>nom</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="last_name" /><span class="info info-invalid">nom non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>courriel</label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="email" /><span class="info info-invalid">courriel non valide...</span>
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
										$add = ($i == 1) ? "er" : "";
										echo "<option value=\"$i\">$i$add</option>";
									}
								?>
							</select>
							<select class="select-input" name="bd_month">
								<?php
									foreach (get_month_names() as $k => $v) {
										++$k;
										echo "<option value=\"$k\">$v</option>";
									}
								?>
							</select>
							<select class="select-input" name="bd_year">
								<?php
									for ($i = 1900; $i <= 2010; ++$i) {
										$add = ($i == 1988) ? ' selected="selected" ' : "";
										echo "<option $add value=\"$i\">$i</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>sexe</label>
						</td>
						<td class="fi">
							<input type="radio" name="gender" value="male" checked="checked" /><span class="check_behind">homme</span>&nbsp;<input type="radio" name="gender" value="female" /><span class="check_behind">femme</span><span class="info info-invalid">sexe non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>langue</label>
						</td>
						<td class="fi">
							<select class="select-input" name="locale_code">
								<?php
									foreach ($g_config['valid_locales'] as $locale) {
										$z = ($locale == $g_locale) ? ' selected="selected" ' : '';
										printf('<option value="%s" %s>%s</option>', $locale, $z, $locale);
									}
								?>
							</select>
						</td>
					<tr>
						<td class="infos">
							<label>utilisateur</label><br />
							<small>caractères alphanumériques seulement</small>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="username" /><span class="info info-invalid">utilisateur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>mot de passe</label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="passwd" /><span class="info info-invalid">mot de passe non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>confirmation du mot de passe</label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="passwd_conf" /><span class="info info-invalid">mots de passe différents...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label></label>
						</td>
						<td>
							<input class="button" type="submit" value="soumission" />&nbsp;<input class="button" type="reset" value="réinitialisation" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>