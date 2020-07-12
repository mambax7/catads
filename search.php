<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2008/12/22 by Kraven30
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

include("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectRegions.php";
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formSelectDepartements.php";
include_once(XOOPS_ROOT_PATH."/class/pagenav.php");
//$xoopsOption['template_main'] = 'catads_adslist.html';
include(XOOPS_ROOT_PATH."/header.php");

global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
//$myts =& MyTextSanitizer::getInstance();


                        // Formulaire de recherche

                        // pk Advanced Search Form (NOT block) built here.

                        if (AdsCategory::countCat() > 0)
                        {


                                $xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');

                                //$search_form = new XoopsThemeForm(_MD_CATADS_SEARCH, "search", xoops_getenv('PHP_SELF'));

                                $search_form = new XoopsThemeForm(_MD_CATADS_SEARCH, "advanced_search", "adslist.php?search=1");

                                // keywords

                                // pk reduce field length - make keywords required (true)
                                $search_form->addElement(new XoopsFormText(_MD_CATADS_SEARCH_WORDS, "words", 30, 100),true);

                                // Categories

                                /*ob_start();
                                $xt->makeMySelBox('topic_title','topic_title',0,0);
                                $search_form->addElement(new XoopsFormLabel(_MD_CATADS_SEARCH_CATEGORY, "categorie"));
                                ob_end_clean();*/
                                /*$sql = "SELECT topic_id, topic_title FROM ".$xoopsDB->prefix("catads_cat")." ORDER BY topic_title";
                                $result = $xoopsDB->query($sql);
                                $select_cat = new XoopsFormSelect(_MD_CATADS_SEARCH_CATEGORY, "categorie");
                                $select_cat->addOption('', _MD_CATADS_OTHER);
                                while ( $myrow = $xoopsDB->fetchArray($result) )
                                {
                                        if
                                        $select_cat->addOption($myrow['topic_id'],$myrow['topic_title']);
                                }
                                $search_form->addElement($select_cat, false);*/


                                // pk category list box. Second value sets 'order by' request.
                                // Can add secondary sort from other columns in same statement, e.g. 'topic_title ASC, another_column DESC'.
                                // However... I don't know how to pass two values from this, so I use the old-fashioned method:


                                // pk re-instated selbox and changed name - this now passes topic_id :-)

                                 ob_start();
                                 $xt->makeMySelBox('topic_title','topic_title ASC', 0, '-', 'topic_title');
                                 $search_form->addElement(new XoopsFormLabel(_MD_CATADS_SEARCH_CATEGORY, ob_get_contents()));
                                 ob_end_clean();


                                // pk cats - rem this version and try to get sub-cats working again
                               // $query1 = "SELECT topic_id, topic_title FROM ".$xoopsDB->prefix("catads_cat")." ORDER BY topic_title";
                               // $result1 = $xoopsDB->query($query1);
                               // $select1 = new XoopsFormSelect(_MD_CATADS_SEARCH_CATEGORY, "topic_title", null, 1, false);
                               // $select1->addOption(0, '- - - - - - -  ');
                               // while($myrow = $xoopsDB->fetchArray($result1))
                               // {
                               //         $select1->addOption($myrow["topic_id"], $myrow["topic_title"]);
                               // }
                               // $search_form->addElement($select1, false);

                                // end cats

                                /*$xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
                                $jump = XOOPS_URL."/modules/catads/submit1.php?topic_id=";
                                ob_start();
                                $xt->makeMySelBox('topic_title','topic_title',0,1, 'topic_pid', "location=\"".$jump."\"+this.options[this.selectedIndex].value");
                                $block['selectbox'] = ob_get_contents();
                                ob_end_clean();*/

                                // Price

                                $price_tray = new XoopsFormElementTray(_MD_CATADS_SEARCH_PRICE ,'');
                                        $price_tray->addElement(new XoopsFormText('', "price_start", 10, 10), false);
                                        // $price_tray->addElement(new XoopsFormLabel('',_MD_CATADS_EURO));
                                        $price_tray->addElement(new XoopsFormText(_MD_CATADS_SEARCH_PRICE_A, "price_end", 10, 10), false);
                                        // $price_tray->addElement(new XoopsFormLabel('',_MD_CATADS_EURO));
                                $search_form->addElement($price_tray);

                                // Region and departements

                                        // pk to change ORDER BY value for menus edit 'formSelectRegions' and 'formSelectDepartements'
                                        // in the 'class' folder.

                                        //Regions

                                        if ($xoopsModuleConfig['region_req'] > 0)
                                        {
                                                $search_form->addElement(new formSelectRegions(_MD_CATADS_SEARCH_REGIONS, "region"), false);
                                        }

                                        //Departements

                                        if ($xoopsModuleConfig['departement_req'] > 0)
                                        {
                                                $search_form->addElement(new formSelectDepartements(_MD_CATADS_SEARCH_DEPARTEMENTS, "departement"), false);
                                        }

                                // Town

                                $search_form->addElement(new XoopsFormText(_MD_CATADS_SEARCH_CITY, "town", 30, 100), false);

                                // Code Postal

                                if ($xoopsModuleConfig['zipcode_req'] > 0)
                                {
                                        $search_form->addElement(new XoopsFormText(_MD_CATADS_SEARCH_ZIPCOD, "zipcod", 10, 10), false);
                                }
                                $search_form->addElement(new XoopsFormHidden("op", "traitement_search"), true);

                                $search_form->addElement(new XoopsFormButton("", "submit", _SEARCH, "submit"), true);

                                // pk validate form trigger - not required

                                // $search_form->setExtra('onsubmit="javascript:return xoopsFormValidate_search_block();"');

                                $search_form->display();
                        }

include(XOOPS_ROOT_PATH."/footer.php");
?>