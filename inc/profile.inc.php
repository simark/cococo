<?php
	$vo = $g_be_user;
	$sf_vo = $vo->get_html_safe_copy();
	$uid = $vo->get_unique_id();
	$first_name = $sf_vo->first_name;
	$last_name = $sf_vo->last_name;
	$email = $sf_vo->email;
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
