<?php
// $Id: blocks/catads_myads.php,v 1.1 2005/07/11 C. Felix AKA the Cat
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

function b_catads_myads($options) {
global $xoopsModule, $xoopsModuleConfig, $xoopsUser;

         $show_ad_type = $xoopsModuleConfig['show_ad_type'] ;

        if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'catads') {
                $module_handler =& xoops_gethandler('module');
                $module =& $module_handler->getByDirname('catads');
                $config_handler =& xoops_gethandler('config');
                $config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
        } else {
                $module =& $xoopsModule;
                $config =& $xoopsModuleConfig;
        }

        $block = array();
        $block['title'] = _MB_CATADS_TITLE;


        // pk get module pref and add to block array. NB use config VAR
        $show_ad_type = $config['show_ad_type'] ;
        $block['show_ad_type'] = $show_ad_type ;


    if ( $options[1] != 0 ) {
        $block['full_view'] = true;
    } else {
        $block['full_view'] = false;
    }

        if ($xoopsUser) {
                $ads_hnd =& xoops_getmodulehandler('ads', 'catads');
                $criteria = new Criteria('uid', $xoopsUser->getVar('uid'));
                $criteria->setSort('published');
                $criteria->setOrder('DESC');
                $nbads = $ads_hnd->getCount($criteria);
                $item = array();
        } else {
                $nbads = 0;
        }

        if ($nbads > 0) {
                $ads = $ads_hnd->getObjects($criteria);
                $ts =& MyTextSanitizer::getInstance();
                foreach( $ads as $oneads ){
                        $ads_id = $oneads->getVar('ads_id');
                        $item['id'] = $ads_id;
                        $item['type'] = $oneads->getVar('ads_type');
                        $item['title'] = $oneads->getVar('ads_title');
                        if ($oneads->getVar('price') > 0)
                                $item['price'] = $oneads->getVar('price').' '.$oneads->getVar('monnaie');
                        $item['date'] = ($oneads->getVar('published') > 0) ? formatTimestamp($oneads->getVar('published'),"s") : '';
                                $item['local'] = $oneads->getVar('codpost');
                                $item['local'] .= ' '.$oneads->getVar('town');
                        $i = 0;
                        $strid ='';
                        while ($i < 6){
                                if ($oneads->getVar('photo'.$i)) {
                                        $strid .= '_'.$i;
                                }
                                $i++;
                        }
                        if ($strid != ''){
                                $width         = $config['photo_maxwidth'] + 40;
                                $height        = $config['photo_maxheight'] + 80;
                                $item['photo'] = "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/modules/".$module->getVar('dirname')."/display_image.php?array_id=".$strid."&ads_id=".$ads_id."','Photo',".$width.",".$height.");\"><img src=\"".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/icon/photo.gif\" border=0 width=15 height=11 ></a>";
                        }

                        $item['views'] = $oneads->getVar('view');

                        if ($oneads->getVar('published') == 0){
                                $item['status'] = "<img src='".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/ic15_question.gif'>";
                        }elseif ($oneads->getVar('expired') < time()){
                                $item['status'] = "<img src='".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/expiree.gif'>";
                        }elseif ($oneads->getVar('suspend')){
                                $item['status'] = "<img src='".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/ic15_stop.gif'>";
                        }else {
                                $item['status'] = "<img src='".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/en_ligne.gif'>";
                        }

                        // pk pass show ad type pref to template
                        $item['show_ad_type'] = $show_ad_type ;


                        $block['items'][] = $item;
                        unset($item);
                }

        } else {
            $block['noads'] = true;
        }
    return $block;
}

function b_catads_myads_edit($options) {
        $form = _MB_CATADS_NBADS."&nbsp;<input type='text' name='options[]' value='".$options[0]."' />";

    $form .= '<br />'._MB_CATADS_FULL."&nbsp;<input type='radio' name='options[1]' value='1'";
    if ( $options[1] == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."<input type='radio' name='options[1]' value='0'";
    if ( $options[1] == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO;
        $form .= '<br />'._MB_CATADS_NBCHAR."&nbsp;<input type='text' name='options[]' value='".$options[2]."' />";

        return $form;
}

?>