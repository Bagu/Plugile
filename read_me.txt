##
##
##           Mod title:  Plugile
##
##         Mod version:  2.1
##      Works on PunBB:  1.4.x
##        Release date:  14/06/2008
##           Date 1.2 :  28/03/2007
##           Date 1.1 :  17/03/2007
##           Date 1.0 :  02/03/2007
##
##              Author:  BN [http://la-bnbox.info] and Pandark [http://pandark.free.fr]
##
##         Description:  This mod add a plugin system in profile page.
##                       It works like admins and moderators plugins but for members.
##
##      Affected files:  include/functions.php
##                       profile.php
##
##          Affects DB:  Yes
##
##
##          DISCLAIMER:  Please note that "mods" are not officially supported by
##                       PunBB. Installation of this modification is done at your
##                       own risk. Backup your forum database and any and all
##                       applicable files before proceeding.
##

#
#---------[ 1. UPLOAD FILES ]-------------------------------------
#

lang/LANG/plugile.php in lang/LANG
upload/plugins/AP_Plugile.php in /plugins/
upload/plugiles/UP_Exemple.php in /plugiles/
upload/plugiles/UPP_Exemple.php in /plugiles/ 
(UP_Exemple and UPP_Exemple are just examples, delete them after test)

#
#---------[ 2. OPEN ]-------------------------------------------------------
#

include/functions.php


#
#---------[ 3. FIND ]-----------------------------------------------------
#

//
// Display the profile navigation menu
//
function generate_profile_menu($page = '')
{
	global $lang_profile, $pun_config, $pun_user, $id;

?>
<div id="profile" class="block2col">
	<div class="blockmenu">
		<h2><span><?php echo $lang_profile['Profile menu'] ?></span></h2>
		<div class="box">
			<div class="inbox">
				<ul>
					<li<?php if ($page == 'essentials') echo ' class="isactive"'; ?>><a href="profile.php?section=essentials&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section essentials'] ?></a></li>
					<li<?php if ($page == 'personal') echo ' class="isactive"'; ?>><a href="profile.php?section=personal&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section personal'] ?></a></li>
					<li<?php if ($page == 'messaging') echo ' class="isactive"'; ?>><a href="profile.php?section=messaging&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section messaging'] ?></a></li>
<?php if ($pun_config['o_avatars'] == '1' || $pun_config['o_signatures'] == '1'): ?>					<li<?php if ($page == 'personality') echo ' class="isactive"'; ?>><a href="profile.php?section=personality&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section personality'] ?></a></li>
<?php endif; ?>					<li<?php if ($page == 'display') echo ' class="isactive"'; ?>><a href="profile.php?section=display&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section display'] ?></a></li>
					<li<?php if ($page == 'privacy') echo ' class="isactive"'; ?>><a href="profile.php?section=privacy&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section privacy'] ?></a></li>
<?php if ($pun_user['g_id'] == PUN_ADMIN || ($pun_user['g_moderator'] == '1' && $pun_user['g_mod_ban_users'] == '1')): ?>					<li<?php if ($page == 'admin') echo ' class="isactive"'; ?>><a href="profile.php?section=admin&amp;id=<?php echo $id ?>"><?php echo $lang_profile['Section admin'] ?></a></li>
<?php endif; ?>				</ul>
			</div>
		</div>
	</div>
<?php

}

#
#---------[ 4. INSIDE, FIND ]-----------------------------------------
#

<?php endif; ?>				</ul>
			</div>
		</div>
	</div>
<?php

}

#
#---------[ 5. REPLACE WITH ]-----------------------------------------
#

<?php endif; ?>
					<?php
					// See if there are any plugiles
					$plugiles = array();
					$d = dir(PUN_ROOT.'plugiles');
					while (($entry = $d->read()) !== false)
					{
						$prefix = substr($entry, 0, strpos($entry, '_'));
						$suffix = substr($entry, strlen($entry) - 4);

						// UP <=> User Plugile (everyone can see it) - UPP <=> User Private Plugile (only the member et the admins can see it)
						if ($suffix == '.php' && ($prefix == 'UP' || $prefix == 'UPP'))
							$plugiles[] = array(substr(substr($entry, strpos($entry, '_') + 1), 0, -4), $entry);
					}
					$d->close();

					// Did we find any plugiles?
					if (!empty($plugiles))
					{
						if($pun_config['o_plugile_menu'] == '1')
						{
										?>
									</ul>
								</div>
							</div>
							

							<h2><span><?php echo $pun_config['o_plugile_menu_name'] ?></span></h2>
							<div class="box">
								<div class="inbox">
									<ul>
										<?php
						}
					while (list(, $cur_plugile) = @each($plugiles))
						echo "\t\t\t\t\t".'<li'.(($page == $cur_plugile[1]) ? ' class="isactive"' : '').'><a href="profile.php?plugin='.$cur_plugile[1].'&amp;id='.$id.'">'.str_replace('_', ' ', $cur_plugile[0]).'</a></li>'."\n";
					?>
				</ul>
			</div>
		</div>
	</div>
<?php

	}
}

#
#---------[ 6. OPEN ]-------------------------------------------------------
#

profile.php


#
#---------[ 7. FIND ]-----------------------------------
#

<?php endif; ?>			<div class="inform">
				<fieldset>
				<legend><?php echo $lang_profile['User activity'] ?></legend>
					<div class="infldset">
						<dl>
							<?php echo implode("\n\t\t\t\t\t\t\t", $user_activity)."\n" ?>
						</dl>
						<div class="clearer"></div>
					</div>
				</fieldset>
			</div>


#
#---------[ 8. AFTER ADD ]------------------------------------------
#

			<?php
			// See if there are any plugiles
			$plugiles = array();
			$d = dir(PUN_ROOT.'plugiles');
			while (($entry = $d->read()) !== false)
			{
				$prefix = substr($entry, 0, strpos($entry, '_'));
				$suffix = substr($entry, strlen($entry) - 4);

				if ($suffix == '.php' && $prefix == 'UP')
					$plugiles[] = array(substr(substr($entry, strpos($entry, '_') + 1), 0, -4), $entry);
			}
			$d->close();

			// Did we find any plugiles?
			if (!empty($plugiles))
			{
				while (list(, $cur_plugile) = @each($plugiles))
				{
					// Make sure the file actually exists
					if (!file_exists(PUN_ROOT.'plugiles/'.$cur_plugile[1]))
						message('There is no plugile \''.$cur_plugile[0].'\' in /plugile.');
					// Construct REQUEST_URI if it isn't set
					if (!isset($_SERVER['REQUEST_URI']))
						$_SERVER['REQUEST_URI'] = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '').'?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
					?>
					<div class="inform">
						<fieldset>
						<legend><?php echo str_replace('_', ' ', $cur_plugile[0]); ?></legend>
							<div class="infldset">
							<?php
							include PUN_ROOT.'plugiles/'.$cur_plugile[1];
							?>
							<div class="clearer"></div>
							</div>
						</fieldset>
					</div>
					<?php
					if (!defined('PUN_PLUGIN_LOADED'))
						message('Error while loading \''.$cur_plugile[0].'\' plugile.');
				}
			}
			?>

#
#---------[ 9. FIND ]-----------------------------------
#

if (!$section || $section == 'essentials')


#
#---------[ 10. REPLACE BY ]------------------------------------------
#

if ((!$section AND !$_GET['plugin']) || $section == 'essentials')


#
#---------[ 11. FIND (at the end) ]------------------------------------------------
#

	}
	else
		message($lang_common['Bad request']);

?>
	<div class="clearer"></div>
</div>
<?php

	require PUN_ROOT.'footer.php';
}

#
#---------[ 12. BEFORE ADD ]-------------------------------------
#
	elseif(!isset($section) AND isset($_GET['plugin']))
	{
		// The plugile to load should be supplied via GET
		$plugile = isset($_GET['plugin']) ? $_GET['plugin'] : '';
		if (!preg_match('/^UP?P_(\w*?)\.php$/i', $plugile))
					message($lang_common['Bad request']);

		$prefix = substr($plugile, 0, strpos($plugile, '_'));
		$suffix = substr($plugile, strlen($plugile) - 4);
		// UP <=> User Plugile (visible par tous) - UPP <=> User Private Plugile (visible par le membre et les administrateurs)
		if ($suffix == '.php' && ($prefix == 'UP' || $prefix == 'UPP'))
			$plugiles[] = array(substr(substr($plugile, strpos($plugile, '_') + 1), 0, -4), $plugile);

		// Make sure the file actually exists
		if (!file_exists(PUN_ROOT.'plugiles/'.$plugile))
					message('There is no plugile \''.$plugile.'\' in /plugile.');

		// Construct REQUEST_URI if it isn't set
		if (!isset($_SERVER['REQUEST_URI']))
			$_SERVER['REQUEST_URI'] = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '').'?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');

		$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_common['Profile'], str_replace('_', ' ', $plugiles[0][0]));
		require PUN_ROOT.'header.php';
		
		// Attempt to load the plugile. We don't use @ here to supress error messages,
		// because if we did and a parse error occurred in the plugile, we would only
		// get the "blank page of death".
		include PUN_ROOT.'plugiles/'.$plugile;
		if (!defined('PUN_PLUGIN_LOADED'))
			message('Error while loading \''.$plugile.'\' plugile.');
	}

#
#---------[ 13. SAVE/UPLOAD ]----------------------------
#

include/functions.php
profile.php


#
#---------[ 14. END AND CONFIGURATION ]----------------------------------------------
#

To end the installation, you must go in administration plugin file. You can modify 
the look too.
To create a new plugile: read example plugin UP_Exemple.php
