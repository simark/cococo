<div class="struct struct-body">
	<div class="struct-inner-content">
		<h2 class="first">mes favoris</h2>
		<p>
		Vos <strong>favoris</strong> sont un ensemble d'utilisateurs facilement accessibles pour l'ajout de
		dettes et d'autres opérations sur le système.
		</p>
	</div>
</div>

<div class="struct struct-ban-blue-top"></div>
<div class="struct struct-ban-blue-body">
	<div class="struct-ban-blue-inner-content">
		<h2>ajouter un utilisateur à mes favoris</h2>
		<form id="form-adddebt" action="" class="kf">
			<table>
				<tbody>
					<tr>
						<td class="infos">
							<label>autre utilisateur</label><br />
							<small>nom d'utilisateur</small>
						</td>
						<td class="fi">
							<input type="text" class="text-input init-focus" name="username" /><button class="button" id="add-user-to-favs">ajouter</button><button class="button" id="search-user">rechercher</button>
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
