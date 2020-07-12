<?php
// $Id: submit1.php,v 1.61 2005/07/12 C. Felix AKA the Cat
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
include_once XOOPS_ROOT_PATH.'/modules/catads/class/permissions.php';

//                 pk USER-SIDE SUBMIT NEW AD form                            //
//         Form is built using 'include/form1_ads.inc.php'                    //
//          Form is rendered using 'catads_adsform.html'                      //

if (!$xoopsModuleConfig['anoncanpost'] && !$xoopsUser){
        redirect_header(XOOPS_URL."/user.php",3,_MD_CATADS_ONLY_MEMBERS);
        exit();
}

$op = 'form';
foreach ($_GET as $k => $v) {${$k} = $v;}

foreach ($_POST as $k => $v) {
        if(preg_match('/previewname_/', $k)){
                $n = explode('_',$k);
                $preview_name[$n[1]] = $v;
        } else {
                ${$k} = $v;
        }
}

if ( isset($preview)) $op = 'preview';
elseif ( isset($cancel) ) $op = 'cancel';
elseif ( isset($post) ) $op = 'post';

// pk define VARs
$topic_id =  isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
$notify_pub = !isset($_REQUEST['notify_pub'])? 0 : $_REQUEST['notify_pub'];

$ads_type = !isset($_REQUEST['ads_type'])? NULL : $_REQUEST['ads_type'];
$ads_video = !isset($_REQUEST['ads_video'])? NULL : $_REQUEST['ads_video'];
$published = !isset($_REQUEST['published'])? 0 : $_REQUEST['published'];


// array des sous-catégories 'enfant' d'une catégorie
function getFirstChild($topic_id = 0) {
        global $allcat;
        $firstChild = array();
        if (isset($allcat)) {
                foreach($allcat as $onechild)         {
                        if( $onechild['topic_pid'] == $topic_id) {
                                array_push($firstChild, $onechild);
                        }
                }
        }
        return $firstChild;
}

function showsubcat($categorys, $level = 0, $topic_id = 0, $pid) {
        global $xoopsModule, $ts, $lastchildren, $arr_subcat, $cptsubcat, $tpltype, $xoopsModuleConfig;

        foreach($categorys as $onecat)         {
                $title = $ts->htmlSpecialChars($onecat['topic_title']);
                $arr_scat['id'] = $onecat['topic_id'];
                if (in_array($onecat['topic_id'], $lastchildren)) {
                        $arr_scat['submit'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/submit1.php?topic_id=' . $onecat['topic_id'];
                }
                $arr_scat['title'] = $title;
                $arr_scat['level'] = $level;
                if ($level == 0 && $tpltype == 1) {
                        $arr_scat['newcol'] = ($cptsubcat > 0) ? true : false;
                        $cptsubcat++;
                        $arr_scat['newline'] = ($cptsubcat % $xoopsModuleConfig['nbcol'] == 1) ? true : false;
                }
                array_push($arr_subcat, $arr_scat);
                $childcats =  getFirstChild($onecat['topic_id']);
                        if (count($childcats) > 0) {
                                showsubcat($childcats, $level + 1, $onecat['topic_id'], $pid);
                        }
                }
        return;
        }

switch($op) {
case "cancel":
        $photos_dir = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original" ;
        $nb_removed_tmp = catads_clear_tmp_files( $photos_dir ) ;
        redirect_header("adslist.php?topic_id=".$topic_id, 0);
        break;

// pk post new ad to DB

case "post":
        $msgstop = '';
        if (isset($email) && $email != '' && !checkEmail($email) ) $msgstop .= _MD_CATADS_INVALIDMAIL.'<br />';
        if (strlen(deleteCode($ads_desc)) > $xoopsModuleConfig['txt_maxlength'] ) $msgstop .= sprintf(_MD_CATADS_MAXLENGTH.'<br />', $xoopsModuleConfig['txt_maxlength']);
        if (isset($price) && $price != '' && !is_numeric(trim($price))) $msgstop.= _MD_CATADS_INVALIDPRICE.'<br />';
        if ($mode_contact == 2 && $email == '') $msgstop .= _MD_CATADS_MAILREQ.'<br />';
        if ($mode_contact == 3 && $phone == '') $msgstop .= _MD_CATADS_PHONEREQ.'<br />';
        //echo $departement;
        if (!isset($region)) { $region = '00'; }
        if (!isset($departement)) { $departement = '00'; }

        // pk can ignore this for now. Was used by France map.
        if( $region != '00' || $departement != '00' )
        {
                $pays = 'FRANCE';
        } else {
                $pays = 'OTHER';
        }


        //Captcha
        include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
        if ( defined('SECURITYIMAGE_INCLUDED') && !SecurityImage::CheckSecurityImage() ) {
                 redirect_header( 'index.php', 2, _SECURITYIMAGE_ERROR ) ;
                 exit();
        }

        $cat = new AdsCategory($topic_id);
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
                $xoopsTpl->assign('nb_days_before', $xoopsModuleConfig['nb_days_before']);
                include_once "include/form1_ads.inc.php";
                $adsform->assign($xoopsTpl);
                include XOOPS_ROOT_PATH."/footer.php";
                exit();
        }

        $ads_handler =& xoops_getmodulehandler('ads');
        $ads = $ads_handler->create();
        $photos_dir = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original" ;
        //A optimiser, pk faire 6 boucles, si il n'y a qu'une photo
        $i = 0;
        while  ($i < 6)
        {
                if (isset($preview_name[$i]) && $preview_name[$i] != '')
                {
                        $photo = str_replace('tmp_', 'img_',$preview_name[$i]);
                        rename( "$photos_dir/$preview_name[$i]" , "$photos_dir/$photo" ) ;
                        $ads->setVar('photo'.$i, $photo);
                        // pk create thumbs for all images, not just the first? no, just image0 for the listing page
                        if ( $i == 0 )
                        // if ( $i >= 0 )
                        {
                                //Thumb (a revoir avec l'optimisation au-dessus)
                                $image = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original/".$photo;
                                $thumb = str_replace('tmp_', 'thumb_',$preview_name[$i]);
                                $thumb_dir = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/thumb/".$thumb;
                                //echo "thumb = ".$thumb;

                                if (!file_exists($thumb_dir))
                                        if (!resize_image($image, $thumb_dir, $xoopsModuleConfig['thumb_width'], $xoopsModuleConfig['thumb_method']))
                                                return false;
                        }

                } else
                {
                        $ads->setVar('photo'.$i, '');
                }
                $i++;
        }


        //$photo = str_replace('tmp_', 'thumb_',$preview_name[0]);
        $ads->setVar('thumb', $thumb);
        $ads->setVar('pays', $pays);


                include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                include_once XOOPS_ROOT_PATH.'/class/template.php';
                xoops_template_clear_module_cache($xoopsModule->getVar('mid'));

                // pk notify pub value - bugfix needed - FIXED
                // $notify_pub = isset($notify_pub) ? intval($notify_pub) : 0;
                $notify_pub = intval($notify_pub);

                $waiting = ($xoopsModuleConfig['moderated']) ? 1 : 0;
                $poster_ip = $_SERVER['REMOTE_ADDR'];

                $ads->setVar('cat_id', $topic_id);
                $ads->setVar('ads_title', $ads_title);
                $ads->setVar('ads_type', $ads_type);
                $ads->setVar('ads_desc', $ads_desc);

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

                $ads->setVar('ads_video', $ads_video);
                $ads->setVar('uid', $uid);
                $ads->setVar('phone', $phone);
                $ads->setVar('region', $region);
                $ads->setVar('departement', $departement);
                $ads->setVar('town', $town);
                if (isset($price)) {
                        $ads->setVar('price', $price);
                        $ads->setVar('monnaie', $monnaie);
                        $ads->setVar('price_option', $price_option);
                } else {
                        $ads->setVar('price', 0);
                        $ads->setVar('monnaie', '');
                        $ads->setVar('price_option', '');
                }
                if (isset($email))
                        $ads->setVar('email', $email);
                else
                        $ads->setVar('email', '');

                if (isset($codpost))
                        $ads->setVar('codpost', $codpost);
                else
                        $ads->setVar('codpost', '');

                if ($xoopsModuleConfig['nb_days_before'] > 0 ) {
                        $now = getdate();
                        $published = strtotime($published) + mktime($now['hours'], $now['minutes'], $now['seconds'], 1, 1, 1970);
                } else {
                        $published = time();
                }
                $ads->setVar('created', time());
                $ads->setVar('published', $published);
                $ads->setVar('expired', $published + $duration*86400);
                /*
                0 -> rien recevoir
                1 -> recevoir + message prive
                2 -> recevoir + email
                */

                // pk set epired mail send to '1' and ADD value of $expired by mode (not set as a DB entry)
                // So... if notice required, value is '1' + mode = '1' then value in DB = '2'.
                // The DB entry is re-set to '0' when the notice has been sent.
                if ($expired_mail_send == 0)
                        $ads->setVar('expired_mail_send', '0');
                else
                        $ads->setVar('expired_mail_send', '1' + $expired_by_mode);

                //expired_by_mode

                //echo $notifypub;
                $ads->setVar('notify_pub', $notify_pub);

                $ads->setVar('poster_ip', $poster_ip);
                $ads->setVar('contact_mode', $mode_contact + $pref_contact);
                $ads->setVar('countpub', $xoopsModuleConfig['nb_pub_again']);
                $ads->setVar('waiting', $waiting);

                $nb_removed_tmp = catads_clear_tmp_files( $photos_dir ) ;
                if (!$ads_handler->insert($ads)) {
                        $msg = sprintf(_MD_CATADS_ERROR_INSERT, $ads->getVar('ads_title'));
                        $msg .= '<br />'.$ads->getErrors();
                        xoops_header();
                        echo "<h4>"._MD_CATADS_MODULE_NAME."</h4>";
                        xoops_error($msg);
                        xoops_footer();
                        exit();
                }
                // Notification
                $ads_id = $xoopsDB->getInsertId();
                $notification_handler =& xoops_gethandler('notification');
                $tags = array();
                $tags['ADS_TITLE'] = $ads_type.' '.$ads_title;

                if ( $xoopsModuleConfig['moderated'] == 1) {

                        $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/ads.php?sel_status=1';
                        $notification_handler->triggerEvent('global', 0, 'ads_submit', $tags);
                        $notification_handler->triggerEvent('category', $topic_id, 'ads_submit', $tags);
                        // If notify checkbox is set, add subscription for approve

                        if ($notify_pub) {

                                include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                                //subscribe = inscrire dans les notifications
                                //triggerEvent = Envoie par email
                                $notification_handler->subscribe('ads', $ads_id, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                        }
                        $messagesent ="<br />"._MD_CATADS_AFTER_MODERATE;

                } else {

                        $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/adslist.php?topic_id=' . $topic_id;
                        $notification_handler->triggerEvent('global', 0, 'new_ads', $tags);
                        $notification_handler->triggerEvent('category', $topic_id, 'new_ads', $tags);
                        $messagesent ="<br />"._MD_CATADS_NO_MODERATE;
                }
                redirect_header("index.php",2,$messagesent);
        break;

// pk preview - check GET VARs

case "preview":

        $msgstop = '';
        $cat = new AdsCategory($topic_id);

        // pk  - bugfix - preview images. NB thumbnail is only created for 'photo0'. All others are scaled.
        // Set image preview width and height here. Ideally, should come from admin pref.

        $photos_preview_dir = XOOPS_URL . "/uploads/".$xoopsModule->dirname()."/images/annonces/original" ;
        $thumb_preview_dir = XOOPS_URL . "/uploads/".$xoopsModule->dirname()."/images/annonces/thumb" ;

        $i = $j = 0;
        // while  ($i < $cat->nb_photo) {
        while  ($i < 6) {

                   if (!empty($_FILES['photo'.$i]['name'])) {
                        catads_upload($i);
                   }
                       if ($preview_name[$i] != '') {
                             $photo = $preview_name[$i] ;
                             $ads['photo'.$j] = "<img src=\"".$photos_preview_dir."/".$photo."\" width=\"125\" height=\"90\" alt=\"\" />" ;
                             $j++;
                       } else {
                             // pk set VAR if no image (blank)
                             $ads['photo'.$j] = '' ;
                             $j++;
                       }

                $i++;

        }

        if (strlen(deleteCode($ads_desc)) > $xoopsModuleConfig['txt_maxlength'] )
                $msgstop .= sprintf(_MD_CATADS_MAXLENGTH, $xoopsModuleConfig['txt_maxlength']).'<br />';
        if (isset($email) && $email != '' && !checkEmail($email) )
                $msgstop .= _MD_CATADS_INVALIDMAIL.'<br />';
        if (isset($price) && $price != '' && !is_numeric(trim($price) )) {
                $msgstop.= _MD_CATADS_INVALIDPRICE.'<br />';
                $price = '';
        }
        if ($mode_contact == 2 && $email == '')
                $msgstop .= _MD_CATADS_MAILREQ.'<br />';
        if ($mode_contact == 3 && $phone == '')
                $msgstop .= _MD_CATADS_PHONEREQ.'<br />';

        $ts =& MyTextSanitizer::getInstance();

        $ads_title= $ts->htmlSpecialChars($ts->stripSlashesGPC($ads_title));

        // $ads_desc= $ts->htmlSpecialChars($ts->stripSlashesGPC($ads_desc));

        $ads_desc= $ts->undoHtmlSpecialChars($ts->stripSlashesGPC($ads_desc));

        // pk duplicate function for preview if the user has not entered any tags...
                if(!$ads_tags)
                {
                        // Create tags from the item title.
                        $newtags = $ads_title;
                        // Omit common letters and words from the tag list. NB spaces before and after are important.
                        $remplace = array(
                        ' of ', ' if ', ' to ', ' in ', ' at ', ' on ', ' by ', ' it ', ' is ', ' or ', ' are ', ' the ', ' for ', ' and ',
                        ' &amp; ', ' when ', ' with '
                        );
                        $par = ' ';// Replace them with a space
                        $newtags = str_replace($remplace, $par, $newtags);
                        // Trim the space from the tag list and save it to a VAR
                        $newtags = trim($newtags, ' ');
                        $ads_tags = $newtags;
                } else {
                        $ads_tags = $ads_tags;
                }

        // pk sanitise tags
        // $ads_tags= $ts->htmlSpecialChars($ts->stripSlashesGPC($ads_tags));

        $ads_tags= $ts->undoHtmlSpecialChars($ts->stripSlashesGPC($ads_tags));

        $phone= $ts->htmlSpecialChars($ts->stripSlashesGPC($phone));

        //$region= $ts->htmlSpecialChars($ts->stripSlashesGPC($region));
        //$departement= $ts->htmlSpecialChars($ts->stripSlashesGPC($departement));

        $town= $ts->htmlSpecialChars($ts->stripSlashesGPC($town));
        if (isset($codpost)) {
                $codpost= $ts->htmlSpecialChars($ts->stripSlashesGPC($codpost));
                $ads['codpost']= $codpost;
        }

        $ads['type']= $ads_type;
        $ads['title']= $ads_title;
        $ads['description']= $ts->previewTarea($ads_desc);

        // $ads['region']= $region;

        //pk - bugfix - get region name from DB
        $region_number = $region;
        $sql1 = $xoopsDB->query("SELECT region_nom FROM ".$xoopsDB->prefix("catads_regions")." WHERE region_numero = ".$region_number);
        list($region_name) = $xoopsDB->fetchRow($sql1);
        $ads['region'] = $region_name;

        // $ads['departement']= $departement;

        //pk - bugfix - get department name from DB
        $departement_number = $departement;
        $sql1 = $xoopsDB->query("SELECT departement_nom FROM ".$xoopsDB->prefix("catads_departements")." WHERE departement_numero = ".$departement_number);
        list($departement_name) = $xoopsDB->fetchRow($sql1);
        $ads['departement'] = $departement_name;

        $ads['town']= $town;

        // $ads['photo']= $photo;

        // $ads['photo'.$i]= $photo[$i]; - no effect

        // pk get video VAR
        $ads['video']= $ads_video;

        // pk get notify_pub VAR
        // $notify_pub = intval($notify_pub);
        $ads['notify_pub'] = $notify_pub;

        if ($xoopsModuleConfig['nb_days_before'] > 0 ) {
                $ads['date_pub']= formatTimestamp(strtotime($published),"s");
                $ads['date_exp']= formatTimestamp(strtotime($published)+ $duration*86400,"s");
                $published = strtotime($published);
                $expired = strtotime($published) + $duration*86400;
        }
        $ads['nbview'] = sprintf(_MD_CATADS_NBVIEW, 0);

        if ($display_price){
                $ads['price']= $price;
                $ads['monnaie']= $monnaie;
                $ads['price_option']= $price_option;
        }

        // pk bugfix - email and PM icons path for preview
        if($pref_contact > 9){
                if ($mode_contact == 1)
                        $ads['pmlink'] = "<br /><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/pm.gif\" alt=\""._MD_CATADS_BYPM."\" /></a>";
                if ($mode_contact == 2)
                        $ads['maillink'] = "<br /><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/email.gif\" alt=\""._MD_CATADS_BYMAIL."\" /></a>";
                if ($mode_contact == 3)
                        $ads['phone'] = '<br /><b>'._MD_CATADS_PHONE.'</b>.<br /> '.$phone;
        } else {
                $ads['msg_contact'] = _MD_CATADS_CONTACT_PREF1.' '._MD_CATADS_BY.' ';
                if ($mode_contact == 1) $ads['msg_contact'] .= _MD_CATADS_CONTACT_MODE1;
                if ($mode_contact == 2) $ads['msg_contact'] .= _MD_CATADS_CONTACT_MODE2;
                if ($mode_contact == 3) $ads['msg_contact'] .= _MD_CATADS_CONTACT_MODE3;
                $ads['msg_contact'] .= '<br /><br />';
                if (isset($email) && $email !='')
                        $ads['maillink'] = "<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/email.gif\" alt=\""._MD_CATADS_BYMAIL."\" /></a>&nbsp;";
                if ($xoopsUser)
                        $ads['pmlink'] = "<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/pm.gif\" alt=\""._MD_CATADS_BYPM."\" /></a><br /><br />";
                if ($phone!= '')
                        $ads['phone'] = '<br /><b>'._MD_CATADS_PHONE.'</b><br /> '.$phone;
        }

        // pk dynamic column formatting removed from template (didn't work) so this is for legacy or future use.
        // $ads['nbcols'] = $xoopsModuleConfig['nb_cols_img'];


        include  XOOPS_ROOT_PATH.'/header.php';
        // pk template file
        $xoopsOption['template_main'] = 'catads_adsform.html';
        $xoopsTpl->assign('preview', true);
        $xoopsTpl->assign('msgstop',$msgstop);
        $xoopsTpl->assign('annonce', $ads);

        // pk assign $ads_tags VAR to $link_tags VAR for preview
        $xoopsTpl->assign('link_tags', $ads_tags);


        include_once "include/form1_ads.inc.php";
//        $adsform->display();
        $xoopsTpl->assign('nb_days_before', $xoopsModuleConfig['nb_days_before']);
        $adsform->assign($xoopsTpl);
        include XOOPS_ROOT_PATH."/footer.php";
        break;

// pk form defaults

case "form":
default:
        $ts =& MyTextSanitizer::getInstance();
        $topic_id = (isset($topic_id)) ? intval($topic_id) : 0;
        $cat = new AdsCategory($topic_id);
        $lastchildren = AdsCategory::getAllLastChild();

        if (in_array($topic_id, $lastchildren)) {
        // rubrique terminale

        //Permissions
        $permHandler = CatadsPermHandler::getHandler();
        if(!$permHandler->isAllowed($xoopsUser, 'catads_submit', $topic_id))
        {
                redirect_header("index.php", 3, _NOPERM);
                exit;
        }

                $xoopsOption['template_main'] = 'catads_adsform.html';
                include XOOPS_ROOT_PATH."/header.php";
                $xoopsTpl->assign('jstime', time());
                $xoopsTpl->assign('nb_days_before', $xoopsModuleConfig['nb_days_before']);

                include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
                $mytree = new XoopsTree($xoopsDB->prefix("catads_cat "),"topic_id","topic_pid");

                $display_price = $cat->display_price;
                $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
                $email = !empty($xoopsUser) ? $xoopsUser->getVar("email", "E") : "";
                $ads_type = '';
                $ads_title = '';
                $ads_desc = '';
                $ads_tags = '';
                $monnaie = '';
                $price = '';
                $price_option = '';
                $phone = '';
                $region = '';
                $departement = '';
                $town = '';
                $codpost = '';

                // pk add notify_pub default
                $notify_pub = 1 ;

                // pk default pref for ad contact?
                $pref_contact = 0;

                // pk add default values for $expired_mail_send and $expired_by_mode
                // see changes in 'form1_adds.inc.php'.
                $expired_mail_send = 1 ;
                $expired_by_mode = 1 ;

                /*if ($xoopsModuleConfig['email_req'] > 0) {
                        $mode_contact = 2;
                } elseif($uid > 0){
                        $mode_contact = 1;
                } else {
                        $mode_contact = 3;
                }*/
                if ($xoopsModuleConfig['email_req'] > 0) {
                        $mode_contact = 2;
                } elseif($uid > 0){
                        $mode_contact = 1;
                } else {
                        $mode_contact = 3;
                }
                $published =time();

                $duration =0;

                // pk whats this? changed to '6' (not sure if nb_photo VAR works)
                $i = 0;
                // while  ($i < $cat->nb_photo) {

                 while  ($i < 6) {
                        $preview_name[$i] = '';
                        $i++;
                }

                include "include/form1_ads.inc.php";
                $adsform->assign($xoopsTpl);

                include XOOPS_ROOT_PATH."/footer.php";
                break;
        }else {
// rubrique intermédiaire
                include_once(XOOPS_ROOT_PATH."/header.php");
                $xoopsOption['template_main'] = 'catads_index.html';
                $tpltype = $xoopsModuleConfig['tpltype'];// 1 en lignes, 2 en colonnes
                $wcol = 100/$xoopsModuleConfig['nbcol'];
                //$show_card = 0;
                $nbcol = $xoopsModuleConfig['nbcol'];
                $xoopsTpl->assign('wcol', $wcol);
                $xoopsTpl->assign('tpltype', $tpltype);
                $xoopsTpl->assign('addads', true);
                //$xoopsTpl->assign('show_card', $show_card);
                $xoopsTpl->assign('nb_col_or_row', $nbcol);
                $ts =& MyTextSanitizer::getInstance();
                if ($topic_id == 0) {
                        $allcat =  AdsCategory::getAllCat(); // array de toutes les catégories
                } else {
                        $mytree = new XoopsTree($xoopsDB->prefix("catads_cat "),"topic_id","topic_pid");
                        $cat_path = $mytree->getpathFromId( $topic_id, 'topic_title');
                        $cat_path = substr($cat_path, 1);
                        $cat_path = str_replace("/"," <img src='".XOOPS_URL."/modules/catads/images/arrow.gif' border='0' alt='' /> ",$cat_path);
                        $xoopsTpl->assign('cat_path', $cat_path);
                }
                $parray = AdsCategory::getCatWithPid($topic_id); //array des objets catégories principales
                $pcount = count($parray);
                // rubriques principales
                for ( $i = 0; $i < $pcount; $i++ ) {
                        $arr_cat = array();
                        $arr_scat = array();
                        $arr_subcat = array();
                        $cptsubcat = 0;
                        $topic_id = $parray[$i]->topic_id();

                        $title = $ts->htmlSpecialChars($parray[$i]->topic_title());
                        $arr_cat[$i]['title'] = $title;
                        $arr_cat[$i]['id'] = $topic_id;
                        if (in_array($topic_id, $lastchildren)) {
                                $arr_cat[$i]['submit'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/submit1.php?topic_id=' . $topic_id;
                        }

                        if ( $parray[$i]->img() != 'blank.gif')
                        {
                                $arr_cat[$i]['image'] = "<img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$parray[$i]->img()."' align='middle' alt='' />";
                        }
                        else
                        {
                                $arr_cat[$i]['image'] = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/no_dispo_mini.gif' align='middle' alt='' />";
                        }
                        $arr_cat[$i]['title'] = $title;
                        $arr_cat[$i]['i'] = $i;

                        $level = 0;
                        $childcats =  AdsCategory::getFirstChildArr($topic_id, 'weight');
                        unset($arr_scat);
                        showsubcat($childcats, 0, $topic_id, $topic_id);
                if ($tpltype == 1) {
                // ajout blocks vides si template en lignes
                        $mod = count($childcats) % $xoopsModuleConfig['nbcol'];
                        $adjust = ($mod > 0) ? $xoopsModuleConfig['nbcol'] - $mod : 0;
                        for ( $j = 0; $j < $adjust; $j++ ) {
                                $cptsubcat++;
                                $arr_scat['newcol']=1;
                                array_push($arr_subcat, $arr_scat);
                        }
                } else {
                // calcul saut de ligne si template en colonnes
                        $mod = ($i+1) % $xoopsModuleConfig['nbcol'];
                        $arr_cat[$i]['newline'] = ($mod == 0) ? true : false;
                }
                        //saut de ligne fct nombre de colonnes
                $arr_cat[$i]['subcat'] = $arr_subcat;
                $xoopsTpl->append('categories', $arr_cat[$i]);

                }
                unset($arr_cat);
                $mod = $pcount % $xoopsModuleConfig['nbcol'];
                $adjust = ($mod > 0) ? $xoopsModuleConfig['nbcol'] - $mod : 0;
                for ( $j = 0; $j < $adjust; $j++ ) {
                        $arr_cat[$j]['title'] = "";
                        $xoopsTpl->append('categories', $arr_cat[$j]);
                }
        }
        $xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');
        include XOOPS_ROOT_PATH."/footer.php";
        break;
}

?>