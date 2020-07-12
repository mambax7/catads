<?php
// $Id: install_funcs.php v 1.0 2004/10/16 C. Felix AKA the Cat
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// Version:  1.2                                                             //
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

/*if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

if( file_exists(XOOPS_ROOT_PATH."/modules/mpmanager/language/".$xoopsConfig['language']."/admin.php") ) {
        include(XOOPS_ROOT_PATH."/modules/mpmanager/language/".$xoopsConfig['language']."/admin.php");
} else {
        include(XOOPS_ROOT_PATH."/modules/mpmanager/language/english/admin.php");
}
*/
function catads_create_files_uploads()
{
        global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

        // Creation du dossier catads dans uploads
        $dir = XOOPS_ROOT_PATH."/uploads/catads";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);

        // Creation des sous-dossiers de catads
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces/thumb";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/categories";
        if(!is_dir($dir))
                mkdir($dir);
                //chmod ($dir, 0777);


        // Copy index.html files on uploads folders
        $indexFile = XOOPS_ROOT_PATH."/modules/catads/include/index.html";
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/index.html");
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/images/index.html");
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/index.html");
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/thumb/index.html");
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/index.html");
        copy($indexFile, XOOPS_ROOT_PATH."/uploads/catads/images/categories/index.html");

        $imgFile = XOOPS_ROOT_PATH."/modules/catads/images/blank.gif";
        copy($imgFile, XOOPS_ROOT_PATH."/uploads/catads/images/categories/blank.gif");

        //Copie des images des annonces dans le dossier uploads/images/annonces/original
        /*$dirup = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/ads/";
        $racine=opendir($dirup);

         while($dossier=@readdir($racine))
                {
                        if(!in_array($dossier, Array("..", ".")))
                        {
                                //echo $fileCopy."<br />";
                                $fileCopy = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/ads/".$dossier;
                                copy($fileCopy, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/".$dossier."");
                                unlink("".$fileCopy."");
                        }
                }
        @closedir($racine);
        $dir = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/ads";
        chmod ($dir, 0777);
        rmdir($dir);

        //Copie des images des annonces dans le dossier uploads/images/categories
        $dirup = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/cat/";
        $racine=opendir($dirup);

         while($dossier=@readdir($racine))
                {
                        if(!in_array($dossier, Array("..", ".")))
                        {
                                //echo $fileCopy."<br />";
                                $fileCopy = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/cat/".$dossier;
                                copy($fileCopy, XOOPS_ROOT_PATH."/uploads/catads/images/categories/".$dossier."");
                                unlink("".$fileCopy."");
                        }
                }
        @closedir($racine);
        $dir = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/cat";
        chmod ($dir, 0777);
        rmdir($dir);*/

        return true;
}

catads_create_files_uploads();


?>