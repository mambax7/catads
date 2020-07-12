<?php

/**
* $Id: backend.php,v 1.1 2005/02/18 18:59:11 malanciault Exp $
* Module: SmartSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

//include_once("header.php");
include("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/option.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");

// pk 30-04-10 updated to respect category permissions and suspended ads.

include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/ads.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/permissions.php");



global   $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
$ads_handler =& xoops_getmodulehandler('ads');
 $myts = MyTextSanitizer::getInstance();

include_once XOOPS_ROOT_PATH.'/class/template.php';
//include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';
if (function_exists('mb_http_output')) {
        mb_http_output('pass');
}
$channel_category =  xoops_utf8_encode(htmlspecialchars($myts->displayTarea($xoopsModule->name(), ENT_QUOTES)));

// pk get config pref for ad-type

$show_ad_type = $xoopsModuleConfig['show_ad_type'] ;

// pk if a category is selected

if (isset($_GET['id']) ){


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

         // pk - bugfix - add cat ID and omit waiting, suspended, un-published and expired.
         $criteria->add(new Criteria('waiting','0'), 'AND');
         $criteria->add(new Criteria('suspend','0'), 'AND');
         $criteria->add(new Criteria('published', time(),'<'), 'AND');
         $criteria->add(new Criteria('expired', time(),'>'), 'AND');

         $criteria->add(new Criteria('a.cat_id', $_GET['id']));

         $channel_category .= " > " . xoops_utf8_encode(htmlspecialchars($myts->displayTarea(getTitleById($_GET['id']), ENT_QUOTES)));
}

// pk if no category is selected (ads in non-public categories are now hidden)

else {


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
}

header ('Content-Type:text/xml; charset=utf-8');
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(0);
if (!$tpl->is_cached('db:system_rss.html')) {
        $sarray = $ads_handler->getAllAds($criteria);
        if (is_array($sarray)) {
                $tpl->assign('channel_title', xoops_utf8_encode(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
                $tpl->assign('channel_link', XOOPS_URL.'/');
                $tpl->assign('channel_desc', xoops_utf8_encode(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
                $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));

                // pk these channels need removing from the template - DB system_rss.html
                $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
                $tpl->assign('channel_editor', $xoopsConfig['adminmail']);

                $tpl->assign('channel_category', $channel_category);
                $tpl->assign('channel_generator', 'CatAdd');
                $tpl->assign('channel_language', _LANGCODE);
                $tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
                $dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
                if (empty($dimention[0])) {
                        $width = 88;
                } else {
                        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
                }
                if (empty($dimention[1])) {
                        $height = 31;
                } else {
                        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
                }
                $tpl->assign('image_width', $width);
                $tpl->assign('image_height', $height);
                $count = $sarray;
                foreach ($sarray as $item) {

                        // pk show ad-type only if pref is set
                        if($show_ad_type == '1'){
                        $title = $item->getVar('ads_type')." : ".$item->getVar('ads_title');
                        } else {
                        $title = $item->getVar('ads_title');
                        }
                        $link = XOOPS_URL."/modules/catads/adsitem.php?ads_id=".$item->getVar('ads_id');

                        $tpl->append('items',
                                      array('title' => xoops_utf8_encode(htmlspecialchars($title, ENT_QUOTES)),
                                    'link' =>  $link,
                                    'guid' => $link,
                                    'pubdate' => formatTimestamp($item->getVar('published'), 'rss'),
                                    'description' => xoops_utf8_encode(htmlspecialchars($item->getVar('ads_desc'), ENT_QUOTES))));
                }
        }
}
unset($_GET);
$tpl->display('db:system_rss.html');