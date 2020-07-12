#
# Structure de la table `catads_ads`
#

CREATE TABLE `catads_ads` (
  `ads_id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL default '0',
  `ads_title` varchar(100) NOT NULL default '',
  `ads_type` varchar(40) NOT NULL default '',
  `ads_desc` text NOT NULL,
  `ads_tags` varchar(255) NOT NULL,
  `ads_video` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL default '0.00',
  `monnaie` varchar(20) NOT NULL default '',
  `price_option` varchar(40) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `uid` int(6) NOT NULL default '0',
  `phone` varchar(20) NOT NULL default '',
  `pays` varchar(50) NOT NULL default 'FRANCE',
  `region` varchar(5) NOT NULL default '0',
  `departement` varchar(5) NOT NULL default '0',
  `town` varchar(200) NOT NULL default '0',
  `codpost` varchar(25) NOT NULL default '0',
  `created` int(10) NOT NULL default '0',
  `published` int(10) NOT NULL default '0',
  `expired` int(10) NOT NULL default '0',
  `expired_mail_send` int(1) NOT NULL default '0',
  `view` int(3) NOT NULL default '0',
  `notify_pub` tinyint(1) NOT NULL default '0',
  `poster_ip` varchar(20) NOT NULL default '',
  `contact_mode` tinyint(1) NOT NULL default '0',
  `countpub` tinyint(1) NOT NULL default '0',
  `suspend` tinyint(1) NOT NULL default '0',
  `waiting` tinyint(1) NOT NULL default '0',
  `photo0` varchar(255) NOT NULL default '',
  `photo1` varchar(255) NOT NULL default '',
  `photo2` varchar(255) NOT NULL default '',
  `photo3` varchar(255) NOT NULL default '',
  `photo4` varchar(255) NOT NULL default '',
  `photo5` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ads_id`)
) TYPE=MyISAM  ;



# --------------------------------------------------------

#
# Structure de la table `catads_cat`
#

CREATE TABLE `catads_cat` (
  `topic_id` int(11) unsigned NOT NULL auto_increment,
  `topic_pid` int(5) unsigned NOT NULL default '0',
  `topic_title` varchar(50) NOT NULL default '',
  `topic_desc` varchar(255) NOT NULL default '',  
  `img` varchar(150) NOT NULL default '',
  `display_cat` tinyint(1) NOT NULL,
  `weight` int(5) NOT NULL default '0',
  `display_price` int(5) NOT NULL default '0',
  `nb_photo` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) TYPE=MyISAM  ;




# --------------------------------------------------------

#
# Structure de la table `catads_options`
#

CREATE TABLE `catads_options` (
  `option_id` tinyint(4) NOT NULL auto_increment,
  `option_type` tinyint(4) NOT NULL default '0',
  `option_desc` varchar(20) NOT NULL default '',
  `option_order` tinyint(5) NOT NULL default '0',
  PRIMARY KEY  (`option_id`)
) TYPE=MyISAM  ;

INSERT INTO `catads_options` (`option_id`, `option_type`, `option_desc`, `option_order`) VALUES 
(1, 1, 'Euros', 0),
(2, 2, 'Minimum', 0),
(3, 2, 'Maximum', 0),
(4, 2, '', 0),
(5, 2, 'Environ', 0),
(6, 2, 'Ferme', 0),
(7, 3, 'Loue', 0),
(8, 3, 'Vend', 0),
(9, 3, 'Acheter', 0),
(10, 3, 'Echange', 0),
(11, 3, 'Recherche', 0),
(12, 4, '30', 0);

# --------------------------------------------------------

#
# Structure de la table `catads_regions`
#

CREATE TABLE `catads_regions` (
  `region_numero` smallint(3) NOT NULL default '0',
  `region_nom` char(32) default NULL,
  PRIMARY KEY  (`region_numero`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `catads_regions`
-- 

INSERT INTO `catads_regions` VALUES ('1', 'Alsace');
INSERT INTO `catads_regions` VALUES ('2', 'Aquitaine');
INSERT INTO `catads_regions` VALUES ('3', 'Auvergne');
INSERT INTO `catads_regions` VALUES ('4', 'Basse Normandie');
INSERT INTO `catads_regions` VALUES ('5', 'Bourgogne');
INSERT INTO `catads_regions` VALUES ('6', 'Bretagne');
INSERT INTO `catads_regions` VALUES ('7', 'Centre');
INSERT INTO `catads_regions` VALUES ('8', 'Champagne Ardenne');
INSERT INTO `catads_regions` VALUES ('9', 'Corse');
INSERT INTO `catads_regions` VALUES ('10', 'Franche Comte');
INSERT INTO `catads_regions` VALUES ('11', 'Haute Normandie');
INSERT INTO `catads_regions` VALUES ('12', 'Ile de France');
INSERT INTO `catads_regions` VALUES ('13', 'Languedoc Roussillon');
INSERT INTO `catads_regions` VALUES ('14', 'Limousin');
INSERT INTO `catads_regions` VALUES ('15', 'Lorraine');
INSERT INTO `catads_regions` VALUES ('16', 'Midi-Pyrenees');
INSERT INTO `catads_regions` VALUES ('17', 'Nord Pas de Calais');
INSERT INTO `catads_regions` VALUES ('18', 'P.A.C.A');
INSERT INTO `catads_regions` VALUES ('19', 'Pays de la Loire');
INSERT INTO `catads_regions` VALUES ('20', 'Picardie');
INSERT INTO `catads_regions` VALUES ('21', 'Poitou Charente');
INSERT INTO `catads_regions` VALUES ('22', 'Rhone Alpes');


# --------------------------------------------------------

#
# Structure de la table `catads_departements`
#

CREATE TABLE `catads_departements` (
  `departement_numero` varchar(3) NOT NULL default '0',
  `departement_numero_region` smallint(3) NOT NULL default '0',
  `departement_nom` char(32) default NULL,
  PRIMARY KEY  (`departement_numero`),
  KEY `FK_DEPARTEMENT_REGION` (`departement_numero_region`)
) TYPE=MyISAM;


-- 
-- Contenu de la table `catads_departements`
-- 

INSERT INTO `catads_departements` VALUES ('1', '22', 'Ain');
INSERT INTO `catads_departements` VALUES ('2', '20', 'Aisne');
INSERT INTO `catads_departements` VALUES ('3', '3', 'Allier');
INSERT INTO `catads_departements` VALUES ('4', '18', 'Alpes de haute provence');
INSERT INTO `catads_departements` VALUES ('5', '18', 'Hautes alpes');
INSERT INTO `catads_departements` VALUES ('6', '18', 'Alpes maritimes');
INSERT INTO `catads_departements` VALUES ('7', '22', 'Ardeche');
INSERT INTO `catads_departements` VALUES ('8', '8', 'Ardennes');
INSERT INTO `catads_departements` VALUES ('9', '16', 'Ariege');
INSERT INTO `catads_departements` VALUES ('10', '8', 'Aube');
INSERT INTO `catads_departements` VALUES ('11', '13', 'Aude');
INSERT INTO `catads_departements` VALUES ('12', '16', 'Aveyron');
INSERT INTO `catads_departements` VALUES ('13', '18', 'Bouches du rhône');
INSERT INTO `catads_departements` VALUES ('14', '4', 'Calvados');
INSERT INTO `catads_departements` VALUES ('15', '3', 'Cantal');
INSERT INTO `catads_departements` VALUES ('16', '21', 'Charente');
INSERT INTO `catads_departements` VALUES ('17', '21', 'Charente maritime');
INSERT INTO `catads_departements` VALUES ('18', '7', 'Cher');
INSERT INTO `catads_departements` VALUES ('19', '14', 'Correze');
INSERT INTO `catads_departements` VALUES ('21', '5', 'Côte d\'or');
INSERT INTO `catads_departements` VALUES ('22', '6', 'Côtes d\'Armor');
INSERT INTO `catads_departements` VALUES ('23', '14', 'Creuse');
INSERT INTO `catads_departements` VALUES ('24', '2', 'Dordogne');
INSERT INTO `catads_departements` VALUES ('25', '10', 'Doubs');
INSERT INTO `catads_departements` VALUES ('26', '22', 'Drôme');
INSERT INTO `catads_departements` VALUES ('27', '11', 'Eure');
INSERT INTO `catads_departements` VALUES ('28', '7', 'Eure et Loir');
INSERT INTO `catads_departements` VALUES ('29', '6', 'Finistere');
INSERT INTO `catads_departements` VALUES ('30', '13', 'Gard');
INSERT INTO `catads_departements` VALUES ('31', '16', 'Haute garonne');
INSERT INTO `catads_departements` VALUES ('32', '16', 'Gers');
INSERT INTO `catads_departements` VALUES ('33', '2', 'Gironde');
INSERT INTO `catads_departements` VALUES ('34', '13', 'Herault');
INSERT INTO `catads_departements` VALUES ('35', '6', 'Ile et Vilaine');
INSERT INTO `catads_departements` VALUES ('36', '7', 'Indre');
INSERT INTO `catads_departements` VALUES ('37', '7', 'Indre et Loire');
INSERT INTO `catads_departements` VALUES ('38', '22', 'Isere');
INSERT INTO `catads_departements` VALUES ('39', '10', 'Jura');
INSERT INTO `catads_departements` VALUES ('40', '2', 'Landes');
INSERT INTO `catads_departements` VALUES ('41', '7', 'Loir et Cher');
INSERT INTO `catads_departements` VALUES ('42', '22', 'Loire');
INSERT INTO `catads_departements` VALUES ('43', '3', 'Haute loire');
INSERT INTO `catads_departements` VALUES ('44', '19', 'Loire Atlantique');
INSERT INTO `catads_departements` VALUES ('45', '7', 'Loiret');
INSERT INTO `catads_departements` VALUES ('46', '16', 'Lot');
INSERT INTO `catads_departements` VALUES ('47', '2', 'Lot et Garonne');
INSERT INTO `catads_departements` VALUES ('48', '13', 'Lozere');
INSERT INTO `catads_departements` VALUES ('49', '19', 'Maine et Loire');
INSERT INTO `catads_departements` VALUES ('50', '4', 'Manche');
INSERT INTO `catads_departements` VALUES ('51', '8', 'Marne');
INSERT INTO `catads_departements` VALUES ('52', '8', 'Haute Marne');
INSERT INTO `catads_departements` VALUES ('53', '19', 'Mayenne');
INSERT INTO `catads_departements` VALUES ('54', '15', 'Meurthe et Moselle');
INSERT INTO `catads_departements` VALUES ('55', '15', 'Meuse');
INSERT INTO `catads_departements` VALUES ('56', '6', 'Morbihan');
INSERT INTO `catads_departements` VALUES ('57', '15', 'Moselle');
INSERT INTO `catads_departements` VALUES ('58', '5', 'Nievre');
INSERT INTO `catads_departements` VALUES ('59', '17', 'Nord');
INSERT INTO `catads_departements` VALUES ('60', '20', 'Oise');
INSERT INTO `catads_departements` VALUES ('61', '4', 'Orne');
INSERT INTO `catads_departements` VALUES ('62', '17', 'Pas de Calais');
INSERT INTO `catads_departements` VALUES ('63', '3', 'Puy de Dôme');
INSERT INTO `catads_departements` VALUES ('64', '2', 'Pyrenees Atlantiques');
INSERT INTO `catads_departements` VALUES ('65', '16', 'Hautes Pyrenees');
INSERT INTO `catads_departements` VALUES ('66', '13', 'Pyrenees Orientales');
INSERT INTO `catads_departements` VALUES ('67', '1', 'Bas Rhin');
INSERT INTO `catads_departements` VALUES ('68', '1', 'Haut Rhin');
INSERT INTO `catads_departements` VALUES ('69', '22', 'Rhône');
INSERT INTO `catads_departements` VALUES ('70', '10', 'Haute Saône');
INSERT INTO `catads_departements` VALUES ('71', '5', 'Saône et Loire');
INSERT INTO `catads_departements` VALUES ('72', '19', 'Sarthe');
INSERT INTO `catads_departements` VALUES ('73', '22', 'Savoie');
INSERT INTO `catads_departements` VALUES ('74', '22', 'Haute Savoie');
INSERT INTO `catads_departements` VALUES ('75', '12', 'Paris');
INSERT INTO `catads_departements` VALUES ('76', '11', 'Seine Maritime');
INSERT INTO `catads_departements` VALUES ('77', '12', 'Seine et Marne');
INSERT INTO `catads_departements` VALUES ('78', '12', 'Yvelines');
INSERT INTO `catads_departements` VALUES ('79', '21', 'Deux Sevres');
INSERT INTO `catads_departements` VALUES ('80', '20', 'Somme');
INSERT INTO `catads_departements` VALUES ('81', '16', 'Tarn');
INSERT INTO `catads_departements` VALUES ('82', '16', 'Tarn et Garonne');
INSERT INTO `catads_departements` VALUES ('83', '18', 'Var');
INSERT INTO `catads_departements` VALUES ('84', '18', 'Vaucluse');
INSERT INTO `catads_departements` VALUES ('85', '19', 'Vendee');
INSERT INTO `catads_departements` VALUES ('86', '21', 'Vienne');
INSERT INTO `catads_departements` VALUES ('87', '14', 'Haute Vienne');
INSERT INTO `catads_departements` VALUES ('88', '15', 'Vosge');
INSERT INTO `catads_departements` VALUES ('89', '5', 'Yonne');
INSERT INTO `catads_departements` VALUES ('90', '10', 'Territoire de Belfort');
INSERT INTO `catads_departements` VALUES ('91', '12', 'Essonne');
INSERT INTO `catads_departements` VALUES ('92', '12', 'Haut de seine');
INSERT INTO `catads_departements` VALUES ('93', '12', 'Seine Saint Denis');
INSERT INTO `catads_departements` VALUES ('94', '12', 'Val de Marne');
INSERT INTO `catads_departements` VALUES ('95', '12', 'Val d\'Oise');
INSERT INTO `catads_departements` VALUES ('2a', '9', 'Corse du Sud');
INSERT INTO `catads_departements` VALUES ('2b', '9', 'Haute Corse');
