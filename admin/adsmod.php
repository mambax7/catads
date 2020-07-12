<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2005/07/12 the Cat
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 *
 * ****************************************************************************
 */
include("admin_header.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/admin/functions.php";
include_once '../include/functions.php';

include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");

//                    pk ADMIN edit form                                      //
//         Form is built using 'include/form3_ads.inc.php'                    //
//                     Form is rendered using ?                               //

foreach ($_POST as $k => $v) {
        if(preg_match('/delimg_/', $k)){
                $n = explode('_',$k);
                $del_img[$n[1]] = $v;
        } elseif (preg_match('/previewname_/', $k)){
                $n = explode('_',$k);
                $preview_name[$n[1]] = $v;
        } else {
                ${$k} = $v;
        }
}
foreach ($_GET as $k => $v) {${$k} = $v;}

if ( isset($_POST['purge_ads_expired'] )) $op = 'purge_ads_expired';
elseif ( isset($_POST['delete'])) $op = 'delete';
elseif ( isset($_POST['edit']) ) $op = 'edit';
elseif ( isset($_POST['save'])) $op = 'save';
elseif (!isset($op)) $op = 'show';

if (!isset($action)) $action = 'all';
if ( isset($_GET['start']) )
        $start = intval($_GET['start']);
else $start = 0;

switch ($op) {
        case "save":

        $ads_handler =& xoops_getmodulehandler('ads');
        $ads = $ads_handler->get($ads_id);

        $ads->setVar('cat_id', $topic_id);
        $ads->setVar('ads_title', $ads_title);
        $ads->setVar('ads_type', $ads_type);
        $ads->setVar('ads_desc', $ads_desc);

                // pk if the user has not entered any tags...
                if(!isset($_REQUEST['ads_tags'])? NULL : $_REQUEST['ads_tags']);                 
                if ($_REQUEST['ads_tags'] == '')
                {
                        // Create tags from the item title.
                        $newtags = $ads->getVar('ads_title');
                        // Omit common letters and words from the tag list. NB spaces before and after are important.
                        $remplace = array(
                        ' of ', ' if ', ' to ', ' in ', ' at ', ' on ', ' by ', ' it ', ' is ', ' or ', ' are ', ' the ', ' for ', ' and ',
                        ' &amp; ', ' when ', ' with '
                        );
                        $par = ' ';// Replace them with a space
                        $newtags = str_replace($remplace, $par, $newtags);
                        // Trim the space from the tag list and save it to a VAR
                        $newtags = trim($newtags, ' ');
                        $ads->setVar('ads_tags', $newtags);
                } else {
                        $ads->setVar('ads_tags', $_REQUEST['ads_tags']);
                }

        $ads->setVar('ads_video', $ads_video);
        $ads->setVar('phone', $phone);
        $ads->setVar('town', $town);
        $ads->setVar('region', $region);
        $ads->setVar('departement', $departement);
        $ads->setVar('waiting', ($waiting > 0)? 0 : 1);
        if(isset($price)) {
                $ads->setVar('price', $price);
                $ads->setVar('monnaie', $monnaie);
                $ads->setVar('price_option', $price_option);
        }
        if(isset($email)) $ads->setVar('email', $email);
        if(isset($codpost)) $ads->setVar('codpost', $codpost);
        if ($waiting ){
                $ads->setVar('published', strtotime($published['date']) + $published['time']);
                $ads->setVar('expired', strtotime($expired['date']) + $expired['time']);
        }

        $cat = new AdsCategory($topic_id);
        $i = 0;
        while  ($i < $cat->nb_photo) {
                if ( !empty($_FILES['photo'.$i]['name'])) {
                        catads_upload($i);
                }
                $i++;
        }

        $i = 0;

        while  ($i < $cat->nb_photo) {
                $photo = '';

                if(isset($del_img[$i])) {
                        $filename = XOOPS_ROOT_PATH.'/uploads/'.$xoopsModule->dirname().'/images/annonces/original/'.$ads->getVar('photo'.$i);
                        unlink($filename);
                        $ads->setVar('photo'.$i, '');
                        if ($i == 0)
                        {
                                $ads->setVar('thumb', '');
                        }
                } elseif ($preview_name[$i] != '') {
                        $photo = str_replace('tmp_', 'img_',$preview_name[$i]);
                        $photos_dir = XOOPS_ROOT_PATH . '/uploads/'.$xoopsModule->dirname().'/images/annonces/original/' ;
                        rename( $photos_dir.$preview_name[$i] , $photos_dir.$photo ) ;
                        if ( $i == 0 )
                        {
                                //Thumb (a revoir avec l'optimisation au-dessus)
                                $image = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original/".$photo;
                                $thumb = str_replace('tmp_', 'thumb_',$preview_name[$i]);
                                $thumb_dir = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/thumb/".$thumb;
                                //echo "thumb = ".$thumb;

                                if (!file_exists($thumb_dir))
                                        if (!resize_image($image, $thumb_dir, $xoopsModuleConfig['thumb_width'], $xoopsModuleConfig['thumb_method']))
                                                return false;

                                $ads->setVar('thumb', $thumb);
                        }
                if ($preview_name[$i] != $ads->getVar('photo'.$i)) {
                        $filename = XOOPS_ROOT_PATH.'/uploads/'.$xoopsModule->dirname().'/images/annonces/original/'.$ads->getVar('photo'.$i);
                        unlink($filename);
                }
                $ads->setVar('photo'.$i, $photo);
                }
        $i++;
        }

        if (!$ads_handler->insert($ads)) {
                $msg = sprintf(_AM_CATADS_ERROR_UPDATE, $ads->getVar('ads_title'));
                $msg .= '<br />'.$ads->getErrors();
                xoops_header();
//                echo "<h4>"._AM_CATADS_TITLE."</h4>";
                xoops_error($msg);
                xoops_footer();
                exit();
        } elseif ($waiting) {
                // cache
                include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                include_once XOOPS_ROOT_PATH.'/class/template.php';
                xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                // Notification
                $notification_handler =& xoops_gethandler('notification');
                $tags = array();
                $tags['ADS_TITLE'] = $ads->getVar('ads_type').' : '.$ads->getVar('ads_title');
                $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/adsitem.php?ads_id=' . $ads_id;

                $notification_handler->triggerEvent('global', 0, 'new_ads', $tags);
                $notification_handler->triggerEvent('category', $topic_id, 'new_ads', $tags);
                $notification_handler->triggerEvent('ads', $ads_id, 'approve', $tags);
                /*
                $notification_handler->triggerEvent('global', 0, 'ads_edit', $tags);
                $notification_handler->triggerEvent('category', $topic_id, 'ads_edit', $tags);
                $notification_handler->triggerEvent('ads', $ads_id, 'approve', $tags);*/
        }
        redirect_header("ads.php",2,_AM_CATADS_NOERROR_UPDATE);
        exit();
        break;

        // edit

        case "edit":
        xoops_cp_header();
        catads_admin_menu(0);

        echo "<br />" ;

        $option_handler =& xoops_getmodulehandler('option');
        $criteria = new Criteria('option_id', '0', '>');
        $criteria->setSort('option_type');
        $option = $option_handler->getObjects($criteria);
        $count = 0;
        foreach($option as $oneoption){
                $arr_option[$count]['id'] = $oneoption->getVar('option_id');
                $arr_option[$count]['type'] = $oneoption->getVar('option_type');
                $arr_option[$count]['desc'] = $oneoption->getVar('option_desc');
                $arr_option[$count]['order'] = $oneoption->getVar('option_order');
                $count++;
        }

        $ads_handler = & xoops_getmodulehandler('ads');
        $ads = & $ads_handler->get($ads_id);

        $published = $ads->getVar('published');
        $expired = $ads->getVar('expired');
        $created = $ads->getVar('created');
        $cat_id = $ads->getVar('cat_id');
        $ads_type = $ads->getVar('ads_type');
        $ads_title = $ads->getVar('ads_title');
        $ads_desc = $ads->getVar('ads_desc');
        $ads_tags = $ads->getVar('ads_tags');

    $ads_video = $ads->getVar('ads_video');
    $price = $ads->getVar('price');
    $monnaie = $ads->getVar('monnaie');
    $price_option = $ads->getVar('price_option');
    $email = $ads->getVar('email');
    $phone = $ads->getVar('phone');
    $town = $ads->getVar('town');
        $region = $ads->getVar('region');
        $departement = $ads->getVar('departement');
    $codpost = $ads->getVar('codpost');
        $submitter = XoopsUser::getUnameFromId($ads->getVar('uid'));
        $contact_mode = $ads->getVar('contact_mode') % 10;
//echo 'contact '.$contact_mode;
        $i = 0;
        while ($i < 6){
                if ($ads->getVar('photo'.$i)) {
                        $photo[$i]= $ads->getVar('photo'.$i);
                }
                $i++;
        }
        $waiting = $ads->getVar('waiting');

        include "../include/form3_ads.inc.php";
        xoops_cp_footer();
        break;

        case "approve":
                approve($ads_id);
        break;

        case "delete":
                delete($ads_id);
        break;

}
?>