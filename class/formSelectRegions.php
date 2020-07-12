<?php

include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";

class formSelectRegions extends XoopsFormSelect
{

        function formSelectRegions($caption, $name, $value=null, $size=1, $nullopt=false)
        {
                $db =& Database::getInstance();
                $this->XoopsFormSelect($caption, $name, $value, $size);
                if ( $value != null )
                {
                        $sql = "SELECT region_numero, region_nom FROM ".$db->prefix("catads_regions")." WHERE region_numero=".$value."";

                        $result = $db->query($sql);
                        if($nullopt) $this->addOption('','-');
                        $myrow = $db->fetchArray($result);
                        $this->addOption($myrow['region_numero'],$myrow['region_nom']);

                        $sql = "SELECT region_numero, region_nom FROM ".$db->prefix("catads_regions")." WHERE region_numero != ".$value."";
                        $result = $db->query($sql);
                }
                else
                {
                        // pk set ORDER BY value here for select menu if required.
                        $sql = "SELECT region_numero, region_nom FROM ".$db->prefix("catads_regions")." ORDER BY region_numero ASC";
                        $this->addOption('', '----');
                        if($nullopt) $this->addOption('','-');
                }

                $result = $db->query($sql);
                while ( $myrow = $db->fetchArray($result) )
                {
                        $this->addOption($myrow['region_numero'],$myrow['region_nom']);
                }

        }
}

?>