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
  `option_id` tinyint(3) NOT NULL auto_increment,
  `option_type` tinyint(3) NOT NULL default '0',
  `option_desc` varchar(20) NOT NULL default '',
  `option_order` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`option_id`)
) TYPE=MyISAM  ;

INSERT INTO `catads_options` (`option_id`, `option_type`, `option_desc`, `option_order`) VALUES 
(1, 1, 'Euros', 0),
(2, 1, 'Pounds', 0),
(3, 1, 'Dollars', 0),
(4, 2, 'Minimum', 0),
(5, 2, 'Maximum', 0),
(6, 2, 'Cash Only', 0),
(7, 2, 'Negotiable', 0),
(8, 2, 'No Offers', 0),
(9, 3, 'For Sale', 0),
(10, 3, 'Wanted', 0),
(11, 3, 'To Rent', 0),
(12, 3, 'Exchange', 0),
(13, 3, 'For Hire', 0),
(14, 4, '30', 0),
(15, 4, '14', 0),
(16, 4, '7', 0);

# --------------------------------------------------------

#
# Structure de la table `catads_regions`
#

CREATE TABLE `catads_regions` (
  `region_numero` smallint(3) NOT NULL default '0',
  `region_nom` varchar(64),
  PRIMARY KEY  (`region_numero`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `catads_regions`
-- 

INSERT INTO `catads_regions` VALUES ('1', 'Africa');
INSERT INTO `catads_regions` VALUES ('2', 'Alaska');
INSERT INTO `catads_regions` VALUES ('3', 'Arabian Peninsula');
INSERT INTO `catads_regions` VALUES ('4', 'Australia');
INSERT INTO `catads_regions` VALUES ('5', 'Canada');
INSERT INTO `catads_regions` VALUES ('6', 'Carribean');
INSERT INTO `catads_regions` VALUES ('7', 'Central America');
INSERT INTO `catads_regions` VALUES ('8', 'Central Asia');
INSERT INTO `catads_regions` VALUES ('9', 'China');
INSERT INTO `catads_regions` VALUES ('10', 'Europe');
INSERT INTO `catads_regions` VALUES ('11', 'Iceland');
INSERT INTO `catads_regions` VALUES ('12', 'Indian Sub Continent');
INSERT INTO `catads_regions` VALUES ('13', 'Japan');
INSERT INTO `catads_regions` VALUES ('14', 'Korea');
INSERT INTO `catads_regions` VALUES ('15', 'Mexico');
INSERT INTO `catads_regions` VALUES ('16', 'Middle East');
INSERT INTO `catads_regions` VALUES ('17', 'New Zealand');
INSERT INTO `catads_regions` VALUES ('18', 'Scandinavia');
INSERT INTO `catads_regions` VALUES ('19', 'South America');
INSERT INTO `catads_regions` VALUES ('20', 'South East Asia');
INSERT INTO `catads_regions` VALUES ('21', 'UK and Ireland');
INSERT INTO `catads_regions` VALUES ('22', 'USA');
INSERT INTO `catads_regions` VALUES ('23', 'World');


# --------------------------------------------------------

#
# Structure de la table `catads_departements`
#

CREATE TABLE `catads_departements` (
  `departement_numero` smallint(3) NOT NULL default '0',
  `departement_numero_region` smallint(3) ZEROFILL NOT NULL default '0',
  `departement_nom` varchar(64),
  PRIMARY KEY  (`departement_numero`),
  KEY `FK_DEPARTEMENT_REGION` (`departement_numero_region`)
) TYPE=MyISAM;


-- 
-- Contenu de la table `catads_departements`
-- 

INSERT INTO `catads_departements` VALUES ('1', '004', 'Afghanistan');
INSERT INTO `catads_departements` VALUES ('2', '248', 'Aland Islands');
INSERT INTO `catads_departements` VALUES ('3', '008', 'Albania');
INSERT INTO `catads_departements` VALUES ('4', '012', 'Algeria');
INSERT INTO `catads_departements` VALUES ('5', '016', 'American Samoa');
INSERT INTO `catads_departements` VALUES ('6', '020', 'Andorra');
INSERT INTO `catads_departements` VALUES ('7', '024', 'Angola');
INSERT INTO `catads_departements` VALUES ('8', '660', 'Anguilla');
INSERT INTO `catads_departements` VALUES ('9', '028', 'Antigua and Barbuda');
INSERT INTO `catads_departements` VALUES ('10', '032', 'Argentina');
INSERT INTO `catads_departements` VALUES ('11', '051', 'Armenia');
INSERT INTO `catads_departements` VALUES ('12', '533', 'Aruba');
INSERT INTO `catads_departements` VALUES ('13', '036', 'Australia');
INSERT INTO `catads_departements` VALUES ('14', '040', 'Austria');
INSERT INTO `catads_departements` VALUES ('15', '031', 'Azerbaijan');
INSERT INTO `catads_departements` VALUES ('16', '044', 'Bahamas');
INSERT INTO `catads_departements` VALUES ('17', '048', 'Bahrain');
INSERT INTO `catads_departements` VALUES ('18', '050', 'Bangladesh');
INSERT INTO `catads_departements` VALUES ('19', '052', 'Barbados');
INSERT INTO `catads_departements` VALUES ('20', '000', 'Antarctica');
INSERT INTO `catads_departements` VALUES ('21', '112', 'Belarus');
INSERT INTO `catads_departements` VALUES ('22', '056', 'Belgium');
INSERT INTO `catads_departements` VALUES ('23', '084', 'Belize');
INSERT INTO `catads_departements` VALUES ('24', '204', 'Benin');
INSERT INTO `catads_departements` VALUES ('25', '060', 'Bermuda');
INSERT INTO `catads_departements` VALUES ('26', '064', 'Bhutan');
INSERT INTO `catads_departements` VALUES ('27', '068', 'Bolivia');
INSERT INTO `catads_departements` VALUES ('28', '070', 'Bosnia and Herzegovina');
INSERT INTO `catads_departements` VALUES ('29', '072', 'Botswana');
INSERT INTO `catads_departements` VALUES ('30', '076', 'Brazil');
INSERT INTO `catads_departements` VALUES ('31', '092', 'British Virgin Islands');
INSERT INTO `catads_departements` VALUES ('32', '096', 'Brunei Darussalam');
INSERT INTO `catads_departements` VALUES ('33', '100', 'Bulgaria');
INSERT INTO `catads_departements` VALUES ('34', '854', 'Burkina Faso');
INSERT INTO `catads_departements` VALUES ('35', '108', 'Burundi');
INSERT INTO `catads_departements` VALUES ('36', '116', 'Cambodia');
INSERT INTO `catads_departements` VALUES ('37', '120', 'Cameroon');
INSERT INTO `catads_departements` VALUES ('38', '124', 'Canada');
INSERT INTO `catads_departements` VALUES ('39', '132', 'Cape Verde');
INSERT INTO `catads_departements` VALUES ('40', '136', 'Cayman Islands');
INSERT INTO `catads_departements` VALUES ('41', '140', 'Central African Republic');
INSERT INTO `catads_departements` VALUES ('42', '148', 'Chad');
INSERT INTO `catads_departements` VALUES ('43', '152', 'Chile');
INSERT INTO `catads_departements` VALUES ('44', '156', 'China');
INSERT INTO `catads_departements` VALUES ('45', '344', 'Hong Kong - China');
INSERT INTO `catads_departements` VALUES ('46', '446', 'Macao - China');
INSERT INTO `catads_departements` VALUES ('47', '170', 'Colombia');
INSERT INTO `catads_departements` VALUES ('48', '174', 'Comoros');
INSERT INTO `catads_departements` VALUES ('49', '178', 'Congo');
INSERT INTO `catads_departements` VALUES ('50', '184', 'Cook Islands');
INSERT INTO `catads_departements` VALUES ('51', '188', 'Costa Rica');
INSERT INTO `catads_departements` VALUES ('52', '384', 'Cote d ''Ivoire');
INSERT INTO `catads_departements` VALUES ('53', '191', 'Croatia');
INSERT INTO `catads_departements` VALUES ('54', '192', 'Cuba');
INSERT INTO `catads_departements` VALUES ('55', '196', 'Cyprus');
INSERT INTO `catads_departements` VALUES ('56', '203', 'Czech Republic');
INSERT INTO `catads_departements` VALUES ('57', '408', 'D. P. Republic of Korea');
INSERT INTO `catads_departements` VALUES ('58', '180', 'D. Republic of Congo');
INSERT INTO `catads_departements` VALUES ('59', '208', 'Denmark');
INSERT INTO `catads_departements` VALUES ('60', '262', 'Djibouti');
INSERT INTO `catads_departements` VALUES ('61', '212', 'Dominica');
INSERT INTO `catads_departements` VALUES ('62', '214', 'Dominican Republic');
INSERT INTO `catads_departements` VALUES ('63', '218', 'Ecuador');
INSERT INTO `catads_departements` VALUES ('64', '818', 'Egypt');
INSERT INTO `catads_departements` VALUES ('65', '222', 'El Salvador');
INSERT INTO `catads_departements` VALUES ('66', '226', 'Equatorial Guinea');
INSERT INTO `catads_departements` VALUES ('67', '232', 'Eritrea');
INSERT INTO `catads_departements` VALUES ('68', '233', 'Estonia');
INSERT INTO `catads_departements` VALUES ('69', '231', 'Ethiopia');
INSERT INTO `catads_departements` VALUES ('70', '234', 'Faeroe Islands');
INSERT INTO `catads_departements` VALUES ('71', '238', 'Falkland Islands - Malvinas');
INSERT INTO `catads_departements` VALUES ('72', '242', 'Fiji');
INSERT INTO `catads_departements` VALUES ('73', '246', 'Finland');
INSERT INTO `catads_departements` VALUES ('74', '250', 'France');
INSERT INTO `catads_departements` VALUES ('75', '254', 'French Guiana');
INSERT INTO `catads_departements` VALUES ('76', '258', 'French Polynesia');
INSERT INTO `catads_departements` VALUES ('77', '266', 'Gabon');
INSERT INTO `catads_departements` VALUES ('78', '270', 'Gambia');
INSERT INTO `catads_departements` VALUES ('79', '268', 'Georgia');
INSERT INTO `catads_departements` VALUES ('80', '276', 'Germany');
INSERT INTO `catads_departements` VALUES ('81', '288', 'Ghana');
INSERT INTO `catads_departements` VALUES ('82', '292', 'Gibraltar');
INSERT INTO `catads_departements` VALUES ('83', '300', 'Greece');
INSERT INTO `catads_departements` VALUES ('84', '304', 'Greenland');
INSERT INTO `catads_departements` VALUES ('85', '308', 'Grenada');
INSERT INTO `catads_departements` VALUES ('86', '312', 'Guadeloupe');
INSERT INTO `catads_departements` VALUES ('87', '316', 'Guam');
INSERT INTO `catads_departements` VALUES ('88', '320', 'Guatemala');
INSERT INTO `catads_departements` VALUES ('89', '831', 'Guernsey');
INSERT INTO `catads_departements` VALUES ('90', '324', 'Guinea');
INSERT INTO `catads_departements` VALUES ('91', '624', 'Guinea-Bissau');
INSERT INTO `catads_departements` VALUES ('92', '328', 'Guyana');
INSERT INTO `catads_departements` VALUES ('93', '332', 'Haiti');
INSERT INTO `catads_departements` VALUES ('94', '336', 'Holy See');
INSERT INTO `catads_departements` VALUES ('95', '340', 'Honduras');
INSERT INTO `catads_departements` VALUES ('96', '348', 'Hungary');
INSERT INTO `catads_departements` VALUES ('97', '352', 'Iceland');
INSERT INTO `catads_departements` VALUES ('98', '356', 'India');
INSERT INTO `catads_departements` VALUES ('99', '360', 'Indonesia');
INSERT INTO `catads_departements` VALUES ('100', '364', 'Iran - Islamic Republic of');
INSERT INTO `catads_departements` VALUES ('101', '368', 'Iraq');
INSERT INTO `catads_departements` VALUES ('102', '372', 'Ireland');
INSERT INTO `catads_departements` VALUES ('103', '833', 'Isle of Man');
INSERT INTO `catads_departements` VALUES ('104', '376', 'Israel');
INSERT INTO `catads_departements` VALUES ('105', '380', 'Italy');
INSERT INTO `catads_departements` VALUES ('106', '388', 'Jamaica');
INSERT INTO `catads_departements` VALUES ('107', '392', 'Japan');
INSERT INTO `catads_departements` VALUES ('108', '832', 'Jersey');
INSERT INTO `catads_departements` VALUES ('109', '400', 'Jordan');
INSERT INTO `catads_departements` VALUES ('110', '398', 'Kazakhstan');
INSERT INTO `catads_departements` VALUES ('111', '404', 'Kenya');
INSERT INTO `catads_departements` VALUES ('112', '296', 'Kiribati');
INSERT INTO `catads_departements` VALUES ('113', '414', 'Kuwait');
INSERT INTO `catads_departements` VALUES ('114', '417', 'Kyrgyzstan');
INSERT INTO `catads_departements` VALUES ('115', '418', 'Lao - P. D. R.');
INSERT INTO `catads_departements` VALUES ('116', '428', 'Latvia');
INSERT INTO `catads_departements` VALUES ('117', '422', 'Lebanon');
INSERT INTO `catads_departements` VALUES ('118', '426', 'Lesotho');
INSERT INTO `catads_departements` VALUES ('119', '430', 'Liberia');
INSERT INTO `catads_departements` VALUES ('120', '434', 'Libyan Arab Jamahiriya');
INSERT INTO `catads_departements` VALUES ('121', '438', 'Liechtenstein');
INSERT INTO `catads_departements` VALUES ('122', '440', 'Lithuania');
INSERT INTO `catads_departements` VALUES ('123', '442', 'Luxembourg');
INSERT INTO `catads_departements` VALUES ('124', '450', 'Madagascar');
INSERT INTO `catads_departements` VALUES ('125', '454', 'Malawi');
INSERT INTO `catads_departements` VALUES ('126', '458', 'Malaysia');
INSERT INTO `catads_departements` VALUES ('127', '462', 'Maldives');
INSERT INTO `catads_departements` VALUES ('128', '466', 'Mali');
INSERT INTO `catads_departements` VALUES ('129', '470', 'Malta');
INSERT INTO `catads_departements` VALUES ('130', '584', 'Marshall Islands');
INSERT INTO `catads_departements` VALUES ('131', '474', 'Martinique');
INSERT INTO `catads_departements` VALUES ('132', '478', 'Mauritania');
INSERT INTO `catads_departements` VALUES ('133', '480', 'Mauritius');
INSERT INTO `catads_departements` VALUES ('134', '175', 'Mayotte');
INSERT INTO `catads_departements` VALUES ('135', '484', 'Mexico');
INSERT INTO `catads_departements` VALUES ('136', '583', 'Micronesia Fed');
INSERT INTO `catads_departements` VALUES ('137', '492', 'Monaco');
INSERT INTO `catads_departements` VALUES ('138', '496', 'Mongolia');
INSERT INTO `catads_departements` VALUES ('139', '499', 'Montenegro');
INSERT INTO `catads_departements` VALUES ('140', '500', 'Montserrat');
INSERT INTO `catads_departements` VALUES ('141', '504', 'Morocco');
INSERT INTO `catads_departements` VALUES ('142', '508', 'Mozambique');
INSERT INTO `catads_departements` VALUES ('143', '104', 'Myanmar');
INSERT INTO `catads_departements` VALUES ('144', '516', 'Namibia');
INSERT INTO `catads_departements` VALUES ('145', '520', 'Nauru');
INSERT INTO `catads_departements` VALUES ('146', '524', 'Nepal');
INSERT INTO `catads_departements` VALUES ('147', '528', 'Netherlands');
INSERT INTO `catads_departements` VALUES ('148', '530', 'Netherlands Antilles');
INSERT INTO `catads_departements` VALUES ('149', '540', 'New Caledonia');
INSERT INTO `catads_departements` VALUES ('150', '554', 'New Zealand');
INSERT INTO `catads_departements` VALUES ('151', '558', 'Nicaragua');
INSERT INTO `catads_departements` VALUES ('152', '562', 'Niger');
INSERT INTO `catads_departements` VALUES ('153', '566', 'Nigeria');
INSERT INTO `catads_departements` VALUES ('154', '570', 'Niue');
INSERT INTO `catads_departements` VALUES ('155', '574', 'Norfolk Island');
INSERT INTO `catads_departements` VALUES ('156', '580', 'Northern Mariana Islands');
INSERT INTO `catads_departements` VALUES ('157', '578', 'Norway');
INSERT INTO `catads_departements` VALUES ('158', '275', 'Palestinian O. T.');
INSERT INTO `catads_departements` VALUES ('159', '512', 'Oman');
INSERT INTO `catads_departements` VALUES ('160', '586', 'Pakistan');
INSERT INTO `catads_departements` VALUES ('161', '585', 'Palau');
INSERT INTO `catads_departements` VALUES ('162', '591', 'Panama');
INSERT INTO `catads_departements` VALUES ('163', '598', 'Papua New Guinea');
INSERT INTO `catads_departements` VALUES ('164', '600', 'Paraguay');
INSERT INTO `catads_departements` VALUES ('165', '604', 'Peru');
INSERT INTO `catads_departements` VALUES ('166', '608', 'Philippines');
INSERT INTO `catads_departements` VALUES ('167', '612', 'Pitcairn');
INSERT INTO `catads_departements` VALUES ('168', '616', 'Poland');
INSERT INTO `catads_departements` VALUES ('169', '620', 'Portugal');
INSERT INTO `catads_departements` VALUES ('170', '630', 'Puerto Rico');
INSERT INTO `catads_departements` VALUES ('171', '634', 'Qatar');
INSERT INTO `catads_departements` VALUES ('172', '410', 'Republic of Korea');
INSERT INTO `catads_departements` VALUES ('173', '498', 'Republic of Moldova');
INSERT INTO `catads_departements` VALUES ('174', '638', 'Reunion');
INSERT INTO `catads_departements` VALUES ('175', '642', 'Romania');
INSERT INTO `catads_departements` VALUES ('176', '643', 'Russian Federation');
INSERT INTO `catads_departements` VALUES ('177', '646', 'Rwanda');
INSERT INTO `catads_departements` VALUES ('178', '652', 'Saint-Barthelemy');
INSERT INTO `catads_departements` VALUES ('179', '654', 'Saint-Helena');
INSERT INTO `catads_departements` VALUES ('180', '659', 'Saint Kitts and Nevis');
INSERT INTO `catads_departements` VALUES ('181', '662', 'Saint Lucia');
INSERT INTO `catads_departements` VALUES ('182', '663', 'Saint-Martin - French');
INSERT INTO `catads_departements` VALUES ('183', '666', 'Saint Pierre and Miquelon');
INSERT INTO `catads_departements` VALUES ('184', '670', 'Saint Vincent and Grenadines');
INSERT INTO `catads_departements` VALUES ('185', '882', 'Samoa');
INSERT INTO `catads_departements` VALUES ('186', '674', 'San Marino');
INSERT INTO `catads_departements` VALUES ('187', '678', 'Sao Tome and Principe');
INSERT INTO `catads_departements` VALUES ('188', '682', 'Saudi Arabia');
INSERT INTO `catads_departements` VALUES ('189', '686', 'Senegal');
INSERT INTO `catads_departements` VALUES ('190', '688', 'Serbia');
INSERT INTO `catads_departements` VALUES ('191', '690', 'Seychelles');
INSERT INTO `catads_departements` VALUES ('192', '694', 'Sierra Leone');
INSERT INTO `catads_departements` VALUES ('193', '702', 'Singapore');
INSERT INTO `catads_departements` VALUES ('194', '703', 'Slovakia');
INSERT INTO `catads_departements` VALUES ('195', '705', 'Slovenia');
INSERT INTO `catads_departements` VALUES ('196', '090', 'Solomon Islands');
INSERT INTO `catads_departements` VALUES ('197', '706', 'Somalia');
INSERT INTO `catads_departements` VALUES ('198', '710', 'South Africa');
INSERT INTO `catads_departements` VALUES ('199', '724', 'Spain');
INSERT INTO `catads_departements` VALUES ('200', '144', 'Sri Lanka');
INSERT INTO `catads_departements` VALUES ('201', '736', 'Sudan');
INSERT INTO `catads_departements` VALUES ('202', '740', 'Suriname');
INSERT INTO `catads_departements` VALUES ('203', '744', 'Svalbard and Jan Mayen Isles');
INSERT INTO `catads_departements` VALUES ('204', '748', 'Swaziland');
INSERT INTO `catads_departements` VALUES ('205', '752', 'Sweden');
INSERT INTO `catads_departements` VALUES ('206', '756', 'Switzerland');
INSERT INTO `catads_departements` VALUES ('207', '760', 'Syrian Arab Republic');
INSERT INTO `catads_departements` VALUES ('208', '762', 'Tajikistan');
INSERT INTO `catads_departements` VALUES ('209', '764', 'Thailand');
INSERT INTO `catads_departements` VALUES ('210', '807', 'Macedonia - F. Y. R');
INSERT INTO `catads_departements` VALUES ('211', '626', 'Timor-Leste');
INSERT INTO `catads_departements` VALUES ('212', '768', 'Togo');
INSERT INTO `catads_departements` VALUES ('213', '772', 'Tokelau');
INSERT INTO `catads_departements` VALUES ('214', '776', 'Tonga');
INSERT INTO `catads_departements` VALUES ('215', '780', 'Trinidad and Tobago');
INSERT INTO `catads_departements` VALUES ('216', '788', 'Tunisia');
INSERT INTO `catads_departements` VALUES ('217', '792', 'Turkey');
INSERT INTO `catads_departements` VALUES ('218', '795', 'Turkmenistan');
INSERT INTO `catads_departements` VALUES ('219', '796', 'Turks and Caicos Islands');
INSERT INTO `catads_departements` VALUES ('220', '798', 'Tuvalu');
INSERT INTO `catads_departements` VALUES ('221', '800', 'Uganda');
INSERT INTO `catads_departements` VALUES ('222', '804', 'Ukraine');
INSERT INTO `catads_departements` VALUES ('223', '784', 'United Arab Emirates');
INSERT INTO `catads_departements` VALUES ('224', '826', 'United Kingdom');
INSERT INTO `catads_departements` VALUES ('225', '834', 'United Rep. Tanzania');
INSERT INTO `catads_departements` VALUES ('226', '840', 'USA');
INSERT INTO `catads_departements` VALUES ('227', '850', 'US Virgin Islands');
INSERT INTO `catads_departements` VALUES ('228', '858', 'Uruguay');
INSERT INTO `catads_departements` VALUES ('229', '860', 'Uzbekistan');
INSERT INTO `catads_departements` VALUES ('230', '548', 'Vanuatu');
INSERT INTO `catads_departements` VALUES ('231', '862', 'Venezuela B. Rep');
INSERT INTO `catads_departements` VALUES ('232', '704', 'Vietnam');
INSERT INTO `catads_departements` VALUES ('233', '876', 'Wallis and Futuna Islands');
INSERT INTO `catads_departements` VALUES ('234', '732', 'Western Sahara');
INSERT INTO `catads_departements` VALUES ('235', '887', 'Yemen');
INSERT INTO `catads_departements` VALUES ('236', '894', 'Zambia');
INSERT INTO `catads_departements` VALUES ('237', '716', 'Zimbabwe');



