<?php
/**
 * @package Clubdata
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */


/**
 *
 */
require_once 'include/table.class.php';

/**
 * @package Clubdata
 */
class PersonalSettings extends Table {

  /**
   *
   * holds the database object
   * @var object $db
   */
  var $db;

  /**
   *
   * holds authentication infos of current user
   * @var object $auth
   */
  var $auth;

  /**
   *
   * Default settings for current user
   * @var assoc $personalSettings
   */
  var $defaultPersonalSettings = array (
      'SHOW_TOOLTIP' => 1,    // Show tooltips
      'TEST' => 'TESTVAL',    // Test overwriting, not in use
    );

  /**
   *
   * Personal settings for current user
   * @var assoc $personalSettings
   */
  var $personalSettings;


  function PersonalSettings($db, &$formsgeneration, &$auth)
  {

    Table::Table($formsgeneration);

    $this->db = $db;
    $this->auth = &$auth;

    debug_r('TABLE', $this->auth->{'user'}, '[PersonalSettings::PersonalSettings] this->auth->{user}');

    $this->personalSettings = (array)json_decode($this->auth->{'user'}['PersonalSettings_ro'], true) + $this->defaultPersonalSettings;

    debug_r('TABLE', $this->personalSettings, '[PersonalSettings::PersonalSettings] this->personalSettings');
  }

    function showRecordDetails($edit = false, $title='')
    {
      global $APerr;

      $personalSettings = $this->auth->{'user'}['PERSONAL_SETTINGS'];

      $this->formsgeneration->ReadOnly = ( $edit === false ? 1 : 0 );

      // List of personal settings
      $errTxt[] .= $this->formsgeneration->AddInput(array(
                          "TYPE"=>"select",
                          "LABEL"=>helpAndText('Settings','Persoal','Show Tooltips'),
                          "MULTIPLE"=>0,
                          "NAME"=>"SHOW_TOOLTIP",
                          "ID"=>"SHOW_TOOLTIP",
                          "VALUE"=>(isset($personalSettings->{'SHOW_TOOLTIP'}) ? $personalSettings->{'SHOW_TOOLTIP'} : 1),
                          "SIZE"=>1,
                          "OPTIONS"=>array('1' => lang('Yes'),
                                              '0' => lang('No'))
                          ));

        // END List of personal settings

        // Load values from formular, if submitted
        $errTxt[] .= $this->formsgeneration->LoadInputValues($this->formsgeneration->WasSubmitted("doit"));

        // process errors
        debug_r('DBTABLE', $errTxt, "[DBTABLE, showRecordDetails] errTxt");
        if ( count($errTxt = array_filter($errTxt)) )
        {
            debug_backtr("DBTABLE");
            $str = join("<BR>",$errTxt);
            $APerr->setFatal(__FILE__,__LINE__,$str);
        }
        debug_r("DBTABLE", $this->formsgeneration, "showRecordDetails ($value))");

        // return forms object
        return $this->formsgeneration;
    }

    /**
     * updateRecord() updates the personal settings
     *
     * Personal settings are stored in the field PersonalSettings_ro in the table ###_Users as a JSON coded array
     * Formsgeneration will verify the formular values, but it will not be used to set the database field,
     * as the use of getGlobVar is much easier here
     *
     * @see include/Table::updateRecord()
     */
    function updateRecord($uploadID = '')
    {
        global $APerr;

        $error = false;

        /*
        * Therefore we need to validate the submitted form values.
        */
        if(($error_message=$this->formsgeneration->Validate($verify))=="")
        {

            /*
            * It's valid, set the $doit flag variable to 1 to tell the form is ready to
            * processed.
            */
            $doit=1;

        }
        else
        {

            /*
            * It's invalid, set the $doit flag to 0 and encode the returned error message
            * to escape any HTML special characters.
            */
            $doit=0;
            $error_message=nl2br(HtmlSpecialChars($error_message));
            debug("DBTABLE", "[DBTABLE, updateRecord] Formserror: $error_message");
            $APerr->setFatal(__FILE__,__LINE__,$error_message);
            return false;
        }

        foreach ($this->personalSettings as $key => $value)
        {
          $newPersonalSettings[$key] = getGlobVar($key);
        }

//              print("<PRE>"); print_r($auth);print("</PRE>");
//              print($auth->{'user'}['id'] . "<PRE>"); print_r($newPersonalSettings);print("</PRE>: JSON = " . json_encode($newPersonalSettings));
//                      debug_r("TABLE", $this->formsgeneration, "showRecordDetails");
        $sql = "UPDATE `###_Users` SET PersonalSettings_ro = '" . json_encode($newPersonalSettings) . "' WHERE ID = " .  $this->auth->{'user'}['id'];

//              print("$sql<BR>");
        if ( $this->db->Execute($sql) === false )
        {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        }
//              print("<PRE>"); print_r($auth);print("</PRE>");
        $this->auth->refreshInfo();
//              print("<PRE>"); print_r($auth);print("</PRE>");

    }

    /**
     *
     * getPersonalSettings returns the personal settings as an associative array
     *
     * @return assoc personal settings
     */
    function getPersonalSettings()
    {
      return $this->personalSettings;

    }
}

?>