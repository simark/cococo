<h2>administration</h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">utilisateurs</a></li>
	</ul>
	<div id="tabs-1">
		<div id="admin-users">
		<?php
			$txr = $g_be_um->tx_get_users();
			if ($txr->err !== CommonManager::INFO_TX_STARTED || is_null($txr->content)) {
				exit(1);
			} else { ?>
				<table class="results">
					<thead>
						<th>utilisateur</th>
						<th>nom complet</th>
						<th>création</th>
						<th>actions</th>
					</thead>
					<tbody>
					<?php
						$my_id = $g_be_session->get_user()->id;
						foreach ($txr->content as $vo) {
							$sf_vo = $vo->get_html_safe_copy();
							$fn = sprintf('%s, %s', $sf_vo->last_name, $sf_vo->first_name);
							$username = $sf_vo->username;
							if ($vo->id == $my_id) {
								$username = sprintf('<strong>%s</strong>', $username);
							}
							$dc = $vo->date_creation->format("Y/m/d H:i:s");
							$ia = $vo->is_active;
							$is_admin = in_array(3, $vo->groups);
							$actions = array(
								sprintf('<a href="?p=modify_user&id=%d" title="modifier"><img src="res/images/icons/page_white_edit.png" alt="modifier" /></a>', $vo->id),
								sprintf('<a href="actions/r_delete_user.php?id=%d" title="supprimer"><img src="res/images/icons/cross.png" alt="supprimer" /></a>', $vo->id),
								sprintf('<a href="actions/r_activate_user.php?id=%d&state=%s" title="%s"><img src="res/images/icons/%s.png" alt="%s" /></a>',
									$vo->id,
									$ia ? '0' : '1',
									$ia ? 'désactiver' : 'activer',
									$ia ? 'status_online' : 'status_offline',
									$ia ? 'désactiver' : 'activer'),
								sprintf('<a href="actions/r_admin_user.php?id=%d&state=%s" title="%s"><img src="res/images/icons/%s.png" alt="%s" /></a>',
									$vo->id,
									$is_admin ? '0' : '1',
									$is_admin ? 'rendre régulier' : 'rendre administrateur',
									$is_admin ? 'user_red' : 'user_green',
									$is_admin ? 'rendre régulier' : 'rendre administrateur')
							);
							$acts = implode("&nbsp;", $actions);
							echo "<tr>";
							printf('<td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
								$username, $fn, $dc, $acts);
							echo "</tr>";
						}
					?>
					</tbody>
				</table>
			<?php }
		?>
		</div>
	</div>
</div>