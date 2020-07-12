<?php

// pk search block form built here


function b_catads_search()
{
        global $xoopsDB;

        $block = array();

        $block['form'] = '<table border="0">';

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_WORDS.'</td><td align="left"><input type="text" name="words" size="25" maxlength="255" /></td></tr>';

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_CAT.'</td><td align="left"><select name="topic_title">';

        $sql = "SELECT topic_id, topic_title FROM ".$xoopsDB->prefix("catads_cat")." ORDER BY topic_title";
        $result = $xoopsDB->query($sql);

        $block['form'] .= '<option value="0">- - - - - - - -</option>';
        while ( $myrow = $xoopsDB->fetchArray($result) )
               {
                $block['form'] .= '<option value="'.$myrow['topic_id'].'">'.$myrow['topic_title'].'</option>';
               }
        $block['form'] .= '</select></td></tr>';

                $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_PRICE.'</td><td align="left"><input type="text" name="price_start" size="6" maxlength="255" />'._MB_CATADS_SEARCH_A.'<input type="text" name="price_end" size="6" maxlength="255" /></td></tr>';

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_REGION.'</td><td align="left"><select name="region">';

        $sql = "SELECT region_numero, region_nom FROM ".$xoopsDB->prefix("catads_regions")." ORDER BY region_nom ASC";
        $result = $xoopsDB->query($sql);

        $block['form'] .= '<option value="0">- - - - - - - -</option>';
        while ( $myrow = $xoopsDB->fetchArray($result) )
        {
                $block['form'] .= '<option value="'.$myrow['region_numero'].'">'.$myrow['region_nom'].'</option>';
        }
        $block['form'] .= '</select></td></tr>';

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_DEPARTEMENT.'</td><td align="left"><select name="departement">';

        $sql = "SELECT departement_numero, departement_nom FROM ".$xoopsDB->prefix("catads_departements")." ORDER BY departement_nom ASC";
        $result = $xoopsDB->query($sql);
        $block['form'] .= '<option value="0">- - - - - - - -</option>';
        while ( $myrow = $xoopsDB->fetchArray($result) )
        {
                // $block['form'] .= '<option value="'.$myrow['departement_numero'].'">'.$myrow['departement_nom'].'</option>';
                $block['form'] .= '<option value="'.$myrow['departement_numero'].'">'.$myrow['departement_nom'].'</option>';  
        }
        $block['form'] .= '</select></td></tr>';

        $sql = "SELECT price, town, codpost FROM ".$xoopsDB->prefix("catads_ads")."";
        $result = $xoopsDB->query($sql);

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_TOWN.'</td><td align="left"><input type="text" name="town" size="25" maxlength="255" /></td></tr>';

        $block['form'] .= '<tr><td align="left">'._MB_CATADS_SEARCH_CODEPOST.'</td><td align="left"><input type="text" name="zipcod" size="10" maxlength="255" /></td></tr>';

        $block['form'] .= '<tr><td align="left"></td><td align="right"><input type="submit" class="formButton" name="submit" id="submit" value="'._SEND.'" /></td></tr>';

        $block['form'] .= '</table>';
        $block['form'] .= '<input type="hidden" name="op" id="op" value="search" />';

        return $block;
}
?>