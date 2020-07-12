<?php
// $Id: adslist.php,v 1.1 2004/07/17 C. Felix AKA the Cat
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
include_once("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/class/pagenav.php");
include_once XOOPS_ROOT_PATH.'/modules/catads/class/permissions.php';

// pk define VARs

$affichage_titre = isset($_GET['affichage_titre']) ? $_GET['affichage_titre'] : '';
$affichage_prix = isset($_GET['affichage_prix']) ? $_GET['affichage_prix'] : '';
$affichage_option_prix = isset($_GET['affichage_option_prix']) ? $_GET['affichage_option_prix'] : '';
$affichage_localisation = isset($_GET['affichage_localisation']) ? $_GET['affichage_localisation'] : '';
$affichage_date = isset($_GET['affichage_date']) ? $_GET['affichage_date'] : '';


global $pk_topic_id, $xoTheme ;

// topic title undefined
$topic_title = isset($_GET['topic_title']) ? $_GET['topic_title'] : '';

// pk get search results?
$search = isset($_GET['search']) ? $_GET['search'] : '';

// pk more defines
$nbads = isset($_GET['nbads']) ? $_GET['nbads'] : '0';
$arr_ads = isset($_GET['arr_ads']) ? $_GET['arr_ads'] : '';
$newads = isset($_REQUEST['newads']) ? $_REQUEST['newads'] : ''; 

$ads_handler =& xoops_getmodulehandler('ads');
$myts = MyTextSanitizer::getInstance();

foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}
if(!isset($debut)) $debut=0;

// pk topic ID
$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;

$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
$ts =& MyTextSanitizer::getInstance();

$nbcolonnes = $xoopsModuleConfig['nbcol'];

// pk get module pref.
$show_ad_type = $xoopsModuleConfig['show_ad_type'] ;

$xoopsOption['template_main'] = 'catads_adslist.html';
include_once(XOOPS_ROOT_PATH."/header.php");

//rss hack by InBox Solutions for Philippe Montalbetti
$link=sprintf("<a href='%s' title='%s'><img src='%s' border='0' alt='%s' /></a>",XOOPS_URL."/modules/catads/backend.php?id=".$topic_id, _MD_CATADS_RSSFEED, XOOPS_URL."/modules/catads/images/icon/rss.gif",_MD_CATADS_RSSFEED);
$xoopsTpl->assign('rssfeed_link',$link);
//end rss hack by InBox Solutions for Philippe Montalbetti


        function getFirstChild($topic_id = 0) {
                global $allcat;
                $firstChild = array();
                        foreach($allcat as $onechild)         {
                                if( $onechild['topic_pid'] == $topic_id) {
                                        array_push($firstChild, $onechild);
                                }
                        }
                return $firstChild;
        }

        function showsubcat($categorys, $level = 0, $topic_id = 0, $topic_pid) {
                global $xoopsModule, $ts, $lastchildren, $nbadspercat, $newads, $arr_subcat, $cptsubcat, $nbcol, $tpltype;


                foreach($categorys as $onecat)         {


                        $link = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id=' . $onecat['topic_id'];
                        $title = $ts->htmlSpecialChars($onecat['topic_title']);
                        $desc = $ts->htmlSpecialChars($onecat['topic_desc']);
                        if (in_array($onecat['topic_id'], $lastchildren)) {
                                $arr_scat['nb'] = (array_key_exists($onecat['topic_id'], $nbadspercat)) ?  "(".$nbadspercat[$onecat['topic_id']].")": '';
                                $arr_scat['new'] = (array_key_exists($onecat['topic_id'], $newads)) ? $newads[$onecat['topic_id']]: '';
                        }
                        $arr_scat['link'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id=' . $onecat['topic_id'];
                        $arr_scat['title'] = $title;
                        $arr_scat['desc'] = $desc;
                        $arr_scat['img'] = "<img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$onecat['img']."' align='middle' alt='' />";
                        if ($level == 0 && $tpltype == 1) {
                                $arr_scat['newcol'] = ($cptsubcat > 0) ? true : false;
                                $cptsubcat++;
                                $arr_scat['newline'] = ($cptsubcat % $nbcol == 1) ? true : false;
                        }
                        array_push($arr_subcat, $arr_scat);
                        $childcats =  getFirstChild($onecat['topic_id']);
                                if (count($childcats) > 0) {
                                        showsubcat($childcats, $level + 1, $onecat['topic_id'], $pid);
                                }
                        }
                return;
                }

         // pk set global criteria - this change puts SQL statement in correct order (OR followed by AND)
         $criteria = new CriteriaCompo();

// pk if a category is selected

if ($topic_id > 0)
{

        // additional criteria

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');

        //Permissions
        $permHandler = CatadsPermHandler::getHandler();
        if(!$permHandler->isAllowed($xoopsUser, 'catads_access', $topic_id))
        {
                redirect_header("index.php", 3, _NOPERM);
                exit;
        }
        include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
        include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
        // verification existence catégorie
        $cat = new AdsCategory($topic_id);
        if ($cat->topic_id != $topic_id)
        {
                redirect_header("index.php",1,_MD_CATADS_CAT_NOEXIST);
        }

        // test si rubrique terminale
        $lastchildren = AdsCategory::getAllLastChild();
        $lastChild = false;
        foreach($lastchildren as $onechild)
        {
                if( $onechild == $topic_id)
                {
                        $lastChild = true;
                }
        }

        $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");

        if ($lastChild) {
        // rubrique terminale
                $criteria->add(new Criteria('cat_id', $topic_id));
                $nbads = $ads_handler->getCount($criteria);
                $arr_ads = showListAds($topic_id, 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $debut, $xoopsModuleConfig['nb_perpage']);
                $xoopsTpl->assign('add_perm', true);

        } else {

        // rubrique intermédiaire

                $criteria2 = new CriteriaCompo();

                $allcat = $mytree->getAllChildId($topic_id);

        // Début boucle Tableau sous-catégories

        // pk sub-cat display built here

        $sous_cat = AdsCategory::getFirstChildArr2($topic_id, 'weight');

        $show_sous_cat = '<br /><table class="outer"><tr>';

        $countcat = 1;

        echo $newads;

        // pk sub-cat loop for display in each category. Passed to adslist template as sous_cat


        // pk set consistent TD width
        if($nbcolonnes == '1') {
        $td_width = "100%";
        } else if($nbcolonnes == '2') {
        $td_width = "50%";
        } else if($nbcolonnes == '3') {
        $td_width = "33%";
        } else if($nbcolonnes == '4') {
        $td_width = "25%";
        } else if($nbcolonnes == '5') {
        $td_width = "20%";
        }

        foreach($sous_cat as $onecat)
        {

                $show_sous_cat .= '<td class="even" width="'.$td_width.'">
                <a href="'.XOOPS_URL. '/modules/' . $xoopsModule->dirname() . '/adslist.php?topic_id='.$onecat['topic_id'].'">'.$ts->htmlSpecialChars($onecat['topic_title']).'</a>
                </td>';

                $countcat++;

                $new_col = ($countcat % $nbcolonnes == 1) ? true : false;

                if ($new_col == true )
                {
                        $show_sous_cat .= '</tr><tr>';
                }

        }

        $show_sous_cat .= '</tr></table>';

        $xoopsTpl->assign('sous_cat', $show_sous_cat);


                $nbads = $ads_handler->getCount($criteria);

                $arr_ads = showListAds($topic_id, 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $debut, $xoopsModuleConfig['nb_perpage']);

                $xoopsTpl->assign('add_perm', false);

        }

        $pagenav = new XoopsPageNav($nbads, $xoopsModuleConfig['nb_perpage'], $debut, "debut", "topic_id=".$topic_id);

        $xoopsTpl->assign('topic_id', $topic_id);


        // path info

        $pathstring = "<a href='index.php'>"._MD_CATADS_MAIN."</a>&nbsp;:&nbsp;";
        $pathstring .= $mytree->getNicePathFromId($topic_id, "topic_title", "adslist.php?op=");

        $sql = $xoopsDB->query("SELECT topic_desc FROM ".$xoopsDB->prefix("catads_cat")." WHERE topic_id = ".$topic_id);

        list($topic_desc) = $xoopsDB->fetchRow($sql);
        $pathstring = str_replace(":"," <img src='".XOOPS_URL."/modules/catads/images/icon/arrow.gif' border='0' alt='' /> ",$pathstring);
        $xoopsTpl->assign('cat_path', $pathstring);

        $cat_path = $mytree->getpathFromId( $topic_id, 'topic_title');
        $cat_desc = $mytree->getpathFromId( $topic_id, 'topic_desc');

        // titre page pour référencement
        $cat_path2 = str_replace("/"," : ",$cat_path);
        $cat_path3 = str_replace("/"," : ",$cat_desc);
        $xoopsTpl->assign('lang_title', substr($cat_path2, 2));
        if ( $xoopsModuleConfig['show_cat_desc'] > 0 )
        {
                $xoopsTpl->assign('lang_desc', $topic_desc);
        }
        $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name() . '' . $cat_path2);
        $cat_path = str_replace("/"," <img src='".XOOPS_URL."/modules/catads/images/icon/arrow.gif' border='0' alt='' /> ",$cat_path);
        $xoopsTpl->assign('add_tab', 1);

        // pk add meta keywords and description tags for categories
        $desctextclean = strip_tags($topic_desc, '<font><img><b><i><u>');
        $xoTheme->addMeta('meta', 'description', substr($desctextclean, 0, 140));
        $cat_path_for_keywords =  str_replace(":", "", $cat_path2);
        $cat_path_for_keywords =  trim($cat_path_for_keywords);
        $keyword_tags = $cat_path_for_keywords ;
        $xoTheme->addMeta('meta', 'keywords', $keyword_tags);
        // end pk mod

}
elseif ($uid > 0)
{

        global $xoopsUser;

        $ads_hnd =& xoops_getmodulehandler('ads', 'catads');

        $permHandler = CatadsPermHandler::getHandler();

        $criteria = new CriteriaCompo();

        $topic_id = !isset($_REQUEST['topic_id'])? NULL : $_REQUEST['topic_id'];

                $topic = $permHandler->listAds($xoopsUser, 'catads_access', $topic_id);

                include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

                $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
                $criteria5 = new CriteriaCompo();
                $allcat = $mytree->getAllChildId($topic_id);

                $i = 0;

                foreach($topic as $valeur)
                {
                        foreach($allcat as $valeur1)
                        {
                                        if ($valeur == $valeur1)
                                        {
                                                $show_topic_id[$i] = $valeur1;
                                                $i++;
                                        }
                        }
                }

                for($j=0; $j<$i; $j++)
                {
                         $criteria5->add(new Criteria('cat_id',$show_topic_id[$j]), 'OR');
                }

           $criteria->add($criteria5);

        // additional criteria

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');

        //echo $uid;
        $criteria->add(new Criteria('uid', $uid));

        $nbads = $ads_handler->getCount($criteria);

        $start = $debut;

        // $start = '0';

        $limit = $xoopsModuleConfig['nb_perpage'];

        $arr_ads = showListAdsByUser('', $uid, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $debut, $limit);

        $pagenav = new XoopsPageNav($nbads, $limit, $debut, "debut", "uid=".$uid);

        $xoopsTpl->assign('uid', $uid);

        $xoopsTpl->assign('lang_title', _MD_CATADS_ALLADS.XoopsUser::getUnameFromId($uid));

}



// pk search (PHP5 version)
// The initial $search VAR value of '1' comes from the search form
// Search criteria are now added to session variables to enable pagination.
// Secondary function paginates results using session variable data.

elseif ($search == '1')
{
          // pk connect to session
          session_start();

          // pk clear any session VARs created by new search pagination method
          unset($_SESSION["pk_words"]);
          unset($pk_words);
          unset($_SESSION["pk_topic_id"]);
          unset($pk_topic_id);
          unset($_SESSION["pk_town"]);
          unset($pk_town);
          unset($_SESSION["pk_zipcod"]);
          unset($pk_zipcod);
          unset($_SESSION["pk_price_start"]);
          unset($pk_price_start);
          unset($_SESSION["pk_price_end"]);
          unset($pk_price_end);
          unset($_SESSION["pk_region"]);
          unset($pk_region);
          unset($_SESSION["pk_departement"]);
          unset($pk_departement);

          $criteria = new CriteriaCompo();

          if ( isset($_REQUEST['words']) && trim($_REQUEST['words']) != '' )
          {
                $words = split(' ', $myts->addSlashes($_REQUEST['words']));
                $nb_words = count($words);

                // pk group multiple 'OR' statements
                $criteria4 = new CriteriaCompo();

                for ( $i = 0; $i < $nb_words; $i++ )
                {
                        $criteria4->add(new Criteria('ads_title', '%'.$words[$i].'%', 'LIKE'), 'OR');
                        $criteria4->add(new Criteria('ads_desc', '%'.$words[$i].'%','LIKE'), 'OR');
                        // pk add ads_tags to search scope (required if custom tags are to be allowed)
                        $criteria4->add(new Criteria('ads_tags', '%'.$words[$i].'%','LIKE'), 'OR');
                        // end pk mod
                        $_SESSION["pk_words"] = $words[$i];
                }

                $criteria->add($criteria4);
        }

        // pk get topic_id from topic_title select VAR
        $topic_id = $topic_title['topic_id'];

        // pk bugfix - set topic ID
        // if( isset( $categorie ) && !empty( $categorie ) )
        if( isset( $topic_title ) && !empty( $topic_title ) )
        {
                // $criteria->add(new Criteria('cat_id', $topic_id,'='), 'AND');
                $criteria->add(new Criteria('cat_id', $topic_id,'='));

                $_SESSION["pk_topic_id"] = $topic_id;
        }

        if( isset( $town ) && !empty( $town ) )
        {
                $town = $myts->addSlashes($town);
                $criteria->add(new Criteria('town', '%'.$town.'%','LIKE'), 'AND');
                // $criteria->add(new Criteria('town', '%'.$town.'%','LIKE'));

                $_SESSION["pk_town"] = $town;
        }

        if( isset( $zipcod ) && !empty( $zipcod ) )
        {
                $zipcod = $myts->addSlashes($zipcod);
                // pk postal code was originaly exact match - now changed to enable partial match.
                // $criteria->add(new Criteria('codpost', $zipcod,'='));
                $criteria->add(new Criteria('codpost', '%'.$zipcod.'%','LIKE'), 'AND');

                $_SESSION["pk_zipcod"] = $zipcod;
        }

        if( isset( $price_start ) && !empty( $price_start ) )
        {
                if(is_numeric($price_start)){
                $criteria->add(new Criteria('price', $price_start,'>='), 'AND');
                $_SESSION["pk_price_start"] = $price_start;
                } else {
                redirect_header("index.php",1,_MD_CATADS_INVALIDPRICE);
                }
        }

        if( isset( $price_end ) && !empty( $price_end ) )
        {
                if(is_numeric($price_end)){
                $criteria->add(new Criteria('price', $price_end,'<='), 'AND');
                $_SESSION["pk_price_end"] = $price_end;
                } else {
                redirect_header("index.php",1,_MD_CATADS_INVALIDPRICE);
                }

        }

        if( !empty( $region ) && !empty( $region ) )
        {
                $region = intval($region);
                // pk region is exact match - but selected from menu so not a problem
                $criteria->add(new Criteria('region', $region,'='), 'AND');

                $_SESSION["pk_region"] = $region;

        }

        if( !empty( $departement ) && !empty( $departement ) &&  $departement != "other" )
        {
                $departement = intval($departement);
                // pk departement is exact match - but selected from menu so not a problem
                $criteria->add(new Criteria('departement', $departement,'='), 'AND');

                $_SESSION["pk_departement"] = $departement;
        }


        global $xoopsUser;

        $ads_hnd =& xoops_getmodulehandler('ads', 'catads');

        $permHandler = CatadsPermHandler::getHandler();

        $topic_id = !isset($_REQUEST['topic_id'])? NULL : $_REQUEST['topic_id'];

                $topic = $permHandler->listAds($xoopsUser, 'catads_access');

                include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

                $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
                $criteria5 = new CriteriaCompo();
                $allcat = $mytree->getAllChildId($topic_id);

                $i = 0;

                foreach($topic as $valeur)
                {
                        foreach($allcat as $valeur1)
                        {
                                        if ($valeur == $valeur1)
                                        {
                                                $show_topic_id[$i] = $valeur1;
                                                $i++;
                                        }
                        }
                }

                for($j=0; $j<$i; $j++)
                {
                         $criteria5->add(new Criteria('cat_id',$show_topic_id[$j]), 'OR');
                }

           $criteria->add($criteria5);

        // additional criteria

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');

        $nbads = $ads_handler->getCount($criteria);

        // pk set new state for search VAR to enable pagination if required
        $search = '2';

        $start = '0';

        $limit = $xoopsModuleConfig['nb_perpage'];

        $arr_ads = showListSearchAds($search, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $criteria, $start, $limit);

        $xoopsTpl->assign('search', $search);

        $pagenav = new XoopsPageNav($nbads, $limit, $debut, "debut", "search=".$search);

        $xoopsTpl->assign('lang_title', sprintf(_MD_CATADS_SEARCH_NB, $nbads));

        $xoopsTpl->assign('add_tab', 0);

}

// pk process search results if pagination required
elseif ($search == '2')
{

     $search_criteria = new CriteriaCompo();


     if( isset($_SESSION['pk_words']) && !empty($_SESSION['pk_words']) )

          {
                $words = split(' ', $myts->addSlashes($_SESSION['pk_words']));
                $nb_words = count($words);

                // pk group multiple 'OR' statements
                $criteria4 = new CriteriaCompo();

                for ( $i = 0; $i < $nb_words; $i++ )
                {
                        // pk Final condition sets '=' (exact match) or 'LIKE' close match
                        $criteria4->add(new Criteria('ads_title', '%'.$words[$i].'%', 'LIKE'), 'OR');
                        $criteria4->add(new Criteria('ads_desc', '%'.$words[$i].'%','LIKE'), 'OR');
                        // pk add ads_tags to search scope (required if custom tags are to be allowed)
                        $criteria4->add(new Criteria('ads_tags', '%'.$words[$i].'%','LIKE'), 'OR');
                        // end pk mod

                }

                $search_criteria->add($criteria4);

         }

        if( isset($_SESSION['pk_topic_id']) && !empty($_SESSION['pk_topic_id']) ){
        $pk_topic_id = $_SESSION['pk_topic_id'] ;
        $search_criteria->add(new Criteria('cat_id', $pk_topic_id), 'AND'); }


        if( isset($_SESSION['pk_town']) && !empty($_SESSION['pk_town']) ){
        $pk_town = $_SESSION['pk_town'] ;
        $search_criteria->add(new Criteria('town', '%'.$pk_town.'%','LIKE'), 'AND');
        }

        if( isset($_SESSION['pk_zipcod']) && !empty($_SESSION['pk_zipcod']) ){
        $pk_zipcod = $_SESSION['pk_zipcod'] ;
        $search_criteria->add(new Criteria('codpost', '%'.$pk_zipcod.'%','LIKE'), 'AND');
        }

        if( isset($_SESSION['pk_price_start']) && !empty($_SESSION['pk_price_start']) ){
        $pk_price_start = $_SESSION['pk_price_start'] ;
        $search_criteria->add(new Criteria('price', $pk_price_start,'>='), 'AND');
        }

        if( isset($_SESSION['pk_price_end']) && !empty($_SESSION['pk_price_end']) ){
        $pk_price_end = $_SESSION['pk_price_end'] ;
        $search_criteria->add(new Criteria('price', $pk_price_end,'<='), 'AND');
        }

        if( isset($_SESSION['pk_region']) && !empty($_SESSION['pk_region']) ){
        $pk_region = $_SESSION['pk_region'] ;
        $search_criteria->add(new Criteria('region', $pk_region), 'AND');
        }

        if( isset($_SESSION['pk_departement']) && !empty($_SESSION['pk_departement']) ){
        $pk_departement = $_SESSION['pk_departement'] ;
        $search_criteria->add(new Criteria('departement', $pk_departement), 'AND');
        }

        $search_criteria->add(new Criteria('waiting','0'));
        $search_criteria->add(new Criteria('suspend','0'));
        $search_criteria->add(new Criteria('published', time(),'<'));
        $search_criteria->add(new Criteria('expired', time(),'>'));

        $nbads = $ads_handler->getCount($search_criteria);

        $search = '2';

        // $start = '0';

        $start = $debut ;

        $limit = $xoopsModuleConfig['nb_perpage'];

        $arr_ads = showListSearchAds($search, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $search_criteria, $start, $limit);

        $xoopsTpl->assign('search', $search);

        $pagenav = new XoopsPageNav($nbads, $limit, $debut, "debut", "search=".$search);

        $xoopsTpl->assign('lang_title', sprintf(_MD_CATADS_SEARCH_NB, $nbads));

}

// pk global assignments

$xoopsTpl->assign('nbads', $nbads);

$xoopsTpl->assign('items', $arr_ads);

$xoopsTpl->assign('nav_page', $pagenav->renderNav());

$xoopsTpl->assign('show_ad_type', $show_ad_type);


$xoopsTpl->assign("xoops_module_header",
'<link rel="stylesheet" type="text/css" href="style.css" /> <link rel="stylesheet" type="text/css" href="css/highslide.css" />');

include(XOOPS_ROOT_PATH."/footer.php");
?>