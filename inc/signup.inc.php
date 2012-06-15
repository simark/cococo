<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first"><?php T('signup'); ?></h2>
		<p><?php T_('signup-desc'); ?></p>
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
							<label><?php T('first-name'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input init-focus" name="first_name" /><span class="info info-invalid">prénom non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('last-name'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="last_name" /><span class="info info-invalid">nom non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('email'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="email" /><span class="info info-invalid">courriel non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('language'); ?></label>
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
							<label><?php T('username'); ?></label><br />
							<small><?php T('alphanum-only'); ?></small>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="username" /><span class="info info-invalid">utilisateur non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('password'); ?></label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="passwd" /><span class="info info-invalid">mot de passe non valide...</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('password-again'); ?></label>
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
							<input class="button" type="submit" value="<?php T('submit'); ?>" />&nbsp;<input class="button" type="reset" value="<?php T('reset'); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>