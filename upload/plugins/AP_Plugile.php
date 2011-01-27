<?php
/***********************************************************************

  Copyright (C) 2007  BN (bnmaster@la-bnbox.info)

  This file is part of PunBB.

  PunBB is free software; you can redistribute it and/or modify it
  under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  PunBB is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston,
  MA  02111-1307  USA

************************************************************************/

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);
define('PLUGIN_VERSION', $pun_config['o_plugile_version']);
define('PLUGIN_URL', 'admin_loader.php?plugin=AP_Plugile.php');

// Load the plugile language files
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/plugile.php'))
	require PUN_ROOT.'lang/'.$pun_user['language'].'/plugile.php';
else
	require PUN_ROOT.'lang/English/plugile.php';

// Installation
if (isset($_POST['update']))
{
if (isset($pun_config['o_plugile_version'])) { $reinstall=1; }
	$plugile_version = '2.0';
	$plugile_menu = '1';
	$plugile_menu_name = 'Plugile';

	$result = $db->query('REPLACE INTO '.$db->prefix.'config (conf_name, conf_value) VALUES ("o_plugile_version","'.$plugile_version.'"), ("o_plugile_menu","'.$plugile_menu.'"), ("o_plugile_menu_name","'.$plugile_menu_name.'")') or error('Impossible d\'ajouter ou de remplacer "o_plugile_version", "o_plugile_menu" et "o_plugile_menu_name" de la table config', __FILE__, __LINE__, $db->error());
	
	// Regenerate the config cache
	require_once PUN_ROOT.'include/cache.php';
	generate_config_cache();
	
	if (isset($reinstall))
		redirect(PLUGIN_URL, $lang_plugile['Plugile reinstall redirect']);
	else
		redirect(PLUGIN_URL, $lang_plugile['Plugile install redirect']);
}

// Désinstallation
if (isset($_POST['delete']))
{
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name="o_plugile_version" OR conf_name="o_plugile_menu" OR conf_name="o_plugile_menu_name"') or error('Impossible de supprimer "o_plugile_version", "o_plugile_menu" et "o_plugile_menu_name" de la table config', __FILE__, __LINE__, $db->error());
	
	// Regenerate the config cache
	require_once PUN_ROOT.'include/cache.php';
	generate_config_cache();

	redirect(PLUGIN_URL, $lang_plugile['Plugile uninstall redirect']);
}

// Mise à jour de la configuration de Plugile
if (isset($_POST['saveconfig']) AND isset($pun_config['o_plugile_version']))
{
	$db->query('REPLACE INTO '.$db->prefix.'config (conf_name, conf_value) VALUES ("o_plugile_menu","'.intval($_POST['menu']).'"), ("o_plugile_menu_name","'.pun_htmlspecialchars($_POST['plugile_menu_name']).'")') or error('Impossible d\'ajouter ou de remplacer "o_plugile_menu" et "o_plugile_menu_name" de la table config', __FILE__, __LINE__, $db->error());

	// Regenerate the config cache
	require_once PUN_ROOT.'include/cache.php';
	generate_config_cache();

	redirect(PLUGIN_URL, $lang_plugile['Config update redirect']);
}
else
{
	// Display the admin navigation menu
	generate_admin_menu($plugin);

?>
	<div id="exampleplugin" class="blockform">
		<h2><span><?php echo $lang_plugile['Plugile'] ?> <?php echo PLUGIN_VERSION; ?></span></h2>
		<div class="box">
				<form id="plugileinstall" action="<?php echo PLUGIN_URL; ?>" method="post">
				<div class="inform">
					<p><?php echo $lang_plugile['plugin_desc'] ?></p>
					<fieldset>
						<legend><?php echo $lang_plugile['Install'] ?></legend>
						<p><?php echo $lang_plugile['Plugile install infos'] ?></p>
						<p><input type="submit" name="update" value="<?php echo $lang_plugile['Install'] ?>" /></p>
					</fieldset>
					<br /><br />
					<fieldset>
						<legend><?php echo $lang_plugile['Uninstall'] ?></legend>
					<p><?php echo $lang_plugile['Uninstall infos'] ?></p>
					<p><input type="submit" name="delete" value="<?php echo $lang_plugile['Uninstall'] ?>" /></p>
					</fieldset>
				</div>
				</form>
		</div>

		<h2 class="block2"><span><?php echo $lang_plugile['Configuration'] ?></span></h2>
		<div class="box">
			<form id="plugileconfig" method="post" action="<?php echo PLUGIN_URL; ?>">
				<p class="submitend"><input type="submit" name="saveconfig" value="<?php echo $lang_common['Submit']; ?>" /></p>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang_plugile['settings'] ?></legend>
						<div class="infldset">
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_plugile['Menu'] ?></th>
								<td>
									<input type="radio" name="menu" value="1"<?php if ($pun_config['o_plugile_menu'] == '1') echo ' checked="checked"' ?> /> <strong><?php echo $lang_plugile['Yes'] ?></label>&#160;&#160;&#160;<input type="radio" name="menu" value="0"<?php if ($pun_config['o_plugile_menu'] == '0') echo ' checked="checked"' ?> /> <strong><?php echo $lang_plugile['No'] ?></strong>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_plugile['Menu title'] ?></th>
								<td>
									<input type="text" name="plugile_menu_name" size="25" tabindex="1" value="<?php echo $pun_config['o_plugile_menu_name'] ?>" />
								</td>
							</tr>
						</table>
						</div>
					</fieldset>
					<p class="submitend"><input type="submit" name="saveconfig" value="<?php echo $lang_common['Submit']; ?>" /></p>
				</div>
			</form>
		</div>
	</div>
<?php

}

// Note that the script just ends here. The footer will be included by admin_loader.php.
