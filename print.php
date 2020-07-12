<?php
// $Id: print.php,v 1.1 2005/02/14 C. Felix AKA the Cat
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
include(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");

$ts =& MyTextSanitizer::getInstance();

foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}

        $ads_handler = & xoops_getmodulehandler('ads');
        $ads = & $ads_handler->get($ads_id);

        if (!is_object($ads)) {
                redirect_header('index.php',3,_MD_CATADS_NO_EXIST);
                exit();
        }

        $ads_exist = false;
        if ($ads->getVar('waiting') == 0 && $ads->getVar('expired') > time())
                $ads_exist = true;

        if (!$ads_exist) {
                redirect_header('index.php',3,_MD_CATADS_NO_ADS);
                exit();
        }

function catads_get_ad($ads) {
                $ts =& MyTextSanitizer::getInstance();

                $annonce['id'] = $ads_id = $ads->getVar('ads_id');
                $annonce['date_pub'] = ($ads->getVar('published') > 0) ? formatTimestamp($ads->getVar('published'),"s") : 0;
                $annonce['date_exp'] = ($ads->getVar('expired') > time()) ? formatTimestamp($ads->getVar('expired'),"s") : 0;
                $annonce['suspend'] = $ads->getVar('suspend');

                $annonce['contact'] = '';
                switch($ads->getVar('contact_mode'))
                {
                        case 1:
                                $annonce['contact'] .=' '._MD_CATADS_CONTACT_PREF1.' '._MD_CATADS_CONTACT_MODE1;
                        break;

                        case 2:
                                $annonce['contact'] .='  '._MD_CATADS_CONTACT_PREF1.' '._MD_CATADS_CONTACT_MODE2;

                        break;

                        case 3:
                                $annonce['contact'] .='  '._MD_CATADS_CONTACT_PREF1.' '._MD_CATADS_CONTACT_MODE3.' '.$ads->getVar('phone');
                        break;

                        case 11:
                                $annonce['contact'] .=' '._MD_CATADS_CONTACT_PREF2.' '._MD_CATADS_CONTACT_MODE2;
                        break;

                        case 12:
                                $annonce['contact'] .='  '._MD_CATADS_CONTACT_PREF2.' '._MD_CATADS_CONTACT_MODE1;

                        break;

                        case 13:
                                $annonce['contact'] .='  '._MD_CATADS_CONTACT_PREF2.' '._MD_CATADS_CONTACT_MODE3.' '.$ads->getVar('phone');
                        break;
                }


                $annonce['type'] = $ads->getVar('ads_type');
                $annonce['title'] = $ads->getVar('ads_title');

                // pk remove special chars
                $pk_desc = $ts->undoHtmlSpecialChars($ads->getVar('ads_desc'));
                $annonce['description'] = $ts->displayTarea($pk_desc, 0, 1, 1);

                $annonce['price'] = $ads->getVar('price');
                if ($ads->getVar('price') > 0){
                        $annonce['price'] = $ads->getVar('price');
                        $annonce['price'] .= ' '.$ads->getVar('monnaie');
                        $annonce['price'] .= ' '.$ads->getVar('price_option');
                }
                $annonce['town'] = $ads->getVar('town');
                $annonce['codpost'] = $ads->getVar('codpost');
                $annonce['photo'] = $ads->getVar('photo0');
        return ($annonce);
}

function catads_print_head() {
        global $xoopsConfig;
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset='._CHARSET.'" />';
        echo '<title>'.$xoopsConfig['sitename'].'</title>';
        echo '<meta name="AUTHOR" content="'.$xoopsConfig['sitename'].'" />';
        echo '<meta name="COPYRIGHT" content="Copyright (c) 2001 by '.$xoopsConfig['sitename'].'" />';
        echo '<meta name="DESCRIPTION" content="'.$xoopsConfig['slogan'].'" />';
        echo '<meta name="GENERATOR" content="'.XOOPS_VERSION.'" />';
        echo '<body bgcolor="#ffffff" text="#000000" onload="window.print()">
            <table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
            <table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff">
                <tr>';
        echo '<td align="center"><b>'._MD_CATADS_FROMSITE.' '.$xoopsConfig['sitename'].'</b></td>';
}

function catads_print_foot() {
        echo '<tr><td>
            <div align="center"><a href="'.XOOPS_URL.'/">'.XOOPS_URL.'</a></div><br /></td></tr></table>
            </td></tr></table>';
        echo '</body>
            </html>
            ';
}

function catads_print_ad($annonce) {
        global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
//        $myts =& MyTextSanitizer::getInstance();
    echo '<tr>
        <td align="left"><hr>';
        // pk make ad-type conditional
        $show_ad_type = $xoopsModuleConfig['show_ad_type'];
        if($show_ad_type =='1'){
        echo '<b>'.$annonce['type'] . ' - ' . $annonce['title'].'</b><br/>';
        } else {
         echo '<b>'. $annonce['title'].'</b><br/>';
        }


        echo '<b>'.$annonce['codpost'] . ' - ' . $annonce['town'].'</b><br/>';
        if ($annonce['photo'] != '') {
                echo '<img src="'.XOOPS_URL.'/uploads/'.$xoopsModule->dirname().'/images/annonces/original/'.$annonce['photo'].'" class="imgContour" style="width: 100px; height: 100px;" align="right" alt="" />';
        } else {
                echo '<img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/no_dispo.gif" class="imgContour" style=\"width: 100px; height: 100px;\" align="right" alt="" />';
        }
        if ($annonce['price'] != 0)
                echo '<div align="left">'._MD_CATADS_PRICE2.$annonce['price'].'</div><br/>';

        echo $annonce['description'].'<br /><br />';
        if ($annonce['suspend'] != 1)
                echo '<b>'._MD_CATADS_CONTACT.' : </b>'.$annonce['contact'].'<br /><br />';
        else
                echo _MD_CATADS_PUB_SUSP.'<br /><br />';
        echo '<div align="left"><small><b>'._MD_CATADS_DATE.'</b>&nbsp;'.$annonce['date_pub'].'&nbsp;&nbsp;<b>'._MD_CATADS_DATE_EXP.'</b>&nbsp;'.$annonce['date_exp'].'</small><br />
        </td></tr>';
}


if (!isset($op)) $op = 'showone';

switch ($op) {
        case "showone":
        default:
                catads_print_head();
                catads_print_ad(catads_get_ad($ads));
                catads_print_foot();
                break;

}

?>