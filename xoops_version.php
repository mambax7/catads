<?php
// $Id: xoops_version.php,v 1.71 2005/07/07 C. Felix AKA the Cat
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//---------------------------------------------------------------------------//

$modversion['name'] = _MI_CATADS_NAME;
$modversion['version'] = '1.53.5';
$modversion['description'] = _MI_CATADS_DESC;
$modversion['credits'] = "Pascal Le Boustouller (myAds) | Kraven30 (Catads 1.52)";
$modversion['author'] = "Peekay";
// $modversion['help'] = '';
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/catads_slogo.png";
$modversion['dirname'] = "catads";

// Informations supplementaire
$modversion['status'] = "RC3";

$modversion['warning'] = "The Catads module for Xoops enables you to provide a classified advertisements feature on your website.
<br /><br />This version is based on Catads 1.522 FINAL from TDM Xoops (France) with some issues fixed and some new admin features added.
The database structure is unchanged.<br /><br />
The installer now creates menu options for World regions and countries where originally there was French regional data.
At present, to customise these menus to suit your locale you will need to edit the database tables directly.
It is not difficult to do, but please make a back-up of these tables before making any modifications.<br /><br />
If you wish to notify users by email that their ad is due to expire, you must enable the block called
'Send an email if the ad is due to expire'. This does not display on the site, but it is required.<br /><br />
This is an an open-source software development project and the module is provided without charge and without warranty. If you are able to
make improvements and would like to release a new version, please ensure that the module is made freely available in the Xoops
module repository or via Sourceforge.<br /><br />
Please note that the Highslide Javascript used in this version (and v 1.52) is subject to certain licence conditions.
";

$modversion['support_site_url'] = "http://dev.xoops.org/modules/xfmod/project/?cat_ads_153";
$modversion['support_site_name'] = "Xoops Dev Forge";
// $modversion['support_email'] = "";
$modversion['submit_bug'] = "http://dev.xoops.org/modules/xfmod/project/?cat_ads_153";
// $modversion['submit_feature'] = "http://www.xoops.org";


$modversion['author_word'] = "
  <p><u>1.53.5 RC3 10/06/2010</u> | Peekay<br />
  <br />
  Changes:<br />
  <br />
  - Firefox Ad Blocker Plus warning added to release notes (thx madDan).<br />
  - Corrected description in prefs for default email and admin renewal period.<br />
  <br />
  Bugfixes:
  <br /><br />
  - Template cloning generated a Smarty error (French character issue).<br />
  - Email notification failed if notification options were hidden.<br />
  - Email renewal did not respect allowed renewal count.<br />
  - Allowed renewal count was not decremented after email renewal.<br />
  - Currency and price options were missing from admin edit form.<br />
  - Ad preview did not incorporate style CSS.<br />
  </p>
  <p><u>1.53.4 RC2 12/05/2010</u> - Peekay<br />
  <br />
  Changes:<br />
  <br />
  - Search form menus now group categories and sub-categories correctly.<br />
  - Admin ad-listing now shows user-selected currency.<br />
  - Listings now show ad-type if that preference is set.<br />
  - Title and description display length in listings can now be set as prefs.<br />
  - Ad template and category page templates and CSS updated.<br /><br />
    Bugfixes:<br />
  <br />
  - My-ads listing did not paginate.<br />
  </p>
  <p><u>1.53.3 RC1 10/05/2010</u> - Peekay<br />
  <br />
  Changes:<br />
  <br />
  - RSS now hides ads in categories which disallow anonymous viewing.<br />
  - Meta description tag and keywords are generated dynamically.<br />
  - Admin ad-listing now shows the latest ads first by default.<br /><br />
  Bugfixes:<br />
  <br />
  - Blocks did not respect category permissions.<br />
  - Xoops User-Profile listing of ads did not respect category permissions.<br />
  - Icon for suspended-ads was missing from admin ad-management screen.<br />
  - Import.php failed to check for Classifieds module installation resulting in potential data loss.<br />
  - Title character-length option in blocks did not work.<br />
  - Admin blocks displayed incorrectly in IE7.<br />
  - The Javascript intended to require keyword entry in the search form did not work.<br />
  <br /></p>

  <p><u>1.53.2 Beta 26/04/2010</u> - Peekay<br />
  <br />
  Changes:<br />
  <br />
  - Search results pagination function updated to be PHP 5 compatible.<br />
  - One language change (language/English/main.php) see notes.<br />
  <br />
  Bugfixes:<br />
  <br />
  - Anonymous comment posting failed using PHP 5.<br />
  - Notify on publication option didn't work.<br />
  <br />
  Notes:<br />
  <br />
  The words 'by email' were removed from the 'Notify me when published' language definition because the notification is only sent by email if the user chooses email as their method for Xoops
  notifications. Otherwise it is sent by PM.<br /></p>

  <p><u>1.53.1 Beta 20/04/2010</u> - Peekay<br />
  <br />
  Changes:<br />
  <br />
  - Search now checks for a number in the price-search field.<br />
  <br />
  Bugfixes:<br />
  <br />
  - Search results did not paginate.<br />
  <br />
  Language Packs (download from the project page):<br />
  <br />
  - Arabic translation by Mariane.<br />
  - Chinese translation by Hunnuh.<br />
  - French translation by Tatane.<br /></p>

  <p><u>1.53 Beta 01/04/2010</u> - Peekay - <i>Based on Catads 1.522 FINAL (Sourceforge)</i><br />
  <br />
  Changes:<br />
  <br />
  - Option added to allow custom tags.<br />
  - Option added to hide notification options (so renewal notice can be sent by email always).<br />
  - Option added to hide video field.<br />
  - Option added to restrict publication date to today's date.<br />
  - Option added to hide 'For Sale' etc. (so module can be used as a directory)<br />
  - Clicking a tag now includes tags in search.<br />
  - Keyword search now includes tags.<br />
  - Search allows partial match on post code.<br />
  - Users can search for a keyword in 'category', 'region', 'country' or 'town'.<br />
  - Sub-category 'no-image' option added.<br />
  - Default installation now uses 'regions' table for world regions menu (customisable).<br />
  - Default installation now uses 'departements' table for world countries menu (customisable).<br />
  - Click-to-enlarge image size can now be set in admin.<br />
  - English language files updated.<br />
  - Some field lengths changed.<br />
  - Template now uses theme CSS instead of custom CSS.<br />
  - Module is 99% W3C valid for anonymous users.<br />
  - Blocks are W3C valid, except scrolling ads block which uses the 'marquee' tag.<br />
  - French map is no longer operational by default and will require manual menu changes.<br />
  - (French map files and admin pref have been retained for legacy).<br />
  <br />
  Bugfixes:<br />
  <br />
  - RSS feed did not respect expired, waiting, unpublished or suspended ads.<br />
  - Keyword search did not respect expired, waiting, unpublished or suspended ads.<br />
  - Search failed to limit results to specified parameters (category, region, town etc.)<br />
  - Images did not preview.<br />
  - Email and PM icons did not preview.<br />
  - Region and department names did not preview.<br />
  - Tags did not preview.<br />
  - User notification choice was lost on preview.<br />
  - Notify on publication choice was lost on preview.<br />
  - Selecting 'email' set 'PM' as method.<br />
  - Incorrect moderation message was displayed if moderation disabled.<br />
  - Admin blocks displayed incorrectly in Firefox.<br />
  - Admin 'view category description' option duplicated.<br />
  - Invalid ID in pop-box script.<br />
  - Invalid Highslide CSS (loaded in body).<br />
  - Invalid Flash embed code.<br />
  - Invalid 'no-image' ID.<br />
  - Broken image icon in sub-categories if no image chosen.<br />
  <br />
  Known issues. Code patches or file replacements would be welcomed for the following:<br />
  <br />
  - Search results do not paginate - FIXED 1.53.1.<br />
  - Blocks do not respect category permissions - FIXED 1.53.3.<br />
  - Block template file 'catads_block_expired_ads.html' is missing? SOLVED (no template is required)<br />
  - Image filesize error on upload is not flagged in admin edit (this works in user edit).<br />
  - Notify on publication email is not sent - FIXED 1.53.2.<br />
  <br /></p>

  <p><u>1.52 Final 28/07/2009</u> <i>Merci &agrave; Nikita et Tatane et merci a Mariane pour la traduction en anglais et en arabe</i><br />
  <br />
  TDM Xoops | http://www.tdmxoops.net | webmaster@tdmxoops.net<br />
  <br />
  - Modification du script pour le zoom des vignettes<br />
  - Les annonces expirees ne sont plus affichees dans la carte<br />
  - Rajout du bloc \"Recherche\"<br />
  - Rajout d'une video dans les annonces<br />
  - Possibilite d'afficher les descriptions des categories<br />
  - Les sous-categories sont affichees dans la categorie principale<br />
  - Rajout des tags<br />
  - Possibilite d'activer le SEO<br /></p>

  <p><u>1.51 Beta 19/3/2009</u> <i>Merci &agrave; Tatane pour les tests</i><br />
  02/01/2009 :<br />
  - Bug avec les notifications ( Quand l'utilisateur demandait d'etre prevenu lors la parution de son email, aucun email etait envoye ).<br />
  - Modification de l'edition des annonces lorsqu'il y a la moderation ( Quand le membre voulait editer son annonce pour supprimer ou editer ses images, il ne pouvait pas ).<br />
  - Bug avec la creation de la vignette qui ne correspondait &amp; l'image de survol si il y avait plus de 2 images.<br />
  - Bug lors de la creation de la table regions et departements en fonction de certaines bases de donnees.<br />
  - Bug si l'utilisateur ne choisit pas de mettre les regions ou les departements en champs obligatoire, les annonces ne s'affichent pas sur la carte.<br />
  - Rajout dans l'edition d'annonce dans l'administration, les champs \"Departement\" et \"Region\".<br />
  - Captcha \"Formulaire contact\" par Eparcyl.<br />
  - UTF-8 pour les fichiers de langues par Kris.<br />
  - Affichage des menus deroulants au-dessus de la carte de France<br />
  - Petits bugs par si par l&amp;<br />
  19/01/2009 :<br />
  - La page \"notifications.php\" plante &amp; cause du module<br />
  - Quand l'utilisateur edite son annonce et la moderation est active. Un email est envoye &amp; l'administrateur pour le prevenir et l'annonce est mise en attente.<br />
  - Quand le membre edite son annonce, il peut choisir d'etre prevenu de la publication de son annonce.<br />
  - Dans la partie administrateur, rajout de champs dans l'edition d'une annonce (prix, telephone, regions, departement, titre)<br />
  - Rajout d'une icone \"membre prevenu de l'expiration de son annonce\" dans l'administration.<br />
  21/01/2009 :<br />
  - Ajout d'un champ dans les preferences pour inserer des pubs adsences ou des bannieres par exemple en bas de vos annonces.<br />
  - Dans la partie administrateur, rajout de champs dans l'edition d'une annonce (ajout de photo, suppression de photo)<br />
  - Possibilite de ne pas afficher certaines sous-categories dans la page d'accueil du module<br />
  26/01/2009 :<br />
  - Importation des annonces du module Classifields vers Catads<br />
  02/02/2009 :<br />
  - Rajout d'une option dans l'edition du bloc \" Dernieres annonces \" pour accelerer ou ralentir la vitesse de defilement.<br />
  24/02/2009 :<br />
  - Modification de la case \"ville\", on pouvait pas mettre plus de 22 caracteres.<br />
  - Les annonces qui ont ete editees ne recoivent plus de notifications d'annonce &amp; echeance.<br /></p>

  <p><u>V1.50 26/11/08</u> (Kraven30) <i>Merci &agrave; Eparcyl92 et Tatane pour les tests</i><br />
    Administration<br />
  - Modification par lot des statuts des annonces.<br />
  - Suppression des annonces par utilisateur.<br />
  - Rajout d'une fonction renouveler annonce.<br />
  - Rajout d'une fonction approche de l'expiration.<br />
  - Creation d'un bloc \"Envoi d'email lors de l'approche de l'expiration\".<br />
  - Ajout des permissions d'acces des cat&eacute;gories et de soumissions d'annonces dans les cat&eacute;gories.<br />
  - R&eacute;organisation de l'administration.<br />
  - D&eacute;placement des images dans le dossier uploads &agrave; la racine du site<br />
  - Cr&eacute;ation de vignettes</p>

  <p>//Utilisateur<br />
  - Trie par ordre croissant ou d&eacute;croissant des annonces.<br />
  - Modification du formulaire pour choisir de recevoir l'email d'expiration ou pas.<br />
  - Am&eacute;lioration de l'interface des listes des annonces.<br />
  - Am&eacute;lioration de l'interface de l'annonce.<br />
  - Syst&egrave;me d'agradissement des images des annonces ajax \"Popbox\"<br />
  - Survol des images - Bug d'affichage imprimante, rajout d'une photo.<br />
  - L'utilisateur peur renouveler son annonce quand il l'a recoit par email ou par mp.<br />
  - Bug: le nombre de lecture de l'annonce ne s'incremente plus apres le nombre 127.<br />
  - Bug: Probl&egrave;me d'affichage des categories.<br />
  - Affichage d'une carte de France<br />
  - Ajout d'un syst&egrave;me de recherche</p>

  <p><u>V1.41</u>(Philippe Montalbetti)<br />
  - Rajout du syst&egrave;me RSS.</p>

  <p><u>V1.40 17/07/05</u> (C. Felix AKA the Cat)<br />
  - Possibilit&eacute; d'upload de plusieurs photos (jusqu'&agrave; 6).<br />
  - Nombre de photos param&eacute;trable par rubrique.<br />
  - Modification administration.<br />
  - Possibilit&eacute; de modifier une annonce par son auteur (si non mod&eacute;r&eacute;).<br />
  - Possibilit&eacute; de supprimer une annonce par son auteur.<br />
  - Possibilit&eacute; d'affichage du pseudo de l'auteur et lien vers sa liste d'annonces.<br />
  - Script de mise &agrave; jour de la Bdd dans l'administration<br />
  - Champs code postal et email param&eacute;trables (non demand&eacute;, facultatif, requis).<br />
  - Diverses modifications pour compatibilit&eacute; Php5 et Xoops 2.2</p>

  <p><u>V1.3 20/02/05</u> (C. Felix AKA the Cat)<br />
  - Ajout choix date de publication<br />
  - Ajout choix dur&eacute;e publication (liste param&eacute;trable dans l'admin)<br />
  - Modification template formulaire<br />
  - Modification par l'admin des dates de publication et expiration</p>

  <p><u>V1.21 31/10/04</u> (C. Felix AKA the Cat)<br />
  - Ajout fonction d'impression<br />
  - Modification ajout d'annonce (utilisation m&ecirc;me template que l'index)<br />
  - Ajout contr&ocirc;le longueur texte</p>

  <p><u>V1.2 07/10/04</u> (C. Felix AKA the Cat)<br />
  - Cr&eacute;ation bloc ajout d'annonce (acc&egrave;s par liste d&eacute;roulante)<br />
  - Option de pr&eacute;sentation page index (rubriques en lignes ou colonnes)<br />
  - Ajout du sous-menu Soumettre une annonce<br />
  - Effacement du cache des blocs lors de soumission/approbation/effacement annonce (idem pour rubriques)<br />
  - Ajout anglais + espagnol<br />
  - fix bug template bloc new, et preview formulaire contact<br />
  - Ajout insertion des options en fonction de la langue &agrave; l'installation</p>

  <p><u>V1.1 29/09/04</u> (C. Felix AKA the Cat)<br />
  - pr&eacute;visualisation de l'annonce telle qu'elle paraitra (utilisation du m&ecirc;me template) et avec la photo (si photo soumise !)<br />
  - acc&egrave;s direct aux rubriques : liste d&eacute;roulante dans bloc des derni&egrave;res annonces<br />
  - Affichage de toutes les annonces des sous-rubriques lorsque l'on s&eacute;lectionne une rubrique interm&eacute;diaire.<br />
  - modification du titre des pages pour am&eacute;lioration r&eacute;f&eacute;rencement<br />
  - Ajout du sous-menu Mes annonces<br />
  - correction bugs mineurs</p>

  <p><u>V1.0</u> (C. Felix AKA the Cat)<br />
  - fix bug upload image (redirection, ajout maxi dans formulaire, message d'erreur)<br />
  - fix page blanche commentaires<br />
  - fix bug taille fen&ecirc;tre photo. Fichier modifi&eacute; : include/functions.php<br />
  - ajout affichage complet rubrique dans admin<br />
  - ajout affichage rubrique dans 'Ajouter une annonce...'<br />
  - notification(admin/index.php): effacement notifications + commentaires si effacement annonce. Correction \"Notification de la publication\" si mod&eacute;ration<br />
  - ajout affichage adresse IP + liste des annonces d'un membre (adsitem.php, catads_adsitem.html, adsuserlist.php, language/../main.php, images/ip.gig et who.gif)</p>

<p>Merci donc & tous...</p>";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// insert values into catads_options with the right language
$modversion['onUpdate'] = 'include/update_function.php';
$modversion['onInstall'] = 'include/install_function.php';

// Templates
$modversion['templates'][1]['file'] = 'catads_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'catads_adslist.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'catads_item.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'catads_addsubcat.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'catads_adssublist.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'catads_subcat.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'catads_adsform.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'catads_adsform2.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'catads_cat.html';
$modversion['templates'][9]['description'] = '';
// Blocks
$modversion['blocks'][1]['file'] = "catads_new.php";
$modversion['blocks'][1]['name'] = _MI_CATADS_BNAME1;
$modversion['blocks'][1]['description'] = "";
$modversion['blocks'][1]['show_func'] = "b_catads_show";
$modversion['blocks'][1]['edit_func'] = "b_catads_edit";
$modversion['blocks'][1]['options'] = "5|0|1|120|25";
$modversion['blocks'][1]['template'] = 'catads_block_new.html';

$modversion['blocks'][2]['file'] = "catads_add.php";
$modversion['blocks'][2]['name'] = _MI_CATADS_BNAME2;
$modversion['blocks'][2]['description'] = "";
$modversion['blocks'][2]['show_func'] = "b_catads_add";
$modversion['blocks'][2]['template'] = 'catads_block_add.html';

$modversion['blocks'][3]['file'] = "catads_myads.php";
$modversion['blocks'][3]['name'] = _MI_CATADS_BNAME3;
$modversion['blocks'][3]['description'] = "";
$modversion['blocks'][3]['show_func'] = "b_catads_myads";
$modversion['blocks'][3]['edit_func'] = "b_catads_myads_edit";
$modversion['blocks'][3]['options'] = "5|0|25";
$modversion['blocks'][3]['template'] = 'catads_block_myads.html';

// pk block 4 template is missing?

$modversion['blocks'][4]['file'] = "catads_expired_ads.php";
$modversion['blocks'][4]['name'] = _MI_CATADS_BNAME4;
$modversion['blocks'][4]['description'] = "clean expired ads";
$modversion['blocks'][4]['show_func'] = "b_catads_expired_ads";
$modversion['blocks'][4]['options'] = '';
$modversion['blocks'][4]['template'] = 'catads_block_expired_ads.html';

$modversion['blocks'][5]['file'] = "catads_search.php";
$modversion['blocks'][5]['name'] = _MI_CATADS_BNAME5;
$modversion['blocks'][5]['description'] = "";
$modversion['blocks'][5]['show_func'] = "b_catads_search";
$modversion['blocks'][5]['options'] = '';
$modversion['blocks'][5]['template'] = 'catads_block_search.html';

// Menu
$modversion['hasMain'] = 1;
global $xoopsUser;

    $uid = (is_object($xoopsUser))? $xoopsUser->getVar('uid'): 0;
                $modversion['sub'][1]['name'] = _MI_CATADS_SMENU2;
                $modversion['sub'][1]['url'] = "submit1.php";
                $modversion['sub'][3]['name'] = _MI_CATADS_SMENU3;
                $modversion['sub'][3]['url'] = "search.php";

        $ads_handler =& xoops_getmodulehandler('ads', 'catads');
        if ($uid > 0)
        {
                $criteria = new Criteria('uid', $uid);
                $nbads = $ads_handler->getCount($criteria);
                if ($nbads > 0)
                {
                    $modversion['sub'][2]['name'] = _MI_CATADS_SMENU1;
                    $modversion['sub'][2]['url'] = "adsuserlist.php?uid=".$uid;
                }
        }


$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "catads_ads";
$modversion['tables'][1] = "catads_cat";
$modversion['tables'][2] = "catads_options";
$modversion['tables'][3] = "catads_regions";
$modversion['tables'][4] = "catads_departements";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "catads_search";

// Config Settings

// Moderate
$i=1;
$modversion['config'][$i]['name'] = 'moderated';
$modversion['config'][$i]['title'] = '_MI_CATADS_MODERATE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;

// Anonymes peuvent poster
$modversion['config'][$i]['name'] = 'anoncanpost';
$modversion['config'][$i]['title'] = '_MI_CATADS_ANONCANPOST';
$modversion['config'][$i]['description'] = '_MI_CATADS_ANONCANPOST_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;

// Membres peuvent effacer leurs annonces
$modversion['config'][$i]['name'] = 'usercandelete';
$modversion['config'][$i]['title'] = '_MI_CATADS_CANDELETE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;

// Membres peuvent editer leurs annonces
$modversion['config'][$i]['name'] = 'usercanedit';
$modversion['config'][$i]['title'] = '_MI_CATADS_CANEDIT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - allow custom tags
$modversion['config'][$i]['name'] = 'allow_custom_tags';
$modversion['config'][$i]['title'] = '_MI_CATADS_ALLOW_CUSTOM_TAGS';
$modversion['config'][$i]['description'] = '_MI_CATADS_ALLOW_CUSTOM_TAGS_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - show ad-type menu
$modversion['config'][$i]['name'] = 'show_ad_type';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_AD_TYPE';
$modversion['config'][$i]['description'] = '_MI_CATADS_SHOW_AD_TYPE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - show video field
$modversion['config'][$i]['name'] = 'show_video_field';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_VIDEO_FIELD';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - allow publish date
$modversion['config'][$i]['name'] = 'allow_publish_date';
$modversion['config'][$i]['title'] = '_MI_CATADS_ALLOW_PUBLISH_DATE';
$modversion['config'][$i]['description'] = '_MI_CATADS_ALLOW_PUBLISH_DATE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// nombre maxi de jours de pre-publication
$modversion['config'][$i]['name'] = 'nb_days_before';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBDAYS_BEFORE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '14';
$modversion['config'][$i]['options'] = array();
$i++;

//Affichage des descrfiption dans les categories - pk - remmed (duplicated)
//$modversion['config'][$i]['name'] = 'show_cat_desc';
//$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_CAT_DESC';
//$modversion['config'][$i]['description'] = '';
//$modversion['config'][$i]['formtype'] = 'yesno';
//$modversion['config'][$i]['valuetype'] = 'int';
//$modversion['config'][$i]['default'] = '1';
//$modversion['config'][$i]['options'] = array();
//$i++;

// number ads per page
$modversion['config'][$i]['name'] = 'nb_perpage';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBPERPAGE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '10';
$modversion['config'][$i]['options'] = array();
$i++;

// number ads per page admin
$modversion['config'][$i]['name'] = 'nb_perpage_admin';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBPERPAGE_ADMIN';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '20';
$modversion['config'][$i]['options'] = array();
$i++;

// nb ads news
$modversion['config'][$i]['name'] = 'nb_news';
$modversion['config'][$i]['title'] = '_MI_CATADS_DISPLAYNEW';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '10';
$modversion['config'][$i]['options'] = array();
$i++;

// number days new
$modversion['config'][$i]['name'] = 'nb_days_new';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBDAY_NEW';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '3';
$modversion['config'][$i]['options'] = array();
$i++;

// image cols (PK - remmed - doesn't work)
//$modversion['config'][$i]['name'] = 'nb_cols_img';
//$modversion['config'][$i]['title'] = '_MI_CATADS_NBCOLS_IMG';
//$modversion['config'][$i]['description'] = '';
//$modversion['config'][$i]['formtype'] = 'select';
//$modversion['config'][$i]['valuetype'] = 'int';
//$modversion['config'][$i]['default'] = '3';
//$modversion['config'][$i]['options'] = array(1=>1, 2=>2, 3=>3, 4=>4);
//$i++;

// form with bbcodes
$modversion['config'][$i]['name'] = 'bbcode';
$modversion['config'][$i]['title'] = '_MI_CATADS_BBCODE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// Longueur maxi annonce
$modversion['config'][$i]['name'] = 'txt_maxlength';
$modversion['config'][$i]['title'] = '_MI_CATADS_MAXLENTXT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '500';
$modversion['config'][$i]['options'] = array();
$i++;

// Title length in listings
$modversion['config'][$i]['name'] = 'title_length';
$modversion['config'][$i]['title'] = '_MI_CATADS_TITLE_LENGTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '25';
$modversion['config'][$i]['options'] = array();
$i++;

// Description length in listings
$modversion['config'][$i]['name'] = 'desc_length';
$modversion['config'][$i]['title'] = '_MI_CATADS_DESC_LENGTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '80';
$modversion['config'][$i]['options'] = array();
$i++;

// Mail requis
$modversion['config'][$i]['name'] = 'email_req';
$modversion['config'][$i]['title'] = '_MI_CATADS_EMAIL_REQUIRED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '2';
$modversion['config'][$i]['options'] = array(_MI_CATADS_NOASK=>0, _MI_CATADS_OPTIONAL=>1,_MI_CATADS_REQUIRED=>2);
$i++;

// Region requis
$modversion['config'][$i]['name'] = 'region_req';
$modversion['config'][$i]['title'] = '_MI_CATADS_REGION_REQUIRED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array(_MI_CATADS_NOASK=>0,_MI_CATADS_REQUIRED=>1);
$i++;

// Departement requis
$modversion['config'][$i]['name'] = 'departement_req';
$modversion['config'][$i]['title'] = '_MI_CATADS_DEPARTEMENT_REQUIRED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array(_MI_CATADS_NOASK=>0,_MI_CATADS_REQUIRED=>1);
$i++;

// Code postal requis
$modversion['config'][$i]['name'] = 'zipcode_req';
$modversion['config'][$i]['title'] = '_MI_CATADS_ZIPCODE_REQUIRED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array(_MI_CATADS_NOASK=>0,_MI_CATADS_REQUIRED=>1);
$i++;

// Page index : lignes/colonnes
$modversion['config'][$i]['name'] = 'tpltype';
$modversion['config'][$i]['title'] = '_MI_CATADS_TPLTYPE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '2';
$modversion['config'][$i]['options'] = array(_MI_CATADS_LIN=>1, _MI_CATADS_COL=>2);
$i++;

// Nb col index
$modversion['config'][$i]['name'] = 'nbcol';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBCOL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '3';
$modversion['config'][$i]['options'] = array(2=>2, 3=>3, 4=>4, 5=>5);
$i++;

//Affichage des descrfiption dans les categories
$modversion['config'][$i]['name'] = 'show_cat_desc';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_CAT_DESC';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// Photo max size
$modversion['config'][$i]['name'] = 'photo_maxsize';
$modversion['config'][$i]['title'] = '_MI_CATADS_MAXSIZEIMG';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '153600';
$modversion['config'][$i]['options'] = array();
$i++;

// Photo max height
$modversion['config'][$i]['name'] = 'photo_maxheight';
$modversion['config'][$i]['title'] = '_MI_CATADS_MAXHEIGHTIMG';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '300';
$modversion['config'][$i]['options'] = array();
$i++;

// Photo max width
$modversion['config'][$i]['name'] = 'photo_maxwidth';
$modversion['config'][$i]['title'] = '_MI_CATADS_MAXWIDTHIMG';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '400';
$modversion['config'][$i]['options'] = array();
$i++;

//Extensions autorisees
/*$modversion['config'][$i]['name'] = 'allowed_file_extensions';
$modversion['config'][$i]['title'] = '_MI_FILE_EXT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'GIF/PNG/JPG/JPEG/TIF/TIFF/AVI/MP3';
$i++;*/

//Largeur max des thumbs - pk - bugfix array code
$modversion['config'][$i]['name'] = 'thumb_width';
$modversion['config'][$i]['title'] = '_MI_THUMB_WIDTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 60;
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - click-image width
$modversion['config'][$i]['name'] = 'click_image_width';
$modversion['config'][$i]['title'] = '_MI_CLICK_IMAGE_WIDTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 120;
$modversion['config'][$i]['options'] = array();
$i++;

//Librairie & utiliser
$modversion['config'][$i]['name'] = 'thumb_method';
$modversion['config'][$i]['title'] = '_MI_THUMB_METHOD';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'gd2';
$modversion['config'][$i]['options'] = array( 'GD version 1.x' => 'gd1', 'GD version 2.x' => 'gd2', 'Image Magick' => 'im', 'Netpbm' => 'net' );
$i++;

// pk auto-message for renewal
$modversion['config'][$i]['name'] = 'auto_mp';
$modversion['config'][$i]['title'] = '_MI_CATADS_AUTO';
$modversion['config'][$i]['description'] = '_MI_CATADS_AUTO_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

//PK - Add - show renewal notification options
$modversion['config'][$i]['name'] = 'show_notification_options';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_NOTIFICATION_OPTIONS';
$modversion['config'][$i]['description'] = '_MI_CATADS_SHOW_NOTIFICATION_OPTIONS_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// number days last expired
$modversion['config'][$i]['name'] = 'nb_days_expired';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBDAYSEXPIRED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '7';
$modversion['config'][$i]['options'] = array();
$i++;

// renew number days
$modversion['config'][$i]['name'] = 'renew_nb_days';
$modversion['config'][$i]['title'] = '_MI_CATADS_RENEW_NBDAYS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '30';
$modversion['config'][$i]['options'] = array();
$i++;

// nombre de re-publications autorisees
$modversion['config'][$i]['name'] = 'nb_pub_again';
$modversion['config'][$i]['title'] = '_MI_CATADS_NBPUB_AGAIN';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// display nickname
$modversion['config'][$i]['name'] = 'display_pseudo';
$modversion['config'][$i]['title'] = '_MI_CATADS_DISP_PSEUDO';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$modversion['config'][$i]['options'] = array();
$i++;

// pk show banner text under ad (1/2)
$modversion['config'][$i]['name'] = 'pub_footer_show';
$modversion['config'][$i]['title'] = '_MI_CATADS_PUB_FOOTER';
$modversion['config'][$i]['description'] = '_MI_CATADS_PUB_FOOTER_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;

// pk show banner text under ad (2/2)
$modversion['config'][$i]['name'] = 'pub_footer_script';
$modversion['config'][$i]['title'] = '_MI_CATADS_PUB_FOOTER_SCRIPT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$modversion['config'][$i]['options'] = array();
$i++;

//Activate SEO
$modversion['config'][$i]['name'] = 'show_seo';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_SEO';
$modversion['config'][$i]['description'] = '_MI_CATADS_SEO_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;


// PK France map - Affichage de la carte en page d'accueil du module ou affichage normal
$modversion['config'][$i]['name'] = 'show_card';
$modversion['config'][$i]['title'] = '_MI_CATADS_SHOW_CARD';
$modversion['config'][$i]['description'] = '_MI_CATADS_SHOW_CARD_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['options'] = array();
$i++;


// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'adsitem.php';
$modversion['comments']['itemName'] = 'ads_id';

// Notification
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'catads_notify_iteminfo';

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _MI_CATADS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_CATADS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php','adslist.php','adsitem.php');

$modversion['notification']['category'][2]['name'] = 'category';
$modversion['notification']['category'][2]['title'] = _MI_CATADS_CATEGORY_NOTIFY;
$modversion['notification']['category'][2]['description'] = _MI_CATADS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('adslist.php','adsitem.php');
$modversion['notification']['category'][2]['item_name'] = 'cat_id';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name'] = 'ads';
$modversion['notification']['category'][3]['title'] = _MI_CATADS_ADS_NOTIFY;
$modversion['notification']['category'][3]['description'] = _MI_CATADS_ADS_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'adsitem.php';
$modversion['notification']['category'][3]['item_name'] = 'ads_id';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name'] = 'ads_submit';
$modversion['notification']['event'][1]['category'] = 'global';
$modversion['notification']['event'][1]['admin_only'] = 1;
$modversion['notification']['event'][1]['title'] = _MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_adssubmit_notify';
$modversion['notification']['event'][1]['mail_subject'] = _MI_CATADS_GLOBAL_ADSSUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][2]['name'] = 'new_ads';
$modversion['notification']['event'][2]['category'] = 'global';
$modversion['notification']['event'][2]['title'] = _MI_CATADS_GLOBAL_NEWADS_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_CATADS_GLOBAL_NEWADS_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _MI_CATADS_GLOBAL_NEWADS_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_newads_notify';
$modversion['notification']['event'][2]['mail_subject'] = _MI_CATADS_GLOBAL_NEWADS_NOTIFYSBJ;

$modversion['notification']['event'][3]['name'] = 'ads_edit';
$modversion['notification']['event'][3]['category'] = 'global';
$modversion['notification']['event'][3]['admin_only'] = 1;
$modversion['notification']['event'][3]['title'] = _MI_CATADS_GLOBAL_EDIT_NOTIFY;
$modversion['notification']['event'][3]['caption'] = _MI_CATADS_GLOBAL_EDIT_NOTIFYCAP;
$modversion['notification']['event'][3]['description'] = _MI_CATADS_GLOBAL_EDIT_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_adsedit_notify';
$modversion['notification']['event'][3]['mail_subject'] = _MI_CATADS_GLOBAL_EDIT_NOTIFYSBJ;

$modversion['notification']['event'][4]['name'] = 'ads_submit';
$modversion['notification']['event'][4]['category'] = 'category';
$modversion['notification']['event'][4]['admin_only'] = 1;
$modversion['notification']['event'][4]['title'] = _MI_CATADS_CATEGORY_SUBMIT_NOTIFY;
$modversion['notification']['event'][4]['caption'] = _MI_CATADS_CATEGORY_SUBMIT_NOTIFYCAP;
$modversion['notification']['event'][4]['description'] = _MI_CATADS_CATEGORY_SUBMIT_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'category_adssubmit_notify';
$modversion['notification']['event'][4]['mail_subject'] = _MI_CATADS_CATEGORY_SUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][5]['name'] = 'new_ads';
$modversion['notification']['event'][5]['category'] = 'category';
$modversion['notification']['event'][5]['title'] = _MI_CATADS_CATEGORY_NEWADS_NOTIFY;
$modversion['notification']['event'][5]['caption'] = _MI_CATADS_CATEGORY_NEWADS_NOTIFYCAP;
$modversion['notification']['event'][5]['description'] = _MI_CATADS_CATEGORY_NEWADS_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'category_newads_notify';
$modversion['notification']['event'][5]['mail_subject'] = _MI_CATADS_CATEGORY_NEWADS_NOTIFYSBJ;

$modversion['notification']['event'][6]['name'] = 'approve';
$modversion['notification']['event'][6]['category'] = 'ads';
$modversion['notification']['event'][6]['invisible'] = 1; // pk whats this?
$modversion['notification']['event'][6]['title'] = _MI_CATADS_ADS_APPROVE_NOTIFY;
$modversion['notification']['event'][6]['caption'] = _MI_CATADS_ADS_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][6]['description'] = _MI_CATADS_ADS_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][6]['mail_template'] = 'ads_approve_notify';
$modversion['notification']['event'][6]['mail_subject'] = _MI_CATADS_ADS_APPROVE_NOTIFYSBJ;

$modversion['notification']['event'][7]['name'] = 'ads_edit';
$modversion['notification']['event'][7]['category'] = 'ads';
$modversion['notification']['event'][7]['invisible'] = 1; // pk whats this?
$modversion['notification']['event'][7]['title'] = _MI_CATADS_ADS_EDIT_NOTIFY ;
$modversion['notification']['event'][7]['caption'] = _MI_CATADS_ADS_EDIT_NOTIFYCAP;
$modversion['notification']['event'][7]['description'] = _MI_CATADS_ADS_EDIT_NOTIFYDSC;
$modversion['notification']['event'][7]['mail_template'] = 'ads_edit_notify';
$modversion['notification']['event'][7]['mail_subject'] = _MI_CATADS_ADS_EDIT_NOTIFYSBJ;
?>