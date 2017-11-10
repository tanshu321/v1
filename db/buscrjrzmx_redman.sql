-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2017 at 04:37 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buscrjrzmx_redman`
--

-- --------------------------------------------------------

--
-- Table structure for table `companysettings`
--

CREATE TABLE `companysettings` (
  `SettingsID` int(11) NOT NULL,
  `CompanyLogo` varchar(255) DEFAULT NULL,
  `VATRegistered` int(11) DEFAULT '0',
  `VATNumber` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` varchar(255) DEFAULT NULL,
  `BankName` varchar(255) DEFAULT NULL,
  `AccountHolder` varchar(255) DEFAULT NULL,
  `AccountNumber` varchar(255) DEFAULT NULL,
  `BranchCode` varchar(255) DEFAULT NULL,
  `AccountType` varchar(255) DEFAULT NULL,
  `VATRate` double DEFAULT '14',
  `CurrencySymbol` varchar(255) DEFAULT NULL,
  `InvoiceLogo` varchar(255) DEFAULT NULL,
  `InvoiceDisplayCompany` varchar(255) DEFAULT NULL,
  `InvoiceDisplayEmail` varchar(255) DEFAULT NULL,
  `InvoiceDisplayTel` varchar(255) DEFAULT NULL,
  `InvoiceDisplayFax` varchar(255) DEFAULT NULL,
  `RecurringInvoiceDay` int(11) DEFAULT NULL,
  `CompanyRegistration` varchar(255) DEFAULT NULL,
  `TermsAndConditions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companysettings`
--

INSERT INTO `companysettings` (`SettingsID`, `CompanyLogo`, `VATRegistered`, `VATNumber`, `Address1`, `Address2`, `City`, `Region`, `PostCode`, `CountryID`, `BankName`, `AccountHolder`, `AccountNumber`, `BranchCode`, `AccountType`, `VATRate`, `CurrencySymbol`, `InvoiceLogo`, `InvoiceDisplayCompany`, `InvoiceDisplayEmail`, `InvoiceDisplayTel`, `InvoiceDisplayFax`, `RecurringInvoiceDay`, `CompanyRegistration`, `TermsAndConditions`) VALUES
(1, 'redmanlogo.jpg', 0, '', '88 Haygarth Rd', 'Kloof', 'Durban', 'Kwazulu-Natal', '3610', '192', 'FNB', 'Dr JL Redman (Practice Number: 0623954)', '62668715885', '250655', 'Transmission', 14, NULL, 'redmanlogo.jpg', 'Redman Chiro', 'drjade@redmanchiro.co.za', '0827142211', '', 25, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `CountryID` int(11) NOT NULL,
  `CountryName` varchar(255) NOT NULL,
  `TimeOffset` double DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`CountryID`, `CountryName`, `TimeOffset`) VALUES
(1, 'Afghanistan', 4.5),
(2, 'Albania', 1),
(3, 'Algeria', 1),
(4, 'American Samoa', -11),
(5, 'Andorra', 1),
(6, 'Angola', 1),
(7, 'Antarctica', -2),
(8, 'Antigua and Barbuda', -4),
(9, 'Argentina', -3),
(10, 'Armenia', 4),
(11, 'Aruba', -4),
(12, 'Ascension', 0),
(13, 'Australia North', 9.5),
(14, 'Australia South', 10),
(15, 'Australia West', 8),
(16, 'Australia East', 10),
(17, 'Austria', 1),
(18, 'Azerbaijan', 3),
(19, 'Bahamas', -5),
(20, 'Bahrain', 3),
(21, 'Bangladesh', 6),
(22, 'Barbados', -4),
(23, 'Belarus', 2),
(24, 'Belgium', 1),
(25, 'Belize', -6),
(26, 'Benin', 1),
(27, 'Bermuda', -4),
(28, 'Bhutan', 6),
(29, 'Bolivia', -4),
(30, 'Bosniaerzegovina', 1),
(31, 'Botswana', 2),
(32, 'Brazil West', -4),
(33, 'Brazil East', -3),
(34, 'British Virgin Islands', -4),
(35, 'Brunei', 8),
(36, 'Bulgaria', 2),
(37, 'Burkina Faso', 0),
(38, 'Burundi', 2),
(39, 'Cambodia', 7),
(40, 'Cameroon', 1),
(46, 'Cape Verde', -1),
(47, 'Cayman Islands', -5),
(48, 'Central African Rep', 1),
(49, 'Chad Rep', 1),
(50, 'Chile', -4),
(51, 'China', 8),
(52, 'Christmas Is.', -10),
(53, 'Colombia', -5),
(54, 'Congo', 1),
(55, 'Cook Is.', -10),
(56, 'Costa Rica', -6),
(57, 'Croatia', 1),
(58, 'Cuba', -5),
(59, 'Cyprus', 2),
(60, 'Czech Republic', 1),
(61, 'Denmark', 1),
(62, 'Djibouti', 3),
(63, 'Dominica', -4),
(64, 'Dominican Republic', -4),
(65, 'Ecuador', -5),
(66, 'Egypt', 2),
(67, 'El Salvador', -6),
(68, 'Equatorial Guinea', 1),
(69, 'Eritrea', 3),
(70, 'Estonia', 2),
(71, 'Ethiopia', 3),
(72, 'Faeroe Islands', 0),
(73, 'Falkland Islands', -4),
(74, 'Fiji Islands', 12),
(75, 'Finland', 2),
(76, 'France', 1),
(77, 'French Antilles (Martinique)', -3),
(78, 'French Guinea', -3),
(79, 'French Polynesia', -10),
(80, 'Gabon Republic', 1),
(81, 'Gambia', 0),
(82, 'Georgia', 4),
(83, 'Germany', 1),
(84, 'Ghana', 0),
(85, 'Gibraltar', 1),
(86, 'Greece', 2),
(87, 'Greenland', -3),
(88, 'Grenada', -4),
(89, 'Guadeloupe', -4),
(90, 'Guam', 10),
(91, 'Guatemala', -6),
(92, 'Guinea-Bissau', 0),
(93, 'Guinea', 0),
(94, 'Guyana', -3),
(95, 'Haiti', -5),
(96, 'Honduras', -6),
(97, 'Hong Kong', 8),
(98, 'Hungary', 1),
(99, 'Iceland', 0),
(100, 'India', 5.5),
(101, 'Indonesia Central', 8),
(102, 'Indonesia East', 9),
(103, 'Indonesia West', 7),
(104, 'Iran', 3.5),
(105, 'Iraq', 3),
(106, 'Ireland', 0),
(107, 'Israel', 2),
(108, 'Italy', 1),
(109, 'Jamaica', -5),
(110, 'Japan', 9),
(111, 'Jordan', 2),
(112, 'Kazakhstan', 6),
(113, 'Kenya', 3),
(114, 'Kiribati', 12),
(115, 'Korea, North', 9),
(116, 'Korea, South', 9),
(117, 'Kuwait', 3),
(118, 'Kyrgyzstan', 5),
(119, 'Laos', 7),
(120, 'Latvia', 2),
(121, 'Lebanon', 2),
(122, 'Lesotho', 2),
(123, 'Liberia', 0),
(124, 'Libya', 2),
(125, 'Liechtenstein', 1),
(126, 'Lithuania', 2),
(127, 'Luxembourg', 1),
(128, 'Macedonia', 1),
(129, 'Madagascar', 3),
(130, 'Malawi', 2),
(131, 'Malaysia', 8),
(132, 'Maldives', 5),
(133, 'Mali Republic', 0),
(134, 'Malta', 1),
(135, 'Marshall Islands', 12),
(136, 'Mauritania', 0),
(137, 'Mauritius', 4),
(138, 'Mayotte', 3),
(139, 'Mexico Central', -6),
(140, 'Mexico East', -5),
(141, 'Mexico West', -7),
(142, 'Moldova', 2),
(143, 'Monaco', 1),
(144, 'Mongolia', 8),
(145, 'Morocco', 0),
(146, 'Mozambique', 2),
(147, 'Myanmar', 6.5),
(148, 'Namibia', 1),
(149, 'Nauru', 12),
(150, 'Nepal', 5.5),
(151, 'Netherlands', 1),
(152, 'Netherlands Antilles', -4),
(153, 'New Caledonia', 11),
(154, 'New Zealand', 12),
(155, 'Nicaragua', -6),
(156, 'Nigeria', 1),
(157, 'Niger Republic', 1),
(158, 'Norfolk Island', 11.5),
(159, 'Norway', 1),
(160, 'Oman', 4),
(161, 'Pakistan', 5),
(162, 'Palau', 9),
(163, 'Panama, Republic Of', -5),
(164, 'Papua New Guinea', 10),
(165, 'Paraguay', -4),
(166, 'Peru', -5),
(167, 'Philippines', 8),
(168, 'Poland', 1),
(169, 'Portugal', 1),
(170, 'Puerto Rico', -4),
(171, 'Qatar', 3),
(172, 'Reunion Island', 4),
(173, 'Romania', 2),
(174, 'Russia West', 2),
(175, 'Russia Central 1', 4),
(176, 'Russia Central 2', 7),
(177, 'Russia East', 11),
(178, 'Rwanda', 2),
(179, 'Saba', -4),
(180, 'Samoa', -11),
(181, 'San Marino', 1),
(182, 'Sao Tome', 0),
(183, 'Saudi Arabia', 3),
(184, 'Senegal', 0),
(185, 'Seychelles Islands', 4),
(186, 'Sierra Leone', 0),
(187, 'Singapore', 8),
(188, 'Slovakia', 1),
(189, 'Slovenia', 1),
(190, 'Solomon Islands', 11),
(191, 'Somalia', 3),
(192, 'South Africa', 2),
(193, 'Spain', 1),
(194, 'Sri Lanka', 5.5),
(195, 'St Lucia', -4),
(196, 'St Maarteen', -4),
(197, 'St Pierre & Miquelon', -3),
(198, 'St Thomas', -4),
(199, 'St Vincent', -4),
(200, 'Sudan', 2),
(201, 'Suriname', -3),
(202, 'Swaziland', 2),
(203, 'Sweden', 1),
(204, 'Switzerland', 1),
(205, 'Syria', 2),
(206, 'Taiwan', 8),
(207, 'Tajikistan', 6),
(208, 'Tanzania', 3),
(209, 'Thailand', 7),
(210, 'Togo', 0),
(211, 'Tonga Islands', 13),
(212, 'Trinidad and Tobago', -4),
(213, 'Tunisia', 1),
(214, 'Turkey', 2),
(215, 'Turkmenistan', 5),
(216, 'Turks and Caicos', -5),
(217, 'Tuvalu', 12),
(218, 'Uganda', 3),
(219, 'Ukraine', 2),
(220, 'United Arab Emirates', 4),
(221, 'United Kingdom', 0),
(222, 'Uruguay', -3),
(240, 'USA / Canada', -6),
(229, 'Uzbekistan', 5),
(230, 'Vanuatu', 11),
(231, 'Vatican City', 1),
(232, 'Venezuela', -4),
(233, 'Vietnam', 7),
(234, 'Wallis And Futuna Islands', 12),
(235, 'Yemen', 3),
(236, 'Yugoslavia', 1),
(237, 'Zaire', 2),
(238, 'Zambia', 2),
(239, 'Zimbabwe', 2);

-- --------------------------------------------------------

--
-- Table structure for table `customeraccess`
--

CREATE TABLE `customeraccess` (
  `CustomerAccessLogID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `LogType` varchar(255) DEFAULT NULL,
  `LogDate` datetime DEFAULT NULL,
  `AccessName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customeraccess`
--

INSERT INTO `customeraccess` (`CustomerAccessLogID`, `CustomerID`, `ClientID`, `EmployeeID`, `LogType`, `LogDate`, `AccessName`) VALUES
(1, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 15:43:53', 'Redman Chiro'),
(2, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 15:44:08', 'Redman Chiro'),
(3, 1, 3, 0, 'Accessed Customer Products', '2017-02-07 15:44:12', 'Redman Chiro'),
(4, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 16:11:14', 'Redman Chiro'),
(5, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:11:17', 'Redman Chiro'),
(6, 1, 3, 0, 'Added Customer Invoice INV0000001', '2017-02-07 16:11:33', 'Redman Chiro'),
(7, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:13:07', 'Redman Chiro'),
(8, 1, 3, 0, 'Accessed Customer Contacts', '2017-02-07 16:13:08', 'Redman Chiro'),
(9, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:13:15', 'Redman Chiro'),
(10, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:13:58', 'Redman Chiro'),
(11, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 16:14:05', 'Redman Chiro'),
(12, 1, 3, 0, 'Accessed Customer Profile', '2017-02-07 16:15:28', 'Redman Chiro'),
(13, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 16:15:30', 'Redman Chiro'),
(14, 1, 3, 0, 'Accessed Customer Email Logs', '2017-02-07 16:15:34', 'Redman Chiro'),
(15, 1, 3, 0, 'Accessed Customer Email Logs', '2017-02-07 16:16:09', 'Redman Chiro'),
(16, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:16:12', 'Redman Chiro'),
(17, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:16:23', 'Redman Chiro'),
(18, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 16:33:46', 'Redman Chiro'),
(19, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:33:49', 'Redman Chiro'),
(20, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 16:33:57', 'Redman Chiro'),
(21, 1, 3, 0, 'Accessed Customer Summary', '2017-02-07 17:51:39', 'Redman Chiro'),
(22, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 17:51:43', 'Redman Chiro'),
(23, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-07 17:51:54', 'Redman Chiro'),
(24, 1, 3, 0, 'Accessed Customer Summary', '2017-02-08 07:23:55', 'Redman Chiro'),
(25, 1, 3, 0, 'Accessed Customer Summary', '2017-02-08 07:24:42', 'Redman Chiro'),
(26, 1, 3, 0, 'Accessed Customer Summary', '2017-02-24 05:06:26', 'Redman Chiro'),
(27, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:06:28', 'Redman Chiro'),
(28, 1, 3, 0, 'Accessed Customer Summary', '2017-02-24 05:07:54', 'Redman Chiro'),
(29, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:07:59', 'Redman Chiro'),
(30, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:11:04', 'Redman Chiro'),
(31, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:11:33', 'Redman Chiro'),
(32, 1, 3, 0, 'Added Customer Invoice INV0000002', '2017-02-24 05:11:46', 'Redman Chiro'),
(33, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:12:19', 'Redman Chiro'),
(34, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:13:59', 'Redman Chiro'),
(35, 1, 3, 0, 'Accessed Customer Summary', '2017-02-24 05:14:03', 'Redman Chiro'),
(36, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:19:53', 'Redman Chiro'),
(37, 1, 3, 0, 'Added Customer Invoice INV0000003', '2017-02-24 05:20:00', 'Redman Chiro'),
(38, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:20:59', 'Redman Chiro'),
(39, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:22:00', 'Redman Chiro'),
(40, 1, 3, 0, 'Added Customer Invoice INV0000004', '2017-02-24 05:23:40', 'Redman Chiro'),
(41, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:24:12', 'Redman Chiro'),
(42, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 05:24:47', 'Redman Chiro'),
(43, 1, 3, 0, 'Accessed Customer Summary', '2017-02-24 06:46:30', 'Redman Chiro'),
(44, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 06:46:32', 'Redman Chiro'),
(45, 1, 3, 0, 'Added Customer Invoice INV0000005', '2017-02-24 06:46:40', 'Redman Chiro'),
(46, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 06:47:45', 'Redman Chiro'),
(47, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 06:48:34', 'Redman Chiro'),
(48, 1, 3, 0, 'Accessed Customer Products', '2017-02-24 06:50:46', 'Redman Chiro'),
(49, 1, 3, 0, 'Accessed Customer Summary', '2017-02-24 13:05:35', 'Redman Chiro'),
(50, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 13:05:38', 'Redman Chiro'),
(51, 1, 3, 0, 'Added Customer Invoice INV0000006', '2017-02-24 13:05:46', 'Redman Chiro'),
(52, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 13:06:46', 'Redman Chiro'),
(53, 1, 3, 0, 'Accessed Customer Invoices', '2017-02-24 13:07:05', 'Redman Chiro'),
(54, 1, 3, 0, 'Accessed Customer Summary', '2017-03-09 09:47:39', 'Redman Chiro'),
(55, 1, 3, 0, 'Accessed Customer Invoices', '2017-03-09 09:47:42', 'Redman Chiro'),
(56, 1, 3, 0, 'Added Customer Invoice INV0000007', '2017-03-09 09:47:56', 'Redman Chiro'),
(57, 1, 3, 0, 'Accessed Customer Summary', '2017-03-09 09:55:08', 'Redman Chiro'),
(58, 1, 3, 0, 'Accessed Customer Summary', '2017-03-27 11:28:57', 'Redman Chiro'),
(59, 1, 3, 0, 'Accessed Customer Invoices', '2017-03-27 11:29:00', 'Redman Chiro'),
(60, 1, 3, 0, 'Added Customer Invoice INV0000008', '2017-03-27 11:29:06', 'Redman Chiro'),
(61, 1, 3, 0, 'Accessed Customer Invoices', '2017-03-27 11:29:58', 'Redman Chiro'),
(62, 1, 3, 0, 'Accessed Customer Summary', '2017-03-27 11:30:32', 'Redman Chiro'),
(63, 1, 3, 0, 'Accessed Customer Invoices', '2017-03-27 11:30:35', 'Redman Chiro');

-- --------------------------------------------------------

--
-- Table structure for table `customercontacts`
--

CREATE TABLE `customercontacts` (
  `ContactID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `EmailInvoice` int(11) DEFAULT '0',
  `EmailSupport` int(11) DEFAULT '0',
  `EmailQuotes` int(11) DEFAULT '0',
  `AddContacts` int(11) DEFAULT '0',
  `AcceptQuotes` int(11) DEFAULT '0',
  `ChangeDetails` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customercustomentries`
--

CREATE TABLE `customercustomentries` (
  `CustomerCustomValueID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldOptionID` int(11) DEFAULT NULL,
  `CustomOptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customercustomfields`
--

CREATE TABLE `customercustomfields` (
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text',
  `Required` int(11) DEFAULT '0',
  `DisplayOrder` int(11) DEFAULT NULL,
  `DisplayInvoice` int(11) NOT NULL DEFAULT '0',
  `DisplayQuote` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customercustomfieldsvalues`
--

CREATE TABLE `customercustomfieldsvalues` (
  `CustomFieldOptionID` int(11) NOT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerdocumentgroups`
--

CREATE TABLE `customerdocumentgroups` (
  `DocumentGroupID` int(11) NOT NULL,
  `GroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerdocuments`
--

CREATE TABLE `customerdocuments` (
  `DocumentID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `DocumentName` varchar(255) DEFAULT NULL,
  `DocumentFile` varchar(255) DEFAULT NULL,
  `DocumentType` varchar(255) DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `AddedByEmployeeID` int(11) DEFAULT NULL,
  `DocumentGroupID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerfollowups`
--

CREATE TABLE `customerfollowups` (
  `FollowUpID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `FollowUpDate` date NOT NULL,
  `Outcome` text,
  `ClientID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `Status` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerinvoicegroups`
--

CREATE TABLE `customerinvoicegroups` (
  `InvoiceGroupID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `GroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerinvoicelines`
--

CREATE TABLE `customerinvoicelines` (
  `InvoiceLineItemID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `ProductCode` varchar(255) DEFAULT NULL,
  `LineSubTotal` double DEFAULT NULL,
  `LineDiscount` double DEFAULT NULL,
  `LineVAT` double DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MeassurementDescription` varchar(255) DEFAULT NULL,
  `UnitPrice` double DEFAULT NULL,
  `Profit` double DEFAULT NULL,
  `UnitPriceCost` double DEFAULT NULL,
  `WarehouseID` int(11) NOT NULL DEFAULT '1',
  `GroupID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerinvoicelines`
--

INSERT INTO `customerinvoicelines` (`InvoiceLineItemID`, `InvoiceID`, `Description`, `Quantity`, `Price`, `ProductID`, `ProductCode`, `LineSubTotal`, `LineDiscount`, `LineVAT`, `LineTotal`, `BillingType`, `StockAffect`, `MeassurementDescription`, `UnitPrice`, `Profit`, `UnitPriceCost`, `WarehouseID`, `GroupID`) VALUES
(1, 1, 'Chiro Session - Chiro Session 1', 1, 380, 1, 'RC001', 380, 0, 0, 380, 'Once-Off', 1, '1', 380, 380, 0, 0, 0),
(2, 2, 'Consultation - ICD10 CODE :  M99.13   DC04301 - CONSULTATION', 1, 170, 2, 'Consultation', 170, 0, 0, 170, 'Once-Off', 1, '1', 170, 170, 0, 1, 0),
(3, 3, 'CONSULTATION - CONSULTATION', 1, 170, 2, 'ICD10 CODE : M99.13 DC04301 ', 170, 0, 0, 170, 'Once-Off', 1, '1', 170, 170, 0, 1, 0),
(4, 3, 'TREATMENT PROCEDURE  - TREATMENT PROCEDURE   ', 1, 180, 3, 'ICD10 CODE :  M99.13   DC04335', 180, 0, 0, 180, 'Once-Off', 1, '1', 180, 180, 0, 1, 0),
(5, 3, 'DIAGNOSTIC PROCEDURE - DIAGNOSTIC PROCEDURE', 1, 90, 4, 'ICD10 CODE :  M99.13   DC04312', 90, 0, 0, 90, 'Once-Off', 1, '1', 90, 90, 0, 1, 0),
(6, 4, 'ICD10 CODE :  M99.13   DC04312 - DIAGNOSTIC PROCEDURE', 1, 90, 4, 'ICD10 CODE :  M99.13   DC04312', 90, 0, 0, 90, 'Once-Off', 1, '1', 90, 90, 0, 1, 0),
(7, 5, 'Product Name - Product Description', 1, 95, 5, 'Product Code', 95, 0, 0, 95, 'Once-Off', 1, '1', 95, 95, 0, 1, 0),
(8, 6, 'CONSULTATION - ICD10 CODE : M99.13 DC04301', 1, 170, 2, 'ICD10 CODE : M99.13 DC04301 ', 170, 0, 0, 170, 'Once-Off', 1, '1', 170, 170, 0, 1, 0),
(9, 6, 'TREATMENT PROCEDURE  - ICD10 CODE :  M99.13   DC04335', 1, 180, 3, 'ICD10 CODE :  M99.13   DC04335', 180, 0, 0, 180, 'Once-Off', 1, '1', 180, 180, 0, 1, 0),
(10, 6, 'DIAGNOSTIC PROCEDURE - ICD10 CODE :  M99.13   DC04312', 1, 90, 4, 'ICD10 CODE :  M99.13   DC04312', 90, 0, 0, 90, 'Once-Off', 1, '1', 90, 90, 0, 1, 0),
(11, 8, 'DIAGNOSTIC PROCEDURE - ICD10 CODE :  M99.13   DC04312', 1, 90, 4, 'ICD10 CODE :  M99.13   DC04312', 90, 0, 0, 90, 'Once-Off', 1, '1', 90, 90, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customerinvoicepayments`
--

CREATE TABLE `customerinvoicepayments` (
  `InvoicePaymentID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `TransactionID` int(11) NOT NULL,
  `PaymentAmount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerinvoices`
--

CREATE TABLE `customerinvoices` (
  `InvoiceID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `DiscountPercent` double DEFAULT NULL,
  `InvoiceStatus` int(11) DEFAULT '1',
  `Taxed` int(11) DEFAULT NULL,
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `InvoiceNotes` text,
  `SentToCustomer` int(11) DEFAULT '0',
  `SentToCustomerDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerinvoices`
--

INSERT INTO `customerinvoices` (`InvoiceID`, `CustomerID`, `InvoiceNumber`, `InvoiceDate`, `DueDate`, `DiscountPercent`, `InvoiceStatus`, `Taxed`, `AddedByClient`, `AddedByEmployee`, `AddedByName`, `Address1`, `Address2`, `City`, `State`, `PostCode`, `CountryID`, `InvoiceNotes`, `SentToCustomer`, `SentToCustomerDate`) VALUES
(1, 1, 'INV0000001', '2017-02-07', '2017-02-14', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(2, 1, 'INV0000002', '2017-02-24', '2017-03-03', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(3, 1, 'INV0000003', '2017-02-24', '2017-03-03', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(4, 1, 'INV0000004', '2017-02-24', '2017-03-03', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(5, 1, 'INV0000005', '2017-02-24', '2017-03-03', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(6, 1, 'INV0000006', '2017-02-24', '2017-03-03', 0, 1, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(7, 1, 'INV0000007', '2017-03-09', '2017-03-16', 0, 0, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, NULL, 0, NULL),
(8, 1, 'INV0000008', '2017-03-27', '2017-04-03', 0, 0, 1, 3, 0, 'Redman Chiro', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customerlogs`
--

CREATE TABLE `customerlogs` (
  `CustomerLogID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `LogText` text,
  `LogAdded` datetime DEFAULT NULL,
  `AddedByClientID` int(11) DEFAULT NULL,
  `AddedByEmployeeID` int(11) DEFAULT '0',
  `AddedByName` varchar(255) DEFAULT NULL,
  `LogType` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customernotes`
--

CREATE TABLE `customernotes` (
  `NoteID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Note` text NOT NULL,
  `DateAdded` date DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `StickyNote` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerproducts`
--

CREATE TABLE `customerproducts` (
  `ClientProductID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductCostID` int(11) NOT NULL,
  `FirstBillingDate` date DEFAULT NULL,
  `NextBillingDate` date DEFAULT NULL,
  `ProductDateAdded` date DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `ClientProductStatus` int(11) DEFAULT '2',
  `ProductName` varchar(255) DEFAULT NULL,
  `ProductQuantity` double DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `RecurringTimes` int(11) DEFAULT '0',
  `RecurredTimes` int(11) DEFAULT '0',
  `WarehouseID` int(11) DEFAULT '1',
  `RecurringAmount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerquotelines`
--

CREATE TABLE `customerquotelines` (
  `QuoteLineItemID` int(11) NOT NULL,
  `QuoteID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `ProductCode` varchar(255) DEFAULT NULL,
  `LineSubTotal` double DEFAULT NULL,
  `LineDiscount` double DEFAULT NULL,
  `LineVAT` double DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MeassurementDescription` varchar(255) DEFAULT NULL,
  `ProductCostID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerquotes`
--

CREATE TABLE `customerquotes` (
  `QuoteID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `QuoteNumber` varchar(255) DEFAULT NULL,
  `QuoteDate` date DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `DiscountPercent` double DEFAULT NULL,
  `QuoteStatus` int(11) DEFAULT '1',
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `ProposalText` text NOT NULL,
  `FooterText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerrecurring`
--

CREATE TABLE `customerrecurring` (
  `RecurringID` int(11) NOT NULL,
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
  `AddedByName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerrecurringlines`
--

CREATE TABLE `customerrecurringlines` (
  `RecurringLineItemID` int(11) NOT NULL,
  `RecurringID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `LineTotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
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
  `DepositReference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `FirstName`, `Surname`, `CompanyName`, `ContactNumber`, `EmailAddress`, `Address1`, `Address2`, `City`, `Region`, `PostCode`, `CountryID`, `TaxExempt`, `OverdueNotices`, `MarketingEmails`, `PaymentMethod`, `Status`, `VatNumber`, `AdminNotes`, `DateAdded`, `DepositReference`) VALUES
(1, 'Jonathan', 'Hornsby', '', '0837899871', 'jono@hornsby.co.za', '1A Shirley Ave Gillitts', '', 'Gillitts', 'KZN', '3610', 192, 0, 0, 1, 'EFT', 2, '', '', '2017-02-07 15:43:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customersites`
--

CREATE TABLE `customersites` (
  `SiteID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `SiteName` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `ContactPerson` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customertask`
--

CREATE TABLE `customertask` (
  `TaskID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `TaskDescription` text NOT NULL,
  `TaskDate` date NOT NULL,
  `ClientID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `Status` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customertransactions`
--

CREATE TABLE `customertransactions` (
  `TransactionID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `PaymentDate` date DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `TotalPayment` double DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `PaymentMethod` varchar(255) DEFAULT NULL,
  `TransactionReference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employeedepartments`
--

CREATE TABLE `employeedepartments` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employeesecuritygroups`
--

CREATE TABLE `employeesecuritygroups` (
  `EmployeeSecurityGroupID` int(11) NOT NULL,
  `SecurityGroupID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobcardfields`
--

CREATE TABLE `jobcardfields` (
  `JobcardFieldID` int(11) NOT NULL,
  `JobcardTableID` int(11) NOT NULL,
  `FieldName` varchar(255) NOT NULL,
  `Position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobcardfields`
--

INSERT INTO `jobcardfields` (`JobcardFieldID`, `JobcardTableID`, `FieldName`, `Position`) VALUES
(1, 1, 'Item', 1),
(2, 1, 'Size', 2),
(3, 1, 'No.', 3),
(4, 1, 'Item2', 4),
(5, 1, 'Size2', 5),
(6, 1, 'No.2', 6),
(7, 2, 'Left Column', 1),
(8, 2, 'Right Column', 2),
(9, 3, 'COLUMN', 1),
(10, 4, 'Left Column', 1),
(11, 4, 'Right Column', 2);

-- --------------------------------------------------------

--
-- Table structure for table `jobcardinputlines`
--

CREATE TABLE `jobcardinputlines` (
  `JobcardInputLineID` int(11) NOT NULL,
  `JobcardTableID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobcardinputlines`
--

INSERT INTO `jobcardinputlines` (`JobcardInputLineID`, `JobcardTableID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 3),
(26, 3),
(27, 3),
(28, 4),
(29, 4),
(30, 4),
(31, 4);

-- --------------------------------------------------------

--
-- Table structure for table `jobcardinputlinevalues`
--

CREATE TABLE `jobcardinputlinevalues` (
  `InputID` int(11) NOT NULL,
  `JobcardTableID` int(11) DEFAULT NULL,
  `JobcardInputLineID` int(11) NOT NULL,
  `JobcardFieldID` int(11) DEFAULT NULL,
  `InputValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobcardinputlinevalues`
--

INSERT INTO `jobcardinputlinevalues` (`InputID`, `JobcardTableID`, `JobcardInputLineID`, `JobcardFieldID`, `InputValue`) VALUES
(1, 1, 1, 1, 'FAN'),
(2, 1, 1, 2, ''),
(3, 1, 1, 3, ''),
(4, 1, 1, 4, 'OVER LOAD'),
(5, 1, 1, 5, ''),
(6, 1, 1, 6, ''),
(7, 1, 2, 1, 'CONTRACTOR'),
(8, 1, 2, 2, ''),
(9, 1, 2, 3, ''),
(10, 1, 2, 4, 'DRAIN HEATER'),
(11, 1, 2, 5, ''),
(12, 1, 2, 6, ''),
(13, 1, 3, 1, 'DOORSEALS'),
(14, 1, 3, 2, ''),
(15, 1, 3, 3, ''),
(16, 1, 3, 4, 'BREAKER'),
(17, 1, 3, 5, ''),
(18, 1, 3, 6, ''),
(19, 1, 4, 1, 'DRIER'),
(20, 1, 4, 2, ''),
(21, 1, 4, 3, ''),
(22, 1, 4, 4, 'HEATERS'),
(23, 1, 4, 5, ''),
(24, 1, 4, 6, ''),
(25, 1, 5, 1, 'EXPANSION VALVE'),
(26, 1, 5, 2, 'N/A'),
(27, 1, 5, 3, ''),
(28, 1, 5, 4, 'REFRIGERANT'),
(29, 1, 5, 5, ''),
(30, 1, 5, 6, ''),
(31, 1, 6, 1, 'THERMOSTAT'),
(32, 1, 6, 2, 'N/A'),
(33, 1, 6, 3, ''),
(34, 1, 6, 4, 'HP / LP'),
(35, 1, 6, 5, ''),
(36, 1, 6, 6, ''),
(37, 1, 7, 1, 'TIMER'),
(38, 1, 7, 2, ''),
(39, 1, 7, 3, ''),
(40, 1, 7, 4, 'OIL FAILURE'),
(41, 1, 7, 5, ''),
(42, 1, 7, 6, ''),
(43, 1, 8, 1, 'CHEMICAL'),
(44, 1, 8, 2, 'N/A'),
(45, 1, 8, 3, ''),
(46, 1, 8, 4, 'ELEC THERMOSTAT'),
(47, 1, 8, 5, ''),
(48, 1, 8, 6, ''),
(49, 1, 9, 1, 'SILVER SOLDIER'),
(50, 1, 9, 2, 'N/A'),
(51, 1, 9, 3, ''),
(52, 1, 9, 4, 'SOLENOID VALVE'),
(53, 1, 9, 5, ''),
(54, 1, 9, 6, ''),
(55, 1, 10, 1, 'COPPER WELDING'),
(56, 1, 10, 2, 'N/A'),
(57, 1, 10, 3, ''),
(58, 1, 10, 4, 'OIL POLYESTER'),
(59, 1, 10, 5, ''),
(60, 1, 10, 6, ''),
(61, 1, 11, 1, 'WELDING'),
(62, 1, 11, 2, 'N/A'),
(63, 1, 11, 3, ''),
(64, 1, 11, 4, 'MINERAL OIL'),
(65, 1, 11, 5, ''),
(66, 1, 11, 6, ''),
(67, 1, 12, 1, 'ARMERFLEX'),
(68, 1, 12, 2, ''),
(69, 1, 12, 3, ''),
(70, 1, 12, 4, 'CAPILARY TAILS'),
(71, 1, 12, 5, ''),
(72, 1, 12, 6, ''),
(73, 1, 13, 1, 'PIPE COPPER'),
(74, 1, 13, 2, ''),
(75, 1, 13, 3, ''),
(76, 1, 13, 4, 'CABLE'),
(77, 1, 13, 5, ''),
(78, 1, 13, 6, ''),
(79, 1, 14, 1, 'PLASTIC PIPE'),
(80, 1, 14, 2, ''),
(81, 1, 14, 3, ''),
(82, 1, 14, 4, 'JUNCTION BOX'),
(83, 1, 14, 5, ''),
(84, 1, 14, 6, ''),
(85, 1, 15, 1, 'PLASTIC ELBOWS'),
(86, 1, 15, 2, ''),
(87, 1, 15, 3, ''),
(88, 1, 15, 4, 'FAN BLADES'),
(89, 1, 15, 5, ''),
(90, 1, 15, 6, ''),
(91, 1, 16, 1, 'GLUE'),
(92, 1, 16, 2, 'N/A'),
(93, 1, 16, 3, ''),
(94, 1, 16, 4, 'SUNDRIES'),
(95, 1, 16, 5, ''),
(96, 1, 16, 6, ''),
(97, 1, 17, 1, 'BALLEST'),
(98, 1, 17, 2, ''),
(99, 1, 17, 3, ''),
(100, 1, 17, 4, 'COPPER ELBOWS'),
(101, 1, 17, 5, ''),
(102, 1, 17, 6, ''),
(103, 1, 18, 1, 'STARTERS'),
(104, 1, 18, 2, ''),
(105, 1, 18, 3, ''),
(106, 1, 18, 4, 'COPPER COUPLINGS'),
(107, 1, 18, 5, ''),
(108, 1, 18, 6, ''),
(109, 1, 19, 1, 'GLOBES'),
(110, 1, 19, 2, ''),
(111, 1, 19, 3, ''),
(112, 1, 19, 4, 'PLASTIC COUPLINGS'),
(113, 1, 19, 5, ''),
(114, 1, 19, 6, ''),
(115, 1, 20, 1, 'CONTRACTOR'),
(116, 1, 20, 2, ''),
(117, 1, 20, 3, ''),
(118, 1, 20, 4, 'LIGHT FITTINGS'),
(119, 1, 20, 5, ''),
(120, 1, 20, 6, ''),
(121, 2, 21, 7, 'OUTDOOR MODE NO.'),
(122, 2, 21, 8, 'BRF NO.                       TAG NO.'),
(123, 2, 22, 7, 'SERIAL NO.'),
(124, 2, 22, 8, 'BRF NO.                       TAG NO.'),
(125, 2, 23, 7, 'INDOOR MODEL NO.'),
(126, 2, 23, 8, 'DEPARTMENTS'),
(127, 2, 24, 7, 'SERIAL NO.'),
(128, 2, 24, 8, 'FRIDGE / AC TYPE'),
(129, 3, 25, 9, 'FAULT:'),
(130, 3, 26, 9, 'REASON:'),
(131, 3, 27, 9, ''),
(132, 4, 28, 10, 'OTHER:'),
(133, 4, 28, 11, 'TIME IN:                       TIME OUT:'),
(134, 4, 29, 10, 'WAS FRIDGE OFF LOADED:'),
(135, 4, 29, 11, 'TECHNICIAN:'),
(136, 4, 30, 10, 'WAS THERE A DELAY:'),
(137, 4, 30, 11, 'ORDER NO.:'),
(138, 4, 31, 10, 'WAS FRIDGE / AC CLEAN:'),
(139, 4, 31, 11, 'CUSTOMER SIGNATURE:');

-- --------------------------------------------------------

--
-- Table structure for table `jobcards`
--

CREATE TABLE `jobcards` (
  `JobcardID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `AssignedTo` int(11) DEFAULT NULL,
  `JobcardFile` varchar(255) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `InvoiceID` int(11) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateScheduled` date DEFAULT NULL,
  `DateTechReport` date DEFAULT NULL,
  `DateInvoice` date DEFAULT NULL,
  `JobcardNotes` text,
  `JobcardStatus` int(11) DEFAULT '0',
  `TechReport` text,
  `ManualJobcardNumber` varchar(255) DEFAULT NULL,
  `TotalTime` varchar(255) DEFAULT NULL,
  `SiteID` int(11) NOT NULL DEFAULT '0',
  `WorkOrder` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobcardtables`
--

CREATE TABLE `jobcardtables` (
  `JobcardTableID` int(11) NOT NULL,
  `TableHeading` varchar(11) DEFAULT NULL,
  `TablePosition` int(11) DEFAULT NULL,
  `ShowHeading` int(11) DEFAULT '0',
  `ShowLines` int(11) DEFAULT '1',
  `ShowTableHeadings` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobcardtables`
--

INSERT INTO `jobcardtables` (`JobcardTableID`, `TableHeading`, `TablePosition`, `ShowHeading`, `ShowLines`, `ShowTableHeadings`) VALUES
(1, 'Item List', 1, 1, 1, 1),
(2, 'Item List 2', 2, 0, 1, 0),
(3, 'FAULT DESCR', 3, 0, 1, 0),
(4, 'Other', 4, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productcost`
--

CREATE TABLE `productcost` (
  `ProductCostID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ClientCost` double DEFAULT NULL,
  `MeasurementID` int(11) DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT 'Monthly',
  `ProRataBilling` int(11) DEFAULT NULL,
  `PackSize` int(11) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MinimumOrder` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productcost`
--

INSERT INTO `productcost` (`ProductCostID`, `ProductID`, `ClientCost`, `MeasurementID`, `BillingType`, `ProRataBilling`, `PackSize`, `StockAffect`, `MinimumOrder`) VALUES
(1, 1, 380, 0, 'Once-Off', 0, 1, 1, 0),
(2, 2, 170, 0, 'Once-Off', 0, 1, 1, 0),
(3, 3, 180, 0, 'Once-Off', 0, 1, 1, 0),
(4, 4, 90, 0, 'Once-Off', 0, 1, 1, 0),
(5, 5, 95, 0, 'Once-Off', 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productcustomentries`
--

CREATE TABLE `productcustomentries` (
  `ProductCustomValueID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldOptionID` int(11) DEFAULT NULL,
  `CustomOptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productcustomfields`
--

CREATE TABLE `productcustomfields` (
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text',
  `Required` int(11) DEFAULT '0',
  `DisplayOrder` int(11) DEFAULT NULL,
  `ShowInvoice` int(11) NOT NULL DEFAULT '0',
  `ShowQuote` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productcustomfieldsvalues`
--

CREATE TABLE `productcustomfieldsvalues` (
  `CustomFieldOptionID` int(11) NOT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productgroups`
--

CREATE TABLE `productgroups` (
  `ProductGroupID` int(11) NOT NULL,
  `GroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productgroups`
--

INSERT INTO `productgroups` (`ProductGroupID`, `GroupName`) VALUES
(1, 'Treatment');

-- --------------------------------------------------------

--
-- Table structure for table `productimages`
--

CREATE TABLE `productimages` (
  `ProductImageID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productmeasurement`
--

CREATE TABLE `productmeasurement` (
  `MeasurementID` int(11) NOT NULL,
  `MeasurementDescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) DEFAULT NULL,
  `ProductGroupID` int(11) DEFAULT NULL,
  `ProductSubGroupID` int(11) DEFAULT '0',
  `IsStockItem` int(11) DEFAULT '0',
  `ProductCode` varchar(255) DEFAULT NULL,
  `ProductDescription` text,
  `MinimumOrder` int(11) DEFAULT '0',
  `ShowInCatalog` int(11) DEFAULT '0',
  `WarrantyMonths` int(11) DEFAULT '0',
  `ProductSerialNumber` varchar(255) DEFAULT NULL,
  `ProductStatus` int(11) DEFAULT '2',
  `MinimumStock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `ProductGroupID`, `ProductSubGroupID`, `IsStockItem`, `ProductCode`, `ProductDescription`, `MinimumOrder`, `ShowInCatalog`, `WarrantyMonths`, `ProductSerialNumber`, `ProductStatus`, `MinimumStock`) VALUES
(1, 'Chiro Session', 1, 0, 0, 'RC001', 'Chiro Session 1', 0, 0, 0, '', 1, 0),
(2, 'CONSULTATION', 1, 0, 0, 'ICD10 CODE : M99.13 DC04301 ', 'ICD10 CODE : M99.13 DC04301', 0, 1, 0, '', 2, 0),
(3, 'TREATMENT PROCEDURE ', 1, 0, 0, 'ICD10 CODE :  M99.13   DC04335', 'ICD10 CODE :  M99.13   DC04335', 0, 1, 0, '', 2, 0),
(4, 'DIAGNOSTIC PROCEDURE', 1, 0, 0, 'ICD10 CODE :  M99.13   DC04312', 'ICD10 CODE :  M99.13   DC04312', 0, 1, 0, '', 2, 0),
(5, 'Product Name', 1, 0, 0, 'Product Code', 'Product Description', 0, 1, 0, '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productstock`
--

CREATE TABLE `productstock` (
  `StockID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Stock` int(11) DEFAULT NULL,
  `DateAdded` datetime DEFAULT NULL,
  `StockType` varchar(255) DEFAULT NULL,
  `UnitCost` double DEFAULT NULL,
  `SupplierInvoiceID` int(11) DEFAULT '0',
  `InvoiceID` int(11) DEFAULT '0',
  `WarehouseID` int(11) NOT NULL DEFAULT '1',
  `MovedFrom` int(11) DEFAULT NULL,
  `StockTakeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productstock`
--

INSERT INTO `productstock` (`StockID`, `ProductID`, `Stock`, `DateAdded`, `StockType`, `UnitCost`, `SupplierInvoiceID`, `InvoiceID`, `WarehouseID`, `MovedFrom`, `StockTakeID`) VALUES
(1, 1, -1, '2017-02-07 00:00:00', 'Sell', 0, 0, 1, 1, NULL, NULL),
(2, 2, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 2, 1, NULL, NULL),
(3, 2, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 3, 1, NULL, NULL),
(4, 3, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 3, 1, NULL, NULL),
(5, 4, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 3, 1, NULL, NULL),
(6, 4, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 4, 1, NULL, NULL),
(7, 5, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 5, 1, NULL, NULL),
(8, 2, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 6, 1, NULL, NULL),
(9, 3, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 6, 1, NULL, NULL),
(10, 4, -1, '2017-02-24 00:00:00', 'Sell', 0, 0, 6, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `productsubgroups`
--

CREATE TABLE `productsubgroups` (
  `ProductSubGroupID` int(11) NOT NULL,
  `ProductGroupID` int(11) DEFAULT NULL,
  `SubGroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorderlines`
--

CREATE TABLE `purchaseorderlines` (
  `PurchaseLineItemID` int(11) NOT NULL,
  `PurchaseID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `ProductCode` varchar(255) DEFAULT NULL,
  `LineSubTotal` double DEFAULT NULL,
  `LineDiscount` double DEFAULT NULL,
  `LineVAT` double DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MeassurementDescription` varchar(255) DEFAULT NULL,
  `SupplierCostID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorders`
--

CREATE TABLE `purchaseorders` (
  `PurchaseID` int(11) NOT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `PurchaseNumber` varchar(255) DEFAULT NULL,
  `PurchaseOrderDate` date DEFAULT NULL,
  `PurchaseStatus` int(11) DEFAULT '1',
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `SentDate` date DEFAULT NULL,
  `DeliveryType` varchar(255) DEFAULT NULL,
  `SpecialInstructions` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups`
--

CREATE TABLE `securitygroups` (
  `SecurityGroupID` int(11) NOT NULL,
  `SecurityGroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroupsettings`
--

CREATE TABLE `securitygroupsettings` (
  `SecurityGroupSettingsID` int(11) NOT NULL,
  `SecurityGroupID` int(11) NOT NULL,
  `SubModuleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stocktakes`
--

CREATE TABLE `stocktakes` (
  `StockTakeID` int(11) NOT NULL,
  `WarehouseID` int(11) NOT NULL,
  `StockTakeDate` datetime DEFAULT NULL,
  `StockTakeStatus` int(11) DEFAULT '0',
  `StockTakeCompleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliercost`
--

CREATE TABLE `suppliercost` (
  `SupplierCostID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `SupplierCost` double DEFAULT NULL,
  `MeasurementID` int(11) DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT NULL,
  `ProRataBilling` int(11) DEFAULT NULL,
  `PackSize` int(11) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MinimumOrder` int(11) DEFAULT NULL,
  `PricePerUnit` double DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliercostingtracking`
--

CREATE TABLE `suppliercostingtracking` (
  `SupplierCostingID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `SupplierCostID` int(11) DEFAULT NULL,
  `SupplierCost` double DEFAULT NULL,
  `UnitCost` double DEFAULT NULL,
  `PriceDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplierorderlines`
--

CREATE TABLE `supplierorderlines` (
  `SupplierInvoiceLineItemID` int(11) NOT NULL,
  `SupplierInvoiceID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity` double DEFAULT NULL,
  `Price` double DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `ProductCode` varchar(255) DEFAULT NULL,
  `LineSubTotal` double DEFAULT NULL,
  `LineDiscount` double DEFAULT NULL,
  `LineVAT` double DEFAULT NULL,
  `LineTotal` double DEFAULT NULL,
  `BillingType` varchar(255) DEFAULT NULL,
  `StockAffect` int(11) DEFAULT NULL,
  `MeassurementDescription` varchar(255) DEFAULT NULL,
  `SupplierCostID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplierorders`
--

CREATE TABLE `supplierorders` (
  `SupplierInvoiceID` int(11) NOT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `PurchaseOrderID` int(255) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `InvoiceStatus` int(11) DEFAULT '1',
  `AddedByClient` int(11) DEFAULT NULL,
  `AddedByEmployee` int(11) DEFAULT NULL,
  `AddedByName` varchar(255) DEFAULT NULL,
  `PurchaseNumber` varchar(255) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `InvoiceFile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplierproducts`
--

CREATE TABLE `supplierproducts` (
  `SupplierProductID` int(11) NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(255) DEFAULT NULL,
  `SupplierEmail` varchar(255) DEFAULT NULL,
  `SupplierTel` varchar(255) DEFAULT NULL,
  `SupplierFax` varchar(255) DEFAULT NULL,
  `SupplierContact` varchar(255) DEFAULT NULL,
  `SupplierVat` varchar(255) DEFAULT NULL,
  `SupplierAddress1` varchar(255) DEFAULT NULL,
  `SupplierAddress2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `PostCode` varchar(255) DEFAULT NULL,
  `CountryID` int(11) DEFAULT NULL,
  `SupplierNote` text,
  `SupplierStatus` int(11) DEFAULT '1',
  `ChargesVAT` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `WarehouseID` int(11) NOT NULL,
  `WarehouseName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`WarehouseID`, `WarehouseName`) VALUES
(1, 'Main');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companysettings`
--
ALTER TABLE `companysettings`
  ADD PRIMARY KEY (`SettingsID`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`CountryID`);

--
-- Indexes for table `customeraccess`
--
ALTER TABLE `customeraccess`
  ADD PRIMARY KEY (`CustomerAccessLogID`);

--
-- Indexes for table `customercontacts`
--
ALTER TABLE `customercontacts`
  ADD PRIMARY KEY (`ContactID`);

--
-- Indexes for table `customercustomentries`
--
ALTER TABLE `customercustomentries`
  ADD PRIMARY KEY (`CustomerCustomValueID`);

--
-- Indexes for table `customercustomfields`
--
ALTER TABLE `customercustomfields`
  ADD PRIMARY KEY (`CustomFieldID`);

--
-- Indexes for table `customercustomfieldsvalues`
--
ALTER TABLE `customercustomfieldsvalues`
  ADD PRIMARY KEY (`CustomFieldOptionID`);

--
-- Indexes for table `customerdocumentgroups`
--
ALTER TABLE `customerdocumentgroups`
  ADD PRIMARY KEY (`DocumentGroupID`);

--
-- Indexes for table `customerdocuments`
--
ALTER TABLE `customerdocuments`
  ADD PRIMARY KEY (`DocumentID`);

--
-- Indexes for table `customerfollowups`
--
ALTER TABLE `customerfollowups`
  ADD PRIMARY KEY (`FollowUpID`);

--
-- Indexes for table `customerinvoicegroups`
--
ALTER TABLE `customerinvoicegroups`
  ADD PRIMARY KEY (`InvoiceGroupID`);

--
-- Indexes for table `customerinvoicelines`
--
ALTER TABLE `customerinvoicelines`
  ADD PRIMARY KEY (`InvoiceLineItemID`);

--
-- Indexes for table `customerinvoicepayments`
--
ALTER TABLE `customerinvoicepayments`
  ADD PRIMARY KEY (`InvoicePaymentID`);

--
-- Indexes for table `customerinvoices`
--
ALTER TABLE `customerinvoices`
  ADD PRIMARY KEY (`InvoiceID`);

--
-- Indexes for table `customerlogs`
--
ALTER TABLE `customerlogs`
  ADD PRIMARY KEY (`CustomerLogID`);

--
-- Indexes for table `customernotes`
--
ALTER TABLE `customernotes`
  ADD PRIMARY KEY (`NoteID`);

--
-- Indexes for table `customerproducts`
--
ALTER TABLE `customerproducts`
  ADD PRIMARY KEY (`ClientProductID`);

--
-- Indexes for table `customerquotelines`
--
ALTER TABLE `customerquotelines`
  ADD PRIMARY KEY (`QuoteLineItemID`);

--
-- Indexes for table `customerquotes`
--
ALTER TABLE `customerquotes`
  ADD PRIMARY KEY (`QuoteID`);

--
-- Indexes for table `customerrecurring`
--
ALTER TABLE `customerrecurring`
  ADD PRIMARY KEY (`RecurringID`);

--
-- Indexes for table `customerrecurringlines`
--
ALTER TABLE `customerrecurringlines`
  ADD PRIMARY KEY (`RecurringLineItemID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `customersites`
--
ALTER TABLE `customersites`
  ADD PRIMARY KEY (`SiteID`);

--
-- Indexes for table `customertask`
--
ALTER TABLE `customertask`
  ADD PRIMARY KEY (`TaskID`);

--
-- Indexes for table `customertransactions`
--
ALTER TABLE `customertransactions`
  ADD PRIMARY KEY (`TransactionID`);

--
-- Indexes for table `employeedepartments`
--
ALTER TABLE `employeedepartments`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `employeesecuritygroups`
--
ALTER TABLE `employeesecuritygroups`
  ADD PRIMARY KEY (`EmployeeSecurityGroupID`);

--
-- Indexes for table `jobcardfields`
--
ALTER TABLE `jobcardfields`
  ADD PRIMARY KEY (`JobcardFieldID`);

--
-- Indexes for table `jobcardinputlines`
--
ALTER TABLE `jobcardinputlines`
  ADD PRIMARY KEY (`JobcardInputLineID`);

--
-- Indexes for table `jobcardinputlinevalues`
--
ALTER TABLE `jobcardinputlinevalues`
  ADD PRIMARY KEY (`InputID`);

--
-- Indexes for table `jobcards`
--
ALTER TABLE `jobcards`
  ADD PRIMARY KEY (`JobcardID`);

--
-- Indexes for table `jobcardtables`
--
ALTER TABLE `jobcardtables`
  ADD PRIMARY KEY (`JobcardTableID`);

--
-- Indexes for table `productcost`
--
ALTER TABLE `productcost`
  ADD PRIMARY KEY (`ProductCostID`);

--
-- Indexes for table `productcustomentries`
--
ALTER TABLE `productcustomentries`
  ADD PRIMARY KEY (`ProductCustomValueID`);

--
-- Indexes for table `productcustomfields`
--
ALTER TABLE `productcustomfields`
  ADD PRIMARY KEY (`CustomFieldID`);

--
-- Indexes for table `productcustomfieldsvalues`
--
ALTER TABLE `productcustomfieldsvalues`
  ADD PRIMARY KEY (`CustomFieldOptionID`);

--
-- Indexes for table `productgroups`
--
ALTER TABLE `productgroups`
  ADD PRIMARY KEY (`ProductGroupID`);

--
-- Indexes for table `productimages`
--
ALTER TABLE `productimages`
  ADD PRIMARY KEY (`ProductImageID`);

--
-- Indexes for table `productmeasurement`
--
ALTER TABLE `productmeasurement`
  ADD PRIMARY KEY (`MeasurementID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `productstock`
--
ALTER TABLE `productstock`
  ADD PRIMARY KEY (`StockID`);

--
-- Indexes for table `productsubgroups`
--
ALTER TABLE `productsubgroups`
  ADD PRIMARY KEY (`ProductSubGroupID`);

--
-- Indexes for table `purchaseorderlines`
--
ALTER TABLE `purchaseorderlines`
  ADD PRIMARY KEY (`PurchaseLineItemID`);

--
-- Indexes for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  ADD PRIMARY KEY (`PurchaseID`);

--
-- Indexes for table `securitygroups`
--
ALTER TABLE `securitygroups`
  ADD PRIMARY KEY (`SecurityGroupID`);

--
-- Indexes for table `securitygroupsettings`
--
ALTER TABLE `securitygroupsettings`
  ADD PRIMARY KEY (`SecurityGroupSettingsID`);

--
-- Indexes for table `stocktakes`
--
ALTER TABLE `stocktakes`
  ADD PRIMARY KEY (`StockTakeID`);

--
-- Indexes for table `suppliercost`
--
ALTER TABLE `suppliercost`
  ADD PRIMARY KEY (`SupplierCostID`);

--
-- Indexes for table `suppliercostingtracking`
--
ALTER TABLE `suppliercostingtracking`
  ADD PRIMARY KEY (`SupplierCostingID`);

--
-- Indexes for table `supplierorderlines`
--
ALTER TABLE `supplierorderlines`
  ADD PRIMARY KEY (`SupplierInvoiceLineItemID`);

--
-- Indexes for table `supplierorders`
--
ALTER TABLE `supplierorders`
  ADD PRIMARY KEY (`SupplierInvoiceID`);

--
-- Indexes for table `supplierproducts`
--
ALTER TABLE `supplierproducts`
  ADD PRIMARY KEY (`SupplierProductID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`WarehouseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companysettings`
--
ALTER TABLE `companysettings`
  MODIFY `SettingsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `CountryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
--
-- AUTO_INCREMENT for table `customeraccess`
--
ALTER TABLE `customeraccess`
  MODIFY `CustomerAccessLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `customercontacts`
--
ALTER TABLE `customercontacts`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customercustomentries`
--
ALTER TABLE `customercustomentries`
  MODIFY `CustomerCustomValueID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customercustomfields`
--
ALTER TABLE `customercustomfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customercustomfieldsvalues`
--
ALTER TABLE `customercustomfieldsvalues`
  MODIFY `CustomFieldOptionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerdocumentgroups`
--
ALTER TABLE `customerdocumentgroups`
  MODIFY `DocumentGroupID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerdocuments`
--
ALTER TABLE `customerdocuments`
  MODIFY `DocumentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerfollowups`
--
ALTER TABLE `customerfollowups`
  MODIFY `FollowUpID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerinvoicegroups`
--
ALTER TABLE `customerinvoicegroups`
  MODIFY `InvoiceGroupID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerinvoicelines`
--
ALTER TABLE `customerinvoicelines`
  MODIFY `InvoiceLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `customerinvoicepayments`
--
ALTER TABLE `customerinvoicepayments`
  MODIFY `InvoicePaymentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerinvoices`
--
ALTER TABLE `customerinvoices`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `customerlogs`
--
ALTER TABLE `customerlogs`
  MODIFY `CustomerLogID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customernotes`
--
ALTER TABLE `customernotes`
  MODIFY `NoteID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerproducts`
--
ALTER TABLE `customerproducts`
  MODIFY `ClientProductID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerquotelines`
--
ALTER TABLE `customerquotelines`
  MODIFY `QuoteLineItemID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerquotes`
--
ALTER TABLE `customerquotes`
  MODIFY `QuoteID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerrecurring`
--
ALTER TABLE `customerrecurring`
  MODIFY `RecurringID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerrecurringlines`
--
ALTER TABLE `customerrecurringlines`
  MODIFY `RecurringLineItemID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customersites`
--
ALTER TABLE `customersites`
  MODIFY `SiteID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customertask`
--
ALTER TABLE `customertask`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customertransactions`
--
ALTER TABLE `customertransactions`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employeedepartments`
--
ALTER TABLE `employeedepartments`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employeesecuritygroups`
--
ALTER TABLE `employeesecuritygroups`
  MODIFY `EmployeeSecurityGroupID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobcardfields`
--
ALTER TABLE `jobcardfields`
  MODIFY `JobcardFieldID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `jobcardinputlines`
--
ALTER TABLE `jobcardinputlines`
  MODIFY `JobcardInputLineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `jobcardinputlinevalues`
--
ALTER TABLE `jobcardinputlinevalues`
  MODIFY `InputID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `jobcards`
--
ALTER TABLE `jobcards`
  MODIFY `JobcardID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobcardtables`
--
ALTER TABLE `jobcardtables`
  MODIFY `JobcardTableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `productcost`
--
ALTER TABLE `productcost`
  MODIFY `ProductCostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `productcustomentries`
--
ALTER TABLE `productcustomentries`
  MODIFY `ProductCustomValueID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productcustomfields`
--
ALTER TABLE `productcustomfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productcustomfieldsvalues`
--
ALTER TABLE `productcustomfieldsvalues`
  MODIFY `CustomFieldOptionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productgroups`
--
ALTER TABLE `productgroups`
  MODIFY `ProductGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `productimages`
--
ALTER TABLE `productimages`
  MODIFY `ProductImageID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productmeasurement`
--
ALTER TABLE `productmeasurement`
  MODIFY `MeasurementID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `productstock`
--
ALTER TABLE `productstock`
  MODIFY `StockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `productsubgroups`
--
ALTER TABLE `productsubgroups`
  MODIFY `ProductSubGroupID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchaseorderlines`
--
ALTER TABLE `purchaseorderlines`
  MODIFY `PurchaseLineItemID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  MODIFY `PurchaseID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `securitygroups`
--
ALTER TABLE `securitygroups`
  MODIFY `SecurityGroupID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `securitygroupsettings`
--
ALTER TABLE `securitygroupsettings`
  MODIFY `SecurityGroupSettingsID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stocktakes`
--
ALTER TABLE `stocktakes`
  MODIFY `StockTakeID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliercost`
--
ALTER TABLE `suppliercost`
  MODIFY `SupplierCostID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliercostingtracking`
--
ALTER TABLE `suppliercostingtracking`
  MODIFY `SupplierCostingID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplierorderlines`
--
ALTER TABLE `supplierorderlines`
  MODIFY `SupplierInvoiceLineItemID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplierorders`
--
ALTER TABLE `supplierorders`
  MODIFY `SupplierInvoiceID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplierproducts`
--
ALTER TABLE `supplierproducts`
  MODIFY `SupplierProductID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `WarehouseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
