/*
 Navicat Premium Data Transfer

 Source Server         : Local
 Source Server Type    : MySQL
 Source Server Version : 50542
 Source Host           : localhost
 Source Database       : e2acrm

 Target Server Type    : MySQL
 Target Server Version : 50542
 File Encoding         : utf-8

 Date: 09/19/2016 16:20:12 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `administrators`
-- ----------------------------
DROP TABLE IF EXISTS `administrators`;
CREATE TABLE `administrators` (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminName` varchar(255) DEFAULT NULL,
  `AdminSurname` varchar(255) DEFAULT NULL,
  `AdminEmail` varchar(255) DEFAULT NULL,
  `AdminPassword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`AdminID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `administrators`
-- ----------------------------
BEGIN;
INSERT INTO `administrators` VALUES ('1', 'Alex', 'Minnie', 'alex@e2a.co.za', '$2y$11$L2Y.cbKGscp5e1vssRtZ2endL4Rweod/09ZGbWg7goo7RAmxUQAba');
COMMIT;

-- ----------------------------
--  Table structure for `adminlogin`
-- ----------------------------
DROP TABLE IF EXISTS `adminlogin`;
CREATE TABLE `adminlogin` (
  `AdminLoginID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminID` int(11) NOT NULL,
  `LoginDate` datetime DEFAULT NULL,
  `LoginIPAddress` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`AdminLoginID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `adminlogin`
-- ----------------------------
BEGIN;
INSERT INTO `adminlogin` VALUES ('1', '1', '2016-08-29 11:23:50', '::1'), ('2', '1', '2016-08-29 11:24:43', '::1'), ('3', '1', '2016-08-29 11:28:21', '::1'), ('4', '1', '2016-08-29 11:28:30', '::1'), ('5', '1', '2016-09-02 08:51:08', '::1');
COMMIT;

-- ----------------------------
--  Table structure for `clientcontacts`
-- ----------------------------
DROP TABLE IF EXISTS `clientcontacts`;
CREATE TABLE `clientcontacts` (
  `ContactID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `ReceiveInvoices` int(11) DEFAULT NULL,
  `ReceiveQuotes` int(11) DEFAULT NULL,
  `AllowAccountChanges` int(11) DEFAULT NULL,
  `AllowServiceChanges` int(11) DEFAULT NULL,
  `AllowContactUpdates` int(11) DEFAULT NULL,
  PRIMARY KEY (`ContactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `clientcustomfieldvalues`
-- ----------------------------
DROP TABLE IF EXISTS `clientcustomfieldvalues`;
CREATE TABLE `clientcustomfieldvalues` (
  `ClientCustomFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `ClientCustomFieldOptionID` int(11) DEFAULT '0',
  `ClientCustomFieldValue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ClientCustomFieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientcustomfieldvalues`
-- ----------------------------
BEGIN;
INSERT INTO `clientcustomfieldvalues` VALUES ('43', '1', '4', '0', 'asv'), ('44', '1', '2', '0', 'Friend Referral'), ('45', '1', '1', '0', 'asv'), ('46', '1', '3', '0', 'asv'), ('47', '1', '6', '0', 'asv'), ('48', '1', '5', '10', 'true'), ('49', '1', '5', '7', 'false'), ('50', '1', '8', '0', 'asv'), ('51', '1', '7', '8', 'true'), ('52', '1', '7', '9', 'false');
COMMIT;

-- ----------------------------
--  Table structure for `clientdocuments`
-- ----------------------------
DROP TABLE IF EXISTS `clientdocuments`;
CREATE TABLE `clientdocuments` (
  `DocumentID` int(11) NOT NULL AUTO_INCREMENT,
  `DocumentName` varchar(255) DEFAULT NULL,
  `DocumentFile` varchar(255) DEFAULT NULL,
  `DocumentType` varchar(255) DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`DocumentID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientdocuments`
-- ----------------------------
BEGIN;
INSERT INTO `clientdocuments` VALUES ('1', 'Test PDF', '1472799173_1470903653_blank.pdf', 'PDF', '2016-09-02', '1', '1', 'Alex Minnie');
COMMIT;

-- ----------------------------
--  Table structure for `clientinvoices`
-- ----------------------------
DROP TABLE IF EXISTS `clientinvoices`;
CREATE TABLE `clientinvoices` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `InvoiceAmount` double DEFAULT NULL,
  `InvoiceVat` double DEFAULT NULL,
  `InvoiceTotal` double DEFAULT NULL,
  `InvoiceStatus` int(11) DEFAULT '1',
  PRIMARY KEY (`InvoiceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `clientlogin`
-- ----------------------------
DROP TABLE IF EXISTS `clientlogin`;
CREATE TABLE `clientlogin` (
  `LoginID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT '0',
  `LoginDate` datetime DEFAULT NULL,
  `LoginIPAddress` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`LoginID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientlogin`
-- ----------------------------
BEGIN;
INSERT INTO `clientlogin` VALUES ('1', '1', '0', '2016-09-14 15:04:30', '::1'), ('2', '1', '0', '2016-09-14 15:05:26', '::1'), ('3', '1', '0', '2016-09-15 08:18:16', '::1'), ('4', '1', '0', '2016-09-15 11:33:36', '::1'), ('5', '1', '0', '2016-09-15 11:36:59', '::1'), ('6', '1', '0', '2016-09-15 11:38:09', '::1'), ('7', '1', '0', '2016-09-15 11:38:18', '::1');
COMMIT;

-- ----------------------------
--  Table structure for `clientnotes`
-- ----------------------------
DROP TABLE IF EXISTS `clientnotes`;
CREATE TABLE `clientnotes` (
  `NoteID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `Note` text NOT NULL,
  `DateAdded` date DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`NoteID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientnotes`
-- ----------------------------
BEGIN;
INSERT INTO `clientnotes` VALUES ('1', '1', 'Test adding a note', '2016-09-02', '1', 'Alex Minnie'), ('2', '1', 'Adding a second note', '2016-09-02', '1', 'Alex Minnie');
COMMIT;

-- ----------------------------
--  Table structure for `clientpackage`
-- ----------------------------
DROP TABLE IF EXISTS `clientpackage`;
CREATE TABLE `clientpackage` (
  `ClientPackageID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `PackageID` int(11) NOT NULL,
  `PriceOverride` double DEFAULT '0',
  `DateAdded` date DEFAULT NULL,
  `PackageStatus` int(11) DEFAULT '2',
  PRIMARY KEY (`ClientPackageID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientpackage`
-- ----------------------------
BEGIN;
INSERT INTO `clientpackage` VALUES ('1', '1', '1', '0', '2016-08-31', '2');
COMMIT;

-- ----------------------------
--  Table structure for `clientproductgroups`
-- ----------------------------
DROP TABLE IF EXISTS `clientproductgroups`;
CREATE TABLE `clientproductgroups` (
  `ProductGroupID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `ProductGroup` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ProductGroupID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientproductgroups`
-- ----------------------------
BEGIN;
INSERT INTO `clientproductgroups` VALUES ('1', '1', 'ADSL Connectivity'), ('2', '1', 'Fiber Connectivity');
COMMIT;

-- ----------------------------
--  Table structure for `clientproducts`
-- ----------------------------
DROP TABLE IF EXISTS `clientproducts`;
CREATE TABLE `clientproducts` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `Item` varchar(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Cost` double DEFAULT NULL,
  `ProductGroupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clientproducts`
-- ----------------------------
BEGIN;
INSERT INTO `clientproducts` VALUES ('1', '1', 'ADSL 4MB', 'ADSL 4MB Line Speed', '199', '1'), ('2', '1', 'ADSL 8MB', 'ADSL 8MB Line Speed', '299', '1');
COMMIT;

-- ----------------------------
--  Table structure for `clients`
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `ClientID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `TaxExempt` int(11) DEFAULT NULL,
  `OverdueNotices` int(11) DEFAULT NULL,
  `MarketingEmails` int(11) DEFAULT NULL,
  `PaymentMethod` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `ResellerID` int(11) DEFAULT NULL,
  `VatNumber` varchar(255) DEFAULT NULL,
  `AdminNotes` text,
  `DateAdded` datetime DEFAULT NULL,
  PRIMARY KEY (`ClientID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `clients`
-- ----------------------------
BEGIN;
INSERT INTO `clients` VALUES ('1', 'Alex', 'Minnie', 'AllWeb PTY Ltd', '0820724799', 'alex@allweb.co.za', '$2y$11$ozIHHZk7kTOMNwUz4Is49uuuys1WWZNqZWm5wLQDapJJ4JmR.xrR2', '7 Boundary Rd', 'Escombe', 'Durban', 'KZN', '4093', '192', '0', '0', '1', 'EFT', '2', '0', '', 'This is a test account only', '2016-08-30 06:53:09');
COMMIT;

-- ----------------------------
--  Table structure for `countries`
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `CountryID` int(11) NOT NULL AUTO_INCREMENT,
  `CountryName` varchar(255) NOT NULL,
  `TimeOffset` double DEFAULT '0',
  PRIMARY KEY (`CountryID`)
) ENGINE=MyISAM AUTO_INCREMENT=241 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `countries`
-- ----------------------------
BEGIN;
INSERT INTO `countries` VALUES ('1', 'Afghanistan', '4.5'), ('2', 'Albania', '1'), ('3', 'Algeria', '1'), ('4', 'American Samoa', '-11'), ('5', 'Andorra', '1'), ('6', 'Angola', '1'), ('7', 'Antarctica', '-2'), ('8', 'Antigua and Barbuda', '-4'), ('9', 'Argentina', '-3'), ('10', 'Armenia', '4'), ('11', 'Aruba', '-4'), ('12', 'Ascension', '0'), ('13', 'Australia North', '9.5'), ('14', 'Australia South', '10'), ('15', 'Australia West', '8'), ('16', 'Australia East', '10'), ('17', 'Austria', '1'), ('18', 'Azerbaijan', '3'), ('19', 'Bahamas', '-5'), ('20', 'Bahrain', '3'), ('21', 'Bangladesh', '6'), ('22', 'Barbados', '-4'), ('23', 'Belarus', '2'), ('24', 'Belgium', '1'), ('25', 'Belize', '-6'), ('26', 'Benin', '1'), ('27', 'Bermuda', '-4'), ('28', 'Bhutan', '6'), ('29', 'Bolivia', '-4'), ('30', 'Bosniaerzegovina', '1'), ('31', 'Botswana', '2'), ('32', 'Brazil West', '-4'), ('33', 'Brazil East', '-3'), ('34', 'British Virgin Islands', '-4'), ('35', 'Brunei', '8'), ('36', 'Bulgaria', '2'), ('37', 'Burkina Faso', '0'), ('38', 'Burundi', '2'), ('39', 'Cambodia', '7'), ('40', 'Cameroon', '1'), ('46', 'Cape Verde', '-1'), ('47', 'Cayman Islands', '-5'), ('48', 'Central African Rep', '1'), ('49', 'Chad Rep', '1'), ('50', 'Chile', '-4'), ('51', 'China', '8'), ('52', 'Christmas Is.', '-10'), ('53', 'Colombia', '-5'), ('54', 'Congo', '1'), ('55', 'Cook Is.', '-10'), ('56', 'Costa Rica', '-6'), ('57', 'Croatia', '1'), ('58', 'Cuba', '-5'), ('59', 'Cyprus', '2'), ('60', 'Czech Republic', '1'), ('61', 'Denmark', '1'), ('62', 'Djibouti', '3'), ('63', 'Dominica', '-4'), ('64', 'Dominican Republic', '-4'), ('65', 'Ecuador', '-5'), ('66', 'Egypt', '2'), ('67', 'El Salvador', '-6'), ('68', 'Equatorial Guinea', '1'), ('69', 'Eritrea', '3'), ('70', 'Estonia', '2'), ('71', 'Ethiopia', '3'), ('72', 'Faeroe Islands', '0'), ('73', 'Falkland Islands', '-4'), ('74', 'Fiji Islands', '12'), ('75', 'Finland', '2'), ('76', 'France', '1'), ('77', 'French Antilles (Martinique)', '-3'), ('78', 'French Guinea', '-3'), ('79', 'French Polynesia', '-10'), ('80', 'Gabon Republic', '1'), ('81', 'Gambia', '0'), ('82', 'Georgia', '4'), ('83', 'Germany', '1'), ('84', 'Ghana', '0'), ('85', 'Gibraltar', '1'), ('86', 'Greece', '2'), ('87', 'Greenland', '-3'), ('88', 'Grenada', '-4'), ('89', 'Guadeloupe', '-4'), ('90', 'Guam', '10'), ('91', 'Guatemala', '-6'), ('92', 'Guinea-Bissau', '0'), ('93', 'Guinea', '0'), ('94', 'Guyana', '-3'), ('95', 'Haiti', '-5'), ('96', 'Honduras', '-6'), ('97', 'Hong Kong', '8'), ('98', 'Hungary', '1'), ('99', 'Iceland', '0'), ('100', 'India', '5.5'), ('101', 'Indonesia Central', '8'), ('102', 'Indonesia East', '9'), ('103', 'Indonesia West', '7'), ('104', 'Iran', '3.5'), ('105', 'Iraq', '3'), ('106', 'Ireland', '0'), ('107', 'Israel', '2'), ('108', 'Italy', '1'), ('109', 'Jamaica', '-5'), ('110', 'Japan', '9'), ('111', 'Jordan', '2'), ('112', 'Kazakhstan', '6'), ('113', 'Kenya', '3'), ('114', 'Kiribati', '12'), ('115', 'Korea, North', '9'), ('116', 'Korea, South', '9'), ('117', 'Kuwait', '3'), ('118', 'Kyrgyzstan', '5'), ('119', 'Laos', '7'), ('120', 'Latvia', '2'), ('121', 'Lebanon', '2'), ('122', 'Lesotho', '2'), ('123', 'Liberia', '0'), ('124', 'Libya', '2'), ('125', 'Liechtenstein', '1'), ('126', 'Lithuania', '2'), ('127', 'Luxembourg', '1'), ('128', 'Macedonia', '1'), ('129', 'Madagascar', '3'), ('130', 'Malawi', '2'), ('131', 'Malaysia', '8'), ('132', 'Maldives', '5'), ('133', 'Mali Republic', '0'), ('134', 'Malta', '1'), ('135', 'Marshall Islands', '12'), ('136', 'Mauritania', '0'), ('137', 'Mauritius', '4'), ('138', 'Mayotte', '3'), ('139', 'Mexico Central', '-6'), ('140', 'Mexico East', '-5'), ('141', 'Mexico West', '-7'), ('142', 'Moldova', '2'), ('143', 'Monaco', '1'), ('144', 'Mongolia', '8'), ('145', 'Morocco', '0'), ('146', 'Mozambique', '2'), ('147', 'Myanmar', '6.5'), ('148', 'Namibia', '1'), ('149', 'Nauru', '12'), ('150', 'Nepal', '5.5'), ('151', 'Netherlands', '1'), ('152', 'Netherlands Antilles', '-4'), ('153', 'New Caledonia', '11'), ('154', 'New Zealand', '12'), ('155', 'Nicaragua', '-6'), ('156', 'Nigeria', '1'), ('157', 'Niger Republic', '1'), ('158', 'Norfolk Island', '11.5'), ('159', 'Norway', '1'), ('160', 'Oman', '4'), ('161', 'Pakistan', '5'), ('162', 'Palau', '9'), ('163', 'Panama, Republic Of', '-5'), ('164', 'Papua New Guinea', '10'), ('165', 'Paraguay', '-4'), ('166', 'Peru', '-5'), ('167', 'Philippines', '8'), ('168', 'Poland', '1'), ('169', 'Portugal', '1'), ('170', 'Puerto Rico', '-4'), ('171', 'Qatar', '3'), ('172', 'Reunion Island', '4'), ('173', 'Romania', '2'), ('174', 'Russia West', '2'), ('175', 'Russia Central 1', '4'), ('176', 'Russia Central 2', '7'), ('177', 'Russia East', '11'), ('178', 'Rwanda', '2'), ('179', 'Saba', '-4'), ('180', 'Samoa', '-11'), ('181', 'San Marino', '1'), ('182', 'Sao Tome', '0'), ('183', 'Saudi Arabia', '3'), ('184', 'Senegal', '0'), ('185', 'Seychelles Islands', '4'), ('186', 'Sierra Leone', '0'), ('187', 'Singapore', '8'), ('188', 'Slovakia', '1'), ('189', 'Slovenia', '1'), ('190', 'Solomon Islands', '11'), ('191', 'Somalia', '3'), ('192', 'South Africa', '2'), ('193', 'Spain', '1'), ('194', 'Sri Lanka', '5.5'), ('195', 'St Lucia', '-4'), ('196', 'St Maarteen', '-4'), ('197', 'St Pierre & Miquelon', '-3'), ('198', 'St Thomas', '-4'), ('199', 'St Vincent', '-4'), ('200', 'Sudan', '2'), ('201', 'Suriname', '-3'), ('202', 'Swaziland', '2'), ('203', 'Sweden', '1'), ('204', 'Switzerland', '1'), ('205', 'Syria', '2'), ('206', 'Taiwan', '8'), ('207', 'Tajikistan', '6'), ('208', 'Tanzania', '3'), ('209', 'Thailand', '7'), ('210', 'Togo', '0'), ('211', 'Tonga Islands', '13'), ('212', 'Trinidad and Tobago', '-4'), ('213', 'Tunisia', '1'), ('214', 'Turkey', '2'), ('215', 'Turkmenistan', '5'), ('216', 'Turks and Caicos', '-5'), ('217', 'Tuvalu', '12'), ('218', 'Uganda', '3'), ('219', 'Ukraine', '2'), ('220', 'United Arab Emirates', '4'), ('221', 'United Kingdom', '0'), ('222', 'Uruguay', '-3'), ('240', 'USA / Canada', '-6'), ('229', 'Uzbekistan', '5'), ('230', 'Vanuatu', '11'), ('231', 'Vatican City', '1'), ('232', 'Venezuela', '-4'), ('233', 'Vietnam', '7'), ('234', 'Wallis And Futuna Islands', '12'), ('235', 'Yemen', '3'), ('236', 'Yugoslavia', '1'), ('237', 'Zaire', '2'), ('238', 'Zambia', '2'), ('239', 'Zimbabwe', '2');
COMMIT;

-- ----------------------------
--  Table structure for `customclientfields`
-- ----------------------------
DROP TABLE IF EXISTS `customclientfields`;
CREATE TABLE `customclientfields` (
  `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text',
  PRIMARY KEY (`CustomFieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customclientfields`
-- ----------------------------
BEGIN;
INSERT INTO `customclientfields` VALUES ('1', 'ID Number', 'text'), ('2', 'How did you find us?', 'select'), ('3', 'Mobile Phone', 'text'), ('4', 'Company Reg', 'text'), ('5', 'Receive SMS', 'checkbox'), ('6', 'Office Tel', 'text'), ('7', 'Test Radio', 'radio'), ('8', 'Some Additional Info', 'textarea');
COMMIT;

-- ----------------------------
--  Table structure for `customclientfieldsvalues`
-- ----------------------------
DROP TABLE IF EXISTS `customclientfieldsvalues`;
CREATE TABLE `customclientfieldsvalues` (
  `CustomClientFieldOptionID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CustomClientFieldOptionID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customclientfieldsvalues`
-- ----------------------------
BEGIN;
INSERT INTO `customclientfieldsvalues` VALUES ('1', '2', 'Google'), ('2', '2', 'Bing'), ('3', '2', 'Other Search Engine'), ('4', '2', 'Friend Referral'), ('5', '2', 'Advertisement'), ('6', '2', 'Other'), ('7', '5', 'Yes'), ('8', '7', 'Only Saturday'), ('9', '7', 'Only Sunday'), ('10', '5', 'No');
COMMIT;

-- ----------------------------
--  Table structure for `customcustomerfields`
-- ----------------------------
DROP TABLE IF EXISTS `customcustomerfields`;
CREATE TABLE `customcustomerfields` (
  `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text',
  `ClientID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CustomFieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customcustomerfields`
-- ----------------------------
BEGIN;
INSERT INTO `customcustomerfields` VALUES ('1', 'ID Number', 'text', '1'), ('2', 'How did you find us?', 'select', '1'), ('3', 'Mobile Phone', 'text', '1'), ('4', 'Company Reg', 'text', '1'), ('5', 'Receive SMS', 'checkbox', '1'), ('6', 'Office Tel', 'text', '1'), ('7', 'Test Radio', 'radio', '1'), ('8', 'Some Additional Info', 'textarea', '1');
COMMIT;

-- ----------------------------
--  Table structure for `customcustomerfieldsvalues`
-- ----------------------------
DROP TABLE IF EXISTS `customcustomerfieldsvalues`;
CREATE TABLE `customcustomerfieldsvalues` (
  `CustomClientFieldOptionID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CustomClientFieldOptionID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customcustomerfieldsvalues`
-- ----------------------------
BEGIN;
INSERT INTO `customcustomerfieldsvalues` VALUES ('1', '2', 'Google', '1'), ('2', '2', 'Bing', '1'), ('3', '2', 'Other Search Engine', '1'), ('4', '2', 'Friend Referral', '1'), ('5', '2', 'Advertisement', '1'), ('6', '2', 'Other', '1'), ('7', '5', 'Yes', '1'), ('8', '7', 'Only Saturday', '1'), ('9', '7', 'Only Sunday', '1'), ('10', '5', 'No', '1');
COMMIT;

-- ----------------------------
--  Table structure for `customercustomfieldvalues`
-- ----------------------------
DROP TABLE IF EXISTS `customercustomfieldvalues`;
CREATE TABLE `customercustomfieldvalues` (
  `ClientCustomFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `ClientCustomFieldOptionID` int(11) DEFAULT '0',
  `ClientCustomFieldValue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ClientCustomFieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customercustomfieldvalues`
-- ----------------------------
BEGIN;
INSERT INTO `customercustomfieldvalues` VALUES ('83', '1', '2', '5', '10', 'false'), ('84', '1', '2', '5', '7', 'true'), ('85', '1', '2', '7', '8', 'false'), ('86', '1', '2', '7', '9', 'true'), ('87', '1', '2', '2', '0', 'Advertisement'), ('88', '1', '2', '4', '0', 'asfasf'), ('89', '1', '2', '1', '0', 'asfasf'), ('90', '1', '2', '3', '0', 'asfasf'), ('91', '1', '2', '6', '0', 'asfaf'), ('92', '1', '2', '8', '0', 'asfasf'), ('93', '1', '1', '5', '10', 'false'), ('94', '1', '1', '5', '7', 'false'), ('95', '1', '1', '7', '8', 'false'), ('96', '1', '1', '7', '9', 'false'), ('97', '1', '1', '2', '0', ''), ('98', '1', '1', '4', '0', ''), ('99', '1', '1', '1', '0', ''), ('100', '1', '1', '3', '0', ''), ('101', '1', '1', '6', '0', ''), ('102', '1', '1', '8', '0', '');
COMMIT;

-- ----------------------------
--  Table structure for `customerdocuments`
-- ----------------------------
DROP TABLE IF EXISTS `customerdocuments`;
CREATE TABLE `customerdocuments` (
  `DocumentID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `DocumentName` varchar(255) DEFAULT NULL,
  `DocumentFile` varchar(255) DEFAULT NULL,
  `DocumentType` varchar(255) DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `AddedByEmployeeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`DocumentID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerdocuments`
-- ----------------------------
BEGIN;
INSERT INTO `customerdocuments` VALUES ('1', '1', '2', 'Test PDF', '1472799173_1470903653_blank.pdf', 'PDF', '2016-09-02', '1', 'Alex Minnie', null), ('2', '1', '2', 'Test Doc', '1473858286_pic2.jpg', 'IMAGE', '2016-09-14', '1', 'Alex Minnie', '0'), ('3', '1', '2', 'Test', '1473858372_pic4.jpg', 'IMAGE', '2016-09-14', '1', 'Alex Minnie', '0'), ('4', '1', '1', 'Test Doc', '1474294350_plan.jpg', 'IMAGE', '2016-09-19', '1', 'Alex Minnie', '0');
COMMIT;

-- ----------------------------
--  Table structure for `customerinvoicelines`
-- ----------------------------
DROP TABLE IF EXISTS `customerinvoicelines`;
CREATE TABLE `customerinvoicelines` (
  `InvoiceLineItemID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  PRIMARY KEY (`InvoiceLineItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerinvoicelines`
-- ----------------------------
BEGIN;
INSERT INTO `customerinvoicelines` VALUES ('1', '1', 'ADSL 4MB Line Speed', '1', '199', '1', '199'), ('2', '1', 'ADSL 8MB Line Speed', '1', '299', '2', '299'), ('3', '1', 'Test adding custom invoice line', '1', '500', '0', '500');
COMMIT;

-- ----------------------------
--  Table structure for `customerinvoices`
-- ----------------------------
DROP TABLE IF EXISTS `customerinvoices`;
CREATE TABLE `customerinvoices` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) DEFAULT NULL,
  `ClientID` int(11) NOT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `DiscountPercent` double DEFAULT NULL,
  `InvoiceStatus` int(11) DEFAULT '1',
  `InvoiceReference` varchar(255) DEFAULT NULL,
  `InvoiceNotes` text,
  `Taxed` int(11) DEFAULT NULL,
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`InvoiceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerinvoices`
-- ----------------------------
BEGIN;
INSERT INTO `customerinvoices` VALUES ('1', '1', '1', 'INV1', '2016-09-19', '2016-09-30', '0', '1', '', '', '1', '1', '0', 'Alex Minnie');
COMMIT;

-- ----------------------------
--  Table structure for `customerlogs`
-- ----------------------------
DROP TABLE IF EXISTS `customerlogs`;
CREATE TABLE `customerlogs` (
  `CustomerLogID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `LogText` text,
  `LogAdded` datetime DEFAULT NULL,
  `AddedByClientID` int(11) DEFAULT NULL,
  `AddedByEmployeeID` int(11) DEFAULT '0',
  `AddedByName` varchar(255) DEFAULT NULL,
  `LogType` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CustomerLogID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerlogs`
-- ----------------------------
BEGIN;
INSERT INTO `customerlogs` VALUES ('1', '1', '1', 'Test adding a log', '2016-09-19 07:50:58', '1', '0', 'Alex Minnie', 'General'), ('2', '1', '1', 'Test adding a second log', '2016-09-19 07:51:25', '1', '0', 'Alex Minnie', 'General'), ('3', '1', '1', 'Test adding a different log type', '2016-09-19 07:51:49', '1', '0', 'Alex Minnie', 'Called Client'), ('4', '1', '1', 'Test adding a way longer description, client is not very happy at the moment as they have so many queries in one day and we have to sort out this client', '2016-09-19 07:59:36', '1', '0', 'Alex Minnie', 'General');
COMMIT;

-- ----------------------------
--  Table structure for `customernotes`
-- ----------------------------
DROP TABLE IF EXISTS `customernotes`;
CREATE TABLE `customernotes` (
  `NoteID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Note` text NOT NULL,
  `DateAdded` date DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`NoteID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customernotes`
-- ----------------------------
BEGIN;
INSERT INTO `customernotes` VALUES ('1', '1', null, 'Test adding a note', '2016-09-02', null, '1', 'Alex Minnie'), ('2', '1', null, 'Adding a second note', '2016-09-02', null, '1', 'Alex Minnie'), ('3', '1', '2', 'Testing adding a note to a client', '2016-09-14', '0', '1', 'Alex Minnie');
COMMIT;

-- ----------------------------
--  Table structure for `customerrecurring`
-- ----------------------------
DROP TABLE IF EXISTS `customerrecurring`;
CREATE TABLE `customerrecurring` (
  `RecurringID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Frequency` varchar(255) DEFAULT NULL,
  `DueDateForPayment` int(11) DEFAULT NULL,
  `InvoiceDateAdded` date DEFAULT NULL,
  `LastRun` date DEFAULT NULL,
  `NextRun` date DEFAULT NULL,
  `ClientReccuringInvoiceNumber` varchar(255) DEFAULT NULL,
  `ReferenceNumber` varchar(255) DEFAULT NULL,
  `RecurringStatus` int(11) DEFAULT '2',
  `InvoiceNotes` text,
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `DiscountPercentage` double DEFAULT '0',
  `AddedByName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`RecurringID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerrecurring`
-- ----------------------------
BEGIN;
INSERT INTO `customerrecurring` VALUES ('1', '1', '1', '2016-09-17', '0000-00-00', 'Every Month', '5', '2016-09-16', null, null, 'RINV1', '', '2', '', '1', '0', '20', null);
COMMIT;

-- ----------------------------
--  Table structure for `customerrecurringlines`
-- ----------------------------
DROP TABLE IF EXISTS `customerrecurringlines`;
CREATE TABLE `customerrecurringlines` (
  `RecurringLineItemID` int(11) NOT NULL AUTO_INCREMENT,
  `RecurringID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  PRIMARY KEY (`RecurringLineItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customerrecurringlines`
-- ----------------------------
BEGIN;
INSERT INTO `customerrecurringlines` VALUES ('1', '1', 'ADSL 4MB Line Speed', '1', '199', '1', '199');
COMMIT;

-- ----------------------------
--  Table structure for `customers`
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `TaxExempt` int(11) DEFAULT NULL,
  `OverdueNotices` int(11) DEFAULT NULL,
  `MarketingEmails` int(11) DEFAULT NULL,
  `PaymentMethod` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `VatNumber` varchar(255) DEFAULT NULL,
  `AdminNotes` text,
  `DateAdded` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `customers`
-- ----------------------------
BEGIN;
INSERT INTO `customers` VALUES ('1', '1', 'Alex', 'Minnie', 'AllWeb PTY Ltd', '0820724799', 'alex@allweb.co.za', '7 Boundary Rd', 'Escombe', 'Durban', 'KZN', '4093', '192', '0', '0', '1', 'EFT', '2', '', 'This is a test account only', '2016-08-30 06:53:09'), ('2', '1', 'Test', 'Test', 'Test', '0316464748', 'test@test.co.za', 'Test', 'asfasf', 'Test', 'KZN', '4093', '192', '0', '0', '0', 'Debit Order', '2', 'fasfasf', '', '2016-09-14 12:52:07');
COMMIT;

-- ----------------------------
--  Table structure for `employees`
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `IDNumber` varchar(255) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `packages`
-- ----------------------------
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `PackageID` int(11) NOT NULL AUTO_INCREMENT,
  `PackageName` varchar(255) NOT NULL,
  `PackageCost` double NOT NULL,
  PRIMARY KEY (`PackageID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `packages`
-- ----------------------------
BEGIN;
INSERT INTO `packages` VALUES ('1', 'Corporate+', '599.99'), ('2', 'Starter', '99.99'), ('3', 'SME', '299.99'), ('4', 'Corporate', '499.99');
COMMIT;

-- ----------------------------
--  Table structure for `resellers`
-- ----------------------------
DROP TABLE IF EXISTS `resellers`;
CREATE TABLE `resellers` (
  `ResellerID` int(11) NOT NULL AUTO_INCREMENT,
  `ResellerName` varchar(255) DEFAULT NULL,
  `ResellerSurname` varchar(255) DEFAULT NULL,
  `ResellerComm` double DEFAULT NULL,
  `ResellerEmail` varchar(255) DEFAULT NULL,
  `ResellerPassword` varchar(255) DEFAULT NULL,
  `ResellerStatus` int(11) DEFAULT '2',
  PRIMARY KEY (`ResellerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
