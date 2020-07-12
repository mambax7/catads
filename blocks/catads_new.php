<?php
// $Id: blocks/catads_news.php,v 1.21 2004/11/13 C. Felix AKA the Cat
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

include_once(XOOPS_ROOT_PATH."/modules/catads/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/ads.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/permissions.php");

// pk 30-04-10 updated to respect category permissions and suspended ads.

function b_catads_show($options) {


        global $xoopsModule, $xoopsModuleConfig, $xoopsDB, $xoopsUser;


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

        if ( $options[2] != 0 ) {
        $block['defil'] = true;
                $block['vitesse_defil'] = $options[3];
    } else {
        $block['defil'] = false;
    }


        $ads_hnd =& xoops_getmodulehandler('ads', 'catads');

        $permHandler = CatadsPermHandler::getHandler();

        $criteria = new CriteriaCompo();

        $topic_id = !isset($_REQUEST['topic_id'])? NULL : $_REQUEST['topic_id'];

                $topic = $permHandler->listAds($xoopsUser, 'catads_access', $topic_id);

                $mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
                $criteria2 = new CriteriaCompo();
                $allcat = $mytree->getAllChildId($topic_id);

                $i = 0;

                foreach($topic as $valeur)
                {
                        foreach($allcat as $valeur1)
                        {
                                        if ($valeur == $valeur1)
                                        {
                                                $show_topic_id[$i] = $valeur1;
                                                $i++;
                                        }
                        }
                }

                for($j=0; $j<$i; $j++)
                {
                         $criteria2->add(new Criteria('cat_id',$show_topic_id[$j]), 'OR');
                }

           $criteria->add($criteria2);

        $criteria->add(new Criteria('waiting','0'), 'AND');
        $criteria->add(new Criteria('suspend','0'), 'AND');
        $criteria->add(new Criteria('published', time(),'<'), 'AND');
        $criteria->add(new Criteria('expired', time(),'>'), 'AND');


        $criteria->setSort('published');

        $criteria->setOrder('DESC');

        $criteria->setLimit($options[0]);

        $nbads = $ads_hnd->getCount($criteria);

        $a_item = array();

        if ($nbads > 0) {
                $ads = $ads_hnd->getObjects($criteria);
                $ts =& MyTextSanitizer::getInstance();


                foreach( $ads as $oneads ){

                        $ads_id = $oneads->getVar('ads_id');


                        $a_item['id'] = $ads_id;
                        $a_item['type'] = $oneads->getVar('ads_type');
                        $a_item['title'] = $oneads->getVar('ads_title');
                        if (!XOOPS_USE_MULTIBYTES ) {
                                $length1 = strlen($oneads->getVar('ads_type'));
                                $length2 = strlen($oneads->getVar('ads_title'));
                                if ( $length1 + $length2 >= $options[4]) {
                                        $a_item['title'] = substr($a_item['title'],0, $options[4] - $length1)."...";
                                }
                        }
                        if ($oneads->getVar('price') > 0)
                                $a_item['price'] = $oneads->getVar('price').' '.$oneads->getVar('monnaie');
                        $a_item['date'] = ($oneads->getVar('published') > 0) ? formatTimestamp($oneads->getVar('published'),"s") : '';
                                $a_item['local'] = $oneads->getVar('codpost');
                                $a_item['local'] .= ' '.$oneads->getVar('town');

                        if ($oneads->getVar('thumb') != '')
                        {
                                $a_item['thumb'] = '<a href="'.XOOPS_URL.'/uploads/catads/images/annonces/original/'.$oneads->getVar('photo0').'" class="highslide" style="width: 250px;" onclick="return hs.expand(this)">
                        <img class="miniature" src="'.XOOPS_URL.'/uploads/catads/images/annonces/thumb/'.$oneads->getVar('thumb').'" alt="'.$oneads->getVar('ads_title').'" style="width: 60px;"/></a>';

                        } else {
                                $a_item['thumb'] = "<img src=\"".XOOPS_URL."/modules/".$module->getVar('dirname')."/images/no_dispo_mini.gif\" border=\"0\" alt=\"\" />";
                        }
                        $a_item['views'] = $oneads->getVar('view');

                        $block['items'][] = $a_item;
                        unset($a_item);
                        }

        } else {


            $block['noads'] = true;

        }

    return $block;
}


function b_catads_edit($options) {
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

        //
        $form .= '<br />'._MB_CATADS_DEFIL."&nbsp;<input type='radio' name='options[2]' value='1'";
    if ( $options[2] == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."<input type='radio' name='options[2]' value='0'";
    if ( $options[2] == 0 ) {
        $form .= " checked='checked'";
    }
        $form .= " />&nbsp;"._NO;
        //
        $form .= '<br />'._MB_CATADS_VITESSE_DEFIL."&nbsp;<input type='text' name='options[]' value='".$options[3]."' />";
        $form .= '<br />'._MB_CATADS_NBCHAR."&nbsp;<input type='text' name='options[]' value='".$options[4]."' />";

        return $form;
}

?>