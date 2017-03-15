<?
    require_once('include/backup.class.php');
//     include("javascript/calendar.js.php");
    
    class vBackup {
        var $memberID;
        var $db;
        var $addresstype;

        var $adrObj;
        
        function vAddresses($db, $table, $key)
        {
            $this->db = $db;
            $this->memberID = $memberID;
            $this->addresstype = $addresstype;

            $this->adrObj = new Addresses($this->db, $this->addresstype, $this->memberID);

        }
        
        function displayView()
        {
            $this->adrObj->editRecord();
            return;
        }

        function doAction($action)
        {
            switch ($action)
            {
                case "UPDATE":
                    if ( $this->adrObj->recordExists() )
                    {
                        $this->adrObj->updateRecord();
                    }
            }
        }
}
?>