<?php
/*
 *
 * @(#) $Id: form_map_location.php,v 1.18 2006/07/25 06:52:53 mlemos Exp $
 *
 */

class form_map_location_class extends form_custom_class
{
	var $key = '';
	var $style = '';
	var $class = '';
	var $format = "{map}\n<br />\n<div>{latitudelabel} {latitude} {longitudelabel} {longitude}</div>\n{zoom}\n{maptype}";
	var $no_coordinates_format = "{map}\n{latitude}\n{longitude}\n{zoom}\n{maptype}";
	var $validation_error_message = 'It was not specified a valid location.';
	var $controls=array();
	var $markers=array();

	var $server_validate=0;
	var $map = '';
	var $latitude = '';
	var $longitude = '';
	var $zoom = '';
	var $map_type = '';
	var $marker = '';
	var $map_script='';

	var $accessible = 1;
	var $hide_marker = 0;
	var $coordinates = 1;
	var $latitude_value = 0.0;
	var $longitude_value = 0.0;
	var $zoom_value = 0;
	var $zoom_bounds = array();
	var $map_type_value = 'Normal';

	var $map_types = array(
		"Normal"=>"G_NORMAL_MAP",
		"Satellite"=>"G_SATELLITE_MAP",
		"Hybrid"=>"G_HYBRID_MAP"
		);

		Function SetLocation(&$form)
		{
			$eol = $form->end_of_line;
			for($markers = '', $marker = 0; $marker<count($this->markers); $marker++)
			{
				$markers.='var '.$this->marker.$marker.'=new GMarker(new GLatLng('.strval($this->markers[$marker]['Latitude']).', '.strval($this->markers[$marker]['Longitude']).')'.(IsSet($this->markers[$marker]['Title']) ? ', {title: '.$form->EncodeJavascriptString($this->markers[$marker]['Title']).'}' : '').');'.$eol.
				$this->map.'.addOverlay('.$this->marker.$marker.');'.$eol;
				if(IsSet($this->markers[$marker]['Information']))
				{
					$markers.='GEvent.addListener('.$this->marker.$marker.', "mouseover", function() { '.$this->marker.$marker.'.openInfoWindowHtml('.$form->EncodeJavascriptString($this->markers[$marker]['Information']).');});'.$eol.
					'GEvent.addListener('.$this->marker.$marker.', "mouseout", function() { '.$this->map.'.closeInfoWindow();});'.$eol;
				}
				if(IsSet($this->markers[$marker]['Link']))
				$markers.='GEvent.addListener('.$this->marker.$marker.', "click", function() {'.(IsSet($this->markers[$marker]['Target']) ? ' if(!(w=window.open('.$form->EncodeJavascriptString($this->markers[$marker]['Link']).','.$form->EncodeJavascriptString($this->markers[$marker]['Target']).')) || !w.top)' : '').' window.location='.$form->EncodeJavascriptString($this->markers[$marker]['Link']).';});'.$eol;
			}
			$this->valid_marks['data']['map'] =
			'<div'.(strlen($this->style) ? ' style="'.HtmlSpecialChars($this->style).'"' : '').(strlen($this->style) ? ' class="'.HtmlSpecialChars($this->class).'"' : '').' id="'.$this->map.'"></div>'.$eol;
			$this->map_script='<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.HtmlSpecialChars($this->key).'" type="text/javascript"></script>'.$eol.
			'<script type="text/javascript">'.$eol.
			'// <![CDATA['.$eol.
			'var '.$this->map.';'.$eol.
			($this->hide_marker ? '' : 'var '.$this->marker.';'.$eol).
			'function '.$this->map.'load()'.$eol.'{'.$eol.'if(GBrowserIsCompatible())'.$eol.'{'.$eol.
			$this->map.' = new GMap2(document.getElementById("'.$this->map.'"));'.$eol.
			(count($this->controls) ?
			(IsSet($this->controls['SmallMap']) ? $this->map.'.addControl(new GSmallMapControl());'.$eol : '').
			(IsSet($this->controls['LargeMap']) ? $this->map.'.addControl(new GLargeMapControl());'.$eol : '').
			(IsSet($this->controls['SmallZoom']) ? $this->map.'.addControl(new GSmallZoomControl());'.$eol : '').
			(IsSet($this->controls['Scale']) ? $this->map.'.addControl(new GScaleControl());'.$eol : '').
			(IsSet($this->controls['MapType']) ? $this->map.'.addControl(new GMapTypeControl());'.$eol : '').
			(IsSet($this->controls['OverviewMap']) ? $this->map.'.addControl(new GOverviewMapControl());'.$eol : '')
			: '').
			$this->map.'.setCenter(new GLatLng('.$this->latitude_value.', '.$this->longitude_value.'), '.(count($this->zoom_bounds)==4 ? $this->map.'.getBoundsZoomLevel(new GLatLngBounds(new GLatLng('.$this->zoom_bounds[0].','.$this->zoom_bounds[1].'),new GLatLng('.$this->zoom_bounds[2].','.$this->zoom_bounds[3].')))' : $this->zoom_value).');'.$eol.
			$this->map.'.setMapType('.$this->map_types[$this->map_type_value].');'.$eol.
			'GEvent.addListener('.$this->map.', "click", function(marker, point) { if(marker!=null) return false; c=new GLatLng(point.lat(), point.lng()); '.$this->map.'.panTo(c);'.($this->accessible ? ' l=document.getElementById("'.$this->latitude.'"); l.value=c.lat(); l=document.getElementById("'.$this->longitude.'"); l.value=c.lng();'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint(c);' : '') : '').' });'.$eol.
			'GEvent.addListener('.$this->map.', "zoomend", function(oldlevel, newlevel) { l=document.getElementById("'.$this->zoom.'"); l.value=newlevel; });'.$eol.
			'GEvent.addListener('.$this->map.', "maptypechanged", function() { l=document.getElementById("'.$this->map_type.'"); t='.$this->map.'.getCurrentMapType(); if(t==G_NORMAL_MAP) l.value="Normal"; else { if(t==G_SATELLITE_MAP) l.value="Satellite"; else { if(t==G_HYBRID_MAP) l.value = "Hybrid"; } } });'.$eol.
			($this->hide_marker ? '' : $this->marker.'=new GMarker('.$this->map.'.getCenter());'.$eol.
			$this->map.'.addOverlay('.$this->marker.');'.$eol).
			$markers.
			'}'.$eol.'}'.$eol.'// ]]></script>'.$eol;
			return($this->DefaultSetInputProperty($form, 'Format', $this->coordinates ? $this->format : $this->no_coordinates_format));
		}

		Function GetJavascriptSetInputProperty(&$form, $form_object, $property, $value)
		{
			switch($property)
			{
				case "Latitude":
					return($this->latitude.'.value='.$value.';c=new GLatLng('.$this->latitude.'.value,'.$this->longitude.'.value);'.$this->map.'.setCenter(c);'.($this->hide_marker ? '' : $this->marker.'.setPoint(c);'));
				case "Longitude":
					return($this->longitude.'.value='.$value.';c=new GLatLng('.$this->latitude.'.value,'.$this->longitude.'.value);'.$this->map.'.setCenter(c);'.($this->hide_marker ? '' : $this->marker.'.setPoint(c);'));
			}
			return("");
		}

		Function AddInput(&$form, $arguments)
		{
			if(!IsSet($arguments['Key'])
			|| strlen($arguments['Key'])==0)
			return('it was not specified a valid Google Maps API key');
			$this->key = $arguments['Key'];
			if(IsSet($arguments['STYLE']))
			$this->style = $arguments['STYLE'];
			if(IsSet($arguments['CLASS']))
			$this->class = $arguments['CLASS'];
			if(IsSet($arguments['Latitude']))
			{
				$this->latitude_value = doubleval($arguments['Latitude']);
				if(strcmp($this->latitude_value, $arguments['Latitude'])
				|| $this->latitude_value>90.0
				|| $this->latitude_value<-90.0)
				return('it was not specified a valid latitude value');
			}
			if(IsSet($arguments['Longitude']))
			{
				$this->longitude_value = doubleval($arguments['Longitude']);
				if(strcmp($this->longitude_value, $arguments['Longitude'])
				|| $this->longitude_value>180.0
				|| $this->longitude_value<-180.0)
				return('it was not specified a valid longitude value');
			}
			if(IsSet($arguments['Accessible'])
			&& !$arguments['Accessible'])
			$this->accessible = 0;
			if(IsSet($arguments['HideMarker'])
			&& $arguments['HideMarker'])
			$this->hide_marker = 1;
			if(IsSet($arguments['Markers']))
			$this->markers=$arguments['Markers'];
			if(IsSet($arguments['ZoomMarkers'])
			&& $arguments['ZoomMarkers'])
			{
				if($this->hide_marker
				|| !$this->accessible)
				{
					if(count($this->markers)==0)
					return('it were not specified any markers to zoom');
					$start = 0;
				}
				else
				{
					$bounds=array(
					$this->latitude_value,
					$this->longitude_value,
					$this->latitude_value,
					$this->longitude_value
					);
					$start = 1;
				}
				for($m = 0; $m<count($this->markers); $m++)
				{
					$latitude = $this->markers[$m]['Latitude'];
					$longitude = $this->markers[$m]['Longitude'];
					if($latitude<-90
					|| $latitude>90
					|| $longitude<-180
					|| $longitude>180)
					return('it was specified a marker with invalid coordinates');
					if(!$start)
					{
						$bounds=array(
						$latitude,
						$longitude,
						$latitude,
						$longitude
						);
						$start = 1;
					}
					else
					{
						if($latitude<$bounds[0])
						$bounds[0]=$latitude;
						elseif($latitude>$bounds[2])
						$bounds[2]=$latitude;
						if($longitude<$bounds[1])
						$bounds[1]=$longitude;
						elseif($longitude>$bounds[3])
						$bounds[3]=$longitude;
					}
				}
				if($this->hide_marker
				|| !$this->accessible)
				{
					$this->latitude_value = ($bounds[2]+$bounds[0])/2.0;
					$this->longitude_value = ($bounds[3]+$bounds[1])/2.0;
				}
				if($bounds[0]!=$bounds[2]
				|| $bounds[1]!=$bounds[3])
				{
					if(IsSet($arguments['BoundsOffset']))
					{
						$o=$arguments['BoundsOffset'];
						$bounds[0]=max($bounds[0]-$o, -90);
						$bounds[1]=max($bounds[1]-$o, -180);
						$bounds[2]=min($bounds[2]+$o, 90);
						$bounds[3]=min($bounds[3]+$o, 180);
					}
					$this->zoom_bounds=$bounds;
				}
			}
			elseif(IsSet($arguments['ZoomBounds']))
			{
				$bounds=$arguments['ZoomBounds'];
				if(count($bounds)!=4
				|| $bounds[0]<-90
				|| $bounds[0]>=$bounds[2]
				|| $bounds[2]>90
				|| $bounds[1]<-180
				|| $bounds[1]>=$bounds[3]
				|| $bounds[3]>180)
				return('it were not specified a valid zoom bounds coordinates');
				$this->zoom_bounds=$bounds;
			}
			if(count($this->zoom_bounds)!=4
			&& IsSet($arguments['ZoomLevel']))
			{
				$this->zoom_value = intval($arguments['ZoomLevel']);
				if(strcmp($this->zoom_value, $arguments['ZoomLevel'])
				|| $this->zoom_value<0)
				return('it was not specified a valid zoom level value');
			}
			if(IsSet($arguments['MapType']))
			{
				$this->map_type_value = $arguments['MapType'];
				if(!IsSet($this->map_types[$this->map_type_value]))
				return('it was not specified a valid map type value');
			}
			if(IsSet($arguments["ValidationErrorMessage"]))
			$this->validation_error_message=$arguments["ValidationErrorMessage"];
			$this->coordinates=(!IsSet($arguments["Coordinates"]) || $arguments["Coordinates"]);
			$this->map = $this->GenerateInputID($form, $this->input, 'map');
			$this->latitude = $this->GenerateInputID($form, $this->input, 'latitude');
			$this->longitude = $this->GenerateInputID($form, $this->input, 'longitude');
			$this->zoom = $this->GenerateInputID($form, $this->input, 'zoom');
			$this->map_type = $this->GenerateInputID($form, $this->input, 'map_type');
			$this->marker = $this->GenerateInputID($form, $this->input, 'marker');
			if($this->coordinates)
			{
				$this->valid_marks = array(
				'input'=>array(
					'latitude'=>$this->latitude,
					'longitude'=>$this->longitude,
					'zoom'=>$this->zoom,
					'maptype'=>$this->map_type
				),
				'label'=>array(
					'latitudelabel'=>$this->latitude,
					'longitudelabel'=>$this->longitude
				),
				'data'=>array(
					'map'=>''
					)
					);
			}
			else
			{
				$this->valid_marks = array(
				'input'=>array(
					'latitude'=>$this->latitude,
					'longitude'=>$this->longitude,
					'zoom'=>$this->zoom,
					'maptype'=>$this->map_type
				),
				'data'=>array(
					'map'=>''
					)
					);
			}
			if($this->coordinates)
			{
				$input_arguments=array(
				"NAME"=>$this->latitude,
				"ID"=>$this->latitude,
				"TYPE"=>"text",
				"SIZE"=>5,
				"VALUE"=>strval($this->latitude_value),
				"LABEL"=>(IsSet($arguments["LatitudeLabel"]) ? $arguments["LatitudeLabel"] : 'Latitude:'),
				"ONCHANGE"=>'if(!isNaN(parseFloat(this.value))) { c='.$this->map.'.getCenter(); '.$this->map.'.setCenter(new GLatLng(parseFloat(this.value), c.lng()));'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint('.$this->map.'.getCenter());' : '').' }',
				"ValidateAsFloat"=>1,
				"ValidationLowerLimit"=>-90.0,
				"ValidationUpperLimit"=>90.0,
				"ValidationErrorMessage"=>$this->validation_error_message
				);
			}
			else
			{
				$input_arguments=array(
				"NAME"=>$this->latitude,
				"ID"=>$this->latitude,
				"TYPE"=>"hidden",
				"VALUE"=>strval($this->latitude_value),
				"ValidateAsFloat"=>1,
				"ValidationLowerLimit"=>-180.0,
				"ValidationUpperLimit"=>180.0,
				"DiscardInvalidValues"=>1,
				"ValidateOnlyOnServerSide"=>1
				);
			}
			if(IsSet($arguments["LatitudeClass"]))
			$input_arguments["CLASS"]=$arguments["LatitudeClass"];
			if(IsSet($arguments["LatitudeStyle"]))
			$input_arguments["STYLE"]=$arguments["LatitudeStyle"];
			if(strlen($error=$form->AddInput($input_arguments)))
			return($error);
			if($this->coordinates)
			{
				$input_arguments=array(
				"NAME"=>$this->longitude,
				"ID"=>$this->longitude,
				"TYPE"=>"text",
				"SIZE"=>6,
				"VALUE"=>strval($this->longitude_value),
				"LABEL"=>(IsSet($arguments["LongitudeLabel"]) ? $arguments["LongitudeLabel"] : 'Longitude:'),
				"ONCHANGE"=>'if(!isNaN(parseFloat(this.value))) { c='.$this->map.'.getCenter(); '.$this->map.'.setCenter(new GLatLng(c.lat(), parseFloat(this.value)));'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint('.$this->map.'.getCenter());' : '').' }',
				"ValidateAsFloat"=>1,
				"ValidationLowerLimit"=>-180.0,
				"ValidationUpperLimit"=>180.0,
				"ValidationErrorMessage"=>$this->validation_error_message
				);
			}
			else
			{
				$input_arguments=array(
				"NAME"=>$this->longitude,
				"ID"=>$this->longitude,
				"TYPE"=>"hidden",
				"VALUE"=>strval($this->longitude_value),
				"ValidateAsFloat"=>1,
				"ValidationLowerLimit"=>-180.0,
				"ValidationUpperLimit"=>180.0,
				"DiscardInvalidValues"=>1,
				"ValidateOnlyOnServerSide"=>1
				);
			}
			if(IsSet($arguments["LongitudeClass"]))
			$input_arguments["CLASS"]=$arguments["LongitudeClass"];
			if(IsSet($arguments["LongitudeStyle"]))
			$input_arguments["STYLE"]=$arguments["LongitudeStyle"];
			if(strlen($error=$form->AddInput($input_arguments)))
			return($error);
			$input_arguments=array(
			"NAME"=>$this->zoom,
			"ID"=>$this->zoom,
			"TYPE"=>"hidden",
			"VALUE"=>strval($this->zoom_value),
			"ValidateAsInteger"=>1,
			"ValidationLowerLimit"=>0,
			"DiscardInvalidValues"=>1,
			"ValidateOnlyOnServerSide"=>1
			);
			if(strlen($error=$form->AddInput($input_arguments)))
			return($error);
			$input_arguments=array(
			"NAME"=>$this->map_type,
			"ID"=>$this->map_type,
			"TYPE"=>"hidden",
			"VALUE"=>strval($this->map_type_value)
			);
			if(strlen($error=$form->AddInput($input_arguments)))
			return($error);
			if(IsSet($arguments['Controls']))
			$this->controls=$arguments['Controls'];
			if(IsSet($arguments['Format']))
			$this->format=$arguments['Format'];
			if(IsSet($arguments['NoCoordinatesFormat']))
			$this->no_coordinates_format=$arguments['NoCoordinatesFormat'];
			return($this->SetLocation($form));
		}

		Function ClassPageHead(&$form)
		{
			return($this->map_script);
		}

		Function PageLoad(&$form)
		{
			return($this->map.'load();');
		}

		Function PageUnload(&$form)
		{
			return('GUnload();');
		}

		Function GetInputProperty(&$form, $property, &$value)
		{
			switch($property)
			{
				case 'Latitude':
					$value = doubleval($form->GetInputValue($this->latitude));
					break;
				case 'Longitude':
					$value = doubleval($form->GetInputValue($this->longitude));
					break;
				default:
					return($this->DefaultGetInputProperty($form, $property, $value));
			}
		}

		Function SetInputProperty(&$form, $property, $value)
		{
			switch($property)
			{
				case 'Latitude':
					if($this->latitude_value != $value)
					{
						$this->latitude_value = doubleval($value);
						$form->SetInputValue($this->latitude, strval($this->latitude_value));
						$this->SetLocation($form);
					}
					break;
				case 'Longitude':
					if($this->longitude_value != $value)
					{
						$this->longitude_value = doubleval($value);
						$form->SetInputValue($this->longitude, strval($this->longitude_value));
						$this->SetLocation($form);
					}
					break;
				case "Accessible":
					return($this->DefaultSetInputProperty($form, $property, $value));
				default:
					return($this->DefaultSetInputProperty($form, $property, $value));
			}
			return("");
		}

		Function LoadInputValues(&$form, $submitted)
		{
			$latitude = doubleval($form->GetInputValue($this->latitude));
			$longitude = doubleval($form->GetInputValue($this->longitude));
			$zoom = intval($form->GetInputValue($this->zoom));
			$map_type = $form->GetInputValue($this->map_type);
			if(!IsSet($this->map_types[$map_type]))
			$map_type = $this->map_type_value;
			if(IsSet($form->Changes[$this->latitude])
			|| IsSet($form->Changes[$this->longitude])
			|| IsSet($form->Changes[$this->zoom])
			|| IsSet($form->Changes[$this->map_type]))
			{
				if(IsSet($form->Changes[$this->latitude]))
				{
					$form->Changes[$this->input] = $form->Changes[$this->latitude];
					UnSet($form->Changes[$this->latitude]);
				}
				if(IsSet($form->Changes[$this->longitude]))
				{
					$form->Changes[$this->input] = $form->Changes[$this->longitude];
					UnSet($form->Changes[$this->longitude]);
				}
				if(IsSet($form->Changes[$this->zoom]))
				UnSet($form->Changes[$this->zoom]);
				if(IsSet($form->Changes[$this->map_type]))
				UnSet($form->Changes[$this->map_type]);
				$this->latitude_value = $latitude;
				$this->longitude_value = $longitude;
				$this->zoom_value = $zoom;
				$this->map_type_value = $map_type;
				$this->SetLocation($form);
			}
		}

};

?>