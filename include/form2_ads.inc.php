<?php
// $Id: form2_ads.inc.php,v 1.1 2005/07/15 the Cat
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
//  ------------------------------------------------------------------------ //

include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/catadsformbreak.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectRegions.php";
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectDepartements.php";

// Récupération options de liste
        $option_handler =& xoops_getmodulehandler('option');
        $criteria = new Criteria('option_id', '0', '>');
        $criteria->setSort('option_type, option_order');
        $option = $option_handler->getObjects($criteria);
        $count = 0;
        foreach($option as $oneoption){
                $arr_option[$count]['id'] = $oneoption->getVar('option_id');
                $arr_option[$count]['type'] = $oneoption->getVar('option_type');
                $arr_option[$count]['desc'] = $oneoption->getVar('option_desc');
                $arr_option[$count]['order'] = $oneoption->getVar('option_order');
                $count++;
        }

        $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
        $cat_path = $mytree->getpathFromId( $cat_id, 'topic_title');
        $cat_path = substr($cat_path, 1);
        $cat_path = str_replace("/"," <img src='".XOOPS_URL."/modules/catads/images/arrow.gif' border='0' alt='' /> ",$cat_path);

        $cat = new AdsCategory($cat_id);



        if ($xoopsModuleConfig['moderated'] != 1) {

        $adsform = new XoopsThemeForm(_MD_CATADS_MENUADD .'<b>'.$cat_path.'</b>', "adsform", $_SERVER['PHP_SELF'] ."?cat_id=$cat_id");
        $adsform->setExtra( "enctype='multipart/form-data'" ) ;

         // pk - bugfix - show moderated message only if moderated pref is selected
         if ($xoopsModuleConfig['moderated'] == 1) {
         $label_info = new XoopsFormLabel('', _MD_CATADS_MSG_MOD);
         $adsform->addElement($label_info);
         }


                $title_tray = new XoopsFormElementTray(_MD_CATADS_TITLE.'*','');
                // pk make ad-type optional
                if ($xoopsModuleConfig['show_ad_type'] == 1) {
                $text_type = new XoopsFormSelect('', "ads_type", $ads_type);
                        for ( $i = 0; $i < $count; $i++ ) {
                        if ($arr_option[$i]['type'] == 3) $text_type->addOption($arr_option[$i]['desc'],$arr_option[$i]['desc']);
                        }
                $title_tray->addElement($text_type, true);
                }

                $text_title = new XoopsFormText('', "ads_title", 52, 100, $ads_title);
                $title_tray->addElement($text_title, true);
                $adsform->addElement($title_tray, true);

        // Formulaire avec ou sans bbcodes
                if ($xoopsModuleConfig['bbcode'] == 1) {
                        $text_annonce = new XoopsFormDhtmlTextArea(_MD_CATADS_TEXTE_S.'*', "ads_desc", $ads_desc);
                } else {
                        $text_annonce = new XoopsFormTextArea(_MD_CATADS_TEXTE_S.'*', "ads_desc", $ads_desc);
                }
                $adsform->addElement($text_annonce, true);


                // pk add tags and video edit fields (needs new language entries) - FIXED
                if ( $xoopsModuleConfig['allow_custom_tags'] == 1) {
                $title_tags = new XoopsFormText(_MD_CATADS_TAGS, "ads_tags", 52, 100, $ads_tags);
                $adsform->addElement($title_tags);

                $lien_tags_help = new XoopsFormLabel('', "<small><strong>"._MD_CATADS_TAGS_HELP."</strong></small>");
                $adsform->addElement($lien_tags_help);
                }

                // pk show optional video fields
                if ( $xoopsModuleConfig['show_video_field'] == 1) {
                $lien_video = new XoopsFormText(_MD_CATADS_VIDEO, "ads_video", 60, 100, $ads_video);
                $adsform->addElement($lien_video);

                // pk add video help
                $lien_video_help = new XoopsFormLabel('', "<small><strong>"._MD_CATADS_VIDEO_HELP."</strong></small>");
                $adsform->addElement($lien_video_help);
                }



                //$title_tags = new XoopsFormText(_MD_CATADS_TAGS, "ads_tags", 52, 100, $ads_tags);
                //$adsform->addElement($title_tags);

                // Si affichage du prix dans cette catégorie
                if ($cat->display_price)
                {
                        $select_monnaie = new XoopsFormSelect('', "monnaie", $monnaie);
                                for ( $i = 0; $i < $count; $i++ ) {
                                        if ($arr_option[$i]['type'] == 1) $select_monnaie->addOption($arr_option[$i]['desc'],$arr_option[$i]['desc']);
                                }

                        $select_price_option = new XoopsFormSelect('', "price_option", $price_option);
                        $select_price_option->addOption('','');
                        for ( $i = 0; $i < $count; $i++ ) {
                                if ($arr_option[$i]['type'] == 2) $select_price_option->addOption($arr_option[$i]['desc'],$arr_option[$i]['desc']);
                        }

                        $price_tray = new XoopsFormElementTray(_MD_CATADS_PRICE_S ,'');

                        $price_tray->addElement(new XoopsFormText('', "price", 15, 15, $price));
                        $price_tray->addElement($select_monnaie);
                        $price_tray->addElement($select_price_option);
                        $adsform->addElement($price_tray);
                }
        } else {
        $adsform = new XoopsThemeForm(_MD_CATADS_MENUADD1.'&nbsp;'._MD_CATADS_PHOTO_CAUTION, "adsform", $_SERVER['PHP_SELF'] ."?cat_id=$cat_id");
        $adsform->setExtra( "enctype='multipart/form-data'" ) ;
        }
//photos
        if ($cat->nb_photo > 0) {

                if ($xoopsModuleConfig['moderated'] != 1) {
                        $adsform->addElement(new XoopsFormBreak(1, _MD_CATADS_PHOTO.'&nbsp;&nbsp;'._MD_CATADS_PHOTO_CAUTION ));
                }
                $i = 0;
                while  ($i < $cat->nb_photo) {
                        $file_tray = new XoopsFormElementTray(_MD_CATADS_IMG.' '.($i+1), '');
                        if (isset($photo[$i])){
                                $file_tray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/annonces/original/".$photo[$i]."' name='image' id='image' alt=''/><br /><br />" ));
                                $check_del_img = new XoopsFormCheckBox('', 'delimg_'.$i);
                                $check_del_img->addOption(1,_MD_CATADS_DELIMG);
                                $file_tray->addElement($check_del_img);
                                $file_img = new XoopsFormFile(_MD_CATADS_REPLACEIMG, 'photo'.$i, $xoopsModuleConfig['photo_maxsize']);
                                $file_img->setExtra( "size ='40'") ;
                                unset($check_del_img);
                        } else {
                                $file_img = new XoopsFormFile('', 'photo'.$i, $xoopsModuleConfig['photo_maxsize']);
                                $file_img->setExtra( "size ='40'") ;
                        }
                        $file_tray->addElement($file_img);
                        $adsform->addElement($file_tray);
                        $adsform->addElement(new XoopsFormHidden('previewname_'.$i, $preview_name[$i]));
                        unset($file_img);
                        unset($file_tray);
                        $i++;
                }
        }

        // pk if ads NOT moderated
        if ($xoopsModuleConfig['moderated'] != 1) {
                $adsform->addElement(new XoopsFormBreak(2,_MD_CATADS_CONTACTME));

                $text_email = new XoopsFormText(_MD_CATADS_MAIL_S.'*', "email", 50, 100, $email);
                $adsform->addElement($text_email, true);


                $text_phone = new XoopsFormText(_MD_CATADS_PHONE_S, "phone", 20, 20,$phone);
                $adsform->addElement($text_phone, false);

                //Regions
                if ($xoopsModuleConfig['region_req'] > 0)
                {
                        $adsform->addElement(new formSelectRegions(_MD_CATADS_REGION.'*', "region", $region), true);
                }
                //Departements
                if ($xoopsModuleConfig['departement_req'] > 0)
                {
                        $adsform->addElement(new formSelectDepartements(_MD_CATADS_DEPARTEMENT.'*', "departement", $departement), true);
                }

                $text_adress = new XoopsFormText(_MD_CATADS_CITY_S.'*', "town", 35, 50,$town);
                $adsform->addElement($text_adress,true);

                //Code postal
                if ($xoopsModuleConfig['zipcode_req'] > 0) {
                        $text_codpost = new XoopsFormText(_MD_CATADS_CODPOST_S.'*', "codpost", 20, 20, $codpost);
                        $adsform->addElement($text_codpost, ($xoopsModuleConfig['zipcode_req'] > 1));
                }

                $contact_tray = new XoopsFormElementTray(_MD_CATADS_CONTACT_MODE,'&nbsp;','contact_mode');
                $select_prefcontact = new XoopsFormSelect('', "pref_contact", $pref_contact);
                $select_prefcontact->addOptionArray(array('0'=>_MD_CATADS_CONTACT_PREF1,'10'=>_MD_CATADS_CONTACT_PREF2));
                $contact_tray->addElement($select_prefcontact, true);
                $contact_tray->addElement(new XoopsFormLabel('',_MD_CATADS_BY));
                $select_modecontact = new XoopsFormSelect('', "mode_contact", $mode_contact);

                $select_modecontact->addOption(2,_MD_CATADS_CONTACT_MODE2);
                if ($uid > 0)
                $select_modecontact->addOption(1,_MD_CATADS_CONTACT_MODE1);
                $select_modecontact->addOption(3,_MD_CATADS_CONTACT_MODE3);

                $contact_tray->addElement($select_modecontact, true);
                $adsform->addElement($contact_tray);

                        // if ($xoopsModuleConfig['auto_mp'] > 0 ) {
                        if (($xoopsModuleConfig['auto_mp'] > 0 )&&($xoopsModuleConfig['show_notification_options'] == 1)) {
                        $expired_tray = new XoopsFormElementTray(_MD_CATADS_CHOICE_MAIL_EXP ,'&nbsp;','expired');
                        $expired_tray->addElement(new XoopsFormRadioYN('', 'expired_mail_send', $expired_mail_send), true);
                        $expired_tray->addElement(new XoopsFormLabel('',_MD_CATADS_BY));

                        // mode of notification - 'expired_by_mode' value is ADDED to expired mail send value for DB (0 = PM, 1 = email)
                        // $select_prefcontact1 = new XoopsFormSelect('', "expired_by_mode", 0);
                        // pk get notification mode (add VAR as value and set VAR in adsedit.php)
                        $select_prefcontact1 = new XoopsFormSelect('', "expired_by_mode", $expired_by_mode);
                        $select_prefcontact1->addOptionArray(array('0'=>_MD_CATADS_CONTACT_MODE1,'1'=>_MD_CATADS_CONTACT_MODE2));

                        $expired_tray->addElement($select_prefcontact1);
                        $adsform->addElement($expired_tray);
                        }


           // pk notify on publication VAR - bugfix - add variable to maintain state on preview
           // need to decide how to implement this if ads are moderated but edits are allowed

           /* if ($xoopsModuleConfig['moderated'] == 1) {
                $check_advert = new XoopsFormCheckBox('', 'notify_pub' $notify_pub);
                $check_advert->addOption(1,_MD_CATADS_ADVERT);
                $adsform->addElement($check_advert);
              } */

        }

        $button_tray = new XoopsFormElementTray('' ,'');
        $button_tray->addElement(new XoopsFormButton('', 'post', _SEND, 'submit'));
        $button_cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $button_cancel->setExtra("onclick='location=\"adsitem.php?ads_id=".$ads_id."\";'");
        if ($xoopsModuleConfig['usercandelete']) {
                $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit'));
        }
        $button_tray->addElement($button_cancel);
        $adsform->addElement($button_tray);

        $adsform->addElement(new XoopsFormHidden('ads_id', $ads_id));
        $adsform->addElement(new XoopsFormHidden('cat_id', $cat_id));
        $adsform->addElement(new XoopsFormHidden('uid', $uid));
        $adsform->addElement(new XoopsFormHidden('display_price', $cat->display_price));

?>