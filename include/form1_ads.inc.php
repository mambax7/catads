<?php
// $Id: form1_ads.inc.php,v 1.6 2005/07/07 the Cat
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

if (file_exists(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php')) {
        include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php';
} else {
        include_once XOOPS_ROOT_PATH.'/language/english/calendar.php';
}
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/catadsformbreak.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectRegions.php";
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectDepartements.php";

// pk define VARs
        $ads_video = !isset($_REQUEST['ads_video'])? NULL : $_REQUEST['ads_video'];

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
        $cat_path = $mytree->getpathFromId( $topic_id, 'topic_title');
        $cat_path = substr($cat_path, 1);
        $cat_path = str_replace("/"," <img src='".XOOPS_URL."/modules/catads/images/arrow.gif' border='0' alt='' /> ",$cat_path);

        $adsform = new XoopsThemeForm(_MD_CATADS_MENUADD .'<b>'.$cat_path.'</b>', "adsform", $_SERVER['PHP_SELF'] ."?topic_id=$topic_id");
        $adsform->setExtra( "enctype='multipart/form-data'" ) ;

        if ($xoopsModuleConfig['moderated'] > 0) {
                $label_info = new XoopsFormLabel('', _MD_CATADS_MSG_MOD);
                $adsform->addElement($label_info);
        }

        $title_tray = new XoopsFormElementTray(_MD_CATADS_TITLE1.'*','&nbsp;','title');
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



    //$title_tags = new XoopsFormText(_MD_CATADS_TAGS, "ads_tags", 52, 100, $ads_tags);
    //$adsform->addElement($title_tags);

        // Si affichage du prix dans cette catégorie
        if ($display_price)
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

                $price_tray = new XoopsFormElementTray(_MD_CATADS_PRICE_S ,'&nbsp;','price');

                $price_tray->addElement(new XoopsFormText('', "price", 15, 15, $price));
                $price_tray->addElement($select_monnaie);
                $price_tray->addElement($select_price_option);
                $adsform->addElement($price_tray);
        }

        // pk add optional custom tags field (needs new language entry) - FIXED -
        if ( $xoopsModuleConfig['allow_custom_tags'] == 1) {
        $title_tags = new XoopsFormText(_MD_CATADS_TAGS, "ads_tags", 52, 100, $ads_tags);
        $adsform->addElement($title_tags);

        $lien_tags_help = new XoopsFormLabel('', "<small><strong>"._MD_CATADS_TAGS_HELP."</strong></small>");
        $adsform->addElement($lien_tags_help);
        }
        // pk show optional video field
        if ( $xoopsModuleConfig['show_video_field'] == 1) {
        $lien_video = new XoopsFormText(_MD_CATADS_VIDEO, "ads_video", 60, 100, $ads_video);
        $adsform->addElement($lien_video);

        $lien_video_help = new XoopsFormLabel('', "<small><strong>"._MD_CATADS_VIDEO_HELP."</strong></small>");
        $adsform->addElement($lien_video_help);
        }

        // upload image(s)
        $i = 0;
        while  ($i < $cat->nb_photo) {
                $file_tray = new XoopsFormElementTray(_MD_CATADS_ADDIMG ,'&nbsp;','photo'.$i);
                $file_img = new XoopsFormFile('', 'photo'.$i, $xoopsModuleConfig['photo_maxsize']);
                $file_img->setExtra( "size ='40'") ;
                $file_tray->addElement($file_img);
                $msg = sprintf(_MD_CATADS_IMG_CONFIG, intval($xoopsModuleConfig['photo_maxsize']/1000),$xoopsModuleConfig['photo_maxwidth'],$xoopsModuleConfig['photo_maxheight']);
                $file_label = new XoopsFormLabel('' ,'<br />'.$msg);
                $file_tray->addElement($file_label);
                $adsform->addElement($file_tray);
                $adsform->addElement(new XoopsFormHidden('previewname_'.$i, $preview_name[$i]));
                unset($file_img);
                unset($file_tray);
                $i++;
        }

//      $adsform->insertBreak(_MD_CATADS_CONTACT_S,'itemHead');
        $adsform->addElement(new XoopsFormBreak(1,_MD_CATADS_CONTACTME));

        //if ($xoopsModuleConfig['email_req'] > 0) {
                //$star = ($xoopsModuleConfig['email_req'] > 1) ? '*' : '';
                $text_email = new XoopsFormText(_MD_CATADS_MAIL_S, "email", 50, 100, $email);
                $adsform->addElement($text_email, true);
        //}

        $text_phone = new XoopsFormText(_MD_CATADS_PHONE_S, "phone", 20, 20,$phone);
        $adsform->addElement($text_phone, false);

        //Regions/Departemens
        if ( $xoopsModuleConfig['region_req'] > 0 || $xoopsModuleConfig['departement_req'] > 0 )
        {
                //Regions
                if ($xoopsModuleConfig['region_req'] > 0)
                {
                        $region = new formSelectRegions(_MD_CATADS_REGION, "region", $region);
                        $adsform->addElement($region, true);
                }
                //Departements
                if ($xoopsModuleConfig['departement_req'] > 0)
                {
                        //echo $region;
                        $departement = new formSelectDepartements(_MD_CATADS_DEPARTEMENT, "departement", $departement);
                        $adsform->addElement($departement, true);
                }
        }

        //Ville/code postal

        //ville
        $text_town = new XoopsFormText(_MD_CATADS_TWON, "town", 50, 100,$town);
        $adsform->addElement($text_town, true);

        //Code postal
        if ($xoopsModuleConfig['zipcode_req'] > 0)
        {
                $text_codpost = new XoopsFormText(_MD_CATADS_ZIPCOD, "codpost", 20, 20, $codpost);
                $adsform->addElement($text_codpost, true);
        }

        $contact_tray = new XoopsFormElementTray(_MD_CATADS_CONTACT_MODE,'&nbsp;','contact_mode');
        $select_prefcontact = new XoopsFormSelect('', "pref_contact", $pref_contact);
        $select_prefcontact->addOptionArray(array('0'=>_MD_CATADS_CONTACT_PREF1,'10'=>_MD_CATADS_CONTACT_PREF2));
        $contact_tray->addElement($select_prefcontact, true);
        $contact_tray->addElement(new XoopsFormLabel('',_MD_CATADS_BY));
        $select_modecontact = new XoopsFormSelect('', "mode_contact", $mode_contact);

        //if ($xoopsModuleConfig['email_req'] > 0)
        $select_modecontact->addOption(2,_MD_CATADS_CONTACT_MODE2);
        $select_modecontact->addOption(1,_MD_CATADS_CONTACT_MODE1);
        //if ($uid > 0)
        $select_modecontact->addOption(3,_MD_CATADS_CONTACT_MODE3);

        $contact_tray->addElement($select_modecontact, true);
        $adsform->addElement($contact_tray);

        // if ($xoopsModuleConfig['nb_days_before'] > 0 ) {
        if ($xoopsModuleConfig['allow_publish_date'] == 1) {
        include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/catadsformtextdateselect.php");
        $date_pub = new catadsFormTextDateSelect(_MD_CATADS_DATE_PUB,'published',15, $published);
        $date_pub->setExtra("readonly = 'readonly'");
        $adsform->addElement($date_pub);
        }


        //$adsform->addElement(new XoopsFormRadioYN(_MD_CATADS_CHOICE_MAIL_EXP, 'expired_mail_send', 1), true);
        // pk replaced default values for expired_mail_send and expired_by_mode with VAR to maintain state after ad is previewed.
        // Default values ('1' and '1') are now set in 'submit1.php'.

        // show notification options if pref allows - or set email as default

        if (($xoopsModuleConfig['auto_mp'] > 0 ) && ($xoopsModuleConfig['show_notification_options'] == 1)) {

                $expired_tray = new XoopsFormElementTray(_MD_CATADS_CHOICE_MAIL_EXP ,'&nbsp;','expired');
                $expired_tray->addElement(new XoopsFormRadioYN('', 'expired_mail_send', $expired_mail_send), true);
                $expired_tray->addElement(new XoopsFormLabel('',_MD_CATADS_BY));

                $select_prefcontact1 = new XoopsFormSelect('', "expired_by_mode", $expired_by_mode);
                $select_prefcontact1->addOptionArray(array('0'=>_MD_CATADS_CONTACT_MODE1,'1'=>_MD_CATADS_CONTACT_MODE2));

                $expired_tray->addElement($select_prefcontact1);
                $adsform->addElement($expired_tray);

        } else if(($xoopsModuleConfig['auto_mp'] > 0 ) && ($xoopsModuleConfig['show_notification_options'] != 1)) {

             $adsform->addElement(new XoopsFormHidden('expired_mail_send', '1')); // yes
             $adsform->addElement(new XoopsFormHidden('expired_by_mode', '1'));   // by email
        }


        $duration_tray = new XoopsFormElementTray(_MD_CATADS_DURATION_PUB,'&nbsp;','dddd');
        $select_duration_option = new XoopsFormSelect('', "duration", $duration);
        for ( $i = 0; $i < $count; $i++ ) {
                if ($arr_option[$i]['type'] == 4) $select_duration_option->addOption($arr_option[$i]['desc'],$arr_option[$i]['desc']);
        }
        $duration_tray->addElement($select_duration_option, true);
        $duration_tray->addElement(new XoopsFormLabel('',_MD_CATADS_DAYS));
        $adsform->addElement($duration_tray);

        //Hack SecurityImage by Dugris
        if (defined('SECURITYIMAGE_INCLUDED')) {

                $security_image = new SecurityImage( _SECURITYIMAGE_GETCODE );

                if ($security_image->render()) {

                        $adsform->addElement($security_image, true);

                }

        }
        //Hack SecurityImage by Dugris

        // pk notify on publication VAR - bugfix - add variable to maintain state on preview
        if ($xoopsModuleConfig['moderated'] == 1) {
                $check_advert = new XoopsFormCheckBox('', 'notify_pub', $notify_pub);
                $check_advert->addOption(1,_MD_CATADS_ADVERT);
                $adsform->addElement($check_advert);
        }

        $button_tray = new XoopsFormElementTray(_MD_CATADS_PREVIEW_TEXT,'&nbsp;','button');
        $button_tray->addElement(new XoopsFormButton('', 'preview', _PREVIEW, 'submit'));
        $button_tray->addElement(new XoopsFormButton(_MD_CATADS_SUBMIT_AD, 'post', _SEND, 'submit'));
        $button_cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $button_cancel->setExtra("onclick='location=\"submit1.php?topic_id=".$topic_id."&op=cancel\";'");
        $button_tray->addElement($button_cancel);
        $adsform->addElement($button_tray);

        $adsform->addElement(new XoopsFormHidden('uid', $uid));
        $adsform->addElement(new XoopsFormHidden('display_price', $display_price));

?>