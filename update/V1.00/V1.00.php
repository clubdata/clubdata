<?php
/**
 * Clubdata Update Modules
 *
 * Contains the class to Update Clubdata from V1.001 to V2.00
 *
 * @package Update
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require('include/update.class.php');

/**
 * Updates Clubdata from V1.00 to V2.00
 * @package Update
 */
class Update_V1_00 extends Update
{

    var $sqlArr = array(
         "ALTER TABLE `Members` CHANGE `FirmName` `FirmName_ml` VARCHAR( 200 ) DEFAULT NULL",
         "UPDATE `Configuration` SET `value` = 'MemberID, Firstname, Lastname, FirmName_ml' WHERE `id` = '18'",
         "UPDATE `Configuration` SET `value` = 'Firstname, Lastname, FirmName_ml' WHERE `id` = '19'",
         "CREATE TABLE `Address` (  `id` INT NOT NULL AUTO_INCREMENT,
                                    `Adr_MemberID` SMALLINT NOT NULL ,
                                    `Addresstype_ref` SMALLINT NOT NULL ,
                                    `Salutation_ref` TINYINT,
                                    `Title` VARCHAR( 50 ) ,
                                    `Firstname` VARCHAR( 50 ) ,
                                    `Lastname` VARCHAR( 50 ) ,
                                    `FirmName_ml` VARCHAR( 200 ) ,
                                    `FirmDepartment` VARCHAR( 200 ) ,
                                    `Address` VARCHAR( 250 ) ,
                                    `ZipCode` VARCHAR( 10 ) ,
                                    `Town` VARCHAR( 100 ) ,
                                    `Country_ref` VARCHAR( 5 ) ,
                                    `Telephone` VARCHAR( 20 ) ,
                                    `Fax` VARCHAR( 20 ) ,
                                    `Email` VARCHAR( 50 ) ,
                                    `Html` VARCHAR( 255 ) ,
                                    `Logo_link` VARCHAR( 255 ) ,
                                    PRIMARY KEY ( `id` )
                                    ) COMMENT = 'Addresses of a member'",
        "ALTER TABLE `Address` ADD INDEX ( `Adr_MemberID` )",
        "ALTER TABLE `Address` RENAME `Addresses`",
        "CREATE TABLE `Addresstype` (   `id` TINYINT NOT NULL ,
                                        `Description_UK` VARCHAR( 100 ) NOT NULL ,
                                        `Description_DE` VARCHAR( 100 ) NOT NULL ,
                                        `Description_FR` VARCHAR( 100 ) NOT NULL ,
                                        `FieldList`      TEXT NOT NULL,
                                        `LetterFields_ml`   TEXT NOT NULL,
                                        PRIMARY KEY ( `id` )
                                        ) COMMENT = 'Type of addresses'",
        "INSERT    INTO `Addresstype` ( `id` , `Description_UK` , `Description_DE` , `Description_FR`, `FieldList`, `LetterFields_ml` )
            VALUES     (1, 'Privat', 'Privat', 'Private', 'Salutation_ref,Title,Firstname,Lastname,Address,ZipCode,Town,Country_ref,Telephone, Fax, Email, Html', 'Addresses_1.Salutation_ref,\r\nCONCAT_WS('' '', Addresses_1.Title, Addresses_1.Firstname, Addresses_1.Lastname) AS \$lAddressL2,\r\nAddresses_1.Address AS \$lAddressL3,\r\nCONCAT_WS('' '', Addresses_1.ZipCode , Addresses_1.Town) AS \$lAddressL4,\r\nAddresses_1.Country_ref AS \$lAddressL5'),
                    (2, 'Firm', 'Firma', 'Entreprise', 'FirmName_ml, FirmDepartment, Address, ZipCode, Town,Country_ref, Telephone, Fax, Email,Html, Logo_link', 'Addresses_2.FirmName_ml as \$lAddressL1,\r\nAddresses_2.FirmDepartment as \$lAddressL2,\r\nCONCAT_WS('' '', Addresses_1.Salutation_ref, Addresses_1.Title, \r\nAddresses_1.Firstname, Addresses_1.Lastname) AS \$lAddressL3,\r\nAddresses_2.Address AS \$lAddressL4,\r\nCONCAT_WS('' '', Addresses_2.ZipCode , Addresses_2.Town) AS \$lAddressL5,\r\nAddresses_2.Country_ref AS \$lAddressL6\r\n'),
                    (3, 'Invoice', 'Rechnung', 'Facture', 'Salutation_ref,Title,Firstname,Lastname,FirmName_ml, FirmDepartment, Address, ZipCode, Town,Country_ref, Telephone, Fax, Email,Html, Logo_link', 'Addresses_3.FirmName_ml as \$lAddressL1,\r\nAddresses_3.FirmDepartment as \$lAddressL2,\r\nCONCAT_WS('' '', Addresses_3.Salutation_ref, Addresses_3.Title, \r\nAddresses_3.Firstname, Addresses_3.Lastname) AS \$lAddressL3,\r\nAddresses_3.Address AS \$lAddressL4,\r\nCONCAT_WS('' '', Addresses_3.ZipCode , Addresses_3.Town) AS \$lAddressL5,\r\nAddresses_3.Country_ref AS \$lAddressL6\r\n')",
        "UPDATE `Configuration` SET `value` = 'Overview;Memberinfo;Addresses;Payments;Fees;Emails;Conferences' WHERE `id` = '21'",
        "INSERT INTO `Configuration` ( `id` , `name` , `value` , `Description_UK` , `Description_DE` , `Description_FR` )
            VALUES ('28', 'maxRowsPerPage', '20', 'Maximal numbers of rows per page in list output', 'Maximale Anzahl von Zeilen in Listenausgaben', '')",
        "ALTER TABLE `Memberfees` CHANGE `Rechnungsdatum` `InvoiceDate` DATE DEFAULT '0000-00-00' NOT NULL",
        "ALTER TABLE `Memberfees` CHANGE `FaelligAm` `DueTo` DATE DEFAULT '0000-00-00' NOT NULL ",
        "ALTER TABLE `Memberfees` CHANGE `Mitgliedsbeitrag` `Amount` DECIMAL( 9, 2 ) DEFAULT '0.00' NOT NULL ,
                                  CHANGE `Mahnstufe` `DemandLevel` TINYINT( 4 ) DEFAULT NULL ",
        "ALTER TABLE `Emails` CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT",
        "UPDATE Members SET InfosPerEmail_ref=IF(InfosPerEmail_ref=1,2,1) WHERE InfosPerEmail_ref IN(1,2)",
        "ALTER TABLE `InfosPerEmail` DROP INDEX `id`",
        "ALTER TABLE `InfosPerEmail` DROP PRIMARY KEY",
        "UPDATE InfosPerEmail SET id=IF(id=1,2,1) WHERE id IN(1,2)",
        "ALTER TABLE `InfosPerEmail` ADD PRIMARY KEY ( `id` )",
        "CREATE TABLE `Mailingtypes` (
                                    `id` tinyint( 4 ) NOT NULL auto_increment,
                                    `Description_UK` varchar( 100 ) NOT NULL default '',
                                    `Description_DE` varchar( 100 ) NOT NULL default '',
                                    `Description_FR` varchar( 100 ) NOT NULL default '',
                                    `EmailOK` TINYINT( 1 ) NOT NULL default 1,
                                    `InvoiceAddr_yn` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'Address to which invoices are sent',
                                    PRIMARY KEY ( `id` )
                                    ) TYPE = MYISAM COMMENT = 'Type of mailings sent to members'",
        "INSERT INTO `Mailingtypes` VALUES (1, 'Invitation', 'Einladung', 'Invitation', 1, 0)",
        "INSERT INTO `Mailingtypes` VALUES (2, 'Information', 'Information', 'Information', 1, 0)",
        "INSERT INTO `Mailingtypes` VALUES (3, 'Invoice', 'Rechnung', 'Facture', 0, 1)",

        "CREATE TABLE `Addresses_Mailingtypes` (
                                    `AddressID` smallint( 6 ) NOT NULL default '0',
                                    `Mailingtypes_ref` int( 11 ) NOT NULL default '0',
                                    KEY `AddressID` ( `AddressID` )
                                    ) TYPE = MYISAM COMMENT = 'Which attributes belong which member'",
        "ALTER TABLE `Membertype` CHANGE `Beitrag` `Amount` DECIMAL( 9, 2 ) DEFAULT '0.00' NOT NULL",
        "ALTER TABLE `Emails` ADD `EmailEmailtype` TINYINT NOT NULL",
        "ALTER TABLE `Members` CHANGE `Remarks` `Remarks_ml` VARCHAR( 250 )" ,
        "ALTER TABLE `Members` CHANGE `Selection` `Selection_ml` VARCHAR( 250 )",
        "INSERT INTO `Configuration` ( `id` , `name` , `value` , `Description_UK` , `Description_DE` , `Description_FR` )
                VALUES (29 , 'ReplyToName', '', 'Full Name of the reply to mail address (See also parameter ReplyTo)', 'Vollständiger Name der ReplyTo-Email-Adresse (Siehe auch Parameter ReplyTo)', 'Nom complet de l\\'adresse ReplyTo (Voir aussi paramètre ReplyTo)')",
        "ALTER TABLE `Members_Attributes` CHANGE `MA_MemberID` `MemberID` SMALLINT( 6 ) NOT NULL DEFAULT '0'",
        "?ALTER TABLE `Members` CHANGE `Password_pw` `LoginPassword_pw` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL",
        "ALTER TABLE `Addresses` ADD `mobile` VARCHAR( 20 ) NOT NULL AFTER `Email` , ADD `chat` VARCHAR( 20 ) NOT NULL AFTER `mobile`",
        "ALTER TABLE `Users` CHANGE `UpdateFirma_yn` `UpdateFirm_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `ViewFirma_yn` `ViewFirm_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `InsertMailen_yn` `CreateEmail_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `UpdateMailen_yn` `UpdateEmail_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `ViewMailen_yn` `ViewEmail_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `DeleteMailen_yn` `DeleteEmail_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `InsertInfobrief_yn` `CreateInfoletter_yn` TINYINT( 1 ) NOT NULL DEFAULT '0' ",
        "ALTER TABLE `Users` CHANGE `UpdateInfobrief_yn` `UpdateInfoletter_yn` TINYINT( 1 ) NOT NULL DEFAULT '0' ",
        "ALTER TABLE `Users` CHANGE `ViewInfobrief_yn` `ViewInfoletter_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Users` CHANGE `DeleteInfobrief_yn` `DeleteInfoletter_yn` TINYINT( 1 ) NOT NULL DEFAULT '0'",
        "ALTER TABLE `Addresstype` CHANGE `id` `id` TINYINT( 4 ) NOT NULL AUTO_INCREMENT",
/*        "ALTER TABLE `InfoGiveOut` CHANGE `id` `id` TINYINT( 4 ) NOT NULL AUTO_INCREMENT",
        "ALTER TABLE `InfoWWW` CHANGE `id` `id` TINYINT( 4 ) NOT NULL AUTO_INCREMENT",*/
         );

    var $newVersion = "V2.00";

    function Update_V1_00($db)
    {
        Update::Update($db);
    }

    function updateDB()
    {
        if ( Update::updateDB($this->sqlArr) === true &&
             $this->convertToUTF8() === true &&
             $this->updateAttributes() === true &&
             $this->updateAddresses() === true &&
             $this->updateMailtypeScheme() === true &&
             $this->updateConfiguration() === true &&
             $this->renameTablesWithPrefix() === true )
        {
            return true;
        }
        return false;
    }

    function convertToUTF8()
    {
        echo "******** Converting to UTF8 ****************<BR>";

        $table = "Membertype";
        $sql = "UPDATE $table SET
                    Description_DE=CONVERT(Description_DE USING UTF8),
                    Description_FR=CONVERT(Description_FR USING UTF8)
               ";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }

        $sql = "ALTER TABLE $table CHARACTER SET UTF8";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }

        // Changing character set to UTF8
        $sql = "ALTER DATABASE " . DB_NAME . " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }

        echo "******** Finished converting to UTF8 ****************<BR>";
        return true;
    }

    function updateAddresses()
    {
        global $APerr;

        $sql = "SELECT * FROM Members";
        $rs = $this->db->Execute($sql);
        if ( empty($rs) )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        else
        {
            while ( $ra = $rs->FetchRow() )
            {
                $sql = "INSERT INTO `Addresses`
                        (`Addresstype_ref` , `Salutation_ref` , `Title` , `Firstname` ,
                         `Lastname` , `Address` , `ZipCode` , `Town` , `Country_ref` ,
                         `Telephone` , `Fax` , `Email` , `Adr_MemberID` )
                        VALUES (
                        1, " .
                        $this->db->qstr($ra['Salutation_ref']) . ", " .
                        $this->db->qstr($ra['Title']) . ", " .
                        $this->db->qstr($ra['Firstname']) . "," .
                        $this->db->qstr($ra['Lastname']) . ", " .
                        $this->db->qstr($ra['PrivatAddress']) . "," .
                        $this->db->qstr($ra['PrivatZipCode']) . "," .
                        $this->db->qstr($ra['PrivatTown']) . "," .
                        $this->db->qstr($ra['PrivatCountry_xref']) . "," .
                        $this->db->qstr($ra['PrivatTelephone']) . "," .
                        $this->db->qstr($ra['PrivatFax']) . "," .
                        $this->db->qstr($ra['PrivatEmail']) . ", " .
                        $this->db->qstr($ra['MemberID']) . ")";

                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }

                $sql = "INSERT INTO `Addresses`
                        (`Addresstype_ref` , `FirmName_ml` , `FirmDepartment`,
                         `Address` , `ZipCode` , `Town` , `Country_ref` ,
                         `Telephone` , `Fax` , `Email` , `Html`, `Logo_link`,
                         `Adr_MemberID` )
                        VALUES (
                        2, " . $this->db->qstr($ra['FirmName_ml']) . "," . $this->db->qstr($ra['FirmDepartment']) . "," .
                        $this->db->qstr($ra['FirmAddress']) . "," . $this->db->qstr($ra['FirmZipCode']) . "," .
                        $this->db->qstr($ra['FirmTown']) . "," . $this->db->qstr($ra['FirmCountry_xref']) . "," . $this->db->qstr($ra['FirmTelephone']) . "," .
                        $this->db->qstr($ra['FirmFax']) . "," . $this->db->qstr($ra['FirmEmail']) . "," .
                        $this->db->qstr($ra['FirmHTML']) . "," . $this->db->qstr($ra['FirmLogo_link']) . ", " . $this->db->qstr($ra['MemberID']) . ")";

                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }

            }

            // Update default columns to use address notation
            $sql = "SELECT value FROM Configuration WHERE id = 18";
            $value = $this->db->GetOne($sql);
            if ( $value === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            $value = str_replace("Firstname", "Addresses_1.Firstname",
                        str_replace("Lastname" , "Addresses_1.Lastname",
                            str_replace("FirmName_ml", "Addresses_2.FirmName_ml", $value)));

            $sql = "UPDATE Configuration SET value = '$value' WHERE id = 18";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }

            // Update easysearch to use address notation
            $sql = "SELECT value FROM Configuration WHERE id = 19";
            $value = $this->db->GetOne($sql);
            if ( $value === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            $value = str_replace("Firstname", "Addresses_1.Firstname",
                        str_replace("Lastname" , "Addresses_1.Lastname",
                            str_replace("FirmName_ml", "Addresses_2.FirmName_ml", $value)));

            $sql = "UPDATE Configuration SET value = '$value' WHERE id = 19";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }

            // Update TabsShown to use addresses
            $sql = "SELECT value FROM Configuration WHERE id = 21";
            $value = $this->db->GetOne($sql);
            if ( $value === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            $value = str_replace("Privat", "",
                        str_replace("Firm" , "",
                            str_replace("Memberinfo", "Memberinfo;Addresses", $value)));

            $sql = "UPDATE Configuration SET value = '$value' WHERE id = 21";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            $sql = "ALTER TABLE `Members`
                        DROP `Firstname`,
                        DROP `Lastname`,
                        DROP `Title`,
                        DROP `PrivatAddress`,
                        DROP `PrivatZipCode`,
                        DROP `PrivatTown`,
                        DROP `PrivatCountry_xref`,
                        DROP `PrivatTelephone`,
                        DROP `PrivatFax`,
                        DROP `FirmName_ml`,
                        DROP `FirmDepartment`,
                        DROP `FirmAddress`,
                        DROP `FirmZipCode`,
                        DROP `FirmTown`,
                        DROP `FirmCountry_xref`,
                        DROP `FirmTelephone`,
                        DROP `FirmFax`,
                        DROP `FirmEmail`,
                        DROP `FirmLogo_link`,
                        DROP `FirmHTML`,
                        DROP `PrivatEmail`,
                        DROP `Salutation_ref`";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }

            return true;
        }
    }

    // If not present, add "Direct Debit", "Invoice" and "Letter to privat address"
    // to the attributes table.
    // To do so, move all exististing entries by three places and add the new
    // ones in front of them, so they are shown first in Clubdata
    function updateAttributes()
    {
        global $APerr;

        $sql = "SELECT * FROM Attributes WHERE Description_UK = 'Direct Debit'";

        echo "[updateAttributes]: getRow = " . count($this->db->getRow($sql)) . "<BR>\n";
        $tmpRow = $this->db->getRow($sql);
        if ( $tmpRow !== false && count($tmpRow) > 0 )
        {
            echo "Direct debit already in Attributes table, no changes will be done !!";
            return true;
        }
        echo "Updating Table 'Attributes'<BR>";
        $sql = "SELECT * FROM Attributes ORDER BY id DESC";
        $rs = $this->db->Execute($sql);
        if ( empty($rs) )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        else
        {
            while ( $rowArrAssoc = $rs->FetchRow() )
            {
                echo "<P>$rowArrAssoc[Description_UK]/$rowArrAssoc[Description_DE]/$rowArrAssoc[Description_FR]<BR>";
                echo "Setting ID from $rowArrAssoc[id] to " . ($rowArrAssoc['id'] + 9) . "<BR>";
                $sql = "UPDATE Attributes SET id = id + 9 WHERE id = $rowArrAssoc[id]";
                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }
                else
                {
                    echo "<div style='padding-left: 5em;'>Changing References<BR>";
                    $sql = "UPDATE Members_Attributes SET Attributes_ref = " .
                            ($rowArrAssoc['id'] + 9) .
                            " WHERE Attributes_ref  = $rowArrAssoc[id]";
                    echo "SQL: $sql<BR>";
                    if ( $this->db->Execute($sql) === false )
                    {
                        printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                        return false;
                    }
                    echo "</div>";
                }
            }
            echo "Inserting 'Direct Debit' to Attributes table<BR>";
            $sql = "INSERT INTO `Attributes` ( `id` , `Description_UK` , `Description_DE` , `Description_FR` )
                    VALUES (1, 'Direct Debit', 'Lastschrift', 'Prélèvement automatique')";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            echo "Update Attribute 'Direct Debit' from old field<BR>";
            $sql = "SELECT MemberID from Members WHERE DirectDebit_yn <> 0";
            $rs1 = $this->db->Execute($sql);
            while ( $ra = $rs1->FetchRow() )
            {
                $sql = "INSERT INTO `Members_Attributes` ( `MemberID` , `Attributes_ref` ) VALUES ($ra[MemberID], 1)";
                echo "SQL: $sql<BR>";
                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }
            }

            echo "Inserting 'Invoice' to Attributes table<BR>";
            $sql = "INSERT INTO `Attributes` ( `id` , `Description_UK` , `Description_DE` , `Description_FR` )
                    VALUES ('2', 'Invoice', 'Rechnung', 'Facture')";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            echo "Update Attribute 'Invoice' from old field<BR>";
            $sql = "SELECT MemberID from Members WHERE Invoice_yn <> 0";
            $rs1 = $this->db->Execute($sql);
            while ( $ra = $rs1->FetchRow() )
            {
                $sql = "INSERT INTO `Members_Attributes` ( `MemberID` , `Attributes_ref` ) VALUES ($ra[MemberID], 2)";
                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }
            }

            echo "Inserting 'Letter to privat address' to Attributes table<BR>";
            $sql = "INSERT INTO `Attributes` ( `id` , `Description_UK` , `Description_DE` , `Description_FR` )
                    VALUES ('3', 'Letter to privat address', 'Briefe an Privatanschrift', 'Envoyer lettres à l\'adresse privée�')";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            echo "Update Attribute 'Letter to privat address' from old field<BR>";
            $sql = "SELECT MemberID from Members WHERE LetterPrivat_yn <> 0";
            $rs1 = $this->db->Execute($sql);
            while ( $ra = $rs1->FetchRow() )
            {
                $sql = "INSERT INTO `Members_Attributes` ( `MemberID` , `Attributes_ref` ) VALUES ($ra[MemberID], 3)";
                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }
            }

            echo "Inserting 'Canceled by end of year' to Attributes table<BR>";
            $sql = "INSERT INTO `Attributes` ( `id` , `Description_UK` , `Description_DE` , `Description_FR` )
                    VALUES ('4', 'Canceled by end of year', 'Gekündigt zum Jahresende', 'Sorti fin d\'année')";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            echo "Update Attribute 'Canceled by end of year' from old field<BR>";
            $sql = "SELECT MemberID from Members WHERE CancelByEndOfYear_yn <> 0";
            $rs1 = $this->db->Execute($sql);
            while ( $ra = $rs1->FetchRow() )
            {
                $sql = "INSERT INTO `Members_Attributes` ( `MemberID` , `Attributes_ref` ) VALUES ($ra[MemberID], 4)";
                if ( $this->db->Execute($sql) === false )
                {
                    printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    return false;
                }
            }

            echo "Deleting old Memberfields<BR>";
            $sql = "ALTER TABLE `Members` DROP `LetterPrivat_yn`,DROP `DirectDebit_yn`, DROP `Invoice_yn`, DROP `CancelByEndOfYear_yn`";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }

        }
        return true;
    }


    function updateMailtypeScheme()
    {
        echo "Creating new Mailtype scheme<BR>";

        $sql = "SELECT Addresses.id, Members.MemberID
                    FROM Addresses, Members
                    WHERE Addresses.Adr_MemberID = Members.MemberID
                    AND Addresses.Addresstype_ref = Members.InfosPerEmail_ref";
        $rs1 = $this->db->Execute($sql);
        while ( $ra = $rs1->FetchRow() )
        {
            $sql = "INSERT INTO `Addresses_Mailingtypes` ( `AddressID` , `Mailingtypes_ref` )
                                            VALUES ($ra[id], 1),($ra[id], 2)";
            echo "$sql<BR>";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
            $sql = "UPDATE Members SET InfosPerEmail_ref = 1 WHERE MemberID = $ra[MemberID]";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
        }

        $sql = "ALTER TABLE `Members` CHANGE `InfosPerEmail_ref` `InfosPerEmail_yn` TINYINT( 1 ) DEFAULT '0' NOT NULL ";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        $sql = "DROP TABLE `InfosPerEmail`";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        $sql = "SELECT Addresses.id, Addresstype_ref, Members.MemberID, InfosPerEmail_yn, Members_Attributes.Attributes_ref
                FROM Addresses, Members
                LEFT JOIN Members_Attributes ON Members.MemberID = Members_Attributes.MemberID
                AND Members_Attributes.Attributes_ref =3
                WHERE Addresses.Adr_MemberID = Members.MemberID
                AND Addresstype_ref =
                IF (
                ISNULL(
                Attributes_ref
                ), 2, 1
                )";
        $rs1 = $this->db->Execute($sql);
        while ( $ra = $rs1->FetchRow() )
        {
            if ( $ra['InfosPerEmail_yn'] == 1 )
            {
                $sql = "INSERT INTO `Addresses_Mailingtypes` ( `AddressID` , `Mailingtypes_ref` ) VALUES ($ra[id], 3)";
            }
            else
            {
                $sql = "INSERT INTO `Addresses_Mailingtypes` ( `AddressID` , `Mailingtypes_ref` ) VALUES ($ra[id], 1),($ra[id], 2),($ra[id], 3)";
            }
            echo "$sql<BR>";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
        }
    /* Attribute type "Private letter" not needed any longer.
     * The selection is done by address type !!
     */
/*
        $sql = "DELETE FROM Members_Attributes WHERE Attributes_ref = 3";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        $sql = "DELETE FROM Attributes WHERE id = 3";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
*/
        echo "Renaming Attribute 'Letter to privat address' to 'Infos per Email'<BR>";
        $sql = "UPDATE `Attributes` SET `Description_UK` = 'Infos per Email',
                `Description_DE` = 'Informationen per Email',
                `Description_FR` = 'Infos par e-mail' WHERE `id` = 3 LIMIT 1";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        echo "Update Attribute 'Infos per Email' from old field<BR>";
        $sql = "DELETE FROM Members_Attributes WHERE Attributes_ref = 3";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        $sql = "SELECT MemberID from Members WHERE InfosPerEmail_yn <> 0";
        $rs1 = $this->db->Execute($sql);
        while ( $ra = $rs1->FetchRow() )
        {
            $sql = "INSERT INTO `Members_Attributes` ( `MemberID` , `Attributes_ref` ) VALUES ($ra[MemberID], 3)";
            if ( $this->db->Execute($sql) === false )
            {
                printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                return false;
            }
        }
        $sql = "ALTER TABLE `Members` DROP `InfosPerEmail_yn`";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
/**/
    return true;
    }

    function updateConfiguration()
    {
        echo "Updating Configuration<BR>";

        echo "Renaming default style from normal to newStyle'<BR>";
        $sql = "UPDATE `Configuration` SET `value` = 'newstyle' WHERE `Configuration`.`id` =22 LIMIT 1";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }

        echo "Delete MemberID from DefaultCols if it exists<BR>";
        $sql = "SELECT value FROM `Configuration` WHERE `Configuration`.`id` =18";
        $tmpVal = $this->db->GetOne($sql);
        echo "Original value of parameter DefaultCols: $tmpVal<BR>";

        $tmpArr = preg_split("/[\s,]+/", $tmpVal);
        $tmpArr = array_filter($tmpArr, create_function('$a', 'return strcmp(strtolower($a),"memberid");'));
        $tmpVal = join(', ', $tmpArr);
        echo "Filtered value of parameter DefaultCols: $tmpVal<BR>",

        $sql = "UPDATE `Configuration` SET `value` = '$tmpVal' WHERE `Configuration`.`id` =18" ;
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
        return true;
    }


    function renameTablesWithPrefix()
    {
      $tableArr = array(
                  'Addresses',
                  'Addresses_Mailingtypes',
                  'Addresstype',
                  'Attributes',
                  'Conferences',
                  'Configuration',
                  'Country',
                  'Emails',
                  'Help',
                  'InfoGiveOut',
                  'InfoWWW',
                  'Language',
                  'Log',
                  'Mailingtypes',
                  'Memberfees',
                  'Members',
                  'Members_Attributes',
                  'Members_Conferences',
                  'Members_Emails',
                  'Membertype',
                  'Newsletter',
                  'Payments',
                  'Paymode',
                  'Paytype',
                  'Salutation',
                  'Users');

      echo "<BR><BR>Renaming tables, adding prefix<BR>";

      foreach ( $tableArr as $table )
      {
        $sql = "RENAME TABLE `$table` TO `" . DB_TABLEPREFIX . "$table`";

        echo "   Rename $table to " . DB_TABLEPREFIX . "$table<BR>";
        if ( $this->db->Execute($sql) === false )
        {
            printf("%s,%s,%s,%s\n",__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return false;
        }
      }

    return true;
    }

}
?>