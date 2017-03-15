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
require_once('include/addresses.class.php');
require_once('include/attributes.class.php');
require_once('include/memberinfo.class.php');

/**
 * @package Members
 */
class vOverview {
    var $memberID;
    var $db;

    /**
      * @var array
      */
    var $tableObj;

    var $smarty;
    var $formsgeneration;

    function vOverview($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        // Addresstype not used
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $this->tableObj['Memberinfo'] = new Memberinfo($this->db, $this->formsgeneration, $this->memberID);
//        $this->tableObj['Privat'] = new Addresses($this->db, $this->formsgeneration, 1, $this->memberID);
//        $this->tableObj['Firm'] = new Addresses($this->db, $this->formsgeneration, 2, $this->memberID);
        $this->tableObj['Attributes'] = new Attributes($this->db, $this->formsgeneration, $this->memberID);
        
        //FD20150616: Add all addresstypes to overview. For compatibility, Privat address is named 'LeftSide', Firm address is named 'RightSide'
        $sql = "SELECT * FROM `###_Addresstype` ORDER BY id";
        $rs = $this->db->execute($sql);
        if ( $rs === false )
        {
        	$APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        }
        else
        {
        	while ( $anArr = $rs->fetchRow() )
        	{
        		$txt = getDescriptionTxt($anArr);
        		$this->tableObj['Addresses'][$txt] = array( 'id' => $anArr['id'],
        																								'fieldArr' => preg_split("/\s*,\s*/", $anArr['FieldList']),
        																								'address' => new Addresses($this->db, $this->formsgeneration, $anArr['id'], $this->memberID));
        	}
        }
        
    }

    function getSmartyTemplate()
    {
        return 'members/v_Overview.inc.tpl';
    }

    function setSmartyValues()
    {
        if ( getUserType(VIEW, "Overview") )
        {
/*        
          if ( getUserType(VIEW, "Privat") )
          {
              $this->smarty->assign('LeftSide', $this->tableObj['Addresses']['Privat']->getRecord());
          }

          if ( getUserType(VIEW, "Firm") )
          {
              $this->smarty->assign('RightSide', $this->tableObj['Addresses']['Firm']->getRecord());
          }
*/
          //FD20150616: Add all addresstypes to overview. For compatibility, Privat address is named 'LeftSide', Firm address is named 'RightSide'
          $addressArr = array();
      		foreach ($this->tableObj['Addresses'] as $addressName => $addressObj) {
      			if ( $addressObj['id'] == 1 &&  getUserType(VIEW, "Privat") ) { // Privat 
              $this->smarty->assign('LeftSide', $addressObj['address']->getRecord());
      			} elseif ( $addressObj['id'] == 2 &&  getUserType(VIEW, "Firm") ) { // Firm
      				$this->smarty->assign('RightSide', $addressObj['address']->getRecord());
      			}

      			$addressArr[$addressName] = array('id' => $addressObj['id'], 
        																			'fieldArr' => $addressObj['fieldArr'],
      																				'address' => $addressObj['address']->getRecord());
      		}      
      		$this->smarty->assign('Addresses', $addressArr);
      		
      		debug('MAIN', "[v_Overview|setSmartyValues]: getUserType=" . getUserType(VIEW, "Memberinfo"));
          if ( getUserType(VIEW, "Memberinfo") )
          {
              debug_r('MAIN',  $this->tableObj['Memberinfo']->getRecord() , "MEMBERINFO:");

              $this->smarty->assign('Memberinfo', $this->tableObj['Memberinfo']->getRecord());
              $this->smarty->assign('Attributes', $this->tableObj['Attributes']->getRecord());
          }
        }
    }
}
?>
