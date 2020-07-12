<?php
// $Id: perm.php,v 1.1 2005/08/24 07:01:58 zoullou Exp $
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

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

class CatadsPermHandler {

	function &getHandler()
	{
		static $permHandler;
		if(!isset($permHandler)) {
			$permHandler = new CatadsPermHandler();
		}
		return $permHandler;
	}

	function _getUserGroup(&$user) {
		if(is_a($user,'XoopsUser')) {
			return $user->getGroups();
		} else {
			return XOOPS_GROUP_ANONYMOUS;
		}
	}

	function getAuthorizedPublicCat(&$user, $perm) {
		static $authorizedCat;
		$userId = ($user) ? $user->getVar('uid') : 0;
		if(!isset($authorizedCat[$perm][$userId])) {
			$groupPermHandler =& xoops_gethandler('groupperm');
			$moduleHandler =& xoops_gethandler('module');
			$module = $moduleHandler->getByDirname('catads');
			$authorizedCat[$perm][$userId] = $groupPermHandler->getItemIds($perm, $this->_getUserGroup($user), $module->getVar("mid"));
		}
		return $authorizedCat[$perm][$userId];
	}
	
	function isAllowed(&$user, $perm, $catId) {
		$autorizedCat = $this->getAuthorizedPublicCat($user, $perm);
		return in_array($catId, $autorizedCat);
	}
	
//Affichage liste en fonction des droits de l'utilisateur
	function listAds(&$user, $perm) {
		$autorizedCat = $this->getAuthorizedPublicCat($user, $perm);
		return $autorizedCat;
	}

}

?>