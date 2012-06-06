<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first"><?php T('my-friends'); ?></h2>
		<p>
		<?php T('my-friends-desc'); ?>
		</p>
	</div>
</div>

<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content">
		<h2><?php T('add-user-to-friends'); ?></h2>
		<form id="form-adddebt" action="" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label><?php T('other-person'); ?></label><br />
							<small><?php T('username'); ?></small>
						</td>
						<td class="fi">
							<input type="text" class="text-input init-focus" name="username" />
							<button class="button" id="add-user-to-favs"><?php T('add-user'); ?></button>
							<button class="button" id="search-user"><?php T('search-user'); ?></button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<div class="struct struct-body struct-body-spacer"></div>
<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content">
		<div id="fav-users-full">
			<?php
				require_once("contents/favs_favs.php");
				
				echo content_favs_favs();
			?>
		</div>
	</div>
</div>
