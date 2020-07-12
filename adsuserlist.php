<?php
// $Id: adsuserlist.php,v 1.1 2005/02/16 C. Felix AKA the Cat
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

include_once("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
include_once(XOOPS_ROOT_PATH."/class/pagenav.php");
include_once(XOOPS_ROOT_PATH."/header.php");
$xoopsOption['template_main'] = 'catads_adslist.html';

global $xoopsDB, $xoopsUser, $xoopsModuleConfig ;

$ads_handler =& xoops_getmodulehandler('ads');

foreach ($_POST as $k => $v) {${$k} = $v;}

foreach ($_GET as $k => $v) {${$k} = $v;}

// if(!isset($debut)) $debut=0;

// pk get module pref.
$show_ad_type = $xoopsModuleConfig['show_ad_type'] ;

//Ajout
$affichage_titre = isset($_GET['affichage_titre']) ? $_GET['affichage_titre'] : '';
$affichage_prix = isset($_GET['affichage_prix']) ? $_GET['affichage_prix'] : '';
$affichage_option_prix = isset($_GET['affichage_option_prix']) ? $_GET['affichage_option_prix'] : '';
$affichage_localisation = isset($_GET['affichage_localisation']) ? $_GET['affichage_localisation'] : '';
$affichage_date = isset($_GET['affichage_date']) ? $_GET['affichage_date'] : '';
//Ajout

// verification user

$uid = !isset($_REQUEST['uid'])? NULL : $_REQUEST['uid'];

// pk - prevent query-string selection of user ID
if ($xoopsUser) {

        if ($xoopsUser->getVar('uid') != $uid) {
        redirect_header("index.php",1,_NOPERM);
        }

        if ($xoopsUser->getVar('uid') == $uid) {
        // $isauthor = true;
        $xoopsTpl->assign('lang_title', _MD_CATADS_MYADS);
        } elseif ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $xoopsTpl->assign('lang_title', _MD_CATADS_ALLADS.xoops_getLinkedUnameFromId($uid));
        }

    }

        // comptage nombre annonces
        $criteria = new Criteria('uid', $uid);

        $nbads = $ads_handler->getCount($criteria);

        //$start = $debut;

        $start = '0';

        $limit = $xoopsModuleConfig['nb_perpage'];

        //Probleme autrement avec le template catads_adssublist.html
        $topic_id = '';

        // $arr_ads = showListAdsByUser($uid, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $debut, $xoopsModuleConfig['nb_perpage']);
        $arr_ads = showMyAds('', $uid, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $debut, $limit);

        $pagenav = new XoopsPageNav($nbads, $limit, $debut, "debut", "uid=".$uid);

        $xoopsTpl->assign('topic_id', $topic_id);
        //Affichage liste annonces
        $xoopsTpl->assign('lang_back', "<a href=\"index.php\">"._MD_CATADS_MAIN."</a>");
        $xoopsTpl->assign('nbads', $nbads);
        $xoopsTpl->assign('topic_id', 0);
        $xoopsTpl->assign('isauthor', true);
        $xoopsTpl->assign('uid', $uid);

        $xoopsTpl->assign('items', $arr_ads);

        $xoopsTpl->assign('nav_page', $pagenav->renderNav());

        $xoopsTpl->assign('show_ad_type', $show_ad_type);


$xoopsTpl->assign("xoops_module_header",
'<link rel="stylesheet" type="text/css" href="style.css" /> <link rel="stylesheet" type="text/css" href="css/highslide.css" />');

include(XOOPS_ROOT_PATH."/footer.php");
?>