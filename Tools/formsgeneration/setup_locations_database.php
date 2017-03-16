<?php
/*
 * setup_locations_database.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/setup_locations_database.php,v 1.2 2005/12/30 21:00:37 mlemos Exp $
 *
 */

	define("METABASE_PATH","../metabase");
	define("XML_PARSER_PATH","../xmlparser");
	require(METABASE_PATH."/metabase_parser.php");
	require(METABASE_PATH."/metabase_manager.php");
	require(METABASE_PATH."/metabase_database.php");
	require(METABASE_PATH."/metabase_interface.php");
	require(XML_PARSER_PATH."/xml_parser.php");

Function Output($message)
{
	global $html;

	if($html)
		echo nl2br(HtmlSpecialChars($message));
	else
		echo $message,"\n";
}

Function Dump($output)
{
	Output($output);
}

	$html=1;
	if($html)
		echo "<pre>";
	$input_file="locations.schema";
	$variables=array(
		"create"=>"1",
		"name"=>"locations"
	);
	$arguments=array(
		"Type"=>"mysql",
		"User"=>"mysqluser",
		"Password"=>"mysqlpassword",
		"Debug"=>"Output",
		"IncludePath"=>METABASE_PATH
	);
	$manager=new metabase_manager_class;
	$manager->debug="Output";
	$success=$manager->UpdateDatabase($input_file,$input_file.".before",$arguments,$variables);
	if($success)
	{
		echo $manager->DumpDatabase(array(
			"Output"=>"Dump",
			"EndOfLine"=>"\n"
		));
	}
	else
		Output("Error: ".$manager->error,"\n");
	if(count($manager->warnings)>0)
		Output("WARNING:\n",implode($manager->warnings,"!\n"));
	if($manager->database)
		Output(MetabaseDebugOutput($manager->database));
	if($html)
		echo "</pre>";
?>