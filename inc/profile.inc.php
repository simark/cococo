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
		<h2 class="first"><?php T('my-profile'); ?></h2>
		<p>
		<?php T('my-profile-desc'); ?>
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
							<label><?php T('first-name'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="first_name" value="<?php echo $first_name; ?>" /><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?></span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('last-name'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="last_name" value="<?php echo $last_name; ?>" /><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?>.</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('email'); ?></label>
						</td>
						<td class="fi">
							<input type="text" class="text-input" name="email" value="<?php echo $email; ?>" /><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?>.</span>
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
							</select><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?>.</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label>sexe</label>
						</td>
						<td class="fi">
							<input type="radio" name="gender" value="male" <?php echo $checked_male; ?> /><span class="check_behind">homme</span>&nbsp;<input type="radio" name="gender" value="female" <?php echo $checked_female; ?> /><span class="check_behind">femme</span><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?>.</span>
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('language'); ?></label>
						</td>
						<td class="fi">
							<select class="select-input" name="id_locale">
								<?php
									foreach ($g_config['valid_locales'] as $locale) {
										$z = ($locale == $g_locale) ? ' selected="selected" ' : '';
										printf('<option value="%d" %s>%s</option>', $locale, $z, $locale);
									}
								?>
							</select><span class="info info-modified"><?php T('value-modified'); ?></span><span class="info info-invalid"><?php T('value-invalid'); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2><?php T('avatar'); ?></h2>
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
							<label><?php T('current-avatar'); ?></label>
						</td>
						<td class="fi">
							<img src="res/images/avatars/<?php echo $g_be_user->avatar; ?>.png" alt="" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('new-avatar'); ?></label><br />
							<small><?php T('image-will-be-resized-to-48px'); ?></small>
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
							<input class="button" type="submit" value="<?php T('send'); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2><?php T('password'); ?></h2>
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
							<label><?php T('new-password'); ?></label>
						</td>
						<td class="fi">
							<input type="password" class="text-input" name="password" />
						</td>
					</tr>
					<tr>
						<td class="infos">
							<label><?php T('new-password-confirm'); ?></label>
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
							<input class="button" type="submit" value="<?php T('modify'); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
