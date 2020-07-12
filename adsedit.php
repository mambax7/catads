<?php
// $Id: adsedit.php,v 1.01 2005/07/06 the Cat
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

include("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");

//                    pk USER-SIDE edit form                                  //
//         Form is built using 'include/form2_ads.inc.php'                    //
//          Form is rendered using 'catads_adsform2.html'                     //

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

$ads_handler =& xoops_getmodulehandler('ads');
$ads = $ads_handler->get($ads_id);
$topic_pid = $ads->getVar('cat_id');

$cat = new AdsCategory($topic_pid);

// verification user
$uid = $ads->getVar('uid');
if (!$xoopsUser || $xoopsUser->getVar('uid') != $uid) {
        redirect_header("index.php",1,_NOPERM);
}

$ads_id =  isset($_POST['ads_id']) ? intval($_POST['ads_id']) : intval($_GET['ads_id']);

if ( isset($_POST['delete'])) $op = 'delete';
elseif ( isset($_POST['edit']) ) $op = 'edit';
elseif ( isset($_POST['post'])) $op = 'save';
elseif (!isset($op)) $op = 'show';

       // pk save changes to DB

        switch ($op) {
        case "save":

        global $xoopsModuleConfig;

        if ($xoopsModuleConfig['usercanedit'] != 1) {
                redirect_header("index.php",1,_NOPERM);
        }
        $msgstop = '';
        if (isset($email) && $email != '' && !checkEmail($email) ) $msgstop .= _MD_CATADS_INVALIDMAIL.'<br />';
        if (strlen(deleteCode($ads_desc)) > $xoopsModuleConfig['txt_maxlength'] ) $msgstop .= sprintf(_MD_CATADS_MAXLENGTH.'<br />',$xoopsModuleConfig['txt_maxlength']);
        if (isset($price) && $price != '' && !is_numeric(trim($price))) $msgstop.= _MD_CATADS_INVALIDPRICE.'<br />';
        if ($mode_contact == 2 && $email == '') $msgstop .= _MD_CATADS_MAILREQ.'<br />';
        if ($mode_contact == 3 && $phone == '') $msgstop .= _MD_CATADS_PHONEREQ.'<br />';

        // pk statement says if notify pub VAR not set, then set as 0 - what is this? - (notify when published)
        $notify_pub = isset($notify_pub) ? intval($notify_pub) : 0;

        if (!isset($region)) { $region = '00'; }
        if (!isset($departement)) { $departement = '00'; }

        if( $codpost )
        {
                $pays = 'FRANCE';
        } else {
                $pays = 'OTHER';
        }

        $i = 0;
        while  ($i < $cat->nb_photo) {
                if ( !empty($_FILES['photo'.$i]['name'])) {
                        catads_upload($i);
                }
                $i++;
        }

        if ( !empty($msgstop) ) {
                include  XOOPS_ROOT_PATH.'/header.php';
                $xoopsOption['template_main'] = 'catads_adsform.html';
                $xoopsTpl->assign('preview', true);
                $xoopsTpl->assign('msgstop',$msgstop);
                $i = 0;
                while  ($i < $cat->nb_photo) {
                        if ($ads->getVar('photo'.$i)) {
                                $photo[$i]= $ads->getVar('photo'.$i);
                        }
                        $i++;
                }
                include_once "include/form2_ads.inc.php";
                $adsform->assign($xoopsTpl);
                include XOOPS_ROOT_PATH."/footer.php";
                exit();
        }

        // pk if ads are NOT moderated...
        if ($xoopsModuleConfig['moderated'] != 1)
        {
                $ads->setVar('pays', $pays);
                $ads->setVar('cat_id', $cat_id);
                $ads->setVar('ads_title', $ads_title);
                $ads->setVar('ads_type', $ads_type);
                $ads->setVar('ads_desc', $ads_desc);
                $ads->setVar('phone', $phone);
                $ads->setVar('region', $region);
                $ads->setVar('departement', $departement);
                $ads->setVar('town', $town);

                // pk set video path and tags from new VARs
                $ads->setVar('ads_video', $ads_video);
                // $ads->setVar('ads_tags', $ads_tags);


                // pk function from submit1.php to use title to generate tags if none are set
                // pk if the user has not entered any tags...
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

                // end pk mod

                $poster_ip = $_SERVER['REMOTE_ADDR'];

                $ads->setVar('poster_ip', $poster_ip);



                // pk 'expired_mail_send' sets VAR to send or not, 'expired_by_mode' sets email or PM as method.
                // Value set in the DB is the ADDITION of the two fields, e.g. 1 + 1 = 2
                if ($expired_mail_send == 0)
                                $ads->setVar('expired_mail_send', '0');
                        else
                                $ads->setVar('expired_mail_send', '1' + $expired_by_mode);

                if (isset($price)) {
                        $ads->setVar('price', $price);
                        $ads->setVar('monnaie', $monnaie);
                        $ads->setVar('price_option', $price_option);
                }
                if (isset($email)) $ads->setVar('email', $email);
                if (isset($codpost)) $ads->setVar('codpost', $codpost);
                $ads->setVar('contact_mode', $pref_contact + $mode_contact);
        }

        // pk default behavior (i.e. if xoopsmoduleconfig 'moderated' is set)

        $waiting = ($xoopsModuleConfig['moderated']) ? 1 : 0;
        $ads->setVar('waiting', $waiting);
        //$ads->setVar('notify_pub', 1);
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
                $msg = sprintf(_MD_CATADS_ERROR_UPDATE, $ads->getVar('ads_title'));
                $msg .= '<br />'.$ads->getErrors();
                xoops_header();
// echo "<h4>"._MD_CATADS_MODULE_NAME."</h4>";
                xoops_error($msg);
                xoops_footer();
                exit();
        }

//notification

        $ads_type = $ads->getVar('ads_type');
        $ads_title = $ads->getVar('ads_title');
        $notification_handler =& xoops_gethandler('notification');
        $tags = array();
        $tags['ADS_TITLE'] = $ads_type.' '.$ads_title;
        if ( $xoopsModuleConfig['moderated'] == 1)
        {
                $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?sel_status=2';
                $notification_handler->triggerEvent('global', 0, 'ads_edit', $tags);
                //$notification_handler->triggerEvent('category', $cat_id, 'ads_edit', $tags);
                $notification_handler->triggerEvent('ads', $ads_id, 'ads_edit', $tags);

                if ($notify_pub) {
                        include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                        //subscribe = inscrire dans les notifications
                        //triggerEvent = Envoie par email
                        $notification_handler->subscribe('ads', $ads_id, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
        }

        redirect_header("adsitem.php?ads_id=".$ads_id,2,_MD_CATADS_NOERROR_UPDATE);
        exit();
                break;

        // pk the edit form

        case "edit":
        default:
        if ($xoopsModuleConfig['usercanedit'] != 1) {
                redirect_header("index.php",1,_NOPERM);
        }

                $published = $ads->getVar('published');
                $expired = $ads->getVar('expired');
                $created = $ads->getVar('created');
                $cat_id = $ads->getVar('cat_id');
                $ads_type = $ads->getVar('ads_type');
                $ads_title = $ads->getVar('ads_title');
                $ads_desc = $ads->getVar('ads_desc');
                $ads_tags = $ads->getVar('ads_tags');

                // pk get video path for edit form
                $ads_video = $ads->getVar('ads_video');

                $price = ($ads->getVar('price') > 0) ? $ads->getVar('price') : '';
                $monnaie = $ads->getVar('monnaie');
                $price_option = $ads->getVar('price_option');
                $email = $ads->getVar('email');
                $phone = $ads->getVar('phone');
                $town = $ads->getVar('town');
                $region = $ads->getVar('region');
                $departement = $ads->getVar('departement');
                $codpost = $ads->getVar('codpost');
                $uid = $ads->getVar('uid');

                // pk contact mode for users (not expiry) 2=email, 12=ONLY email, 3=Phone, 13=ONLY Phone
                $contact_mode = $ads->getVar('contact_mode');

                // pk ** bugfix ** - get notify pub status ?
                $notify_pub = $ads->getVar('notify_pub');

                // pk get 'send expiry notice' value VAR 'expired_mail_send' set by 'form2_ads.php' in DB (0=no, 1=yes PM, 2=yes Email)
                $expired_mail_send = $ads->getVar('expired_mail_send');


               // if ( $expired_mail_send == 0 )
               // {
               //         $expired_mail_send = 0;
               // } else {
               //         $expired_mail_send = 1;
               //  }

                // pk create expired_by_mode VAR for form when an ad is edited

                if ($expired_mail_send == 1 ){
                        $expired_mail_send = 1 ;
                        $expired_by_mode = 0 ;
                } else if($expired_mail_send == 2){
                        $expired_mail_send = 1 ;
                        $expired_by_mode = 1 ;
                } else {
                        $expired_mail_send = 0 ;
                        $expired_by_mode = 0 ;
                }

                // end pk mod -------------------------------------

                // pk DB value for contact mode, e.g. 3 = phone, 13 = phone ONLY. If above 9, minus 10 to get actual mode.
                if($contact_mode > 9){
                        $mode_contact = $contact_mode - 10;
                        $pref_contact = 10;
                } else {
                        $mode_contact = $contact_mode;
                        $pref_contact = 0;
                }

                $i = 0;
                while  ($i < 6) {
                        $preview_name[$i] = '';
                        if ($ads->getVar('photo'.$i)) {
                                $photo[$i]= $ads->getVar('photo'.$i);
                        }
                        $i++;
                }
                // pk template file
                $xoopsOption['template_main'] = 'catads_adsform2.html';
                include_once(XOOPS_ROOT_PATH."/header.php");
                include "include/form2_ads.inc.php";
                $adsform->assign($xoopsTpl);
                include XOOPS_ROOT_PATH."/footer.php";
                break;

        case "delete":
        if (!$xoopsModuleConfig['usercandelete']) {
                redirect_header("index.php",1,_NOPERM);
        }
        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

    if ( $ok == 1 ) {
                // cache
                include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                include_once XOOPS_ROOT_PATH.'/class/template.php';
                xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                $i=0;
                while ($i < 6){
                        if ($ads->getVar('photo'.$i)) {
                                $filename = XOOPS_ROOT_PATH.'/uploads/'.$xoopsModule->dirname().'/images/annonces/original/'.$ads->getVar('photo'.$i);
                                unlink($filename);
                        }
                        $i++;
                }

                $del_ads_ok = $ads_handler->delete($ads);
                if ($del_ads_ok){
                        // delete comments
                        xoops_comment_delete($xoopsModule->getVar('mid'), $ads_id);
                        // delete notifications
                        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'ads', $ads_id);
                        $messagesent = _MD_CATADS_ADSDELETED;
                } else {
                        $messagesent = _MD_CATADS_ERRORDEL;
                }
                redirect_header("adsuserlist.php?uid=".$uid, 2, $messagesent);

        } else {
                include(XOOPS_ROOT_PATH."/header.php");
                xoops_confirm(array('op' => 'delete', 'ads_id' => $ads_id, 'uid' => $uid, 'ok' => 1), 'adsedit.php', _MD_CATADS_CONF_DEL);
                include(XOOPS_ROOT_PATH."/footer.php");
        }
                break;
}
?>