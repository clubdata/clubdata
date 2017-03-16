<?php
/*
 *
 * @(#) $Id: form_captcha.php,v 1.14 2006/02/21 17:47:22 mlemos Exp $
 *
 */

class form_captcha_class extends form_custom_class
{
	var $format="{image} {text} {redraw}{validation}";
	var $image_parameter="___image";
	var $image_width=80;
	var $image_height=20;
	var $image_align="top";
	var $image_format="gif";
	var $verification_style="";
	var $verification_class="";
	var $text_color="#000000";
	var $background_color="";
	var $noise_image="";
	var $noise_image_format="";
	var $text_length=4;
	var $text_characters="0123456789abcdefghijklmnopqrstuvwxyz";
	var $font=2;
	var $expiry_time=0;
	var $expired_time_error_validation_error_message="";
	var $validation_error_message="It was not entered the correct text.";
	var $reset_incorrect_text=0;

	var $key="";
	var $text="";
	var $redraw_text="Redraw";
	var $redraw_sub_form="redraw";
	var $validation="";
	var $loaded_text="";
	var $remaining_time=0;
	var $requirements=array(
		"imagecreate"=>"the GD extension is not available",
		"imagegif"=>"the GD extension is not able to save in the GIF format",
		"imagecreatefromgif"=>"the GD extension is not able to read image files in the GIF format",
		"mcrypt_cfb"=>"the mcrypt extension is not available"
		);

		Function CheckRequirements()
		{
			if(IsSet($this->requirements["imagegif"])
			&& strcmp($this->image_format,"gif"))
			{
				$this->requirements["image".$this->image_format]="the GD extension is not able to save in the ".strtoupper($this->image_format)." format";
				UnSet($this->requirements["imagegif"]);
			}
			if(IsSet($this->requirements["imagecreatefromgif"])
			&& strcmp($this->noise_image_format,"gif"))
			{
				if(strlen($this->noise_image_format))
				$this->requirements["imagecreatefrom".$this->noise_image_format]="the GD extension is not able to read image files in the ".strtoupper($this->noise_image_format)." format";
				UnSet($this->requirements["imagecreatefromgif"]);
			}
			Reset($this->requirements);
			$end=(GetType($function=Key($this->requirements))!="string");
			for(;!$end;)
			{
				if(!function_exists($function))
				return($this->requirements[$function]);
				Next($this->requirements);
				$end=(GetType($function=Key($this->requirements))!="string");
			}
			return("");
		}

		Function EncodeText($text)
		{
			$encode_time=time();
			$iv_size=mcrypt_get_iv_size(MCRYPT_3DES,MCRYPT_MODE_CFB);
			$iv=str_repeat(chr(0),$iv_size);
			$key_size=mcrypt_get_key_size(MCRYPT_3DES,MCRYPT_MODE_CFB);
			$key=$encode_time.$this->key;
			if(strlen($key)>$key_size)
			$key=substr($key,0,$key_size);
			return(base64_encode(mcrypt_cfb(MCRYPT_3DES,$key,$text,MCRYPT_ENCRYPT,$iv)).":".$encode_time);
		}

		Function DecodeText($encoded, &$encode_time)
		{
			if(GetType($colon=strpos($encoded,":"))!="integer"
			|| ($encode_time=intval(substr($encoded,$colon+1)))==0
			|| $encode_time>time()
			|| !($encrypted=base64_decode(substr($encoded,0,$colon))))
			return("");
			$iv_size=mcrypt_get_iv_size(MCRYPT_3DES,MCRYPT_MODE_CFB);
			$iv=str_repeat(chr(0),$iv_size);
			$key_size=mcrypt_get_key_size(MCRYPT_3DES,MCRYPT_MODE_CFB);
			$key=$encode_time.$this->key;
			if(strlen($key)>$key_size)
			$key=substr($key,0,$key_size);
			return(mcrypt_cfb(MCRYPT_3DES,$key,$encrypted,MCRYPT_DECRYPT,$iv));
		}

		Function GenerateText()
		{
			for($text="",$c=0;$c<$this->text_length;$c++)
			$text.=substr($this->text_characters,rand(0,strlen($this->text_characters)-1),1);
			return($text);
		}

		Function SetKey(&$form,$encrypted,$format)
		{
			if(strlen($error=$form->GetInputEventURL($this->input,"getimage",array($this->image_parameter=>$encrypted),$image_url)))
			return($error);
			$this->valid_marks["data"]["image"]="<img alt=\"CAPTCHA image\" width=\"".$this->image_width."\" height=\"".$this->image_height."\"".(strlen($this->image_align) ? " align=\"".$this->image_align."\"" : "").(strlen($this->verification_style) ? " style=\"".$this->verification_style."\"" : "").(strlen($this->verification_class) ? " class=\"".$this->verification_class."\"" : "")." src=\"".HtmlEntities($image_url)."\" />";
			if(strlen($error=$form->SetInputValue($this->validation,$encrypted)))
			return($error);
			return($this->DefaultSetInputProperty($form, "Format", $format));
		}

		Function DrawNoiseImage($image,$noise_image,$noise_image_format)
		{
			$function="imagecreatefrom".$noise_image_format;
			if(!@($noise=$function($noise_image)))
			return("could not read the noise ".strtoupper($noise_image_format)." image file ".$noise_image);
			$width=imagesx($noise);
			$height=imagesy($noise);
			$offset_x=-rand(0,$width/2);
			$offset_y=-rand(0,$height/2);
			for($x=$offset_x;$x<$this->image_width;$x+=$width)
			for($y=$offset_y;$y<$this->image_height;$y+=$height)
			imagecopy($image,$noise,$x,$y,0,0,$width,$height);
			imagedestroy($noise);
			return("");
		}

		Function ClearImage($image,$color)
		{
			$rgb=(strlen($color) ? $color : "#FFFFFF");
			if(($background_color=imagecolorallocate($image, HexDec(substr($rgb,1,2)), HexDec(substr($rgb,3,2)), HexDec(substr($rgb,5,2))))==-1)
			return("could not allocate the background color");
			if(strlen($color)==0)
			$background_color=imagecolortransparent($image,$background_color);
			imagefilledrectangle($image, 0, 0, $this->image_width-1, $this->image_height-1, $background_color);
			return("");
		}

		Function DrawText($image,$text,$color)
		{
			$rgb=(strlen($color) ? $color : "#000000");
			if(($text_color=imagecolorallocate($image, HexDec(substr($rgb,1,2)), HexDec(substr($rgb,3,2)), HexDec(substr($rgb,5,2))))==-1)
			return("could not allocate the text color");
			if(strlen($color)==0)
			$text_color=imagecolortransparent($image,$text_color);
			$text_width=strlen($text)*imagefontwidth($this->font);
			$text_height=imagefontheight($this->font);
			imagestring($image, $this->font, rand(0,$this->image_width-$text_width),  rand(0,$this->image_height-$text_height), $text, $text_color);
			return("");
		}

		Function SetInputProperty(&$form, $property, $value)
		{
			switch($property)
			{
				case "TextLength":
				case "ImageWidth":
				case "ImageHeight":
					if(strcmp($value,intval($value))
					|| intval($value)<=0)
					return("it was not specified a valid ".$property." value");
					switch($property)
					{
						case "TextLength":
							$this->text_length=intval($value);
							break;
						case "ImageWidth":
							$this->image_width=intval($value);
							break;
						case "ImageHeight":
							$this->image_height=intval($value);
							break;
						case "Font":
							$this->font=intval($value);
							break;
					}
					break;
				case "Font":
					if(strcmp($value,intval($value))
					|| intval($value)<0)
					return("it was not specified a valid ".$property." value");
					$this->font=intval($value);
					break;
					break;
				case "TextColor":
				case "BackgroundColor":
					if(!ereg("^#[0-9a-fA-F]{6}\$",$value))
					return("it was not specified a valid ".$property." value");
					switch($property)
					{
						case "TextColor":
							$this->text_color=$value;
							break;
						case "BackgroundColor":
							$this->background_color=$value;
							break;
					}
					break;
				case "ImageFormat":
					switch($value)
					{
						case "gif":
						case "jpeg":
						case "png":
							$this->image_format=$value;
							break;
						default:
							return($value." is not a supported image format");
					}
					break;
				case "ImageAlign":
					$this->image_align=$value;
					break;
				case "VerificationStyle":
					$this->verification_style=$value;
					break;
				case "VerificationClass":
					$this->verification_class=$value;
					break;
				case "NoiseFromGIFImage":
					$this->noise_image=$value;
					$this->noise_image_format="gif";
					break;
				case "NoiseFromPNGImage":
					$this->noise_image=$value;
					$this->noise_image_format="png";
					break;
				case "RedrawText":
					if(strlen($value)==0)
					return("it was not specified a valid redraw button text value");
					$this->redraw_text=$value;
					break;
				case "RedrawSubForm":
					if(strlen($value)==0)
					return("it was not specified a valid redraw button sub-form value");
					$this->redraw_sub_form=$value;
					break;
				case "ResetIncorrectText":
					$this->reset_incorrect_text=(intval($value)!=0);
					break;
				default:
					return($this->DefaultSetInputProperty($form, $property, $value));
			}
			return("");
		}

		Function SetInputProperties(&$form, $arguments)
		{
			$properties=array(
			"TextLength",
			"TextColor",
			"BackgroundColor",
			"ImageFormat",
			"ImageHeight",
			"ImageWidth",
			"ImageAlign",
			"VerificationStyle",
			"VerificationClass",
			"NoiseFromGIFImage",
			"NoiseFromJPEGImage",
			"NoiseFromPNGImage",
			"RedrawText",
			"RedrawSubForm",
			"Font",
			"ResetIncorrectText"
			);
			for($property=0; $property<count($properties); $property++)
			{
				$name=$properties[$property];
				if(IsSet($arguments[$name])
				&& strlen($error=$this->SetInputProperty($form,$name,$arguments[$name])))
				return($error);
			}
			return("");
		}

		Function AddInput(&$form, $arguments)
		{
			if(!IsSet($arguments["Key"])
			|| strlen($arguments["Key"])==0)
			return("it was not specified a valid key");
			$this->key=$arguments["Key"];
			if(IsSet($arguments["ExpiryTime"]))
			{
				if(($this->expiry_time=intval($arguments["ExpiryTime"]))<=0)
				return("it was not specified a valid expiry time value");
				if(IsSet($arguments["ExpiryTimeValidationErrorMessage"])
				&& strlen($arguments["ExpiryTimeValidationErrorMessage"]))
				$this->expiry_time_validation_error_message=$arguments["ExpiryTimeValidationErrorMessage"];
				else
				return("it was not specified a valid expiry time validation error message");
			}
			if(IsSet($arguments["ValidationErrorMessage"])
			&& strlen($arguments["ValidationErrorMessage"]))
			$this->validation_error_message=$arguments["ValidationErrorMessage"];
			else
			return("it was not specified a valid validation error message");
			if(strlen($error=$this->SetInputProperties($form,$arguments)))
			return($error);
			if(strlen($error=$this->CheckRequirements()))
			return($error);
			$this->text=$this->GenerateInputID($form, $this->input, "text");
			$this->redraw=$this->GenerateInputID($form, $this->input, "redraw");
			$this->validation=$this->GenerateInputID($form, $this->input, "validation");
			$this->valid_marks=array(
			"input"=>array(
				"text"=>$this->text,
				"redraw"=>$this->redraw,
				"validation"=>$this->validation
			),
			"data"=>array(
				"image"=>""
				)
				);
				$input_arguments=array(
			"NAME"=>$this->text,
			"ID"=>$this->text,
			"TYPE"=>"text",
			"ValidateAsNotEmpty"=>1,
			"ValidationErrorMessage"=>$this->validation_error_message
				);
				if(IsSet($arguments["InputClass"]))
				$input_arguments["CLASS"]=$arguments["InputClass"];
				if(IsSet($arguments["InputStyle"]))
				$input_arguments["STYLE"]=$arguments["InputStyle"];
				if(strlen($error=$form->AddInput($input_arguments)))
				return($error);
				$redraw_arguments=array(
			"NAME"=>$this->redraw,
			"ID"=>$this->redraw,
			"TYPE"=>"submit",
			"VALUE"=>$this->redraw_text,
			"SubForm"=>$this->redraw_sub_form
				);
				if(IsSet($arguments["RedrawClass"]))
				$redraw_arguments["CLASS"]=$arguments["RedrawClass"];
				if(IsSet($arguments["RedrawStyle"]))
				$redraw_arguments["STYLE"]=$arguments["RedrawStyle"];
				if(strlen($error=$form->AddInput($redraw_arguments)))
				return($error);
				if(strlen($error=$form->AddInput(array(
			"NAME"=>$this->validation,
			"ID"=>$this->validation,
			"TYPE"=>"hidden",
			"VALUE"=>""
			))))
			return($error);
			$format=(IsSet($arguments["Format"]) ? $arguments["Format"] : $this->format);
			return($this->SetKey($form,$this->EncodeText($this->GenerateText()),$format));
		}

		Function LoadInputValues(&$form, $submitted)
		{
			if(IsSet($form->Changes[$this->validation]))
			{
				$encrypted=$form->GetInputValue($this->validation);
				$this->loaded_text=$this->DecodeText($encrypted,$encode_time);
				$this->remaining_time=$encode_time+$this->expiry_time-time();
				if(strlen($this->loaded_text)==$this->text_length
				&& ($this->expiry_time==0
				|| $this->remaining_time>0))
				$this->SetKey($form, $encrypted,$this->format);
				else
				{
					$form->SetInputValue($this->validation,$form->Changes[$this->validation]);
					$this->loaded_text="";
				}
			}
		}

		Function ValidateInput(&$form)
		{
			if($this->expiry_time
			&& $this->remaining_time<=0)
			return($this->expiry_time_validation_error_message);
			if(strcmp($this->loaded_text,$form->GetInputValue($this->text)))
			{
				if($this->reset_incorrect_text)
				{
					$this->SetKey($form,$this->EncodeText($this->GenerateText()),$this->format);
					$form->SetInputValue($this->text, $this->loaded_text="");
				}
				return($this->validation_error_message);
			}
			return("");
		}

		Function HandleEvent(&$form, $event, $parameters, &$processed)
		{
			switch($event)
			{
				case "getimage":
					if(!IsSet($parameters[$this->image_parameter]))
					return("the image parameter is not being passed to the captcha input getimage event handler");
					$text=$this->DecodeText(strval($parameters[$this->image_parameter]),$encode_time);
					if(!($image=imagecreate($this->image_width,$this->image_height)))
					return("could not create the CAPTCHA image");
					if(strlen($error=$this->ClearImage($image,$this->background_color)))
					return($error);
					if(strlen($this->noise_image)
					&& strlen($error=$this->DrawNoiseImage($image,$this->noise_image,$this->noise_image_format)))
					return($error);
					if(strlen($error=$this->DrawText($image,$text,$this->text_color)))
					return($error);
					Header("Content-Type: image/".$this->image_format);
					$function="image".$this->image_format;
					$function($image);
					imagedestroy($image);
					$processed=1;
					break;
				default:
					return($this->DefaultHandleEvent($form,$event,$parameters,$processed));
			}
			return("");
		}
};

?>