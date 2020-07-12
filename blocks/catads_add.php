<?php
// $Id: blocks/catads_news.php,v 1.0 2004/04/20 C. Felix AKA the Cat
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// Version:  1.0                                                             //
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
//include_once XOOPS_ROOT_PATH.'/modules/catads/include/functions.php';
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';

function b_catads_add() {
global $xoopsDB;
	
	
	$xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
	$jump = XOOPS_URL."/modules/catads/submit1.php?topic_id=";
	ob_start();
	$xt->makeMySelBox('topic_title','topic_title',0,1, 'topic_pid', "location=\"".$jump."\"+this.options[this.selectedIndex].value");
	$block['selectbox'] = ob_get_contents();
	ob_end_clean();

    return $block;
}
		
?>