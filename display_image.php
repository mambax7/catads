<?php
// $Id: display_image.php,v 1.1 2005/06/27 C. Felix AKA the Cat
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
xoops_header();

$img_id = (isset($_GET['img_id'])) ? $_GET['img_id'] : 1;
$ads_id = $_GET['ads_id'];
$array_id = $_GET['array_id'];
$n = explode('_',$array_id);
$nb_img = count($n)-1;

include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
$ads_handler =& xoops_getmodulehandler('ads');
$ads = & $ads_handler->get($ads_id);
$photo = $ads->getVar('photo'.$n[$img_id]);

echo "<CENTER><IMG SRC=\"".XOOPS_ROOT_PATH."/uploads/".$xoopsModule->dirname()."/images/annonces/original/".$photo."\" BORDER='0' alt='' /></CENTER><br />";

echo "<center><table><tr><td>";
if ($img_id > 1) {
        echo "<a href=\"display_image.php?array_id=".$array_id."&img_id=".($img_id-1)."&ads_id=".$ads_id."\" target=\"_self\"><<&nbsp;</a>";
}
echo "<a href=#  onClick='window.close()'>"._MD_CATADS_CLOSEF."</a>";
if ($img_id < ($nb_img)) {
        echo "<a href=\"display_image.php?array_id=".$array_id."&img_id=".($img_id+1)."&ads_id=".$ads_id."\" target=\"_self\">&nbsp;>></a>";
}
echo "</td></tr></table></center>";

xoops_footer();
?>