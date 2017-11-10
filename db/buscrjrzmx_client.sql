-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2017 at 04:35 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buscrjrzmx_client`
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
  `RecurringInvoiceDay` int(11) NOT NULL DEFAULT '25',
  `CompanyRegistration` varchar(255) DEFAULT NULL,
  `TermsAndConditions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companysettings`
--

INSERT INTO `companysettings` (`SettingsID`, `CompanyLogo`, `VATRegistered`, `VATNumber`, `Address1`, `Address2`, `City`, `Region`, `PostCode`, `CountryID`, `BankName`, `AccountHolder`, `AccountNumber`, `BranchCode`, `AccountType`, `VATRate`, `CurrencySymbol`, `InvoiceLogo`, `InvoiceDisplayCompany`, `InvoiceDisplayEmail`, `InvoiceDisplayTel`, `InvoiceDisplayFax`, `RecurringInvoiceDay`, `CompanyRegistration`, `TermsAndConditions`) VALUES
(1, NULL, 1, '12356475', '7 Somewhere', 'Someplace', 'Durban', 'Kwazulu-Natal', '4094', '192', 'Absa', 'BeCooling', '9383939449', '1234', 'Transmission', 14, NULL, 'invoicelogo.jpg', 'BeCooling', 'info@becooling.co.za', '0317474747', '03175474849', 25, 'test reg 123456', '1. This is term one\n2. This is term 2');

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
-- Table structure for table `customclientfields`
--

CREATE TABLE `customclientfields` (
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text',
  `DisplayOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customclientfields`
--

INSERT INTO `customclientfields` (`CustomFieldID`, `CustomFieldName`, `CustomFieldType`, `DisplayOrder`) VALUES
(1, 'ID Number', 'text', 1),
(3, 'Mobile Phone', 'text', 3),
(4, 'Company Reg', 'text', 4),
(5, 'Receive SMS', 'checkbox', 5),
(6, 'Office Tel', 'text', 6),
(7, 'Test Radio', 'radio', 7),
(9, 'How did you find us?', 'select', 2);

-- --------------------------------------------------------

--
-- Table structure for table `customclientfieldsvalues`
--

CREATE TABLE `customclientfieldsvalues` (
  `CustomClientFieldOptionID` int(11) NOT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customclientfieldsvalues`
--

INSERT INTO `customclientfieldsvalues` (`CustomClientFieldOptionID`, `CustomFieldID`, `OptionValue`) VALUES
(1, 2, 'Google'),
(2, 2, 'Bing'),
(3, 2, 'Other Search Engine'),
(4, 2, 'Friend Referral'),
(5, 2, 'Advertisement'),
(6, 2, 'Other'),
(7, 5, 'Yes'),
(8, 7, 'Only Saturday'),
(9, 7, 'Only Sunday'),
(10, 5, 'No'),
(12, 9, 'Bing'),
(13, 9, 'Google'),
(14, 9, 'Online Advertising'),
(15, 9, 'Anywhere');

-- --------------------------------------------------------

--
-- Table structure for table `customcustomerfields`
--

CREATE TABLE `customcustomerfields` (
  `CustomFieldID` int(11) NOT NULL,
  `CustomFieldName` varchar(255) DEFAULT NULL,
  `CustomFieldType` varchar(255) DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customcustomerfieldsvalues`
--

CREATE TABLE `customcustomerfieldsvalues` (
  `CustomClientFieldOptionID` int(11) NOT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(5, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 10:42:03', 'Alex Minnie'),
(6, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 10:42:07', 'Alex Minnie'),
(7, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 10:42:08', 'Alex Minnie'),
(8, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 10:48:38', 'Alex Minnie'),
(9, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 10:48:46', 'Alex Minnie'),
(12, 5, 1, 0, 'Accessed Customer Summary', '2016-12-16 10:56:20', 'Alex Minnie'),
(14, 5, 1, 0, 'Accessed Customer Summary', '2016-12-16 10:57:13', 'Alex Minnie'),
(15, 5, 1, 0, 'Accessed Customer Profile', '2016-12-16 10:57:16', 'Alex Minnie'),
(16, 5, 1, 0, 'Accessed Customer Profile', '2016-12-16 10:59:49', 'Alex Minnie'),
(17, 5, 1, 0, 'Updated Client Profile', '2016-12-16 10:59:52', 'Alex Minnie'),
(18, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 11:02:07', 'Alex Minnie'),
(19, 5, 1, 0, 'Contact Added Test Test', '2016-12-16 11:03:30', 'Alex Minnie'),
(20, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 11:03:32', 'Alex Minnie'),
(21, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 11:03:51', 'Alex Minnie'),
(22, 5, 1, 0, 'Accessed Customer Profile', '2016-12-16 11:06:44', 'Alex Minnie'),
(23, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-16 11:06:46', 'Alex Minnie'),
(24, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:06:51', 'Alex Minnie'),
(25, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:09:10', 'Alex Minnie'),
(26, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:09:22', 'Alex Minnie'),
(27, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:10:20', 'Alex Minnie'),
(28, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:10:27', 'Alex Minnie'),
(29, 5, 1, 0, 'Downloaded Document Test PDF', '2016-12-16 11:12:33', 'Alex Minnie'),
(30, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:12:44', 'Alex Minnie'),
(31, 5, 1, 0, 'Accessed Customer Documents', '2016-12-16 11:16:06', 'Alex Minnie'),
(32, 5, 1, 0, 'Accessed Customer Notes', '2016-12-16 11:16:53', 'Alex Minnie'),
(33, 5, 1, 0, 'Accessed Customer Notes', '2016-12-16 11:19:21', 'Alex Minnie'),
(34, 5, 1, 0, 'Added Customer Note', '2016-12-16 11:19:31', 'Alex Minnie'),
(35, 5, 1, 0, 'Accessed Customer Notes', '2016-12-16 11:19:33', 'Alex Minnie'),
(36, 5, 1, 0, 'Accessed Customer Notes', '2016-12-16 11:20:26', 'Alex Minnie'),
(37, 5, 1, 0, 'Accessed Customer Notes', '2016-12-16 11:20:48', 'Alex Minnie'),
(38, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:21:32', 'Alex Minnie'),
(39, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:23:56', 'Alex Minnie'),
(40, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:34:31', 'Alex Minnie'),
(41, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:37:47', 'Alex Minnie'),
(42, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:38:03', 'Alex Minnie'),
(43, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:42:26', 'Alex Minnie'),
(44, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:42:44', 'Alex Minnie'),
(45, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:49:08', 'Alex Minnie'),
(46, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:49:44', 'Alex Minnie'),
(47, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-16 11:53:29', 'Alex Minnie'),
(48, 5, 1, 0, 'Accessed Customer Documents', '2016-12-19 05:03:35', 'Alex Minnie'),
(49, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 05:03:39', 'Alex Minnie'),
(50, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 05:04:02', 'Alex Minnie'),
(51, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 05:05:41', 'Alex Minnie'),
(52, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 05:10:12', 'Alex Minnie'),
(53, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:20:13', 'Alex Minnie'),
(54, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:21:41', 'Alex Minnie'),
(55, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:29:01', 'Alex Minnie'),
(56, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:31:08', 'Alex Minnie'),
(57, 5, 1, 0, 'Accessed Customer Profile', '2016-12-19 05:31:20', 'Alex Minnie'),
(58, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:31:23', 'Alex Minnie'),
(59, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:31:52', 'Alex Minnie'),
(60, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 05:32:15', 'Alex Minnie'),
(61, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 06:39:00', 'Alex Minnie'),
(62, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 06:39:42', 'Alex Minnie'),
(63, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 06:53:55', 'Alex Minnie'),
(64, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 06:55:17', 'Alex Minnie'),
(65, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:13:25', 'Alex Minnie'),
(66, 5, 1, 0, 'Updated Customer Task', '2016-12-19 09:13:32', 'Alex Minnie'),
(67, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:13:33', 'Alex Minnie'),
(68, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:13:55', 'Alex Minnie'),
(69, 5, 1, 0, 'Updated Customer Task', '2016-12-19 09:14:01', 'Alex Minnie'),
(70, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:14:02', 'Alex Minnie'),
(71, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:15:07', 'Alex Minnie'),
(72, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:15:58', 'Alex Minnie'),
(73, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:16:48', 'Alex Minnie'),
(74, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:17:03', 'Alex Minnie'),
(75, 5, 1, 0, 'Accessed Customer Profile', '2016-12-19 09:17:14', 'Alex Minnie'),
(76, 5, 1, 0, 'Accessed Customer Summary', '2016-12-19 09:17:17', 'Alex Minnie'),
(77, 5, 1, 0, 'Accessed Customer Contacts', '2016-12-19 09:17:20', 'Alex Minnie'),
(78, 5, 1, 0, 'Accessed Customer Documents', '2016-12-19 09:17:22', 'Alex Minnie'),
(79, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 09:17:24', 'Alex Minnie'),
(80, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 09:18:00', 'Alex Minnie'),
(81, 5, 1, 0, 'Accessed Customer Notes', '2016-12-19 09:18:30', 'Alex Minnie'),
(82, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:18:43', 'Alex Minnie'),
(83, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:20:31', 'Alex Minnie'),
(84, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 09:20:36', 'Alex Minnie'),
(85, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:08:38', 'Alex Minnie'),
(86, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:08:54', 'Alex Minnie'),
(87, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:09:52', 'Alex Minnie'),
(88, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:26:02', 'Alex Minnie'),
(89, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:29:08', 'Alex Minnie'),
(90, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:29:22', 'Alex Minnie'),
(91, 5, 1, 0, 'Added Customer Follow Up', '2016-12-19 10:59:38', 'Alex Minnie'),
(92, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 10:59:40', 'Alex Minnie'),
(93, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 14:02:27', 'Alex Minnie'),
(94, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 14:42:57', 'Alex Minnie'),
(95, 5, 1, 0, 'Updated Customer Follow Up', '2016-12-19 14:51:27', 'Alex Minnie'),
(96, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 14:51:29', 'Alex Minnie'),
(97, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-19 14:51:35', 'Alex Minnie'),
(98, 5, 1, 0, 'Accessed Customer Task', '2016-12-19 14:51:44', 'Alex Minnie'),
(99, 5, 1, 0, 'Accessed Customer Email Logs', '2016-12-19 14:53:01', 'Alex Minnie'),
(100, 5, 1, 0, 'Accessed Customer Email Logs', '2016-12-19 14:53:13', 'Alex Minnie'),
(101, 5, 1, 0, 'Accessed Customer Email Logs', '2016-12-19 14:54:10', 'Alex Minnie'),
(102, 5, 1, 0, 'Accessed Customer Email Logs', '2016-12-19 14:54:53', 'Alex Minnie'),
(103, 5, 1, 0, 'Accessed Customer Follow Ups', '2016-12-20 08:56:59', 'Alex Minnie'),
(104, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-20 08:57:21', 'Alex Minnie'),
(105, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-20 08:57:38', 'Alex Minnie'),
(106, 5, 1, 0, 'Accessed Customer Summary', '2016-12-20 09:57:14', 'Alex Minnie'),
(107, 5, 1, 0, 'Accessed Customer Profile', '2016-12-20 09:57:17', 'Alex Minnie'),
(108, 5, 1, 0, 'Accessed Customer Profile', '2016-12-20 10:30:11', 'Alex Minnie'),
(109, 5, 1, 0, 'Accessed Customer Summary', '2016-12-20 13:04:07', 'Alex Minnie'),
(110, 5, 1, 0, 'Accessed Customer Profile', '2016-12-20 13:04:11', 'Alex Minnie'),
(111, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-20 13:46:50', 'Alex Minnie'),
(112, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-20 13:48:04', 'Alex Minnie'),
(113, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-20 13:48:11', 'Alex Minnie'),
(114, 5, 11, 0, 'Accessed Customer Invoices', '2016-12-21 11:18:46', 'Alex Minnie'),
(115, 5, 11, 0, 'Accessed Customer Invoices', '2016-12-21 11:18:54', 'Alex Minnie'),
(116, 5, 11, 0, 'Accessed Customer Invoices', '2016-12-21 12:28:40', 'Alex Minnie'),
(117, 5, 11, 0, 'Accessed Customer Invoices', '2016-12-21 12:30:53', 'Alex Minnie'),
(118, 5, 1, 0, 'Accessed Customer Summary', '2016-12-22 06:53:08', 'Alex Minnie'),
(119, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-22 06:53:11', 'Alex Minnie'),
(120, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-22 15:09:10', 'Alex Minnie'),
(121, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-22 17:49:04', 'Alex Minnie'),
(122, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-22 17:50:25', 'Alex Minnie'),
(123, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 06:59:50', 'Alex Minnie'),
(124, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 07:14:46', 'Alex Minnie'),
(125, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 09:14:02', 'Alex Minnie'),
(126, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 09:57:31', 'Alex Minnie'),
(127, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 09:57:46', 'Alex Minnie'),
(128, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 09:57:53', 'Alex Minnie'),
(129, 5, 1, 0, 'Accessed Customer Summary', '2016-12-23 09:57:58', 'Alex Minnie'),
(130, 5, 1, 0, 'Accessed Customer Summary', '2016-12-23 09:59:25', 'Alex Minnie'),
(131, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 09:59:35', 'Alex Minnie'),
(132, 5, 1, 0, 'Updates Customer Invoice INV0000002 status toUnpaid', '2016-12-23 10:44:02', 'Alex Minnie'),
(133, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 10:44:04', 'Alex Minnie'),
(134, 5, 1, 0, 'Updates Customer Invoice INV0000002 status toUnpaid', '2016-12-23 10:44:23', 'Alex Minnie'),
(135, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 10:44:25', 'Alex Minnie'),
(136, 5, 1, 0, 'Added Customer Invoice INV0000003', '2016-12-23 10:44:43', 'Alex Minnie'),
(137, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 10:53:23', 'Alex Minnie'),
(138, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 10:56:25', 'Alex Minnie'),
(139, 5, 1, 0, 'Accessed Customer Summary', '2016-12-23 10:56:39', 'Alex Minnie'),
(140, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 10:57:00', 'Alex Minnie'),
(141, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:37:11', 'Alex Minnie'),
(142, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:37:42', 'Alex Minnie'),
(143, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:43:40', 'Alex Minnie'),
(144, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:45:02', 'Alex Minnie'),
(145, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:46:19', 'Alex Minnie'),
(146, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:54:02', 'Alex Minnie'),
(147, 5, 1, 0, 'Added Customer Quote QU0000003', '2016-12-23 12:54:29', 'Alex Minnie'),
(148, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 12:54:33', 'Alex Minnie'),
(149, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 13:08:36', 'Alex Minnie'),
(150, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 13:24:11', 'Alex Minnie'),
(151, 5, 1, 0, 'Updates Customer Quote QU0000003 status to Declined', '2016-12-23 13:38:16', 'Alex Minnie'),
(152, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 13:38:18', 'Alex Minnie'),
(153, 5, 1, 0, 'Accessed Customer Invoices', '2016-12-23 13:39:17', 'Alex Minnie'),
(154, 5, 1, 0, 'Accessed Customer Quotes', '2016-12-23 13:39:20', 'Alex Minnie'),
(155, 5, 1, 0, 'Accessed Customer Summary', '2017-01-03 06:27:35', 'Alex Minnie'),
(156, 5, 1, 0, 'Accessed Customer Task', '2017-01-03 06:27:40', 'Alex Minnie'),
(157, 5, 1, 0, 'Updated Customer Task', '2017-01-03 06:27:51', 'Alex Minnie'),
(158, 5, 1, 0, 'Accessed Customer Task', '2017-01-03 06:27:53', 'Alex Minnie'),
(159, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-01-03 06:27:56', 'Alex Minnie'),
(160, 5, 1, 0, 'Accessed Customer Task', '2017-01-03 06:33:21', 'Alex Minnie'),
(161, 5, 1, 0, 'Accessed Customer Task', '2017-01-03 06:33:47', 'Alex Minnie'),
(162, 5, 1, 0, 'Accessed Customer Summary', '2017-01-03 06:47:41', 'Alex Minnie'),
(163, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-01-03 07:30:14', 'Alex Minnie'),
(164, 5, 1, 0, 'Accessed Customer Summary', '2017-01-05 09:02:25', 'Alex Minnie'),
(165, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-05 09:02:28', 'Alex Minnie'),
(166, 5, 1, 0, 'Added Customer Invoice INV0000004', '2017-01-05 09:25:47', 'Alex Minnie'),
(167, 5, 1, 0, 'Accessed Customer Summary', '2017-01-13 08:29:37', 'Alex Minnie'),
(168, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-01-13 08:29:42', 'Alex Minnie'),
(169, 5, 1, 0, 'Accessed Customer Notes', '2017-01-13 08:31:38', 'Alex Minnie'),
(170, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-13 08:31:45', 'Alex Minnie'),
(171, 5, 1, 0, 'Accessed Customer Quotes', '2017-01-13 08:31:46', 'Alex Minnie'),
(172, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-13 08:37:39', 'Alex Minnie'),
(173, 5, 1, 0, 'Accessed Customer Summary', '2017-01-13 08:50:50', 'Alex Minnie'),
(174, 5, 1, 0, 'Accessed Customer Profile', '2017-01-13 08:50:55', 'Alex Minnie'),
(175, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-13 10:33:34', 'Alex Minnie'),
(176, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-13 10:34:29', 'Alex Minnie'),
(177, 5, 1, 0, 'Accessed Customer Profile', '2017-01-13 10:36:17', 'Alex Minnie'),
(178, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-13 10:42:47', 'Alex Minnie'),
(179, 5, 1, 0, 'Accessed Customer Quotes', '2017-01-13 11:03:42', 'Alex Minnie'),
(180, 5, 1, 0, 'Accessed Customer Summary', '2017-01-18 07:29:00', 'Alex Minnie'),
(181, 5, 1, 0, 'Accessed Customer Profile', '2017-01-18 07:29:02', 'Alex Minnie'),
(182, 5, 1, 1, 'Accessed Customer Summary', '2017-01-19 09:41:54', 'Alex Minnie'),
(183, 5, 1, 0, 'Accessed Customer Summary', '2017-01-27 09:13:34', 'Alex Minnie'),
(184, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 09:13:38', 'Alex Minnie'),
(185, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 09:18:44', 'Alex Minnie'),
(186, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 09:20:17', 'Alex Minnie'),
(187, 5, 1, 0, 'Accessed Customer Summary', '2017-01-27 09:36:35', 'Alex Minnie'),
(188, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 09:36:37', 'Alex Minnie'),
(189, 5, 1, 0, 'Added Customer Invoice INV0000005', '2017-01-27 09:37:08', 'Alex Minnie'),
(190, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 10:48:24', 'Alex Minnie'),
(191, 5, 1, 0, 'Accessed Customer Summary', '2017-01-27 10:55:47', 'Alex Minnie'),
(192, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 10:55:49', 'Alex Minnie'),
(193, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 10:56:14', 'Alex Minnie'),
(194, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 10:56:24', 'Alex Minnie'),
(195, 5, 1, 0, 'Added Customer Invoice INV0000006', '2017-01-27 10:56:33', 'Alex Minnie'),
(196, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 11:26:38', 'Alex Minnie'),
(197, 5, 1, 0, 'Accessed Customer Summary', '2017-01-27 11:28:32', 'Alex Minnie'),
(198, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-27 11:28:34', 'Alex Minnie'),
(199, 5, 1, 0, 'Accessed Customer Summary', '2017-01-30 08:10:11', 'Alex Minnie'),
(200, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-30 08:10:14', 'Alex Minnie'),
(201, 5, 1, 0, 'Accessed Customer Summary', '2017-01-30 08:10:16', 'Alex Minnie'),
(202, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-30 08:10:19', 'Alex Minnie'),
(203, 5, 1, 0, 'Accessed Customer Summary', '2017-01-30 08:10:20', 'Alex Minnie'),
(204, 5, 1, 0, 'Accessed Customer Summary', '2017-01-30 08:10:30', 'Alex Minnie'),
(205, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-30 08:10:37', 'Alex Minnie'),
(206, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-30 08:12:38', 'Alex Minnie'),
(207, 5, 1, 0, 'Accessed Customer Summary', '2017-01-30 08:22:28', 'Alex Minnie'),
(208, 5, 1, 0, 'Accessed Customer Invoices', '2017-01-30 08:22:29', 'Alex Minnie'),
(209, 5, 1, 0, 'Accessed Customer Summary', '2017-02-03 11:40:26', 'Alex Minnie'),
(210, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-03 11:40:32', 'Alex Minnie'),
(211, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 11:40:44', 'Alex Minnie'),
(212, 5, 1, 0, 'Accessed Customer Summary', '2017-02-03 16:09:05', 'Alex Minnie'),
(213, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:09:08', 'Alex Minnie'),
(214, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-03 16:09:40', 'Alex Minnie'),
(215, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:09:41', 'Alex Minnie'),
(216, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-03 16:10:09', 'Alex Minnie'),
(217, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:10:11', 'Alex Minnie'),
(218, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:10:22', 'Alex Minnie'),
(219, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:10:41', 'Alex Minnie'),
(220, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:10:42', 'Alex Minnie'),
(221, 5, 1, 0, 'Accessed Customer Summary', '2017-02-03 16:10:59', 'Alex Minnie'),
(222, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-03 16:11:02', 'Alex Minnie'),
(223, 5, 1, 0, 'Accessed Customer Summary', '2017-02-05 18:20:47', 'Alex Minnie'),
(224, 5, 1, 0, 'Accessed Customer Profile', '2017-02-05 18:20:59', 'Alex Minnie'),
(225, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-05 18:21:08', 'Alex Minnie'),
(226, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-05 18:21:30', 'Alex Minnie'),
(227, 5, 1, 0, 'Accessed Customer Summary', '2017-02-05 18:21:42', 'Alex Minnie'),
(228, 5, 1, 0, 'Accessed Customer Notes', '2017-02-05 18:21:48', 'Alex Minnie'),
(229, 5, 1, 0, 'Added Customer Note', '2017-02-05 18:22:15', 'Alex Minnie'),
(230, 5, 1, 0, 'Accessed Customer Notes', '2017-02-05 18:22:17', 'Alex Minnie'),
(231, 5, 1, 0, 'Accessed Customer Summary', '2017-02-05 18:22:19', 'Alex Minnie'),
(232, 5, 1, 0, 'Accessed Customer Products', '2017-02-05 18:22:27', 'Alex Minnie'),
(233, 5, 1, 0, 'Accessed Customer Task', '2017-02-05 18:22:30', 'Alex Minnie'),
(234, 5, 1, 0, 'Accessed Customer Email Logs', '2017-02-05 18:22:35', 'Alex Minnie'),
(235, 5, 1, 0, 'Accessed Customer Summary', '2017-02-05 18:24:00', 'Alex Minnie'),
(236, 5, 1, 0, 'Accessed Customer Notes', '2017-02-05 18:24:04', 'Alex Minnie'),
(237, 5, 1, 0, 'Added Customer Note', '2017-02-05 18:24:25', 'Alex Minnie'),
(238, 5, 1, 0, 'Accessed Customer Notes', '2017-02-05 18:24:27', 'Alex Minnie'),
(239, 5, 1, 0, 'Accessed Customer Summary', '2017-02-05 18:24:37', 'Alex Minnie'),
(240, 5, 1, 0, 'Accessed Customer Notes', '2017-02-05 18:24:50', 'Alex Minnie'),
(241, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 06:57:51', 'Alex Minnie'),
(242, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 06:58:12', 'Alex Minnie'),
(243, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:36:08', 'Alex Minnie'),
(244, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:36:14', 'Alex Minnie'),
(245, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:37:12', 'Alex Minnie'),
(246, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:37:15', 'Alex Minnie'),
(247, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:37:25', 'Alex Minnie'),
(248, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:37:54', 'Alex Minnie'),
(249, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:37:55', 'Alex Minnie'),
(250, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:38:52', 'Alex Minnie'),
(251, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:39:18', 'Alex Minnie'),
(252, 5, 1, 0, 'Added customer transaction feb05', '2017-02-06 07:40:25', 'Alex Minnie'),
(253, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:40:27', 'Alex Minnie'),
(254, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:40:30', 'Alex Minnie'),
(255, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:41:07', 'Alex Minnie'),
(256, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:41:12', 'Alex Minnie'),
(257, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:41:17', 'Alex Minnie'),
(258, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:41:19', 'Alex Minnie'),
(259, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:41:20', 'Alex Minnie'),
(260, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:41:26', 'Alex Minnie'),
(261, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:41:54', 'Alex Minnie'),
(262, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:41:56', 'Alex Minnie'),
(263, 5, 1, 0, 'Accessed Customer Contacts', '2017-02-06 07:42:07', 'Alex Minnie'),
(264, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 07:42:19', 'Alex Minnie'),
(265, 5, 1, 0, 'Accessed Customer Contacts', '2017-02-06 07:42:20', 'Alex Minnie'),
(266, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:42:37', 'Alex Minnie'),
(267, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 07:42:39', 'Alex Minnie'),
(268, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:42:40', 'Alex Minnie'),
(269, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 07:42:42', 'Alex Minnie'),
(270, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:42:59', 'Alex Minnie'),
(271, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:42:59', 'Alex Minnie'),
(272, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 07:43:01', 'Alex Minnie'),
(273, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:43:12', 'Alex Minnie'),
(274, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:43:42', 'Alex Minnie'),
(275, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 07:44:46', 'Alex Minnie'),
(276, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 07:44:48', 'Alex Minnie'),
(277, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:45:22', 'Alex Minnie'),
(278, 5, 1, 0, 'Accessed Customer Profile', '2017-02-06 07:45:26', 'Alex Minnie'),
(279, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:46:17', 'Alex Minnie'),
(280, 5, 1, 0, 'Accessed Customer Profile', '2017-02-06 07:46:20', 'Alex Minnie'),
(281, 5, 1, 0, 'Updated Client Profile', '2017-02-06 07:46:31', 'Alex Minnie'),
(282, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 07:46:57', 'Alex Minnie'),
(283, 5, 1, 0, 'Accessed Customer Profile', '2017-02-06 07:47:08', 'Alex Minnie'),
(284, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 12:56:17', 'Alex Minnie'),
(285, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 12:56:24', 'Alex Minnie'),
(286, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 13:27:08', 'Alex Minnie'),
(287, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 13:27:11', 'Alex Minnie'),
(288, 5, 1, 0, 'Accessed Customer Quotes', '2017-02-06 13:27:18', 'Alex Minnie'),
(289, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 13:58:20', 'Alex Minnie'),
(290, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 13:58:22', 'Alex Minnie'),
(291, 5, 1, 0, 'Added Customer Invoice INV0000007', '2017-02-06 13:58:30', 'Alex Minnie'),
(292, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 14:06:57', 'Alex Minnie'),
(293, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 14:06:58', 'Alex Minnie'),
(294, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 15:43:37', 'Alex Minnie'),
(295, 5, 1, 0, 'Accessed Customer Profile', '2017-02-06 15:43:59', 'Alex Minnie'),
(296, 5, 1, 0, 'Accessed Customer Profile', '2017-02-06 15:45:09', 'Alex Minnie'),
(297, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 15:45:22', 'Alex Minnie'),
(298, 5, 1, 0, 'Accessed Customer Transactions', '2017-02-06 15:45:30', 'Alex Minnie'),
(299, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-06 15:45:34', 'Alex Minnie'),
(300, 5, 1, 0, 'Accessed Customer Documents', '2017-02-06 15:45:43', 'Alex Minnie'),
(301, 5, 1, 0, 'Accessed Customer Notes', '2017-02-06 15:46:21', 'Alex Minnie'),
(302, 5, 1, 0, 'Accessed Customer Summary', '2017-02-06 15:46:25', 'Alex Minnie'),
(303, 5, 1, 0, 'Accessed Customer Task', '2017-02-06 15:47:21', 'Alex Minnie'),
(304, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-02-06 15:47:22', 'Alex Minnie'),
(305, 5, 1, 0, 'Accessed Customer Summary', '2017-02-09 06:39:06', 'Alex Minnie'),
(306, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-09 06:39:12', 'Alex Minnie'),
(307, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-09 07:04:20', 'Alex Minnie'),
(308, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-09 07:15:53', 'Alex Minnie'),
(309, 5, 1, 0, 'Added Jobcard JBC2', '2017-02-16 16:53:25', 'Alex Minnie'),
(310, 5, 1, 0, 'Added Jobcard JBC3', '2017-02-16 16:59:19', 'Alex Minnie'),
(311, 5, 1, 0, 'Accessed Customer Summary', '2017-02-17 15:17:31', 'Alex Minnie'),
(312, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:34:40', 'Alex Minnie'),
(313, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:34:42', 'Alex Minnie'),
(314, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:34:45', 'Alex Minnie'),
(315, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:34:50', 'Alex Minnie'),
(316, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:35:11', 'Alex Minnie'),
(317, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:35:12', 'Alex Minnie'),
(318, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:35:35', 'Alex Minnie'),
(319, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:36:22', 'Alex Minnie'),
(320, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:37:53', 'Alex Minnie'),
(321, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:37:56', 'Alex Minnie'),
(322, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:38:10', 'Alex Minnie'),
(323, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:38:14', 'Alex Minnie'),
(324, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:38:36', 'Alex Minnie'),
(325, 5, 1, 0, 'Added Customer Invoice INV0000008', '2017-02-20 08:38:50', 'Alex Minnie'),
(326, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:38:50', 'Alex Minnie'),
(327, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:39:52', 'Alex Minnie'),
(328, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 08:39:54', 'Alex Minnie'),
(329, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 08:40:34', 'Alex Minnie'),
(330, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 12:31:32', 'Alex Minnie'),
(331, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:31:35', 'Alex Minnie'),
(332, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 12:32:50', 'Alex Minnie'),
(333, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:32:53', 'Alex Minnie'),
(334, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:33:25', 'Alex Minnie'),
(335, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:33:33', 'Alex Minnie'),
(336, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 12:33:52', 'Alex Minnie'),
(337, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:33:55', 'Alex Minnie'),
(338, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 12:34:05', 'Alex Minnie'),
(339, 5, 1, 0, 'Added Jobcard JBC4', '2017-02-20 13:26:04', 'Alex Minnie'),
(340, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 13:33:29', 'Alex Minnie'),
(341, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 13:33:47', 'Alex Minnie'),
(342, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 13:34:37', 'Alex Minnie'),
(343, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 13:34:39', 'Alex Minnie'),
(344, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 13:36:18', 'Alex Minnie'),
(345, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 13:36:19', 'Alex Minnie'),
(346, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 13:39:07', 'Alex Minnie'),
(347, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 13:39:09', 'Alex Minnie'),
(348, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 13:46:44', 'Alex Minnie'),
(349, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 13:46:49', 'Alex Minnie'),
(350, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 13:48:14', 'Alex Minnie'),
(351, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 13:48:24', 'Alex Minnie'),
(352, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 14:36:26', 'Alex Minnie'),
(353, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 14:36:29', 'Alex Minnie'),
(354, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 14:36:31', 'Alex Minnie'),
(355, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 14:36:56', 'Alex Minnie'),
(356, 5, 1, 0, 'Accessed Customer Summary', '2017-02-20 14:37:29', 'Alex Minnie'),
(357, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 14:37:31', 'Alex Minnie'),
(358, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 14:37:54', 'Alex Minnie'),
(359, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-20 14:38:15', 'Alex Minnie'),
(360, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 14:38:19', 'Alex Minnie'),
(361, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-20 14:38:36', 'Alex Minnie'),
(362, 5, 1, 0, 'Added Jobcard JBC5', '2017-02-23 14:50:26', 'Alex Minnie'),
(363, 5, 1, 0, 'Accessed Customer Jobcards', '2017-02-23 14:51:07', 'Alex Minnie'),
(364, 5, 1, 0, 'Accessed Customer Invoices', '2017-02-23 14:51:15', 'Alex Minnie'),
(365, 5, 1, 0, 'Accessed Customer Summary', '2017-03-08 12:16:52', 'Alex Minnie'),
(366, 5, 1, 0, 'Accessed Customer Statements', '2017-03-08 12:16:56', 'Alex Minnie'),
(367, 5, 1, 0, 'Accessed Customer Statements', '2017-03-08 12:17:09', 'Alex Minnie'),
(368, 5, 1, 0, 'Accessed Customer Statements', '2017-03-08 12:17:18', 'Alex Minnie'),
(369, 5, 1, 0, 'Accessed Customer Statements', '2017-03-08 12:17:30', 'Alex Minnie'),
(370, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 08:59:44', 'Alex Minnie'),
(371, 5, 1, 0, 'Accessed Customer Statements', '2017-03-09 08:59:46', 'Alex Minnie'),
(372, 5, 1, 0, 'Accessed Customer Statements', '2017-03-09 08:59:52', 'Alex Minnie'),
(373, 5, 1, 0, 'Accessed Customer Email Logs', '2017-03-09 09:03:05', 'Alex Minnie'),
(374, 5, 1, 0, 'Accessed Customer Statements', '2017-03-09 09:04:16', 'Alex Minnie'),
(375, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:04:20', 'Alex Minnie'),
(376, 5, 1, 0, 'Accessed Customer Profile', '2017-03-09 09:04:21', 'Alex Minnie'),
(377, 5, 1, 0, 'Accessed Customer Email Logs', '2017-03-09 09:04:33', 'Alex Minnie'),
(378, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-03-09 09:04:36', 'Alex Minnie'),
(379, 5, 1, 0, 'Accessed Customer Statements', '2017-03-09 09:04:46', 'Alex Minnie'),
(380, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:04:49', 'Alex Minnie'),
(381, 5, 1, 0, 'Accessed Customer Statements', '2017-03-09 09:04:53', 'Alex Minnie'),
(382, 5, 1, 0, 'Accessed Customer Jobcards', '2017-03-09 09:11:54', 'Alex Minnie'),
(383, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:22:17', 'Alex Minnie'),
(384, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-09 09:22:21', 'Alex Minnie'),
(385, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:23:36', 'Alex Minnie'),
(386, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-09 09:23:38', 'Alex Minnie'),
(387, 5, 1, 0, 'Accessed Customer Quotes', '2017-03-09 09:24:00', 'Alex Minnie'),
(388, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-09 09:29:27', 'Alex Minnie'),
(389, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-09 09:47:05', 'Alex Minnie'),
(390, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:51:28', 'Alex Minnie'),
(391, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-09 09:51:30', 'Alex Minnie'),
(392, 5, 1, 0, 'Accessed Customer Summary', '2017-03-09 09:54:57', 'Alex Minnie'),
(393, 5, 1, 0, 'Accessed Customer Profile', '2017-03-09 09:55:00', 'Alex Minnie'),
(394, 5, 1, 0, 'Accessed Customer Summary', '2017-03-15 14:11:14', 'Alex Minnie'),
(395, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-15 14:11:44', 'Alex Minnie'),
(396, 5, 1, 0, 'Accessed Customer Summary', '2017-03-15 14:23:20', 'Alex Minnie'),
(397, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-15 14:24:21', 'Alex Minnie'),
(398, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-15 14:41:34', 'Alex Minnie'),
(399, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:09:05', 'Alex Minnie'),
(400, 5, 1, 0, 'Accessed Customer Summary', '2017-03-16 16:14:53', 'Alex Minnie'),
(401, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:15:02', 'Alex Minnie'),
(402, 5, 1, 0, 'Added Customer Invoice INV0000009', '2017-03-16 16:15:09', 'Alex Minnie'),
(403, 5, 1, 0, 'Accessed Customer Summary', '2017-03-16 16:15:54', 'Alex Minnie'),
(404, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:16:14', 'Alex Minnie'),
(405, 5, 1, 0, 'Accessed Customer Summary', '2017-03-16 16:16:25', 'Alex Minnie'),
(406, 5, 1, 0, 'Accessed Customer Contacts', '2017-03-16 16:16:29', 'Alex Minnie'),
(407, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:16:36', 'Alex Minnie'),
(408, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:16:53', 'Alex Minnie'),
(409, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-16 16:17:52', 'Alex Minnie'),
(410, 5, 1, 0, 'Accessed Customer Profile', '2017-03-16 16:17:55', 'Alex Minnie'),
(411, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:25:10', 'Alex Minnie'),
(412, 5, 1, 0, 'Accessed Customer Profile', '2017-03-27 11:25:19', 'Alex Minnie'),
(413, 5, 1, 0, 'Updated Client Profile', '2017-03-27 11:25:54', 'Alex Minnie'),
(414, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:26:23', 'Alex Minnie'),
(415, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:26:29', 'Alex Minnie'),
(416, 5, 1, 0, 'Added Customer Invoice INV00000010', '2017-03-27 11:26:36', 'Alex Minnie'),
(417, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:33:00', 'Alex Minnie'),
(418, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:33:03', 'Alex Minnie'),
(419, 5, 1, 0, 'Accessed Customer Contacts', '2017-03-27 11:33:24', 'Alex Minnie'),
(420, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:33:25', 'Alex Minnie'),
(421, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:33:26', 'Alex Minnie'),
(422, 5, 1, 0, 'Added Customer Invoice INV00000011', '2017-03-27 11:33:50', 'Alex Minnie'),
(423, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:34:38', 'Alex Minnie'),
(424, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:34:56', 'Alex Minnie'),
(425, 5, 1, 0, 'Accessed Customer Invoices', '2017-03-27 11:36:44', 'Alex Minnie'),
(426, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:40:35', 'Alex Minnie'),
(427, 5, 1, 0, 'Accessed Customer Email Logs', '2017-03-27 11:40:41', 'Alex Minnie'),
(428, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:46:51', 'Alex Minnie'),
(429, 5, 1, 0, 'Accessed Customer Quotes', '2017-03-27 11:46:54', 'Alex Minnie'),
(430, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:47:52', 'Alex Minnie'),
(431, 5, 1, 0, 'Accessed Customer Quotes', '2017-03-27 11:47:54', 'Alex Minnie'),
(432, 5, 1, 0, 'Added Customer Quote QU0000004', '2017-03-27 11:48:48', 'Alex Minnie'),
(433, 5, 1, 0, 'Accessed Customer Summary', '2017-03-27 11:49:40', 'Alex Minnie'),
(434, 5, 1, 0, 'Accessed Customer Quotes', '2017-03-27 11:49:42', 'Alex Minnie'),
(435, 5, 1, 0, 'Accessed Customer Quotes', '2017-03-27 11:50:16', 'Alex Minnie'),
(436, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:29:23', 'Alex Minnie'),
(437, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:29:25', 'Alex Minnie'),
(438, 5, 1, 0, 'Accessed Customer Quotes', '2017-04-03 13:34:04', 'Alex Minnie'),
(439, 5, 1, 0, 'Accessed Customer Transactions', '2017-04-03 13:34:06', 'Alex Minnie'),
(440, 5, 1, 0, 'Accessed Customer Quotes', '2017-04-03 13:34:09', 'Alex Minnie'),
(441, 5, 1, 0, 'Accessed Customer Quotes', '2017-04-03 13:35:38', 'Alex Minnie'),
(442, 5, 1, 0, 'Updates Customer Quote QU0000004 status to Accepted', '2017-04-03 13:36:15', 'Alex Minnie'),
(443, 5, 1, 0, 'Accessed Customer Quotes', '2017-04-03 13:36:17', 'Alex Minnie'),
(444, 5, 1, 0, 'Updates Customer Quote QU0000004 status to Pending', '2017-04-03 13:36:28', 'Alex Minnie'),
(445, 5, 1, 0, 'Accessed Customer Quotes', '2017-04-03 13:36:30', 'Alex Minnie'),
(446, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:36:33', 'Alex Minnie'),
(447, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:36:43', 'Alex Minnie'),
(448, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:36:54', 'Alex Minnie'),
(449, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:37:43', 'Alex Minnie'),
(450, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:37:44', 'Alex Minnie'),
(451, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:42:01', 'Alex Minnie'),
(452, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:42:06', 'Alex Minnie'),
(453, 5, 1, 0, 'Added Customer Invoice INV00000012', '2017-04-03 13:42:26', 'Alex Minnie'),
(454, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:45:08', 'Alex Minnie'),
(455, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:45:10', 'Alex Minnie'),
(456, 5, 1, 0, 'Added Jobcard JBC6', '2017-04-03 13:45:27', 'Alex Minnie'),
(457, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:46:57', 'Alex Minnie'),
(458, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:47:00', 'Alex Minnie'),
(459, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:47:11', 'Alex Minnie'),
(460, 5, 1, 0, 'Added Customer Invoice INV00000013', '2017-04-03 13:47:17', 'Alex Minnie'),
(461, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:47:38', 'Alex Minnie'),
(462, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:48:19', 'Alex Minnie'),
(463, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:48:22', 'Alex Minnie'),
(464, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:50:03', 'Alex Minnie'),
(465, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:50:28', 'Alex Minnie'),
(466, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:50:39', 'Alex Minnie'),
(467, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:51:30', 'Alex Minnie'),
(468, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:51:34', 'Alex Minnie'),
(469, 5, 1, 0, 'Accessed Customer Summary', '2017-04-03 13:51:58', 'Alex Minnie'),
(470, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-03 13:52:00', 'Alex Minnie'),
(471, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-03 13:52:22', 'Alex Minnie'),
(472, 5, 1, 0, 'Accessed Customer Summary', '2017-04-11 12:15:31', 'Alex Minnie'),
(473, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-11 12:15:33', 'Alex Minnie'),
(474, 5, 1, 0, 'Accessed Customer Summary', '2017-04-11 12:29:41', 'Alex Minnie'),
(475, 5, 1, 0, 'Accessed Customer Jobcards', '2017-04-11 12:29:43', 'Alex Minnie'),
(476, 5, 1, 0, 'Accessed Customer Summary', '2017-04-11 12:31:26', 'Alex Minnie'),
(477, 5, 1, 0, 'Accessed Customer Invoices', '2017-04-11 12:31:28', 'Alex Minnie'),
(478, 5, 1, 0, 'Added Customer Invoice INV00000014', '2017-04-11 12:31:35', 'Alex Minnie'),
(479, 5, 1, 0, 'Accessed Customer Summary', '2017-04-26 10:38:14', 'Alex Minnie'),
(480, 5, 1, 0, 'Accessed Customer Documents', '2017-04-26 10:38:28', 'Alex Minnie'),
(481, 5, 1, 0, 'Accessed Customer Products', '2017-04-26 10:39:02', 'Alex Minnie'),
(482, 5, 1, 0, 'Accessed Customer Summary', '2017-04-26 10:44:16', 'Alex Minnie'),
(483, 5, 1, 0, 'Accessed Customer Task', '2017-04-26 10:44:59', 'Alex Minnie'),
(484, 5, 1, 0, 'Accessed Customer Summary', '2017-05-02 11:29:43', 'Alex Minnie'),
(485, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:29:55', 'Alex Minnie'),
(486, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:30:39', 'Alex Minnie'),
(487, 5, 1, 0, 'Added Customer Quote QU0000005', '2017-05-02 11:30:44', 'Alex Minnie'),
(488, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:31:02', 'Alex Minnie'),
(489, 5, 1, 0, 'Added Customer Invoice INV00000015', '2017-05-02 11:31:43', 'Alex Minnie'),
(490, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:32:12', 'Alex Minnie'),
(491, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:36:24', 'Alex Minnie'),
(492, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:36:28', 'Alex Minnie'),
(493, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:36:44', 'Alex Minnie'),
(494, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:37:29', 'Alex Minnie'),
(495, 5, 1, 0, 'Accessed Customer Summary', '2017-05-02 11:37:47', 'Alex Minnie'),
(496, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:37:49', 'Alex Minnie'),
(497, 5, 1, 0, 'Added Customer Quote QU0000006', '2017-05-02 11:37:56', 'Alex Minnie'),
(498, 5, 1, 0, 'Added Customer Quote QU0000007', '2017-05-02 11:37:59', 'Alex Minnie'),
(499, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:38:22', 'Alex Minnie'),
(500, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:39:28', 'Alex Minnie'),
(501, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:39:30', 'Alex Minnie'),
(502, 5, 1, 0, 'Accessed Customer Summary', '2017-05-02 11:41:43', 'Alex Minnie'),
(503, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:41:45', 'Alex Minnie'),
(504, 5, 1, 0, 'Added Customer Invoice INV00000016', '2017-05-02 11:43:34', 'Alex Minnie'),
(505, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:43:47', 'Alex Minnie'),
(506, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:44:00', 'Alex Minnie'),
(507, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:44:20', 'Alex Minnie'),
(508, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:44:32', 'Alex Minnie'),
(509, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:44:39', 'Alex Minnie'),
(510, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:44:43', 'Alex Minnie'),
(511, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:44:56', 'Alex Minnie'),
(512, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:45:05', 'Alex Minnie'),
(513, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:45:41', 'Alex Minnie'),
(514, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:45:49', 'Alex Minnie'),
(515, 5, 1, 0, 'Accessed Customer Summary', '2017-05-02 11:52:33', 'Alex Minnie'),
(516, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-02 11:52:38', 'Alex Minnie'),
(517, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-02 11:52:49', 'Alex Minnie'),
(518, 5, 1, 0, 'Accessed Customer Products', '2017-05-02 11:53:00', 'Alex Minnie'),
(519, 5, 1, 0, 'Accessed Customer Task', '2017-05-02 11:53:22', 'Alex Minnie'),
(520, 5, 1, 0, 'Accessed Customer Follow Ups', '2017-05-02 11:53:22', 'Alex Minnie'),
(521, 5, 1, 0, 'Accessed Customer Summary', '2017-05-02 11:53:25', 'Alex Minnie'),
(522, 5, 1, 0, 'Accessed Customer Summary', '2017-05-06 06:30:33', 'Alex Minnie'),
(523, 5, 1, 0, 'Accessed Customer Task', '2017-05-06 06:31:53', 'Alex Minnie'),
(524, 5, 1, 0, 'Accessed Customer Summary', '2017-05-06 06:45:20', 'Alex Minnie'),
(525, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-06 06:45:32', 'Alex Minnie'),
(526, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-06 06:48:30', 'Alex Minnie'),
(527, 5, 1, 0, 'Accessed Customer Quotes', '2017-05-06 06:49:40', 'Alex Minnie'),
(528, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-06 06:52:16', 'Alex Minnie'),
(529, 5, 1, 0, 'Accessed Customer Summary', '2017-05-06 07:05:42', 'Alex Minnie'),
(530, 5, 1, 0, 'Accessed Customer Profile', '2017-05-06 07:05:48', 'Alex Minnie'),
(531, 5, 1, 0, 'Accessed Customer Contacts', '2017-05-06 07:05:51', 'Alex Minnie'),
(532, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-06 07:05:57', 'Alex Minnie'),
(533, 5, 1, 0, 'Accessed Customer Summary', '2017-05-12 11:24:52', 'Alex Minnie'),
(534, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-12 11:24:54', 'Alex Minnie'),
(535, 5, 1, 0, 'Added Customer Invoice INV00000017', '2017-05-12 11:25:00', 'Alex Minnie'),
(536, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-12 11:25:10', 'Alex Minnie'),
(537, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-12 11:25:11', 'Alex Minnie'),
(538, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-12 11:25:23', 'Alex Minnie'),
(539, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 04:39:10', 'Alex Minnie'),
(540, 6, 1, 0, 'Accessed Customer Profile', '2017-05-13 04:39:24', 'Alex Minnie'),
(541, 6, 1, 0, 'Accessed Customer Contacts', '2017-05-13 04:39:35', 'Alex Minnie'),
(542, 6, 1, 0, 'Contact Added Support Department', '2017-05-13 04:43:14', 'Alex Minnie'),
(543, 6, 1, 0, 'Accessed Customer Contacts', '2017-05-13 04:43:16', 'Alex Minnie'),
(544, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 04:43:20', 'Alex Minnie'),
(545, 6, 1, 0, 'Added Customer Invoice INV00000018', '2017-05-13 04:44:10', 'Alex Minnie'),
(546, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 04:48:03', 'Alex Minnie'),
(547, 6, 1, 0, 'Accessed Customer Profile', '2017-05-13 04:48:08', 'Alex Minnie'),
(548, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 04:48:29', 'Alex Minnie'),
(549, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 04:49:01', 'Alex Minnie'),
(550, 6, 1, 0, 'Accessed Customer Profile', '2017-05-13 04:49:06', 'Alex Minnie'),
(551, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 04:49:15', 'Alex Minnie'),
(552, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 04:49:52', 'Alex Minnie'),
(553, 6, 1, 0, 'Accessed Customer Profile', '2017-05-13 04:49:55', 'Alex Minnie'),
(554, 6, 1, 0, 'Accessed Customer Contacts', '2017-05-13 04:49:57', 'Alex Minnie'),
(555, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 04:49:58', 'Alex Minnie'),
(556, 6, 1, 0, 'Added customer transaction 124', '2017-05-13 04:58:05', 'Alex Minnie'),
(557, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 04:58:09', 'Alex Minnie'),
(558, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 04:58:31', 'Alex Minnie'),
(559, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 04:58:39', 'Alex Minnie'),
(560, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 04:58:50', 'Alex Minnie'),
(561, 6, 1, 0, 'Accessed Customer Email Logs', '2017-05-13 04:59:17', 'Alex Minnie'),
(562, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 04:59:22', 'Alex Minnie'),
(563, 6, 1, 0, 'Accessed Customer Email Logs', '2017-05-13 04:59:32', 'Alex Minnie'),
(564, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:01:43', 'Alex Minnie'),
(565, 6, 1, 0, 'Added Customer Quote QU0000008', '2017-05-13 05:01:53', 'Alex Minnie'),
(566, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:03:14', 'Alex Minnie'),
(567, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:03:25', 'Alex Minnie'),
(568, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:08:10', 'Alex Minnie'),
(569, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:08:11', 'Alex Minnie'),
(570, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:08:28', 'Alex Minnie'),
(571, 6, 1, 0, 'Added Customer Quote QU0000009', '2017-05-13 05:08:37', 'Alex Minnie'),
(572, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:09:22', 'Alex Minnie'),
(573, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:09:24', 'Alex Minnie'),
(574, 6, 1, 0, 'Added Customer Invoice INV00000019', '2017-05-13 05:09:31', 'Alex Minnie'),
(575, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:20:18', 'Alex Minnie'),
(576, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:20:24', 'Alex Minnie'),
(577, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:20:54', 'Alex Minnie'),
(578, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:21:37', 'Alex Minnie'),
(579, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:21:59', 'Alex Minnie'),
(580, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:25:43', 'Alex Minnie'),
(581, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:27:32', 'Alex Minnie'),
(582, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:31:49', 'Alex Minnie'),
(583, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:31:56', 'Alex Minnie'),
(584, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:32:01', 'Alex Minnie'),
(585, 6, 1, 0, 'Added Customer Invoice INV00000020', '2017-05-13 05:33:54', 'Alex Minnie'),
(586, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:34:20', 'Alex Minnie'),
(587, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:34:36', 'Alex Minnie'),
(588, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:34:48', 'Alex Minnie'),
(589, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:34:58', 'Alex Minnie'),
(590, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:35:04', 'Alex Minnie'),
(591, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:35:16', 'Alex Minnie'),
(592, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:35:23', 'Alex Minnie'),
(593, 6, 1, 0, 'Added customer transaction 225', '2017-05-13 05:36:01', 'Alex Minnie'),
(594, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 05:36:03', 'Alex Minnie'),
(595, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:36:06', 'Alex Minnie'),
(596, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:36:25', 'Alex Minnie'),
(597, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:36:29', 'Alex Minnie'),
(598, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:37:45', 'Alex Minnie'),
(599, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 05:37:48', 'Alex Minnie'),
(600, 6, 1, 0, 'Accessed Customer Statements', '2017-05-13 05:37:53', 'Alex Minnie'),
(601, 6, 1, 0, 'Accessed Customer Statements', '2017-05-13 05:38:06', 'Alex Minnie'),
(602, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:38:30', 'Alex Minnie'),
(603, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-13 05:39:11', 'Alex Minnie'),
(604, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-13 05:39:18', 'Alex Minnie'),
(605, 6, 1, 0, 'Accessed Customer Statements', '2017-05-13 05:39:20', 'Alex Minnie'),
(606, 6, 1, 0, 'Accessed Customer Documents', '2017-05-13 05:39:46', 'Alex Minnie'),
(607, 6, 1, 0, 'Added Document Payment', '2017-05-13 05:40:13', 'Alex Minnie'),
(608, 6, 1, 0, 'Accessed Customer Documents', '2017-05-13 05:40:13', 'Alex Minnie'),
(609, 6, 1, 0, 'Accessed Customer Notes', '2017-05-13 05:41:56', 'Alex Minnie'),
(610, 6, 1, 0, 'Added Customer Note', '2017-05-13 05:42:39', 'Alex Minnie'),
(611, 6, 1, 0, 'Accessed Customer Notes', '2017-05-13 05:42:42', 'Alex Minnie'),
(612, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:42:46', 'Alex Minnie'),
(613, 6, 1, 0, 'Accessed Customer Documents', '2017-05-13 05:42:51', 'Alex Minnie'),
(614, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-13 05:42:54', 'Alex Minnie'),
(615, 6, 1, 0, 'Added Jobcard JBC7', '2017-05-13 05:43:37', 'Alex Minnie'),
(616, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-13 05:45:10', 'Alex Minnie'),
(617, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:45:20', 'Alex Minnie');
INSERT INTO `customeraccess` (`CustomerAccessLogID`, `CustomerID`, `ClientID`, `EmployeeID`, `LogType`, `LogDate`, `AccessName`) VALUES
(618, 5, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:45:49', 'Alex Minnie'),
(619, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:45:52', 'Alex Minnie'),
(620, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:46:04', 'Alex Minnie'),
(621, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:46:06', 'Alex Minnie'),
(622, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-13 05:46:14', 'Alex Minnie'),
(623, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:47:01', 'Alex Minnie'),
(624, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 05:47:03', 'Alex Minnie'),
(625, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:47:19', 'Alex Minnie'),
(626, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-13 05:47:21', 'Alex Minnie'),
(627, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:47:42', 'Alex Minnie'),
(628, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-13 05:50:22', 'Alex Minnie'),
(629, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 05:54:24', 'Alex Minnie'),
(630, 6, 1, 0, 'Accessed Customer Notes', '2017-05-13 05:54:34', 'Alex Minnie'),
(631, 6, 1, 0, 'Accessed Customer Products', '2017-05-13 05:54:37', 'Alex Minnie'),
(632, 6, 1, 0, 'Accessed Customer Task', '2017-05-13 05:54:44', 'Alex Minnie'),
(633, 6, 1, 0, 'Added Customer Task', '2017-05-13 05:55:08', 'Alex Minnie'),
(634, 6, 1, 0, 'Accessed Customer Task', '2017-05-13 05:55:10', 'Alex Minnie'),
(635, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:00:08', 'Alex Minnie'),
(636, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-05-13 06:00:11', 'Alex Minnie'),
(637, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:00:25', 'Alex Minnie'),
(638, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-05-13 06:00:31', 'Alex Minnie'),
(639, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:00:46', 'Alex Minnie'),
(640, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-05-13 06:00:50', 'Alex Minnie'),
(641, 6, 1, 0, 'Added Customer Follow Up', '2017-05-13 06:01:51', 'Alex Minnie'),
(642, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-05-13 06:01:53', 'Alex Minnie'),
(643, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:02:23', 'Alex Minnie'),
(644, 6, 1, 0, 'Accessed Customer Email Logs', '2017-05-13 06:02:25', 'Alex Minnie'),
(645, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 06:03:25', 'Alex Minnie'),
(646, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 06:03:36', 'Alex Minnie'),
(647, 6, 1, 0, 'Accessed Customer Email Logs', '2017-05-13 06:03:39', 'Alex Minnie'),
(648, 6, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:34:36', 'Alex Minnie'),
(649, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-13 06:34:38', 'Alex Minnie'),
(650, 5, 1, 0, 'Accessed Customer Summary', '2017-05-13 06:34:53', 'Alex Minnie'),
(651, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-13 06:34:56', 'Alex Minnie'),
(652, 6, 1, 0, 'Accessed Customer Summary', '2017-05-24 11:58:44', 'Alex Minnie'),
(653, 6, 1, 0, 'Accessed Customer Transactions', '2017-05-24 11:59:03', 'Alex Minnie'),
(654, 6, 1, 0, 'Accessed Customer Summary', '2017-05-24 11:59:16', 'Alex Minnie'),
(655, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-24 12:00:07', 'Alex Minnie'),
(656, 6, 1, 0, 'Accessed Customer Summary', '2017-05-29 08:44:04', 'Alex Minnie'),
(657, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 08:44:08', 'Alex Minnie'),
(658, 6, 1, 0, 'Accessed Customer Summary', '2017-05-29 09:21:03', 'Alex Minnie'),
(659, 6, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 09:21:07', 'Alex Minnie'),
(660, 5, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 09:21:54', 'Alex Minnie'),
(661, 6, 1, 0, 'Accessed Customer Summary', '2017-05-29 09:29:49', 'Alex Minnie'),
(662, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-29 09:29:50', 'Alex Minnie'),
(663, 6, 1, 0, 'Accessed Customer Quotes', '2017-05-29 09:30:12', 'Alex Minnie'),
(664, 5, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 10:47:10', 'Alex Minnie'),
(665, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-29 10:47:16', 'Alex Minnie'),
(666, 5, 1, 0, 'Accessed Customer Summary', '2017-05-29 10:48:11', 'Alex Minnie'),
(667, 5, 1, 0, 'Accessed Customer Profile', '2017-05-29 10:48:15', 'Alex Minnie'),
(668, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-29 10:48:21', 'Alex Minnie'),
(669, 5, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 10:48:24', 'Alex Minnie'),
(670, 5, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 10:49:12', 'Alex Minnie'),
(671, 5, 1, 0, 'Accessed Customer Invoices', '2017-05-29 10:49:14', 'Alex Minnie'),
(672, 5, 1, 0, 'Accessed Customer Summary', '2017-05-29 10:49:35', 'Alex Minnie'),
(673, 5, 1, 0, 'Accessed Customer Profile', '2017-05-29 10:49:38', 'Alex Minnie'),
(674, 5, 1, 0, 'Accessed Customer Jobcards', '2017-05-29 10:50:37', 'Alex Minnie'),
(675, 5, 1, 0, 'Accessed Customer Profile', '2017-05-29 10:50:53', 'Alex Minnie'),
(676, 5, 1, 0, 'Accessed Customer Contacts', '2017-05-29 10:50:58', 'Alex Minnie'),
(677, 6, 1, 0, 'Accessed Customer Summary', '2017-05-29 10:55:35', 'Alex Minnie'),
(678, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-29 10:55:37', 'Alex Minnie'),
(679, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 10:14:46', 'Alex Minnie'),
(680, 6, 1, 0, 'Accessed Customer Profile', '2017-05-30 10:14:53', 'Alex Minnie'),
(681, 6, 1, 0, 'Accessed Customer Products', '2017-05-30 10:15:01', 'Alex Minnie'),
(682, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 10:15:26', 'Alex Minnie'),
(683, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 10:17:20', 'Alex Minnie'),
(684, 6, 1, 0, 'Accessed Customer Products', '2017-05-30 10:17:23', 'Alex Minnie'),
(685, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 11:35:36', 'Alex Minnie'),
(686, 6, 1, 0, 'Accessed Customer Profile', '2017-05-30 11:35:38', 'Alex Minnie'),
(687, 6, 1, 0, 'Accessed Customer Contacts', '2017-05-30 11:36:06', 'Alex Minnie'),
(688, 6, 1, 0, 'Accessed Customer Profile', '2017-05-30 11:36:13', 'Alex Minnie'),
(689, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 13:20:21', 'Alex Minnie'),
(690, 6, 1, 0, 'Accessed Customer Summary', '2017-05-30 14:55:54', 'Alex Minnie'),
(691, 6, 1, 0, 'Accessed Customer Profile', '2017-05-30 14:55:55', 'Alex Minnie'),
(692, 6, 1, 0, 'Accessed Customer Contacts', '2017-05-30 14:56:07', 'Alex Minnie'),
(693, 6, 1, 0, 'Accessed Customer Profile', '2017-05-30 14:56:32', 'Alex Minnie'),
(694, 6, 1, 0, 'Accessed Customer Invoices', '2017-05-30 14:56:47', 'Alex Minnie'),
(695, 6, 1, 0, 'Accessed Customer Summary', '2017-05-31 08:17:20', 'Alex Minnie'),
(696, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 11:54:53', 'Alex Minnie'),
(697, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 11:54:55', 'Alex Minnie'),
(698, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 11:55:32', 'Alex Minnie'),
(699, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 11:56:37', 'Alex Minnie'),
(700, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 11:58:05', 'Alex Minnie'),
(701, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 11:58:43', 'Alex Minnie'),
(702, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 11:58:44', 'Alex Minnie'),
(703, 5, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:54:13', 'Alex Minnie'),
(704, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:54:38', 'Alex Minnie'),
(705, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:55:05', 'Alex Minnie'),
(706, 5, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:55:14', 'Alex Minnie'),
(707, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-01 12:55:27', 'Alex Minnie'),
(708, 5, 1, 0, 'Accessed Customer Contacts', '2017-06-01 12:55:39', 'Alex Minnie'),
(709, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:55:53', 'Alex Minnie'),
(710, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:55:55', 'Alex Minnie'),
(711, 5, 1, 0, 'Added Customer Invoice INV00000021', '2017-06-01 12:56:01', 'Alex Minnie'),
(712, 5, 1, 0, 'Accessed Customer Quotes', '2017-06-01 12:56:58', 'Alex Minnie'),
(713, 5, 1, 0, 'Added Customer Quote QU00000010', '2017-06-01 12:57:05', 'Alex Minnie'),
(714, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:57:24', 'Alex Minnie'),
(715, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:57:27', 'Alex Minnie'),
(716, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:57:43', 'Alex Minnie'),
(717, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:57:54', 'Alex Minnie'),
(718, 5, 1, 0, 'Accessed Customer Summary', '2017-06-01 12:57:55', 'Alex Minnie'),
(719, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:57:57', 'Alex Minnie'),
(720, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:58:07', 'Alex Minnie'),
(721, 5, 1, 0, 'Accessed Customer Transactions', '2017-06-01 12:58:10', 'Alex Minnie'),
(722, 5, 1, 0, 'Accessed Customer Profile', '2017-06-01 12:58:37', 'Alex Minnie'),
(723, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:59:13', 'Alex Minnie'),
(724, 5, 1, 0, 'Accessed Customer Profile', '2017-06-01 12:59:27', 'Alex Minnie'),
(725, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 12:59:58', 'Alex Minnie'),
(726, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:01:03', 'Alex Minnie'),
(727, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:01:10', 'Alex Minnie'),
(728, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:01:36', 'Alex Minnie'),
(729, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:01:42', 'Alex Minnie'),
(730, 5, 1, 0, 'Accessed Customer Summary', '2017-06-01 13:02:00', 'Alex Minnie'),
(731, 5, 1, 0, 'Accessed Customer Task', '2017-06-01 13:04:56', 'Alex Minnie'),
(732, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 13:08:21', 'Alex Minnie'),
(733, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 13:10:25', 'Alex Minnie'),
(734, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:10:32', 'Alex Minnie'),
(735, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 13:10:38', 'Alex Minnie'),
(736, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:10:40', 'Alex Minnie'),
(737, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:10:56', 'Alex Minnie'),
(738, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:11:01', 'Alex Minnie'),
(739, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:01', 'Alex Minnie'),
(740, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:11:04', 'Alex Minnie'),
(741, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:07', 'Alex Minnie'),
(742, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:08', 'Alex Minnie'),
(743, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:15', 'Alex Minnie'),
(744, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:11:17', 'Alex Minnie'),
(745, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:52', 'Alex Minnie'),
(746, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:11:56', 'Alex Minnie'),
(747, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:11:59', 'Alex Minnie'),
(748, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:12:08', 'Alex Minnie'),
(749, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:12:12', 'Alex Minnie'),
(750, 6, 1, 0, 'Accessed Customer Summary', '2017-06-01 13:14:33', 'Alex Minnie'),
(751, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:14:36', 'Alex Minnie'),
(752, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:15:37', 'Alex Minnie'),
(753, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:15:39', 'Alex Minnie'),
(754, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:16:08', 'Alex Minnie'),
(755, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:16:09', 'Alex Minnie'),
(756, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:17:09', 'Alex Minnie'),
(757, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-01 13:17:10', 'Alex Minnie'),
(758, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-01 13:18:31', 'Alex Minnie'),
(759, 6, 1, 0, 'Accessed Customer Summary', '2017-06-05 08:11:39', 'Alex Minnie'),
(760, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-05 08:11:41', 'Alex Minnie'),
(761, 6, 1, 0, 'Accessed Customer Summary', '2017-06-07 11:12:00', 'Alex Minnie'),
(762, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-07 11:12:03', 'Alex Minnie'),
(763, 6, 1, 0, 'Added Jobcard JBC8', '2017-06-07 11:56:10', 'Alex Minnie'),
(764, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 10:24:18', 'Alex Minnie'),
(765, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 10:24:28', 'Alex Minnie'),
(766, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:25:13', 'Alex Minnie'),
(767, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 10:25:19', 'Alex Minnie'),
(768, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-08 10:25:52', 'Alex Minnie'),
(769, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:25:57', 'Alex Minnie'),
(770, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:26:16', 'Alex Minnie'),
(771, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:28:13', 'Alex Minnie'),
(772, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:28:25', 'Alex Minnie'),
(773, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:28:33', 'Alex Minnie'),
(774, 6, 1, 0, 'Added Customer Invoice INV00000022', '2017-06-08 10:28:41', 'Alex Minnie'),
(775, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:30:29', 'Alex Minnie'),
(776, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:30:54', 'Alex Minnie'),
(777, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 10:33:01', 'Alex Minnie'),
(778, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-08 10:33:03', 'Alex Minnie'),
(779, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 10:33:07', 'Alex Minnie'),
(780, 6, 1, 0, 'Added Customer Quote QU00000011', '2017-06-08 10:33:17', 'Alex Minnie'),
(781, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 10:33:48', 'Alex Minnie'),
(782, 6, 1, 0, 'Accessed Customer Documents', '2017-06-08 10:35:36', 'Alex Minnie'),
(783, 6, 1, 0, 'Added Document Test', '2017-06-08 10:36:08', 'Alex Minnie'),
(784, 6, 1, 0, 'Accessed Customer Documents', '2017-06-08 10:36:08', 'Alex Minnie'),
(785, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 10:36:17', 'Alex Minnie'),
(786, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 10:49:15', 'Alex Minnie'),
(787, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 10:49:33', 'Alex Minnie'),
(788, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-08 10:49:36', 'Alex Minnie'),
(789, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 10:56:30', 'Alex Minnie'),
(790, 6, 1, 0, 'Accessed Customer Sites', '2017-06-08 10:56:36', 'Alex Minnie'),
(791, 6, 1, 0, 'Accessed Customer Profile', '2017-06-08 10:56:52', 'Alex Minnie'),
(792, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 10:56:55', 'Alex Minnie'),
(793, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 10:56:59', 'Alex Minnie'),
(794, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:26:50', 'Alex Minnie'),
(795, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 11:26:53', 'Alex Minnie'),
(796, 6, 1, 0, 'Added Customer Invoice INV00000024', '2017-06-08 11:28:16', 'Alex Minnie'),
(797, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:28:38', 'Alex Minnie'),
(798, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 11:29:18', 'Alex Minnie'),
(799, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 11:29:30', 'Alex Minnie'),
(800, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:29:36', 'Alex Minnie'),
(801, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:30:22', 'Alex Minnie'),
(802, 6, 1, 0, 'Added Customer Invoice INV00000025', '2017-06-08 11:31:35', 'Alex Minnie'),
(803, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:31:41', 'Alex Minnie'),
(804, 5, 1, 0, 'Added Customer Invoice INV00000026', '2017-06-08 11:32:02', 'Alex Minnie'),
(805, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:32:21', 'Alex Minnie'),
(806, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:32:28', 'Alex Minnie'),
(807, 5, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:32:33', 'Alex Minnie'),
(808, 5, 1, 0, 'Added Customer Invoice INV0000001', '2017-06-08 11:33:26', 'Alex Minnie'),
(809, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:33:39', 'Alex Minnie'),
(810, 5, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:34:08', 'Alex Minnie'),
(811, 5, 1, 0, 'Added Jobcard JBC9', '2017-06-08 11:34:19', 'Alex Minnie'),
(812, 5, 1, 0, 'Added Customer Invoice INV0000002', '2017-06-08 11:34:50', 'Alex Minnie'),
(813, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:35:04', 'Alex Minnie'),
(814, 5, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:35:09', 'Alex Minnie'),
(815, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:35:11', 'Alex Minnie'),
(816, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:37:13', 'Alex Minnie'),
(817, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:37:16', 'Alex Minnie'),
(818, 5, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:37:20', 'Alex Minnie'),
(819, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:37:22', 'Alex Minnie'),
(820, 5, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:37:35', 'Alex Minnie'),
(821, 5, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:40:23', 'Alex Minnie'),
(822, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:40:43', 'Alex Minnie'),
(823, 5, 1, 0, 'Added Customer Invoice INV0000003', '2017-06-08 11:40:48', 'Alex Minnie'),
(824, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:42:31', 'Alex Minnie'),
(825, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:42:41', 'Alex Minnie'),
(826, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:43:07', 'Alex Minnie'),
(827, 5, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:44:21', 'Alex Minnie'),
(828, 5, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:44:24', 'Alex Minnie'),
(829, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:45:08', 'Alex Minnie'),
(830, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:45:19', 'Alex Minnie'),
(831, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 11:46:08', 'Alex Minnie'),
(832, 5, 1, 0, 'Accessed Customer Summary', '2017-06-08 11:47:44', 'Alex Minnie'),
(833, 5, 1, 0, 'Accessed Customer Invoices', '2017-06-08 11:47:49', 'Alex Minnie'),
(834, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 14:07:45', 'Alex Minnie'),
(835, 6, 1, 0, 'Accessed Customer Profile', '2017-06-08 14:07:51', 'Alex Minnie'),
(836, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-08 14:07:57', 'Alex Minnie'),
(837, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 14:08:00', 'Alex Minnie'),
(838, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 14:08:01', 'Alex Minnie'),
(839, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-08 14:08:03', 'Alex Minnie'),
(840, 6, 1, 0, 'Accessed Customer Statements', '2017-06-08 14:08:04', 'Alex Minnie'),
(841, 6, 1, 0, 'Accessed Customer Documents', '2017-06-08 14:08:06', 'Alex Minnie'),
(842, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 14:08:09', 'Alex Minnie'),
(843, 6, 1, 0, 'Accessed Customer Notes', '2017-06-08 14:08:12', 'Alex Minnie'),
(844, 6, 1, 0, 'Accessed Customer Products', '2017-06-08 14:08:12', 'Alex Minnie'),
(845, 6, 1, 0, 'Accessed Customer Summary', '2017-06-08 14:51:47', 'Alex Minnie'),
(846, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-08 14:51:57', 'Alex Minnie'),
(847, 6, 1, 0, 'Accessed Customer Profile', '2017-06-08 14:51:57', 'Alex Minnie'),
(848, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-08 14:52:01', 'Alex Minnie'),
(849, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-08 14:52:07', 'Alex Minnie'),
(850, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-08 14:52:08', 'Alex Minnie'),
(851, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-08 14:52:09', 'Alex Minnie'),
(852, 6, 1, 0, 'Accessed Customer Statements', '2017-06-08 14:52:12', 'Alex Minnie'),
(853, 6, 1, 0, 'Accessed Customer Documents', '2017-06-08 14:52:13', 'Alex Minnie'),
(854, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-08 14:52:15', 'Alex Minnie'),
(855, 6, 1, 0, 'Accessed Customer Documents', '2017-06-09 08:55:18', 'Alex Minnie'),
(856, 6, 1, 0, 'Accessed Customer Notes', '2017-06-09 09:00:50', 'Alex Minnie'),
(857, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 09:00:54', 'Alex Minnie'),
(858, 6, 1, 0, 'Accessed Customer Task', '2017-06-09 09:00:57', 'Alex Minnie'),
(859, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 09:01:01', 'Alex Minnie'),
(860, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 09:01:05', 'Alex Minnie'),
(861, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 09:03:38', 'Alex Minnie'),
(862, 6, 1, 0, 'Accessed Customer Profile', '2017-06-09 09:09:40', 'Alex Minnie'),
(863, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 09:10:29', 'Alex Minnie'),
(864, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-09 09:10:36', 'Alex Minnie'),
(865, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 09:10:39', 'Alex Minnie'),
(866, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 09:10:43', 'Alex Minnie'),
(867, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-09 09:10:45', 'Alex Minnie'),
(868, 6, 1, 0, 'Accessed Customer Statements', '2017-06-09 09:10:47', 'Alex Minnie'),
(869, 6, 1, 0, 'Accessed Customer Documents', '2017-06-09 09:10:51', 'Alex Minnie'),
(870, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 09:10:57', 'Alex Minnie'),
(871, 6, 1, 0, 'Accessed Customer Notes', '2017-06-09 09:11:13', 'Alex Minnie'),
(872, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 09:11:19', 'Alex Minnie'),
(873, 6, 1, 0, 'Accessed Customer Task', '2017-06-09 09:11:41', 'Alex Minnie'),
(874, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 09:11:46', 'Alex Minnie'),
(875, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 09:11:47', 'Alex Minnie'),
(876, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 09:12:36', 'Alex Minnie'),
(877, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 09:12:43', 'Alex Minnie'),
(878, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 10:54:15', 'Alex Minnie'),
(879, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 10:54:16', 'Alex Minnie'),
(880, 6, 1, 0, 'Added Customer Invoice INV0000004', '2017-06-09 10:54:23', 'Alex Minnie'),
(881, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 10:55:49', 'Alex Minnie'),
(882, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 10:55:54', 'Alex Minnie'),
(883, 6, 1, 0, 'Added Customer Invoice INV0000005', '2017-06-09 10:56:03', 'Alex Minnie'),
(884, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 10:56:09', 'Alex Minnie'),
(885, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 10:57:38', 'Alex Minnie'),
(886, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 10:57:39', 'Alex Minnie'),
(887, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 14:21:48', 'Alex Minnie'),
(888, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 14:22:13', 'Alex Minnie'),
(889, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 14:22:16', 'Alex Minnie'),
(890, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 14:54:03', 'Alex Minnie'),
(891, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 14:54:05', 'Alex Minnie'),
(892, 6, 1, 0, 'Accessed Customer Profile', '2017-06-09 14:54:31', 'Alex Minnie'),
(893, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 14:56:26', 'Alex Minnie'),
(894, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 14:56:29', 'Alex Minnie'),
(895, 6, 1, 0, 'Added Customer Quote QU00000012', '2017-06-09 14:56:53', 'Alex Minnie'),
(896, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:02:29', 'Alex Minnie'),
(897, 6, 1, 0, 'Added Customer Invoice INV0000006', '2017-06-09 15:02:43', 'Alex Minnie'),
(898, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:06:37', 'Alex Minnie'),
(899, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:07:29', 'Alex Minnie'),
(900, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:07:48', 'Alex Minnie'),
(901, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:08:09', 'Alex Minnie'),
(902, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:08:41', 'Alex Minnie'),
(903, 6, 1, 0, 'Added Customer Quote QU00000013', '2017-06-09 15:08:46', 'Alex Minnie'),
(904, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:09:14', 'Alex Minnie'),
(905, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:09:17', 'Alex Minnie'),
(906, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:09:28', 'Alex Minnie'),
(907, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:09:41', 'Alex Minnie'),
(908, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:11:09', 'Alex Minnie'),
(909, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:11:21', 'Alex Minnie'),
(910, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:12:04', 'Alex Minnie'),
(911, 6, 1, 0, 'Added Customer Invoice INV0000007', '2017-06-09 15:12:11', 'Alex Minnie'),
(912, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:12:18', 'Alex Minnie'),
(913, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:13:04', 'Alex Minnie'),
(914, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:13:14', 'Alex Minnie'),
(915, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:14:23', 'Alex Minnie'),
(916, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-09 15:15:17', 'Alex Minnie'),
(917, 6, 1, 0, 'Accessed Customer Statements', '2017-06-09 15:15:32', 'Alex Minnie'),
(918, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:16:40', 'Alex Minnie'),
(919, 6, 1, 0, 'Accessed Customer Statements', '2017-06-09 15:16:46', 'Alex Minnie'),
(920, 6, 1, 0, 'Accessed Customer Documents', '2017-06-09 15:17:11', 'Alex Minnie'),
(921, 6, 1, 0, 'Accessed Customer Documents', '2017-06-09 15:17:31', 'Alex Minnie'),
(922, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 15:17:59', 'Alex Minnie'),
(923, 6, 1, 0, 'Added Jobcard JBC10', '2017-06-09 15:18:30', 'Alex Minnie'),
(924, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:21:06', 'Alex Minnie'),
(925, 6, 1, 0, 'Added Customer Invoice INV0000008', '2017-06-09 15:21:34', 'Alex Minnie'),
(926, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:24:51', 'Alex Minnie'),
(927, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:25:37', 'Alex Minnie'),
(928, 6, 1, 0, 'Added Customer Invoice INV0000009', '2017-06-09 15:26:02', 'Alex Minnie'),
(929, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:26:23', 'Alex Minnie'),
(930, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 15:26:50', 'Alex Minnie'),
(931, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:27:24', 'Alex Minnie'),
(932, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:27:25', 'Alex Minnie'),
(933, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:28:27', 'Alex Minnie'),
(934, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:28:50', 'Alex Minnie'),
(935, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 15:28:59', 'Alex Minnie'),
(936, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:29:33', 'Alex Minnie'),
(937, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 15:29:48', 'Alex Minnie'),
(938, 6, 1, 0, 'Accessed Customer Notes', '2017-06-09 15:30:16', 'Alex Minnie'),
(939, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:30:24', 'Alex Minnie'),
(940, 6, 1, 0, 'Accessed Customer Notes', '2017-06-09 15:30:35', 'Alex Minnie'),
(941, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:30:37', 'Alex Minnie'),
(942, 6, 1, 0, 'Added Customer Note', '2017-06-09 15:30:54', 'Alex Minnie'),
(943, 6, 1, 0, 'Accessed Customer Notes', '2017-06-09 15:30:57', 'Alex Minnie'),
(944, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:31:02', 'Alex Minnie'),
(945, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-09 15:31:19', 'Alex Minnie'),
(946, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:31:38', 'Alex Minnie'),
(947, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 15:31:58', 'Alex Minnie'),
(948, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:34:02', 'Alex Minnie'),
(949, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 15:34:05', 'Alex Minnie'),
(950, 6, 1, 0, 'Added ccustomer product', '2017-06-09 15:36:34', 'Alex Minnie'),
(951, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 15:36:37', 'Alex Minnie'),
(952, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:36:40', 'Alex Minnie'),
(953, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-09 15:39:48', 'Alex Minnie'),
(954, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 15:41:07', 'Alex Minnie'),
(955, 6, 1, 0, 'Accessed Customer Task', '2017-06-09 15:41:33', 'Alex Minnie'),
(956, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 15:42:10', 'Alex Minnie'),
(957, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 15:42:27', 'Alex Minnie'),
(958, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 15:42:43', 'Alex Minnie'),
(959, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 15:44:08', 'Alex Minnie'),
(960, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 15:44:21', 'Alex Minnie'),
(961, 6, 1, 0, 'Updated Customer Follow Up', '2017-06-09 15:44:39', 'Alex Minnie'),
(962, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-09 15:44:41', 'Alex Minnie'),
(963, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 16:08:36', 'Alex Minnie'),
(964, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 16:23:23', 'Alex Minnie'),
(965, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 16:23:27', 'Alex Minnie'),
(966, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 16:24:07', 'Alex Minnie'),
(967, 6, 1, 0, 'Added Client Site - Test', '2017-06-09 16:24:54', 'Alex Minnie'),
(968, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 16:25:24', 'Alex Minnie'),
(969, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 16:26:14', 'Alex Minnie'),
(970, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 16:26:17', 'Alex Minnie'),
(971, 6, 1, 0, 'Added ccustomer product', '2017-06-09 16:26:43', 'Alex Minnie'),
(972, 6, 1, 0, 'Added Client Site - Test', '2017-06-09 16:28:01', 'Alex Minnie'),
(973, 6, 1, 0, 'Accessed Customer Sites', '2017-06-09 16:28:01', 'Alex Minnie'),
(974, 6, 1, 0, 'Accessed Customer Summary', '2017-06-09 16:38:07', 'Alex Minnie'),
(975, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 16:38:10', 'Alex Minnie'),
(976, 6, 1, 0, 'Added Customer Invoice INV00000012', '2017-06-09 16:38:17', 'Alex Minnie'),
(977, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-09 16:38:58', 'Alex Minnie'),
(978, 6, 1, 0, 'Accessed Customer Products', '2017-06-09 16:49:59', 'Alex Minnie'),
(979, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 12:33:54', 'Alex Minnie'),
(980, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-13 12:33:57', 'Alex Minnie'),
(981, 6, 1, 0, 'Added Customer Invoice INV00000013', '2017-06-13 12:34:06', 'Alex Minnie'),
(982, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 12:39:47', 'Alex Minnie'),
(983, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-13 12:39:50', 'Alex Minnie'),
(984, 6, 1, 0, 'Added Customer Invoice INV00000014', '2017-06-13 12:40:01', 'Alex Minnie'),
(985, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 12:41:00', 'Alex Minnie'),
(986, 6, 1, 0, 'Accessed Customer Profile', '2017-06-13 12:41:02', 'Alex Minnie'),
(987, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 14:13:25', 'Alex Minnie'),
(988, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 14:14:14', 'Alex Minnie'),
(989, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-13 14:14:19', 'Alex Minnie'),
(990, 6, 1, 0, 'Accessed Customer Products', '2017-06-13 14:14:22', 'Alex Minnie'),
(991, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 14:16:53', 'Alex Minnie'),
(992, 6, 1, 0, 'Accessed Customer Products', '2017-06-13 14:16:56', 'Alex Minnie'),
(993, 6, 1, 0, 'Added ccustomer product', '2017-06-13 14:17:50', 'Alex Minnie'),
(994, 6, 1, 0, 'Accessed Customer Products', '2017-06-13 14:19:40', 'Alex Minnie'),
(995, 6, 1, 0, 'Accessed Customer Summary', '2017-06-13 14:21:32', 'Alex Minnie'),
(996, 6, 1, 0, 'Accessed Customer Products', '2017-06-13 14:21:35', 'Alex Minnie'),
(997, 6, 1, 0, 'Accessed Customer Products', '2017-06-13 14:21:55', 'Alex Minnie'),
(998, 6, 1, 0, 'Accessed Customer Summary', '2017-06-19 12:36:37', 'Alex Minnie'),
(999, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:36:39', 'Alex Minnie'),
(1000, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:38:29', 'Alex Minnie'),
(1001, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:38:34', 'Alex Minnie'),
(1002, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:38:59', 'Alex Minnie'),
(1003, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:40:36', 'Alex Minnie'),
(1004, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:43:34', 'Alex Minnie'),
(1005, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:45:03', 'Alex Minnie'),
(1006, 6, 1, 0, 'Added Customer Quote QU00000014', '2017-06-19 12:45:08', 'Alex Minnie'),
(1007, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:45:53', 'Alex Minnie'),
(1008, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:48:34', 'Alex Minnie'),
(1009, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:49:48', 'Alex Minnie'),
(1010, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:50:16', 'Alex Minnie'),
(1011, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 12:50:19', 'Alex Minnie'),
(1012, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 12:50:20', 'Alex Minnie'),
(1013, 6, 1, 0, 'Accessed Customer Summary', '2017-06-19 14:52:23', 'Alex Minnie'),
(1014, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 14:52:25', 'Alex Minnie'),
(1015, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 14:52:49', 'Alex Minnie'),
(1016, 6, 1, 0, 'Added Customer Invoice INV00000015', '2017-06-19 14:52:55', 'Alex Minnie'),
(1017, 6, 1, 0, 'Accessed Customer Summary', '2017-06-19 14:53:03', 'Alex Minnie'),
(1018, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 14:53:05', 'Alex Minnie'),
(1019, 6, 1, 0, 'Accessed Customer Summary', '2017-06-19 15:05:35', 'Alex Minnie'),
(1020, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-19 15:05:38', 'Alex Minnie'),
(1021, 6, 1, 0, 'Accessed Customer Summary', '2017-06-19 15:18:55', 'Alex Minnie'),
(1022, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 15:18:57', 'Alex Minnie'),
(1023, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 15:19:09', 'Alex Minnie'),
(1024, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-19 15:19:16', 'Alex Minnie'),
(1025, 6, 1, 0, 'Accessed Customer Summary', '2017-06-20 13:11:40', 'Alex Minnie'),
(1026, 6, 1, 0, 'Accessed Customer Summary', '2017-06-21 14:25:14', 'Alex Minnie'),
(1027, 6, 1, 0, 'Accessed Customer Summary', '2017-06-22 14:58:14', 'Alex Minnie'),
(1028, 6, 1, 0, 'Accessed Customer Profile', '2017-06-22 14:58:19', 'Alex Minnie'),
(1029, 6, 1, 0, 'Accessed Customer Summary', '2017-06-22 15:00:23', 'Alex Minnie'),
(1030, 6, 1, 0, 'Accessed Customer Profile', '2017-06-22 15:00:24', 'Alex Minnie'),
(1031, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-22 15:51:05', 'Alex Minnie'),
(1032, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 09:35:50', 'Alex Minnie'),
(1033, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 09:35:51', 'Alex Minnie'),
(1034, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 09:35:54', 'Alex Minnie'),
(1035, 6, 1, 0, 'Accessed Customer Summary', '2017-06-23 12:35:02', 'Alex Minnie'),
(1036, 6, 1, 0, 'Accessed Customer Summary', '2017-06-23 13:04:20', 'Alex Minnie'),
(1037, 6, 1, 0, 'Accessed Customer Summary', '2017-06-23 13:05:38', 'Alex Minnie'),
(1038, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 13:05:39', 'Alex Minnie'),
(1039, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-23 13:05:42', 'Alex Minnie'),
(1040, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-23 13:05:47', 'Alex Minnie'),
(1041, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-23 13:05:54', 'Alex Minnie'),
(1042, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 13:05:56', 'Alex Minnie'),
(1043, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-23 13:05:58', 'Alex Minnie'),
(1044, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-23 13:06:00', 'Alex Minnie'),
(1045, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-23 13:06:02', 'Alex Minnie'),
(1046, 6, 1, 0, 'Accessed Customer Summary', '2017-06-26 08:14:07', 'Alex Minnie'),
(1047, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 08:14:13', 'Alex Minnie'),
(1048, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-26 08:14:17', 'Alex Minnie'),
(1049, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:53:51', 'Alex Minnie'),
(1050, 6, 1, 0, 'Accessed Customer Summary', '2017-06-26 12:53:53', 'Alex Minnie'),
(1051, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-26 12:53:55', 'Alex Minnie'),
(1052, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:53:56', 'Alex Minnie'),
(1053, 6, 1, 0, 'Accessed Customer Summary', '2017-06-26 12:53:58', 'Alex Minnie'),
(1054, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-26 12:53:58', 'Alex Minnie'),
(1055, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:53:59', 'Alex Minnie'),
(1056, 6, 1, 0, 'Accessed Customer Summary', '2017-06-26 12:54:00', 'Alex Minnie'),
(1057, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:00', 'Alex Minnie'),
(1058, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-26 12:54:01', 'Alex Minnie'),
(1059, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:01', 'Alex Minnie'),
(1060, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:02', 'Alex Minnie'),
(1061, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:03', 'Alex Minnie'),
(1062, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:03', 'Alex Minnie'),
(1063, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:04', 'Alex Minnie'),
(1064, 6, 1, 0, 'Accessed Customer Profile', '2017-06-26 12:54:05', 'Alex Minnie'),
(1065, 6, 1, 0, 'Accessed Customer Summary', '2017-06-26 12:54:05', 'Alex Minnie'),
(1066, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-26 14:41:12', 'Alex Minnie'),
(1067, 6, 1, 0, 'Accessed Customer Summary', '2017-06-27 10:04:50', 'Alex Minnie'),
(1068, 6, 1, 0, 'Accessed Customer Profile', '2017-06-27 10:04:52', 'Alex Minnie'),
(1069, 6, 1, 0, 'Accessed Customer Contacts', '2017-06-27 10:04:54', 'Alex Minnie'),
(1070, 6, 1, 0, 'Accessed Customer Summary', '2017-06-27 10:04:55', 'Alex Minnie'),
(1071, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-27 11:11:08', 'Alex Minnie'),
(1072, 6, 1, 0, 'Added Customer Invoice INV00000016', '2017-06-27 11:33:41', 'Alex Minnie'),
(1073, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-27 11:33:49', 'Alex Minnie'),
(1074, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-27 12:01:36', 'Alex Minnie'),
(1075, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-27 12:03:55', 'Alex Minnie'),
(1076, 6, 1, 0, 'Accessed Customer Summary', '2017-06-28 16:28:24', 'Alex Minnie'),
(1077, 6, 1, 0, 'Accessed Customer Summary', '2017-06-29 10:33:23', 'Alex Minnie'),
(1078, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 10:33:25', 'Alex Minnie'),
(1079, 6, 1, 0, 'Accessed Customer Summary', '2017-06-29 12:26:33', 'Alex Minnie'),
(1080, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 12:26:36', 'Alex Minnie'),
(1081, 6, 1, 0, 'Accessed Customer Summary', '2017-06-29 12:28:54', 'Alex Minnie'),
(1082, 6, 1, 0, 'Accessed Customer Profile', '2017-06-29 12:28:56', 'Alex Minnie'),
(1083, 6, 1, 0, 'Updated Client Profile', '2017-06-29 12:29:14', 'Alex Minnie'),
(1084, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 12:29:18', 'Alex Minnie'),
(1085, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:29:39', 'Alex Minnie'),
(1086, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:30:51', 'Alex Minnie'),
(1087, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:31:38', 'Alex Minnie'),
(1088, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:31:45', 'Alex Minnie'),
(1089, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:32:43', 'Alex Minnie'),
(1090, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:34:04', 'Alex Minnie'),
(1091, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 12:34:22', 'Alex Minnie'),
(1092, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 12:34:34', 'Alex Minnie'),
(1093, 6, 1, 0, 'Accessed Customer Summary', '2017-06-29 12:37:36', 'Alex Minnie'),
(1094, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:37:38', 'Alex Minnie'),
(1095, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:37:46', 'Alex Minnie'),
(1096, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:37:52', 'Alex Minnie'),
(1097, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:38:41', 'Alex Minnie'),
(1098, 6, 1, 0, 'Accessed Customer Invoices', '2017-06-29 12:39:17', 'Alex Minnie'),
(1099, 6, 1, 0, 'Accessed Customer Summary', '2017-06-29 15:10:01', 'Alex Minnie'),
(1100, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 15:10:03', 'Alex Minnie'),
(1101, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-29 15:20:24', 'Alex Minnie'),
(1102, 6, 1, 0, 'Accessed Customer Statements', '2017-06-29 15:20:28', 'Alex Minnie'),
(1103, 6, 1, 0, 'Accessed Customer Statements', '2017-06-29 15:20:31', 'Alex Minnie'),
(1104, 6, 1, 0, 'Accessed Customer Statements', '2017-06-29 15:20:33', 'Alex Minnie'),
(1105, 6, 1, 0, 'Accessed Customer Statements', '2017-06-29 15:20:34', 'Alex Minnie'),
(1106, 6, 1, 0, 'Accessed Customer Statements', '2017-06-29 15:20:35', 'Alex Minnie'),
(1107, 6, 1, 0, 'Accessed Customer Documents', '2017-06-29 15:20:54', 'Alex Minnie'),
(1108, 6, 1, 0, 'Accessed Customer Jobcards', '2017-06-29 15:20:56', 'Alex Minnie'),
(1109, 6, 1, 0, 'Accessed Customer Notes', '2017-06-29 15:20:56', 'Alex Minnie'),
(1110, 6, 1, 0, 'Accessed Customer Products', '2017-06-29 15:20:57', 'Alex Minnie'),
(1111, 6, 1, 0, 'Accessed Customer Task', '2017-06-29 15:20:58', 'Alex Minnie'),
(1112, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-29 15:20:58', 'Alex Minnie'),
(1113, 6, 1, 0, 'Accessed Customer Sites', '2017-06-29 15:20:59', 'Alex Minnie'),
(1114, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 15:21:05', 'Alex Minnie'),
(1115, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-06-29 15:21:13', 'Alex Minnie'),
(1116, 6, 1, 0, 'Accessed Customer Quotes', '2017-06-29 15:21:24', 'Alex Minnie'),
(1117, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-30 11:57:38', 'Alex Minnie'),
(1118, 6, 1, 0, 'Accessed Customer Transactions', '2017-06-30 13:47:54', 'Alex Minnie'),
(1119, 6, 1, 0, 'Accessed Customer Documents', '2017-06-30 14:41:18', 'Alex Minnie'),
(1120, 6, 1, 0, 'Accessed Customer Statements', '2017-06-30 14:44:04', 'Alex Minnie'),
(1121, 6, 1, 0, 'Accessed Customer Statements', '2017-06-30 14:56:02', 'Alex Minnie'),
(1122, 6, 1, 0, 'Accessed Customer Statements', '2017-06-30 14:56:04', 'Alex Minnie'),
(1123, 6, 1, 0, 'Accessed Customer Summary', '2017-07-03 08:19:53', 'Alex Minnie'),
(1124, 6, 1, 0, 'Accessed Customer Documents', '2017-07-03 09:16:34', 'Alex Minnie'),
(1125, 6, 1, 0, 'Downloaded Document Test', '2017-07-03 09:21:31', 'Alex Minnie'),
(1126, 6, 1, 0, 'Downloaded Document Test', '2017-07-03 09:47:21', 'Alex Minnie'),
(1127, 6, 1, 0, 'Accessed Customer Documents', '2017-07-03 11:15:06', 'Alex Minnie'),
(1128, 6, 1, 0, 'Accessed Customer Documents', '2017-07-03 11:22:10', 'Alex Minnie'),
(1129, 6, 1, 0, 'Downloaded Document Test', '2017-07-03 11:35:06', 'Alex Minnie'),
(1130, 5, 1, 0, 'Accessed Customer Documents', '2017-07-03 11:35:21', 'Alex Minnie'),
(1131, 5, 1, 0, 'Downloaded Document Test Doc', '2017-07-03 11:35:22', 'Alex Minnie'),
(1132, 5, 1, 0, 'Downloaded Document Test Doc', '2017-07-03 11:35:28', 'Alex Minnie'),
(1133, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 11:50:58', 'Alex Minnie'),
(1134, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 11:51:19', 'Alex Minnie'),
(1135, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 11:51:23', 'Alex Minnie'),
(1136, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 11:51:28', 'Alex Minnie'),
(1137, 5, 1, 0, 'Accessed Customer Documents', '2017-07-03 11:52:56', 'Alex Minnie'),
(1138, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 13:33:25', 'Alex Minnie'),
(1139, 5, 1, 0, 'Accessed Customer Summary', '2017-07-03 13:33:37', 'Alex Minnie'),
(1140, 5, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 13:33:43', 'Alex Minnie'),
(1141, 6, 1, 0, 'Accessed Customer Summary', '2017-07-03 13:33:57', 'Alex Minnie'),
(1142, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 13:33:59', 'Alex Minnie'),
(1143, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-03 14:18:43', 'Alex Minnie'),
(1144, 6, 1, 0, 'Accessed Customer Notes', '2017-07-03 14:18:44', 'Alex Minnie'),
(1145, 6, 1, 0, 'Accessed Customer Products', '2017-07-03 14:50:02', 'Alex Minnie'),
(1146, 6, 1, 0, 'Added ccustomer product', '2017-07-03 15:23:35', 'Alex Minnie'),
(1147, 6, 1, 0, 'Accessed Customer Products', '2017-07-03 15:23:36', 'Alex Minnie'),
(1148, 6, 1, 0, 'Accessed Customer Products', '2017-07-03 15:23:49', 'Alex Minnie'),
(1149, 6, 1, 0, 'Accessed Customer Products', '2017-07-03 15:23:50', 'Alex Minnie'),
(1150, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 05:00:04', 'Alex Minnie'),
(1151, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-04 05:00:09', 'Alex Minnie'),
(1152, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 06:20:22', 'Alex Minnie'),
(1153, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 06:21:44', 'Alex Minnie'),
(1154, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-04 06:23:38', 'Alex Minnie'),
(1155, 6, 1, 0, 'Accessed Customer Transactions', '2017-07-04 06:25:11', 'Alex Minnie'),
(1156, 6, 1, 0, 'Accessed Customer Documents', '2017-07-04 06:26:03', 'Alex Minnie'),
(1157, 6, 1, 0, 'Accessed Customer Notes', '2017-07-04 06:27:22', 'Alex Minnie'),
(1158, 6, 1, 0, 'Accessed Customer Products', '2017-07-04 06:28:18', 'Alex Minnie'),
(1159, 6, 1, 0, 'Accessed Customer Task', '2017-07-04 06:28:38', 'Alex Minnie'),
(1160, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 06:29:05', 'Alex Minnie'),
(1161, 6, 1, 0, 'Accessed Customer Profile', '2017-07-04 06:29:59', 'Alex Minnie'),
(1162, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 06:39:08', 'Alex Minnie'),
(1163, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 06:39:10', 'Alex Minnie'),
(1164, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 06:45:03', 'Alex Minnie'),
(1165, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-04 06:45:05', 'Alex Minnie'),
(1166, 6, 1, 0, 'Added Customer Quote QU00000015', '2017-07-04 06:45:10', 'Alex Minnie'),
(1167, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-04 06:48:50', 'Alex Minnie'),
(1168, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-04 06:55:03', 'Alex Minnie'),
(1169, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 10:01:12', 'Alex Minnie'),
(1170, 6, 1, 0, 'Accessed Customer Task', '2017-07-04 10:01:14', 'Alex Minnie'),
(1171, 6, 1, 0, 'Accessed Customer Task', '2017-07-04 10:09:40', 'Alex Minnie'),
(1172, 6, 1, 0, 'Accessed Customer Task', '2017-07-04 10:36:59', 'Alex Minnie'),
(1173, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 10:37:05', 'Alex Minnie'),
(1174, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 10:45:50', 'Alex Minnie'),
(1175, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 11:04:52', 'Alex Minnie'),
(1176, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 11:05:06', 'Alex Minnie'),
(1177, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 11:05:07', 'Alex Minnie'),
(1178, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 11:51:12', 'Alex Minnie'),
(1179, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 12:44:52', 'Alex Minnie'),
(1180, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 12:45:02', 'Alex Minnie'),
(1181, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-04 12:45:30', 'Alex Minnie'),
(1182, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 12:45:32', 'Alex Minnie'),
(1183, 6, 1, 0, 'Updated Client Site - Test', '2017-07-04 12:45:36', 'Alex Minnie'),
(1184, 6, 1, 0, 'Accessed Customer Sites', '2017-07-04 12:45:36', 'Alex Minnie'),
(1185, 6, 1, 0, 'Accessed Customer Summary', '2017-07-04 13:09:01', 'Alex Minnie'),
(1186, 5, 1, 0, 'Accessed Customer Summary', '2017-07-04 13:20:41', 'Alex Minnie'),
(1187, 5, 1, 0, 'Accessed Customer Summary', '2017-07-05 10:52:01', 'Alex Minnie'),
(1188, 6, 1, 0, 'Accessed Customer Summary', '2017-07-05 10:52:20', 'Alex Minnie'),
(1189, 6, 1, 0, 'Accessed Customer Profile', '2017-07-05 10:52:23', 'Alex Minnie'),
(1190, 6, 1, 0, 'Accessed Customer Contacts', '2017-07-05 10:52:26', 'Alex Minnie'),
(1191, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-05 10:52:44', 'Alex Minnie'),
(1192, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 10:52:54', 'Alex Minnie'),
(1193, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-05 10:52:58', 'Alex Minnie'),
(1194, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-05 10:53:39', 'Alex Minnie'),
(1195, 6, 1, 0, 'Accessed Customer Summary', '2017-07-05 10:56:01', 'Alex Minnie'),
(1196, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-05 10:56:04', 'Alex Minnie'),
(1197, 6, 1, 0, 'Added Jobcard JBC11', '2017-07-05 10:56:45', 'Alex Minnie'),
(1198, 6, 1, 0, 'Added Customer Invoice INV00000018', '2017-07-05 10:58:47', 'Alex Minnie'),
(1199, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-05 10:59:24', 'Alex Minnie'),
(1200, 6, 1, 0, 'Accessed Customer Summary', '2017-07-05 11:09:45', 'Alex Minnie'),
(1201, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:09:54', 'Alex Minnie'),
(1202, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:10:21', 'Alex Minnie'),
(1203, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:10:32', 'Alex Minnie'),
(1204, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:11:05', 'Alex Minnie'),
(1205, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:11:39', 'Alex Minnie'),
(1206, 6, 1, 0, 'Added Customer Invoice INV00000019', '2017-07-05 11:12:29', 'Alex Minnie'),
(1207, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-05 11:12:40', 'Alex Minnie'),
(1208, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-05 11:13:13', 'Alex Minnie'),
(1209, 6, 1, 0, 'Accessed Customer Summary', '2017-07-05 11:20:23', 'Alex Minnie'),
(1210, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-05 11:20:26', 'Alex Minnie'),
(1211, 6, 1, 0, 'Accessed Customer Summary', '2017-07-06 10:55:26', 'Alex Minnie'),
(1212, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-06 10:55:30', 'Alex Minnie'),
(1213, 6, 1, 0, 'Accessed Customer Summary', '2017-07-06 11:17:32', 'Alex Minnie'),
(1214, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-13 01:03:37', 'Alex Minnie'),
(1215, 6, 1, 0, 'Accessed Customer Summary', '2017-07-13 11:52:25', 'Alex Minnie'),
(1216, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-13 11:52:27', 'Alex Minnie'),
(1217, 6, 1, 0, 'Accessed Customer Summary', '2017-07-13 14:16:20', 'Alex Minnie'),
(1218, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-13 14:16:22', 'Alex Minnie'),
(1219, 5, 1, 0, 'Accessed Customer Invoices', '2017-07-13 15:39:06', 'Alex Minnie'),
(1220, 5, 1, 0, 'Accessed Customer Invoices', '2017-07-13 15:39:13', 'Alex Minnie'),
(1221, 6, 1, 0, 'Accessed Customer Summary', '2017-07-14 11:40:59', 'Alex Minnie'),
(1222, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-14 11:41:01', 'Alex Minnie'),
(1223, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-14 11:41:09', 'Alex Minnie'),
(1224, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-14 11:41:44', 'Alex Minnie');
INSERT INTO `customeraccess` (`CustomerAccessLogID`, `CustomerID`, `ClientID`, `EmployeeID`, `LogType`, `LogDate`, `AccessName`) VALUES
(1225, 6, 1, 0, 'Accessed Customer Summary', '2017-07-14 11:56:22', 'Alex Minnie'),
(1226, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-14 11:56:24', 'Alex Minnie'),
(1227, 6, 1, 0, 'Accessed Customer Summary', '2017-07-21 07:56:18', 'Alex Minnie'),
(1228, 6, 1, 0, 'Accessed Customer Summary', '2017-07-21 07:56:26', 'Alex Minnie'),
(1229, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-21 07:56:29', 'Alex Minnie'),
(1230, 6, 1, 0, 'Accessed Customer Summary', '2017-07-21 07:59:58', 'Alex Minnie'),
(1231, 6, 1, 0, 'Accessed Customer Profile', '2017-07-21 08:00:00', 'Alex Minnie'),
(1232, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-21 08:00:04', 'Alex Minnie'),
(1233, 6, 1, 0, 'Accessed Customer Summary', '2017-07-21 08:02:55', 'Alex Minnie'),
(1234, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-21 08:02:59', 'Alex Minnie'),
(1235, 6, 1, 0, 'Accessed Customer Summary', '2017-07-25 12:32:28', 'Alex Minnie'),
(1236, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-25 12:32:31', 'Alex Minnie'),
(1237, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-25 12:33:17', 'Alex Minnie'),
(1238, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-25 12:35:54', 'Alex Minnie'),
(1239, 6, 1, 0, 'Accessed Customer Summary', '2017-07-26 11:38:33', 'Alex Minnie'),
(1240, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-26 11:38:35', 'Alex Minnie'),
(1241, 6, 1, 0, 'Accessed Customer Summary', '2017-07-26 11:42:49', 'Alex Minnie'),
(1242, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-26 11:42:51', 'Alex Minnie'),
(1243, 6, 1, 0, 'Accessed Customer Summary', '2017-07-28 14:34:34', 'Alex Minnie'),
(1244, 6, 1, 0, 'Accessed Customer Profile', '2017-07-28 14:34:37', 'Alex Minnie'),
(1245, 6, 1, 0, 'Accessed Customer Contacts', '2017-07-28 14:34:40', 'Alex Minnie'),
(1246, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-28 14:34:52', 'Alex Minnie'),
(1247, 6, 1, 0, 'Accessed Customer Quotes', '2017-07-28 14:34:57', 'Alex Minnie'),
(1248, 6, 1, 0, 'Accessed Customer Transactions', '2017-07-28 14:35:03', 'Alex Minnie'),
(1249, 6, 1, 0, 'Accessed Customer Statements', '2017-07-28 14:35:08', 'Alex Minnie'),
(1250, 6, 1, 0, 'Accessed Customer Documents', '2017-07-28 14:35:11', 'Alex Minnie'),
(1251, 6, 1, 0, 'Accessed Customer Jobcards', '2017-07-28 14:35:14', 'Alex Minnie'),
(1252, 6, 1, 0, 'Added Jobcard JBC12', '2017-07-28 14:35:38', 'Alex Minnie'),
(1253, 6, 1, 0, 'Accessed Customer Summary', '2017-07-28 14:36:21', 'Alex Minnie'),
(1254, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-28 14:36:27', 'Alex Minnie'),
(1255, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-28 14:36:44', 'Alex Minnie'),
(1256, 6, 1, 0, 'Accessed Customer Statements', '2017-07-28 14:36:47', 'Alex Minnie'),
(1257, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-28 14:36:52', 'Alex Minnie'),
(1258, 6, 1, 0, 'Accessed Customer Transactions', '2017-07-28 14:37:31', 'Alex Minnie'),
(1259, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-28 14:37:32', 'Alex Minnie'),
(1260, 6, 1, 0, 'Accessed Customer Notes', '2017-07-28 14:37:40', 'Alex Minnie'),
(1261, 6, 1, 0, 'Accessed Customer Products', '2017-07-28 14:37:46', 'Alex Minnie'),
(1262, 6, 1, 0, 'Accessed Customer Task', '2017-07-28 14:38:12', 'Alex Minnie'),
(1263, 6, 1, 0, 'Accessed Customer Follow Ups', '2017-07-28 14:38:15', 'Alex Minnie'),
(1264, 6, 1, 0, 'Accessed Customer Sites', '2017-07-28 14:38:19', 'Alex Minnie'),
(1265, 6, 1, 0, 'Accessed Customer Summary', '2017-07-30 02:01:06', 'Alex Minnie'),
(1266, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-30 02:01:12', 'Alex Minnie'),
(1267, 6, 1, 0, 'Accessed Customer Summary', '2017-07-31 10:46:44', 'Alex Minnie'),
(1268, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-31 10:46:46', 'Alex Minnie'),
(1269, 6, 1, 0, 'Accessed Customer Summary', '2017-07-31 10:55:58', 'Alex Minnie'),
(1270, 6, 1, 0, 'Accessed Customer Invoices', '2017-07-31 10:56:00', 'Alex Minnie'),
(1271, 6, 1, 0, 'Accessed Customer Summary', '2017-08-01 09:37:56', 'Alex Minnie'),
(1272, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-01 09:37:58', 'Alex Minnie'),
(1273, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-01 10:40:05', 'Alex Minnie'),
(1274, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-01 15:40:34', 'Alex Minnie'),
(1275, 6, 1, 0, 'Accessed Customer Summary', '2017-08-03 23:22:53', 'Alex Minnie'),
(1276, 5, 1, 0, 'Accessed Customer Summary', '2017-08-03 23:26:53', 'Alex Minnie'),
(1277, 5, 1, 0, 'Accessed Customer Profile', '2017-08-03 23:26:57', 'Alex Minnie'),
(1278, 5, 1, 0, 'Accessed Customer Task', '2017-08-03 23:27:35', 'Alex Minnie'),
(1279, 6, 1, 0, 'Accessed Customer Summary', '2017-08-07 10:56:32', 'Alex Minnie'),
(1280, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-07 10:56:35', 'Alex Minnie'),
(1281, 6, 1, 0, 'Accessed Customer Jobcards', '2017-08-07 10:56:56', 'Alex Minnie'),
(1282, 6, 1, 0, 'Accessed Customer Summary', '2017-08-10 12:10:39', 'Alex Minnie'),
(1283, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:10:42', 'Alex Minnie'),
(1284, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:12:59', 'Alex Minnie'),
(1285, 6, 1, 0, 'Accessed Customer Summary', '2017-08-10 12:15:05', 'Alex Minnie'),
(1286, 6, 1, 0, 'Accessed Customer Profile', '2017-08-10 12:15:07', 'Alex Minnie'),
(1287, 6, 1, 0, 'Accessed Customer Summary', '2017-08-10 12:17:14', 'Alex Minnie'),
(1288, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:17:15', 'Alex Minnie'),
(1289, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:18:40', 'Alex Minnie'),
(1290, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:18:49', 'Alex Minnie'),
(1291, 6, 1, 0, 'Updates Customer Quote QU00000015 status to Pending', '2017-08-10 12:19:03', 'Alex Minnie'),
(1292, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:19:05', 'Alex Minnie'),
(1293, 6, 1, 0, 'Accessed Customer Transactions', '2017-08-10 12:19:58', 'Alex Minnie'),
(1294, 6, 1, 0, 'Accessed Customer Statements', '2017-08-10 12:20:00', 'Alex Minnie'),
(1295, 6, 1, 0, 'Accessed Customer Documents', '2017-08-10 12:20:29', 'Alex Minnie'),
(1296, 6, 1, 0, 'Accessed Customer Notes', '2017-08-10 12:22:52', 'Alex Minnie'),
(1297, 6, 1, 0, 'Accessed Customer Products', '2017-08-10 12:23:24', 'Alex Minnie'),
(1298, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:37:24', 'Alex Minnie'),
(1299, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:37:48', 'Alex Minnie'),
(1300, 6, 1, 0, 'Added Customer Invoice INV20', '2017-08-10 12:38:18', 'Alex Minnie'),
(1301, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:38:36', 'Alex Minnie'),
(1302, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-10 12:39:07', 'Alex Minnie'),
(1303, 6, 1, 0, 'Added Customer Quote QU00000016', '2017-08-10 12:39:12', 'Alex Minnie'),
(1304, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:40:00', 'Alex Minnie'),
(1305, 6, 1, 0, 'Added Customer Invoice INV21', '2017-08-10 12:40:07', 'Alex Minnie'),
(1306, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:40:10', 'Alex Minnie'),
(1307, 6, 1, 0, 'Added Customer Invoice INV22', '2017-08-10 12:40:33', 'Alex Minnie'),
(1308, 6, 1, 0, 'Accessed Customer Invoices', '2017-08-10 12:42:04', 'Alex Minnie'),
(1309, 6, 1, 0, 'Accessed Customer Summary', '2017-08-24 13:55:08', 'Alex Minnie'),
(1310, 6, 1, 0, 'Accessed Customer Quotes', '2017-08-24 13:55:11', 'Alex Minnie'),
(1311, 5, 1, 0, 'Accessed Customer Summary', '2017-08-25 12:21:16', 'Alex Minnie'),
(1312, 5, 1, 0, 'Accessed Customer Jobcards', '2017-08-25 12:21:18', 'Alex Minnie');

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

--
-- Dumping data for table `customercontacts`
--

INSERT INTO `customercontacts` (`ContactID`, `CustomerID`, `Name`, `Surname`, `CompanyName`, `Department`, `EmailAddress`, `ContactNumber`, `EmailInvoice`, `EmailSupport`, `EmailQuotes`, `AddContacts`, `AcceptQuotes`, `ChangeDetails`) VALUES
(1, 5, 'Alex 2', 'Minnie', 'Easy2Access', 'IT', 'alex2@e2a.co.za', '0820737373', 1, 1, 1, 1, 1, 1),
(2, 5, 'Test', 'Test', 'Test', 'Test', 'Test', 'Test', 1, 1, 1, 0, 0, 0),
(3, 6, 'Support', 'Department', '', 'Support', 'support@e2a.co.za', '12353352', 1, 1, 1, 0, 1, 1);

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

--
-- Dumping data for table `customercustomentries`
--

INSERT INTO `customercustomentries` (`CustomerCustomValueID`, `CustomerID`, `CustomFieldID`, `CustomFieldOptionID`, `CustomOptionValue`) VALUES
(1, 5, 13, 0, 'dddddvsd '),
(2, 5, 12, 0, 'ksdjnf'),
(3, 5, 14, 0, 'JON001'),
(4, 5, 15, 0, ''),
(5, 6, 15, 0, '12345MA'),
(6, 6, 14, 0, 'support@e2a.co.za'),
(7, 6, 13, 0, '45524422'),
(8, 6, 12, 0, 'Accounts');

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
  `DisplayQuote` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customercustomfields`
--

INSERT INTO `customercustomfields` (`CustomFieldID`, `CustomFieldName`, `CustomFieldType`, `Required`, `DisplayOrder`, `DisplayInvoice`, `DisplayQuote`) VALUES
(12, 'Account Contact', 'text', 1, 4, 0, 0),
(13, 'Company Registration', 'text', 1, 3, 0, 0),
(14, 'Support Email', 'text', 0, 2, 1, 0),
(15, 'Med aid number', 'text', 0, 1, 0, 1);

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
-- Table structure for table `customercustomfieldvalues`
--

CREATE TABLE `customercustomfieldvalues` (
  `ClientCustomFieldID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `ClientCustomFieldOptionID` int(11) DEFAULT '0',
  `ClientCustomFieldValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customerdocumentgroups`
--

CREATE TABLE `customerdocumentgroups` (
  `DocumentGroupID` int(11) NOT NULL,
  `GroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerdocumentgroups`
--

INSERT INTO `customerdocumentgroups` (`DocumentGroupID`, `GroupName`) VALUES
(1, 'Subscription Forms'),
(2, 'Proof of Payment');

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

--
-- Dumping data for table `customerdocuments`
--

INSERT INTO `customerdocuments` (`DocumentID`, `CustomerID`, `DocumentName`, `DocumentFile`, `DocumentType`, `DateAdded`, `AddedBy`, `AddedByName`, `AddedByEmployeeID`, `DocumentGroupID`) VALUES
(1, 5, 'Test PDF', '1472799173_1470903653_blank.pdf', 'PDF', '2016-09-02', 1, 'Alex Minnie', NULL, 1),
(2, 5, 'Test Doc', '1473858286_pic2.jpg', 'IMAGE', '2016-09-14', 1, 'Alex Minnie', 0, 1),
(3, 5, 'Test', '1473858372_pic4.jpg', 'IMAGE', '2016-09-14', 1, 'Alex Minnie', 0, 1),
(4, 5, 'Test Doc', '1474294350_plan.jpg', 'IMAGE', '2016-09-19', 1, 'Alex Minnie', 0, 1),
(5, 6, 'Test', '1496910967_test.jpg', 'IMAGE', '2017-06-08', 1, 'Alex Minnie', 0, 2);

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

--
-- Dumping data for table `customerfollowups`
--

INSERT INTO `customerfollowups` (`FollowUpID`, `CustomerID`, `Description`, `FollowUpDate`, `Outcome`, `ClientID`, `EmployeeID`, `AddedByName`, `DateAdded`, `Status`) VALUES
(1, 5, 'Follow up with client regarding something', '2016-12-30', 'Client is going ahead', 1, 0, 'Alex Minnie', '2016-12-19', 1),
(2, 5, 'Check if client is happy', '2016-12-26', NULL, 1, 0, 'Alex Minnie', '2016-12-19', 0),
(3, 6, 'send email', '2017-05-22', 'done', 1, 0, 'Alex Minnie', '2017-05-13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customerinvoicegroups`
--

CREATE TABLE `customerinvoicegroups` (
  `InvoiceGroupID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `GroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerinvoicegroups`
--

INSERT INTO `customerinvoicegroups` (`InvoiceGroupID`, `InvoiceID`, `GroupName`) VALUES
(1, 9, 'invoice line test'),
(2, 10, 'Software'),
(3, 14, 'Delivery'),
(4, 6, 'This was 1st repair'),
(5, 5, 'New Installation');

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
(1, 26, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(2, 1, 'Fridge 1 - Fridge double door', 2, 7800, 15, '12354654', 15600, 0, 2184, 17784, 'Once-Off', 2, '1', 7800, 15600, 0, 1, 0),
(3, 2, 'Fridge 1 - Fridge double door', 4, 7800, 15, '12354654', 31200, 0, 4368, 35568, 'Once-Off', 4, '1', 7800, 31200, 0, 1, 0),
(4, 3, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 0),
(5, 6, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 4),
(6, 6, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 4),
(7, 6, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 0),
(8, 6, 'Repair on 09062017', 1, 0, 0, 'CUSTOM', 0, 0, 0, 0, 'Once-Off', 0, '', NULL, 0, NULL, 0, 0),
(9, 8, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(10, 8, 'Notes: sb vahrvaeh h vae rh veu vuaauovuo ouyuaou o uveru ou ce oeugref ear  aoe foae vovre ovre re er yvreyer er', 1, 0, 0, 'CUSTOM', 0, 0, 0, 0, 'Once-Off', 0, '', NULL, 0, NULL, 0, 0),
(11, 9, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(12, 10, 'Web Hosting - Micro Hosting', 1, 49, 16, 'HOST1235', 49, 0, 6.86, 55.86, 'Monthly', 1, '1 Each', 49, 49, 0, 1, 0),
(13, 11, 'Web Hosting - Micro Hosting', 1, 49, 16, 'HOST1235', 49, 0, 6.86, 55.86, 'Monthly', 1, '1 Each', 49, 49, 0, 1, 0),
(14, 13, 'Coke - Coca-Cola', 20, 284.67, 13, 'X235', 5693.4, 0, 797.08, 6490.48, 'Once-Off', 960, '4 dozen', 5.93, 2131.2, 3.71, 1, 0),
(15, 13, 'Fanta Grape - Test', 22, 500, 12, 'X4536', 11000, 0, 1540, 12540, 'Once-Off', 1056, '4 dozen', 10.42, 2640, 7.92, 1, 0),
(16, 13, 'Coke - Coca-Cola', 22, 123.95, 13, 'X235', 2726.9, 0, 381.77, 3108.67, 'Once-Off', 528, '2 dozen', 5.16, 765.6, 3.71, 1, 0),
(17, 7, 'Expansion Valve - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(18, 7, 'Expansion Valve - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(19, 7, 'Module 1 - Module 1', 1, 100, 17, 'abx1', 100, 0, 14, 114, 'Once-Off', 1, '1', 100, 100, 0, 1, 0),
(20, 7, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 0),
(21, 17, 'Fanta Grape - Test', 1, 1800, 12, 'X4536', 1800, 0, 252, 2052, 'Monthly', 24, '2 dozen', 75, 1609.92, 7.92, 1, 0),
(22, 18, 'Expansion Valve', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(23, 19, 'Expansion Valve', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(24, 5, 'Expansion Valve', 2, 123.95, 13, 'X235', 247.9, 0, 34.71, 282.61, 'Once-Off', 48, '2 dozen', 5.16, 69.6, 3.71, 1, 5),
(25, 5, 'Fridge 1', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 0),
(26, 16, 'Module 1', 0.5, 100, 17, 'abx1', 50, 0, 7, 57, 'Once-Off', 1, '1', 100, 50, 0, 1, 0),
(27, 20, 'Expansion Valve', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(28, 20, 'Fridge 1', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 7800, 7800, 0, 1, 0),
(29, 22, 'Expansion Valve', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 5.16, 34.8, 3.71, 1, 0),
(30, 22, 'Module 1', 2, 100, 17, 'abx1', 200, 0, 28, 228, 'Once-Off', 2, '1', 100, 200, 0, 1, 0),
(31, 22, 'Asset tag Test', 10, 1, 18, 'Ass001', 10, 0, 1.4, 11.4, 'Once-Off', 10, '1', 1, 10, 0, 1, 0);

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
(1, 5, 'INV0000001', '2017-06-08', '2017-06-15', 0, 1, 1, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', 0, NULL),
(2, 5, 'INV0000002', '2017-06-08', '2017-06-15', 0, 1, 1, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', 0, NULL),
(3, 5, 'INV0000003', '2017-06-08', '2017-06-15', 0, 1, 1, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', 0, NULL),
(4, 6, 'INV0000004', '2017-06-09', '2017-06-16', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(5, 6, 'INV0000005', '2017-06-09', '2017-06-16', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(6, 6, 'INV0000006', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(7, 6, 'INV0000007', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(8, 6, 'INV0000008', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(9, 6, 'INV0000009', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, 'j; bb ldfjbavahbv;bvoff\nb;db;bvdfbfvfdb\n f `JDV ojngk\n\njf`jrgofvo`dodr', 0, NULL),
(10, 6, 'INV00000010', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(11, 6, 'INV00000011', '2017-06-09', '2017-06-16', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(12, 6, 'INV00000012', '2017-06-09', '2017-06-16', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(13, 6, 'INV00000013', '2017-06-13', '2017-06-20', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(14, 6, 'INV00000014', '2017-06-13', '2017-06-20', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(15, 6, 'INV00000015', '2017-06-19', '2017-06-26', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(16, 6, 'INV00000016', '2017-06-27', '2017-07-04', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(17, 6, 'INV00000017', '2017-07-03', '2017-07-10', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(18, 6, 'INV00000018', '2017-07-05', '2017-07-12', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(19, 6, 'INV00000019', '2017-07-05', '2017-07-09', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(20, 6, 'INV20', '2017-08-10', '2017-08-20', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(21, 6, 'INV21', '2017-08-10', '2017-08-17', 0, 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL),
(22, 6, 'INV22', '2017-08-10', '2017-08-17', 0, 1, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', 0, NULL);

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

--
-- Dumping data for table `customerlogs`
--

INSERT INTO `customerlogs` (`CustomerLogID`, `CustomerID`, `LogText`, `LogAdded`, `AddedByClientID`, `AddedByEmployeeID`, `AddedByName`, `LogType`) VALUES
(1, 1, 'Test adding a log', '2016-09-19 07:50:58', 1, 0, 'Alex Minnie', 'General'),
(2, 1, 'Test adding a second log', '2016-09-19 07:51:25', 1, 0, 'Alex Minnie', 'General'),
(3, 1, 'Test adding a different log type', '2016-09-19 07:51:49', 1, 0, 'Alex Minnie', 'Called Client'),
(4, 1, 'Test adding a way longer description, client is not very happy at the moment as they have so many queries in one day and we have to sort out this client', '2016-09-19 07:59:36', 1, 0, 'Alex Minnie', 'General');

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

--
-- Dumping data for table `customernotes`
--

INSERT INTO `customernotes` (`NoteID`, `CustomerID`, `Note`, `DateAdded`, `AddedByEmployee`, `AddedBy`, `AddedByName`, `StickyNote`) VALUES
(1, 5, 'Test adding a note', '2016-09-02', NULL, 1, 'Alex Minnie', 0),
(2, 5, 'Adding a second note', '2016-09-02', NULL, 1, 'Alex Minnie', 1),
(3, 5, 'Testing adding a note to a client', '2016-09-14', 0, 1, 'Alex Minnie', 0),
(4, 5, 'Test Adding a note', '2016-11-25', 0, 1, 'Alex Minnie', 0),
(5, 5, 'Testing another note', '2016-11-25', 0, 1, 'Alex Minnie', 1),
(6, 5, 'Test Note', '2016-12-16', 0, 1, 'Alex Minnie', 0),
(7, 5, 'Invoice spelt wrong, qoutoe still pending?', '2017-02-05', 0, 1, 'Alex Minnie', 1),
(8, 5, 'todo list and calendar ??', '2017-02-05', 0, 1, 'Alex Minnie', 1),
(9, 6, 'PLease call client', '2017-05-13', 0, 1, 'Alex Minnie', 1),
(10, 6, 'test t', '2017-06-09', 0, 1, 'Alex Minnie', 0);

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

--
-- Dumping data for table `customerproducts`
--

INSERT INTO `customerproducts` (`ClientProductID`, `CustomerID`, `ProductID`, `ProductCostID`, `FirstBillingDate`, `NextBillingDate`, `ProductDateAdded`, `ClientID`, `EmployeeID`, `AddedByName`, `ClientProductStatus`, `ProductName`, `ProductQuantity`, `DateAdded`, `RecurringTimes`, `RecurredTimes`, `WarehouseID`, `RecurringAmount`) VALUES
(1, 5, 12, 14, '2017-05-02', '2017-06-02', '2017-05-02', 1, 0, 'Alex Minnie', 2, 'Fanta Grape - Test', 1, NULL, 0, 1, 1, 1800),
(2, 5, 12, 14, '2017-05-02', '2017-06-02', '2017-05-02', 1, 0, 'Alex Minnie', 2, 'Fanta Grape - Test', 1, NULL, 0, 1, 1, 1800),
(3, 5, 12, 15, '2017-05-02', '2017-06-02', '2017-05-02', 1, 0, 'Alex Minnie', 2, 'Fanta Grape - Test', 2, NULL, 0, 1, 1, 4400),
(4, 6, 16, 20, '2017-06-09', '2017-07-09', '2017-06-09', 1, 0, 'Alex Minnie', 2, '', 1, NULL, 0, 1, 1, 49),
(5, 6, 16, 20, '2017-06-09', '2017-07-09', '2017-06-09', 1, 0, 'Alex Minnie', 2, '', 1, NULL, 12, 1, 1, 49),
(6, 6, 12, 14, '2017-07-03', '2017-08-03', '2017-07-03', 1, 0, 'Alex Minnie', 2, '', 1, NULL, 1, 1, 1, 1800);

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

--
-- Dumping data for table `customerquotelines`
--

INSERT INTO `customerquotelines` (`QuoteLineItemID`, `QuoteID`, `Description`, `Quantity`, `Price`, `ProductID`, `ProductCode`, `LineSubTotal`, `LineDiscount`, `LineVAT`, `LineTotal`, `BillingType`, `StockAffect`, `MeassurementDescription`, `ProductCostID`) VALUES
(1, 3, 'Fanta Grape - Test', 2, 1800, 12, 'X4536', 3600, 0, 0, 3600, 'Monthly', 48, '2 dozen', NULL),
(2, 1, 'Fanta Grape - Test', 2, 2200, 12, 'X4536', 4400, 0, 0, 4400, 'Monthly', 72, '3 dozen', NULL),
(3, 4, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', NULL),
(4, 5, 'Fanta Grape - Test', 1, 1800, 12, 'X4536', 1800, 0, 252, 2052, 'Monthly', 24, '2 dozen', 14),
(5, 7, 'Coke - Coca-Cola', 25, 123.95, 13, 'X235', 3098.75, 0, 433.83, 3532.58, 'Once-Off', 600, '2 dozen', 17),
(6, 6, 'Fanta Grape - Test', 5, 500, 12, 'X4536', 2500, 0, 350, 2850, 'Once-Off', 240, '4 dozen', 16),
(7, 7, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 19),
(8, 7, 'Fanta Grape - Test', 1, 1800, 12, 'X4536', 1800, 0, 252, 2052, 'Monthly', 24, '2 dozen', 14),
(9, 7, 'Fanta Grape - Test', 2, 2200, 12, 'X4536', 4400, 0, 616, 5016, 'Monthly', 72, '3 dozen', 15),
(10, 8, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 19),
(11, 9, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 19),
(12, 11, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 17),
(14, 12, 'Courier Fee', 1, 500, 0, 'CUSTOM', 500, 0, 70, 570, 'Once-Off', 0, '', NULL),
(15, 13, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 17),
(16, 12, 'Coke - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 17),
(17, 14, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 19),
(18, 15, 'Expansion Valve - Coca-Cola', 1, 123.95, 13, 'X235', 123.95, 0, 17.35, 141.3, 'Once-Off', 24, '2 dozen', 17),
(19, 15, 'Fridge 1 - Fridge double door', 1, 7800, 15, '12354654', 7800, 0, 1092, 8892, 'Once-Off', 1, '1', 19),
(20, 16, 'Expansion Valve - Coca-Cola', 0.5, 123.95, 13, 'X235', 61.975, 0, 8.68, 70.655, 'Once-Off', 12, '2 dozen', 17);

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

--
-- Dumping data for table `customerquotes`
--

INSERT INTO `customerquotes` (`QuoteID`, `CustomerID`, `QuoteNumber`, `QuoteDate`, `ExpiryDate`, `DiscountPercent`, `QuoteStatus`, `AddedByClient`, `AddedByEmployee`, `AddedByName`, `Address1`, `Address2`, `City`, `State`, `PostCode`, `CountryID`, `ProposalText`, `FooterText`) VALUES
(1, 5, NULL, '2016-12-23', '2017-01-23', 0, 1, 1, 0, 'Alex Minnie', '7 Somewhere', 'Seomplace', 'Durban', 'KZN', '4093', 192, '', ''),
(2, 5, NULL, '2016-12-23', '2017-01-23', 0, 0, 1, 0, 'Alex Minnie', '7 Somewhere', 'Seomplace', 'Durban', 'KZN', '4093', 192, '', ''),
(3, 5, NULL, '2016-12-23', '2017-01-23', 0, 3, 1, 0, 'Alex Minnie', '7 Somewhere', 'Seomplace', 'Durban', 'KZN', '4093', 192, '', ''),
(4, 5, NULL, '2017-03-27', '2017-04-27', 0, 1, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', ''),
(5, 5, NULL, '2017-05-02', '2017-06-02', 0, 2, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', ''),
(6, 5, NULL, '2017-05-02', '2017-06-02', 0, 0, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', ''),
(7, 5, NULL, '2017-05-02', '2017-06-02', 0, 2, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', ''),
(8, 6, NULL, '2017-05-13', '2017-06-13', 0, 2, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(9, 6, NULL, '2017-05-13', '2017-06-13', 0, 2, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(10, 5, NULL, '2017-06-01', '2017-07-02', 0, 0, 1, 0, 'Alex Minnie', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, '', ''),
(11, 6, NULL, '2017-06-08', '2017-07-09', 0, 2, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(12, 6, NULL, '2017-06-09', '2017-07-10', 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, 'Hi', 'Thanks'),
(13, 6, NULL, '2017-06-09', '2017-07-10', 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(14, 6, NULL, '2017-06-19', '2017-07-20', 0, 1, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(15, 6, NULL, '2017-07-04', '2017-08-20', 0, 2, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', ''),
(16, 6, NULL, '2017-08-10', '2017-09-10', 0, 0, 1, 0, 'Alex Minnie', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, '', '');

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

--
-- Dumping data for table `customerrecurring`
--

INSERT INTO `customerrecurring` (`RecurringID`, `CustomerID`, `StartDate`, `EndDate`, `Frequency`, `DueDateForPayment`, `InvoiceDateAdded`, `LastRun`, `NextRun`, `ClientReccuringInvoiceNumber`, `ReferenceNumber`, `RecurringStatus`, `InvoiceNotes`, `AddedByClient`, `AddedByEmployee`, `DiscountPercentage`, `AddedByName`) VALUES
(1, 1, '2016-09-17', '0000-00-00', 'Every Month', 5, '2016-09-16', NULL, NULL, 'RINV0000001', '', 2, '', 1, 0, 20, NULL);

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

--
-- Dumping data for table `customerrecurringlines`
--

INSERT INTO `customerrecurringlines` (`RecurringLineItemID`, `RecurringID`, `Description`, `Quantity`, `Price`, `ItemID`, `LineTotal`) VALUES
(1, 1, 'ADSL 4MB Line Speed', 1, 199, 1, 199);

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
(5, 'Jono', 'Hornsby', 'Easy2Access', '08737373737', 'alex@e2a.co.za', '29 Stapelton Rd', 'Durban North', 'Durban', 'KZN', '4093', 192, 0, 0, 1, 'Debit Order', 2, '', '', '2016-11-17 15:09:21', 'JON001'),
(6, 'Ben', 'Botes', 'E2A', '12532544', 'ben@e2a.co.za', '2 Impangele Road', 'Kloof', 'Durban', 'KZN', '3640', 192, 0, 0, 1, 'Debit Order', 2, '154642221', 'Test', '2017-05-13 04:39:04', 'BEN001');

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

--
-- Dumping data for table `customersites`
--

INSERT INTO `customersites` (`SiteID`, `CustomerID`, `SiteName`, `ContactNumber`, `EmailAddress`, `Address1`, `Address2`, `City`, `Region`, `PostCode`, `CountryID`, `ContactPerson`) VALUES
(1, 6, 'Test', 'Test', 'test@test.co.za', 'Test', 'Test', 'Test', 'Test', 'Test', 192, 'Test'),
(2, 6, 'Test', 'Test', 'tester@test.co.za', 'Test', 'Test', 'Test', 'Test', 'Test', 192, 'Test');

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

--
-- Dumping data for table `customertask`
--

INSERT INTO `customertask` (`TaskID`, `CustomerID`, `TaskDescription`, `TaskDate`, `ClientID`, `EmployeeID`, `AddedByName`, `DateAdded`, `Status`) VALUES
(1, 5, 'Cancel client services end of the month', '2016-12-30', 1, 0, 'Alex Minnie', '2016-12-19', 0),
(2, 6, 'Call client to Setup Meeting', '2017-05-14', 1, 0, 'Alex Minnie', '2017-05-13', 0);

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

--
-- Dumping data for table `customertransactions`
--

INSERT INTO `customertransactions` (`TransactionID`, `CustomerID`, `PaymentDate`, `ClientID`, `EmployeeID`, `AddedByName`, `TotalPayment`, `Description`, `PaymentMethod`, `TransactionReference`) VALUES
(1, 5, '2017-02-05', 1, 0, 'Alex Minnie', 2000, 'Cash Deposit', 'EFT Payment', 'feb05'),
(2, 6, '2017-05-13', 1, 0, 'Alex Minnie', 2518.55, 'Invoice Payment INV00000018', 'EFT Payment', '124'),
(3, 6, '2017-05-13', 1, 0, 'Alex Minnie', 8892, 'Invoice Payment INV00000020', 'EFT Payment', '225');

-- --------------------------------------------------------

--
-- Table structure for table `employeedepartments`
--

CREATE TABLE `employeedepartments` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeedepartments`
--

INSERT INTO `employeedepartments` (`DepartmentID`, `DepartmentName`) VALUES
(1, 'Web Dev'),
(2, 'Human Resources');

-- --------------------------------------------------------

--
-- Table structure for table `employeesecuritygroups`
--

CREATE TABLE `employeesecuritygroups` (
  `EmployeeSecurityGroupID` int(11) NOT NULL,
  `SecurityGroupID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeesecuritygroups`
--

INSERT INTO `employeesecuritygroups` (`EmployeeSecurityGroupID`, `SecurityGroupID`, `EmployeeID`) VALUES
(1, 1, 4);

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

--
-- Dumping data for table `jobcards`
--

INSERT INTO `jobcards` (`JobcardID`, `CustomerID`, `AssignedTo`, `JobcardFile`, `AddedByEmployee`, `AddedBy`, `AddedByName`, `InvoiceID`, `DateCreated`, `DateScheduled`, `DateTechReport`, `DateInvoice`, `JobcardNotes`, `JobcardStatus`, `TechReport`, `ManualJobcardNumber`, `TotalTime`, `SiteID`, `WorkOrder`) VALUES
(1, 5, 1, 'JBC1_1487244886', 0, 1, 'Alex Minnie', 25, '2017-02-16', '2017-02-20', '2017-02-16', '2017-02-16', 'Test adding a new jobcard to the system', 2, 'Job went well', NULL, NULL, 0, NULL),
(2, 5, 4, 'JBC2_1491219616', 0, 1, 'Alex Minnie', 11, '2017-02-16', '2017-02-16', '2017-04-03', '2017-04-03', 'fridge 1 all others were ok', 2, 'was hard', '', '', 0, NULL),
(3, 5, 4, 'JBC3_1493372205_/usr/home/buscrjrzmx/.tmp/phpA1JHn7', 0, 1, 'Alex Minnie', 26, '2017-02-16', '2017-02-16', '2017-06-08', '2017-06-08', '', 2, '', '', '01:00', 0, NULL),
(4, 5, 4, 'JBC4_1491219553', 0, 1, 'Alex Minnie', 8, '2017-02-20', '2017-02-20', '2017-02-20', '2017-02-20', 'fridge 2 worked on,replaced light', 2, 'cleaned area as well', '', '', 0, NULL),
(5, 5, 4, 'JBC5_1496914388_test.jpg', 0, 1, 'Alex Minnie', 1, '2017-02-23', '2017-02-23', '2017-06-08', '2017-06-08', '', 2, '', '', '03:00', 0, NULL),
(6, 5, 4, 'JBC6_1491219996', 0, 1, 'Alex Minnie', 13, '2017-04-03', '2017-04-04', '2017-04-03', '2017-04-03', 'Repair Aircon', 2, 'Replaced Filter\nGas\nService', '12358', '04:00', 0, NULL),
(7, 6, 4, 'JBC7_1494647080_test.jpg', 0, 1, 'Alex Minnie', 25, '2017-05-13', '2017-05-16', '2017-06-08', '2017-06-08', 'Repair front brakes', 2, 'Brakes were fine', '3257', '01:00', 0, NULL),
(8, 6, 4, 'JBC8_1499245087_DSC06222.JPG', 0, 1, 'Alex Minnie', 18, '2017-06-07', '2017-06-07', '2017-07-05', '2017-07-05', 'Test', 2, 'vki uygipug p', '123545', '04:15', 0, ''),
(9, 5, 4, 'JBC9_1496914478_test.jpg', 0, 1, 'Alex Minnie', 2, '2017-06-08', '2017-06-09', '2017-06-08', '2017-06-08', 'test', 2, '', '', '03:15', 0, NULL),
(10, 6, 4, 'JBC10_1497014437_test.jpg', 0, 1, 'Alex Minnie', 8, '2017-06-09', '2017-06-11', '2017-06-09', '2017-06-09', 'Repair Fridge', 2, '', '12589', '05:30', 0, NULL),
(11, 6, 4, 'JBC11_1499245722_DSC06222.JPG', 0, 1, 'Alex Minnie', NULL, '2017-07-05', '2017-07-06', '2017-07-05', NULL, 'Fridge not working', 1, '', '1254, 1357', '04:00', 0, 'A1254'),
(12, 6, 4, NULL, 0, 1, 'Alex Minnie', NULL, '2017-07-28', '2017-07-31', NULL, NULL, '', 0, NULL, NULL, NULL, 0, '124');

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
(4, 'Other', 4, 0, 1, 0),
(5, 'yrdy', 5, 1, 1, 0);

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
(13, 12, 3000, 1, 'Annually', 0, 1, 12, 0),
(14, 12, 1800, 1, 'Monthly', 0, 2, 24, 0),
(15, 12, 2200, 1, 'Monthly', 0, 3, 36, 0),
(16, 12, 500, 1, 'Once-Off', 0, 4, 48, 5),
(17, 13, 123.95, 1, 'Once-Off', 0, 2, 24, 0),
(18, 13, 284.67, 1, 'Once-Off', 0, 4, 48, 20),
(19, 15, 7800, 0, 'Once-Off', 0, 1, 1, 0),
(20, 16, 49, 8, 'Monthly', 1, 1, 1, 0),
(21, 17, 100, 0, 'Once-Off', 0, 1, 1, 0),
(22, 18, 1, 0, 'Once-Off', 0, 1, 1, 0);

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

--
-- Dumping data for table `productcustomentries`
--

INSERT INTO `productcustomentries` (`ProductCustomValueID`, `ProductID`, `CustomFieldID`, `CustomFieldOptionID`, `CustomOptionValue`) VALUES
(20, 9, 10, 0, '16'),
(21, 9, 1, 0, 'Test'),
(22, 9, 4, 0, 'Test'),
(23, 9, 7, 0, 'Test'),
(24, 9, 5, 0, 'Test'),
(25, 9, 3, 0, 'Test'),
(26, 9, 6, 0, 'Test'),
(27, 9, 11, 0, 'Test'),
(28, 10, 10, 0, '16'),
(29, 10, 1, 0, 'Test'),
(30, 10, 4, 0, 'Test'),
(31, 10, 7, 0, 'Test'),
(32, 10, 5, 0, 'Test'),
(33, 10, 3, 0, 'Test'),
(34, 10, 6, 0, 'Test'),
(35, 10, 11, 0, 'Test'),
(36, 11, 10, 0, '16'),
(37, 11, 1, 0, 'Test'),
(38, 11, 4, 0, 'Test'),
(39, 11, 7, 0, 'Test'),
(40, 11, 5, 0, 'Test'),
(41, 11, 3, 0, 'Test'),
(42, 11, 6, 0, 'Test'),
(43, 11, 11, 0, 'Test'),
(44, 12, 10, 0, '16'),
(45, 12, 1, 0, 'Test'),
(46, 12, 4, 0, 'Test'),
(47, 12, 7, 0, 'Test'),
(48, 12, 5, 0, 'Test'),
(49, 12, 3, 0, 'Test'),
(50, 12, 6, 0, 'Test'),
(51, 12, 9, 12, 'true'),
(52, 12, 9, 13, 'true'),
(53, 12, 9, 15, 'true'),
(54, 12, 11, 0, 'Test'),
(55, 13, 10, 0, ''),
(56, 13, 1, 0, ''),
(57, 13, 4, 0, '12'),
(58, 13, 7, 0, '12'),
(59, 13, 5, 0, '12'),
(60, 13, 3, 0, '24'),
(61, 13, 6, 0, '12'),
(62, 13, 9, 12, 'false'),
(63, 13, 9, 13, 'false'),
(64, 13, 9, 15, 'false'),
(65, 13, 11, 0, ''),
(66, 14, 10, 0, ''),
(67, 14, 1, 0, ''),
(68, 14, 4, 0, '123'),
(69, 14, 7, 0, '123'),
(70, 14, 5, 0, '123'),
(71, 14, 3, 0, ''),
(72, 14, 6, 0, '123'),
(73, 14, 9, 12, 'false'),
(74, 14, 9, 13, 'false'),
(75, 14, 9, 15, 'false'),
(76, 14, 11, 0, ''),
(77, 15, 10, 0, '16'),
(78, 15, 1, 0, '2 weeks'),
(79, 15, 4, 0, '500'),
(80, 15, 7, 0, '5'),
(81, 15, 5, 0, '1.5'),
(82, 15, 3, 0, ''),
(83, 15, 6, 0, '1.6'),
(84, 15, 9, 12, 'false'),
(85, 15, 9, 13, 'false'),
(86, 15, 9, 15, 'true'),
(87, 15, 11, 0, ''),
(88, 16, 10, 0, '16'),
(89, 16, 1, 0, ''),
(90, 16, 4, 0, '0'),
(91, 16, 7, 0, '0'),
(92, 16, 5, 0, '0'),
(93, 16, 3, 0, ''),
(94, 16, 6, 0, '0'),
(95, 16, 9, 12, 'false'),
(96, 16, 9, 13, 'false'),
(97, 16, 9, 15, 'false'),
(98, 16, 11, 0, ''),
(99, 17, 10, 0, ''),
(100, 17, 1, 0, ''),
(101, 17, 4, 0, '1'),
(102, 17, 7, 0, '1'),
(103, 17, 5, 0, '1'),
(104, 17, 3, 0, ''),
(105, 17, 6, 0, '1'),
(106, 17, 9, 12, 'false'),
(107, 17, 9, 13, 'false'),
(108, 17, 9, 15, 'false'),
(109, 17, 11, 0, ''),
(110, 18, 10, 0, ''),
(111, 18, 1, 0, ''),
(112, 18, 4, 0, '1'),
(113, 18, 7, 0, '1'),
(114, 18, 5, 0, '1'),
(115, 18, 3, 0, ''),
(116, 18, 6, 0, '1'),
(117, 18, 9, 12, 'false'),
(118, 18, 9, 13, 'false'),
(119, 18, 9, 15, 'false'),
(120, 18, 11, 0, '');

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
  `ShowQuote` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productcustomfields`
--

INSERT INTO `productcustomfields` (`CustomFieldID`, `CustomFieldName`, `CustomFieldType`, `Required`, `DisplayOrder`, `ShowInvoice`, `ShowQuote`) VALUES
(1, 'Order Lead Time', 'text', 0, 2, 0, 1),
(3, 'Pallet Size', 'text', 0, 6, 0, 0),
(4, 'Weight', 'text', 1, 3, 0, 0),
(5, 'Width', 'text', 1, 5, 0, 0),
(6, 'Height', 'text', 1, 7, 0, 0),
(7, 'Length', 'text', 1, 4, 0, 0),
(9, 'Postage Type', 'checkbox', 0, 8, 1, 0),
(10, 'Test Another', 'select', 0, 1, 0, 0),
(11, 'Test Text Area', 'textarea', 0, 9, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productcustomfieldsvalues`
--

CREATE TABLE `productcustomfieldsvalues` (
  `CustomFieldOptionID` int(11) NOT NULL,
  `CustomFieldID` int(11) DEFAULT NULL,
  `OptionValue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productcustomfieldsvalues`
--

INSERT INTO `productcustomfieldsvalues` (`CustomFieldOptionID`, `CustomFieldID`, `OptionValue`) VALUES
(1, 2, 'Google'),
(2, 2, 'Bing'),
(3, 2, 'Other Search Engine'),
(4, 2, 'Friend Referral'),
(5, 2, 'Advertisement'),
(6, 2, 'Other'),
(7, 5, 'Yes'),
(8, 7, 'Only Saturday'),
(9, 7, 'Only Sunday'),
(10, 5, 'No'),
(12, 9, 'Express2'),
(13, 9, 'Overnight'),
(15, 9, 'Road Freight'),
(16, 10, 'Testing adding an option');

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
(1, 'Web Design'),
(2, 'Rapid Development'),
(4, 'Beverages'),
(5, 'Hardware'),
(6, 'SEO Services'),
(7, 'Programming'),
(8, 'Spices'),
(9, 'Test'),
(10, 'Software'),
(11, 'Asset Tags');

-- --------------------------------------------------------

--
-- Table structure for table `productimages`
--

CREATE TABLE `productimages` (
  `ProductImageID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productimages`
--

INSERT INTO `productimages` (`ProductImageID`, `ProductID`, `ProductImage`) VALUES
(3, 12, '1478863054_5135985.jpg'),
(4, 12, '1478863062_7241270_orig.jpg'),
(5, 12, '1478863069_7717022.jpg'),
(10, 12, '1478863489_patio.jpg'),
(12, 12, '1478863693_3146347.jpg'),
(13, 12, '1478863703_5939154_orig.jpg'),
(14, 12, '1478863710_3146347_orig.jpg'),
(15, 12, '1478863715_5135985_orig.jpg'),
(16, 16, '1494644874_test.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `productmeasurement`
--

CREATE TABLE `productmeasurement` (
  `MeasurementID` int(11) NOT NULL,
  `MeasurementDescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productmeasurement`
--

INSERT INTO `productmeasurement` (`MeasurementID`, `MeasurementDescription`) VALUES
(1, 'dozen'),
(2, 'm'),
(5, 'ml'),
(7, 'kg'),
(8, 'Each');

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
(12, 'Fanta Grape', 4, 0, 1, 'X4536', 'Test', 0, 1, 0, 'Test', 2, 100),
(13, 'Expansion Valve', 4, 0, 1, 'X235', 'Coca-Cola', 0, 1, 0, 'C93484393489348', 2, 20),
(14, 'Test', 4, 0, 1, 'Test', 'tEst', 0, 1, 2, 'Test', 2, 0),
(15, 'Fridge 1', 5, 0, 1, '12354654', 'Fridge double door', 0, 1, 24, 'aa125463d445423df', 2, 0),
(16, 'Web Hosting', 1, 0, 0, 'HOST1235', 'Micro Hosting', 0, 1, 0, '', 2, 0),
(17, 'Module 1', 10, 0, 0, 'abx1', 'Module 1', 0, 1, 0, '', 2, 0),
(18, 'Asset tag Test', 11, 0, 0, 'Ass001', 'Asste Tag Test', 0, 0, 0, '', 2, 0);

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
(5, 12, 48, '2017-01-12 00:00:00', 'Purchased', 9, 5, 0, 1, NULL, NULL),
(6, 13, 3600, '2017-01-12 00:00:00', 'Purchased', 6.23, 5, 0, 1, NULL, NULL),
(7, 12, 192, '2017-01-12 00:00:00', 'Purchased', 9, 5, 0, 1, NULL, NULL),
(8, 13, 3600, '2017-01-12 00:00:00', 'Purchased', 6.25, 5, 0, 1, NULL, NULL),
(9, 13, 6, '2017-01-24 00:00:00', 'Purchased', 95, 9, 0, 1, NULL, NULL),
(10, 13, 200, '2017-01-27 00:00:00', 'Purchased', 95, 10, 0, 1, NULL, NULL),
(11, 12, 672, '2017-01-27 00:00:00', 'Purchased', 7.92, 11, 0, 1, NULL, NULL),
(12, 12, -240, '2017-01-27 00:00:00', 'Sell', 7.92, 0, 6, 1, NULL, NULL),
(13, 13, -96, '2017-01-27 00:00:00', 'Sell', 3.71, 0, 6, 1, NULL, NULL),
(14, 13, 0, '2017-02-06 00:00:00', 'Stock Take (7310)', 95, 0, 0, 1, NULL, NULL),
(15, 12, 0, '2017-02-06 00:00:00', 'Stock Take (672)', 7.92, 0, 0, 1, NULL, NULL),
(16, 12, 0, '2017-02-06 00:00:00', 'Stock Take (672)', 7.92, 0, 0, 1, NULL, NULL),
(17, 12, 0, '2017-02-06 00:00:00', 'Stock Take (672)', 7.92, 0, 0, 1, NULL, NULL),
(18, 13, -100, '2017-02-23 00:00:00', 'Stock Movement from Main to Pmb', 95, 0, 0, 1, NULL, NULL),
(19, 13, 100, '2017-02-23 00:00:00', 'Stock Movement from Main to Pmb', 95, 0, 0, 2, 1, NULL),
(20, 13, -100, '2017-03-09 08:58:09', 'Stock Take (7110)', 95, 0, 0, 1, NULL, 3),
(21, 13, -120, '2017-03-16 16:16:12', 'Sell', 3.71, 0, 9, 1, NULL, NULL),
(22, 13, -24, '2017-03-27 11:34:34', 'Sell', 3.71, 0, 11, 1, NULL, NULL),
(23, 13, -48, '2017-04-03 13:47:35', 'Sell', 3.71, 0, 13, 1, NULL, NULL),
(24, 13, -2, '2017-04-05 15:08:51', 'Stock Movement from Main to Pmb', 95, 0, 0, 1, NULL, NULL),
(25, 13, 2, '2017-04-05 15:08:51', 'Stock Movement from Main to Pmb', 95, 0, 0, 2, 1, NULL),
(26, 12, -96, '2017-05-02 11:44:17', 'Sell', 7.92, 0, 16, 1, NULL, NULL),
(27, 13, -600, '2017-05-02 11:44:17', 'Sell', 3.71, 0, 16, 1, NULL, NULL),
(28, 15, -1, '2017-05-02 11:44:17', 'Sell', 0, 0, 16, 1, NULL, NULL),
(29, 13, -360, '2017-05-13 04:48:00', 'Sell', 3.71, 0, 18, 1, NULL, NULL),
(30, 15, -1, '2017-05-13 05:34:17', 'Sell', 0, 0, 20, 1, NULL, NULL),
(31, 15, 2, '2017-05-13 06:29:27', 'Stock Take (0)', 0, 0, 0, 1, NULL, 4),
(32, 13, -24, '2017-06-08 10:28:10', 'Sell', 3.71, 0, 19, 1, NULL, NULL),
(33, 13, -24, '2017-06-08 10:30:27', 'Sell', 3.71, 0, 22, 1, NULL, NULL),
(34, 15, -1, '2017-06-08 11:28:36', 'Sell', 0, 0, 24, 1, NULL, NULL),
(35, 13, -24, '2017-06-08 11:32:19', 'Sell', 3.71, 0, 26, 1, NULL, NULL),
(36, 15, -2, '2017-06-08 11:33:37', 'Sell', 0, 0, 1, 1, NULL, NULL),
(37, 15, -4, '2017-06-08 11:35:03', 'Sell', 0, 0, 2, 1, NULL, NULL),
(38, 15, -1, '2017-06-08 11:43:05', 'Sell', 0, 0, 3, 1, NULL, NULL),
(39, 13, -24, '2017-06-09 15:06:31', 'Sell', 3.71, 0, 6, 1, NULL, NULL),
(40, 15, -2, '2017-06-09 15:06:31', 'Sell', 0, 0, 6, 1, NULL, NULL),
(41, 13, -24, '2017-06-09 15:24:49', 'Sell', 3.71, 0, 8, 1, NULL, NULL),
(42, 13, -24, '2017-06-09 15:26:21', 'Sell', 3.71, 0, 9, 1, NULL, NULL),
(43, 16, -1, '2017-06-09 15:36:34', 'Sell', 0, 0, 10, 1, NULL, NULL),
(44, 16, -1, '2017-06-09 16:26:43', 'Sell', 0, 0, 11, 1, NULL, NULL),
(45, 13, -48, '2017-06-29 12:39:14', 'Sell', 3.71, 0, 7, 1, NULL, NULL),
(46, 15, -1, '2017-06-29 12:39:14', 'Sell', 0, 0, 7, 1, NULL, NULL),
(47, 17, -1, '2017-06-29 12:39:14', 'Sell', 0, 0, 7, 1, NULL, NULL),
(48, 12, -24, '2017-07-03 00:00:00', 'Sell', 7.92, 0, 17, 1, NULL, NULL),
(49, 13, -24, '2017-07-05 00:00:00', 'Sell', 3.71, 0, 18, 1, NULL, NULL),
(50, 13, -24, '2017-07-05 00:00:00', 'Sell', 3.71, 0, 19, 1, NULL, NULL),
(51, 17, -1, '2017-07-25 00:00:00', 'Sell', 0, 0, 16, 1, NULL, NULL),
(52, 13, -24, '2017-08-10 00:00:00', 'Sell', 3.71, 0, 20, 1, NULL, NULL),
(53, 15, -1, '2017-08-10 00:00:00', 'Sell', 0, 0, 20, 1, NULL, NULL),
(54, 13, -24, '2017-08-10 00:00:00', 'Sell', 3.71, 0, 22, 1, NULL, NULL),
(55, 17, -2, '2017-08-10 00:00:00', 'Sell', 0, 0, 22, 1, NULL, NULL),
(56, 18, -10, '2017-08-10 00:00:00', 'Sell', 0, 0, 22, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `productsubgroups`
--

CREATE TABLE `productsubgroups` (
  `ProductSubGroupID` int(11) NOT NULL,
  `ProductGroupID` int(11) DEFAULT NULL,
  `SubGroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productsubgroups`
--

INSERT INTO `productsubgroups` (`ProductSubGroupID`, `ProductGroupID`, `SubGroupName`) VALUES
(1, 1, 'Hosting'),
(2, 4, 'test');

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

--
-- Dumping data for table `purchaseorderlines`
--

INSERT INTO `purchaseorderlines` (`PurchaseLineItemID`, `PurchaseID`, `Description`, `Quantity`, `Price`, `ProductID`, `ProductCode`, `LineSubTotal`, `LineDiscount`, `LineVAT`, `LineTotal`, `BillingType`, `StockAffect`, `MeassurementDescription`, `SupplierCostID`) VALUES
(6, 6, 'Coke - Coca-Cola', 1, 140, 13, 'X235', 140, 0, 19.6, 159.6, 'Once-Off', 2, '2 kg', 8),
(7, 7, 'Coke - Coca-Cola', 100, 190, 13, 'X235', 19000, 0, 2660, 21660, 'Once-Off', 200, '2 kg', 8),
(8, 8, 'Fanta Grape - Test', 14, 380, 12, 'X4536', 5320, 0, 744.8, 6064.8, 'Once-Off', 672, '4 dozen', 9),
(9, 10, 'Expansion Valve - Coca-Cola', 1, 89, 13, 'X235', 89, 0, 12.46, 101.46, 'Once-Off', 24, '2 dozen', 8);

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
  `SpecialInstructions` text,
  `WarehouseID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchaseorders`
--

INSERT INTO `purchaseorders` (`PurchaseID`, `SupplierID`, `PurchaseNumber`, `PurchaseOrderDate`, `PurchaseStatus`, `AddedByClient`, `AddedByEmployee`, `AddedByName`, `SentDate`, `DeliveryType`, `SpecialInstructions`, `WarehouseID`) VALUES
(1, 2, 'PO0001', '2016-12-23', 2, 1, 0, 'Alex Minnie', '2017-01-10', 'Deliver', NULL, 1),
(4, 2, 'PO4', '2017-01-09', 0, 1, 0, 'Alex Minnie', NULL, 'Deliver', 'Test PO Instructions', 1),
(5, 2, 'PO5', '2017-01-09', 0, 1, 0, 'Alex Minnie', NULL, 'Deliver', 'Test', 1),
(6, 1, 'PO6', '2017-01-23', 2, 1, 0, 'Alex Minnie', '2017-01-23', 'Deliver', 'Test', 1),
(7, 1, 'PO7', '2017-01-27', 2, 1, 0, 'Alex Minnie', '2017-01-27', 'Deliver', '', 1),
(8, 1, 'PO8', '2017-01-27', 2, 1, 0, 'Alex Minnie', '2017-01-27', 'Deliver', '', 1),
(9, 3, 'PO9', '2017-06-09', 0, 1, 0, 'Alex Minnie', NULL, 'Deliver', '', 1),
(10, 2, 'PO10', '2017-07-05', 1, 1, 0, 'Alex Minnie', '2017-07-05', 'Deliver', 'hgdujy., ', 2);

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups`
--

CREATE TABLE `securitygroups` (
  `SecurityGroupID` int(11) NOT NULL,
  `SecurityGroupName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `securitygroups`
--

INSERT INTO `securitygroups` (`SecurityGroupID`, `SecurityGroupName`) VALUES
(1, 'Admin'),
(2, 'test');

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

--
-- Dumping data for table `stocktakes`
--

INSERT INTO `stocktakes` (`StockTakeID`, `WarehouseID`, `StockTakeDate`, `StockTakeStatus`, `StockTakeCompleted`) VALUES
(3, 1, '2017-03-09 08:57:33', 1, '2017-03-09 08:58:09'),
(4, 1, '2017-05-13 06:28:28', 1, '2017-05-13 06:29:28');

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

--
-- Dumping data for table `suppliercost`
--

INSERT INTO `suppliercost` (`SupplierCostID`, `ProductID`, `SupplierCost`, `MeasurementID`, `BillingType`, `ProRataBilling`, `PackSize`, `StockAffect`, `MinimumOrder`, `PricePerUnit`, `SupplierID`) VALUES
(8, 13, 89, 1, 'Once-Off', 0, 2, 24, 0, 3.71, 1),
(9, 12, 380, 1, 'Once-Off', 0, 4, 48, 5, 7.92, 1);

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

--
-- Dumping data for table `suppliercostingtracking`
--

INSERT INTO `suppliercostingtracking` (`SupplierCostingID`, `ProductID`, `SupplierCostID`, `SupplierCost`, `UnitCost`, `PriceDate`) VALUES
(1, 12, 5, 286.31, 5.96, '2017-01-03'),
(2, 12, 5, 286.31, 5.96, '2017-01-03'),
(3, 12, 5, 432, 9, '2017-01-03'),
(4, 13, 6, 897.32, 6.23, '2017-01-03'),
(5, 13, 6, 900, 6.25, '2017-01-12'),
(6, 12, 5, 432, 9, '2017-01-16'),
(7, 13, 6, 490, 3.4, '2017-01-16'),
(8, 12, 7, 340, 18.89, '2017-01-16'),
(9, 12, 7, 350, 19.44, '2017-01-16'),
(10, 13, 8, 120, 60, '2017-01-23'),
(11, 13, 8, 140, 70, '2017-01-24'),
(12, 13, 8, 190, 95, '2017-01-24'),
(13, 12, 9, 380, 7.92, '2017-01-27'),
(14, 13, 8, 89, 44.5, '2017-01-27'),
(15, 13, 8, 89, 3.71, '2017-01-27');

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

--
-- Dumping data for table `supplierorderlines`
--

INSERT INTO `supplierorderlines` (`SupplierInvoiceLineItemID`, `SupplierInvoiceID`, `Description`, `Quantity`, `Price`, `ProductID`, `ProductCode`, `LineSubTotal`, `LineDiscount`, `LineVAT`, `LineTotal`, `BillingType`, `StockAffect`, `MeassurementDescription`, `SupplierCostID`) VALUES
(11, 9, 'Coke - Coca-Cola', 3, 190, 13, 'X235', 570, 0, 79.8, 649.8, 'Once-Off', 6, '2 kg', 8),
(12, 10, 'Coke - Coca-Cola', 100, 190, 13, 'X235', 19000, 0, 2660, 21660, 'Once-Off', 200, '2 kg', 8),
(13, 11, 'Fanta Grape - Test', 14, 380, 12, 'X4536', 5320, 0, 744.8, 6064.8, 'Once-Off', 672, '4 dozen', 9);

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
  `InvoiceFile` varchar(255) DEFAULT NULL,
  `WarehouseID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplierorders`
--

INSERT INTO `supplierorders` (`SupplierInvoiceID`, `SupplierID`, `PurchaseOrderID`, `InvoiceDate`, `InvoiceStatus`, `AddedByClient`, `AddedByEmployee`, `AddedByName`, `PurchaseNumber`, `InvoiceNumber`, `InvoiceFile`, `WarehouseID`) VALUES
(9, 1, 6, '2017-01-24', 1, 1, 0, 'Alex Minnie', 'PO6', 'INV89539358', '1485506071_Invoice_Alex.pdf', 1),
(10, 1, 7, '2017-01-27', 1, 1, 0, 'Alex Minnie', 'PO7', 'INV123456', NULL, 1),
(11, 1, 8, '2017-01-27', 1, 1, 0, 'Alex Minnie', 'PO8', 'INV8758745', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplierproducts`
--

CREATE TABLE `supplierproducts` (
  `SupplierProductID` int(11) NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplierproducts`
--

INSERT INTO `supplierproducts` (`SupplierProductID`, `SupplierID`, `ProductID`) VALUES
(1, 1, 13),
(2, 1, 12),
(3, 2, 13),
(4, 3, 15),
(5, 2, 15);

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

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `SupplierName`, `SupplierEmail`, `SupplierTel`, `SupplierFax`, `SupplierContact`, `SupplierVat`, `SupplierAddress1`, `SupplierAddress2`, `City`, `State`, `PostCode`, `CountryID`, `SupplierNote`, `SupplierStatus`, `ChargesVAT`) VALUES
(1, 'Mustek', 'alex@e2a.co.za', '0314648390', '031585949', 'Dwayne', '6234872364', '7 Somewhere Someplace', '', 'Durban', 'KZN', '4094', 192, '', 1, 1),
(2, 'Engen', 'alex@allweb.co.za', '031464648399', '', 'Alex', '', '7 Somewhere', 'Someplace', 'Durban', 'KZN', '4093', 192, 'Test adding a supplier to the system', 1, 1),
(3, 'Hetzner', 'suport@hetzner.co.za', '1255456', '', 'support', '115122', 'test', 'test', 'cape town', 'cape town', '2550', 192, '', 1, 1);

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
(1, 'Main'),
(2, 'Pmb');

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
-- Indexes for table `customclientfields`
--
ALTER TABLE `customclientfields`
  ADD PRIMARY KEY (`CustomFieldID`);

--
-- Indexes for table `customclientfieldsvalues`
--
ALTER TABLE `customclientfieldsvalues`
  ADD PRIMARY KEY (`CustomClientFieldOptionID`);

--
-- Indexes for table `customcustomerfields`
--
ALTER TABLE `customcustomerfields`
  ADD PRIMARY KEY (`CustomFieldID`);

--
-- Indexes for table `customcustomerfieldsvalues`
--
ALTER TABLE `customcustomerfieldsvalues`
  ADD PRIMARY KEY (`CustomClientFieldOptionID`);

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
-- Indexes for table `customercustomfieldvalues`
--
ALTER TABLE `customercustomfieldvalues`
  ADD PRIMARY KEY (`ClientCustomFieldID`);

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
-- AUTO_INCREMENT for table `customclientfields`
--
ALTER TABLE `customclientfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `customclientfieldsvalues`
--
ALTER TABLE `customclientfieldsvalues`
  MODIFY `CustomClientFieldOptionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `customcustomerfields`
--
ALTER TABLE `customcustomerfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customcustomerfieldsvalues`
--
ALTER TABLE `customcustomerfieldsvalues`
  MODIFY `CustomClientFieldOptionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customeraccess`
--
ALTER TABLE `customeraccess`
  MODIFY `CustomerAccessLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1313;
--
-- AUTO_INCREMENT for table `customercontacts`
--
ALTER TABLE `customercontacts`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customercustomentries`
--
ALTER TABLE `customercustomentries`
  MODIFY `CustomerCustomValueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `customercustomfields`
--
ALTER TABLE `customercustomfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `customercustomfieldsvalues`
--
ALTER TABLE `customercustomfieldsvalues`
  MODIFY `CustomFieldOptionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customercustomfieldvalues`
--
ALTER TABLE `customercustomfieldvalues`
  MODIFY `ClientCustomFieldID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerdocumentgroups`
--
ALTER TABLE `customerdocumentgroups`
  MODIFY `DocumentGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customerdocuments`
--
ALTER TABLE `customerdocuments`
  MODIFY `DocumentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customerfollowups`
--
ALTER TABLE `customerfollowups`
  MODIFY `FollowUpID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customerinvoicegroups`
--
ALTER TABLE `customerinvoicegroups`
  MODIFY `InvoiceGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customerinvoicelines`
--
ALTER TABLE `customerinvoicelines`
  MODIFY `InvoiceLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `customerinvoicepayments`
--
ALTER TABLE `customerinvoicepayments`
  MODIFY `InvoicePaymentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customerinvoices`
--
ALTER TABLE `customerinvoices`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `customerlogs`
--
ALTER TABLE `customerlogs`
  MODIFY `CustomerLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customernotes`
--
ALTER TABLE `customernotes`
  MODIFY `NoteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `customerproducts`
--
ALTER TABLE `customerproducts`
  MODIFY `ClientProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `customerquotelines`
--
ALTER TABLE `customerquotelines`
  MODIFY `QuoteLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `customerquotes`
--
ALTER TABLE `customerquotes`
  MODIFY `QuoteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `customerrecurring`
--
ALTER TABLE `customerrecurring`
  MODIFY `RecurringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customerrecurringlines`
--
ALTER TABLE `customerrecurringlines`
  MODIFY `RecurringLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `customersites`
--
ALTER TABLE `customersites`
  MODIFY `SiteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customertask`
--
ALTER TABLE `customertask`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customertransactions`
--
ALTER TABLE `customertransactions`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `employeedepartments`
--
ALTER TABLE `employeedepartments`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `employeesecuritygroups`
--
ALTER TABLE `employeesecuritygroups`
  MODIFY `EmployeeSecurityGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `JobcardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `jobcardtables`
--
ALTER TABLE `jobcardtables`
  MODIFY `JobcardTableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `productcost`
--
ALTER TABLE `productcost`
  MODIFY `ProductCostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `productcustomentries`
--
ALTER TABLE `productcustomentries`
  MODIFY `ProductCustomValueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `productcustomfields`
--
ALTER TABLE `productcustomfields`
  MODIFY `CustomFieldID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `productcustomfieldsvalues`
--
ALTER TABLE `productcustomfieldsvalues`
  MODIFY `CustomFieldOptionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `productgroups`
--
ALTER TABLE `productgroups`
  MODIFY `ProductGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `productimages`
--
ALTER TABLE `productimages`
  MODIFY `ProductImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `productmeasurement`
--
ALTER TABLE `productmeasurement`
  MODIFY `MeasurementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `productstock`
--
ALTER TABLE `productstock`
  MODIFY `StockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `productsubgroups`
--
ALTER TABLE `productsubgroups`
  MODIFY `ProductSubGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `purchaseorderlines`
--
ALTER TABLE `purchaseorderlines`
  MODIFY `PurchaseLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  MODIFY `PurchaseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `securitygroups`
--
ALTER TABLE `securitygroups`
  MODIFY `SecurityGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `securitygroupsettings`
--
ALTER TABLE `securitygroupsettings`
  MODIFY `SecurityGroupSettingsID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stocktakes`
--
ALTER TABLE `stocktakes`
  MODIFY `StockTakeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `suppliercost`
--
ALTER TABLE `suppliercost`
  MODIFY `SupplierCostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `suppliercostingtracking`
--
ALTER TABLE `suppliercostingtracking`
  MODIFY `SupplierCostingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `supplierorderlines`
--
ALTER TABLE `supplierorderlines`
  MODIFY `SupplierInvoiceLineItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `supplierorders`
--
ALTER TABLE `supplierorders`
  MODIFY `SupplierInvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `supplierproducts`
--
ALTER TABLE `supplierproducts`
  MODIFY `SupplierProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `WarehouseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
