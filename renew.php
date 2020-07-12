<?php
// $Id: adslist.php,v 1.1 2004/07/17 C. Felix AKA the Cat
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

include_once("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");


global $xoopsModuleConfig;

$ads_id =  isset($_POST['ads_id']) ? intval($_POST['ads_id']) : intval($_GET['ads_id']);
$uid =  isset($_POST['uid']) ? intval($_POST['uid']) : intval($_GET['uid']);
$key =  isset($_POST['key']) ? intval($_POST['key']) : intval($_GET['key']);
$expired =  isset($_POST['expired']) ? intval($_POST['expired']) : intval($_GET['expired']);



        $ads_handler = & xoops_getmodulehandler('ads');
        $ads =& $ads_handler->get($ads_id);

        if (!is_object($ads))
        {
                redirect_header('index.php',3,_MD_CATADS_NO_EXIST);
                exit();
        }


        // pk bugfix - get countpub value (if allowed renewals pref was '1' value should be '1')
        // bounce renewal request if allowed renewals reached
        $countpub = $ads->getVar('countpub') ;
        if($countpub == '0') {

                redirect_header('index.php',3,_MD_CATADS_RENEWALS_EXCEEDED);
                exit();

        }

        //Verification de l'uid et de la date de creation de l'annonce
        if ( ($ads->getVar('uid') == $uid) && ($ads->getVar('created') == $key) )
        {
                        $expired_date = time() + ($xoopsModuleConfig['renew_nb_days'] * 86400);
                        // if this is the last renewal allowed, cancel renewal notification
                        if($countpub == '1') {
                        $ads->setVar('expired_mail_send', '0');
                        }
                        $ads->setVar('published', time());
                        $ads->setVar('expired', $expired_date);
                        // $ads->setVar('expired_mail_send', $expired);

                        // pk bugfix decrement allowed renewals count
                        $ads->setVar('countpub', $countpub -1);

                        $update_ads_ok = $ads_handler->insert($ads);
//Mise à reussi ou pas
                if ($update_ads_ok)
                {
                        $messagesent = sprintf(_MD_CATADS_PUBAGAIN_OK, $xoopsModuleConfig['renew_nb_days']);
                }
                else
                {
                        $messagesent = _MD_CATADS_UPDATE_ERROR;
                }
        }

        redirect_header("adsitem.php?ads_id=".$ads_id, 1, $messagesent);
        exit();
?>