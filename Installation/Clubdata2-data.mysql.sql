-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Mai 2015 um 15:37
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

--
-- Daten für Tabelle `###_Addresses`
--

INSERT INTO `###_Addresses` (`id`, `Adr_MemberID`, `Addresstype_ref`, `Salutation_ref`, `Title`, `Firstname`, `Lastname`, `FirmName_ml`, `FirmDepartment`, `Address`, `ZipCode`, `Town`, `Country_ref`, `Telephone`, `Fax`, `Email`, `mobile`, `chat`, `Html`, `Logo_link`) VALUES
(1, 147, 1, 1, 'von', 'Franz', 'TEST', NULL, NULL, 'Wili-Busch-Str. 4', '81123', 'München', 'DE', '089-544321', '089-544411', 'test.franz@web.de', '', '', 'www.testfranz.com', NULL),
(2, 147, 2, NULL, NULL, NULL, NULL, 'Die Firma GmbH', '', 'Teststr. 17', '81111', 'München', 'DE', '08912345', '', '', '', '', '', ''),
(3, 148, 1, 1, 'von', 'Max', 'Muster', NULL, NULL, '', '', '', '', '', '', '', '', '', NULL, NULL),
(4, 148, 2, NULL, NULL, NULL, NULL, 'My Enterprise', '', 'test road 17', '81111', 'Munich', 'DE', '08912345', '', '', '', '', '', ''),
(5, 150, 1, 4, '', 'Mufline', 'Legrand', NULL, NULL, '2, rue de la gare', '77777', 'Bussy-St. George', 'FR', '0033 1 6 4004004', '0033 1 6 4004005', 'mufline.legrand@wanadoo.fr', '', '', 'www.legrands.fr', NULL);

--
-- Daten für Tabelle `###_Addresses_Mailingtypes`
--

INSERT INTO `###_Addresses_Mailingtypes` (`AddressID`, `Mailingtypes_ref`) VALUES
(2, 3),
(2, 2),
(2, 1),
(4, 1),
(4, 2),
(4, 3),
(1, 2),
(5, 1),
(5, 2),
(5, 3);

--
-- Daten für Tabelle `###_Addresstype`
--

INSERT INTO `###_Addresstype` (`id`, `Description_UK`, `Description_DE`, `Description_FR`, `FieldList`, `LetterFields_ml`) VALUES
(1, 'Privat', 'Privat', 'Private', 'Salutation_ref,Title,Firstname,Lastname,Address,ZipCode,Town,Country_ref,Telephone, Fax, Email, Html', 'Addresses_1.Salutation_ref AS $lAddressL1, CONCAT_WS('' '', Addresses_1.Title, Addresses_1.Firstname, Addresses_1.Lastname) AS $lAddressL2, Addresses_1.Address AS $lAddressL3, CONCAT_WS('' '', Addresses_1.ZipCode , Addresses_1.Town) AS $lAddressL4, Addresses_1.Country_ref AS $lAddressL5'),
(2, 'Firm', 'Firma', 'Entreprise', 'FirmName_ml, FirmDepartment, Address, ZipCode, Town,Country_ref, Telephone, Fax, Email,Html, Logo_link', 'Addresses_2.FirmName_ml as $lAddressL1, Addresses_2.FirmDepartment as $lAddressL2, CONCAT_WS('' '', Addresses_1.Salutation_ref, Addresses_1.Title, Addresses_1.Firstname, Addresses_1.Lastname) AS $lAddressL3, Addresses_2.Address AS $lAddressL4, CONCAT_WS('' '', Addresses_2.ZipCode , Addresses_2.Town) AS $lAddressL5, Addresses_2.Country_ref AS $lAddressL6'),
(3, 'Invoiceaddress', 'Rechnungsadresse', 'Facture', 'Salutation_ref,Title,Firstname,Lastname,FirmName_ml, FirmDepartment, Address, ZipCode, Town,Country_ref, Telephone, Fax, Email,Html, Logo_link', 'Addresses_3.FirmName_ml as $lAddressL1,\r\nAddresses_3.FirmDepartment as $lAddressL2,\r\nCONCAT_WS('' '', Addresses_3.Salutation_ref, Addresses_3.Title, \r\nAddresses_3.Firstname, Addresses_3.Lastname) AS $lAddressL3,\r\nAddresses_3.Address AS $lAddressL4,\r\nCONCAT_WS('' '', Addresses_3.ZipCode , Addresses_3.Town) AS $lAddressL5,\r\nAddresses_3.Country_ref AS $lAddressL6\r\n');

--
-- Daten für Tabelle `###_Attributes`
--

INSERT INTO `###_Attributes` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(1, 'Direct Debit', 'Lastschrift', 'Prélèvement automatique'),
(2, 'Invoice', 'Rechnung', 'Facture'),
(3, 'Infos per Email', 'Informationen per Email', 'Infos par e-mail'),
(4, 'Canceled by end of year', 'Gekündigt zum Jahresende', 'Sorti fin d''année');

--
-- Daten für Tabelle `###_Conferences`
--

INSERT INTO `###_Conferences` (`id`, `Description_UK`, `Description_DE`, `Description_FR`, `Startdate`, `Starttime`, `Enddate`, `Endtime`, `DescrLong_UK`, `DescrLong_DE`, `DescrLong_FR`, `PriceMember`, `PriceNoMember`, `Active_yn`) VALUES
(1, '', 'Konferenz 1', 'Conférence 1', '2004-01-04', '19:00:00', '2004-01-04', NULL, NULL, 'Dies ist die 1. Konferenz', 'C''est la première conference', 10.00, 15.00, 1);

--
-- Daten für Tabelle `###_Configuration`
--

INSERT INTO `###_Configuration` (`id`, `name`, `value`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(1, 'Firmname', 'Franzöischer Wirtschaftsclub in Bayern e.V.', 'Name of the club', 'Name des Clubs', 'Nom du club'),
(2, 'Address', 'MeineStrasse. 1', 'Address of the club', 'Clubanschrift', 'L''adresse du club'),
(3, 'City', 'München', 'Town of the club', 'Ort des Clubs', 'La ville du club'),
(4, 'Zipcode', '88888', 'Zipcode of the club', 'Postleitzahl des Clubs', 'Code postal du  club'),
(5, 'Country', 'Deutschland', 'Country of the club', 'Land des Clubs', 'Pays du club'),
(6, 'Telefon', '(089) 12345678', 'Telephone of the club', 'Telefonnummer des Clubs', 'Numéro de téléhone du club'),
(7, 'Fax', '(089) 12345679', 'fax number of the club', 'Faxnummer des Clubs', 'Numéro de fax du club'),
(8, 'Email', 'myemail@myprovider.de', 'E-Mail address of the club', 'E-Mail Adresse des Clubs', 'L'' adresse e-mail du club'),
(11, 'Emailname', 'Wirtschaftsclub e.V.', 'Full text email name of the club', 'E-Mail Name der Firma', 'Nom complet du club pour e-mails'),
(12, 'Mailhost', 'mail.myprovider.de', 'Address of the SMTP mail host', 'Adresse des SMTP Mailhosts', 'L'' adresse du server SMTP'),
(13, 'SMTPAuthorizing', '1', 'SMTP Authorizing. 0 or 1. If 1 SMTPUsername and SMPTPassword must be set.', 'SMTP Autorisierung. 0 oder 1. Wenn 1 müssen SMTPUsername und SMPTPassword gesetzt sein.', 'Autorisation SMTP. 0 ou 1. Si 1, SMTPUsername et SMTPPassword doivent être remplis'),
(14, 'SMTPUsername', 'myUserName', 'User id, if SMTPAutorizing is set to 1.', 'Benutzerkennung, wenn SMTPAuthorizing auf 1 gesetzt ist', 'l''identifiant du compte, si SMTPAuthorizing est mis à 1'),
(15, 'SMTPPassword', 'myPassword', 'Password, if SMTPAuthorizing is set to 1', 'SMTP Passwort, wenn SMTPAuthorizing auf 1 gesetzt ist', 'Mot de passe, si SMTPAuthorizing est mis à 1'),
(16, 'ReplyTo', 'myreply@otherprovider.de', 'Reply-To for emails', 'Antwortadresse für E-Mails', 'L''adresse de reponse pour e-mails'),
(17, 'CheckedCheckboxes', '1', 'If 1, all checkboxes will be selected by default', 'Wenn 1, sind alle Checkboxen in der Auswahlliste gesetzt, sonst (0) nicht.', ''),
(18, 'DefaultCols', 'Addresses_1.Firstname, Addresses_1.Lastname, Addresses_2.FirmName_ml', 'Columns of the table Members, which will be shown by default.', 'Spalten aus der Tabelle Members die standardmäßig ausgewählt werden', ''),
(19, 'EasySearch', 'Addresses_1.Firstname, Addresses_1.Lastname, Addresses_2.FirmName_ml', 'Columns of the table Members, which will be shown for easy search', 'Spalten aus der Tabelle Members die bei einfacher Suche angezeigt werden', ''),
(20, 'Countrycode', 'D', 'Countrycode of the Club', 'Landeskennung des Clubs', 'code du pays du club'),
(21, 'TabsShown', 'Overview;Memberinfo;Addresses;Payments;Fees;Emails;Conferences', 'Tabs shown on Member Navigator; Defines also Order of Tabs', 'Tabulatoren die beim Mitglied angezeigt werden; Legt auch die Reihenfolge fest', ''),
(22, 'Style', 'fresh', 'Style used to display', 'Stil der Anzeige', ''),
(23, 'EmailAsHTML', '1', 'If 1, Show HTML-Editor for emails and send Emails as HTML', 'Wenn 1 wird HTML-Editor angezeigt und Emails als HTML versandt.', 'Si 1, un editeur HTML est affich? et les courriels sont envoy?s comme HTML'),
(99, 'Clubdata_Version_DB', 'V2.03', 'Version of Clubdata Database, DO NOT EDIT!', 'Version der Clubdata Database, BITTE NICHT EDITIEREN !', ''),
(24, 'InvoiceNumber', '1004', 'The next invoice number inserted when a new invoice (membership, conference) is generated', 'Die n?chste Rechnungsnummer. Sie wird vergeben wenn eine neue Rechnung (Mitglied, Konferenz) erzeugt wird', 'The next invoice number inserted when a new invoice (membership, conference) is generated'),
(25, 'InvoiceNumberFormat', '%d', 'The format of the invoice number generated. Put %d where the invoice number shall be inserted', 'Das Format der Rechungsnummer. F?gen Sie %d an die Stelle ein, wo die laufende Rechnungsnummer erscheinen soll', 'The format of the invoice number generated. Put %d where the invoice number shall be inserted'),
(26, 'EmailSendType', 'BCC', 'The type of sending mass emails: BCC (one email for all users, listed as BCC; no personlisation possible), INDIV (each user gets an individual email, personalisation is possible)', 'Die Art des Versands von Massen-Emails: BCC (Eine Email an alle Empf?nger, aufgelistet im Feld BCC; keine Personalisierung m?glich), INDIV (jeder Empf?nger erh?lt eine individuelle Email, Personalisierung ist m?glich)', 'Le type d''expédition des emails de masse: BCC (un email pour tous les destinataires, répertoriés en BCC; personnalisation non possible), INDIV (un courriel par destinataire, personnalisation possible)'),
(27, 'Startpage', 'MemberMain/MemberMain_Main.php', 'First page shown in main window when Clubdata starts', 'Erste Seite des Hauptfensters, wenn Clubdata startet', 'Premi?re page dans la fen?tre principale quand Clubdata demarre'),
(28, 'maxRowsPerPage', '20', 'Maximal numbers of rows per page in list output', 'Maximale Anzahl von Zeilen in Listenausgaben', ''),
(29, 'ReplyToName', '', 'Full Name of the reply to mail address (See also parameter ReplyTo)', 'Vollständiger Name der ReplyTo-Email-Adresse (Siehe auch Parameter ReplyTo)', 'Nom complet de l''adresse ReplyTo (Voir aussi paramÃ¨tre ReplyTo)');

--
-- Daten für Tabelle `###_Country`
--

INSERT INTO `###_Country` (`id`, `Description_UK`, `Description_FR`, `Description_DE`, `DialCode`, `Show_yn`) VALUES
('D', 'Germany', 'Allemagne', 'Deutschland', '', 0),
('F', 'France', 'France', 'Frankreich', '', 0),
('SY', 'Syria', 'SYRIENNE, REPUBLIQUE ARABE', 'Syrien, Arabische Republik', '963', 0),
('CH', 'Switzerland', 'SUISSE', 'Schweiz (Confoederatio Helvetica)', '41', 1),
('SE', 'Sweden', 'SUEDE', 'Schweden', '46', 0),
('SZ', 'Swaziland', 'SWAZILAND', 'Swasiland', '268', 0),
('SJ', 'Svalbard & Jan Mayen Islands', 'SVALBARD ET ILE JAN MAYEN', 'Svalbard und Jan Mayen', '47', 0),
('SR', 'Suriname', 'SURINAME', 'Suriname', '597', 0),
('VC', 'St. Vincent & Grenadines', 'SAINT-VINCENT-ET-LES GRENADINES', 'St. Vincent und die Grenadinen', '+1 809', 0),
('SD', 'Sudan', 'SOUDAN', 'Sudan', '249', 0),
('PM', 'St. Pierre & Miquelon', 'SAINT-PIERRE-ET-MIQUELON', 'St. Pierre und Miquelon', '508', 0),
('LC', 'St. Lucia', 'SAINTE-LUCIE', 'St. Lucia', '+1 758', 0),
('KN', 'St. Kitts & Nevis', 'SAINT-KITTS-ET-NEVIS', 'St. Kitts und Nevis', '+1 869', 0),
('LK', 'Sri Lanka', 'SRI LANKA', 'Sri Lanka', '94', 0),
('ES', 'Spain', 'ESPAGNE', 'Spanien', '34', 0),
('GS', 'South Georgia & S.Sandwich Isls.', 'GEORGIE DU SUD ET LES ILES SANDWICH DU SUD', 'Südgeorgien und die Südlichen Sandwichinseln', '500', 0),
('SO', 'Somalia', 'SOMALIE', 'Somalia', '252', 0),
('ZA', 'South Africa', 'AFRIQUE DU SUD', 'Südafrika', '27', 0),
('SB', 'Solomon Islands', 'SALOMON, ILES', 'Salomonen', '677', 0),
('SK', 'Slovakia', 'SLOVAQUIE', 'Slowakei', '421', 0),
('SI', 'Slovenia', 'SLOVENIE', 'Slowenien', '386', 0),
('SG', 'Singapore', 'SINGAPOUR', 'Singapur', '65', 0),
('SL', 'Sierra Leone', 'SIERRA LEONE', 'Sierra Leone', '232', 0),
('SC', 'Seychelles', 'SEYCHELLES', 'Seychellen', '248', 0),
('SN', 'Senegal', 'SENEGAL', 'Senegal', '221', 0),
('CS', 'Serbia & Montenegro (former Yugoslavia)', 'SERBIE-ET-MONTENEGRO', 'Serbien und Montenegro', '381', 0),
('SA', 'Saudia Arabia', 'ARABIE SAOUDITE', 'Saudi-Arabien', '966', 0),
('ST', 'Sao Toméé', 'SAO TOME-ET-PRINCIPE', 'Sao Toméé und Prîncipe', '239', 0),
('SM', 'San Marino', 'SAINT-MARIN', 'San Marino', '378', 0),
('WS', 'Samoa (Western)', 'SAMOA', 'Samoa', '685', 0),
('RU', 'Russian Federation', 'RUSSIE, FEDERATION DE', 'Russische Föderation', '7', 0),
('RW', 'Rwanda', 'RWANDA', 'Ruanda', '250', 0),
('RO', 'Romania', 'ROUMANIE', 'Rumänien', '40', 0),
('RE', 'Reunion', 'REUNION', 'Réunion', '262', 0),
('QA', 'Qatar', 'QATAR', 'Katar', '974', 0),
('PL', 'Poland', 'POLOGNE', 'Polen', '48', 0),
('PT', 'Portugal', 'PORTUGAL', 'Portugal', '351', 0),
('PR', 'Puerto Rico', 'PORTO RICO', 'Puerto Rico', '+1 787 and', 0),
('PN', 'Pitcairn', 'PITCAIRN', 'Pitcairninseln', '', 0),
('PH', 'Philippines', 'PHILIPPINES', 'Philippinen', '63', 0),
('PE', 'Peru', 'PEROU', 'Peru', '51', 0),
('PY', 'Paraguay', 'PARAGUAY', 'Paraguay', '595', 0),
('PG', 'Papua New Guinea', 'PAPOUASIE-NOUVELLE-GUINEE', 'Papua-Neuguinea', '675', 0),
('PA', 'Panama', 'PANAMA', 'Panama', '507', 0),
('PS', 'Palestine(Gaza Strip & West Bank)', 'PALESTINIEN OCCUPE, TERRITOIRE', 'Palästinensische Autonomiegebiete', '970', 0),
('OM', 'Oman', 'OMAN', 'Oman', '968', 0),
('PK', 'Pakistan', 'PAKISTAN', 'Pakistan', '92', 0),
('PW', 'Palau', 'PALAOS', 'Palau', '680', 0),
('NO', 'Norway', 'NORVEGE', 'Norwegen', '47', 0),
('MP', 'Northern Mariana Islands', 'MARIANNES DU NORD, ILES', 'Nördliche Marianen', '+1 670', 0),
('NF', 'Norfolk', 'NORFOLK, ILE', 'Norfolkinsel', '6723', 0),
('NU', 'Niue', 'NIU', 'Niue', '683', 0),
('NG', 'Nigeria', 'NIGERIA', 'Nigeria', '234', 0),
('NE', 'Niger', 'NIGER', 'Niger', '227', 0),
('NI', 'Nicaragua', 'NICARAGUA', 'Nicaragua', '505', 0),
('NZ', 'New Zealand', 'NOUVELLE-ZELANDE', 'Neuseeland', '64', 0),
('NC', 'New Caledonia', 'NOUVELLE-CALEDONIE', 'Neukaledonien', '687', 0),
('AN', 'Netherlands Antilles', 'ANTILLES NEERLANDAISES', 'Niederländische Antillen', '599', 0),
('NL', 'Netherlands', 'PAYS-BAS', 'Niederlande', '31', 0),
('NP', 'Nepal', 'NEPAL', 'Nepal', '977', 0),
('NA', 'Namibia', 'NAMIBIE', 'Namibia', '264', 0),
('NR', 'Nauru', 'NAURU', 'Nauru', '674', 0),
('MZ', 'Mozambique', 'MOZAMBIQUE', 'Mosambik', '258', 0),
('MM', 'Myanmar (Burma)', 'MYANMAR', 'Myanmar (Burma)', '95', 0),
('MA', 'Morocco', 'MAROC', 'Marokko', '212', 0),
('MS', 'Montserrat', 'MONTSERRAT', 'Montserrat', '+1 664', 0),
('MN', 'Mongolia', 'MONGOLIE', 'Mongolei', '976', 0),
('MC', 'Monaco', 'MONACO', 'Monaco', '377', 0),
('MD', 'Moldova', 'MOLDOVA, REPUBLIQUE DE', 'Moldawien (Republik Moldau)', '373', 0),
('FM', 'Micronesia', 'MICRONESIE, ETATS F?D?R?S DE', 'Mikronesien', '691', 0),
('YT', 'Mayotte Island', 'MAYOTTE', 'Mayotte', '269', 0),
('MX', 'Mexico', 'MEXIQUE', 'Mexiko', '52', 0),
('MU', 'Mauritius', 'MAURICE', 'Mauritius', '230', 0),
('MR', 'Mauretania', 'MAURITANIE', 'Mauretanien', '222', 0),
('MQ', 'Martinique', 'MARTINIQUE', 'Martinique', '596', 0),
('MH', 'Marshall Islands', 'MARSHALL, ILES', 'Marshallinseln', '692', 0),
('US', 'United States of America', 'ETATS-UNIS', 'Vereinigte Staaten von Amerika', '1', 0),
('ML', 'Mali', 'MALI', 'Mali', '223', 0),
('MT', 'Malta', 'MALTE', 'Malta', '356', 0),
('MY', 'Malaysia', 'MALAISIE', 'Malaysia', '60', 0),
('MV', 'Maldives', 'MALDIVES', 'Malediven', '960', 0),
('MW', 'Malawi', 'MALAWI', 'Malawi', '265', 0),
('MG', 'Madagascar', 'MADAGASCAR', 'Madagaskar', '261', 0),
('MK', 'Macedonia', 'MAC?DOINE, L''EX-REPUBLIQUE YOUGOSLAVE DE', 'Mazedonien, ehem. jugoslawische Republik [2b]', '389', 0),
('MO', 'Macao', 'MACAO', 'Macao', '853', 0),
('LT', 'Lithuania', 'LITUANIE', 'Litauen', '370', 0),
('LU', 'Luxembourg', 'LUXEMBOURG', 'Luxemburg', '352', 0),
('LI', 'Liechtenstein', 'LIECHTENSTEIN', 'Liechtenstein', '423', 0),
('LY', 'Libya', 'LIBYENNE, JAMAHIRIYA ARABE', 'Libysch-Arabische Dschamahirija (Libyen)', '218', 0),
('LS', 'Lesotho', 'LESOTHO', 'Lesotho', '266', 0),
('LR', 'Liberia', 'LIBERIA', 'Liberia', '231', 0),
('LP', 'Lebanon', '#NV', 'Libanon', '961', 0),
('LV', 'Latvia', 'LETTONIE', 'Lettland', '371', 0),
('LA', 'Laos', 'LAO, REPUBLIQUE DEMOCRATIQUE POPULAIRE', 'Laos, Demokratische Volksrepublik', '856', 0),
('KW', 'Kuwait', 'KOWEIT', 'Kuwait', '965', 0),
('KG', 'Kyrgyzstan', 'KIRGHIZISTAN', 'Kirgisistan', '996', 0),
('KR', 'Korea South', 'COREE, REPUBLIQUE DE', 'Korea, Republik (Südkorea)', '82', 0),
('KP', 'Korea North', 'COREE, REPUBLIQUE POPULAIRE DEMOCRATIQUE DE', 'Korea, Demokratische Volksrepublik (Nordkorea)', '850', 0),
('KI', 'Kiribati', 'KIRIBATI', 'Kiribati', '686', 0),
('KE', 'Kenya', 'KENYA', 'Kenia', '254', 0),
('KZ', 'Kazakhstan', 'KAZAKHSTAN', 'Kasachstan', '7', 0),
('JO', 'Jordan', 'JORDANIE', 'Jordanien', '962', 0),
('JP', 'Japan', 'JAPON', 'Japan', '81', 0),
('JM', 'Jamaica', 'JAMAIQUE', 'Jamaika', '+1 876', 0),
('IE', 'Ireland', 'IRLANDE', 'Irland', '353', 0),
('IL', 'Israel', 'ISRAEL', 'Israel', '972', 0),
('IT', 'Italy', 'ITALIE', 'Italien', '+39 0', 0),
('IQ', 'Iraq', 'IRAQ', 'Irak', '964', 0),
('IR', 'Iran', 'IRAN, REPUBLIQUE ISLAMIQUE D''', 'Iran, Islamische Republik', '98', 0),
('IN', 'India', 'INDE', 'Indien', '91', 0),
('HU', 'Hungary', 'HONGRIE', 'Ungarn', '36', 0),
('IS', 'Iceland', 'ISLANDE', 'Island', '354', 0),
('HN', 'Honduras', 'HONDURAS', 'Honduras', '504', 0),
('HK', 'Honk Kong', 'HONG-KONG', 'Hongkong', '852', 0),
('HM', 'Heard & McDonald Islands', 'HEARD, ILE ET MCDONALD, ILES', 'Heard und McDonaldinseln', '672', 0),
('HT', 'Haiti', 'HAITI', 'Haiti', '509', 0),
('GY', 'Guyana', 'GUYANA', 'Guyana', '592', 0),
('GN', 'Guinea', 'GUINEE', 'Guinea', '224', 0),
('GW', 'Guinea-Bissau', 'GUINEE-BISSAU', 'Guinea-Bissau', '245', 0),
('GU', 'Guam', 'GUAM', 'Guam', '+1 671', 0),
('GT', 'Guatemala', 'GUATEMALA', 'Guatemala', '502', 0),
('GP', 'Guadeloupe incl.St. Bartholemy & St. Martin', 'GUADELOUPE', 'Guadeloupe', '590', 0),
('GD', 'Grenada', 'GRENADE', 'Grenada', '+1 473', 0),
('GL', 'Greenland', 'GROENLAND', 'Grönland', '299', 0),
('GH', 'Ghana', 'GHANA', 'Ghana', '233', 0),
('GR', 'Greece', 'GR?CE', 'Griechenland', '30', 0),
('DE', 'Germany incl. West Germany', 'ALLEMAGNE', 'Deutschland', '49', 1),
('GE', 'Georgia', 'GEORGIE', 'Georgien', '995', 0),
('GM', 'Gambia', 'GAMBIE', 'Gambia', '220', 0),
('GA', 'Gabon', 'GABON', 'Gabun', '241', 0),
('TF', 'French Southern Territories', 'TERRES AUSTRALES FRANCAISES', 'Französische Süd- und Antarktisgebiete', '', 0),
('PF', 'French Polynesia', 'POLYNESIE FRANCAISE', 'Französisch-Polynesien', '689', 0),
('GF', 'French Guiana', 'GUYANE FRANCAISE', 'Französisch-Guayana', '594', 0),
('FR', 'France', 'FRANCE', 'Frankreich', '33', 1),
('FI', 'Finland', 'FINLANDE', 'Finnland', '358', 0),
('FJ', 'Fiji', 'FIDJI', 'Fidschi', '679', 0),
('FO', 'Faroe Islands', 'FEROE, ILES', 'Färöer', '298', 0),
('FK', 'Falkland Islands', 'FALKLAND, ILES (MALVINAS)', 'Falklandinseln (Malwinen)', '500', 0),
('ET', 'Ethiopia', 'ETHIOPIE', 'Äthiopien', '251', 0),
('EE', 'Estonia', 'ESTONIE', 'Estland (Reval)', '372', 0),
('ER', 'Eritrea', 'ERYTHREE', 'Eritrea', '291', 0),
('GQ', 'Equatorial Guinea', 'GUINEE EQUATORIALE', 'Äquatorialguinea', '240', 0),
('SV', 'El Salvador', 'EL SALVADOR', 'El Salvador', '503', 0),
('EG', 'Egypt', 'EGYPTE', 'Ägypten', '20', 0),
('EC', 'Ecuador', 'EQUATEUR', 'Ecuador', '593', 0),
('TP', 'East Timor', '#NV', 'Osttimor', '670', 0),
('DO', 'Dominican Rep.', 'DOMINICAINE, REPUBLIQUE', 'Dominikanische Republik', '+1 809', 0),
('DM', 'Dominica', 'DOMINIQUE', 'Dominica', '+1 767', 0),
('DJ', 'Djibouti', 'DJIBOUTI', 'Dschibuti', '253', 0),
('DK', 'Denmark', 'DANEMARK', 'Dänemark', '45', 0),
('CU', 'Cuba', 'CUBA', 'Kuba', '53', 0),
('CY', 'Cyprus', 'CHYPRE', 'Zypern', '357', 0),
('CZ', 'Czech Rep.', 'TCHEQUE, REPUBLIQUE', 'Tschechische Republik', '420', 0),
('HR', 'Croatia', 'CROATIE', 'Kroatien (Hrvatska)', '385', 0),
('CI', 'Gibraltar', 'COTE D''IVOIRE', 'Côte d''Ivoire (Elfenbeinküste)', '350', 0),
('CR', 'Costa Rica', 'COSTA RICA', 'Costa Rica', '506', 0),
('CK', 'Cook Islands', 'COOK, ILES', 'Cookinseln', '682', 0),
('CD', 'Congo / Kinshasa (former Zaire)', 'CONGO, LA REPUBLIQUE DEMOCRATIQUE DU', 'Kongo, Demokratische Republik (ehem. Zaire)', '243', 0),
('CG', 'Congo / Brazzaville', 'CONGO', 'Republik Kongo', '242', 0),
('KM', 'Comores', 'COMORES', 'Komoren', '269', 0),
('CO', 'Colombia', 'COLOMBIE', 'Kolumbien', '57', 0),
('CC', 'Cocos-Keeling Islands', 'COCOS (KEELING), ILES', 'Kokosinseln (Keelinginseln)', '672', 0),
('CX', 'Christmas Islands', 'CHRISTMAS, ILE', 'Weihnachtsinsel', '672', 0),
('CN', 'China Peoples Rep.', 'CHINE', 'China, Volksrepublik', '86', 0),
('CL', 'Chile', 'CHILI', 'Chile', '56', 0),
('TD', 'Chad', 'TCHAD', 'Tschad', '235', 0),
('CF', 'Central African Rep.', 'CENTRAFRICAINE, REPUBLIQUE', 'Zentralafrikanische Republik', '236', 0),
('KY', 'Cayman Islands', 'CAIMANES, ILES', 'Kaimaninseln', '+1 345', 0),
('CV', 'Cape Verde Islands', 'CAP-VERT', 'Kap Verde', '238', 0),
('CA', 'Canada', 'CANADA', 'Kanada', '1', 0),
('CM', 'Cameroon', 'CAMEROUN', 'Kamerun', '237', 0),
('KH', 'Cambodia', 'CAMBODGE', 'Kambodscha', '855', 0),
('BI', 'Burundi', 'BURUNDI', 'Burundi', '257', 0),
('BF', 'Burkina Faso', 'BURKINA FASO', 'Burkina Faso', '226', 0),
('BG', 'Bulgaria', 'BULGARIE', 'Bulgarien', '359', 0),
('BN', 'Brunei', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', '673', 0),
('VG', 'British Virgin Islands', 'ILES VIERGES BRITANNIQUES', 'Britische Jungferninseln', '+1 284', 0),
('IO', 'British Indian Ocean Territory', 'OCEAN INDIEN, TERRITOIRE BRITANNIQUE DE L''', 'Britisches Territorium im Indischen Ozean', '246', 0),
('BR', 'Brazil', 'BRESIL', 'Brasilien', '55', 0),
('BV', 'Bouvet Island', 'BOUVET, ILE', 'Bouvetinsel', '', 0),
('BW', 'Botswana', 'BOTSWANA', 'Botswana', '267', 0),
('BA', 'Bosnia & Hercegovina', 'BOSNIE-HERZEGOVINE', 'Bosnien und Herzegowina', '387', 0),
('BO', 'Bolivia', 'BOLIVIE', 'Bolivien', '591', 0),
('BT', 'Bhutan', 'BHOUTAN', 'Bhutan', '975', 0),
('BM', 'Bermuda', 'BERMUDES', 'Bermuda', '+1 441', 0),
('BJ', 'Benin', 'BENIN', 'Benin', '229', 0),
('BZ', 'Belize', 'BELIZE', 'Belize', '501', 0),
('BE', 'Belgium', 'BELGIQUE', 'Belgien', '32', 0),
('BY', 'Belarus', 'BELARUS', 'Belarus (Weißrussland)', '375', 0),
('BB', 'Barbados', 'BARBADE', 'Barbados', '+1 246', 0),
('BD', 'Bangladesh', 'BANGLADESH', 'Bangladesch', '880', 0),
('BH', 'Bahrain', 'BAHREIN', 'Bahrain', '973', 0),
('BS', 'Bahamas', 'BAHAMAS', 'Bahamas', '+1 242', 0),
('AZ', 'Azerbaijan', 'AZERBAIDJAN', 'Aserbaidschan', '994', 0),
('AT', 'Austria', 'AUTRICHE', 'Österreich', '43', 0),
('AU', 'Australia', 'AUSTRALIE', 'Australien', '61', 0),
('SH', 'St. Helena', 'SAINTE-HELENE', 'St. Helena', '290', 0),
('AW', 'Aruba', 'ARUBA', 'Aruba', '297', 0),
('AM', 'Armenia', 'ARMENIE', 'Armenien', '374', 0),
('AR', 'Argentina', 'ARGENTINE', 'Argentinien', '54', 0),
('AG', 'Antigua & Barbuda', 'ANTIGUA-ET-BARBUDA', 'Antigua und Barbuda', '+1 268', 0),
('AQ', 'Antarctica', 'ANTARCTIQUE', 'Antarktis (Sonderstatus durch Antarktis-Vertrag)', '672', 0),
('AO', 'Angola', 'ANGOLA', 'Angola', '244', 0),
('AI', 'Anguilla', 'ANGUILLA', 'Anguilla', '+1 264', 0),
('AD', 'Andorra', 'ANDORRE', 'Andorra', '376', 0),
('AS', 'American Samoa', 'SAMOA AMERICAINES', 'Amerikanisch-Samoa', '684', 0),
('DZ', 'Algeria', 'ALGERIE', 'Algerien', '213', 0),
('GB', 'United Kingdom', 'ROYAUME-UNI', 'Vereinigtes Königreich von Großbritannien und No', '44', 0),
('AL', 'Albania', 'ALBANIE', 'Albanien', '355', 0),
('AF', 'Afghanistan', 'AFGHANISTAN', 'Afghanistan', '93', 0),
('ID', 'Indonesia', 'INDONESIE', 'Indonesien', '62', 0),
('TW', 'Taiwan', 'TAIWAN, PROVINCE DE CHINE', 'Taiwan (Formosa)', '886', 0),
('TJ', 'Tajikistan', 'TADJIKISTAN', 'Tadschikistan', '992', 0),
('TZ', 'Tanzania', 'TANZANIE, REPUBLIQUE-UNIE DE', 'Tansania, Vereinigte Republik', '255', 0),
('TH', 'Thailand', 'THAILANDE', 'Thailand', '66', 0),
('TG', 'Togo', 'TOGO', 'Togo', '228', 0),
('TK', 'Tokelau', 'TOKELAU', 'Tokelau', '690', 0),
('TO', 'Tonga', 'TONGA', 'Tonga', '676', 0),
('TT', 'Trinidad & Tobago', 'TRINITE-ET-TOBAGO', 'Trinidad und Tobago', '+1 868', 0),
('TN', 'Tunisia', 'TUNISIE', 'Tunesien', '216', 0),
('TR', 'Turkey', 'TURQUIE', 'Türkei', '90', 0),
('TM', 'Turkmenistan', 'TURKM?NISTAN', 'Turkmenistan', '993', 0),
('TC', 'Turks & Caicos Islands', 'TURKS ET CAIQUES, ILES', 'Turks- und Caicosinseln', '+1 649', 0),
('TV', 'Tuvalu', 'TUVALU', 'Tuvalu', '688', 0),
('UG', 'Uganda', 'OUGANDA', 'Uganda', '256', 0),
('UA', 'Ukraine', 'UKRAINE', 'Ukraine', '380', 0),
('AE', 'United Arab Emirates', 'EMIRATS ARABES UNIS', 'Vereinigte Arabische Emirate', '971', 0),
('UY', 'Uruguay', 'URUGUAY', 'Uruguay', '598', 0),
('UM', 'US Minor Outlying Isl.(Wake,Midway,Johnston etc.)', 'ILES MINEURES ELOIGNEES DES ETATS-UNIS', 'Amerikanisch-Ozeanien (US Minor Outlying Islands)', '+1 808', 0),
('VI', 'US Virgin Islands', 'ILES VIERGES DES ETATS-UNIS', 'Amerikanische Jungferninseln', '+1 340', 0),
('UZ', 'Uzbekistan', 'OUZBEKISTAN', 'Usbekistan', '998', 0),
('VU', 'Vanuatu', 'VANUATU', 'Vanuatu', '678', 0),
('VA', 'Vatican (Holy See)', 'SAINT-SIEGE (ETAT DE LA CITE DU VATICAN)', 'Vatikanstadt', '+39 06', 0),
('VE', 'Venezuela', 'VENEZUELA', 'Venezuela', '58', 0),
('VN', 'Vietnam', 'VIET NAM', 'Vietnam', '84', 0),
('WF', 'Wallis & Futuna', 'WALLIS ET FUTUNA', 'Wallis und Futuna', '681', 0),
('EH', 'Western Sahara', 'SAHARA OCCIDENTAL', 'Westsahara', '+212 8', 0),
('YE', 'Yemen', 'YEMEN', 'Jemen', '967', 0),
('ZM', 'Zambia', 'ZAMBIE', 'Sambia', '260', 0),
('ZW', 'Zimbabwe', 'ZIMBABWE', 'Simbabwe', '263', 0);


--
-- Daten für Tabelle `###_InfoGiveOut`
--

INSERT INTO `###_InfoGiveOut` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(-1, 'Not Mentioned', 'Keine Angabe', 'Sans mention'),
(0, 'No', 'Nein', 'Non'),
(1, 'Yes', 'Ja', 'Oui'),
(2, 'Only firm datas', 'Nur Firmendaten', 'Données de l''entreprise seulement'),
(3, 'Only privat datas', 'Nur Privatdaten', 'Données privées seulment');

--
-- Daten für Tabelle `###_InfoWWW`
--

INSERT INTO `###_InfoWWW` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(-1, 'Not Mentioned', 'Keine Angabe', 'Sans mention'),
(0, 'No', 'Nein', 'Non'),
(1, 'Yes', 'Ja', 'Oui'),
(2, 'Only firm datas', 'Nur Firmendaten', 'Données de l''entreprise seulement'),
(3, 'Only privat datas', 'Nur Privatdaten', 'Données privées seulment');

--
-- Daten für Tabelle `###_Language`
--

INSERT INTO `###_Language` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
('UK', 'English', 'Englisch', 'Anglais'),
('DE', 'German', 'Deutsch', 'Allemand'),
('FR', 'French', 'Französisch', 'Français');

--
-- Daten für Tabelle `###_Log`
--

INSERT INTO `###_Log` (`id`, `user`, `date`, `host`, `task`, `parameter`) VALUES
(350, 'admin', '2015-05-10 14:05:53', '127.0.0.1', 'LOGIN', ''),
(351, 'admin', '2015-05-10 14:06:57', '127.0.0.1', 'LOGIN', ''),
(352, 'admin', '2015-05-10 14:17:30', '127.0.0.1', 'LOGIN', ''),
(353, 'admin', '2015-05-10 14:22:11', '127.0.0.1', 'LOGIN', ''),
(354, 'admin', '2015-05-10 14:39:03', '127.0.0.1', 'LOGIN', ''),
(355, 'admin', '2015-05-10 14:42:09', '127.0.0.1', 'LOGIN', ''),
(356, 'admin', '2015-05-10 14:46:25', '127.0.0.1', 'LOGIN', ''),
(357, 'admin', '2015-05-10 14:56:51', '127.0.0.1', 'LOGIN', ''),
(358, 'admin', '2015-05-10 15:16:57', '127.0.0.1', 'LOGIN', ''),
(359, 'admin', '2015-05-10 15:17:28', '127.0.0.1', 'LOGIN', ''),
(360, 'admin', '2015-05-10 15:25:59', '127.0.0.1', 'LOGIN', ''),
(361, 'admin', '2015-05-10 15:26:09', '127.0.0.1', 'LOGIN', '');

--
-- Daten für Tabelle `###_Mailingtypes`
--

INSERT INTO `###_Mailingtypes` (`id`, `Description_UK`, `Description_DE`, `Description_FR`, `EmailOK`, `InvoiceAddr_yn`) VALUES
(1, 'Invitation', 'Einladung', 'Invitation', 1, 0),
(2, 'Information', 'Information', 'Information', 1, 0),
(3, 'Invoice', 'Rechnung', 'Facture', 0, 1);

--
-- Daten für Tabelle `###_Memberfees`
--

INSERT INTO `###_Memberfees` (`id`, `MemberID`, `InvoiceNumber`, `InvoiceDate`, `DueTo`, `Period`, `Amount`, `Remarks`, `DemandLevel`) VALUES
(1, 147, '-1', '2000-01-01', '2000-01-31', 2000, 306.77, NULL, NULL),
(2, 147, '-2', '2001-01-01', '2001-01-31', 2001, 0.00, NULL, NULL),
(3, 147, '-3', '2002-02-04', '2002-02-28', 2002, 75.00, '', 0),
(5, 147, '1001', '2009-01-01', '2009-01-31', 2009, 43.44, 'Testzahlung', 0),
(6, 147, '1002', '2009-01-01', '2009-01-31', 2009, 43.44, 'Testzahlung', 0),
(7, 148, '1003', '2009-01-02', '2009-01-31', 2009, 100.55, 'Testzahlung', 0);

--
-- Daten für Tabelle `###_Members`
--

INSERT INTO `###_Members` (`MemberID`, `Membertype_ref`, `Birthdate`, `Entrydate`, `Remarks_ml`, `MembershiptypeSince`, `InfoGiveOut_ref`, `InfoWWW_ref`, `Selection_ml`, `LoginPassword_pw`, `Language_ref`, `MainMemberID`) VALUES
(147, 8, '1935-01-01', '2001-01-02', '', '2002-01-07', 1, 2, '', '25f862a757c5dab24ca0fe30e2744ce9', 'DE', NULL),
(148, 3, '0000-00-00', '0000-00-00', '', '2002-01-07', 1, 2, '', '', 'UK', NULL),
(150, 8, NULL, '2008-02-10', '', NULL, 2, 2, '', '', 'FR', NULL),
(149, 0, NULL, NULL, NULL, NULL, -1, -1, NULL, '', 'DE', NULL);

--
-- Daten für Tabelle `###_Members_Attributes`
--

INSERT INTO `###_Members_Attributes` (`MemberID`, `Attributes_ref`) VALUES
(151, 2),
(151, 1),
(150, 3),
(150, 2),
(150, 1),
(147, 2);

--
-- Daten für Tabelle `###_Membertype`
--

INSERT INTO `###_Membertype` (`id`, `Kuerzel`, `Description_UK`, `Description_DE`, `Description_FR`, `Amount`, `SelectByDefault_yn`, `IsCancelled_yn`, `TypeDependencies`) VALUES
(1, 'ZM', 'Supplementary member', 'Zweitmitglied', 'membre supplementaire', 0.00, 1, 0, '3,4,5'),
(3, 'FM1', 'Small firm membership', 'Mitgliedschaft für kleine Unternehmen', 'Petit entreprise membre', 200.00, 1, 0, '0'),
(4, 'FM2', 'Medium firm membership', 'Mitgliedschaft für mittlere Unternehmen', 'medium entreprise membre', 400.00, 1, 0, '0'),
(5, 'FM3', 'Large firm membership', 'Mitgliedschaft für große Unternehmen', 'Grand entreprise membre', 400.00, 1, 0, '0'),
(6, 'JM1', 'Junior membership', 'Junior-Mitgliedschaft', 'Jeune membre', 50.00, 1, 0, '-1'),
(7, 'JM2', 'Junior student membership', 'Junior-Student', 'jeune membre étudiant', 50.00, 1, 0, '-1'),
(8, 'MG', 'Member', 'Mitglied', 'Membre', 75.00, 1, 0, '-1'),
(9, 'PM1', 'Potientiell member', 'Potentielle Mitgliedschaft', 'Membre potentiel', 0.00, 1, 0, '-1'),
(10, 'PM2', 'Membre potentiell firm', 'Potentielle Firmen-Mitgliedschaft', 'entreprise membre potentiel', 0.00, 1, 0, '-1'),
(11, 'EHM', 'membership of honour', 'Ehren-Mitgliedschaft', 'membre d''honeur', 0.00, 1, 0, '-1'),
(12, 'PRS', 'Press', 'Presse', 'Presse', 0.00, 1, 0, '-1'),
(13, 'CLU', 'Club', 'Club', 'Club', 0.00, 1, 0, '-1'),
(14, 'GEK', 'Canceled', 'Gekündigt', 'Sortie', 0.00, 0, 0, '-1'),
(15, 'SCL', 'Other clubs', 'Sonstige Clubs', 'Autre Clubs', 0.00, 0, 0, '-1');

--
-- Daten für Tabelle `###_Payments`
--

INSERT INTO `###_Payments` (`id`, `MemberID`, `InvoiceNumber`, `Period`, `Amount`, `Paydate`, `Paymode_ref`, `Checknumber`, `Remarks`, `Paytype_ref`) VALUES
(1, 147, '', 1999, 306.77, '1999-03-19', 2, '', '', 1),
(2, 147, '', 2001, 76.44, '2001-01-01', 1, '', 'Automatisch Eingefügt. Die Zahlung wurde nicht so getätigt !!', 1),
(3, 147, '', 2002, 75.00, '2002-01-01', 1, '', 'Einzug per DirectDebit', 1),
(4, 147, '1234', 2009, 250.00, '2009-01-01', 3, '1234-AAA', 'Testzahlung', 1),
(6, 147, '1234', 2001, 0.00, '2001-01-01', 3, '1234-AAA', 'Automatisch Eingefügt. Die Zahlung wurde nicht so getätigt !!', 1),
(7, 147, '1234', 2001, 0.00, '2001-01-01', 3, '1234-AAA', 'Automatisch Eingefügt. Die Zahlung wurde nicht so getätigt !!', 1);

--
-- Daten für Tabelle `###_Paymode`
--

INSERT INTO `###_Paymode` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(1, 'Unknown', 'Unbekannt', 'Inconnue'),
(2, 'Debit entry', 'DirectDebit', ''),
(3, 'Transfer', 'Überweisung', 'Virement');

--
-- Daten für Tabelle `###_Paytype`
--

INSERT INTO `###_Paytype` (`id`, `Description_UK`, `Description_DE`, `Description_FR`) VALUES
(1, 'Membership fee', 'Mitgliedsbeitrag', 'Cotisation'),
(2, 'Conference fee', 'Konferenzgebühr', 'Frais de conférence');

--
-- Daten für Tabelle `###_Salutation`
--

INSERT INTO `###_Salutation` (`id`, `Description`, `BriefkopfSalutation`, `BrieftextSalutation`) VALUES
(1, 'Herr', 'An Herrn', 'Sehr geehrter Herr'),
(2, 'Frau', 'An Frau', 'Sehr geehrte Frau'),
(3, 'Monsieur', 'A l''attention de Monsieur', 'Cher Monsieur'),
(4, 'Madame', 'A l''attention de Madame', 'Chère Madame'),
(5, 'Société', 'Société', 'Chère Madame, cher Monsieur'),
(6, '.', 'An den', 'Sehr geehrte Damen und Herren,'),
(7, ',', 'A l''attention du', 'Chère Madame, cher Monsieur');

--
-- Daten für Tabelle `###_Users`
--

INSERT INTO `###_Users` (`id`, `Login`, `Fullname`, `Password_pw`, `Language_ref`, `Admin_yn`, `UpdateAll_yn`, `InsertAll_yn`, `ViewAll_yn`, `DeleteAll_yn`, `InsertMember_yn`, `ViewMember_yn`, `DeleteMember_yn`, `UpdateFees_yn`, `InsertFees_yn`, `ViewFees_yn`, `DeleteFees_yn`, `UpdatePayments_yn`, `InsertPayments_yn`, `ViewPayments_yn`, `DeletePayments_yn`, `UpdateMemberinfo_yn`, `ViewMemberinfo_yn`, `UpdateFirm_yn`, `ViewFirm_yn`, `UpdatePrivat_yn`, `ViewPrivat_yn`, `ViewOverview_yn`, `ViewLists_yn`, `UpdateEmail_yn`, `CreateEmail_yn`, `ViewEmail_yn`, `DeleteEmail_yn`, `UpdateInfoletter_yn`, `CreateInfoletter_yn`, `ViewInfoletter_yn`, `DeleteInfoletter_yn`, `UpdateConferences_yn`, `InsertConferences_yn`, `ViewConferences_yn`, `DeleteConferences_yn`, `PersonalSettings_ro`) VALUES
(1, 'admin', 'Administrator', '21232f297a57a5a743894a0e4a801fc3', 'UK', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL),
(3, 'AllUser', 'User with All rights', '24467ab04fb6c73ef57404d4061d4b7e', 'UK', 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL),
(4, 'test', 'Testuser', '098f6bcd4621d373cade4e832627b4f6', 'UK', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
