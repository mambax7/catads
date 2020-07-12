<?php
// $Id: adsitem.php,v 1.21 2005/07/17 C. Felix AKA the Cat
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
include "header.php";
include("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/option.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");

include_once(XOOPS_ROOT_PATH."/modules/catads/class/permissions.php");

global $xoopsUser ;

foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}

//if (isset($_GET['id'])) $ads_id = $id;
$ads_handler = & xoops_getmodulehandler('ads');
$ads = & $ads_handler->get($ads_id);

if (!is_object($ads)) {
        redirect_header('index.php',3,_MD_CATADS_NO_EXIST);
        exit();
}

// non publiée ou expirée, et admin ou auteur
$ads_exist = false;
$poster_id = $ads->getVar('uid');
if ($xoopsUser) {
        if ($xoopsUser->getVar('uid') == $poster_id) {
                $isAuthor = true;
                $ads_exist = true;
        } elseif ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
                $isAdmin = true;
                $ads_exist = true;
        }
} else {
        $isAuthor = false;
        $isAdmin = false;
}

// pk add category permissions control

        $topic_id = $ads->getVar('cat_id');
        $permHandler = CatadsPermHandler::getHandler();
        if(!$permHandler->isAllowed($xoopsUser, 'catads_access', $topic_id))
        {
                redirect_header("index.php", 3, _NOPERM);
                exit;
        }

if ($ads->getVar('waiting') == 0 && $ads->getVar('expired') > time())
        $ads_exist = true;

//annonce existe mais non disponible
$messagesent =        _MD_CATADS_NO_ADS;

if($ads->getVar('expired') < time())
        $messagesent .=        _MD_CATADS_NO_ADS_E;
elseif($ads->getVar('published') > time())
        $messagesent .=        sprintf(_MD_CATADS_NO_ADS_P, formatTimestamp($ads->getVar('published'),'s'));
elseif ($ads->getVar('waiting') > 0)
        $messagesent .=        _MD_CATADS_NO_ADS_W;

if (!$ads_exist) {
        redirect_header('index.php',4, $messagesent);
        exit();
}


function pubagain() {
        global $ads, $ads_handler, $xoopsModuleConfig, $duration, $isAuthor;

        $ads_id = $ads->getVar('ads_id');

        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

        $option_handler = & xoops_getmodulehandler('option');

        if ( $ok == 1 && $isAuthor) {
                if(!$option_handler->optionIsValid($duration, 4)) {
                        //TODO:
                        redirect_header("adsitem.php?ads_id=".$ads_id, 3, 'Tricheur !');
                        exit;
                }
                // pk get countpub value (if allowed renewals pref was '1' value should be '1') and deduct '1'.
                $countpub = $ads->getVar('countpub')- 1 ;
                $expired = time() + $duration*86400;
                $ads->setVar('expired', $expired);
                // pk set new countpub value (if pref was '1' this would now be '0' so template will not display renewal option).
                $ads->setVar('countpub', $countpub);
                $update_ads_ok = $ads_handler->insert($ads);

                if ($update_ads_ok){
                        $messagesent = sprintf(_MD_CATADS_PUBAGAIN_OK, $duration);
                } else {
                        $messagesent = _MD_CATADS_UPDATE_ERROR;
                }
                redirect_header("adsitem.php?ads_id=".$ads_id, 3, $messagesent);

        } else {
                xoops_confirm(array('op' => 'pubagain', 'ads_id' => $ads_id, 'duration' => $duration, 'ok' => 1), 'adsitem.php', _MD_CATADS_PUBAGAIN_CONF);
        }
}

function stopandgo() {
        global $xoopsUser, $ads, $ads_handler;

        $uid = $ads->getVar('uid');
        if (!$xoopsUser || $xoopsUser->getVar('uid') != $uid) {
                redirect_header("index.php",1,_NOPERM);
        }

        $ads_id = $ads->getVar('ads_id');
        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

        if ($ads->getVar('suspend') == 0) {
        //suspendre
                $msgconf = _MD_CATADS_PUBSTOP_CONF;
                $msgok = _MD_CATADS_PUBSTOP_OK;
                $suspend = 1;
        } else {
        //reprendre
                $msgconf = _MD_CATADS_PUBGO_CONF;
                $msgok = _MD_CATADS_PUBGO_OK;
                $suspend = 0;
        }
    if ( $ok == 1 ) {
                $ads->setVar('suspend', $suspend);
                $update_ads_ok = $ads_handler->insert($ads);
                if ($update_ads_ok){
                        $messagesent = $msgok;
                } else {
                        $messagesent = _MD_CATADS_UPDATE_ERROR;
                }
                redirect_header("adsitem.php?ads_id=".$ads_id, 3, $messagesent);

        } else {
                xoops_confirm(array('op' => 'stopandgo', 'ads_id' => $ads_id, 'ok' => 1), 'adsitem.php', $msgconf);
        }
}

function showAds() {
        global $XoopsUser, $ads, $ads_handler, $xoopsTpl, $xoopsModule, $poster_id, $isAuthor, $isAdmin, $xoopsModuleConfig, $xoopsDB, $xoTheme;

        $ts =& MyTextSanitizer::getInstance();

        $xoopsTpl->assign('ad_exists', true);
        $annonce['id'] = $ads_id = $ads->getVar('ads_id');
        $annonce['date_pub'] = ($ads->getVar('waiting') == 0) ? formatTimestamp($ads->getVar('published'),"s") : 0;
        $annonce['date_exp'] = ($ads->getVar('expired') > time()) ? formatTimestamp($ads->getVar('expired'),"s") : 0;
        $annonce['countpub'] = $ads->getVar('countpub');
        $annonce['waiting'] = $ads->getVar('waiting');
        $annonce['suspend'] = $ads->getVar('suspend');

        $annonce['uid'] =  $poster_id;
        $annonce['submitter_name'] =  XoopsUser::getUnameFromId($poster_id);
        $annonce['poster_ip'] =  $ads->getVar('poster_ip');
        $annonce['isauthor'] = $isAuthor;
        $annonce['nbview'] = sprintf(_MD_CATADS_NBVIEW, $ads->getVar('view'));
        $annonce['type'] = $ads->getVar('ads_type');
        $annonce['title'] = $ads->getVar('ads_title');

        // pk fix extended chars
        // $mots_tags = $ads->getVar('ads_tags');
        $mots_tags = $ts->undoHtmlSpecialChars($ads->getVar('ads_tags'));

        // On decoupe la chaine délimitée par des virgules avec explode
        $mots_tags = explode(' ', $mots_tags);

        // On traite la chaine pr supprimer les doublons
        $mots_tags = array_unique($mots_tags);

        // Soit $max est = à l'ensemble du contenu du tableau
        $max = count($mots_tags); // count renvoi le nombre total d'éléments

   // $font_mini = 9;
   $font_maxi = 16;
   // Puis on affiche le nuage avec la boucle for

   // pk define VAR
   $nuage_tags = '';

   for ( $i=0; $i< $max ; $i++ ) // Début de boucle
   {
   $nuage_tags .='<img src="images/delimiter.gif" alt="" />&nbsp;<a href="./adslist.php?search=1&amp;words='.$mots_tags[$i].'">'.$mots_tags[$i].'</a> ';
   }
   // Affichage final
   $xoopsTpl->assign('link_tags', $nuage_tags);


        // video
        $donneesvideo = $ads->getVar('ads_video');
        if ( strpos($donneesvideo, 'youtube') ) {
    $annonce['video']   = str_replace('http://www.youtube.com/watch?v=', 'http://www.youtube.com/v/', $donneesvideo);
    } elseif ( strpos($donneesvideo, 'dailymotion') ) {
        $annonce['video']   = str_replace('http://www.dailymotion.com/video/', 'http://www.dailymotion.com/swf/', $donneesvideo);
        }

        // pk fix extended chars
        $pk_desc = $ts->undoHtmlSpecialChars($ads->getVar('ads_desc'));
        // $annonce['description'] = $ts->displayTarea($ads->getVar('ads_desc'), 0, 1, 1);
        $annonce['description'] = $ts->displayTarea($pk_desc, 0, 1, 1);

        // pk addition - get 'display_price' pref from cat table (not used at present)
        // $annonce['display_price'] = $ads->getVar('display_price');

        $annonce['price'] = $ads->getVar('price');
        $annonce['monnaie'] = $ads->getVar('monnaie');
        $annonce['price_option'] = $ads->getVar('price_option');
        //Region
        $sql1 = $xoopsDB->query("SELECT region_nom FROM ".$xoopsDB->prefix("catads_regions")." WHERE region_numero = ".$ads->getVar('region'));
        list($region) = $xoopsDB->fetchRow($sql1);
        $annonce['region'] = $region;

        //Departement
        $sql2 = $xoopsDB->query("SELECT departement_nom FROM ".$xoopsDB->prefix("catads_departements")." WHERE departement_numero = ".$ads->getVar('departement'));
        list($departement) = $xoopsDB->fetchRow($sql2);
        $annonce['departement'] = $departement;

        $annonce['town'] = $ads->getVar('town');
        $annonce['codpost'] = $ads->getVar('codpost');

        $annonce['candelete'] = $xoopsModuleConfig['usercandelete'];
        //$annonce['canedit'] = ($xoopsModuleConfig['moderated'] < 1) && $xoopsModuleConfig['usercanedit'];
        $annonce['canedit'] = $xoopsModuleConfig['usercanedit'];

        if(!$isAdmin && !$isAuthor) $ads_handler->incView($ads_id);

        // pk contact mode - correct in DB - wrong here somewhere (CORRECTED) - 3 =phone, 2=email, 1=PM, 13=only phone, 12=only email, 11=only PM
        // si mode contact = uniquement
        // basically, knock 10 off the DB value if over 9, so '2' should be email (12 minus 10), not PM.
        if ($ads->getVar('contact_mode') > 9) {
                $contact_mode = $ads->getVar('contact_mode') -10;
                if ($contact_mode == 1)
                        $annonce['pmlink'] = "<a href='#' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$poster_id."\",\"pmlite\",450,380);'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/pm.gif' alt='"._MD_CATADS_BYPM."' /></a><br />";
                if ($contact_mode == 2)
                        $annonce['maillink'] = "<a href='#' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/modules/catads/contact.php?ads_id=".$ads_id."\",\"contact\",600,450);'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/email.gif' alt='"._MD_CATADS_BYMAIL."' /></a><br />";
                if ($contact_mode == 3)
                        $annonce['phone'] = '<b>'._MD_CATADS_PHONE_P.'</b> '.$ads->getVar('phone');
        } else {
                $contact_mode = $ads->getVar('contact_mode');
                $annonce['msg_contact'] = _MD_CATADS_CONTACT_PREF1.'&nbsp;'._MD_CATADS_BY.'';
                if ($contact_mode == 1) $annonce['msg_contact'] .= _MD_CATADS_CONTACT_MODE1;
                if ($contact_mode == 2) $annonce['msg_contact'] .= _MD_CATADS_CONTACT_MODE2;
                if ($contact_mode == 3) $annonce['msg_contact'] .= _MD_CATADS_CONTACT_MODE3;
                $annonce['msg_contact'] .= '<br /><br />';
                if ($ads->getVar('email')!= '')
                        $annonce['maillink'] = "<a href='#' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/modules/catads/contact.php?ads_id=".$ads_id."\",\"contact\",600,450);'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/email.gif' alt='"._MD_CATADS_BYMAIL."' /></a>&nbsp;";
                if ($poster_id > 0)
                        $annonce['pmlink'] = "<a href='#' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$poster_id."\",\"pmlite\",450,380);'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/pm.gif' alt='"._MD_CATADS_BYPM."' /></a><br />";
                if ($ads->getVar('phone') != '')
                        $annonce['phone'] = '<br /><b>'._MD_CATADS_PHONE.'</b> '.$ads->getVar('phone');
        }

        // pour affichage pseudo et lien vers ses annonces
        if ($xoopsModuleConfig['display_pseudo'] && $poster_id > 0){
                $annonce['submitter_link'] =  xoops_getLinkedUnameFromId($poster_id);
                $criteria = new CriteriaCompo(new Criteria('waiting', '0'));
                $criteria->add(new Criteria('published', time(),'<'));
                $criteria->add(new Criteria('expired', time(),'>'));
                $criteria->add(new Criteria('uid', $poster_id));
                $nbads = $ads_handler->getCount($criteria);
                if ($nbads > 1)
                        $annonce['submitter_ads'] = sprintf(_MD_CATADS_SEE_OTHER_ADS, "<a href='adslist.php?uid=".$poster_id."'>".$nbads."</a>", $annonce['submitter_link']);
                else
                        $annonce['submitter_ads'] = sprintf(_MD_CATADS_NO_OTHER_ADS, $annonce['submitter_link']);

        }
        //        $cat = new AdsCategory($ads->getVar('cat_id'));

        if ( $ads->getVar('photo0') != '' )
        {
                $i = 0;
                // pk bugfix - set photo count higher than 3
                // while  ($i < 3)
                while  ($i < 6)
                {
                        if ($ads->getVar('photo'.$i))
                        {
                        // pk display all images with popbox pop-up. try to fix invalid code.
                        // $annonce['photo'.$i] = "<img src=\"".XOOPS_URL."/uploads/catads/images/annonces/original/".$ads->getVar('photo'.$i)."\"  id=\"".$i."\" alt=\"".$i."\" pbshowcaption=\"false\" pbcaption=\"".$i."\" style=\"width: 200px; height: *;\" class=\"PopBoxImageSmall\" onclick=\"Pop(this,50,'PopBoxImageLarge');\" />";
                        $annonce['photo'.$i] = "<img src=\"".XOOPS_URL."/uploads/catads/images/annonces/original/".$ads->getVar('photo'.$i)."\"  id=\"photo".$i."\" alt=\"".$i."\" style=\"width: ".$xoopsModuleConfig['click_image_width']."px; height: *;\" class=\"PopBoxImageSmall\" onclick=\"Pop(this,50,'PopBoxImageLarge');\" />";

                        // pk tried using thumbs as link img but sadly only one thumb is created per ad, not one per photo :-(
                        // $annonce['photo'.$i] = "<a href=\"".XOOPS_URL."/uploads/catads/images/annonces/original/".$ads->getVar('photo'.$i)."\" pbshowcaption=\"false\" pbcaption=\"".$i."\" class=\"highslide\" style=\"width: 250px;\" onclick=\"return hs.expand(this)\">
                        // <img src=\"".XOOPS_URL."/uploads/catads/images/annonces/thumb/".$ads->getVar('thumb')."\" alt=\"\" style=\"width: 70px;\" /></a>";

                        }
                        $i++;
                }
        } else {
                $annonce['photo0'] = "<img  id=\"no_image\" alt=\"no_image\" src=\"".XOOPS_URL."/modules/catads/images/no_dispo.gif\" class=\"contour\" style=\"width: 100px; height: 100px;\" />";
        }

        // pk assign show/hide ads type pref
        $xoopsTpl->assign('show_ad_type', $xoopsModuleConfig['show_ad_type']);

        $annonce['nbcols'] = 2;

        // pk show renewal option if today is greater than expiry date minus renewal notice period

        $reminder_days = $xoopsModuleConfig['nb_days_expired'] ;
        $reminder_timestamp = $reminder_days*86400 ;
        $expires_timestamp = $ads->getVar('expired');
        $reminder_time = $expires_timestamp - $reminder_timestamp;
        if(time() > $reminder_time){
        $annonce['show_renewal'] = '1' ;
        }

        $xoopsTpl->assign('annonce', $annonce);

        //boite de selection durée de (re)publication
        $jump = XOOPS_URL."/modules/catads/adsitem.php?op=pubagain&amp;ads_id=".$ads_id."&amp;duration=";
        $opt = new CatadsOption();
        ob_start();
        $opt->makeMySelBox('option_order','', 1, 4, "location=\"".$jump."\"+this.options[this.selectedIndex].value");
        $xoopsTpl->assign('sel_box', ob_get_contents());
        ob_end_clean();

        $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
        $pathstring = "<a href='index.php'>"._MD_CATADS_MAIN."</a>&nbsp;:&nbsp;";
        $pathstring .= $mytree->getNicePathFromId($ads->getVar('cat_id'), "topic_title", "adslist.php?op=");
        //$pathstring = substr($pathstring, 0, -7);
        $pathstring = str_replace(":"," <img src='".XOOPS_URL."/modules/catads/images/icon/arrow.gif' border='0' alt='' /> ",$pathstring);
        $xoopsTpl->assign('link_cat', $pathstring);
        if ( $xoopsModuleConfig['pub_footer_show'] == 1 ) {
                $xoopsTpl->assign('pub', $xoopsModuleConfig['pub_footer_script']);
        }
        // titre page pour référencement - pk add highslide.css to head to validate
        $xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" /> <link rel="stylesheet" type="text/css" href="css/highslide.css" />');

        // pk make page title conditional to ad-type pref
        if($xoopsModuleConfig['show_ad_type'] == '1'){
        $xoopsTpl->assign('xoops_pagetitle', $ads->getVar('ads_type').' '.$ads->getVar('ads_title').' - ' .$xoopsModule->name());
        } else {
        $xoopsTpl->assign('xoops_pagetitle', $ads->getVar('ads_title').' - ' .$xoopsModule->name());
        }

        // pk add meta keywords and description tags for ads
        $keyword_tags = '' ;  
        $desctextclean = strip_tags($annonce['description']);
        $xoTheme->addMeta('meta', 'description', substr($desctextclean, 0, 140));
        for ( $i=0; $i< $max ; $i++ ) {
        $keyword_tags .= $mots_tags[$i].", " ;
        }
        $xoTheme->addMeta('meta', 'keywords', $keyword_tags);
        // end pk mod
}

if ( isset($_POST['pubagain'] )) $op = 'pubagain';
elseif ( isset($_POST['stopandgo'])) $op = 'stopandgo';
elseif (!isset($op)) $op = 'showAds';

switch ($op) {
        case "pubagain":
                include(XOOPS_ROOT_PATH."/header.php");
                pubagain();
                include(XOOPS_ROOT_PATH."/footer.php");
                break;
        case "stopandgo":
                include(XOOPS_ROOT_PATH."/header.php");
                stopandgo();
                include(XOOPS_ROOT_PATH."/footer.php");
                break;
        case "showAds":
        default:
                include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
                $xoopsOption['template_main'] = 'catads_item.html';
                include(XOOPS_ROOT_PATH."/header.php");
                showAds();
                include XOOPS_ROOT_PATH.'/include/comment_view.php';
                include(XOOPS_ROOT_PATH."/footer.php");
                break;
}

?>