<?php
/***********************************************************************

  Copyright (C) 2007  BN (bnmaster@la-bnbox.info)

  This software is free software; you can redistribute it and/or modify it
  under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  This software is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston,
  MA  02111-1307  USA

************************************************************************/

##
##
##  Voici quelques notes intéressantes pour les aspirants auteurs de plugiles :
##
##  1. Si vous voulez afficher un message par l’intermédiaire de la fonction 
##     message(), vous devez le faire avant d’appeler generate_admin_menu($plugile).
##
##  2. Les plugiles sont chargés par profil.php?plugile=UP_nomplugile.php et ne doivent pas être terminés 
##     (par exemple en appelant exit()). Après que le script du plugile ait fini, le 
##     script du chargeur affiche le pied de page, ainsi inutile de vous souciez de cela. 
##     Cependant veuillez noter que terminer un plugile en appelant message() ou 
##     redirect() est très bien.
##
##  3. L’attribut action de toute balise <forme> et l’URL cible pour la fonction 
##     redirect() doit être placé à la valeur de $_SERVER[’REQUEST_URI’]. Cette 
##     URL peut cependant être étendue pour inclure des variables supplémentaires 
##     (comme l’ajout de &foo=bar dans le plugile exemple).
##
##  4. Pour qu'il soit visible par le membre et les administrateurs et modérateurs, votre fichier doit avoir le préfixe : 
##      UPP_ (User Private Plugile).
##      Pour que tous les membres y aient accès, votre fichier doit avoir le préfixe : UP_ (User Plugin)
##
##  5. Dans le cas d'un UP_, il peut être important de bien différencier la partie visible seulement par le membre (et les 
##      administrateurs et modérateurs) de la partie visible par tous. (ceci grâce à des conditions vérifiant l'id ou l'id de 
##      groupe du membre visitant le profil)
##
##  6. Utilisez _ au lieu des espaces dans le nom de fichier.
##
##  7. Tant que les scripts de plugile sont inclus depuis le scripts profil.php 
##     de PunBB, vous avez accès toutes les fonctions et variables globales de PunBB 
##     (par exemple $db, $pun_config, $pun_user etc.).
##
##  8. Faites de votre mieux pour garder l’aspect et l’ergonomie de votre interface 
##     utilisateur de plugiles semblable au reste des scripts de profils. 
##     N’hésitez pas à emprunter le marquage et le code aux scripts de profil pour 
##     l’employer dans vos plugiles.
##
##  9. Les plugiles doivent êtres délivrés sous la licence d’utilisation GNU/GPL ou 
##     une licence compatible. Recopiez le préambule GPL (situé en haut des scripts 
##     de PunBB) dans votre script de plugile et changez l e copyright pour qu’il 
##     corresponde à l’auteur du plugile (c’est à dire vous).
##
##


// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Tell profil.php that this is indeed a plugile and that it is loaded
define('PUN_PLUGIN_LOADED', 1);
define('PLUGIN_URL', 'profile.php?plugin='.$_GET['plugin'].'&amp;id='.$_GET['id']);
define('PLUGIN_NAME', $_GET['plugin']);

//
// The rest is up to you!
//

/***********************************************************************\
 Languages definitions
\***********************************************************************/

$english_array = array(
'Nothing entered'			=>	'You didn\'t enter anything!',
'Result'					=>	'You said "%s". Great stuff.',
'Plugile name'				=>	'Example plugile',
'Plugile description 1'		=>	'This plugile doesn\'t do anything useful. Hence the name "Example".',
'Plugile description 2'		=>	'This would be a good spot to talk a little about your plugile. Describe what it does and how it should be used. Be brief, but informative.',
'Form title'				=>	'An example form',
'Plugile legend'			=>	'Enter a piece of text and hit "Show text"!',
'Text to show'				=>	'Text to show',
'Text to show desc'			=>	'The text you want to display.',
'Plugile submit'			=>	'Show text',
);

$french_array = array(
'Nothing entered'			=>	'Vous n\'avez rien saisi !',
'Result'					=>	'Vous avez dit "%s". Bon boulot.',
'Plugile name'				=>	'Plugile exemple',
'Plugile description 1'		=>	'Ce plugile ne fait rien de bien utile. D\'où le nom &quot;Exemple&quot;.',
'Plugile description 2'		=>	'Ce serait un bon endroit pour parler au sujet de votre plugile. Décrivez ce qu\'il fait et comment il devrait être utilisé. Soyez bref, mais instructif.',
'Form title'				=>	'Un formulaire d\'exemple',
'Plugile legend'			=>	'Saisissez un bout de texte et cliquez "Afficher"&nbsp;!',
'Text to show'				=>	'Texte à afficher',
'Text to show desc'			=>	'Le texte que vous voulez afficher.',
'Plugile submit' 			=>	'Afficher&nbsp;!',
);

// Set language
if($pun_user['language'] == 'English')
	$lang_exemple = $english_array;
elseif($pun_user['language'] == 'French')
	$lang_exemple = $french_array;


	// If the "Show text" button was clicked
	if (isset($_POST['show_text']))
	{
		// Make sure something something was entered
		if (trim($_POST['text_to_show']) == '')
			message($lang_exemple['Nothing entered']);
			
		generate_profile_menu(PLUGIN_NAME);
		?>
		<div class="block">
			<h2><span><?php echo $lang_exemple['Plugile name']; ?></span></h2>
			<div class="box">
				<div class="inbox">
					<p><?php echo str_replace('%s', pun_htmlspecialchars($_POST['text_to_show']), $lang_exemple['Result']); ?></p>
					<p><a href="javascript: history.go(-1)"><?php echo $lang_common['Go back']; ?></a></p>
				</div>
			</div>
		</div>
	<?php

	}
	else	// If not, we show the "Show text" form
	{
		generate_profile_menu(PLUGIN_NAME);
		?>
		<div id="exampleplugile" class="blockform">
			<h2><span><?php echo $lang_exemple['Plugile name']; ?></span></h2>
			<div class="box">
				<div class="inbox" style="padding-left: 6px;">
					<p><?php echo $lang_exemple['Plugile description 1']; ?></p>
					<p><?php echo $lang_exemple['Plugile description 2']; ?></p>
				</div>
			</div>

			<h2 class="block2"><span><?php echo $lang_exemple['Form title']; ?></span></h2>
			<div class="box">
				<form id="example" method="post" action="<?php echo PLUGIN_URL; ?>">
					<div class="inform">
						<fieldset>
							<legend><?php echo $lang_exemple['Plugile legend']; ?></legend>
							<div class="infldset">
								<label class="conl"><?php echo $lang_exemple['Text to show']; ?><br /><input type="text" name="text_to_show" size="25" /><br /></label>
								<label class="conl"><br /><?php echo $lang_exemple['Text to show desc']; ?><br /></label><div class="clearer"></div>
							</div>
						</fieldset>
					</div>
					<p><input type="submit" name="show_text" value="<?php echo $lang_exemple['Plugile submit']; ?>" /></p>
				</form>
				
			</div>
		</div>
<?php

	}

// Note that the script just ends here. The footer will be included by profil.php.
