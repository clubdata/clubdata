<?php
/*
 *
 * @(#) $Id: form_map_location.php,v 1.31 2017/01/13 06:03:08 mlemos Exp $
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
	var $clusters=array();
	var $markers=array();
	var $icons=array();
	var $ads_manager = array();

	var $server_validate=0;
	var $use_focus_input_label = 0;
	var $map = '';
	var $latitude = '';
	var $longitude = '';
	var $zoom = '';
	var $map_type = '';
	var $marker_icon = '';
	var $default_marker_icon = '';
	var $cluster = '';
	var $marker = '';
	var $icon = '';
	var $setup_marker = '';
	var $map_script='';

	var $accessible = 1;
	var $hide_marker = 0;
	var $coordinates = 1;
	var $latitude_value = 0.0;
	var $longitude_value = 0.0;
	var $coordinates_set = 0;
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
		$used_icons = array();
		if(strlen($this->marker_icon))
			$used_icons[$this->marker_icon] = 0;
		$tc = count($this->clusters);
		for($markers = '', Reset($this->clusters), $c = 0; $c < $tc; Next($this->clusters), ++$c)
		{
			$cluster = Key($this->clusters);
			$markers.='var '.$this->cluster.'_markers_'.$cluster.'=[];'.$eol;
		}
		for($marker = 0; $marker<count($this->markers); $marker++)
		{
			$icon = $this->default_marker_icon;
			if(IsSet($this->markers[$marker]['Icon']))
			{
				$icon = $this->markers[$marker]['Icon'];
				if(strlen($icon)
				&& $this->icons[$icon])
				{
					if(!IsSet($used_icons[$icon]))
						$used_icons[$icon] = count($used_icons);
				}
				else
				{
					$form->OutputError('it was specified an invalid icon ("'.$icon.'") for marker '.$marker, $this->input);
					$icon = $this->default_marker_icon;
				}
			}
			elseif(strlen($icon)
			&& !IsSet($used_icons[$icon]))
				$used_icons[$icon] = count($used_icons);
			$options = '';
			if(IsSet($this->markers[$marker]['Title']))
				$options .= 'title:'.$form->EncodeJavascriptString($this->markers[$marker]['Title']);
			if(strlen($icon))
			{
				if(strlen($options))
					$options.=',';
				$options .= 'icon:'.$this->icon.$used_icons[$icon];
			}
			if(IsSet($this->markers[$marker]['Cluster']))
			{
				$cluster = $this->markers[$marker]['Cluster'];
				if(IsSet($this->clusters[$cluster]))
					$cl = ',cl:'.$this->cluster.'_markers_'.$cluster;
				else
				{
					$cl = '';
					$form->OutputError('it was specified an invalid marker cluster ("'.$cluster.'") for marker '.$marker, $this->input);
				}
			}
			else
				$cl = '';
			$markers.='var '.$this->marker.$marker.'='.$this->setup_marker.'('.$this->map.',{lt:'.strval($this->markers[$marker]['Latitude']).',ln:'.strval($this->markers[$marker]['Longitude']).(strlen($options) ? ',o:{'.$options.'}' : '').',i:'.$form->EncodeJavascriptString($this->markers[$marker]['Information']).(IsSet($this->markers[$marker]['Link']) ? ',lk:'.$form->EncodeJavascriptString($this->markers[$marker]['Link']) : '').(IsSet($this->markers[$marker]['Target']) ? ',t:'.$form->EncodeJavascriptString($this->markers[$marker]['Target']) : '').$cl.'});'.$eol;
		}
		$cluster_paths = array();
		$tc = count($this->clusters);
		for(Reset($this->clusters), $c = 0; $c < $tc; Next($this->clusters), ++$c)
		{
			$cluster = Key($this->clusters);
			$manager = (IsSet($this->clusters[$cluster]['Manager']) ? $this->clusters[$cluster]['Manager'] : 'not specified');
			switch($manager)
			{
				case 'MarkerClusterer':
					$markers.='var '.$this->cluster.$cluster.'= new '.$manager.'('.$this->map.', '.$this->cluster.'_markers_'.$cluster.');'.$eol;
					$path = 'markerclusterer.js';
					break;
				default:
					$form->OutputError('it was specified an invalid marker cluster manager ("'.$manager.'") for cluster '.$cluster, $this->input);
					break;
			}
			if(IsSet($this->clusters[$cluster]['Path']))
				$path = $this->clusters[$cluster]['Path'];
			$cluster_paths[$path] = $cluster;
		}
		$clusters = '';
		$tc = count($cluster_paths);
		for(Reset($cluster_paths), $c = 0; $c < $tc; Next($cluster_paths), ++$c)
			$clusters.='<script src="'.HtmlSpecialChars(Key($cluster_paths)).'" type="text/javascript"></script>'.$eol;
		for($icons = '', $i = 0, Reset($used_icons); $i<count($used_icons); Next($used_icons), $i++)
		{
			$icon = Key($used_icons);
			$icons.='var '.$this->icon.$i.'=new GIcon();'.$eol;
			for($p = 0, Reset($this->icons[$icon]); $p<count($this->icons[$icon]); ++$p, Next($this->icons[$icon]))
			{
				$property = Key($this->icons[$icon]);
				switch($property)
				{
					case 'image':
					case 'shadow':
					case 'printImage':
					case 'mozPrintImage':
					case 'transparent':
					case 'dragCrossImage':
						$icons.=$this->icon.$i.'.'.$property.'='.$form->EncodeJavascriptString($this->icons[$icon][$property]).';'.$eol;
						break;
					case 'iconSize':
					case 'shadowSize':
					case 'dragCrossSize':
						$icons.=$this->icon.$i.'.'.$property.'=new GSize('.$this->icons[$icon][$property][0].','.$this->icons[$icon][$property][1].');'.$eol;
						break;
					case 'iconAnchor':
					case 'infoWindowAnchor':
					case 'dragCrossAnchor':
						$icons.=$this->icon.$i.'.'.$property.'=new GPoint('.$this->icons[$icon][$property][0].','.$this->icons[$icon][$property][1].');'.$eol;
						break;
					case 'maxHeight':
						$icons.=$this->icon.$i.'.'.$property.'='.$this->icons[$icon][$property].';'.$eol;
						break;
					case 'imageMap';
						$icons.=$this->icon.$i.'.'.$property.'=['.implode(',',$this->icons[$icon][$property]).'];'.$eol;
						break;
		     default:
					$form->OutputError('it was specified an unsupported icon property ("'.$property.'")', $this->input);
					break;
				}
			}
		}
		$this->valid_marks['data']['map'] =
			'<div'.(strlen($this->style) ? ' style="'.HtmlSpecialChars($this->style).'"' : '').(strlen($this->style) ? ' class="'.HtmlSpecialChars($this->class).'"' : '').' id="'.$this->map.'"></div>'.$eol;
		$this->map_script='<script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key='.HtmlSpecialChars($this->key).'" type="text/javascript"></script>'.$eol.
			$clusters.
			'<script type="text/javascript">'.$eol.
			'// <![CDATA['.$eol.
			'var '.$this->map.';'.$eol.
			($this->hide_marker ? '' : 'var '.$this->marker.';'.$eol).
			'function '.$this->setup_marker.'(map, options)'.$eol.
			'{'.$eol.
			' var marker=new GMarker(new GLatLng(options.lt, options.ln), options.o ? options.o : null);'.$eol.
			' if(options.cl)'.$eol.
			'  options.cl.push(marker);'.$eol.
			' else'.$eol.
			'  map.addOverlay(marker);'.$eol.
			' if(options.i)'.$eol.
			' {'.$eol.
			'  GEvent.addListener(marker, "mouseover", function() { marker.openInfoWindowHtml(options.i); });'.$eol.
			'  GEvent.addListener(marker, "mouseout", function() { map.closeInfoWindow(); });'.$eol.
			' }'.$eol.
			' if(options.lk)'.$eol.
			'  GEvent.addListener(marker, "click", function() { var w; if(!options.t || !(w = window.open(options.lk, options.t)) || !w.top) { window.location=options.lk;}} );'.$eol.
			'}'.$eol.
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
			$this->map.'.setCenter(new GLatLng('.(($this->coordinates_set || count($this->zoom_bounds)!=4) ? $this->latitude_value.', '.$this->longitude_value : (($this->zoom_bounds[0] + $this->zoom_bounds[2])/2).', '.(($this->zoom_bounds[1] + $this->zoom_bounds[3])/2)).'), '.(count($this->zoom_bounds)==4 ? $this->map.'.getBoundsZoomLevel(new GLatLngBounds(new GLatLng('.$this->zoom_bounds[0].','.$this->zoom_bounds[1].'),new GLatLng('.$this->zoom_bounds[2].','.$this->zoom_bounds[3].')))' : $this->zoom_value).');'.$eol.
			$this->map.'.setMapType('.$this->map_types[$this->map_type_value].');'.$eol.
			'GEvent.addListener('.$this->map.', "click", function(marker, point) { if(marker!=null) return false; c=new GLatLng(point.lat(), point.lng()); '.$this->map.'.panTo(c);'.($this->accessible ? ' l=document.getElementById("'.$this->latitude.'"); l.value=c.lat(); l=document.getElementById("'.$this->longitude.'"); l.value=c.lng();'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint(c);'.($this->coordinates_set ? '' : $this->marker.'.show();') : '') : '').' });'.$eol.
			'GEvent.addListener('.$this->map.', "zoomend", function(oldlevel, newlevel) { l=document.getElementById("'.$this->zoom.'"); l.value=newlevel; });'.$eol.
			'GEvent.addListener('.$this->map.', "maptypechanged", function() { l=document.getElementById("'.$this->map_type.'"); t='.$this->map.'.getCurrentMapType(); if(t==G_NORMAL_MAP) l.value="Normal"; else { if(t==G_SATELLITE_MAP) l.value="Satellite"; else { if(t==G_HYBRID_MAP) l.value = "Hybrid"; } } });'.$eol.
			$icons.
			($this->hide_marker ? '' : $this->marker.'=new GMarker('.$this->map.'.getCenter(), { draggable: true '.(strlen($this->marker_icon) ? ', icon: '.$this->icon.$used_icons[$this->marker_icon] : '').' });'.$eol.
			'GEvent.addListener('.$this->marker.', "dragend", function(marker) { var point='.$this->marker.'.getPoint(); var c=new GLatLng(point.lat(), point.lng()); '.$this->map.'.panTo(c);'.($this->accessible ? ' l=document.getElementById("'.$this->latitude.'"); l.value=c.lat(); l=document.getElementById("'.$this->longitude.'"); l.value=c.lng();'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint(c);'.($this->coordinates_set ? '' : $this->marker.'.show();') : '') : '').' });'.$eol.
			$this->map.'.addOverlay('.$this->marker.');'.$eol).
			(($this->hide_marker || $this->coordinates_set) ? '' : $this->marker.'.hide();').
			$markers.
			(IsSet($this->ads_manager['Publisher']) ?
			'(new GAdsManager('.$this->map.', '.$form->EncodeJavascriptString($this->ads_manager['Publisher']).','.$eol.
			'{'.$eol.
			' style: \'adunit\''.
			(IsSet($this->ads_manager['MaxAdsOnMap']) ? ','.$eol.' maxAdsOnMap: '.intval($this->ads_manager['MaxAdsOnMap']) : '').
			(IsSet($this->ads_manager['Channel']) ? ','.$eol.' channel: '.$form->EncodeJavascriptString($this->ads_manager['Channel']) : '').
			$eol.'}'.$eol.
			')).enable()'.$eol
			: '').
			'}'.$eol.'}'.$eol.'// ]]></script>'.$eol;
		return($this->DefaultSetInputProperty($form, 'Format', $this->coordinates ? $this->format : $this->no_coordinates_format));
	}

	Function GetJavascriptSetInputProperty(&$form, $form_object, $property, $value)
	{
		switch($property)
		{
			case "Latitude":
				return(($this->coordinates ? $this->latitude.'.value='.$value.';' : '').'c=new GLatLng('.$this->latitude.'.value,'.$this->longitude.'.value);'.$this->map.'.setCenter(c);'.($this->hide_marker ? '' : $this->marker.'.setPoint(c);'.($this->coordinates_set ? '' : $this->marker.'.show();')));
			case "Longitude":
				return(($this->coordinates ? $this->longitude.'.value='.$value.';' : '').'c=new GLatLng('.$this->latitude.'.value,'.$this->longitude.'.value);'.$this->map.'.setCenter(c);'.($this->hide_marker ? '' : $this->marker.'.setPoint(c);'.($this->coordinates_set ? '' : $this->marker.'.show();')));
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
			$this->longitude_value = 0.0;
			$this->coordinates_set = 1;
		}
		if(IsSet($arguments['Longitude']))
		{
			$this->longitude_value = doubleval($arguments['Longitude']);
			if(strcmp($this->longitude_value, $arguments['Longitude'])
			|| $this->longitude_value>180.0
			|| $this->longitude_value<-180.0)
				return('it was not specified a valid longitude value');
			if(!$this->coordinates_set)
			{
				$this->latitude_value = 0.0;
				$this->coordinates_set = 1;
			}
		}
		if(IsSet($arguments['Accessible'])
		&& !$arguments['Accessible'])
			$this->accessible = 0;
		if(IsSet($arguments['HideMarker'])
		&& $arguments['HideMarker'])
			$this->hide_marker = 1;
		if(IsSet($arguments['Icons']))
			$this->icons=$arguments['Icons'];
		if(IsSet($arguments['MarkerIcon']))
		{
			$this->marker_icon = $arguments['MarkerIcon'];
			if(strlen($this->marker_icon) == 0
			|| !IsSet($this->icons[$this->marker_icon]))
				return('it was not specified a valid marker icon');
		}
		if(IsSet($arguments['DefaultMarkerIcon']))
		{
			$this->default_marker_icon = $arguments['DefaultMarkerIcon'];
			if(strlen($this->default_marker_icon) == 0
			|| !IsSet($this->icons[$this->default_marker_icon]))
				return('it was not specified a valid default marker icon');
		}
		if(IsSet($arguments['Clusters']))
			$this->clusters=$arguments['Clusters'];
		if(IsSet($arguments['Markers']))
			$this->markers=$arguments['Markers'];
		if(IsSet($arguments['ZoomMarkers'])
		&& $arguments['ZoomMarkers'])
		{
			if($this->hide_marker
			|| !$this->coordinates_set
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
			|| !$this->coordinates_set
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
		$this->cluster = $this->GenerateInputID($form, $this->input, 'cluster');
		$this->marker = $this->GenerateInputID($form, $this->input, 'marker');
		$this->icon = $this->GenerateInputID($form, $this->input, 'icon');
		$this->setup_marker = $this->GenerateInputID($form, $this->input, 'sm');
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
				"VALUE"=>($this->coordinates_set ? strval($this->latitude_value) : ''),
				"LABEL"=>(IsSet($arguments["LatitudeLabel"]) ? $arguments["LatitudeLabel"] : 'Latitude:'),
				"ONCHANGE"=>'if(!isNaN(parseFloat(this.value))) { c='.$this->map.'.getCenter(); '.$this->map.'.setCenter(new GLatLng(parseFloat(this.value), c.lng()));'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint('.$this->map.'.getCenter());'.($this->coordinates_set ? '' : $this->marker.'.show();') : '').' }',
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
		if(IsSet($arguments['DependentValidation']))
			$input_arguments['DependentValidation'] = $arguments['DependentValidation'];
		if(strlen($error=$form->AddInput($input_arguments)))
			return($error);
		if($this->coordinates)
		{
			$input_arguments=array(
				"NAME"=>$this->longitude,
				"ID"=>$this->longitude,
				"TYPE"=>"text",
				"SIZE"=>6,
				"VALUE"=>($this->coordinates_set ? strval($this->longitude_value) : ''),
				"LABEL"=>(IsSet($arguments["LongitudeLabel"]) ? $arguments["LongitudeLabel"] : 'Longitude:'),
				"ONCHANGE"=>'if(!isNaN(parseFloat(this.value))) { c='.$this->map.'.getCenter(); '.$this->map.'.setCenter(new GLatLng(c.lat(), parseFloat(this.value)));'.(!$this->hide_marker ? ' '.$this->marker.'.setPoint('.$this->map.'.getCenter());'.($this->coordinates_set ? '' : $this->marker.'.show();') : '').' }',
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
		if(IsSet($arguments['DependentValidation']))
			$input_arguments['DependentValidation'] = $arguments['DependentValidation'];
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
		if(IsSet($arguments['DependentValidation']))
			$input_arguments['DependentValidation'] = $arguments['DependentValidation'];
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
		if(IsSet($arguments['AdsManager']))
			$this->ads_manager=$arguments['AdsManager'];
		if(IsSet($arguments['Format']))
			$this->format=$arguments['Format'];
		if(IsSet($arguments['NoCoordinatesFormat']))
			$this->no_coordinates_format=$arguments['NoCoordinatesFormat'];
		return($this->SetLocation($form));
	}

	Function PageHead(&$form)
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
				$value = $form->GetInputValue($this->latitude);
				if(strlen($value))
					$value = min(max(-90, doubleval($value)), 90);
				break;
			case 'Longitude':
				$value = $form->GetInputValue($this->longitude);
				if(strlen($value))
					$value = min(max(-180, doubleval($value)), 180);
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
		$latitude = $form->GetInputValue($this->latitude);
		$longitude = $form->GetInputValue($this->longitude);
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
			if(strlen($latitude))
				$latitude = min(max(-90, doubleval($latitude)), 90);
			if(strlen($longitude))
				$longitude = min(max(-180, doubleval($longitude)), 180);
			if(strlen($latitude)
			&& strlen($longitude))
				$this->coordinates_set = 1;
			$this->latitude_value = $latitude;
			$this->longitude_value = $longitude;
			$this->zoom_value = $zoom;
			$this->map_type_value = $map_type;
			$this->SetLocation($form);
		}
		return('');
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'LocateAddress':
				if(!IsSet($context['Address'])
				|| strlen($address = $form->GetJavascriptInputValue($form_object, $context['Address'])) == 0)
					return('it was not specified a valid address input for LocateAddress action');
				if(IsSet($context['Country']))
				{
					if(strlen($country = $form->GetJavascriptInputValue($form_object, $context['Country'])) == 0)
						return('it was not specified a valid country input for LocateAddress action');
					if(IsSet($context['CountryValue'])
					&& $context['CountryValue'] == "SelectedOption")
					{
						if(strlen($country_name = $form->GetJavascriptSelectedOption($form_object, $context['Country'])) == 0)
							return('it was not specified a valid country select input for LocateAddress action');
						$address .= ' + ", " + '.$country_name;
					}
					else
						$address .= ' + ", " + c';
				}
				else
					$country = '';
				$javascript='var g = new GClientGeocoder();'.(strlen($country) ? ' var c = '.$country.'; if(c.length == 2) g.setBaseCountryCode(c);' : '').' g.getLatLng('.$address.', function(c) { if(c) { '.$this->map.'.setCenter(c); '.($this->hide_marker ? '' : $this->marker.'.setPoint(c);'.($this->coordinates_set ? '' : $this->marker.'.show();')).' '.($this->coordinates ? $this->longitude.'.value=c.lng();'.$this->latitude.'.value=c.lat();' : '').'} } );';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}
};

?>