<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2008/11/19 by Kraven30
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

include_once( "admin_header.php" );
include_once '../include/functions.php';
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/admin/functions.php";
//Class
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");

xoops_cp_header();
catads_admin_menu(1, _AM_CATADS_ADSMANAGE);

echo "<br />" ;

//Action dans switch
        if (isset($_GET['op']))
                $op = $_GET['op'];
        elseif (isset($_POST['op']))
                $op = $_POST['op'];
        else
                $op = 'show_ads';


//Affichage des l'administration des annonces
        switch ($op)
        {
                case "approve_ads":
                        approve_ads();
                break;

                case "wait_ads":
                        wait_ads();
                break;

                case "renew_ads":
                        renew_ads();
                break;

                case "delete_ads":
                        delete_ads();
                break;

                case "show_ads":
                default:
                                show_ads();
                break;
        }

xoops_cp_footer();
?>