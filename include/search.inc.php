<?php
// $Id: include/search.inc.php,v 1.0 2004/07/22 the Cat
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

global $xoopsModule;
function catads_search($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB;
	
	$sql = "SELECT ads_id, uid, ads_title, published FROM ".$xoopsDB->prefix("catads_ads")." WHERE published >0  AND expired >".time()."";
	if ( $userid != 0 ) {
		$sql .= " AND uid=".$userid." ";
	} 
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((ads_desc LIKE '%$queryarray[0]%' OR ads_title LIKE '%$queryarray[0]%' OR ads_tags LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(ads_desc LIKE '%$queryarray[$i]%' OR ads_title LIKE '%$queryarray[$i]%' OR ads_tags LIKE '%$queryarray[0]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY published DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
		$ret[$i]['image'] = "images/catads.gif";
		$ret[$i]['link'] = "adsitem.php?ads_id=".$myrow['ads_id']."";
		$ret[$i]['title'] = $myrow['ads_title'];
		$ret[$i]['time'] = $myrow['published'];
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}
?>