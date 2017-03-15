<?php
/**
 * Update class
 *
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * @package Clubdata
 */
class Update
{
    var $db;

    function Update($db)
    {
        $this->db = $db;
        if ( $this->updateDB() === true )
        {
            $this->setNewVersion();
            printf("<H2>" . lang("Please reload using the reload button of your browser when all update steps are finished") . "</H2><BR>");
        }
    }

    function setNewVersion()
    {
        $sql = "UPDATE `###_Configuration` SET `value` = '$this->newVersion' WHERE `id` = '99'";
        if ( $this->db->Execute($sql) === false)
        {
            print "<H2>Error</H2><BR>SQL: $sql<BR>" . $this->db->ErrorMsg() . "<BR>";
            print lang("Cannot set new version!");
            exit;
        }
        printf(lang("Set version number to %s") . "<P>", $this->newVersion);
    }

    function updateDB()
    {
        foreach ( $this->sqlArr as $sqlStatement)
        {
            //print "LEN: " . strlen($sqlStatement) . ">%$sqlStatement%<\n";

            $ignoreError = false;
            if ( substr($sqlStatement,0,1) == "?" )
            {
                // Ignore Errors
                $ignoreError = true;
                $sqlStatement = substr($sqlStatement, 1);
            }
            printf("<PRE>\t%s</PRE>\n", $sqlStatement);
            if ( $this->db->Execute($sqlStatement) === false)
            {
                if ( $ignoreError === false )
                {
                    print "<H2>Error</H2><BR>SQL: $sqlStatement<BR>" . $this->db->ErrorMsg() . "<BR>";
                    print lang("Please correct errors");
                    exit;
                }
                else
                {
                    print "<B>Warning: </B>SQL: $sqlStatement<BR>" . $this->db->ErrorMsg() . "<BR>";
                    print lang("The update script will continue anyway, as this error is not considered severe");
                }
            }
            print "<P></P>";
        }
        return true;
    }

}

?>