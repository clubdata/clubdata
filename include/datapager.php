<?php
/**
 * @package Clubdata
 * @subpackage General
 * @author  Sam Yapp www.samscripts.com
 * @license You are free to use modify and do whatever you like with this script.
 * @copyright 2002 Sam Yapp www.samscripts.com
 */

/************************************

	datapager - this class provides a simple method of querying databases and returning specific 'page' sizes of results

@ 2002 Sam Yapp www.samscripts.com

	You are free to use modify and do whatever you like with this script.


	Usage:

	the constructor:

	$datapager->datapager($dbconnection, $query, $pagesize, $querytousetocountrecords);

	where:
	 $dbconnection is a connection to a mysql database
		$query is the sql query (*without any limit x, y on the end)
		$pagesize is the number of records per page
		$querytousetocountrecords is optional
			it needs to be used when simply replaceing the fields in your queries SELECT bit with a COUNT(*) returns
			more than 1 row. (this is how datapager counts the number of records and the number of pages


		the main function - executes the query and returns a mysql result id or 0 if it fails

		$datapager->execute($pagesize, $pagenumber);

	where:
		$pagesize is the number or records per page
		$page is the page of results


	set up another query to execute

		$datapager->loadquery($query, $pagesize, $querytousetocountrecords); // called internally by creator function

	get a string containing a link to display the next/previous page

	$str = $datapager->nextpage($html, $althtml = "");
	$str = $datapager->prevpage($html, $althtml = "");

	where $html is something like <a href='thispage.php?page=%page%'>Next</a>
	and $althtml is what to use when this is already the last page - defaults to ""

	get a string containing links to all possible pages

		$datapager->pagelinks($linkhtml, $currenthtml = "%page%", $separator = " | ");

	where $linkhtml is something like <a href='thispage.php?page=%page%'>%page%</a>
	and $currenthtml is used for the current page, ie "%page%"
	and $separator is what to separate each pagenumber with


	the following variables are available once a query has been executed:

	$datapager->page	// the current page
	$datapager->pagesize	// number of records per page
	$datapager->recordcount	// total number of records available
	$datapager->pagecount	// total number of pages of records using this page size

************************************/
/**
 * @package Clubdata
 */
class datapager{

    var $mainquery;
    var $countquery;
    var $results;
    var $connection;
    var $pagesize;
    var $pagecount;
    var $page;
    var $recordcount;
    var $querydone;

    function datapager($conn = 0, $query  = "", $pagesize = 10, $countquery = ""){
            $this->connection = $conn;
            $this->querydone = false;
            $this->pagesize = $pagesize;
            $this->loadquery($query, $pagesize, $countquery);
    }

    function loadquery($query, $pagesize=0, $countquery=""){
        global $APerr;

        $this->querydone = false;
        if( $pagesize > 0 )$this->pagesize = $pagesize;
        $this->results = $this->pagecount = $this->page = $this->recordcount = 0;
        //FD20050305 trim whitespaces around SQL statement
        $query = trim($query);
        if( $query == "" || strtoupper(substr($query, 0, 6)) != "SELECT") return false;
        $this->mainquery = $query;
        if( $countquery == "" ){
            if( $this->connection ){
                $res = $this->connection->Execute($this->mainquery);
                if ($res === false )
                {
                    debug_backtr('LIST');
                    $APerr->setFatal(__FILE__,__LINE__,$this->connection->ErrorMsg(),"SQL: $this->mainquery");
                    return false;
                }

                $this->recordcount = $res->RecordCount();
                $this->pagecount = ceil($this->recordcount / $this->pagesize);
                $this->page = 1;
                $this->querydone = true;
                return true;
            }else{
                $this->countquery = $countquery;

    //          echo "MAINQUERY: $this->mainquery<BR>COUNTQUERY: $this->countquery<BR>";
                if( $this->connection ){

                    $res = $this->connection->Execute($this->countquery);
                    if ($res === false )
                    {
                        $APerr->setFatal(__FILE__,__LINE__,$this->connection->ErrorMsg(),"SQL: $this->countquery");
                        return false;
                    }
                    if ( $res && $res->RecordCount() != 1 ) {
                        $this->recordcount = $this->connection->GetOne($this->mainquery)  or die ($this->connection->ErrorMsg());
                    }
                    else
                    {
                        $this->recordcount = $res->fields[0];
                    }
                    $this->pagecount = ceil($this->recordcount / $this->pagesize);
                    $this->page = 1;
                    $this->querydone = true;
                    return true;
                }
            }
        }
        return false;
    }

    function nextpage($html, $althtml = ""){
            if( $this->page < $this->pagecount ){
                    return str_replace("%page%", $this->page+1, $html);
            }else{
                    return $althtml;
            }
    }

    function prevpage($html, $althtml = ""){
            if( $this->page > 1 ){
                    return str_replace("%page%", $this->page-1, $html);
            }else{
                    return $althtml;
            }
    }

    function pagelinks($linkhtml, $currenthtml = "%page%", $separator = " | "){
            $str = "";
            for( $i = 1; $i <= $this->pagecount; $i++){
                    if( $i != $this->page ){
                            $str .= str_replace("%page%", $i, $linkhtml);
                    }else{
                            $str .=  str_replace("%page%", $i, $currenthtml);
                    }
                    if( $i < $this->pagecount ) $str .= $separator;
            }
            return $str;
    }

    function execute( $page = 1, $pagesize = 10){
    global $APerr;

            if( $this->querydone == false )
    {
        echo "NO QUERYDONE<BR>\n";
        return 0;
    }
            if( $page < 1 ) $page = 1;
            if( $pagesize > $this->recordcount ) $pagesize = $this->recordcount;
            $this->pagesize = $pagesize;
            $this->page = $page;
    // FD20050306 if no records are found recordcount == pagesize == 0
    // insert if statement to avoid division by zero fault
    if ( $this->pagesize == 0 )
    {
        $this->pagecount = 1;
    }
    else
    {
                $this->pagecount = ceil($this->recordcount / $this->pagesize);
    }
            if( $this->page > $this->pagecount ) $this->page = $this->pagecount;

            // do query

    $this->results  = $this->connection->SelectLimit($this->mainquery, $this->pagesize, (($this->page-1) * $this->pagesize));
            if ( $this->results   === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->ErrorMsg(),"Pagesize: $this->pagesize, Start: " . (($this->page-1) * $this->pagesize), "SQL: $this->mainquery");
            }

            return $this->results;
    }

}


?>
