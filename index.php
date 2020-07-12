<?php
// $Id: index.php,v 1.41 2005/07/07 C. Felix AKA the Cat
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
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once XOOPS_ROOT_PATH.'/modules/catads/class/permissions.php';

//////Parametres////////
$reduireTitle = 25; //Nb de caracteres
$afficherBlocNbAnnonces = 1; //1 = Affichage du bloc nb annonces, 0 = le contraire
$afficherBlocDernieresAnnonces = 1; //1 =  Affichage du bloc derniere annonces, 0 = le contraire
$afficherNbDernieresAnnonces = 4; //Nombre d'annonces à afficher dans le bloc "dernieres annonces"
////////////////////////
$xoopsOption['template_main'] = 'catads_index.html';
include(XOOPS_ROOT_PATH."/header.php");

//Ajout
$affichage_titre = isset($_GET['affichage_titre']) ? $_GET['affichage_titre'] : '';
$affichage_prix = isset($_GET['affichage_prix']) ? $_GET['affichage_prix'] : '';
$affichage_option_prix = isset($_GET['affichage_option_prix']) ? $_GET['affichage_option_prix'] : '';
$affichage_localisation = isset($_GET['affichage_localisation']) ? $_GET['affichage_localisation'] : '';
$affichage_date = isset($_GET['affichage_date']) ? $_GET['affichage_date'] : '';
//Ajout


//rss hack by InBox Solutions for Philippe Montalbetti
$link=sprintf("<a href='%s' title='%s'><img src='%s' border='0' alt='%s' /></a>",XOOPS_URL."/modules/catads/backend.php", _MD_CATADS_RSSFEED, XOOPS_URL."/modules/catads/images/icon/rss.gif",_MD_CATADS_RSSFEED);
$xoopsTpl->assign('rssfeed_link',$link);
//end rss hack by InBox Solutions for Philippe Montalbetti

// pk get module pref
$show_ad_type = $xoopsModuleConfig['show_ad_type'] ;

$ads_handler =& xoops_getmodulehandler('ads');
$ts =& MyTextSanitizer::getInstance();

// array des sous-catégories 'enfant' d'une catégorie
        function getFirstChild($topic_id = 0) {
                global $allcat;
                $firstChild = array();
                        foreach($allcat as $onechild)         {
                                if( $onechild['topic_pid'] == $topic_id) {
                                        array_push($firstChild, $onechild);
                                        /*echo $topic_id;*/
                                }
                        }
                return $firstChild;
        }

        function showsubcat($categorys, $level = 0, $topic_id = 0, $topic_pid) {
                global $xoopsModule, $ts, $lastchildren, $nbadspercat, $newads, $arr_subcat, $cptsubcat, $nbcol, $tpltype, $xoTheme;


                foreach($categorys as $onecat)         {


                        $link = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id=' . $onecat['topic_id'];
                        $title = $ts->htmlSpecialChars($onecat['topic_title']);
                        $desc = $ts->htmlSpecialChars($onecat['topic_desc']);
                        if (in_array($onecat['topic_id'], $lastchildren)) {
                                $arr_scat['nb'] = (array_key_exists($onecat['topic_id'], $nbadspercat)) ?  "(".$nbadspercat[$onecat['topic_id']].")": '';
                                $arr_scat['new'] = (array_key_exists($onecat['topic_id'], $newads)) ? $newads[$onecat['topic_id']]: '';
                        }
                        $arr_scat['link'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id=' . $onecat['topic_id'];
                        $arr_scat['id'] = $onecat['topic_id'];
                        $arr_scat['title'] = $title;
                        $arr_scat['desc'] = $desc;

                        // pk add conditional statement to stop broken image icon if no category image is selected
                        if($onecat['img'] != '') {
                        $arr_scat['img'] = "<img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$onecat['img']."' align='middle' alt='' />";
                        }else{
                        $arr_scat['img'] = '' ;
                        }
                        // end pk mod

                        if ($level == 0 && $tpltype == 1) {
                                $arr_scat['newcol'] = ($cptsubcat > 0) ? true : false;
                                $cptsubcat++;
                                $arr_scat['newline'] = ($cptsubcat % $nbcol == 1) ? true : false;
                        }
                        array_push($arr_subcat, $arr_scat);
                        $childcats =  getFirstChild($onecat['topic_id']);
                                if (count($childcats) > 0) {
                                        showsubcat($childcats, $level + 1, $onecat['topic_id'], $topic_pid);
                                }
                        }
                return;
                }


// annonces en attente de validation - pk if moderated
                if ($xoopsModuleConfig['moderated'] == '1') {
                        $ads_wait = $ads_handler->getCount(new Criteria('waiting', '1'));
                        $xoopsTpl->assign('moderated', true);
                        // si administrateur du module
                        if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
                                $xoopsTpl->assign('admin_block', _MD_CATADS_ADM_WAIT);
                                if($ads_wait == 0) {
                                        $xoopsTpl->assign('confirm_ads', _MD_CATADS_NO_WAIT);
                                } else {
                                        $xoopsTpl->assign('confirm_ads', sprintf(_MD_CATADS_NBWAIT, $ads_wait)."<br /><a href=\"admin/ads.php?sel_status=1&amp;sel_order=ASC\">"._MD_CATADS_SEEWAIT."</a>");
                                }
                        }
                }

                $tpltype = $xoopsModuleConfig['tpltype'];// 1 en lignes, 2 en colonnes
                $xoopsTpl->assign('tpltype', $tpltype);
                $nbcol = $xoopsModuleConfig['nbcol'];
                $wcol = 100/$nbcol;
                $xoopsTpl->assign('wcol', $wcol);

                // nombre annonces actives par catégorie. pk - bugfix add suspend criteria
                $criteria = new CriteriaCompo(new Criteria('waiting', '0'));
                $criteria->add(new Criteria('suspend', '0'));
                $criteria->add(new Criteria('published', time(), '<'));
                $criteria->add(new Criteria('expired', time(),'>'));
                $nbadspercat = $ads_handler->getCountAdsByCat($criteria);

                // nombre annonces nouvelles par catégorie. pk bugfix - add suspend criteria. Fix duplicate publish criteria
                $criteria = new CriteriaCompo(new Criteria('published', time()- $xoopsModuleConfig['nb_days_new']*86400, '>'));
                $criteria->add(new Criteria('waiting', '0'));
                $criteria->add(new Criteria('suspend', '0'));
                $criteria->add(new Criteria('expired', time(),'>'));
                // $criteria->add(new Criteria('published', time(),'<'));

                $newads = $ads_handler->getCountAdsByCat($criteria);

                $allcat =  AdsCategory::getAllCat(); // array de toutes les catégories
                $lastchildren = AdsCategory::getAllLastChild(); //array des catégories 'terminales'''
                $parray = AdsCategory::getCatWithPid(); //array des objets catégories principales
                $pcount = count($parray);
                $ptitle = '';
            $pdesc = '';

                // catégories principales
                for ( $i = 0; $i < $pcount; $i++ ) {
                        $arr_cat = array();
                        $arr_scat = array();
                        $arr_subcat = array();
                        $cptsubcat = 0;
                        $topic_id = $parray[$i]->topic_id();

                        $title = $ts->htmlSpecialChars($parray[$i]->topic_title());
                        $ptitle .= $title.' -';

                        $desc = $ts->htmlSpecialChars($parray[$i]->topic_desc());
                        $pdesc .= $desc.' -';

                        // pk correct popbox code for valid W3C
                        if ( $parray[$i]->img() != '')
                        {
                                // $arr_cat[$i]['image'] = "<img  id=\"".$i."\" alt=\"".$i."\" src=\"".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$parray[$i]->img()."\" pbshowcaption=\"false\" pbcaption=\"".$i."\" class=\"PopBoxImageSmall\" onclick=\"Pop(this,50,'PopBoxImageLarge');\" />";
                                $arr_cat[$i]['image'] = "<img  id=\"photo".$i."\" alt=\"photo".$i."\" src=\"".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$parray[$i]->img()."\" class=\"PopBoxImageSmall\" onclick=\"Pop(this,50,'PopBoxImageLarge');\" />";
                        }
                        else
                        {
                                $arr_cat[$i]['image'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/no_dispo_mini.gif' align='middle' alt='' />";
                        }
                        $arr_cat[$i]['link'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id=' . $topic_id;
                        $arr_cat[$i]['id'] = $topic_id;
                        $arr_cat[$i]['title'] = $title;
                        $arr_cat[$i]['desc'] = $desc;

                        if (in_array($topic_id, $lastchildren)) {
                                $arr_cat[$i]['nb'] = (array_key_exists($topic_id, $nbadspercat)) ?  "(".$nbadspercat[$topic_id].")": '';
                                $arr_cat[$i]['new'] = (array_key_exists($topic_id, $newads)) ? $newads[$topic_id]: '';
                        }
                        $level = 0;
                        $childcats =  AdsCategory::getFirstChildArr($topic_id, 'weight');
                        unset($arr_scat);

                        showsubcat($childcats, 0, $topic_id, $topic_id);
                        if ($tpltype == 1) {
                        // ajout blocks vides si template en lignes
                                $mod = count($childcats) % $nbcol;
                                $adjust = ($mod > 0) ? $nbcol - $mod : 0;
                                for ( $j = 0; $j < $adjust; $j++ ) {
                                        $cptsubcat++;
                                        $arr_scat['newcol']=1;
                                        array_push($arr_subcat, $arr_scat);
                                }
                        } else {
                        // calcul saut de ligne si template en colonnes
                                $mod = ($i+1) % $nbcol;
                                $arr_cat[$i]['newline'] = ($mod == 0) ? true : false;
                        }
                        $arr_cat[$i]['subcat'] = $arr_subcat;
                        $xoopsTpl->append('categories', $arr_cat[$i]);
                }
                if ($tpltype == 2) {
                // ajout blocks vides si template en colonnes
                        unset($arr_cat);
                        $mod = $pcount % $nbcol;
                        $adjust = ($mod > 0) ? $nbcol - $mod : 0;
                        for ( $j = 0; $j < $adjust; $j++ ) {
                                $arr_cat[$j]['title'] = "";
                                $xoopsTpl->append('categories', $arr_cat[$j]);
                        }

                }
                $xoopsTpl->assign('nb_col_or_row', $nbcol);
                // $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name() . ' -' . $ptitle);
                $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name());

                // pk add meta description from main category list
                $catlistclean = '';
                for ( $i = 0; $i < $pcount; $i++ ) {
                $title = $ts->htmlSpecialChars($parray[$i]->topic_title());
                $catlistclean .= $title.', ';
                }
                $xoTheme->addMeta('meta', 'description', substr($catlistclean, 0, 140));
                // end pk mod

                // nombre annonces actives
                $criteria = new CriteriaCompo(new Criteria('waiting', '0'));
                $criteria->add(new Criteria('suspend', '0'));
                $criteria->add(new Criteria('published', time(),'<'));
                $criteria->add(new Criteria('expired', time(),'>'));

                $nbads = $ads_handler->getCount($criteria);
                $xoopsTpl->assign('nbads', $nbads);
                $xoopsTpl->assign('total_annonces',sprintf( _MD_CATADS_ACTUALY, $nbads));
                if ($xoopsModuleConfig['moderated'] == '1') {
                        $xoopsTpl->assign('total_confirm', sprintf(_MD_CATADS_ANDWAIT, $ads_wait));
                }

                $xoopsTpl->assign('show_card', $xoopsModuleConfig['show_card']);


if ( $xoopsModuleConfig['show_card'] == '0' )
{
                // dernières annonces
                if ($xoopsModuleConfig['nb_news'] > 0 )
                {
                        if ($nbads > 0)
                        {
                                $xoopsTpl->assign('lang_title', _MD_CATADS_LASTADD);

                                //Recuperer les annonces en fonction des categories
                                $permHandler = CatadsPermHandler::getHandler();
                                if( $permHandler->listAds($xoopsUser, 'catads_access') )
                                {
                                        $show_topic_id = $permHandler->listAds($xoopsUser, 'catads_access');
                                }
                                $arr_lastads = showListAds($show_topic_id, 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, 0, $xoopsModuleConfig['nb_news']);
                                $xoopsTpl->assign('items', $arr_lastads);
                        }
                }
}
else
{
                $xoopsTpl->assign('total_annonces',sprintf( _MD_CATADS_ACTUALY, $nbads));
                $xoopsTpl->assign('afficherBlocNbAnnonces', $afficherBlocNbAnnonces);
                $xoopsTpl->assign('afficherBlocDernieresAnnonces', $afficherBlocDernieresAnnonces);

                //Recuperer les annonces en fonction des categories
                $permHandler = CatadsPermHandler::getHandler();
                if( $permHandler->listAds($xoopsUser, 'catads_access') )
                {
                        $show_topic_id = $permHandler->listAds($xoopsUser, 'catads_access');
                }
                $arr_lastads = showListLastAds($show_topic_id, $reduireTitle, 0, 0, $afficherNbDernieresAnnonces);
                $xoopsTpl->assign('items', $arr_lastads);
}

// pk pass ad_type pref to template
$xoopsTpl->assign('show_ad_type', $show_ad_type);

// pk transfer pop-up script CSS to here from catads_index.html, catads_sub_list.html and catads_block_new.html
$xoopsTpl->assign("xoops_module_header",'<link rel="stylesheet" type="text/css" href="style.css" /> <link rel="stylesheet" type="text/css" href="css/highslide.css" />');

include(XOOPS_ROOT_PATH."/footer.php");
?>