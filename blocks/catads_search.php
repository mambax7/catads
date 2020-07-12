<?php

// pk search block form built here

include("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/include/functions.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/cat.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/ads.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/formSelectRegions.php");
include_once(XOOPS_ROOT_PATH."/modules/catads/class/formSelectDepartements.php");


function b_catads_search()
{
       global $xoopsDB, $xoopsModule, $xoopsModuleConfig;

       $block = array();

       // keywords
       $block['words'] = '<input type="text" name="words" size="25" maxlength="100" />' ;

       // categories
       $xt0 = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
       ob_start();
       $xt0->makeMySelBox('topic_title','topic_title', 0, 1, 'topic_title');
       $block['category'] = ob_get_contents();
       ob_end_clean();

       // price from
       $block['price_start'] = '<input type="text" name="price_start" size="6" maxlength="10" />' ;

       // price to
       $block['price_end'] = '<input type="text" name="price_end" size="6" maxlength="10" />' ;

       // region
       $block['region'] = '<select name="region">';
       $sql = "SELECT region_numero, region_nom FROM ".$xoopsDB->prefix("catads_regions")." ORDER BY region_nom ASC";
       $result = $xoopsDB->query($sql);

       $block['region'] .= '<option value="0">----</option>';
       while ( $myrow = $xoopsDB->fetchArray($result) )
       {
               $block['region'] .= '<option value="'.$myrow['region_numero'].'">'.$myrow['region_nom'].'</option>';
       }
       $block['region'] .= '</select>';

       // departement
       $block['departement'] = '<select name="departement">';
       $sql = "SELECT departement_numero, departement_nom FROM ".$xoopsDB->prefix("catads_departements")." ORDER BY departement_nom ASC";
       $result = $xoopsDB->query($sql);
       $block['departement'] .= '<option value="0">----</option>';
       while ( $myrow = $xoopsDB->fetchArray($result) )
       {
               $block['departement'] .= '<option value="'.$myrow['departement_numero'].'">'.$myrow['departement_nom'].'</option>';
       }
       $block['departement'] .= '</select>';

       // town
       $block['town'] = '<input type="text" name="town" size="25" maxlength="100" />' ;

       // post code
       $block['post_code'] = '<input type="text" name="zipcod" size="10" maxlength="10" />' ;

       // submit
       $block['submit'] = '<input type="submit" class="formButton" name="submit" id="submit" value="'._SEND.'" />' ;


       return $block;
       
}

?>