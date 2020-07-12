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

include_once( XOOPS_ROOT_PATH . '/modules/catads/include/functions.php');

function sql_exec($sql)
{
        global $xoopsDB;

        $ret = $xoopsDB->queryF($sql);
        if ($ret != false ) { return $ret; }

        $error = $xoopsDB->error();
        echo "<font color='red'>$sql<br>$error</font><br>";

        return false;
}

function catads_update()
{
        global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->getByDirname('catads');

        $sql = "UPDATE ". $xoopsDB->prefix('modules')." SET weight = 1 WHERE mid = ".$module->getVar('mid');
        $result = $xoopsDB->queryF($sql);

        /*$versioninfo =& $module_handler->get($xoopsModule->getVar('mid'));

        if ( $versioninfo->getInfo('version') == "1.50" )
        {        */

        //Table catads_ads
        $result = $xoopsDB->query( "SELECT ads_tags FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD ads_tags VARCHAR(255) DEFAULT '0' NOT NULL AFTER ads_desc");
        }

        $result = $xoopsDB->query( "SELECT ads_video FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD ads_video VARCHAR(255) DEFAULT '0' NOT NULL AFTER ads_tags");
        }

        $result = $xoopsDB->query( "SELECT price FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` MODIFY price DECIMAL(7,2) NOT NULL DEFAULT '0'");
        }

        $result = $xoopsDB->query( "SELECT view FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` MODIFY view INT(3) NOT NULL DEFAULT '0'");
        }

        $result = $xoopsDB->query( "SELECT expired_mail_send FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD expired_mail_send VARCHAR(1) DEFAULT '0' NOT NULL AFTER expired");
        }

        $result = $xoopsDB->query( "SELECT pays FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD pays VARCHAR(10) DEFAULT 'FRANCE' NOT NULL AFTER phone");
        }

        $result = $xoopsDB->query( "SELECT region FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD region VARCHAR(5) DEFAULT '0' NOT NULL AFTER pays");
        }

        $result = $xoopsDB->query( "SELECT departement FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD departement VARCHAR(5) DEFAULT '0' NOT NULL AFTER region");
        }

        $result = $xoopsDB->query( "SELECT thumb FROM `".$xoopsDB->prefix("catads_ads")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_ads")."` ADD thumb VARCHAR(255) DEFAULT '' NOT NULL AFTER photo5");
        }

        //Table catads_cat
        $result = $xoopsDB->query( "SELECT topic_id FROM `".$xoopsDB->prefix("catads_cat")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_cat")."` CHANGE cat_id topic_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        $result = $xoopsDB->query( "SELECT topic_pid FROM `".$xoopsDB->prefix("catads_cat")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_cat")."` CHANGE pid topic_pid INT(5) UNSIGNED NOT NULL DEFAULT '0'");
        }

        $result = $xoopsDB->query( "SELECT topic_title FROM `".$xoopsDB->prefix("catads_cat")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_cat")."` CHANGE title topic_title VARCHAR(50) NOT NULL DEFAULT '0'");
        }

        $result = $xoopsDB->query( "SELECT topic_desc FROM `".$xoopsDB->prefix("catads_cat")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_cat")."` ADD topic_desc text NOT NULL AFTER topic_title");
        }


        $result = $xoopsDB->query( "SELECT display_cat FROM `".$xoopsDB->prefix("catads_cat")."` LIMIT 1" ) ;
        if( ! $result )
        {
                $result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("catads_cat")."` ADD display_cat TINYINT(1) NOT NULL AFTER img");
        }

        //Regions - pk get one record to check if table is populated. Need to DROP the original table to force this to update.
        $result = $xoopsDB->query( "SELECT * FROM `".$xoopsDB->prefix("catads_regions")."` LIMIT 1" );
        if( ! $result )
        {
                $sql1 = "CREATE TABLE IF NOT EXISTS ".$xoopsDB->prefix("catads_regions")." (
                                          `region_numero` smallint(3) NOT NULL,
                                          `region_nom` varchar(64),
                                           PRIMARY KEY  (`region_numero`)
                                );";
                sql_exec($sql1);

                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (1, 'Africa')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (2, 'Alaska')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (3, 'Arabian Peninsula')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (4, 'Australia')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (5, 'Canada')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (6, 'Carribean')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (7, 'Central America')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (8, 'Central Asia')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (9, 'China')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (10, 'Europe')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (11, 'Iceland')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (12, 'Indian Sub Continent')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (13, 'Japan')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (14, 'Korea')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (15, 'Mexico')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (16, 'Middle East')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (17, 'New Zealand')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (18, 'Scandinavia')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (19, 'South America')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (20, 'South East Asia')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (21, 'UK and Ireland')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (22, 'USA')");
                $result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (23, 'World')");
        }

        //Departement  - pk get one record to check if table exists. Need to DROP table to force this to update.
        $result = $xoopsDB->query( "SELECT * FROM ".$xoopsDB->prefix("catads_departements")." LIMIT 1" ) ;
        if( ! $result )
        {
                $sql2 = "CREATE TABLE IF NOT EXISTS ".$xoopsDB->prefix("catads_departements")." (
                                          `departement_numero` smallint(3) NOT NULL,
                                          `departement_numero_region` smallint(3) ZEROFILL NOT NULL,
                                          `departement_nom` varchar(64),
                                          PRIMARY KEY  (`departement_numero`),
                                          KEY `FK_DEPARTEMENT_REGION` (`departement_numero_region`)
                                        );";
                sql_exec($sql2);

// $result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('1', '22', 'Ain')");

$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('1', '004', 'Afghanistan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('2', '248', 'Aland Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('3', '008', 'Albania')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('4', '012', 'Algeria')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('5', '016', 'American Samoa')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('6', '020', 'Andorra')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('7', '024', 'Angola')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('8', '660', 'Anguilla')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('9', '028', 'Antigua and Barbuda')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('10', '032', 'Argentina')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('11', '051', 'Armenia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('12', '533', 'Aruba')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('13', '036', 'Australia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('14', '040', 'Austria')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('15', '031', 'Azerbaijan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('16', '044', 'Bahamas')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('17', '048', 'Bahrain')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('18', '050', 'Bangladesh')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('19', '052', 'Barbados')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('20', '000', 'Antarctica')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('21', '112', 'Belarus')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('22', '056', 'Belgium')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('23', '084', 'Belize')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('24', '204', 'Benin')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('25', '060', 'Bermuda')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('26', '064', 'Bhutan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('27', '068', 'Bolivia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('28', '070', 'Bosnia and Herzegovina')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('29', '072', 'Botswana')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('30', '076', 'Brazil')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('31', '092', 'British Virgin Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('32', '096', 'Brunei Darussalam')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('33', '100', 'Bulgaria')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('34', '854', 'Burkina Faso')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('35', '108', 'Burundi')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('36', '116', 'Cambodia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('37', '120', 'Cameroon')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('38', '124', 'Canada')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('39', '132', 'Cape Verde')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('40', '136', 'Cayman Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('41', '140', 'Central African Republic')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('42', '148', 'Chad')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('43', '152', 'Chile')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('44', '156', 'China')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('45', '344', 'Hong Kong - China')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('46', '446', 'Macao - China')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('47', '170', 'Colombia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('48', '174', 'Comoros')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('49', '178', 'Congo')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('50', '184', 'Cook Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('51', '188', 'Costa Rica')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('52', '384', 'Cote d ''Ivoire')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('53', '191', 'Croatia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('54', '192', 'Cuba')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('55', '196', 'Cyprus')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('56', '203', 'Czech Republic')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('57', '408', 'D. P. Republic of Korea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('58', '180', 'D. Republic of Congo')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('59', '208', 'Denmark')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('60', '262', 'Djibouti')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('61', '212', 'Dominica')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('62', '214', 'Dominican Republic')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('63', '218', 'Ecuador')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('64', '818', 'Egypt')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('65', '222', 'El Salvador')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('66', '226', 'Equatorial Guinea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('67', '232', 'Eritrea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('68', '233', 'Estonia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('69', '231', 'Ethiopia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('70', '234', 'Faeroe Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('71', '238', 'Falkland Islands - Malvinas')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('72', '242', 'Fiji')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('73', '246', 'Finland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('74', '250', 'France')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('75', '254', 'French Guiana')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('76', '258', 'French Polynesia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('77', '266', 'Gabon')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('78', '270', 'Gambia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('79', '268', 'Georgia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('80', '276', 'Germany')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('81', '288', 'Ghana')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('82', '292', 'Gibraltar')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('83', '300', 'Greece')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('84', '304', 'Greenland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('85', '308', 'Grenada')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('86', '312', 'Guadeloupe')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('87', '316', 'Guam')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('88', '320', 'Guatemala')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('89', '831', 'Guernsey')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('90', '324', 'Guinea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('91', '624', 'Guinea-Bissau')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('92', '328', 'Guyana')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('93', '332', 'Haiti')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('94', '336', 'Holy See')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('95', '340', 'Honduras')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('96', '348', 'Hungary')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('97', '352', 'Iceland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('98', '356', 'India')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('99', '360', 'Indonesia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('100', '364', 'Iran - Islamic Republic of')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('101', '368', 'Iraq')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('102', '372', 'Ireland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('103', '833', 'Isle of Man')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('104', '376', 'Israel')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('105', '380', 'Italy')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('106', '388', 'Jamaica')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('107', '392', 'Japan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('108', '832', 'Jersey')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('109', '400', 'Jordan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('110', '398', 'Kazakhstan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('111', '404', 'Kenya')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('112', '296', 'Kiribati')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('113', '414', 'Kuwait')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('114', '417', 'Kyrgyzstan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('115', '418', 'Lao - P. D. R.')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('116', '428', 'Latvia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('117', '422', 'Lebanon')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('118', '426', 'Lesotho')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('119', '430', 'Liberia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('120', '434', 'Libyan Arab Jamahiriya')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('121', '438', 'Liechtenstein')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('122', '440', 'Lithuania')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('123', '442', 'Luxembourg')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('124', '450', 'Madagascar')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('125', '454', 'Malawi')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('126', '458', 'Malaysia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('127', '462', 'Maldives')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('128', '466', 'Mali')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('129', '470', 'Malta')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('130', '584', 'Marshall Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('131', '474', 'Martinique')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('132', '478', 'Mauritania')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('133', '480', 'Mauritius')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('134', '175', 'Mayotte')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('135', '484', 'Mexico')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('136', '583', 'Micronesia Fed')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('137', '492', 'Monaco')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('138', '496', 'Mongolia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('139', '499', 'Montenegro')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('140', '500', 'Montserrat')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('141', '504', 'Morocco')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('142', '508', 'Mozambique')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('143', '104', 'Myanmar')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('144', '516', 'Namibia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('145', '520', 'Nauru')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('146', '524', 'Nepal')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('147', '528', 'Netherlands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('148', '530', 'Netherlands Antilles')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('149', '540', 'New Caledonia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('150', '554', 'New Zealand')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('151', '558', 'Nicaragua')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('152', '562', 'Niger')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('153', '566', 'Nigeria')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('154', '570', 'Niue')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('155', '574', 'Norfolk Island')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('156', '580', 'Northern Mariana Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('157', '578', 'Norway')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('158', '275', 'Palestinian O. T.')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('159', '512', 'Oman')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('160', '586', 'Pakistan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('161', '585', 'Palau')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('162', '591', 'Panama')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('163', '598', 'Papua New Guinea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('164', '600', 'Paraguay')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('165', '604', 'Peru')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('166', '608', 'Philippines')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('167', '612', 'Pitcairn')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('168', '616', 'Poland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('169', '620', 'Portugal')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('170', '630', 'Puerto Rico')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('171', '634', 'Qatar')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('172', '410', 'Republic of Korea')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('173', '498', 'Republic of Moldova')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('174', '638', 'Reunion')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('175', '642', 'Romania')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('176', '643', 'Russian Federation')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('177', '646', 'Rwanda')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('178', '652', 'Saint-Barthelemy')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('179', '654', 'Saint-Helena')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('180', '659', 'Saint Kitts and Nevis')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('181', '662', 'Saint Lucia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('182', '663', 'Saint-Martin - French')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('183', '666', 'Saint Pierre and Miquelon')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('184', '670', 'Saint Vincent and Grenadines')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('185', '882', 'Samoa')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('186', '674', 'San Marino')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('187', '678', 'Sao Tome and Principe')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('188', '682', 'Saudi Arabia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('189', '686', 'Senegal')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('190', '688', 'Serbia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('191', '690', 'Seychelles')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('192', '694', 'Sierra Leone')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('193', '702', 'Singapore')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('194', '703', 'Slovakia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('195', '705', 'Slovenia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('196', '090', 'Solomon Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('197', '706', 'Somalia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('198', '710', 'South Africa')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('199', '724', 'Spain')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('200', '144', 'Sri Lanka')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('201', '736', 'Sudan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('202', '740', 'Suriname')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('203', '744', 'Svalbard and Jan Mayen Isles')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('204', '748', 'Swaziland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('205', '752', 'Sweden')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('206', '756', 'Switzerland')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('207', '760', 'Syrian Arab Republic')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('208', '762', 'Tajikistan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('209', '764', 'Thailand')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('210', '807', 'Macedonia - F. Y. R')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('211', '626', 'Timor-Leste')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('212', '768', 'Togo')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('213', '772', 'Tokelau')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('214', '776', 'Tonga')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('215', '780', 'Trinidad and Tobago')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('216', '788', 'Tunisia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('217', '792', 'Turkey')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('218', '795', 'Turkmenistan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('219', '796', 'Turks and Caicos Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('220', '798', 'Tuvalu')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('221', '800', 'Uganda')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('222', '804', 'Ukraine')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('223', '784', 'United Arab Emirates')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('224', '826', 'United Kingdom')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('225', '834', 'United Rep. Tanzania')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('226', '840', 'USA')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('227', '850', 'US Virgin Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('228', '858', 'Uruguay')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('229', '860', 'Uzbekistan')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('230', '548', 'Vanuatu')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('231', '862', 'Venezuela B. Rep')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('232', '704', 'Vietnam')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('233', '876', 'Wallis and Futuna Islands')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('234', '732', 'Western Sahara')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('235', '887', 'Yemen')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('236', '894', 'Zambia')");
$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('237', '716', 'Zimbabwe')");

        }
}

function catads_create_files_uploads()
{
        global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

        // Creation du dossier catads dans uploads
        $dir = XOOPS_ROOT_PATH."/uploads/catads";
        if(!is_dir($dir))
                mkdir($dir, 0777);
                //chmod ($dir, 0777);

        // Creation des sous-dossiers de catads
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images";
        if(!is_dir($dir))
                mkdir($dir, 0777);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces";
        if(!is_dir($dir))
                mkdir($dir, 0777);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces/thumb";
        if(!is_dir($dir))
                mkdir($dir, 0777);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original";
        if(!is_dir($dir))
                mkdir($dir, 0777);
                //chmod ($dir, 0777);
        $dir = XOOPS_ROOT_PATH."/uploads/catads/images/categories";
        if(!is_dir($dir))
                mkdir($dir, 0777);
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

        //Changer les droits du dossier uploads/images/annonces/original
        $dirup = XOOPS_ROOT_PATH . "/modules/catads/images";
        chmod ($dirup, 0777);

        $dirup = XOOPS_ROOT_PATH . "/modules/catads/images/ads";
        chmod ($dirup, 0777);

        $dirup = XOOPS_ROOT_PATH . "/modules/catads/images/cat";
        chmod ($dirup, 0777);

        $dirup = XOOPS_ROOT_PATH . "/modules/catads/images/cat/";

        $racine=opendir($dirup);

         while($dossier=@readdir($racine))
                {
                        if(!in_array($dossier, Array("..", ".")))
                        {
                                $fileCopy = XOOPS_ROOT_PATH . "/modules/catads/images/cat/".$dossier;
                                copy($fileCopy, XOOPS_ROOT_PATH."/uploads/catads/images/categories/".$dossier."");
                                unlink("".$fileCopy."");
                        }
                }
        @closedir($racine);
        //$dir = XOOPS_ROOT_PATH . "/modules/catads/images/cat";
        //chmod ($dir, 0777);
        //rmdir($dir);
        /*$dir = XOOPS_ROOT_PATH . "/modules/catads/images/ads";
        rmdir($dir);*/

        return true;
}

catads_update();
catads_create_files_uploads();

?>