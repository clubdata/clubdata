<?php
/**
 * Clubdata Member Modules
 *
 * Contains the class to list and manipulate conferences participation for a member
 * The views which are called by this class correspond to the tabs shown on the member page
 *
 * @package Members
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/dbtable.class.php');
require_once('include/attributes.class.php');
include("javascript/calendar.js.php");

/**
 * @package Members
 */
class vMemberinfo {
    var $memberID;
    var $db;
    var $fieldListUser = 'MemberID, Membertype_ref, MainMemberID, Entrydate,
                          Language_ref, InfoGiveOut_ref, InfoWWW_ref,
                          LoginPassword_pw,
                          \'\' as Attributes,
                          Selection_ml, Remarks_ml, Birthdate';

    var $memberROfields = array("Membertype_ref", "MainMemberID", "Entrydate", "Attributes", "Selection_ml", "Remarks_ml");

    var $tableObj;
    var $attribObj;

    var $smarty;
    var $formsgeneration;

    function vMemberinfo($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $this->tableObj = new DbTable($this->db, $this->formsgeneration, "`###_Members`", "MemberID = $this->memberID", $this->fieldListUser);

        $subTableAttributeSql = <<<_EOT_
            SELECT  id, `###_Members_Attributes`.Attributes_ref
            FROM  ``###_Attributes` LEFT JOIN `###_Members_Attributes`
                ON `###_Attributes`.id = `###_Members_Attributes`.Attributes_ref
                AND MemberID = $this->memberID
_EOT_;

            $this->attribObj = new Attributes($db, $this->formsgeneration, $memberID);
            
            $this->tableObj->addSubTable('Attributes', $this->attribObj);

            
            $this->tableObj->editRecord();
            
            if ( getClubUserInfo("MemberOnly") === true )
            {
              foreach ( $this->memberROfields as $memberROfield )
              {
                $this->formsgeneration->SetInputProperty($memberROfield, "Accessible", false);
              }

              // Attribute handling is difficult for members. As no (or later not all) attributes can be
              // set by a member, you have to merge setable and not setable attributes
              // So do the following:
              // 1. get Attributes as defined in the database ($attributesSet)
              // 2. get list of Attributes in Forms ($inputsArr), e.g. (Attributes1, Attributes2, ...)
              // 3. Set form properties for these attributes to ReadOnly (Accessible = false)
              // 4. Reset form checked state for these attributes to the database value
              // 5. Set POST parameter for set attributes
              //
              // This forces all attributes to be (re)set to the database values, regardless of the 
              // passed parameters.
              //
              // @TODO: Make defineable if an attribute can be changed by a member or not 
               
              
              // FD20110105: Unset SETAttributes, so all attributes will not be updated
//              unset($_POST['SETAttributes']);unset($_GET['SETAttributes']);

              $attributesSet = $this->attribObj->getCol('Attributes_ref'); 
              $inputsArr = array_filter($this->formsgeneration->GetInputs(''), create_function('$a', 'return !strncmp($a, "Attributes", strlen("Attributes"));'));

              foreach ( $inputsArr as $memberROfield )
              {
                $this->formsgeneration->SetInputProperty($memberROfield, "ReadOnlyMark", "X ");
                $this->formsgeneration->SetInputProperty($memberROfield, "ReadOnlyMarkUnchecked", "- ");
                $this->formsgeneration->SetInputProperty($memberROfield, "Accessible", false);

                $isSet = in_array(substr($memberROfield, strlen('Attributes')), $attributesSet);
                $this->formsgeneration->SetCheckedState($memberROfield, $isSet);
                if ( $isSet )
                {
                	$_POST['Attributes'][] = substr($memberROfield, strlen('Attributes'));
                }
              }

            }
    }

    function getSmartyTemplate()
    {
        return 'members/v_Memberinfo.inc.tpl';
    }

    function setSmartyValues()
    {

        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction($action)
    {
        switch ( $action )
        {
            case 'UPDATE':
                $this->tableObj->updateRecord($this->formsgeneration);
                break;
        }
    }
}
?>
