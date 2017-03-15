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
// don't want any warnings turning up in the pdf code if the server is set to 'anal' mode.
//error_reporting(7);
//set_time_limit(1800);
//error_reporting(E_ALL);

    require_once("include/class.ezpdf.php");
    require_once("include/function.php");
    require_once("include/dblist.class.php");
    require_once("include/addresses.class.php");

//    if ( getClubUserInfo("MemberOnly") === true )
//    {
//        echo "No permission";
//        exit;
//    }


// define a clas extension to allow the use of a callback to get the table of contents, and to put the dots in the toc
/**
 * @package Clubdata
 */
class Creport extends Cezpdf {

    var $reportContents = array();

    function Creport($p,$o){
    $this->Cezpdf($p,$o);
    }

    function rf($info){
    // this callback records all of the table of contents entries, it also places a destination marker there
    // so that it can be linked too
    $tmp = $info['p'];
    $lvl = $tmp[0];
    $lbl = rawurldecode(substr($tmp,1));
    $num=$this->ezWhatPageNumber($this->ezGetCurrentPageNumber());
    $this->reportContents[] = array($lbl,$num,$lvl );
    $this->addDestination('toc'.(count($this->reportContents)-1),'FitH',$info['y']+$info['height']);
    }

    function dots($info){
    // draw a dotted line over to the right and put on a page number
    $tmp = $info['p'];
    $lvl = $tmp[0];
    $lbl = substr($tmp,1);
    $xpos = 520;

    switch($lvl){
        case '1':
        $size=16;
        $thick=1;
        break;
        case '2':
        $size=12;
        $thick=0.5;
        break;
    }

    $this->saveState();
    $this->setLineStyle($thick,'round','',array(0,10));
    $this->line($xpos,$info['y'],$info['x']+5,$info['y']);
    $this->restoreState();
    $this->addText($xpos+5,$info['y'],$size,$lbl);


    }


}

/**
 * @package Clubdata
 */
class createPDF {

    var $db;
    var $pdf;   // PDF Object

    // FullList = false : Only datas, which are allowed via InfosGiveOut_ref are printed
    // FullList = true : All datas are printed
    var $fullList;

    function insertTable1($res, $mgArr)
    {
        $left =  $this->pdf->ez["leftMargin"] + 5;
        $width = $this->pdf->ez['pageWidth']-$this->pdf->ez['leftMargin']-$this->pdf->ez['rightMargin'];

        $addr = array('Privat' => '', 'Firm' => '');

        $anrede = icT(getMyRefDescription($this->db, $mgArr["Addresses_1%Salutation_ref"], resolveFieldName($res, "Addresses_1%Salutation_ref")));
        $privatCountry = icT(getMyRefDescription($this->db, $mgArr["Addresses_1%Country_ref"], resolveFieldName($res, "Addresses_1%Country_ref")));
        $firmCountry = icT(getMyRefDescription($this->db, $mgArr["Addresses_2%Country_ref"], resolveFieldName($res, "Addresses_2%Country_ref")));

        if ($this->fullList || $mgArr["Members%InfoGiveOut_ref"] != 2 )
        {
            $addr["Privat"] = icT($anrede ."\n" .
                        ($mgArr["Addresses_1%Title"] <> "" ? $mgArr["Addresses_1%Title"] . " " : "") . $mgArr["Addresses_1%Firstname"] . " " . $mgArr["Addresses_1%Lastname"] . "\n" .
                        $mgArr["Addresses_1%Address"] . ( $mgArr["Addresses_1%Address"] != "" ? "\n" : "") .
                        $mgArr["Addresses_1%ZipCode"] . " " . $mgArr["Addresses_1%Town"] . ( $mgArr["Addresses_1%ZipCode"] != "" || $mgArr["Addresses_1%Town"] != "" ? "\n" : "") .
                        $privatCountry);
        }
        else
        {
            $addr["Privat"] = icT($anrede ."\n" .
                        ($mgArr["Addresses_1%Title"] <> "" ? $mgArr["Addresses_1%Title"] . " " : "") . $mgArr["Addresses_1%Firstname"] . " " . $mgArr["Addresses_1%Lastname"]);

        }

        if ($this->fullList || $mgArr["Members%InfoGiveOut_ref"] != 3 )
        {
            $addr["Firm"] = icT($mgArr["Addresses_2%FirmName_ml"] . ( $mgArr["Addresses_2%FirmName_ml"] != "" ? "\n" : "") .
                        $mgArr["Addresses_2%FirmDepartment"] . ( $mgArr["Addresses_2%FirmDepartment"] != "" ? "\n" : "") .
                        $mgArr["Addresses_2%Address"] . ( $mgArr["Addresses_2%Address"] != "" ? "\n" : "") .
                        $mgArr["Addresses_2%ZipCode"] . " " . $mgArr["Addresses_2%Town"] . ( $mgArr["Addresses_2%ZipCode"] != "" || $mgArr["Addresses_2%Town"] != "" ? "\n" : "") .
                        $firmCountry);
        }
        $tmpArr = array($addr);
        //echo "<PRE>"; var_dump($tmpArr); echo "</PRE>";
        $this->pdf->ezTable($tmpArr,"", "", array ( "showHeadings" => 0,
                                            "rowGap" => 0,
                                            "innerLineThickness" => 0,
                                            "outerLineThickness" => 0,
                                                "showLines" => 1,
                                                "lineCol" => array(1,1,1),
                                                "xPos" => $this->pdf->ez["leftMargin"] + 5,
                                                "xOrientation" => "right",
                                            "width" => $width,
                                            "fontSize" => 10,
                                            "cols" => array("Firm" => array("width" => $width / 2,
                                                                            'justification' => 'left'),
                                                            "Privat" => array("width" => $width / 2,
                                                                                'justification' => 'left')
                                                            ),
                                            )
                );

    }

    function insertTable2($res, $mgArr)
    {
        $left =  $this->pdf->ez["leftMargin"] + 5;
        $width = $this->pdf->ez['pageWidth']-$this->pdf->ez['leftMargin']-$this->pdf->ez['rightMargin'];

        $addr = array();
        $telP = $faxP = $emailP = $emailPlink = "";
        $telF = $faxF = $emailF = $emailFlink = $htmlF = $htmlFlink = "";

        if ($this->fullList || $mgArr["Members%InfoGiveOut_ref"] != 2 )
        {
            $telP = $mgArr["Addresses_1%Telephone"];
            $faxP = $mgArr["Addresses_1%Fax"];
            if ( $mgArr["Addresses_1%Email"] != "" )
            {
                $emailP = $mgArr["Addresses_1%Email"];
                $emailPlink = "mailto:$emailP";
            }
        }

        if ($this->fullList || $mgArr["Members%InfoGiveOut_ref"] != 3 )
        {
            $telF = $mgArr["Addresses_2%Telephone"];
            $faxF = $mgArr["Addresses_2%Fax"];
            if ( $mgArr["Addresses_2%Email"] != "" )
            {
                $emailF = $mgArr["Addresses_2%Email"];
                $emailFlink = "mailto:$emailF";
            }
            if ( $mgArr["Addresses_2%Html"] != "" )
            {
                $htmlF = $mgArr["Addresses_2%Html"];
                $htmlFlink = "http://$htmlF";
            }
        }

        $tmpArr = array();
        if ( !empty($telP) || !empty($faxP) || !empty($telF) || !empty($faxF) )
        {
            $tmpArr = array (
                        array ( "PTL" => (empty($telP) ? '' : icT(lang('Tel')) . ':'),
                                "PT"  => $telP,
                                "PFL" => (empty($faxP) ? '' : icT(lang('Fax')) . ':'),
                                "PF"  => $faxP,
                                "FTL" => (empty($telF) ? '' : icT(lang('Tel')) . ':'),
                                "FT"  => $telF,
                                "FFL" => (empty($faxF) ? '' : icT(lang('Fax')) . ':'),
                                "FF"  => $faxF
                            ),
                            );
        }
        //echo "<PRE>"; var_dump($tmpArr); echo "</PRE>";
        $this->pdf->ezTable($tmpArr,"", "", array ( "showHeadings" => 0,
                                                "rowGap" => 0,
                                                "colGap" => 0,
                                                "shaded" => 0,
                                                "showLines" => 0,
                                                "lineCol" => array(1,1,1),
                                                "xPos" => $this->pdf->ez["leftMargin"] + 5,
                                                "xOrientation" => "right",
                                                "width" => $width,
                                                "fontSize" => 10,
                                                "innerLineThickness" => 0,
                                                "outerLineThickness" => 0,
                                                "cols" => array("PTL" => array("width" => $width / 16,
                                                                                'justification' => 'left'),
                                                                "PT" => array("width" => $width / 16 * 3,
                                                                                'justification' => 'left',
                                                                                'link' => "PTLink"),
                                                                "PFL" => array("width" => $width / 16,
                                                                                'justification' => 'left'),
                                                                "PF" => array("width" => $width / 16 * 3,
                                                                                'justification' => 'left',
                                                                                'link' => "PFLink"),
                                                                "FTL" => array("width" => $width / 16,
                                                                                'justification' => 'left'),
                                                                "FT" => array("width" => $width / 16 * 3,
                                                                                'justification' => 'left',
                                                                                'link' => "FTLink"),
                                                                "FFL" => array("width" => $width / 16,
                                                                                'justification' => 'left'),
                                                                "FF" => array("width" => $width / 16 * 3,
                                                                                'justification' => 'left',
                                                                                'link' => "FFLink")
                                                                ),
                                                )
                );

        $tmpArr = array();
        if ( !empty($emailP) || !empty($emailF) )
        {
            $tmpArr = array(
                        array ( "PTL" => (empty($emailP) ? '' : icT(lang('email')) . ':'),
                                "PT"  => $emailP,
                                "PTLink" => $emailPlink,
                                "FTL" => (empty($emailF) ? '' : icT(lang('email')) . ':'),
                                "FT"  => $emailF,
                                "FTLink" => $emailFlink
                            )
                    );
        }

        if ( !empty($htmlF) )
        {
            $tmpArr[] = array ( "PTL" => "",
                                "PT"  => "",
                                "PTLink" => "",
                                "FTL" => (empty($htmlF) ? '' : icT(lang('Internet')) . ':'),
                                "FT"  => $htmlF,
                                "FTLink" => $htmlFlink
                            );
        }
        $this->pdf->ezTable($tmpArr,array("PTL" => "", "PT" =>"", "FTL" =>"", "FT" =>""), "",
                        array ( "showHeadings" => 0,
                                "rowGap" => 0,
                                "colGap" => 0,
                                "shaded" => 0,
                                "showLines" => 0,
                                "lineCol" => array(1,1,1),
                                "xPos" => $this->pdf->ez["leftMargin"] + 5,
                                "xOrientation" => "right",
                                "width" => $width,
                                "fontSize" => 10,
                                "innerLineThickness" => 0,
                                "outerLineThickness" => 0,
                                "cols" => array("PTL" => array("width" => $width / 16,
                                                            'justification' => 'left'),
                                                "PT" => array("width" => $width / 16 * 7,
                                                            'justification' => 'left',
                                                            'link' => "PTLink"),
                                                "FTL" => array("width" => $width / 16,
                                                            'justification' => 'left'),
                                                "FT" => array("width" => $width / 16 * 7,
                                                            'justification' => 'left',
                                                            'link' => "FTLink")
                                            )
                                )
                );
    }

    function title($titletxt, $options = array())
    {
        $titletxt = icT($titletxt);

        //echo "PDF: $this->pdf, $titletxt<BR>";
        $fs=(isset ($options["fontsize"]) ? $options["fontsize"] : 14);
        $bgcolor = (isset ($options["bgcolor"]) ? $options["bgcolor"] : array(0.76, 0.79, 0.85));
        $left = (isset ($options["left"]) ? $options["left"] : $this->pdf->ez["leftMargin"]);
        $width = (isset ($options["width"]) ? $options["width"] : $this->pdf->ez['pageWidth']-$this->pdf->ez['leftMargin']-$this->pdf->ez['rightMargin']);
        $just = (isset ($options["justification"]) ? $options["justification"] : "left");
        //echo "TEXT: $titletxt, WIDTH: $width<BR>";
        $tmp2 = "<b>$titletxt</b><C:rf:2".rawurlencode($titletxt).'>';
        $this->pdf->transaction('start');
        $ok=0;
        while (!$ok){
        $thisPageNum = $this->pdf->ezPageCount;
        $this->pdf->saveState();
        $this->pdf->setColor($bgcolor[0], $bgcolor[1], $bgcolor[2]); //#c4c9d8
        $this->pdf->filledRectangle($left + 1,$this->pdf->y-$this->pdf->getFontHeight($fs)+$this->pdf->getFontDecender($fs),
                            $width - 2,$this->pdf->getFontHeight($fs));
        $this->pdf->restoreState();
        $this->pdf->ezText($tmp2,$fs,array('justification'=>$just, 'left' => 5));
        if ($this->pdf->ezPageCount==$thisPageNum){
            $this->pdf->transaction('commit');
            $ok=1;
        } else {
            // then we have moved onto a new page, bad bad, as the background colour will be on the old one
            $this->pdf->transaction('rewind');
            $this->pdf->ezNewPage();
        }
        }
    }

    function _createPDFbySQL($sql)
    {
        global $APerr;

        $rs = $this->db->Execute($sql);
        if ( $rs === false )
        {
        debug_backtr('ADDRESSES');
         $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return;
        }


        $this->pdf = new Creport('a4','portrait');
        $mainFont = 'include/fonts/Times-Roman.afm';
        $this->pdf->selectFont($mainFont, array('encoding' => 'UTF-8'));

        $this->pdf -> ezSetMargins(30,40,40,10);

        // put a line top and bottom on all the pages
        $all = $this->pdf->openObject();
        $this->pdf->saveState();
        $this->pdf->setStrokeColor(0,0,0,1);
        $this->pdf->line($this->pdf->ez["leftMargin"],40,$this->pdf->ez["pageWidth"] - $this->pdf->ez['rightMargin'],40);
        $this->pdf->line($this->pdf->ez["leftMargin"],817,$this->pdf->ez["pageWidth"] - $this->pdf->ez['rightMargin'],817);
        $this->pdf->addText($this->pdf->ez["leftMargin"], 820, 10, icT(lang("Memberlist")));

        $width = $this->pdf->ez['pageWidth']-$this->pdf->ez['leftMargin']-$this->pdf->ez['rightMargin'];
        $text = getConfigEntry($this->db, "Firmname");
        //echo "TEXT: $text, WIDTH: $width, TEXTWIDTH: " . $this->pdf->getTextWidth(10,$text)/2 . "<BR>";
        $this->pdf->addText($this->pdf->ez["leftMargin"] + $width/2-$this->pdf->getTextWidth(10,$text)/2, 820, 10, icT($text));

        $text = strftime("%d.%m.%Y %H:%M:%S");
        //echo "WIDTH: $width, TEXTWIDTH: " . $this->pdf->getTextWidth(10,$text)/2 . "<BR>";
        $this->pdf->addText($this->pdf->ez["pageWidth"] - $this->pdf->ez["rightMargin"] - $this->pdf->getTextWidth(10,$text), 820, 10, icT($text));

        $this->pdf->restoreState();
        $this->pdf->closeObject();
        // note that object can be told to appear on just odd or even pages by changing 'all' to 'odd'
        // or 'even'.button
        $this->pdf->addObject($all,'all');

        //$this->pdf->ezSetDy(-100);

        $this->pdf->openHere('Fit');

        $this->pdf->ezStartPageNumbers($this->pdf->ez["pageWidth"] - $this->pdf->ez["rightMargin"],28,10, 'left',icT(lang("Page" )) . " {PAGENUM} " . icT(lang("of")) . " {TOTALPAGENUM}",1);
        $size=12;
        $height = $this->pdf->getFontHeight($size);
        $textOptions = array('justification'=>'full');
        $collecting=0;
        $code='';

        for( $row = 0; ($mgArr = $rs->FetchRow()); $row++)
        {
    //         echo "ROW: $row, MemberID: $mgArr[MemberID]<BR>"; flush();
    //         echo ("<PRE>");print_r($mgArr);echo("</PRE>");
            $width = $this->pdf->ez['pageWidth']-$this->pdf->ez['leftMargin']-$this->pdf->ez['rightMargin'];

            $this->pdf->transaction('start');
            $ok=0;
            while (!$ok)
            {
                $thisPageNum = $this->pdf->ezPageCount;

                $this->title((!empty($mgArr["Addresses_1%Title"]) ? $mgArr["Addresses_1%Title"] . " " : "") . $mgArr["Addresses_1%Lastname"] . ", " . $mgArr["Addresses_1%Firstname"]
                    . "({$mgArr['Members%InfoGiveOut_ref']})");
                $this->pdf->ezSetDy(0);

                $colTitles = array(array("Privat" => "<b>" . icT(lang("Privat")) . "</b>",
                                        "Firm" => "<b>" . icT(lang("Firm")) . "</b>"));

                $this->pdf->ezTable($colTitles,"", "",
                                    array ( "showHeadings" => 0,
                                            "shaded" => 2,
                                            "showLines" => 1,
                                            "lineCol" => array(1,1,1),
                                            "xPos" => $this->pdf->ez["leftMargin"] + 5,
                                            "xOrientation" => "right",
                                            "shadeCol2" => array(0.6,0.8,1),
                                            "width" => $width,
                                            "fontSize" => 12,
                                            "outerLineThickness" => 1,
                                            "cols" => array("Firm" => array('justification' => 'center'),
                                                            "Privat" => array('justification' => 'center')
                                                            ),
                                        )
                        );

                $this->insertTable1($rs, $mgArr);
                $this->pdf->ezSetDy(-5);
                $this->insertTable2($rs, $mgArr);
                $this->pdf->ezSetDy(-5);

                if ($this->pdf->ezPageCount==$thisPageNum){
                    $this->pdf->transaction('commit');
                    $ok=1;
                } else {
                    // then we have moved onto a new page, bad bad, as the background colour will be on the old one
                    $this->pdf->transaction('rewind');
                    $this->pdf->ezNewPage();
                }
            }

        }
        $this->pdf->ezStopPageNumbers(1,1);

        $d=0;
        //Ignore output made so far
        $tmpOutputContent = ob_get_contents();
        ob_end_clean();

        if (!empty($d)){
        $pdfcode = $pdf->ezOutput(1);
        $pdfcode = str_replace("\n","\n<br>",htmlspecialchars($pdfcode));
        echo trim($pdfcode);
        } else {
        $this->pdf->ezStream();
        }

        //Restart output buffering
        ob_start();
        echo $tmpOutputContent;
    }

    // I am in NZ, so will design my page for A4 paper.. but don't get me started on that.
    // (defaults to legal)
    // this code has been modified to use ezpdf.
    //
    // FullList = false : Only datas, which are allowed via InfosGiveOut_ref are printed
    // FullList = true : All datas are printed
    //
    function createPDF($db, &$formsgeneration, $fullList = false)
    {
        $this->db = $db;
        $this->fullList = $fullList;
        $this->formsgeneration = $formsgeneration;
    }

    function createPDFbyMlist($mlist)
    {
        global $APerr;

        $adrObj = new Addresses($this->db, $this->formsgeneration);

        $query = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON ``###_Members`.MemberID = `###_Members_Attributes`.MemberID',
                                                    '`###_Members`.MemberID');
        //          echo "<PRE>";print_r($addresstypes); echo "</PRE>";
        $addresstypes = $adrObj->getAddresstypes();
//         echo "QUERY: $query<PRE>";print_r($addresstypes); echo "</PRE>";
        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $adrID = $addresstypes[$i]['id'];
            $adrObj->setAddressType($adrID);
            $adrColNames[] = $adrObj->getFieldList("`Addresses_$adrID`", array("'' AS Mailingtypes"));
        }
        foreach (array('`###_Members`', '`###_Members_Attributes`') as $table)
        {
            $dbTableObj = new DBTable($this->db, $this->formsgeneration, $table, '1');
            $adrColNames[] = $dbTableObj->getFieldList($table, array('MemberID'));
        }

        /* Delete empty entries in $adrColNames */
        $adrColNames = array_filter($adrColNames);

        $tmpCols = $mlist->getConfig("cols");
        $tmpSort = $mlist->getConfig("sort");
        $mlist->setConfig("cols", join(',', $adrColNames));


        // Sort PDF by lastname
        $mlist->setConfig("sort", "`Addresses_1`.Lastname");
        //     print("<PRE>");print_r($mlist);print("</PRE>");
        $sql = $mlist->prepareSQL();
        echo "createPDF SQL: $sql<BR>";
        $mlist->setConfig("cols", $tmpCols);
        $mlist->setConfig("sort", $tmpSort);

        $this->_createPDFbySQL($sql);
    }

    function createPDFbyCond($cond)
    {
        global $APerr;

        $adrObj = new Addresses($this->db);

        $query = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID LEFT JOIN `###_Membertype` ON `###_Members`.Membertype_ref = `###_Membertype`.id',
                                                    'MemberID');
        //          echo "<PRE>";print_r($addresstypes); echo "</PRE>";
        $addresstypes = $adrObj->getAddresstypes();
        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $adrID = $addresstypes[$i]['id'];
            $adrObj->setAddressType($adrID);
            $adrColNames[] = $adrObj->getFieldList("`###_Addresses_$adrID`", array("'' AS Mailingtypes"));
        }
        foreach (array('`###_Members`', '`###_Members_Attributes`') as $table)
        {
            $dbTableObj = new DBTable($this->db, $formsgeneration, $table, '1');
            $adrColNames[] = $dbTableObj->getFieldList($table, array('MemberID'));
        }

        $sql = "SELECT DISTINCT " . join(',', $adrColNames) .
                " FROM $query WHERE $cond" .
                " ORDER BY `###_Addresses_1`.Lastname";

        echo "createPDF SQL: $sql<BR>";

        $this->_createPDFbySQL($sql);
    }

}
?>