<?php
/*
 *
 * @(#) $Id: test_map_location_input.php,v 1.4 2006/12/20 06:21:17 mlemos Exp $
 *
 */

require("forms.php");
require("form_map_location.php");

/*
 * Request a Google Maps key for your domain here:
 * http://www.google.com/apis/maps/signup.html
 */
$key="ABQIAAAA7V8XnKRU9Ap_TYRAFk9tqRSjKdwWpQikcbMmFM-d61BWa0XlKhRfShNejYLw0m_FSORW6mu6r-IHlg";

$form=new form_class;
$form->NAME="maps_form";
$form->METHOD="POST";
$form->ACTION="";
$form->debug="trigger_error";

/*
 * This is just for testing purposes
 */
if(defined('GOOGLE_MAPS_KEY'))
$key=GOOGLE_MAPS_KEY;

$error=$form->AddInput(array(
		"TYPE"=>"custom",
		"CustomClass"=>"form_map_location_class",
		"ID"=>"map",
		"LABEL"=>"<u>P</u>oint your location on the map:",
		"ACCESSKEY"=>"P",
		"STYLE"=>"width: 600px; height: 400px",
/*
 "CLASS"=>"some CSS class",
 */
/*
 *  Go to the Google Maps API site to obtain a free API key for your
 *   domain: http://www.google.com/apis/maps/signup.html
 */
		"Key"=>$key,
		"Latitude"=>37.4419,
		"Longitude"=>-122.1419,
		"ZoomLevel"=>3,
/*
 "ZoomBounds"=>array(
 32,
 -123,
 39,
 -117
 ),
 */
		"ZoomMarkers"=>1,
		"BoundsOffset"=>1.0,
		"MapType"=>"Hybrid",
		"LatitudeLabel"=>"<b>Latitude:</b>",
		"LatitudeStyle"=>"background-color: #f1d9d9;",
/*
 "LatitudeClass"=>"some CSS class",
 */
		"LongitudeLabel"=>"<b>Longitude:</b>",
		"LongitudeStyle"=>"background-color: #f1d9d9",
/*
 "LatitudeClass"=>"some CSS class",
 */
		"Controls"=>array(
/*
 "SmallMap"=>array(),
 */
			"LargeMap"=>array(),
/*
 "SmallZoom"=>array(),
 */
			"Scale"=>array(),
			"MapType"=>array(),
			"OverviewMap"=>array(),
),
/*
 "Accessible"=>1,
 "HideMarker"=>0,
 */
		"Markers"=>array(
array(
				"Latitude"=>37.78156937014928,
				"Longitude"=>-122.42340087890625,
				"Information"=>"San Francisco",
				"Link"=>"http://www.ci.sf.ca.us/",
				"Target"=>"_blank",
				"Title"=>
					"Click here to go to the official San Francisco government site on a new window"
					),
					array(
				"Latitude"=>38.58252615935333,
				"Longitude"=>-121.48818969726562,
				"Information"=>"Sacramento"
				),
				array(
				"Latitude"=>34.05265942137599,
				"Longitude"=>-118.2403564453125,
				"Information"=>"Los Angeles",
				"Link"=>"http://www.ci.la.ca.us/",
				"Title"=>
					"Click here to go to the official Los Angeles government site on this window"
					),
					array(
				"Latitude"=>32.71855479966606,
				"Longitude"=>-117.16232299804688,
				"Information"=>"San Diego"
				),
				)
				));
				if(strlen($error))
				die("Error: ".$error);
				$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Submit",
		"NAME"=>"doit"
		));

		$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Refresh",
		"ID"=>"refresh",
		"SubForm"=>"refresh"
		));

		$form->LoadInputValues($form->WasSubmitted("doit"));
		$verify=array();
		if($form->WasSubmitted("doit"))
		{
			if(($error_message=$form->Validate($verify))=="")
			$doit=1;
			else
			{
				$doit=0;
				$error_message=HtmlEntities($error_message);
			}
		}
		else
		{
			$error_message="";
			$doit=0;
		}

		if(!$doit)
		{
			if(strlen($error_message))
			{
				Reset($verify);
				$focus=Key($verify);
			}
			else
			$focus='map';
			$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
		}

		$onload = HtmlSpecialChars($form->PageLoad());
		$onunload = HtmlSpecialChars($form->PageUnload());

		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the map location
plug-in input</title>
		<?php
		echo $form->PageHead();
		?>
</head>
<body onload="<?php echo $onload; ?>"
	onunload="<?php echo $onunload; ?>" bgcolor="#cccccc">
<center>
<h1>Test for Manuel Lemos' PHP form class using the map location plug-in
input</h1>
</center>
<hr />
		<?php
		if($doit)
		{
			$form->GetInputProperty("map", "Latitude", $latitude);
			$form->GetInputProperty("map", "Longitude", $longitude);
			echo '<center><h2>The location latitude is ', $latitude,
			' and the longitude is ', $longitude, '.</h2></center>';
		}
		else
		{
			$form->StartLayoutCapture();
			$title="Form map location plug-in test";
			$body_template="form_map_location_body.html.php";
			include("templates/form_frame.html.php");
			$form->EndLayoutCapture();

			$form->DisplayOutput();
		}
		?>
<hr />
</body>
</html>
