<?php
// $Id: cp_functions.php,v 2.3 2005/07/07 C.Felix alias the Cat
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


function catads_admin_menu ($currentoption = 0, $breadcrumb = '')
{
global $xoopsModule, $xoopsConfig;
        /* Nice buttons styles */
        echo "
            <style type='text/css'>
            #buttontop { float:left; width:100%; background: #e7e7e7; font-size:12px; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
            #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() . "/images/deco/bg.png') repeat-x left bottom; font-size:12px; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
            #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
                #buttonbar li { display:inline; margin:0; padding:0; }
                #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() ."/images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
                #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/". $xoopsModule->dirname() . "/images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
                /* Commented Backslash Hack hides rule from IE5-Mac \*/
                #buttonbar a span {float:none;}
                /* End IE5-Mac hack */
                #buttonbar a:hover span { color:#333; }
                #buttonbar #current a { background-position:0 -150px; border-width:0; }
                #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
                #buttonbar a:hover { background-position:0% -150px; }
                #buttonbar a:hover span { background-position:100% -150px; }
                </style>
    ";



        $tblColors = Array();
        $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = $tblColors[9] = '';
        $tblColors[$currentoption] = 'current';

        if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {

                include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
        } else {
                include_once XOOPS_ROOT_PATH . '/modules/'. $xoopsModule->dirname() .'/language/english/modinfo.php';
        }

        echo "<div id='buttontop'>";
        echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
        echo "<td style=\"width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\">
        <a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule -> getVar( 'mid' )."\">" . _AM_CATADS_GENERALSET . "</a> | <a href='" . XOOPS_URL . "/modules/catads/index.php'>" . _AM_CATADS_GOINDEX. "</a> | <a href=\"about.php\">" . _AM_CATADS_ABOUT . "</a></td>";
        echo "<td style=\"width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\"><strong>" . _AM_CATADS_PAGE . "</strong> " . $breadcrumb . "</td>";
        echo "</tr></table>";
        echo "</div>";

        echo "<div id='buttonbar'>";
        echo "<ul>";
        echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/index.php\"><span>" . _AM_CATADS_INDEXMANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/ads.php\"><span>" . _AM_CATADS_ADSMANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/category.php\"><span>" . _AM_CATADS_CATMANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/options.php\"><span>" . _AM_CATADS_OPTMANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[4] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/purge.php\"><span>" . _AM_CATADS_PURGEMANAGE . "</span></a></li>";
        //echo "<li id='" . $tblColors[5] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/import_images.php\"><span>" . _AM_CATADS_IMPORT_IMAGE_MANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[5] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/permissions.php\"><span>" . _AM_CATADS_PERMISSIONSMANAGE . "</span></a></li>";
        echo "<li id='" . $tblColors[6] . "'><a href=\"" . XOOPS_URL . "/modules/catads/admin/import.php\"><span>" . _AM_CATADS_IMPORTMANAGE . "</span></a></li>";
        echo "</ul></div>";
        // pk bugfix - ?!
        // echo "<pre>&nbsp;</pre><pre>&nbsp;</pre><pre>&nbsp;</pre>";
        echo "<br />&nbsp;<br />" ;
}

//" . $xoopsModule->name() . "

function &catads_getconfig() {
    static $catadsConfig;
    if (!$catadsConfig) {
        global $xoopsModule;
            if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == 'catads') {
                global $xoopsModuleConfig;
                $catadsConfig =& $xoopsModuleConfig;
            }
            else {
                $catadsModule =& sf_getModuleInfo();
                $hModConfig = &xoops_gethandler('config');
                $catadsConfig = $hModConfig->getConfigsByCat(0, $catadsModule->getVar('mid'));
            }
    }
        return $catadsConfig;
}

?>