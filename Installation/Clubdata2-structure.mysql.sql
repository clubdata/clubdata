-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Mai 2015 um 15:30
-- Server Version: 5.5.43-0ubuntu0.14.04.1
-- PHP-Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `Clubdata2a`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Addresses`
--

CREATE TABLE `###_Addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Adr_MemberID` smallint(6) NOT NULL,
  `Addresstype_ref` smallint(6) NOT NULL,
  `Salutation_ref` tinyint(4) DEFAULT NULL,
  `Title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Firstname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FirmName_ml` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FirmDepartment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ZipCode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Town` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country_ref` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Telephone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Fax` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `chat` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Html` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Logo_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Adr_MemberID` (`Adr_MemberID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Addresses of a member' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Addresses_Mailingtypes`
--

CREATE TABLE `###_Addresses_Mailingtypes` (
  `AddressID` smallint(6) NOT NULL DEFAULT '0',
  `Mailingtypes_ref` int(11) NOT NULL DEFAULT '0',
  KEY `AddressID` (`AddressID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Which attributes belong which member';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Addresstype`
--

CREATE TABLE `###_Addresstype` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FieldList` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `LetterFields_ml` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Type of addresses' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Attributes`
--

CREATE TABLE `###_Attributes` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Conferences`
--

CREATE TABLE `###_Conferences` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description_DE` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description_FR` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Startdate` date NOT NULL DEFAULT '0000-00-00',
  `Starttime` time NOT NULL DEFAULT '00:00:00',
  `Enddate` date DEFAULT NULL,
  `Endtime` time DEFAULT NULL,
  `DescrLong_UK` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `DescrLong_DE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `DescrLong_FR` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `PriceMember` decimal(6,2) NOT NULL DEFAULT '0.00',
  `PriceNoMember` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Active_yn` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Configuration`
--

CREATE TABLE `###_Configuration` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description_UK` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Country`
--

CREATE TABLE `###_Country` (
  `id` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `DialCode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Show_yn` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Country code to full name';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Emails`
--

CREATE TABLE `###_Emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EmailFrom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailTo` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailCC` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailBCC` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailSubject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailBody` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailSendtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `EmailAttachedFiles` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailEmailtype` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Emails sent to members' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Help`
--

CREATE TABLE `###_Help` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Category` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Subcategory` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Description_UK` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Category` (`Category`,`Subcategory`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Online Help' AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Help_Backup`
--

CREATE TABLE `###_Help_Backup` (
  `id` tinyint(4) NOT NULL DEFAULT '0',
  `Category` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Subcategory` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Description_UK` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_InfoGiveOut`
--

CREATE TABLE `###_InfoGiveOut` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_InfoWWW`
--

CREATE TABLE `###_InfoWWW` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Language`
--

CREATE TABLE `###_Language` (
  `id` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Log`
--

CREATE TABLE `###_Log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `host` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `task` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `parameter` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Log entires' AUTO_INCREMENT=362 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Mailingtypes`
--

CREATE TABLE `###_Mailingtypes` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EmailOK` tinyint(1) NOT NULL DEFAULT '1',
  `InvoiceAddr_yn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Address to which invoices are sent',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Type of mailings sent to members' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Memberfees`
--

CREATE TABLE `###_Memberfees` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `MemberID` smallint(6) NOT NULL DEFAULT '0',
  `InvoiceNumber` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `InvoiceDate` date NOT NULL DEFAULT '0000-00-00',
  `DueTo` date NOT NULL DEFAULT '0000-00-00',
  `Period` smallint(6) NOT NULL DEFAULT '0',
  `Amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `Remarks` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `DemandLevel` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `InvoiceNumber` (`InvoiceNumber`),
  KEY `ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Members`
--

CREATE TABLE `###_Members` (
  `MemberID` smallint(6) NOT NULL DEFAULT '0',
  `Membertype_ref` smallint(6) NOT NULL DEFAULT '0',
  `Birthdate` date DEFAULT NULL,
  `Entrydate` date DEFAULT NULL,
  `Remarks_ml` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `MembershiptypeSince` date DEFAULT NULL,
  `InfoGiveOut_ref` tinyint(1) NOT NULL DEFAULT '0',
  `InfoWWW_ref` tinyint(1) NOT NULL DEFAULT '0',
  `Selection_ml` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `LoginPassword_pw` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Language_ref` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `MainMemberID` smallint(6) DEFAULT NULL,
  UNIQUE KEY `MitgliedsNr` (`MemberID`),
  KEY `MitgliedsNr_2` (`MemberID`),
  KEY `LoginPassword_pw` (`LoginPassword_pw`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Members_Attributes`
--

CREATE TABLE `###_Members_Attributes` (
  `MemberID` smallint(6) NOT NULL DEFAULT '0',
  `Attributes_ref` int(11) NOT NULL DEFAULT '0',
  KEY `MA_MemberID` (`MemberID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Which attributes belong which member';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Members_Conferences`
--

CREATE TABLE `###_Members_Conferences` (
  `SubscriptionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MemberID` smallint(6) NOT NULL DEFAULT '0',
  `Conferences_ref` smallint(6) NOT NULL DEFAULT '0',
  `Firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FirmName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `NumPersons` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`SubscriptionID`),
  UNIQUE KEY `###_MemberConferencesUIDX` (`MemberID`,`Conferences_ref`),
  KEY `MemberID` (`MemberID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Which member has vistited which conference' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Members_Emails`
--

CREATE TABLE `###_Members_Emails` (
  `MemberID` smallint(6) NOT NULL DEFAULT '0',
  `EmailsID` int(11) NOT NULL DEFAULT '0',
  KEY `MemberID` (`MemberID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Which mail has been sent to which member';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Membertype`
--

CREATE TABLE `###_Membertype` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Kuerzel` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `SelectByDefault_yn` tinyint(1) NOT NULL DEFAULT '0',
  `IsCancelled_yn` tinyint(1) NOT NULL DEFAULT '0',
  `TypeDependencies` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Kuerzel`,`id`),
  UNIQUE KEY `Kuerzel` (`Kuerzel`),
  UNIQUE KEY `ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Newsletter`
--

CREATE TABLE `###_Newsletter` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `emailfrom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` blob NOT NULL,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Payments`
--

CREATE TABLE `###_Payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) NOT NULL DEFAULT '0',
  `InvoiceNumber` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Period` smallint(4) NOT NULL DEFAULT '0',
  `Amount` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Paydate` date NOT NULL DEFAULT '0000-00-00',
  `Paymode_ref` tinyint(1) NOT NULL DEFAULT '0',
  `Checknumber` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Paytype_ref` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `MitgliedsNr` (`MemberID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Paymode`
--

CREATE TABLE `###_Paymode` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Paytype`
--

CREATE TABLE `###_Paytype` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Description_UK` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_DE` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description_FR` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Salutation`
--

CREATE TABLE `###_Salutation` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `Description` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `BriefkopfSalutation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `BrieftextSalutation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ID` (`id`),
  KEY `ID_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `###_Users`
--

CREATE TABLE `###_Users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `Login` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Fullname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Password_pw` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Language_ref` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Admin_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateAll_yn` tinyint(1) NOT NULL DEFAULT '0',
  `InsertAll_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewAll_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteAll_yn` tinyint(1) NOT NULL DEFAULT '0',
  `InsertMember_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewMember_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteMember_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateFees_yn` tinyint(1) NOT NULL DEFAULT '0',
  `InsertFees_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewFees_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteFees_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdatePayments_yn` tinyint(1) NOT NULL DEFAULT '0',
  `InsertPayments_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewPayments_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeletePayments_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateMemberinfo_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewMemberinfo_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateFirm_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewFirm_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdatePrivat_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewPrivat_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewOverview_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewLists_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateEmail_yn` tinyint(1) NOT NULL DEFAULT '0',
  `CreateEmail_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewEmail_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteEmail_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateInfoletter_yn` tinyint(1) NOT NULL DEFAULT '0',
  `CreateInfoletter_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewInfoletter_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteInfoletter_yn` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateConferences_yn` tinyint(1) NOT NULL DEFAULT '0',
  `InsertConferences_yn` tinyint(1) NOT NULL DEFAULT '0',
  `ViewConferences_yn` tinyint(1) NOT NULL DEFAULT '0',
  `DeleteConferences_yn` tinyint(1) NOT NULL DEFAULT '0',
  `PersonalSettings_ro` varchar(1024) DEFAULT NULL COMMENT 'Personal setting, changeable by user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Login` (`Login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
