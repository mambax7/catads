<?php
// $Id: form3_ads.inc.php, v 1.11 2005/07/12 the Cat
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectRegions.php";
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectDepartements.php";

// pk define vars
$preview_name = !isset($_REQUEST['preview_name'])? NULL : $_REQUEST['preview_name'];

// set cat var
$cat = new AdsCategory($cat_id);


        $adsform = new XoopsThemeForm(_AM_CATADS_FORMEDIT, "annonceform", xoops_getenv('PHP_SELF')."?ads_id=$ads_id");
        $adsform->setExtra( "enctype='multipart/form-data'" ) ;
        // Afiichage statut
        $msg1  = _AM_CATADS_DATE_CREA;
        $msg1 .= '<br /><br />'._AM_CATADS_STATUS;
        $msg2  = formatTimestamp($created,"m");

        if ($waiting == 0 && $expired < time()) {
                $delay = (intval((time()-$expired)/86400) > 0) ? intval((time()-$expired)/86400)._AM_CATADS_DAYS : _AM_CATADS_TODAY;
                $msg2 .= '<br /><br />'._AM_CATADS_EXP2.$delay;
        } elseif ($waiting == 0 && $published < time()) {
                $delay = (intval((time()-$published)/86400) > 0) ? intval((time()-$published)/86400)._AM_CATADS_DAYS : _AM_CATADS_TODAY;
                $msg2 .= '<br /><br />'._AM_CATADS_PUB2.$delay;
        } elseif ($published > time()) {
                $delay = (intval(($published-time())/86400) > 0) ? _AM_CATADS_TO.intval(($published-time())/86400)._AM_CATADS_DAYS : _AM_CATADS_TODAY;
                $msg2 .= '<br /><br />'._AM_CATADS_DELAY_PUB.$delay;
        } else  {
                $delay = (intval((time()-$created)/86400)>0) ? intval((time()-$created)/86400)._AM_CATADS_DAYS : _AM_CATADS_TODAY;
                $msg2 .= '<br /><br />'._AM_CATADS_WAIT2.$delay;
        }
        $adsform->addElement(new XoopsFormLabel($msg1,$msg2));

    $adsform->addElement(new XoopsFormDateTime(_AM_CATADS_DATE_PUB,'published',15, $published));
    $adsform->addElement(new XoopsFormDateTime(_AM_CATADS_DATE_EXP,'expired',15, $expired));

        // Affichage annonce
        $title_tray = new XoopsFormElementTray(_AM_CATADS_TITLE_ADS,'');
        // show ad type
        if ($xoopsModuleConfig['show_ad_type'] == '1'){
         $type_text = new XoopsFormSelect('', "ads_type", $ads_type);
         for ( $i = 0; $i < $count; $i++ ) {
                if ($arr_option[$i]['type'] == 3) $type_text->addOption($arr_option[$i]['desc'],$arr_option[$i]['desc']);
         }
         $title_tray->addElement($type_text, true);
        }

        $title_text = new XoopsFormText('', "ads_title", 52, 100, $ads_title);
        $title_tray->addElement($title_text, true);
        $adsform->addElement($title_tray, true);

        // if cat pref is to show price
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

                $price_tray = new XoopsFormElementTray(_AM_CATADS_PRICE ,'&nbsp;','price');

                $price_tray->addElement(new XoopsFormText('', "price", 15, 15, $price));
                $price_tray->addElement($select_monnaie);
                $price_tray->addElement($select_price_option);
                $adsform->addElement($price_tray);
        }

         // Rubrique
        $xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
        ob_start();
        $xt->makeMySelBox('topic_title','topic_title', $cat_id, 1);
        $cat_select = new XoopsFormLabel(_AM_CATADS_TITLE_CAT, ob_get_contents());
        ob_end_clean();
        $adsform->addElement($cat_select);

        if ($xoopsModuleConfig['bbcode'] == 1) {
                $annonce_text = new XoopsFormDhtmlTextArea(_AM_CATADS_DESCR, "ads_desc", $ads_desc);
        } else {
                $annonce_text = new XoopsFormTextArea(_AM_CATADS_DESCR, "ads_desc", $ads_desc);
        }
        $adsform->addElement($annonce_text, true);

        $title_tags = new XoopsFormText(_AM_CATADS_TAGS, "ads_tags", 52, 100, $ads_tags);
    $adsform->addElement($title_tags);

        $lien_video = new XoopsFormText(_AM_CATADS_VIDEO, "ads_video", 60, 100, $ads_video);
    $adsform->addElement($lien_video);


if ($cat->nb_photo > 0) {
                $i = 0;
                while  ($i < $cat->nb_photo) {
                        $file_tray = new XoopsFormElementTray(_AM_CATADS_IMG.' '.($i+1), '');
                        if (isset($photo[$i])){
                                $file_tray->addElement(new XoopsFormLabel('', "<img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/annonces/original/".$photo[$i]."' name='image' id='image' alt=''/><br /><br />" ));
                                $check_del_img = new XoopsFormCheckBox('', 'delimg_'.$i);
                                $check_del_img->addOption(1,_AM_CATADS_DELIMG);
                                $file_tray->addElement($check_del_img);
                                $file_img = new XoopsFormFile(_AM_CATADS_REPLACEIMG, 'photo'.$i, $xoopsModuleConfig['photo_maxsize']);
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

        // Partie contact
        $adsform->insertBreak(_AM_CATADS_CONTACT,'bg3');
        $msg1 = _AM_CATADS_ADS_FROM;
        $msg2 = $submitter;
        $adsform->addElement(new XoopsFormLabel($msg1,$msg2));

        //if ($xoopsModuleConfig['email_req'] > 0) {
                //if($xoopsModuleConfig['email_req'] > 1 || $contact_mode == 2)
        if( $contact_mode == 2 )
                        $star = '*';
                $email_text = new XoopsFormText(_AM_CATADS_EMAIL.'*', "email", 50, 100, $email);
                $adsform->addElement($email_text, ($star == '*'));
        //}

        $phone_text = new XoopsFormText(_AM_CATADS_PHONE, "phone", 20, 20,$phone);
        $adsform->addElement($phone_text, false);

        $adress_text = new XoopsFormText(_AM_CATADS_TOWN, "town", 35, 50,$town);
        $adsform->addElement($adress_text,true);

        //Regions
        if ($xoopsModuleConfig['region_req'] > 0)
        {
                $adsform->addElement(new formSelectRegions(_AM_CATADS_REGION, "region", $region), true);
        }
        //Departements
        if ($xoopsModuleConfig['departement_req'] > 0)
        {
                $adsform->addElement(new formSelectDepartements(_AM_CATADS_DEPARTEMENT, "departement", $departement), true);
        }

        if ($xoopsModuleConfig['zipcode_req'] > 0)
        {
                //$star = ($xoopsModuleConfig['zipcode_req'] > 1) ? '*' : '';
                //$codpost_text = new XoopsFormText(_AM_CATADS_CODPOST, "codpost", 20, 20, $codpost);
                $adsform->addElement(new XoopsFormText(_AM_CATADS_CODPOST, "codpost", 20, 20, $codpost), true);
        }

        $adsform->addElement(new XoopsFormRadioYN(_AM_CATADS_PUBADS, 'waiting', ($waiting > 0) ? 0 : 1));

        $button_tray = new XoopsFormElementTray('','');
        $button_tray->addElement(new XoopsFormHidden('op', 'modify' ));
        $button_tray->addElement(new XoopsFormButton('', 'save', _SEND, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit'));
        $adsform->addElement($button_tray);
        $adsform->display();
?>