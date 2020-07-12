<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2008/11/15 by Kraven30
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

xoops_cp_header();
catads_admin_menu(4, _AM_CATADS_PURGEMANAGE);
echo "<br />" ;

//Action dans switch
        if (isset($_GET['op']))
                $op = $_GET['op'];
        elseif (isset($_POST['op']))
                $op = $_POST['op'];
        else
                $op = 'show_purge';


        switch ($op)
        {
                case "purge_ads_all_user":
                        purge_ads_all_user();
                break;

                case "purge_ads_expired":
                        purge_ads_expired();
                break;

                case "purge_ads_user":
                        purge_ads_user();
                break;

                case "show_purge":
                default:

                        show_purge();

                break;
        }

xoops_cp_footer();
?>
