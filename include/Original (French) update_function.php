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
	{	*/
	
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

	//Regions
	$result = $xoopsDB->query( "SELECT * FROM `".$xoopsDB->prefix("catads_regions")."` LIMIT 1" );
	if( ! $result ) 
	{
		$sql1 = "CREATE TABLE IF NOT EXISTS ".$xoopsDB->prefix("catads_regions")." (
					  `region_numero` varchar(3) NOT NULL,
					  `region_nom` varchar(255) NOT NULL,
					  PRIMARY KEY  (`region_numero`)
				) ENGINE=MyISAM;";
		sql_exec($sql1);		
				
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (1, 'Alsace')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (2, 'Aquitaine')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (3, 'Auvergne')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (4, 'Basse Normandie')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (5, 'Bourgogne')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (6, 'Bretagne')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (7, 'Centre')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (8, 'Champagne Ardenne')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (9, 'Corse')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (10, 'Franche Comte')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (11, 'Haute Normandie')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (12, 'Ile de France')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (13, 'Languedoc Roussillon')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (14, 'Limousin')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (15, 'Lorraine')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (16, 'Midi-Pyrenees')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (17, 'Nord Pas de Calais')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (18, 'P.A.C.A')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (19, 'Pays de la Loire')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (20, 'Picardie')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (21, 'Poitou Charente')");
		$result = $xoopsDB->queryF("INSERT INTO `".$xoopsDB->prefix("catads_regions")."` (`region_numero`, `region_nom`) VALUES (22, 'Rhone Alpes')");
	}
	
	//Departement
	$result = $xoopsDB->query( "SELECT * FROM ".$xoopsDB->prefix("catads_departements")." LIMIT 1" ) ;
	if( ! $result ) 
	{
		$sql2 = "CREATE TABLE IF NOT EXISTS ".$xoopsDB->prefix("catads_departements")." (
				      `departement_numero` varchar(3) NOT NULL,
					  `departement_numero_region` varchar(255) NOT NULL,
					  `departement_nom` varchar(255) NOT NULL,
					  PRIMARY KEY  (`departement_numero`),
					  KEY `FK_DEPARTEMENT_REGION` (`departement_numero_region`)
					) ENGINE=MyISAM;";
		sql_exec($sql2);
			
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('1', '22', 'Ain')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('2', '20', 'Aisne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('3', '3', 'Allier')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('4', '18', 'Alpes de haute provence')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('5', '18', 'Hautes alpes')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('6', '18', 'Alpes maritimes')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('7', '22', 'Ardeche')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('8', '8', 'Ardennes')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('9', '16', 'Ariege')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('10', '8', 'Aube')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('11', '13', 'Aude')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('12', '16', 'Aveyron')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('13', '18', 'Bouches du rhone')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('14', '4', 'Calvados')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('15', '3', 'Cantal')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('16', '21', 'Charente')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('17', '21', 'Charente maritime')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('18', '7', 'Cher')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('19', '14', 'Correze')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('21', '5', 'Cote d\'or')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('22', '6', 'Cotes d\'Armor')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('23', '14', 'Creuse')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('24', '2', 'Dordogne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('25', '10', 'Doubs')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('26', '22', 'Drome')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('27', '11', 'Eure')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('28', '7', 'Eure et Loir')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('29', '6', 'Finistere')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('30', '13', 'Gard')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('31', '16', 'Haute garonne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('32', '16', 'Gers')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('33', '2', 'Gironde')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('34', '13', 'Herault')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('35', '6', 'Ile et Vilaine')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('36', '7', 'Indre')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('37', '7', 'Indre et Loire')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('38', '22', 'Isere')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('39', '10', 'Jura')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('40', '2', 'Landes')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('41', '7', 'Loir et Cher')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('42', '22', 'Loire')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('43', '3', 'Haute loire')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('44', '19', 'Loire Atlantique')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('45', '7', 'Loiret')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('46', '16', 'Lot')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('47', '2', 'Lot et Garonne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('48', '13', 'Lozere')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('49', '19', 'Maine et Loire')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('50', '4', 'Manche')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('51', '8', 'Marne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('52', '8', 'Haute Marne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('53', '19', 'Mayenne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('54', '15', 'Meurthe et Moselle')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('55', '15', 'Meuse')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('56', '6', 'Morbihan')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('57', '15', 'Moselle')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('58', '5', 'Nievre')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('59', '17', 'Nord')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('60', '20', 'Oise')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('61', '4', 'Orne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('62', '17', 'Pas de Calais')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('63', '3', 'Puy de Dome')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('64', '2', 'Pyrenees Atlantiques')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('65', '16', 'Hautes Pyrenees')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('66', '13', 'Pyrenees Orientales')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('67', '1', 'Bas Rhin')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('68', '1', 'Haut Rhin')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('69', '22', 'Rhone')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('70', '10', 'Haute Saone')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('71', '5', 'Saone et Loire')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('72', '19', 'Sarthe')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('73', '22', 'Savoie')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('74', '22', 'Haute Savoie')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('75', '12', 'Paris')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('76', '11', 'Seine Maritime')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('77', '12', 'Seine et Marne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('78', '12', 'Yvelines')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('79', '21', 'Deux Sevres')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('80', '20', 'Somme')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('81', '16', 'Tarn')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('82', '16', 'Tarn et Garonne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('83', '18', 'Var')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('84', '18', 'Vaucluse')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('85', '19', 'Vendee')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('86', '21', 'Vienne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('87', '14', 'Haute Vienne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('88', '15', 'Vosge')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('89', '5', 'Yonne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('90', '10', 'Territoire de Belfort')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('91', '12', 'Essonne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('92', '12', 'Haut de seine')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('93', '12', 'Seine Saint Denis')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('94', '12', 'Val de Marne')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('95', '12', 'Val d\'Oise')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('2a', '9', 'Corse du Sud')");
	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("catads_departements")." (`departement_numero`, `departement_numero_region`, `departement_nom`) VALUES ('2b', '9', 'Haute Corse')");	
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
	
	$imgFile = XOOPS_ROOT_PATH."/modules/catads/image/blank.gif";
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