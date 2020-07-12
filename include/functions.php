<?php
// $Id: functions.php,v 1.2 2005/02/06 C. Felix AKA the Cat
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

include_once(XOOPS_ROOT_PATH."/modules/catads/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/ads.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/permissions.php");

function catads_upload($i) {
        global $xoopsModule, $xoopsModuleConfig, $preview_name, $msgstop;
        $created = time();
        $ext = preg_replace( "/^.+\.([^.]+)$/sU" , "\\1" , $_FILES['photo'.$i]['name']) ;
        include_once(XOOPS_ROOT_PATH."/class/uploader.php");
        $field = $_POST["xoops_upload_file"][$i] ;
        if( !empty( $field ) || $field != "" ) {
                // Check if file uploaded
                if( $_FILES[$field]['tmp_name'] == "" || ! is_readable( $_FILES[$field]['tmp_name'] ) ) {
                        $msgstop .= sprintf(_MD_CATADS_FILEERROR, $xoopsModuleConfig['photo_maxsize']);
                } else {
                        $photos_dir = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original" ;
                        $array_allowed_mimetypes = array("image/gif","image/pjpeg","image/jpeg","image/x-png") ;
                        $uploader = new XoopsMediaUploader( $photos_dir , $array_allowed_mimetypes , $xoopsModuleConfig['photo_maxsize'] ,  $xoopsModuleConfig['photo_maxwidth'] ,  $xoopsModuleConfig['photo_maxheight'] ) ;
                        if( $uploader->fetchMedia( $field ) && $uploader->upload() ) {
                                @unlink("$photos_dir/".$preview_name[$i]);
                                $tmp_name = $uploader->getSavedFileName() ;
                                $ext = preg_replace( "/^.+\.([^.]+)$/sU" , "\\1" , $tmp_name ) ;
                                $preview_name[$i] = 'tmp_'.$created.'_'.$i.'.'.$ext;
                                rename( "$photos_dir/$tmp_name" , "$photos_dir/$preview_name[$i]" ) ;
                        } else {
                                $msgstop.= $uploader->getErrors();
                        }
                }
        }
}


function showListLastAds($topic_id = 0, $reduireTitle = 0, $uid = 0, $start = 0, $limit = 4) {
    global $ads_handler;

                $criteria = new CriteriaCompo(new Criteria('waiting', '0'));

                $criteria->add(new Criteria('suspend','0'));
                $criteria->add(new Criteria('published', time(), '<'));
                $criteria->add(new Criteria('expired', time(),'>'));
                if (is_array($topic_id)){
                        $criteria2 = new CriteriaCompo();
                        foreach($topic_id as $onecat) {
                                $criteria2->add(new Criteria('cat_id',$onecat), 'OR');
                        }
                        $criteria->add($criteria2);
                } elseif ($topic_id > 0) {
                        $criteria->add(new Criteria('cat_id', $topic_id));
                } elseif ($uid > 0)
                        $criteria->add(new Criteria('uid', $uid));

                $criteria->setSort('published');
                $criteria->setOrder('DESC');
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $ads = $ads_handler->getObjects($criteria);
                $listads = getAdsItem($ads, 0, $reduireTitle);
        return $listads;
}


function showListAds($topic_id = 0, $uid = 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $start = '', $limit = '') {
    global $ads_handler, $xoopsDB, $xoopsUser;

       $criteria = new CriteriaCompo();

       // additional criteria

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');

                if (is_array($topic_id)){
                        $criteria2 = new CriteriaCompo();
                        foreach($topic_id as $onecat) {
                                $criteria2->add(new Criteria('cat_id',$onecat), 'OR');
                        }
                        $criteria->add($criteria2);

                } elseif ($topic_id > 0) {
                        $criteria->add(new Criteria('cat_id', $topic_id));
                } elseif ($uid > 0)
                        $criteria->add(new Criteria('uid', $uid));
//Ajout
        //On regarde si les variables ne sont egal a rien
        if( $affichage_titre != '' || $affichage_prix != '' || $affichage_option_prix != '' || $affichage_localisation != '' || $affichage_date != '')
        {
        //Verif titre
                if ($affichage_titre != '' && $affichage_titre == 'ASC')  {
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_titre != '' && $affichage_titre == 'DESC'){
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('DESC');
                }
        //Verif prix
                if ($affichage_prix != '' && $affichage_prix == 'ASC')  {
                        $criteria->setSort('price');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_prix != '' && $affichage_prix == 'DESC'){
                        $criteria->setSort('price');
                        $criteria->setOrder('DESC');
                }
        //Verif option prix
                if ($affichage_option_prix != '' && $affichage_option_prix == 'ASC')  {
                        $criteria->setSort('price_option');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_option_prix != '' && $affichage_option_prix == 'DESC'){
                        $criteria->setSort('price_option');
                        $criteria->setOrder('DESC');
                }
        //Verif localisation
                if ($affichage_localisation != '' && $affichage_localisation == 'ASC')  {
                        $criteria->setSort('codpost');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_localisation != '' && $affichage_localisation == 'DESC'){
                        $criteria->setSort('codpost');
                        $criteria->setOrder('DESC');
                }
        //Verif date
                if ($affichage_date != '' && $affichage_date == 'ASC')  {
                        $criteria->setSort('published');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_date != '' && $affichage_date == 'DESC'){
                        $criteria->setSort('published');
                        $criteria->setOrder('DESC');
                }
        }
        else
        {
        //Autrement on affiche les annonces par date, la plus recente
                $criteria->setSort('published');
                $criteria->setOrder('DESC');
        }
        //Ajout
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $ads = $ads_handler->getObjects($criteria);
                $listads = getAdsItem($ads);
        return $listads;
}



function showListSearchAds($search, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $criteria, $start='', $limit=''){
    global $ads_handler, $xoopsDB, $xoopsUser, $myts;

        $criteria = new CriteriaCompo();

         if ( isset($_SESSION['pk_words']) && trim($_SESSION['pk_words']) != '' ) {

                $criteria4 = new CriteriaCompo();

                        $words = $_SESSION['pk_words'];
                        $words = split(' ', $myts->addSlashes($words));
                        $nb_words = count($words);
                        for ( $i = 0; $i < $nb_words; $i++ )
                        {
                        $criteria4->add(new Criteria('ads_title', '%'.$words[$i].'%', 'LIKE'), 'OR');
                        $criteria4->add(new Criteria('ads_desc', '%'.$words[$i].'%','LIKE'), 'OR');
                        $criteria4->add(new Criteria('ads_tags', '%'.$words[$i].'%','LIKE'), 'OR');
                        }

                $criteria->add($criteria4);
        }


                $ads_hnd =& xoops_getmodulehandler('ads', 'catads');

                $permHandler = CatadsPermHandler::getHandler();

                // $topic_id = !isset($_SESSION['pk_topic_id'])? NULL : $_SESSION['pk_topic_id'];

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



               // pk get specific topic_id takes precedent over perms function
               // $topic_id = !isset($_SESSION['pk_topic_id'])? NULL : $_SESSION['pk_topic_id'];
               if ( isset($_SESSION['pk_topic_id']) && trim($_SESSION['pk_topic_id']) != '' ) {
               $topic_id = $_SESSION["pk_topic_id"];
               $criteria->add(new Criteria('cat_id', $topic_id,'='));
               }



        if ( isset($_SESSION['pk_town']) && trim($_SESSION['pk_town']) != '' ) {
        $town = $_SESSION["pk_town"];
        $criteria->add(new Criteria('town', '%'.$town.'%','LIKE'), 'AND');
        }

        if ( isset($_SESSION['pk_zipcod']) && trim($_SESSION['pk_zipcod']) != '' ) {
        $zipcod = $_SESSION["pk_zipcod"];
        $criteria->add(new Criteria('codpost', '%'.$zipcod.'%','LIKE'), 'AND');
        }

        if ( isset($_SESSION['pk_price_start']) && trim($_SESSION['pk_price_start']) != '' ) {
        $price_start = $_SESSION["pk_price_start"];
        $criteria->add(new Criteria('price', $price_start,'>='), 'AND');
        }

        if ( isset($_SESSION['pk_price_end']) && trim($_SESSION['pk_price_end']) != '' ) {
        $price_end = $_SESSION["pk_price_end"];
        $criteria->add(new Criteria('price', $price_end,'<='), 'AND');
        }

        if ( isset($_SESSION['pk_region']) && trim($_SESSION['pk_region']) != '' ) {
        $region = $_SESSION["pk_region"];
        $criteria->add(new Criteria('region', $region,'='), 'AND');
        }

        if ( isset($_SESSION['pk_departement']) && trim($_SESSION['pk_departement']) != '' ) {
        $departement = $_SESSION["pk_departement"];
        $criteria->add(new Criteria('departement', $departement,'='), 'AND');
        }


        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');


//Ajout
        //On regarde si les variables ne sont egal a rien
        if( $affichage_titre != '' || $affichage_prix != '' || $affichage_option_prix != '' || $affichage_localisation != '' || $affichage_date != '')
        {
        //Verif titre
                if ($affichage_titre != '' && $affichage_titre == 'ASC')  {
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_titre != '' && $affichage_titre == 'DESC'){
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('DESC');
                }
        //Verif prix
                if ($affichage_prix != '' && $affichage_prix == 'ASC')  {
                        $criteria->setSort('price');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_prix != '' && $affichage_prix == 'DESC'){
                        $criteria->setSort('price');
                        $criteria->setOrder('DESC');
                }
        //Verif option prix
                if ($affichage_option_prix != '' && $affichage_option_prix == 'ASC')  {
                        $criteria->setSort('price_option');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_option_prix != '' && $affichage_option_prix == 'DESC'){
                        $criteria->setSort('price_option');
                        $criteria->setOrder('DESC');
                }
        //Verif localisation
                if ($affichage_localisation != '' && $affichage_localisation == 'ASC')  {
                        $criteria->setSort('codpost');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_localisation != '' && $affichage_localisation == 'DESC'){
                        $criteria->setSort('codpost');
                        $criteria->setOrder('DESC');
                }
        //Verif date
                if ($affichage_date != '' && $affichage_date == 'ASC')  {
                        $criteria->setSort('published');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_date != '' && $affichage_date == 'DESC'){
                        $criteria->setSort('published');
                        $criteria->setOrder('DESC');
                }
        }
        else
        {
        //Autrement on affiche les annonces par date, la plus recente
                $criteria->setSort('published');
                $criteria->setOrder('DESC');
        }
        //Ajout
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $ads = $ads_handler->getObjects($criteria);
                $listads = getAdsItem($ads);
        return $listads;
}


function showListAdsByUser($topic_id = 0, $uid = 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $start = '', $limit = '') {
    global $ads_handler, $xoopsDB, $xoopsUser;

        $ads_hnd =& xoops_getmodulehandler('ads', 'catads');

        $permHandler = CatadsPermHandler::getHandler();

        $criteria = new CriteriaCompo();

        $uid = !isset($_REQUEST['uid'])? NULL : $_REQUEST['uid'];

        $topic_id = !isset($_REQUEST['topic_id'])? NULL : $_REQUEST['topic_id'];

                $topic = $permHandler->listAds($xoopsUser, 'catads_access', $topic_id);

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

        $criteria->add(new Criteria('uid', $uid));

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');


//Ajout
        //On regarde si les variables ne sont egal a rien
        if( $affichage_titre != '' || $affichage_prix != '' || $affichage_option_prix != '' || $affichage_localisation != '' || $affichage_date != '')
        {
        //Verif titre
                if ($affichage_titre != '' && $affichage_titre == 'ASC')  {
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_titre != '' && $affichage_titre == 'DESC'){
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('DESC');
                }
        //Verif prix
                if ($affichage_prix != '' && $affichage_prix == 'ASC')  {
                        $criteria->setSort('price');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_prix != '' && $affichage_prix == 'DESC'){
                        $criteria->setSort('price');
                        $criteria->setOrder('DESC');
                }
        //Verif option prix
                if ($affichage_option_prix != '' && $affichage_option_prix == 'ASC')  {
                        $criteria->setSort('price_option');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_option_prix != '' && $affichage_option_prix == 'DESC'){
                        $criteria->setSort('price_option');
                        $criteria->setOrder('DESC');
                }
        //Verif localisation
                if ($affichage_localisation != '' && $affichage_localisation == 'ASC')  {
                        $criteria->setSort('codpost');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_localisation != '' && $affichage_localisation == 'DESC'){
                        $criteria->setSort('codpost');
                        $criteria->setOrder('DESC');
                }
        //Verif date
                if ($affichage_date != '' && $affichage_date == 'ASC')  {
                        $criteria->setSort('published');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_date != '' && $affichage_date == 'DESC'){
                        $criteria->setSort('published');
                        $criteria->setOrder('DESC');
                }
        }
        else
        {
        //Autrement on affiche les annonces par date, la plus recente
                $criteria->setSort('published');
                $criteria->setOrder('DESC');
        }
        //Ajout
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $ads = $ads_handler->getObjects($criteria);
                $listads = getAdsItem($ads);
        return $listads;
}


function showMyAds($topic_id = 0, $uid = 0, $affichage_titre, $affichage_prix, $affichage_option_prix, $affichage_localisation, $affichage_date, $start = '', $limit = '') {
    global $ads_handler, $xoopsDB, $xoopsUser;

       $uid = !isset($_REQUEST['uid'])? NULL : $_REQUEST['uid'];

       $criteria = new Criteria('uid', $uid);

        //On regarde si les variables ne sont egal a rien
        if( $affichage_titre != '' || $affichage_prix != '' || $affichage_option_prix != '' || $affichage_localisation != '' || $affichage_date != '')
        {
        //Verif titre
                if ($affichage_titre != '' && $affichage_titre == 'ASC')  {
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_titre != '' && $affichage_titre == 'DESC'){
                        $criteria->setSort('ads_title');
                        $criteria->setOrder('DESC');
                }
        //Verif prix
                if ($affichage_prix != '' && $affichage_prix == 'ASC')  {
                        $criteria->setSort('price');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_prix != '' && $affichage_prix == 'DESC'){
                        $criteria->setSort('price');
                        $criteria->setOrder('DESC');
                }
        //Verif option prix
                if ($affichage_option_prix != '' && $affichage_option_prix == 'ASC')  {
                        $criteria->setSort('price_option');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_option_prix != '' && $affichage_option_prix == 'DESC'){
                        $criteria->setSort('price_option');
                        $criteria->setOrder('DESC');
                }
        //Verif localisation
                if ($affichage_localisation != '' && $affichage_localisation == 'ASC')  {
                        $criteria->setSort('codpost');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_localisation != '' && $affichage_localisation == 'DESC'){
                        $criteria->setSort('codpost');
                        $criteria->setOrder('DESC');
                }
        //Verif date
                if ($affichage_date != '' && $affichage_date == 'ASC')  {
                        $criteria->setSort('published');
                        $criteria->setOrder('ASC');
                }
                else if ($affichage_date != '' && $affichage_date == 'DESC'){
                        $criteria->setSort('published');
                        $criteria->setOrder('DESC');
                }
        }
        else
        {
        //Autrement on affiche les annonces par date, la plus recente
                $criteria->setSort('published');
                $criteria->setOrder('DESC');
        }
        //Ajout
                $criteria->setStart($start);
                $criteria->setLimit($limit);
                $ads = $ads_handler->getObjects($criteria);
                $listads = getAdsItem($ads);
        return $listads;
}



function getAdsItem($ads, $block = 0, $reduireTitle = 0) {
global $xoopsModule, $xoopsModuleConfig;

        if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'catads') {
                $module_handler =& xoops_gethandler('module');
                $module =& $module_handler->getByDirname('catads');
                $config_handler =& xoops_gethandler('config');
                $config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
        } else {
                $module =& $xoopsModule;
                $config =& $xoopsModuleConfig;
        }
                $a_item = array();
                $listads = array();
                foreach( $ads as $oneads ){
                        $ads_id = $oneads->getVar('ads_id');
                        $a_item['id'] = $ads_id;
                        $a_item['type'] = $oneads->getVar('ads_type');

                        // pk shorten title

                        $reduireTitle = '1' ; // debug

                        $title_length = $xoopsModuleConfig['title_length'];

                        $desc_length = $xoopsModuleConfig['desc_length'];

                        // $title_length = '15' ; // debug

                        if ($reduireTitle > 0)
                        {
                                if ( strlen($oneads->getVar('ads_title')) < $title_length )
                                {
                                        $a_item['title'] = $oneads->getVar('ads_title');
                                }
                                else
                                {
                                        $recupereCaractere =  substr($oneads->getVar('ads_title'), 0, $title_length);
                                        $last_space = strrpos($recupereCaractere, " ");
                                        $a_item['title'] = substr($oneads->getVar('ads_title'), 0, $last_space)."...";
                                }
                        }
                        else
                        {
                                $a_item['title'] = $oneads->getVar('ads_title');
                        }

                        // pk shorten description


                        // $desc_length = '60' ; // debug

                        if ( strlen($oneads->getVar('ads_desc')) < $desc_length )
                        {
                                $a_item['desc'] = $oneads->getVar('ads_desc');
                        }
                        else
                        {
                                $recupereCaractere =  substr($oneads->getVar('ads_desc'), 0, $desc_length);
                                $last_space = strrpos($recupereCaractere, " ");
                                $a_item['desc'] = substr($oneads->getVar('ads_desc'), 0, $last_space)."...";
                        }

                        if ($oneads->getVar('price') > 0)
                        $a_item['price'] = $oneads->getVar('price').' '.$oneads->getVar('monnaie');
                        $a_item['price_option'] = $oneads->getVar('price_option');
                        $a_item['date'] = ($oneads->getVar('published') > 0) ? formatTimestamp($oneads->getVar('published'),"s") : '';

                        // pk post code and town concatenated for listing. Edit here if required.
                        // $a_item['local'] = $oneads->getVar('codpost');
                        // $a_item['local'] .= ' '.$oneads->getVar('town');
                           $a_item['local'] = $oneads->getVar('town');
                        // end pk mod

                        $pk_thumb_width = $xoopsModuleConfig['thumb_width']; 

                        if ( $oneads->getVar('photo0') == '')
                        {       // pk display no-photo image - fix ID for validation
                                $a_item['photo'] = "<img  id=\"photo".$oneads->getVar('ads_id')."\" alt=\"\" class=\"miniature\" src=\"".XOOPS_URL."/modules/catads/images/no_dispo_mini.gif\" style=\"width: 60px; height: 60px;\"  />";
                        }
                        else
                        {
                                // pk display thumbnail - NB set width to match module pref (e.g. 60, 70, 100px etc.)
                                $a_item['photo'] = '<a href="'.XOOPS_URL.'/uploads/catads/images/annonces/original/'.$oneads->getVar('photo0').'" class="highslide" style="width: 250px;" onclick="return hs.expand(this)">
        <img class="miniature" src="'.XOOPS_URL.'/uploads/catads/images/annonces/thumb/'.$oneads->getVar('thumb').'" alt="'.$oneads->getVar('ads_title').'" style="width:'.$pk_thumb_width.'px"/></a>';

                        }

                        if ($oneads->getVar('waiting') == 1){
                                $a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/en_attente.gif' alt='Waiting' title='Waiting' />";
                        }elseif ($oneads->getVar('expired') < time()){
                                $a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/expiree.gif' alt='Expired' title='Expired' />";
                        }elseif ($oneads->getVar('suspend')){
                                $a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/stop.gif' alt='Suspended' title='Suspended' />";
                        }elseif ($oneads->getVar('published') > time()){
                                //$a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/ic16_clockgreen.gif'>";
                                $a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/en_attente.gif' alt='Waiting' title='Waiting' />";
                        }else {
                                $a_item['status'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/en_ligne.gif' alt='Online' title='Online' />";
                        }
                        array_push($listads,$a_item);
                        unset($a_item);
                }
        return $listads;
}

// Effacement fichiers temporaires
function catads_clear_tmp_files( $dir_path , $prefix = 'tmp_' ) {
        if( ! ( $dir = @opendir( $dir_path ) ) )
                return 0 ;
        $ret = 0 ;
        $prefix_len = strlen( $prefix ) ;
        while( ( $file = readdir( $dir ) ) !== false ) {
                if( strncmp( $file , $prefix , $prefix_len ) === 0 ) {
                        if( @unlink( "$dir_path/$file" ) ) $ret ++ ;
                }
        }
        closedir( $dir ) ;
        return $ret ;
}
//Code récuperer sur le module xcgal
function resize_image($src_file, $dest_file, $new_size, $method)
{
        global $xoopsModuleConfig, $ERROR;

        $imginfo = getimagesize($src_file);
        if ($imginfo == null)
                return false;
/*
$imginfo[0] //Width
$imginfo[1] //Height
$imginfo[2]; //Type image (1 = GIF  , 2 = JPG  , 3 = PNG  , 4 = SWF  , 5 = PSD  , 6 = BMP  , 7 = TIFF )
*/
        // GD can only handle JPG & PNG images
        if ($imginfo[2] != 1 && $imginfo[2] != 2 && $imginfo[2] != 3 && ($method == 'gd1' || $method == 'gd2'))
        {
                $ERROR = _MD_GD_FILE_TYPE_ERR;
                return false;
        }

        // height/width
                //echo "Width = ".$imginfo[0]."<br />";

        $srcWidth = $imginfo[0];
        $srcHeight = $imginfo[1];

        $ratio = max($srcWidth, $srcHeight) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($srcWidth / $ratio);
        $destHeight = (int)($srcHeight / $ratio);

        // Method for thumbnails creation
        switch ($method) {
        case "im" :
                if (preg_match("#[A-Z]:|\\\\#Ai",__FILE__)){
                        // get the basedir, remove '/include'
                        $cur_dir = substr(dirname(__FILE__),0, -8);
                        $src_file =   '"'.$cur_dir.'\\'.strtr($src_file, '/', '\\').'"';
                        $im_dest_file = str_replace('%', '%%', ('"'.$cur_dir.'\\'.strtr($dest_file, '/', '\\').'"'));
                } else {
                        $src_file =   escapeshellarg($src_file);
                        $im_dest_file = str_replace('%', '%%', escapeshellarg($dest_file));
                }

                $output = array();
                $cmd = "{$xoopsModuleConfig['impath']}convert -quality {$xoopsModuleConfig['jpeg_qual']} {$xoopsModuleConfig['im_options']} -geometry {$destWidth}x{$destHeight} $src_file $im_dest_file";
                exec ($cmd, $output, $retval);

                if ($retval) {
                    $ERROR = _MD_IM_ERROR." $retval";
                        if ($xoopsModuleConfig['debug_mode']) {
                                // Re-execute the command with the backtit operator in order to get all outputs
                                // will not work is safe mode is enabled
                                $output = `$cmd 2>&1`;
                            $ERROR .= "<br /><br /><div align=\"left\">"._MD_IM_ERROR_CMD."<br /><font size=\"2\">".nl2br(htmlspecialchars($cmd))."</font></div>";
                            $ERROR .= "<br /><br /><div align=\"left\">"._MD_IM_ERROR_CONV."<br /><font size=\"2\">";
                                $ERROR .= nl2br(htmlspecialchars($output));
                                $ERROR .= "</font></div>";
                        }
                        @unlink($dest_file);
                        return false;
                }
                break;
    case "net" :
                if (preg_match("#[A-Z]:|\\\\#Ai",__FILE__)){
                        // get the basedir, remove '/include'
                        $cur_dir = substr(dirname(__FILE__),0, -8);
                        $src_file =   '"'.$cur_dir.'\\'.strtr($src_file, '/', '\\').'"';
                        $im_dest_file = str_replace('%', '%%', ('"'.$cur_dir.'\\'.strtr($dest_file, '/', '\\').'"'));
                } else {
                        $src_file =   escapeshellarg($src_file);
                        $im_dest_file = str_replace('%', '%%', escapeshellarg($dest_file));
                }
               switch ($imginfo[2]){
                case GIS_GIF:
                        $op_in   = 'giftopnm';
                        $op_out = 'ppmtogif';
                        $op_out2 = 'pnmtogif';
                        $cmd = "{$xoopsModuleConfig['impath']}{$op_in} $src_file | pnmscale -xsize={$destWidth} -ysize={$destHeight} | ppmquant 255 | {$op_out} > $im_dest_file";
                        break;

                case GIS_JPG:
                        $op_in   = 'jpegtopnm';
                        $op_out = 'pnmtojpeg';
                        $op_out2 = 'ppmtojpeg';
                        $cmd = "{$xoopsModuleConfig['impath']}{$op_in} $src_file | pnmscale -xsize={$destWidth} -ysize={$destHeight} | {$op_out} -quality={$xoopsModuleConfig['jpeg_qual']} > $im_dest_file";
                        $cmd2 = "{$xoopsModuleConfig['impath']}{$op_in} $src_file | pnmscale -xsize={$destWidth} -ysize={$destHeight} | {$op_out2} -quality={$xoopsModuleConfig['jpeg_qual']} > $im_dest_file";
            break;

                case GIS_PNG:
                        $op_in   = 'pngtopnm';
                        $op_out = 'pnmtopng';
                        $cmd = "{$xoopsModuleConfig['impath']}{$op_in} $src_file | pnmscale -xsize={$destWidth} -ysize={$destHeight} | {$op_out} > $im_dest_file";
                        break;
        }
                $output = array();
                echo $cmd;
        if(!(@exec ($cmd)) && isset($cmd2)) @exec ($cmd2);

                break;
        case "gd1" :
                        //echo "gd1<br>";
                if (!function_exists('imagecreatefromjpeg')) {
                    redirect_header('index.php',2,_MD_NO_GD_FOUND);
                }
                if ($imginfo[2] == GIS_JPG)
                        $src_img = imagecreatefromjpeg($src_file);
                elseif ($imginfo[2] == GIS_GIF)
                        $src_img = imagecreatefromgif($src_file);
                else
                        $src_img = imagecreatefrompng($src_file);
                if (!$src_img){
                        $ERROR = _MD_INVALID_IMG;
                        return false;
                }
                $dst_img = imagecreate($destWidth, $destHeight);
                imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
                imagejpeg($dst_img, $dest_file, 80);
                imagedestroy($src_img);
                imagedestroy($dst_img);
                break;

        case "gd2" :
                        //echo "gd2<br>";
                if (!function_exists('imagecreatefromjpeg')) {
                    redirect_header('index.php',2,_AM_CATADS_NO_GD_FOUND);
                }
                if (!function_exists('imagecreatetruecolor')) {
                    redirect_header('index.php',2,_MD_GD_VERSION_ERR);
                }
                if ($imginfo[2] == 2 )
                        $src_img = imagecreatefromjpeg($src_file);
                elseif ($imginfo[2] == 1 )
                        $src_img = imagecreatefromgif($src_file);
                else
                        $src_img = imagecreatefrompng($src_file);
                if (!$src_img){
                        $ERROR = _MD_INVALID_IMG;
                        return false;
                }
                $dst_img = imagecreatetruecolor($destWidth, $destHeight);
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
                imagejpeg($dst_img, $dest_file, 80);
                imagedestroy($src_img);
                imagedestroy($dst_img);
                break;
        }


        // Set mode of uploaded picture
        chmod($dest_file, 0644);
                //echo "fichier destination = ".$dest_file;
        // We check that the image is valid
        $imginfo = getimagesize($dest_file);
        if ($imginfo == null){
                $ERROR = _MD_RESIZE_FAILED;
                @unlink($dest_file);
                return false;
        } else {
                return true;
        }
}

function catads_copyright() {
        global $xoopsTpl, $xoopsOption, $xoopsModule;
        include(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/xoops_version.php");
        $copyright = " CatAds ".$modversion['version'];
        $copyright .= " <img src =".XOOPS_URL.'/modules/catads/images/icon/thecat.gif align="texttop"> C.Félix';
        $xoopsTpl->assign('copyright', $copyright);
}

function &deleteCode(&$text) {
        $patterns = array();
        $replacements = array();
        $patterns[] = "/\[siteurl=(['\"]?)([^\"'<>]*)\\1](.*)\[\/siteurl\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[url=(['\"]?)(http[s]?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[url=(['\"]?)(ftp?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[url=(['\"]?)([^\"'<>]*)\\1](.*)\[\/url\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[color=(['\"]?)([a-zA-Z0-9]*)\\1](.*)\[\/color\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[size=(['\"]?)([a-z0-9-]*)\\1](.*)\[\/size\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[font=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/font\]/sU";
        $replacements[] = '\\3';
        $patterns[] = "/\[email]([^;<>\*\(\)\"']*)\[\/email\]/sU";
        $replacements[] = '\\1';
        $patterns[] = "/\[b](.*)\[\/b\]/sU";
        $replacements[] = '\\1';
        $patterns[] = "/\[i](.*)\[\/i\]/sU";
        $replacements[] = '\\1';
        $patterns[] = "/\[u](.*)\[\/u\]/sU";
        $replacements[] = '\\1';
        $patterns[] = "/\[d](.*)\[\/d\]/sU";
        $replacements[] = '\\1';
        return preg_replace($patterns, $replacements, $text);
}
        //rss hack by InBox Solutions for Philippe Montalbetti
        function getTitleById($topic_id = 0){
            $db =& Database::getInstance();
        $sql = "SELECT topic_title FROM ".$db->prefix("catads_cat")." WHERE topic_id =".$topic_id;
                $result = $db->query($sql);
                list($title) = $db->fetchRow($result);
                return $title;
        }
        //end hack by InBox Solutions for Philippe Montalbetti


function sf_collapsableBar($tablename = '', $iconname = '')
{

   ?>
        <script type="text/javascript"><!--
        function goto_URL(object)
        {
                window.location.href = object.options[object.selectedIndex].value;
        }

        function toggle(id)
        {
                if (document.getElementById) { obj = document.getElementById(id); }
                if (document.all) { obj = document.all[id]; }
                if (document.layers) { obj = document.layers[id]; }
                if (obj) {
                        if (obj.style.display == "none") {
                                obj.style.display = "";
                        } else {
                                obj.style.display = "none";
                        }
                }
                return false;
        }

        var iconClose = new Image();
        iconClose.src = '../images/icon/close12.gif';
        var iconOpen = new Image();
        iconOpen.src = '../images/icon/open12.gif';

        function toggleIcon ( iconName )
        {
                if ( document.images[iconName].src == window.iconOpen.src ) {
                        document.images[iconName].src = window.iconClose.src;
                } else if ( document.images[iconName].src == window.iconClose.src ) {
                        document.images[iconName].src = window.iconOpen.src;
                }
                return;
        }

        //-->
        </script>
        <?php
        echo "<h3 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}
?>