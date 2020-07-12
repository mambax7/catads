<?php

include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";

class formSelectDepartements extends XoopsFormSelect
{

        function formSelectDepartements($caption, $name, $value=null, $size=1, $nullopt=false)
        {
                $db =& Database::getInstance();
                $this->XoopsFormSelect($caption, $name, $value, $size);
                if ( $value != null )
                {
                        $sql = "SELECT departement_numero, departement_nom FROM ".$db->prefix("catads_departements")." WHERE departement_numero=".$value."";

                        $result = $db->query($sql);
                        if($nullopt) $this->addOption('','-');
                        $myrow = $db->fetchArray($result);
                        $this->addOption($myrow['departement_numero'],$myrow['departement_nom']);

                        $sql = "SELECT departement_numero, departement_nom FROM ".$db->prefix("catads_departements")." WHERE departement_numero != ".$value."";
                        $result = $db->query($sql);
                }
                else
                {
                        // pk set ORDER BY value here for select menu if required.
                        $sql = "SELECT departement_numero, departement_nom FROM ".$db->prefix("catads_departements")." ORDER BY departement_nom ASC";
                        $this->addOption('', '----');
                        if($nullopt) $this->addOption('','-');
                }

                $result = $db->query($sql);
                while ( $myrow = $db->fetchArray($result) )
                {
                        $this->addOption($myrow['departement_numero'],$myrow['departement_nom']);
                }

        }
}

?>