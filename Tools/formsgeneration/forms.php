<?php
if(!defined("PHP_LIBRARY_FORMS"))
{
	define("PHP_LIBRARY_FORMS",1);

	/*
	 * forms.php
	 *
	 * @(#) $Header: /home/mlemos/cvsroot/forms/forms.php,v 1.270 2006/12/20 05:26:58 mlemos Exp $
	 *
	 * This LICENSE is in the BSD license style.
	 *
	 *
	 * Copyright (c) 1999-2006, Manuel Lemos
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without
	 * modification, are permitted provided that the following conditions
	 * are met:
	 *
	 *   Redistributions of source code must retain the above copyright
	 *   notice, this list of conditions and the following disclaimer.
	 *
	 *   Redistributions in binary form must reproduce the above copyright
	 *   notice, this list of conditions and the following disclaimer in the
	 *   documentation and/or other materials provided with the distribution.
	 *
	 *   Neither the name of Manuel Lemos nor the names of his contributors
	 *   may be used to endorse or promote products derived from this software
	 *   without specific prior written permission.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
	 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
	 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
	 * A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR
	 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
	 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
	 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
	 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
	 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
	 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	 *
	 */

	Function FormAppendOutput($output)
	{
		global $form_output;

		$form_output.=$output;
	}

	Function FormDisplayOutput($output)
	{
		echo $output;
	}

	class form_custom_class
	{
		var $input;
		var $custom_class='';
		var $valid_marks=array();
		var $format="";
		var $focus_input="";
		var $mark_start="{";
		var $mark_end="}";
		var $client_validate=0;
		var $server_validate=1;

		var $format_data;
		var $format_marks;
		var $children=array();
		var $connections=array();
		var $events=array();

		Function GenerateInputID(&$form, $input, $kind)
		{
			return("p_".$input."_".$kind);
		}

		Function ParseFormat($format, $valid_marks, &$data, &$marks)
		{
			$mark_start_length=strlen($this->mark_start);
			$mark_end_length=strlen($this->mark_end);
			for($last_data=0, $data=$marks=array(), $length=strlen($format), $position=0; $position<$length;)
			{
				if(GetType($next=strpos($format,$this->mark_start,$position))=="integer")
				{
					$block=substr($format,$position,$next-$position);
					if($last_data)
					$data[count($data)-1].=$block;
					else
					$data[]=$block;
					if(GetType($following=strpos($format,$this->mark_end,$next+$mark_start_length))=="integer")
					{
						$mark=substr($format,$next+$mark_start_length,$following-$next-$mark_start_length);
						if(IsSet($valid_marks["input"][$mark]))
						{
							$marks[]=array(1, $valid_marks["input"][$mark]);
							$last_data=0;
						}
						elseif(IsSet($valid_marks["dynamicinput"][$mark]))
						{
							$marks[]=array(2, $valid_marks["dynamicinput"][$mark]);
							$last_data=0;
						}
						elseif(IsSet($valid_marks["data"][$mark]))
						{
							$data[count($data)-1].=$valid_marks["data"][$mark];
							$last_data=1;
						}
						elseif(IsSet($valid_marks["dynamicdata"][$mark]))
						{
							$marks[]=array(3, $valid_marks["dynamicdata"][$mark]);
							$last_data=0;
						}
						elseif(IsSet($valid_marks["label"][$mark]))
						{
							$marks[]=array(4, $valid_marks["label"][$mark]);
							$last_data=0;
						}
						elseif(IsSet($valid_marks["dynamiclabel"][$mark]))
						{
							$marks[]=array(5, $valid_marks["dynamiclabel"][$mark]);
							$last_data=0;
						}
						else
						return("it was specified an invalid format mark (".$mark.") at position ".$next);
						$position=$following+$mark_end_length;
					}
					else
					return("it was specified an unfinished format mark at position ".$next);
				}
				else
				{
					$block=substr($format,$position);
					if($last_data)
					$data[count($data)-1].=$block;
					else
					$data[]=$block;
					break;
				}
			}
			return("");
		}

		Function ParseInputFormat()
		{
			if(!IsSet($this->format_data)
			&& strlen($error=$this->ParseFormat($this->format, $this->valid_marks, $this->format_data, $this->format_marks)))
			{
				UnSet($this->format_data);
				return($error);
			}
			return("");
		}

		Function ParseNewInputFormat()
		{
			UnSet($this->format_data);
			UnSet($this->format_marks);
			if(strlen($error=$this->ParseInputFormat()))
			{
				UnSet($this->format_data);
				UnSet($this->format_marks);
			}
			return($error);
		}

		Function AddFormattedDynamicPart(&$f, $d, $m, $h, $y)
		{
			$c=count($d);
			$k=count($m);
			for($p=0;$p<$c;$p++)
			{
				if((strlen($d[$p])
				&& strlen($e=$f->AddDataPart($d[$p]))))
				return($e);
				if($p<$k)
				{
					$v=$m[$p][1];
					switch($m[$p][0])
					{
						case 1:
							$e=($h ? $f->AddInputHiddenPart($v) : $f->AddInputPart($v));
							break;
						case 2:
							$e=(IsSet($y[$v]) ? ($h ? $f->AddInputHiddenPart($y[$v]) : $f->AddInputPart($y[$v])) : $v.' is not a valid dynamic parameter for an input');
							break;
						case 3:
							$e=(IsSet($y[$v]) ? $f->AddDataPart($y[$v]) : $v.' is not a valid dynamic parameter for a form output data part');
							break;
						case 4:
							$e=$f->AddLabelPart(array('FOR'=>$v));
							break;
						case 5:
							$e=(IsSet($y[$v]) ? $f->AddLabelPart(array('FOR'=>$y[$v])) : $v.' is not a valid dynamic parameter for an input label');
							break;
					}
					if(strlen($e))
					return($e);
				}
			}
			return("");
		}

		Function AddFormattedPart(&$form, $data, $marks, $hidden)
		{
			return($this->AddFormattedDynamicPart($form, $data, $marks, $hidden, array()));
		}

		Function AddInput(&$form, $arguments)
		{
			return("form input custom class does not implement the AddInput function");
		}

		Function AddInputPart(&$form)
		{
			if(count($this->valid_marks)==0
			|| strlen($this->format)==0)
			return("form input custom class does not implement the AddInputPart function");
			if(strlen($error=$this->ParseInputFormat()))
			return($error);
			return($this->AddFormattedPart($form, $this->format_data, $this->format_marks, 0));
		}

		Function AddInputHiddenPart(&$form)
		{
			if(count($this->valid_marks)==0
			|| strlen($this->format)==0)
			return("form input custom class does not implement the AddInputHiddenPart function");
			if(strlen($error=$this->ParseInputFormat()))
			return($error);
			return($this->AddFormattedPart($form, $this->format_data, $this->format_marks, 1));
		}

		Function AddLabelPart(&$form, $arguments)
		{
			if(strlen($this->focus_input)==0)
			return("form input custom class does not implement the AddLabelPart function");
			$arguments["FOR"]=$this->focus_input;
			if(!IsSet($arguments["STYLE"])
			&& !IsSet($form->inputs[$this->focus_input]["LabelSTYLE"])
			&& IsSet($form->inputs[$this->input]["LabelSTYLE"]))
			$arguments["STYLE"]=$form->inputs[$this->input]["LabelSTYLE"];
			if(!IsSet($arguments["CLASS"])
			&& !IsSet($form->inputs[$this->focus_input]["LabelCLASS"])
			&& IsSet($form->inputs[$this->input]["LabelCLASS"]))
			$arguments["CLASS"]=$form->inputs[$this->input]["LabelCLASS"];
			return($form->AddLabelPart($arguments));
		}

		Function ValidateInput(&$form)
		{
			return("form input custom class does not implement the Validate function");
		}

		Function DefaultSetInputProperty(&$form, $property, $value)
		{
			switch($property)
			{
				case "Format":
					$this->format=$value;
					if(strlen($error=$this->ParseNewInputFormat()))
					return($error);
					for($mark=0; $mark<count($this->format_marks) && $this->format_marks[$mark][0]!=1; $mark++);
					if($mark>=count($this->format_marks))
					{
						$this->focus_input="";
						return("the input format does not specify any fixed inputs");
					}
					$this->focus_input=$this->format_marks[$mark][1];
					break;
				case "Accessible":
				case "SubForm":
					for($child=0;$child<count($this->children);$child++)
					{
						$error=$form->SetInputProperty($this->children[$child], $property, $value);
						if(strlen($error))
						return($error);
					}
					break;
				default:
					return($property." is not a changeable form ".$this->input." input property");
			}
			return("");
		}

		Function SetInputProperty(&$form, $property, $value)
		{
			return($this->DefaultSetInputProperty($form, $property, $value));
		}

		Function DefaultGetInputProperty(&$form, $property, &$value)
		{
			return("GetInputProperty is not implemented for form ".$this->input." input property ".$property);
		}

		Function GetInputProperty(&$form, $property, &$value)
		{
			return($this->DefaultGetInputProperty($form, $property, $value));
		}

		Function GetInputValue(&$form)
		{
			return("");
		}

		Function AddFunction(&$form, $arguments)
		{
			if(strlen($this->focus_input)==0)
			return("form input custom class does not implement the AddFunction function");
			$arguments["Element"]=$this->focus_input;
			return($form->AddFunction($arguments));
		}

		Function LoadInputValues(&$form, $submitted)
		{
		}

		Function GetJavascriptValidations(&$form, $form_object, &$validations)
		{
			$validations=array();
			return("");
		}

		Function GetJavascriptInputValue(&$form, $form_object)
		{
			return("");
		}

		Function GetJavascriptSetInputProperty(&$form, $form_object, $property, $value)
		{
			return("");
		}

		Function AddChild(&$form, $name)
		{
			$this->children[]=$name;
			return("");
		}

		Function DefaultHandleEvent(&$form, $event, $parameters, &$processed)
		{
			return("form input custom class is not ready to handle ".$event." events");
		}

		Function HandleEvent(&$form, $event, $parameters, &$processed)
		{
			return($this->DefaultHandleEvent($form,$event,$parameters,$processed));
		}

		Function GetEventActions(&$form, $form_object, $event)
		{
			if(IsSet($this->events[$event]))
			{
				$actions=trim($this->events[$event]);
				if(($last=strlen($actions))
				&& strcmp($actions[$last-1], ";"))
				$actions.=";";
			}
			else
			$actions="";
			if(!IsSet($this->connections[$event]))
			return($actions);
			for($c=0; $c <count($this->connections[$event]); $c++)
			{
				$error=$form->GetJavascriptConnectionAction($form_object, $this->input, $this->connections[$event][$c]["To"], $event, $this->connections[$event][$c]["Action"], $this->connections[$event][$c]["Context"], $javascript);
				if(strlen($error))
				$form->OutputError($error, $this->input);
				elseif(strlen($javascript))
				{
					if(strlen($actions))
					$actions.="; ";
					$actions.=$javascript;
				}
			}
			return($actions);
		}

		Function DefaultConnect(&$form, $to, $event, $action, &$context)
		{
			if(!IsSet($form->inputs[$to]))
			return("it was not specified a valid event connection destination input");
			if(!IsSet($this->connections[$event]))
			return("form input custom class does not implement the Connect function for action ".$action);
			$this->connections[$event][]=array(
			"To"=>$to,
			"Action"=>$action,
			"Context"=>$context
			);
			if(!IsSet($this->events[$event]))
			$this->events[$event]="";
			return("");
		}

		Function Connect(&$form, $to, $event, $action, &$context)
		{
			return($this->DefaultConnect($form, $to, $event, $action, $context));
		}

		Function DefaultGetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
		{
			if(strlen($this->focus_input)
			&& !strcmp($action, 'Focus'))
			return($form->GetJavascriptConnectionAction($form_object, $from, $this->focus_input, $event, $action, $context, $javascript));
			$javascript="";
			return("form input custom class does not implement the GetJavascriptConnectionAction function for action ".$action);
		}

		Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
		{
			return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}

		Function SetSelectOptions(&$form, $options, $selected)
		{
			return("form input custom class does not implement the SetSelectOptions function");
		}

		Function ReplyMessage(&$form, $message, &$processed)
		{
			return("form input custom class does not implement the ReplyMessage function");
		}

		Function ClassPageHead(&$form)
		{
			return("");
		}

		Function PageHead(&$form)
		{
			return("");
		}

		Function PageLoad(&$form)
		{
			return("");
		}

		Function PageUnload(&$form)
		{
			return("");
		}

		Function DefaultPostMessage(&$form, $message, &$processed)
		{
			return($form->ReplyMessage($message, $processed));
		}

		Function PostMessage(&$form, $message, &$processed)
		{
			return($this->DefaultPostMessage($form, $message, $processed));
		}
	};

	class form_class
	{
		var $parts=array();
		var $inputs=array();
		var $types=array();
		var $ID="";
		var $NAME="";
		var $METHOD="";
		var $ACTION="";
		var $TARGET="";
		var $ONSUBMIT="";
		var $ONSUBMITTING="";
		var $ENCTYPE="";
		var $ONRESET="";
		var $ExtraAttributes=array();
		var $encoding="iso-8859-1";
		var $ValidationFunctionName="ValidateForm";
		var $ValidateAsEmail="ValidateEmail";
		var $ValidateAsCreditCard="ValidateCreditCard";
		var $ReadOnly=0;
		var $OutputPasswordValues=0;
		var $Changes=array();
		var $Invalid=array();
		var $InvalidCLASS='';
		var $InvalidSTYLE='';
		var $OptionsSeparator="\n";
		var $ShowAllErrors=0;
		var $ErrorMessagePrefix="";
		var $ErrorMessageSuffix="";
		var $sub_form_variable_name="sub_form";
		var $email_regular_expression="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
		var $float_regular_expression="^-?[0-9]+(\\.[0-9]*)?([Ee][+-]?[0-9]+)?\$";
		var $decimal_regular_expression="^-?[0-9]+(\\.[0-9]{0,PLACES})?\$";
		var $client_validate=0;
		var $server_validate=0;
		var $accessibility_tab_index="";
		var $debug="";
		var $end_of_line="\n";
		var $input_parts=array();
		var $input_elements=array();
		var $functions=array();
		var $toupper_function="strtoupper";
		var $tolower_function="strtolower";
		var $form_submitted_variable_name="form_submitted";
		var $form_submitted_test_variable_name="form_submitted_test";
		var $event_parameter="___event";
		var $input_parameter="___input";
		var $ResubmitConfirmMessage="";
		var $allow_used_access_keys=1;
		var $label_access_keys=array();
		var $reserved_access_keys=array();
		var $hidden_parts=0;
		var $accessible_parts=0;
		var $file_parts=0;
		var $error="";
		var $capturing=0;
		var $checkbox_inputs;
		var $radio_inputs;
		var $current_parent="";
		var $messages = array();
		var $connections=array();

		Function EncodeJavascriptString($s)
		{
			$l=strlen($s=strval($s));
			if($l==0)
			return("''");
			$n=!strcmp(strtolower($this->encoding),"iso-8859-1");
			$e=array(
			"\n"=>"\\n",
			"\r"=>"\\r",
			"\t"=>"\\t"
			);
			for($d=0, $j="", $c=0; $c<$l; $c++)
			{
				$t = $s[$c];
				$a = Ord($t);
				if($a<32
				|| $t=='<'
				|| ($a>126
				&& $n))
				{
					switch($t)
					{
						case "\n":
						case "\r":
						case "\t":
							if($c==0
							|| $d)
							{
								if($c!=0)
								$j.='+';
								$j.="'".$e[$t];
								$d=0;
							}
							else
							$j.=$e[$t];
							break;
						default:
							if($c!=0)
							{
								if(!$d)
								$j.="'";
								$j.="+";
							}
							$j.="unescape('%".sprintf("%02X",$a)."')";
							$d=1;
							break;
					}
				}
				else
				{
					if($d)
					$j.="+'";
					else
					{
						if($c==0)
						$j.="'";
					}
					if($t=="'"
					|| $t=="\\")
					$j.="\\";
					$j.=$t;
					$d=0;
				}
			}
			if(!$d)
			$j.="'";
			return($j);
		}

		Function EncodeHTMLString($string)
		{
			switch(strtolower($this->encoding))
			{
				case "iso-8859-1":
					return(HtmlEntities($string));
				default:
					return(HtmlSpecialChars($string));
			}
		}

		Function EscapeJavascriptRegularExpressions($expression)
		{
			return($this->EncodeJavascriptString($expression));
		}

		Function OutputDebug($message)
		{
			if(strlen($function=$this->debug))
			$function($message);
		}

		Function OutputError($error,$scope="")
		{
			$this->error=(strcmp($scope,"") ? $scope.": ".$error : $error);
			if(strcmp($function=$this->debug,"")
			&& strcmp($this->error,""))
			$this->OutputDebug($this->error);
			return($this->error);
		}

		Function SetSelectedOptions($name, &$input, &$options, &$value)
		{
			if(!IsSet($options)
			|| strcmp(GetType($options),"array")
			|| count($options)==0)
			return($this->OutputError("it was not defined a valid options array",$name));
			if(IsSet($input["MULTIPLE"]))
			{
				$selected=array();
				if(strcmp(GetType($value),"array"))
				return($this->OutputError("it was not defined a valid selected options array",$name));
				for($option=0;$option<count($value);$option++)
				{
					$option_value=$value[$option];
					if(!IsSet($options[$option_value]))
					return($this->OutputError("it specified a selected value that is not a valid option",$name));
					if(IsSet($selected[$option_value]))
					return($this->OutputError("it specified a repeated selected option value",$name));
					$selected[$option_value]=1;
				}
				$input["SELECTED"]=$selected;
			}
			else
			{
				if(!IsSet($value)
				|| (strcmp(GetType($value),"string")
				&& strcmp(GetType($value),"integer")
				&& strcmp(GetType($value),"double"))
				|| !IsSet($options[strval($value)]))
				return($this->OutputError("it was not defined a valid input value",$name));
				$input["VALUE"]=strval($value);
			}
			$input["OPTIONS"]=$options;
			return("");
		}

		Function AddInput($arguments)
		{
			if(strcmp(GetType($arguments),"array"))
			return($this->OutputError("it was not specified a valid arguments array","AddInput"));
			$input=array();
			$name="";
			if(IsSet($arguments["NAME"])
			&& strcmp($arguments["NAME"],""))
			$name=$input["NAME"]=$arguments["NAME"];
			if(IsSet($arguments["ID"])
			&& strcmp($arguments["ID"],""))
			$name=$input["ID"]=$arguments["ID"];
			if(!strcmp($name,""))
			return($this->OutputError("it was not specified a valid input name","AddInput"));
			if(IsSet($this->inputs[$name]))
			return($this->OutputError("it was specified the name of an already defined input",$name));
			if(!IsSet($arguments["TYPE"]))
			return($this->OutputError("it was not defined the type of form input element",$name));
			$needs_client_error_message=$needs_server_error_message=0;
			if(IsSet($arguments["ValidateAsEmail"]))
			{
				if(IsSet($arguments["ValidateAsEmailErrorMessage"])
				&& strcmp($arguments["ValidateAsEmailErrorMessage"],""))
				$input["ValidateAsEmailErrorMessage"]=$arguments["ValidateAsEmailErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=$input["ValidateAsEmail"]=1;
			}
			if(IsSet($arguments["ValidateAsCreditCard"]))
			{
				switch($arguments["ValidateAsCreditCard"])
				{
					case "field":
						$field=(IsSet($arguments["ValidationCreditCardTypeField"]) ? $arguments["ValidationCreditCardTypeField"] : "");
						$field_type=(IsSet($this->inputs[$field]) ? $this->inputs[$field]["TYPE"] : "");
						switch($field_type)
						{
							case "text":
							case "select":
								break;
							case "radio":
							case "password":
							case "submit":
							case "image":
							case "reset":
							case "button":
							case "textarea":
							case "checkbox":
							case "hidden":
							default:
								return($this->OutputError("it was not specified valid validation credit card type field",$name));
						}
						$input["ValidationCreditCardTypeField"]=$arguments["ValidationCreditCardTypeField"];
					case "mastercard":
					case "visa":
					case "amex":
					case "dinersclub":
					case "carteblanche":
					case "discover":
					case "enroute":
					case "jcb":
					case "unknown":
						$input["ValidateAsCreditCard"]=$arguments["ValidateAsCreditCard"];
						break;
					default:
						return($this->OutputError("it was not specified valid credit card type",$name));
				}
				if(IsSet($arguments["ValidateAsCreditCardErrorMessage"])
				&& strcmp($arguments["ValidateAsCreditCardErrorMessage"],""))
				$input["ValidateAsCreditCardErrorMessage"]=$arguments["ValidateAsCreditCardErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateRegularExpression"]))
			{
				if(GetType($arguments["ValidateRegularExpression"])=="array")
				{
					$regular_expression=$arguments["ValidateRegularExpression"];
					if(IsSet($arguments["ValidateRegularExpressionErrorMessage"]))
					{
						if(count($arguments["ValidateRegularExpressionErrorMessage"])!=count($arguments["ValidateRegularExpression"]))
						return($this->OutputError("it was a matching number of regular expression validation error messages",$name));
						$input["ValidateRegularExpressionErrorMessage"]=$arguments["ValidateRegularExpressionErrorMessage"];
					}
					else
					{
						$needs_client_error_message++;
						$needs_server_error_message++;
					}
				}
				else
				{
					$regular_expression=array(strval($arguments["ValidateRegularExpression"]));
					if(IsSet($arguments["ValidateRegularExpressionErrorMessage"])
					&& strcmp($arguments["ValidateRegularExpressionErrorMessage"],""))
					$input["ValidateRegularExpressionErrorMessage"]=array($arguments["ValidateRegularExpressionErrorMessage"]);
					else
					{
						$needs_client_error_message++;
						$needs_server_error_message++;
					}
				}
				$input["ValidateRegularExpression"]=$regular_expression;
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsNotRegularExpression"]))
			{
				if(GetType($arguments["ValidateAsNotRegularExpression"])=="array")
				{
					$regular_expression=$arguments["ValidateAsNotRegularExpression"];
					if(IsSet($arguments["ValidateAsNotRegularExpressionErrorMessage"]))
					{
						if(count($arguments["ValidateAsNotRegularExpressionErrorMessage"])!=count($arguments["ValidateAsNotRegularExpression"]))
						return($this->OutputError("it was a matching number of regular expression validation error messages",$name));
						$input["ValidateAsNotRegularExpressionErrorMessage"]=$arguments["ValidateAsNotRegularExpressionErrorMessage"];
					}
					else
					{
						$needs_client_error_message++;
						$needs_server_error_message++;
					}
				}
				else
				{
					$regular_expression=array(strval($arguments["ValidateAsNotRegularExpression"]));
					if(IsSet($arguments["ValidateAsNotRegularExpressionErrorMessage"])
					&& strcmp($arguments["ValidateAsNotRegularExpressionErrorMessage"],""))
					$input["ValidateAsNotRegularExpressionErrorMessage"]=array($arguments["ValidateAsNotRegularExpressionErrorMessage"]);
					else
					{
						$needs_client_error_message++;
						$needs_server_error_message++;
					}
				}
				$input["ValidateAsNotRegularExpression"]=$regular_expression;
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsNotEmpty"]))
			{
				if(IsSet($arguments["ValidateAsNotEmptyErrorMessage"])
				&& strcmp($arguments["ValidateAsNotEmptyErrorMessage"],""))
				$input["ValidateAsNotEmptyErrorMessage"]=$arguments["ValidateAsNotEmptyErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=$input["ValidateAsNotEmpty"]=1;
			}
			if(IsSet($arguments["ValidateMinimumLength"]))
			{
				$minimum_length=intval($arguments["ValidateMinimumLength"]);
				if(strcmp(strval($minimum_length),$arguments["ValidateMinimumLength"])
				|| $minimum_length<=0)
				return($this->OutputError("it was not specified a valid minimum field length to validate",$name));
				$input["ValidateMinimumLength"]=$minimum_length;
				if(IsSet($arguments["ValidateMinimumLengthErrorMessage"])
				&& strcmp($arguments["ValidateMinimumLengthErrorMessage"],""))
				$input["ValidateMinimumLengthErrorMessage"]=$arguments["ValidateMinimumLengthErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsEqualTo"]))
			{
				if(!strcmp($arguments["ValidateAsEqualTo"],""))
				return($this->OutputError("it was not specified a valid comparision field",$name));
				$input["ValidateAsEqualTo"]=$arguments["ValidateAsEqualTo"];
				if(IsSet($arguments["ValidateAsEqualToErrorMessage"])
				&& strcmp($arguments["ValidateAsEqualToErrorMessage"],""))
				$input["ValidateAsEqualToErrorMessage"]=$arguments["ValidateAsEqualToErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsDifferentFrom"]))
			{
				if(!strcmp($arguments["ValidateAsDifferentFrom"],""))
				return($this->OutputError("it was not specified a valid comparision field",$name));
				$input["ValidateAsDifferentFrom"]=$arguments["ValidateAsDifferentFrom"];
				if(IsSet($arguments["ValidateAsDifferentFromErrorMessage"])
				&& strcmp($arguments["ValidateAsDifferentFromErrorMessage"],""))
				$input["ValidateAsDifferentFromErrorMessage"]=$arguments["ValidateAsDifferentFromErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsDifferentFromText"]))
			{
				$input["ValidateAsDifferentFromText"]=$arguments["ValidateAsDifferentFromText"];
				if(IsSet($arguments["ValidateAsDifferentFromTextErrorMessage"])
				&& strcmp($arguments["ValidateAsDifferentFromTextErrorMessage"],""))
				$input["ValidateAsDifferentFromTextErrorMessage"]=$arguments["ValidateAsDifferentFromTextErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsSet"]))
			{
				switch($arguments["TYPE"])
				{
					case "radio":
					case "checkbox":
					case "custom":
						break;
					case "select":
						if(IsSet($arguments["MULTIPLE"]))
						break;
					default:
						return($this->OutputError("it was not specified a valid field for as set validation",$name));
				}
				$input["ValidateAsSet"]=$arguments["ValidateAsSet"];
				if(IsSet($arguments["ValidateAsSetErrorMessage"])
				&& strcmp($arguments["ValidateAsSetErrorMessage"],""))
				$input["ValidateAsSetErrorMessage"]=$arguments["ValidateAsSetErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidateAsInteger"]))
			{
				if(IsSet($arguments["ValidationLowerLimit"]))
				{
					if(strcmp(GetType($limit=$arguments["ValidationLowerLimit"]),"integer"))
					return($this->OutputError("it was not specified a valid lower limit value",$name));
					$input["ValidationLowerLimit"]=$limit;
				}
				if(IsSet($arguments["ValidationUpperLimit"]))
				{
					if(strcmp(GetType($limit=$arguments["ValidationUpperLimit"]),"integer")
					|| $limit<$input["ValidationLowerLimit"])
					return($this->OutputError("it was not specified a valid upper limit value",$name));
					$input["ValidationUpperLimit"]=$limit;
				}
				if(IsSet($arguments["ValidateAsIntegerErrorMessage"])
				&& strcmp($arguments["ValidateAsIntegerErrorMessage"],""))
				$input["ValidateAsIntegerErrorMessage"]=$arguments["ValidateAsIntegerErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=$input["ValidateAsInteger"]=1;
			}
			if(IsSet($arguments["ValidateAsFloat"]))
			{
				if(IsSet($arguments["ValidationLowerLimit"]))
				{
					$limit_type=GetType($limit=$arguments["ValidationLowerLimit"]);
					if(strcmp($limit_type,"double")
					&& strcmp($limit_type,"integer"))
					return($this->OutputError("it was not specified a valid lower limit value",$name));
					$input["ValidationLowerLimit"]=$limit;
				}
				if(IsSet($arguments["ValidationUpperLimit"]))
				{
					$limit_type=GetType($limit=$arguments["ValidationUpperLimit"]);
					if((strcmp($limit_type,"double")
					&& strcmp($limit_type,"integer"))
					|| $limit<$input["ValidationLowerLimit"])
					return($this->OutputError("it was not specified a valid upper limit value",$name));
					$input["ValidationUpperLimit"]=$limit;
				}
				if(IsSet($arguments["ValidationDecimalPlaces"]))
				{
					if((strcmp(GetType($places=$arguments["ValidationDecimalPlaces"]),"integer")))
					return($this->OutputError("it was not specified a valid number of decimal places",$name));
					$input["ValidationDecimalPlaces"]=$places;
				}
				if(IsSet($arguments["ValidateAsFloatErrorMessage"])
				&& strcmp($arguments["ValidateAsFloatErrorMessage"],""))
				$input["ValidateAsFloatErrorMessage"]=$arguments["ValidateAsFloatErrorMessage"];
				else
				{
					$needs_client_error_message++;
					$needs_server_error_message++;
				}
				$input["ServerValidate"]=$input["ClientValidate"]=$input["ValidateAsFloat"]=1;
			}
			if(IsSet($arguments["ValidationClientFunction"]))
			{
				if(!strcmp($arguments["ValidationClientFunction"],""))
				return($this->OutputError("it was not specified a valid client validatation function",$name));
				$input["ValidationClientFunction"]=$arguments["ValidationClientFunction"];
				if(IsSet($arguments["ValidationClientFunctionErrorMessage"])
				&& strcmp($arguments["ValidationClientFunctionErrorMessage"],""))
				$input["ValidationClientFunctionErrorMessage"]=$arguments["ValidationClientFunctionErrorMessage"];
				else
				$needs_client_error_message++;
				$input["ClientValidate"]=1;
			}
			if(IsSet($arguments["ValidationServerFunction"]))
			{
				if(!strcmp($arguments["ValidationServerFunction"],""))
				return($this->OutputError("it was not specified a valid server validation function",$name));
				$input["ValidationServerFunction"]=$arguments["ValidationServerFunction"];
				if(IsSet($arguments["ValidationServerFunctionErrorMessage"])
				&& strcmp($arguments["ValidationServerFunctionErrorMessage"],""))
				$input["ValidationServerFunctionErrorMessage"]=$arguments["ValidationServerFunctionErrorMessage"];
				else
				$needs_server_error_message++;
				$input["ServerValidate"]=1;
			}
			if(IsSet($arguments["DiscardInvalidValues"]))
			$input["DiscardInvalidValues"]=!!$arguments["DiscardInvalidValues"];
			if(IsSet($arguments["ValidationErrorMessage"])
			&& strcmp($arguments["ValidationErrorMessage"],""))
			$input["ValidationErrorMessage"]=$arguments["ValidationErrorMessage"];
			else
			{
				if((!IsSet($input["DiscardInvalidValues"])
				|| !$input["DiscardInvalidValues"])
				&& ($needs_client_error_message>0
				|| $needs_server_error_message>0))
				return($this->OutputError("it was not specified a valid validate error message",$name));
			}
			$validate_only_on_client_side=(IsSet($arguments["ValidateOnlyOnClientSide"]) && $arguments["ValidateOnlyOnClientSide"]);
			$validate_only_on_server_side=(IsSet($arguments["ValidateOnlyOnServerSide"]) && $arguments["ValidateOnlyOnServerSide"]);
			if($validate_only_on_client_side
			&& $validate_only_on_server_side)
			return($this->OutputError("it was specified to validate the input field value either only on client side and only on server side",$name));
			switch(($input["TYPE"]=$arguments["TYPE"]))
			{
				case "file":
					if(IsSet($arguments["ACCEPT"]))
					$input["ACCEPT"]=$arguments["ACCEPT"];
				case "text":
					if(IsSet($arguments["MAXLENGTH"]))
					$input["MAXLENGTH"]=intval($arguments["MAXLENGTH"]);
					if(IsSet($arguments["SIZE"]))
					$input["SIZE"]=intval($arguments["SIZE"]);
					break;
				case "password":
					if(IsSet($arguments["MAXLENGTH"]))
					$input["MAXLENGTH"]=intval($arguments["MAXLENGTH"]);
					if(IsSet($arguments["SIZE"]))
					$input["SIZE"]=intval($arguments["SIZE"]);
					if(IsSet($arguments["Encoding"]))
					{
						if(!strcmp($input["Encoding"]=$arguments["Encoding"],""))
						return($this->OutputError("it was not defined a valid password encoding function",$name));
						if(IsSet($arguments["EncodedField"]))
						{
							if(!strcmp($input["EncodedField"]=$arguments["EncodedField"],""))
							return($this->OutputError("it was not defined a valid password encoded field",$name));
						}
						if(IsSet($arguments["EncodingFunctionVerification"]))
						{
							if(!strcmp($input["EncodingFunctionVerification"]=$arguments["EncodingFunctionVerification"],""))
							return($this->OutputError("it was not defined a valid password encoding function verification",$name));
						}
						else
						$input["EncodingFunctionVerification"]=$arguments["Encoding"];
						if(IsSet($arguments["EncodingFunctionScriptFile"]))
						{
							if(!strcmp($input["EncodingFunctionScriptFile"]=$arguments["EncodingFunctionScriptFile"],""))
							return($this->OutputError("it was not defined a valid password encoding function script file",$name));
						}
					}
					break;
				case "checkbox":
					if(IsSet($arguments["MULTIPLE"])
					&& $arguments["MULTIPLE"])
					$input["MULTIPLE"]=1;
				case "radio":
					if(IsSet($arguments["CHECKED"])
					&& $arguments["CHECKED"])
					$input["CHECKED"]=1;
					break;
				case "image":
					if(!IsSet($arguments["SRC"]))
					return($this->OutputError("it was not defined a valid image button source",$name));
					$input["SRC"]=$arguments["SRC"];
					if(IsSet($arguments["ALT"]))
					$input["ALT"]=$arguments["ALT"];
					if(IsSet($arguments["ALIGN"]))
					$input["ALIGN"]=$arguments["ALIGN"];
					break;
				case "submit":
				case "reset":
				case "hidden":
				case "button":
					break;
				case "textarea":
					if(IsSet($arguments["ROWS"]))
					{
						$value=$arguments["ROWS"];
						if(strcmp(GetType($value),"integer")
						|| $value<=0)
						return($this->OutputError("it was not defined a valid number of ROWS",$name));
						$input["ROWS"]=$value;
					}
					if(IsSet($arguments["COLS"]))
					{
						$value=$arguments["COLS"];
						if(strcmp(GetType($value),"integer")
						|| $value<=0)
						return($this->OutputError("it was not defined a valid number of COLS",$name));
						$input["COLS"]=$value;
					}
					break;
				case "select":
					if(IsSet($arguments["MULTIPLE"])
					&& $arguments["MULTIPLE"])
					{
						$input["MULTIPLE"]=1;
						$error=$this->SetSelectedOptions($name, $input, $arguments["OPTIONS"], $arguments["SELECTED"]);
					}
					else
					$error=$this->SetSelectedOptions($name, $input, $arguments["OPTIONS"], $arguments["VALUE"]);
					if(strlen($error))
					return($error);
					if(IsSet($arguments["SIZE"]))
					$input["SIZE"]=intval($arguments["SIZE"]);
					break;
				case "custom":
					if(!IsSet($arguments["CustomClass"]))
					return($this->OutputError("it was not defined the custom input class",$name));
					if(function_exists("class_exists")
					&& !class_exists($arguments["CustomClass"]))
					return($this->OutputError("it was specified an inexisting the custom input class ".$arguments["CustomClass"],$name));
					break;
				default:
					return($this->OutputError("it was not defined a supported input element",$name));
			}
			if(IsSet($arguments["VALUE"]))
			$input["VALUE"]=$arguments["VALUE"];
			if(IsSet($arguments["ReadOnlyMark"]))
			$input["ReadOnlyMark"]=$arguments["ReadOnlyMark"];
			if(IsSet($arguments["ReadOnlyMarkUnchecked"]))
			$input["ReadOnlyMarkUnchecked"]=$arguments["ReadOnlyMarkUnchecked"];
			if(IsSet($arguments["TABINDEX"]))
			$input["TABINDEX"]=intval($arguments["TABINDEX"]);
			if(IsSet($arguments["STYLE"]))
			$input["STYLE"]=$arguments["STYLE"];
			if(IsSet($arguments["CLASS"]))
			$input["CLASS"]=$arguments["CLASS"];
			if(IsSet($arguments["ApplicationData"]))
			$input["ApplicationData"]=$arguments["ApplicationData"];

			$events=array(
			"ONBLUR",
			"ONCHANGE",
			"ONCLICK",
			"ONDBLCLICK",
			"ONFOCUS",
			"ONKEYDOWN",
			"ONKEYPRESS",
			"ONKEYUP",
			"ONMOUSEDOWN",
			"ONMOUSEMOVE",
			"ONMOUSEOUT",
			"ONMOUSEOVER",
			"ONMOUSEUP",
			"ONSELECT"
			);
			for($e=0; $e<count($events); $e++)
			{
				$n=$events[$e];
				if(IsSet($arguments[$n]))
				$input["EVENTS"][$n]=$arguments[$n];
			}

			if(IsSet($arguments["ReplacePatterns"]))
			{
				if(GetType($arguments["ReplacePatterns"])!="array")
				return($this->OutputError("it was not specified valid ReplacePatterns array argument",$name));
				$input["ReplacePatterns"]=$arguments["ReplacePatterns"];
				if(IsSet($arguments["ReplacePatternsOnlyOnServerSide"])
				&& $arguments["ReplacePatternsOnlyOnServerSide"])
				$input["ReplacePatternsOnlyOnServerSide"]=$arguments["ReplacePatternsOnlyOnServerSide"];
				elseif(IsSet($arguments["ReplacePatternsOnlyOnClientSide"])
				&& $arguments["ReplacePatternsOnlyOnClientSide"])
				$input["ReplacePatternsOnlyOnClientSide"]=$arguments["ReplacePatternsOnlyOnClientSide"];
			}
			if(IsSet($arguments["Capitalization"]))
			{
				switch($arguments["Capitalization"])
				{
					case "uppercase":
					case "lowercase":
					case "words":
						$input["Capitalization"]=$arguments["Capitalization"];
						break;
					default:
						return($this->OutputError("it was not defined valid capitalization method attribute",$name));
				}
			}
			if(IsSet($arguments["LABEL"]))
			{
				if(strlen($arguments["LABEL"])==0)
				return($this->OutputError("it was not defined valid input LABEL",$name));
				$input["LABEL"]=$arguments["LABEL"];
			}
			if(IsSet($arguments["ACCESSKEY"]))
			{
				if(strlen($arguments["ACCESSKEY"])==0)
				return($this->OutputError("it was not defined valid input ACCESSKEY",$name));
				$input["ACCESSKEY"]=$arguments["ACCESSKEY"];
			}
			if(IsSet($arguments["LabelID"]))
			{
				if(strlen($arguments["LabelID"])==0)
				return($this->OutputError("it was not defined valid input LabelID",$name));
				$input["LabelID"]=$arguments["LabelID"];
			}
			if(IsSet($arguments["LabelCLASS"]))
			{
				if(strlen($arguments["LabelCLASS"])==0)
				return($this->OutputError("it was not defined valid input LabelCLASS",$name));
				$input["LabelCLASS"]=$arguments["LabelCLASS"];
			}
			if(IsSet($arguments["LabelSTYLE"]))
			{
				if(strlen($arguments["LabelSTYLE"])==0)
				return($this->OutputError("it was not defined valid input LabelSTYLE",$name));
				$input["LabelSTYLE"]=$arguments["LabelSTYLE"];
			}
			if(IsSet($arguments["ExtraAttributes"]))
			$input["ExtraAttributes"]=$arguments["ExtraAttributes"];
			if(IsSet($arguments["ClientScript"]))
			$input["ClientScript"]=$arguments["ClientScript"];
			if(IsSet($arguments["ValidateOptionalValue"]))
			$input["ValidateOptionalValue"]=$arguments["ValidateOptionalValue"];
			$current_parent=$this->current_parent;
			if(strlen($current_parent))
			{
				$input["parent"]=$current_parent;
				if(IsSet($this->inputs[$this->current_parent]["Accessible"])
				&& !IsSet($arguments["Accessible"]))
				$arguments["Accessible"]=$this->inputs[$this->current_parent]["Accessible"];
				if(strlen($this->inputs[$this->current_parent]["SubForm"])
				&& !IsSet($arguments["SubForm"]))
				$arguments["SubForm"]=$this->inputs[$this->current_parent]["SubForm"];
			}
			if(IsSet($arguments["Accessible"]))
			$input["Accessible"]=$arguments["Accessible"];
			$input["SubForm"]=(IsSet($arguments["SubForm"]) ? $arguments["SubForm"] : "");
			$this->inputs[$name]=$input;
			if(!strcmp($input["TYPE"],"custom"))
			{
				$this->inputs[$name]["object"]=new $arguments["CustomClass"];
				$this->inputs[$name]["object"]->custom_class=$arguments["CustomClass"];
				$this->inputs[$name]["object"]->input=$name;
				$this->current_parent=$name;
				$error=$this->inputs[$name]["object"]->AddInput($this, $arguments);
				$this->current_parent=$current_parent;
				if(strlen($error))
				{
					$this->OutputError($error,$name);
					UnSet($this->inputs[$name]);
					return($error);
				}
				$this->inputs[$name]["ClientValidate"]=$this->inputs[$name]["object"]->client_validate;
				$this->inputs[$name]["ServerValidate"]=$this->inputs[$name]["object"]->server_validate;
			}
			if(!IsSet($this->inputs[$name]["ClientValidate"])
			|| $validate_only_on_server_side)
			$this->inputs[$name]["ClientValidate"]=0;
			if(!IsSet($this->inputs[$name]["ServerValidate"])
			|| $validate_only_on_client_side)
			$this->inputs[$name]["ServerValidate"]=0;
			if($this->inputs[$name]["ServerValidate"])
			$this->server_validate=1;
			if(strlen($current_parent))
			$this->inputs[$current_parent]["object"]->AddChild($this, $name);
			return("");
		}

		Function AddDataPart($data)
		{
			if($this->capturing)
			{
				$data=ob_get_contents().$data;
				ob_end_clean();
				if(!$this->capturing=ob_start())
				return($this->OutputError("could not resume layout capturing","AddDataPart"));
			}
			if(strlen($data))
			{
				$this->parts[]=$data;
				$this->types[]="DATA";
			}
			return("");
		}

		Function SetInputElement($element)
		{
			if(IsSet($this->inputs[$element]["NAME"]))
			{
				$name=$this->inputs[$element]["NAME"];
				if(IsSet($this->input_elements[$name]))
				{
					$input_element="elements[".$this->EncodeJavascriptString($name)."]";
					if(count($this->input_elements[$name])==1)
					$this->inputs[$this->input_elements[$name][0]]["InputElement"]="elements[".$this->EncodeJavascriptString($this->inputs[$this->input_elements[$name][0]]["NAME"])."][0]";
					$input_element.="[".count($this->input_elements[$name])."]";
					$this->input_elements[$name][]=$element;
				}
				else
				{
			  $input_element=$name;
			  $this->input_elements[$name]=array($element);
				}
				$this->inputs[$element]["InputElement"]=$input_element;
			}
			else
			{
				if(strcmp($this->inputs[$element]["TYPE"],"custom")
				&& $this->inputs[$element]["ClientValidate"])
				return($this->OutputError("it was specified an unnamed input to validate",$element));
				if(IsSet($this->inputs[$element]["Encoding"]))
				return($this->OutputError("it was specified an unnamed input to encode",$element));
				$this->inputs[$element]["InputElement"]="";
			}
			return("");
		}

		Function AddInputPart($input)
		{
			if($this->capturing)
			$this->AddDataPart("");
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			if(!strcmp($this->inputs[$input]["TYPE"],"custom")
			&& strlen($error=$this->inputs[$input]["object"]->AddInputPart($this)))
			{
				$this->OutputError($error,$input);
				return($error);
			}
			if(IsSet($this->inputs[$input]["Accessible"]))
			{
				if(($read_only=!$this->inputs[$input]["Accessible"]))
				$type="READ_ONLY_INPUT";
				else
				{
					$type="ACCESSIBLE_INPUT";
					$this->accessible_parts++;
				}
			}
			else
			$type=(($read_only=$this->ReadOnly) ? "READ_ONLY_INPUT" : "INPUT");
			if(!$read_only)
			{
				if(strcmp($error=$this->SetInputElement($input),""))
				return($error);
				$this->input_parts[]=$input;
				if($this->inputs[$input]["ClientValidate"])
				$this->client_validate=1;
				if(!strcmp($this->inputs[$input]["TYPE"],"file"))
				$this->file_parts++;
			}
			$this->inputs[$input]["Part"]=count($this->parts);
			$this->parts[]=$input;
			$this->types[]=$type;
			return("");
		}

		Function AddInputHiddenPart($input)
		{
			if($this->capturing)
			$this->AddDataPart("");
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			if(!strcmp($this->inputs[$input]["TYPE"],"custom")
			&& strlen($error=$this->inputs[$input]["object"]->AddInputHiddenPart($this)))
			{
				$this->OutputError($error,$input);
				return($error);
			}
			if(strcmp($error=$this->SetInputElement($input),""))
			return($error);
			$this->inputs[$input]["Part"]=count($this->parts);
			$this->input_parts[]=$input;
			$this->parts[]=$input;
			$this->types[]="HIDDEN_INPUT";
			$this->hidden_parts++;
			return("");
		}

		Function AddLabelPart($arguments)
		{
			if($this->capturing)
			$this->AddDataPart("");
			if(!IsSet($arguments["FOR"])
			|| !strcmp($for=$label["FOR"]=$arguments["FOR"],"")
			|| !IsSet($this->inputs[$for])
			|| !IsSet($this->inputs[$for]["ID"]))
			return($this->OutputError("it was not specified a valid label FOR input ID","AddLabelPart"));
			if(IsSet($arguments["LABEL"]))
			$label["LABEL"]=$arguments["LABEL"];
			else
			{
				if(IsSet($this->inputs[$for]["LABEL"]))
				$label["LABEL"]=$this->inputs[$for]["LABEL"];
				else
				$label["LABEL"]="";
			}
			if(strlen($label["LABEL"])==0)
			return($this->OutputError("it was not specified a valid label",$for));
			if(IsSet($arguments["ACCESSKEY"]))
			$label["ACCESSKEY"]=$arguments["ACCESSKEY"];
			else
			{
				if(IsSet($this->inputs[$for]["ACCESSKEY"]))
				$label["ACCESSKEY"]=$this->inputs[$for]["ACCESSKEY"];
				else
				$label["ACCESSKEY"]="";
			}
			if(!strcmp($this->inputs[$for]["TYPE"],"custom"))
			{
				if(strlen($error=$this->inputs[$for]["object"]->AddLabelPart($this, $label)))
				{
					$this->OutputError($error, $for);
					return($error);
				}
				return("");
			}
			if(strlen($label["ACCESSKEY"])==0)
			Unset($label["ACCESSKEY"]);
			else
			{
				$lower=$this->tolower_function;
				$key=$lower($label["ACCESSKEY"]);
				if(!$this->allow_used_access_keys)
				{
					if(IsSet($this->label_access_keys[$key]))
					return($this->OutputError("it was specified label FOR input \"".$label["FOR"]."\" that was already specified for input \"".$this->label_access_keys[$key]."\"",$for));
				}
				if(IsSet($this->reserved_access_keys[$key]))
				return($this->OutputError("it was specified label FOR input \"".$label["FOR"]."\" that is already reserved for \"".$this->reserved_access_keys[$key]."\"",$for));
				$this->label_access_keys[$key]=$for;
			}
			if(IsSet($arguments["ID"]))
			$label["ID"]=$arguments["ID"];
			else
			{
				if(IsSet($this->inputs[$for]["LabelID"]))
				$label["ID"]=$this->inputs[$for]["LabelID"];
			}
			if(IsSet($arguments["STYLE"]))
			$label["STYLE"]=$arguments["STYLE"];
			else
			{
				if(IsSet($this->inputs[$for]["LabelSTYLE"]))
				$label["STYLE"]=$this->inputs[$for]["LabelSTYLE"];
			}
			if(IsSet($arguments["CLASS"]))
			$label["CLASS"]=$arguments["CLASS"];
			else
			{
				if(IsSet($this->inputs[$for]["LabelCLASS"]))
				$label["CLASS"]=$this->inputs[$for]["LabelCLASS"];
			}
			$this->parts[]=$label;
			$this->types[]=(($this->ReadOnly && (!IsSet($this->inputs[$for]["Accessible"]) || !$this->inputs[$for]["Accessible"])) ? "READ_ONLY_LABEL" : "LABEL");
			return("");
		}

		Function AddFunction($arguments)
		{
			if(!IsSet($arguments["Function"])
			|| !strcmp($name=$arguments["Function"],""))
			return($this->OutputError("it was not specified a valid function name",$name));
			if(IsSet($this->functions[$name]))
			return($this->OutputError("it was specified an already existing function",$name));
			$function=array();
			switch($function["Type"]=(IsSet($arguments["Type"]) ? $arguments["Type"] : ""))
			{
				case "focus":
				case "select":
				case "select_focus":
				case "disable":
				case "enable":
					if(IsSet($arguments["Element"])
					&& IsSet($this->inputs[$input=$arguments["Element"]]))
					{
						if(!strcmp($this->inputs[$input]["TYPE"],"custom"))
						{
							if(strlen($error=$this->inputs[$input]["object"]->AddFunction($this, $arguments)))
							$this->OutputError($error,$input);
							return($error);
						}
						if(IsSet($this->inputs[$input]["InputElement"])
						&& strcmp($this->inputs[$input]["InputElement"],""))
						{
							$function["Element"]=$input;
							break;
						}
					}
					return($this->OutputError("it was not specified a valid named form element to define a function",$name));
				case "void":
					break;
				default:
					return($this->OutputError("it was not specified a valid function type",$name));
			}
			$this->functions[$name]=$function;
			return("");
		}

		Function RemoveFunction($name)
		{
			if(!IsSet($this->functions[$name]))
			return($this->OutputError("it was not specified an existing function name",$name));
			Unset($this->functions[$name]);
			return("");
		}

		Function GetFormObject()
		{
			if(strlen($this->ID))
			return('document.getElementById('.$this->EncodeJavascriptString($this->ID).')');
			elseif(strlen($this->NAME))
			return('document.forms['.$this->EncodeJavascriptString($this->NAME).']');
			else
			return('');
		}

		Function GetJavascriptConnectionAction($form_object, $from, $to, $event, $action, &$context, &$javascript)
		{
			$javascript="";
			$type=$this->inputs[$to]["TYPE"];
			$error="it was requested an unsupported action ".$action." for ".$type." type inputs";
			if(strlen($form_object)==0)
			{
				$form_object=$this->GetFormObject();
				if(strlen($form_object)==0)
				{
					$this->OutputError("it was not specified a valid form object expression",$to);
					return("");
				}

			}
			switch($type)
			{
				case "button":
				case "checkbox":
				case "image":
				case "radio":
				case "reset":
				case "password":
				case "select":
				case "submit":
				case "text":
				case "textarea":
				case "file":
					switch($action)
					{
						case 'Click':
						case 'Focus':
						case 'Select':
						case 'Enable':
						case 'Disable':
							if(!$this->GetInputAction($form_object, $to, $action, $condition, $javascript))
							break;
							if(strlen($condition))
							$javascript='if('.$condition.') '.$javascript;
							return('');
					}
					break;
				case "custom":
					if(strlen($error=$this->inputs[$to]["object"]->GetJavascriptConnectionAction($this, $form_object, $from, $event, $action, $context, $javascript))==0)
					return("");
					break;
			}
			$this->OutputError($error,$to);
			return("");
		}

		Function GetFormEventActions($form_object, $event)
		{
			if(!IsSet($this->connections[$event]))
			return("");
			for($actions="", $c=0; $c <count($this->connections[$event]); $c++)
			{
				$to = $this->connections[$event][$c]["To"];
				$error=$this->GetJavascriptConnectionAction($form_object, '', $to, $event, $this->connections[$event][$c]["Action"], $this->connections[$event][$c]["Context"], $javascript);
				if(strlen($error))
				$form->OutputError($error, $to);
				elseif(strlen($javascript))
				{
					if(strlen($actions))
					$connection.="; ";
					$actions.=$javascript;
				}
			}
			return($actions);
		}

		Function GetEventActions($form_object, $from, $event)
		{
			if(!IsSet($this->inputs[$from]["Connections"][$event]))
			return("");
			for($actions="", $c=0; $c <count($this->inputs[$from]["Connections"][$event]); $c++)
			{
				$error=$this->GetJavascriptConnectionAction($form_object, $from, $this->inputs[$from]["Connections"][$event][$c]["To"], $event, $this->inputs[$from]["Connections"][$event][$c]["Action"], $this->inputs[$from]["Connections"][$event][$c]["Context"], $javascript);
				if(strlen($error))
				$form->OutputError($error, $from);
				elseif(strlen($javascript))
				{
					if(strlen($actions))
					$connection.="; ";
					$actions.=$javascript;
				}
			}
			return($actions);
		}

		Function OutputEventHandlingAttributes(&$input, $input_id, $onclick, $function)
		{
			$onchange="";
			if(IsSet($input["Capitalization"]))
			{
				switch($input["Capitalization"])
				{
					case "uppercase":
						$onchange.="if(new_value.toUpperCase) new_value=new_value.toUpperCase() ; ";
						break;
					case "lowercase":
						$onchange.="if(new_value.toLowerCase) new_value=new_value.toLowerCase() ; ";
						break;
					case "words":
						$onchange.="if(new_value.toLowerCase && new_value.toUpperCase) { for(var capitalize=true, position=0, capitalized_value='' ; position<new_value.length; position++) { character=new_value.charAt(position) ; if(character==' ' || character=='\\t' || character=='\\n' || character=='\\r') { capitalize=true } else { character=(capitalize ? character.toUpperCase() : character.toLowerCase()) ; capitalize=false } ; capitalized_value+=character } new_value=capitalized_value } ; ";
						break;
				}
			}
			if(IsSet($input["ReplacePatterns"])
			&& count($input["ReplacePatterns"])
			&& !IsSet($input["ReplacePatternsOnlyOnServerSide"]))
			{
				for($value="new_value",$pattern=0,Reset($input["ReplacePatterns"]);$pattern<count($input["ReplacePatterns"]);Next($input["ReplacePatterns"]),$pattern++)
				{
					$expression=Key($input["ReplacePatterns"]);
					$replacement=ereg_replace('\\\\([0-9])','$\\1',$input["ReplacePatterns"][$expression]);
					$value=$value.".replace(new RegExp(".$this->EscapeJavascriptRegularExpressions($expression).",'g'), ".$this->EncodeJavascriptString($replacement).")";
				}
				$onchange.="if(new_value.replace) { new_value=".$value."; } ; ";
			}
			if(strlen($onchange))
			$onchange="new_value=value; ".$onchange." if(new_value!=value) value=new_value ;";
			if(IsSet($input["EVENTS"]["ONCHANGE"]))
			$onchange.=$input["EVENTS"]["ONCHANGE"];
			if(strlen($actions=$this->GetEventActions("this.form", $input_id,"ONCHANGE")))
			{
				if(strlen($onchange))
				$onchange.="; ";
				$onchange.=$actions;
			}
			if(strlen($onchange))
			$function(" onchange=\"".$this->EncodeHtmlString($onchange)."\"");

			if(IsSet($input["EVENTS"]["ONCLICK"]))
			{
				if(strlen($onclick))
				$onclick.=" ; ";
				$onclick.=$input["EVENTS"]["ONCLICK"];
			}
			if(strlen($actions=$this->GetEventActions("this.form", $input_id,"ONCLICK")))
			{
				if(strlen($onclick))
				$onclick.="; ";
				$onclick.=$actions;
			}
			if(strlen($onclick))
			$function(" onclick=\"".$this->EncodeHtmlString($onclick)." ; return true\"");

			if(IsSet($input["EVENTS"]))
			{
				for($event=0, Reset($input["EVENTS"]);$event<count($input["EVENTS"]); $event++, Next($input["EVENTS"]))
				{
					$name=Key($input["EVENTS"]);
					switch($name)
					{
						case "ONCHANGE":
						case "ONCLICK":
							break;
						default:
							$action=$input["EVENTS"][$name];
							if(strlen($actions=$this->GetEventActions("this.form", $input_id,$name)))
							{
								if(strlen($action))
								$action.="; ";
								$action.=$actions;
							}
							if(strlen($action))
							$function(" ".strtolower($name)."=\"".$this->EncodeHtmlString($action)."\"");
					}
				}
			}
		}

		Function MergeStyles($style_1, $style_2)
		{
			$length=strlen($style_2=trim($style_2));
			if($length
			&& strcmp(substr($style_2, $length-1),";"))
			$style_2.="; ";
			return($style_2.$style_1);
		}

		Function OutputStyleAttributes(&$input, $function, $id)
		{
			$invalid=IsSet($this->Invalid[$id]);
			if($invalid
			&& IsSet($input["InvalidCLASS"]))
			$class=$input["InvalidCLASS"];
			elseif($invalid
			&& strlen($this->InvalidCLASS))
			$class= $this->InvalidCLASS;
			elseif(IsSet($input["CLASS"]))
			$class=$input["CLASS"];
			else
			$class="";
			if(strlen($class))
			$function(" class=\"".$this->EncodeHTMLString($class)."\"");
			if($invalid
			&& IsSet($input["InvalidSTYLE"]))
			$style=$input["InvalidSTYLE"];
			elseif($invalid
			&& strlen($this->InvalidSTYLE))
			$style=$this->InvalidSTYLE;
			else
			$style="";
			if(IsSet($input["STYLE"]))
			$style=$this->MergeStyles($style, $input["STYLE"]);
			if(strlen($style))
			$function(" style=\"".$this->EncodeHTMLString($style)."\"");
		}

		Function OutputExtraAttributes(&$attributes,$function)
		{
			if(IsSet($attributes))
			{
				for(Reset($attributes),$attribute=0;$attribute<count($attributes);Next($attributes),$attribute++)
				{
					$attribute_name=Key($attributes);
					$function(" ".$attribute_name."=\"".$this->EncodeHTMLString($attributes[$attribute_name])."\"");
				}
			}
		}

		Function OutputInput(&$input, $input_id, $input_read_only, $function, $eol, &$resubmit_condition)
		{
			switch($input["TYPE"])
			{
				case "textarea":
					if($input_read_only
					&& IsSet($input["ReadOnlyMark"]))
					{
						$function($input["ReadOnlyMark"]);
						break;
					}
					$function("<textarea name=\"".$this->EncodeHtmlString($input["NAME"])."\"");
					if(IsSet($input["ROWS"]))
					$function(" rows=\"".$input["ROWS"]."\"");
					if(IsSet($input["COLS"]))
					$function(" cols=\"".$input["COLS"]."\"");
					$this->OutputEventHandlingAttributes($input, $input_id, "", $function);
					if(IsSet($input["ID"]))
					$function(" id=\"".$this->EncodeHtmlString($input["ID"])."\"");
					if(IsSet($input["TABINDEX"])
					|| strcmp($tab_index_function=$this->accessibility_tab_index,""))
					$function(" tabindex=\"".(IsSet($input["TABINDEX"]) ? $input["TABINDEX"] : intval($tab_index_function($input_id)))."\"");
					if(IsSet($input["ACCESSKEY"]))
					$function(" accesskey=\"".$this->EncodeHtmlString($input["ACCESSKEY"])."\"");
					if($input_read_only)
					$function(" disabled=\"disabled\"");
					$this->OutputExtraAttributes($input["ExtraAttributes"],$function);
					$this->OutputStyleAttributes($input, $function, $input_id);
					$function(">");
					if(IsSet($input["VALUE"]))
					$function($this->EncodeHTMLString($input["VALUE"]));
					$function("</textarea>");
					break;
				case "select":
					if($input_read_only)
					{
						if(IsSet($input["ReadOnlyMark"]))
						{
							$function($input["ReadOnlyMark"]);
							break;
						}
						$function("<span".(IsSet($input["ID"]) ? " id=\"".$this->EncodeHtmlString($input["ID"])."\"" : ""));
						$this->OutputStyleAttributes($input, $function, $input_id);
						$function(">");
					}
					else
					{
						$function("<select name=\"".$this->EncodeHtmlString($input["NAME"]));
						if(IsSet($input["MULTIPLE"]))
						$function("[]\" multiple=\"multiple\"");
						else
						$function("\"");
						$this->OutputEventHandlingAttributes($input, $input_id, "", $function);
						if(IsSet($input["ID"]))
						$function(" id=\"".$this->EncodeHtmlString($input["ID"])."\"");
						if(IsSet($input["SIZE"]))
						$function(" size=\"".$input["SIZE"]."\"");
						if(IsSet($input["TABINDEX"])
						|| strcmp($tab_index_function=$this->accessibility_tab_index,""))
						$function(" tabindex=\"".(IsSet($input["TABINDEX"]) ? $input["TABINDEX"] : intval($tab_index_function($input_id)))."\"");
						$this->OutputStyleAttributes($input, $function, $input_id);
						$this->OutputExtraAttributes($input["ExtraAttributes"],$function);
						$function(">$eol");
					}
					$options=$input["OPTIONS"];
					for($space="",$option=0,Reset($options);$option<count($options);Next($options),$option++)
					{
						if(!$input_read_only)
						$function("<option value=\"".$this->EncodeHTMLString(Key($options))."\"");
						if(IsSet($input["MULTIPLE"]))
						{
							if(IsSet($input["SELECTED"][Key($options)]))
							{
								$function($input_read_only ? $space.$this->EncodeHTMLString($options[Key($options)]) : " selected=\"selected\"");
								if($input_read_only)
								$space=$this->OptionsSeparator;
							}
						}
						else
						{
							if(IsSet($input["VALUE"])
							&& !strcmp($input["VALUE"],Key($options)))
							$function($input_read_only ? $this->EncodeHTMLString($options[Key($options)]) : " selected=\"selected\"");
						}
						if(!$input_read_only)
						$function(">".$this->EncodeHTMLString($options[Key($options)])."</option>$eol");
					}
					if($input_read_only)
					$function("</span>");
					else
					$function("</select>");
					break;
				case "custom":
					break;
				default:
					if($input_read_only)
					{
						$output="";
						switch($input["TYPE"])
						{
							case "hidden":
								break;
							case "submit":
							case "image":
							case "reset":
							case "button":
								if(IsSet($input["ReadOnlyMark"]))
								$output=$input["ReadOnlyMark"];
								break;
							case "checkbox":
							case "radio":
								if(IsSet($input["CHECKED"]))
								$output=(IsSet($input["ReadOnlyMark"]) ? $input["ReadOnlyMark"] : (IsSet($input["VALUE"]) ? $this->EncodeHTMLString($input["VALUE"]) : "On"));
								else
								$output=(IsSet($input["ReadOnlyMarkUnchecked"]) ? $input["ReadOnlyMarkUnchecked"] : "");
								break;
							default:
								if(IsSet($input["VALUE"]))
								$output=(IsSet($input["ReadOnlyMark"]) ? $input["ReadOnlyMark"] : $this->EncodeHTMLString($input["VALUE"]));
								break;
						}
						if(strlen($output))
						{
							$function("<span".(IsSet($input["ID"]) ? " id=\"".$this->EncodeHtmlString($input["ID"])."\"" : ""));
							$this->OutputStyleAttributes($input, $function, $input_id);
							$function(">".$output."</span>");
						}
					}
					else
					{
						$function("<input type=\"".$input["TYPE"]."\"");
						if(IsSet($input["NAME"]))
						{
							$name=$input["NAME"];
							if(!strcmp($input["TYPE"],"checkbox")
							&& IsSet($input["MULTIPLE"]))
							$name.="[]";
							$function(" name=\"".$this->EncodeHTMLString($name)."\"");
						}
						$accessible_input=0;
						$onclick="";
						switch($input["TYPE"])
						{
							case "password":
							case "text":
								if(($input["TYPE"]!="password"
								|| $this->OutputPasswordValues)
								&& IsSet($input["VALUE"]))
								$function(" value=\"".$this->EncodeHTMLString($input["VALUE"])."\"");
							case "file":
								if(IsSet($input["ACCEPT"]))
								$function(" accept=\"".$this->EncodeHtmlString($input["ACCEPT"])."\"");
								if(IsSet($input["MAXLENGTH"]))
								$function(" maxlength=\"".$input["MAXLENGTH"]."\"");
								if(IsSet($input["SIZE"]))
								$function(" size=\"".$input["SIZE"]."\"");
								$accessible_input=1;
								break;
							case "checkbox":
							case "radio":
								if(IsSet($input["VALUE"]))
								$function(" value=\"".$this->EncodeHTMLString($input["VALUE"])."\"");
								if(IsSet($input["CHECKED"]))
								$function(" checked=\"checked\"");
								$accessible_input=1;
								break;
							case "image":
								$function(" src=\"".$this->EncodeHtmlString($input["SRC"])."\"");
								if(IsSet($input["ALT"]))
								$function(" alt=\"".$this->EncodeHTMLString($input["ALT"])."\"");
								if(IsSet($input["ALIGN"]))
								$function(" align=\"".$this->EncodeHTMLString($input["ALIGN"])."\"");
							case "submit":
								if(strcmp($resubmit_condition,""))
								$onclick="if(this.disabled || typeof(this.disabled)=='boolean') this.disabled=true ; ".$this->form_submitted_test_variable_name."=".$this->form_submitted_variable_name." ; ".$this->form_submitted_variable_name."=true ; ".$this->form_submitted_variable_name."=".$resubmit_condition." ; if(this.disabled || typeof(this.disabled)=='boolean') this.disabled=false";
								if(IsSet($input["SubForm"]))
								{
									if(strcmp($onclick,""))
									$onclick.=" ; ";
									if($this->client_validate)
									$onclick.=$this->sub_form_variable_name."='".$input["SubForm"]."'";
								}
							case "reset":
							case "button":
								$accessible_input=1;
								if(IsSet($input["VALUE"]))
								$function(" value=\"".$this->EncodeHtmlString($input["VALUE"])."\"");
								break;
							case "hidden":
								$function(" value=\"".(IsSet($input["VALUE"]) ? $this->EncodeHTMLString($input["VALUE"]) : "")."\"");
								break;
						}
						$this->OutputEventHandlingAttributes($input, $input_id, $onclick, $function);
						if(IsSet($input["ID"]))
						$function(" id=\"".$this->EncodeHtmlString($input["ID"])."\"");
						if($accessible_input
						&& (IsSet($input["TABINDEX"])
						|| strcmp($tab_index_function=$this->accessibility_tab_index,"")))
						$function(" tabindex=\"".(IsSet($input["TABINDEX"]) ? $input["TABINDEX"] : intval($tab_index_function($input_id)))."\"");
						if(strcmp($input["TYPE"],"hidden")
						&& IsSet($input["ACCESSKEY"]))
						$function(" accesskey=\"".$this->EncodeHtmlString($input["ACCESSKEY"])."\"");
						$this->OutputStyleAttributes($input, $function, $input_id);
						$this->OutputExtraAttributes($input["ExtraAttributes"],$function);
						$function(" />");
					}
					break;
			}
		}

		Function GetInputAction($form_object, $input, $action, &$condition, &$javascript)
		{
			$element=$form_object.'['.$this->EncodeJavascriptString($input).']';
			$condition='';
			switch($this->inputs[$input]["TYPE"])
			{
				case 'submit':
				case 'image':
				case 'reset':
				case 'button':
					$check=1;
					break;
				default:
					$check=0;
					break;
			}
			switch($action)
			{
				case 'Click':
					$condition=$element.'.click';
					$javascript=$element.'.click()';
					break;
				case 'Focus':
					if($check)
					$condition=$element.'.focus';
					$javascript=$element.'.focus()';
					break;
				case 'Select':
					if($check)
					$condition=$element.'.select';
					$javascript=$element.'.select()';
					break;
				case 'Select_focus':
					if($check)
					$condition=$element.'.select && '.$element.'focus';
					$javascript=$element.'.select(); '.$element.'.focus()';
					break;
				case 'Disable':
				case 'Enable':
					if($check)
					$condition=$element.'.disabled && typeof('.$element.'.disabled)==\'boolean\'';
					$javascript=$element.'.disabled='.(strcmp($action, 'disable') ? 'false' : 'true');
					break;
				default:
					$javascript='';
					return(0);
			}
			return(1);
		}

		Function OutputValidation(&$last_test, $last_optional_value_set, $last_optional_value, $last_field_value, $needs_sub_form, &$last_subform, $jseol, $field, $last_error_message, $resubmit_condition, $input_id)
		{
			if(!strlen($last_test))
			return("");
			$o="";
			if($last_optional_value_set)
			$last_test=$last_field_value."!=".$this->EncodeJavascriptString($last_optional_value).$jseol."\t&& (".$last_test.")";
			if($this->ShowAllErrors)
			$last_test="!i[".$this->EncodeJavascriptString($input_id)."]".$jseol."\t&& (".$last_test.")";
			if($needs_sub_form
			|| strcmp($last_subform,""))
			{
				$sub_form_test=$this->sub_form_variable_name."==".$this->EncodeJavascriptString($last_subform);
				if(strcmp($last_subform,""))
				$sub_form_test="(".$this->sub_form_variable_name."==''".$jseol."\t|| ".$sub_form_test.")";
				$last_test=$sub_form_test.$jseol."\t&& (".$last_test.")";
			}
			$o.=$last_test.")".$jseol."\t{".$jseol;
			if(IsSet($input["InvalidCLASS"]))
			$class=$input["InvalidCLASS"];
			elseif(strlen($this->InvalidCLASS))
			$class= $this->InvalidCLASS;
			else
			$class="";
			if(IsSet($this->inputs[$input_id]["InvalidSTYLE"]))
			$style=$this->inputs[$input_id]["InvalidSTYLE"];
			elseif(strlen($this->InvalidSTYLE))
			$style=$this->InvalidSTYLE;
			else
			$style="";
			if(IsSet($this->inputs[$input_id]["STYLE"]))
			$style=$this->MergeStyles($style, $this->inputs[$input_id]["STYLE"]);
			if(strlen($style)
			|| strlen($class))
			{
				$o.="\t\tr=".$field.$jseol;
				if(strlen($class))
				$o.="\t\tr.className=".$this->EncodeJavascriptString($class).$jseol;
				if(strlen($style))
				{
					$style=$this->EncodeJavascriptString($style);
					$o.="\t\ts=".$style.$jseol."\t\tif(r.currentStyle)".$jseol."\t\t\tr.style.cssText=s".$jseol."\t\telse".$jseol."\t\t\tr.setAttribute('style', s)".$jseol;
				}
			}
			if($this->ShowAllErrors)
			{
				$o.="\t\tif(e=='')".$jseol;
				$o.="\t\t\tf=".$this->EncodeJavascriptString($input_id).$jseol;
				$o.="\t\telse".$jseol;
				$o.="\t\t\te+=".$this->EncodeJavascriptString($jseol).$jseol;
				$o.="\t\te+=".$this->EncodeJavascriptString($this->ErrorMessagePrefix.$last_error_message.$this->ErrorMessageSuffix).$jseol;
				$o.="\t\ti[".$this->EncodeJavascriptString($input_id)."]=true;".$jseol;
			}
			else
			{
				$o.="\t\tif(".$field.".focus)".$jseol;
				$o.="\t\t\t".$field.".focus()".$jseol;
				$o.="\t\talert(".$this->EncodeJavascriptString($this->ErrorMessagePrefix.$last_error_message.$this->ErrorMessageSuffix).")".$jseol;
				if(strcmp($resubmit_condition,""))
				$o.="\t\t".$this->form_submitted_variable_name."=false".$jseol;
				$o.="\t\treturn false".$jseol;
			}
			$o.="\t}".$jseol;
			$last_test=$last_subform="";
			return($o);
		}

		Function Output($arguments)
		{
			$function="Output";
			if(!IsSet($arguments["Function"])
			|| !strcmp($function=$arguments["Function"],""))
			return($this->OutputError("it was not specified a valid output function",$function));
			if($this->client_validate
			&& !strcmp($this->ValidationFunctionName,""))
			return($this->OutputError("it was not specified a valid client validation function name","Output"));
			if(count($this->functions)
			&& !strcmp($this->NAME,""))
			return($this->OutputError("it was not specified a valid form name","Output"));
			$eol=(IsSet($arguments["EndOfLine"]) ? $arguments["EndOfLine"] : "");
			$resubmit_condition="";
			if(!$this->ReadOnly
			|| $this->hidden_parts
			|| $this->accessible_parts)
			{
				$method=strtolower($this->METHOD);
				$action=$this->ACTION;
				$encoding_type=$this->ENCTYPE;
				if($this->file_parts)
				{
					if(strcmp($method,"post"))
					$method="post";
					if(strlen($encoding_type)==0)
					$encoding_type="multipart/form-data";
				}
				if(strlen($method)==0)
				$method="post";
				$onsubmit=$this->ONSUBMIT;
				if(strlen($actions=$this->GetFormEventActions('this', 'ONSUBMIT')))
				{
					if(strlen($onsubmit))
					$onsubmit.='; ';
					$onsubmit.=$actions;
				}
				$onsubmitting=$this->ONSUBMITTING;
				if(strlen($actions=$this->GetFormEventActions('this', 'ONSUBMITTING')))
				{
					if(strlen($onsubmitting))
					$onsubmitting.='; ';
					$onsubmitting.=$actions;
				}
				$onreset=$this->ONRESET;
				if(strlen($actions=$this->GetFormEventActions('this', 'ONRESET')))
				{
					if(strlen($onreset))
					$onreset.='; ';
					$onreset.=$actions;
				}
				$function("<form method=\"".$this->EncodeHtmlString($method)."\" action=\"".$this->EncodeHtmlString($action)."\"");
				if(strcmp($this->ID,""))
				$function(" id=\"".$this->EncodeHtmlString($this->ID)."\"");
				if(strcmp($this->NAME,""))
				$function(" name=\"".$this->EncodeHtmlString($this->NAME)."\"");
				if(strcmp($this->TARGET,""))
				$function(" target=\"".$this->EncodeHtmlString($this->TARGET)."\"");
				if(strcmp($encoding_type,""))
				$function(" enctype=\"".$this->EncodeHtmlString($encoding_type)."\"");
				if(strcmp($this->form_submitted_variable_name,"")
				&& strcmp($this->form_submitted_test_variable_name,"")
				&& strcmp($this->ResubmitConfirmMessage,""))
				$resubmit_condition="(!".$this->form_submitted_test_variable_name." || confirm(".$this->EncodeJavascriptString($this->ResubmitConfirmMessage)."))";
				if(strlen($onsubmitting))
				$onsubmitting=($this->client_validate ? "if(".$this->ValidationFunctionName."(this)==false) return false; " : "").$onsubmitting."; return true";
				else
				$onsubmitting=($this->client_validate ? "return ".$this->ValidationFunctionName."(this)" : "");
				if(strlen($onsubmitting))
				$onsubmit.=(strlen($onsubmit) ? '; ' : '').$onsubmitting;
				if(strlen($onsubmit))
				$function(' onsubmit="'.$this->EncodeHTMLString($onsubmit).'"');
				if(strlen($onreset))
				$function(' onreset='.$this->EncodeHTMLString($this->ONRESET).'"');
				$this->OutputExtraAttributes($this->ExtraAttributes,$function);
				$function(">$eol");
			}
			if($this->client_validate
			|| strcmp($resubmit_condition,"")
			|| count($this->functions))
			{
				$jseol=(!strcmp($eol,"") ? "\n" : $eol);
				if(count($this->parts)
				&& $this->client_validate)
				{
					for($script_files=$password_fields=array(),$input_part=0;$input_part<count($this->input_parts);$input_part++)
					{
						$input=$this->inputs[$this->input_parts[$input_part]];
						$part_type=$this->types[$input["Part"]];
						if(!strcmp($input["TYPE"],"password")
						&& IsSet($input["Encoding"])
						&& (!strcmp($part_type,"INPUT")
						|| !strcmp($part_type,"ACCESSIBLE_INPUT")))
						{
							$password_fields[]=$this->input_parts[$input_part];
							if(IsSet($input["EncodingFunctionScriptFile"])
							&& !IsSet($script_files[$input["EncodingFunctionScriptFile"]]))
							{
								$function("<script type=\"text/javascript\" defer=\"defer\" src=\"".$this->EncodeHtmlString($input["EncodingFunctionScriptFile"])."\">\n</script>\n");
								$script_files[$input["EncodingFunctionScriptFile"]]=$this->input_parts[$input_part];
							}
						}
					}
				}
				$function("<script type=\"text/javascript\" defer=\"defer\">$eol<!--$jseol");
				if(strcmp($resubmit_condition,""))
				$function($this->form_submitted_variable_name."=false$jseol");
				if(count($this->parts)
				&& $this->client_validate)
				{
					for($needs_sub_form=0,$validate_as_email="",$validate_as_credit_card="",$part=0;$part<count($this->parts);$part++)
					{
						switch($this->types[$part])
						{
							case "INPUT":
							case "ACCESSIBLE_INPUT":
								if(IsSet($this->inputs[$this->parts[$part]]["ClientScript"]))
								$function($jseol.$this->inputs[$this->parts[$part]]["ClientScript"].$jseol);
								break;
						}
					}
					for($validate_as_email="",$validate_as_credit_card="",$part=0;$part<count($this->parts);$part++)
					{
						switch($this->types[$part])
						{
							case "INPUT":
							case "ACCESSIBLE_INPUT":
								$input=$this->inputs[$this->parts[$part]];
								if($input["ClientValidate"])
								{
									if(strcmp($input["SubForm"],""))
									$needs_sub_form=1;
									If(IsSet($input["ValidateAsEmail"]))
									{
										if(!strcmp($validate_as_email,""))
										{
											if(!IsSet($this->ValidateAsEmail)
											|| !strcmp($this->ValidateAsEmail,""))
											return($this->OutputError("it was not specified a valid validation as email function name","Output"));
											$validate_as_email=$this->ValidateAsEmail;
											$function("function ".$validate_as_email."(theinput)".$jseol."{".$jseol);
											$function("\tvar s=theinput.value".$jseol."\tif(s.search)".$jseol."\t{".$jseol."\t\treturn (s.search(new RegExp(".$this->EscapeJavascriptRegularExpressions($this->email_regular_expression).",'gi'))>=0)".$jseol."\t}".$jseol);
											$function("\tif(s.indexOf)".$jseol."\t{".$jseol."\t\tvar at_character=s.indexOf('@')".$jseol."\t\tif(at_character<=0 || at_character+4>s.length)".$jseol."\t\t\treturn false".$jseol."\t}".$jseol);
											$function("\tif(s.length<6)".$jseol."\t\treturn false".$jseol."\telse".$jseol."\t\treturn true".$jseol."}".$jseol);
										}
									}
									If(IsSet($input["ValidateAsCreditCard"]))
									{
										if(!strcmp($validate_as_credit_card,""))
										{
											if(!IsSet($this->ValidateAsCreditCard)
											|| !strcmp($this->ValidateAsCreditCard,""))
											return($this->OutputError("it was not specified a valid validation as credit card function name","Output"));
											$validate_as_credit_card=$this->ValidateAsCreditCard;
											$function("function ".$validate_as_credit_card."(theinput,cardtype)".$jseol."{".$jseol);
											$function("\tvar first, second, third, val=theinput.value".$jseol."\tvar len=val.length".$jseol."\tfor(var position=0; position<len; )".$jseol."\t{".$jseol."\t\tif(val.charAt(position)==' ' || val.charAt(position)=='.' || val.charAt(position)=='-')".$jseol."\t\t{".$jseol.$jseol."\t\t\tval=val.substring(0,position)+val.substring(position+1,len)".$jseol."\t\t\tlen=len-1".$jseol."\t\t}".$jseol."\t\telse".$jseol."\t\t\tposition++".$jseol."\t}".$jseol."\tif(len<13)".$jseol."\t\treturn false".$jseol);
											$function("\tif(cardtype!='unknown')".$jseol."\t{".$jseol);
											$function("\t\tif(isNaN(first=parseInt(val.charAt(0),10)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif(isNaN(second=parseInt(val.charAt(1),10)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif(isNaN(third=parseInt(val.charAt(2),10)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='mastercard') && (len!=16 || first!=5 || second<1 || second>5))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='visa') && ((len!=16 && len!=13) || first!=4))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='amex') && (len!=15 || first!=3 || (second!=4 && second!=7)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='dinersclub' || cardtype=='carteblanche') && (len!=14 || first!=3 || ((second!=0 || third<0 || third>5) && second!=6 && second!=8)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='discover') && (len!=16 || ((first!=5 || second<1 || second>5) && val.substring(0,4)!='6011')))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='enroute') && (len!=15 || (val.substring(0,4)!='2014' && val.substring(0,4)!='2149')))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif((cardtype=='jcb') && ((len!=16 || first!=3) && (len!=15 || (val.substring(0,4)!='2031' && val.substring(0,4)!='1800'))))".$jseol."\t\t\treturn false".$jseol);
											$function("\t}".$jseol);
											$function("\tfor(var check=0,position=1;position<=len;position++)".$jseol."\t{".$jseol);
											$function("\t\tif(isNaN(digit=parseInt(val.charAt(len-position),10)))".$jseol."\t\t\treturn false".$jseol);
											$function("\t\tif(!(position % 2))".$jseol."\t\t\tvar digit=parseInt('0246813579'.charAt(digit),10)".$jseol."\t\tcheck+=digit".$jseol);
											$function("\t}".$jseol."\treturn((check % 10)==0)".$jseol."}".$jseol);
										}
									}
								}
								else
								{
									if((!strcmp($input["TYPE"],"submit")
									|| !strcmp($input["TYPE"],"image"))
									&& strcmp($input["SubForm"],""))
									$needs_sub_form=1;
								}
								break;
						}
					}
					if($needs_sub_form)
					$function($jseol.$this->sub_form_variable_name."=''".$jseol.$jseol);
					$function("function ".$this->ValidationFunctionName."(theform)".$eol."{".$eol);
					$o="";
					for($invalid_styles=$custom=array(),$input_part=0;$input_part<count($this->input_parts);$input_part++)
					{
						$input_id=$this->input_parts[$input_part];
						if(!strcmp($this->inputs[$input_id]["TYPE"],"custom"))
						{
							if($this->inputs[$input_id]["ClientValidate"])
							$custom[]=$input_id;
							continue;
						}
						$input=$this->inputs[$input_id];
						$part_type=$this->types[$input["Part"]];
						if($input["ClientValidate"]
						&& (!strcmp($part_type,"INPUT")
						|| !strcmp($part_type,"ACCESSIBLE_INPUT")))
						{
							$validations=array("ValidateAsNotEmpty", "ValidateAsDifferentFromText", "ValidateMinimumLength", "ValidateRegularExpression", "ValidateAsNotRegularExpression", "ValidateAsInteger", "ValidateAsFloat", "ValidateAsEmail", "ValidateAsCreditCard", "ValidateAsEqualTo", "ValidateAsDifferentFrom", "ValidateAsSet", "ValidationClientFunction");
							for($last_optional_value_set=0,$last_optional_value=$last_field_value=$last_test=$last_subform=$last_error_message="",$last_field="",$validation=0,$validate_as_set=array();$validation<count($validations);$validation++)
							{
								if(IsSet($input[$validations[$validation]]))
								{
									$error_message=array(IsSet($input["ValidationErrorMessage"]) ? $input["ValidationErrorMessage"] : "");
									$input_id=$this->input_parts[$input_part];
									$field=$this->GetJavascriptInputObject("theform", $input_id);
									$field_value=$this->GetJavascriptInputValue("theform", $input_id);
									switch($validations[$validation])
									{
										case "ValidateRegularExpression":
											$e=$error_message[0];
											for($test=$error_message=array(), $r=0; $r<count($input["ValidateRegularExpression"]); $r++)
											{
												$test[$r]="(".$field.".value.search".$jseol."\t&& ".$field.".value.search(new RegExp(".$this->EscapeJavascriptRegularExpressions($input["ValidateRegularExpression"][$r]).",'g'))==-1)";
												$error_message[$r]=(IsSet($input["ValidateRegularExpressionErrorMessage"]) ? $input["ValidateRegularExpressionErrorMessage"][$r] : $e);
											}
											break;
										case "ValidateAsNotRegularExpression":
											$e=$error_message[0];
											for($test=$error_message=array(), $r=0; $r<count($input["ValidateAsNotRegularExpression"]); $r++)
											{
												$test[$r]="(".$field.".value.search".$jseol."\t&& ".$field.".value.search(new RegExp(".$this->EscapeJavascriptRegularExpressions($input["ValidateAsNotRegularExpression"][$r]).",'g'))!=-1)";
												$error_message[$r]=(IsSet($input["ValidateAsNotRegularExpressionErrorMessage"]) ? $input["ValidateAsNotRegularExpressionErrorMessage"][$r] : $e);
											}
											break;
										case "ValidateAsNotEmpty":
											if($input["TYPE"]=="select")
											$test=array($field.".selectedIndex==-1 || ".$field_value."==''");
											else
											$test=array($field_value."==''");
											if(IsSet($input["ValidateAsNotEmptyErrorMessage"]))
											$error_message=array($input["ValidateAsNotEmptyErrorMessage"]);
											break;
										case "ValidateAsDifferentFromText":
											$test=array($field_value."==".$this->EncodeJavascriptString($input["ValidateAsDifferentFromText"]));
											if(IsSet($input["ValidateAsDifferentFromTextErrorMessage"]))
											$error_message=array($input["ValidateAsDifferentFromTextErrorMessage"]);
											break;
										case "ValidateMinimumLength":
											if($input["TYPE"]=="select")
											$test=array($field.".selectedIndex==-1 || ".$field_value.".length<".$input["ValidateMinimumLength"]);
											else
											$test=array($field_value.".length<".$input["ValidateMinimumLength"]);
											if(IsSet($input["ValidateMinimumLengthErrorMessage"]))
											$error_message=array($input["ValidateMinimumLengthErrorMessage"]);
											break;
										case "ValidateAsEqualTo":
											$test=array($field_value."!=".$this->GetJavascriptInputValue("theform", $input["ValidateAsEqualTo"]));
											if(IsSet($input["ValidateAsEqualToErrorMessage"]))
											$error_message=array($input["ValidateAsEqualToErrorMessage"]);
											break;
										case "ValidateAsDifferentFrom":
											$test=array($field_value."==".$this->GetJavascriptInputValue("theform", $input["ValidateAsDifferentFrom"]));
											if(IsSet($input["ValidateAsDifferentFromErrorMessage"]))
											$error_message=array($input["ValidateAsDifferentFromErrorMessage"]);
											break;
										case "ValidateAsSet":
											switch($input["TYPE"])
											{
												case "radio":
													for($t="",$set_part=$validate_input=0;$validate_input<count($this->input_parts);$validate_input++)
													{
														$set_input=$this->inputs[$this->input_parts[$validate_input]];
														if(!strcmp($set_input["TYPE"],"radio")
														&& IsSet($set_input["NAME"])
														&& !strcmp($set_input["NAME"],$input["NAME"]))
														{
															if($set_part>0)
															$t.=" && ";
															$t.=$this->GetJavascriptInputObject("theform", $this->input_parts[$validate_input]).".checked==false";
															$set_part++;
														}
													}
													break;
												case "checkbox":
													if(IsSet($input["MULTIPLE"]))
													{
														for($t="",$set_part=$validate_input=0;$validate_input<count($this->input_parts);$validate_input++)
														{
															$id=$this->input_parts[$validate_input];
															$set_input=$this->inputs[$id];
															if(!strcmp($set_input["TYPE"],"checkbox")
															&& IsSet($set_input["MULTIPLE"])
															&& IsSet($set_input["NAME"])
															&& !strcmp($set_input["NAME"],$input["NAME"]))
															{
																if($set_part>0)
																$t.=" && ";
																$t.=$this->GetJavascriptInputObject("theform", $id).".checked==false";
																$set_part++;
															}
														}
													}
													else
													$t=$field.".checked==false";
													break;
												case "select":
													if(IsSet($input["MULTIPLE"]))
													$t=$field.".selectedIndex==-1";
													break;
											}
											$test=array($t);
											if(IsSet($input["ValidateAsSetErrorMessage"]))
											$error_message=array($input["ValidateAsSetErrorMessage"]);
											break;
										case "ValidateAsInteger":
											$t="parseInt(".$field_value.",10).toString()!=".$field_value;
											if(IsSet($input["ValidationLowerLimit"]))
											$t.="$jseol\t|| parseInt(".$field_value.",10) < ".$input["ValidationLowerLimit"];
											if(IsSet($input["ValidationUpperLimit"]))
											$t.="$jseol\t|| ".$input["ValidationUpperLimit"]." < parseInt(".$field_value.",10)";
											$test=array($t);
											if(IsSet($input["ValidateAsIntegerErrorMessage"]))
											$error_message=array($input["ValidateAsIntegerErrorMessage"]);
											break;
										case "ValidateAsFloat":
											$t="(".$field_value.".search".$jseol."\t&& ".$field_value.".search(new RegExp(".$this->EscapeJavascriptRegularExpressions(IsSet($input["ValidationDecimalPlaces"]) ? str_replace("PLACES", intval($input["ValidationDecimalPlaces"]), $this->decimal_regular_expression) : $this->float_regular_expression).",'g'))<0)".$jseol."\t|| "."isNaN(parseFloat(".$field_value."))";
											if(IsSet($input["ValidationLowerLimit"]))
											$t.="$jseol\t|| parseFloat(".$field_value.") < ".$input["ValidationLowerLimit"];
											if(IsSet($input["ValidationUpperLimit"]))
											$t.="$jseol\t|| ".$input["ValidationUpperLimit"]." < parseFloat(".$field_value.")";
											$test=array($t);
											if(IsSet($input["ValidateAsFloatErrorMessage"]))
											$error_message=array($input["ValidateAsFloatErrorMessage"]);
											break;
										case "ValidateAsCreditCard":
											if(strcmp($input["ValidateAsCreditCard"],"field"))
											$card_type=$this->EncodeJavascriptString($input["ValidateAsCreditCard"]);
											else
											{
												$card_type_field=$input["ValidationCreditCardTypeField"];
												$card_type=$this->GetJavascriptInputValue("theform", $card_type_field);
											}
											$test=array($validate_as_credit_card."(".$field.",".$card_type.")==false");
											if(IsSet($input["ValidateAsCreditCardErrorMessage"]))
											$error_message=array($input["ValidateAsCreditCardErrorMessage"]);
											break;
										case "ValidateAsEmail":
											$test=array($validate_as_email."(".$field.")==false");
											if(IsSet($input["ValidateAsEmailErrorMessage"]))
											$error_message=array($input["ValidateAsEmailErrorMessage"]);
											break;
										case "ValidationClientFunction":
											$test=array($input["ValidationClientFunction"]."(".$field.")==false");
											if(IsSet($input["ValidationClientFunctionErrorMessage"]))
											$error_message=array($input["ValidationClientFunctionErrorMessage"]);
											break;
									}
									$subform=(strcmp($input["SubForm"],"") ? $input["SubForm"] : "");
									$optional_value_set=IsSet($input["ValidateOptionalValue"]);
									$optional_value=($optional_value_set ? $input["ValidateOptionalValue"] : "");
									for($t=0; $t<count($test); $t++)
									{
										if(strcmp($last_error_message,$error_message[$t])
										|| strcmp($last_field,$field))
										{
											$o.=$this->OutputValidation($last_test, $last_optional_value_set, $last_optional_value, $last_field_value, $needs_sub_form, $last_subform, $jseol, $field, $last_error_message, $resubmit_condition, $input_id);
											$o.="\tif(";
											$last_error_message=$error_message[$t];
											$last_field=$field;
											$conditions=0;
										}
										else
										{
											if($conditions>0)
											$test=$jseol."\t|| ".$test[$t];
										}
										$conditions++;
										$last_test.=$test[$t];
										$last_subform=$subform;
										$last_optional_value_set=$optional_value_set;
										$last_optional_value=$optional_value;
										$last_field_value=$field_value;
									}
									if(!IsSet($invalid_styles[$field])
									&& (strlen($this->InvalidSTYLE)
									|| IsSet($this->inputs[$input_id]["InvalidSTYLE"])
									|| strlen($this->InvalidCLASS)
									|| IsSet($this->inputs[$input_id]["InvalidCLASS"])))
									$invalid_styles[$input_id]=$field;
								}
							}
							$o.=$this->OutputValidation($last_test, $last_optional_value_set, $last_optional_value, $last_field_value, $needs_sub_form, $last_subform, $jseol, $field, $last_error_message, $resubmit_condition, $input_id);
						}
					}
					$c=count($invalid_styles);
					if($this->ShowAllErrors)
					{
						$function("\tvar e='', i={}, f=''");
						if($c)
						$function(", r, s");
						$function($jseol);
					}
					if($c)
					{
						for($s="", $i=0, Reset($invalid_styles); $i<$c; Next($invalid_styles), $i++)
						{
							$input=Key($invalid_styles);
							$field=$invalid_styles[$input];
							$function("\tr=".$field.$jseol);
							if(strlen($this->InvalidCLASS)
							|| IsSet($this->inputs[$input]["InvalidCLASS"]))
							{
								$class=(IsSet($this->inputs[$input]["CLASS"]) ? $this->inputs[$input]["CLASS"] : "");
								$function("\tr.className=".$this->EncodeJavascriptString($class).$jseol);
							}
							if(strlen($this->InvalidSTYLE)
							|| IsSet($this->inputs[$input]["InvalidSTYLE"]))
							{
								$field=$invalid_styles[$input];
								$style=(IsSet($this->inputs[$input]["STYLE"]) ? $this->inputs[$input]["STYLE"] : "");
								if(!strlen($style))
								$style=";";
								$style=$this->EncodeJavascriptString($style);
								if(strcmp($s, $style))
								{
									$function("\ts=".$style.$jseol);
									$s=$style;
								}
								$function("\tif(r.currentStyle)".$jseol."\t\tr.style.cssText=s".$jseol."\telse".$jseol."\t\tr.setAttribute('style', s)".$jseol);
							}
						}
					}
					$function($o);
					if($this->ShowAllErrors)
					{
						$function("\tif(e!='')".$jseol);
						$function("\t{".$jseol);
						$function("\t\tif(theform[f].focus)".$jseol);
						$function("\t\t\ttheform[f].focus()".$jseol);
						$function("\t\talert(e)".$jseol);
						if(strcmp($resubmit_condition,""))
						$function("\t\t".$this->form_submitted_variable_name."=false".$jseol);
						$function("\t\treturn false".$jseol);
						$function("\t}".$jseol);
					}
					for($input_part=0;$input_part<count($custom);$input_part++)
					{
						$input=$custom[$input_part];
						if(strlen($error=$this->inputs[$input]["object"]->GetJavascriptValidations($this, "theform", $validations)))
						{
							$this->OutputError($error,$input);
							return($error);
						}
						for($validation=0;$validation<count($validations);$validation++)
						{
							if(IsSet($validations[$validation]["Commands"]))
							{
								for($command=0; $command<count($validations[$validation]["Commands"]); $command++)
								{
									if(strlen($validations[$validation]["Commands"][$command]))
									$function("\t".$validations[$validation]["Commands"][$command].$jseol);
								}
							}
							if(strlen($validations[$validation]["Condition"]))
							{
								if(strlen($validations[$validation]["ErrorMessage"])==0)
								{
									$this->OutputError($error="custom input did not generate a validation error message",$input);
									return($error);
								}
								$function("\tif(".$validations[$validation]["Condition"].")".$jseol."\t{".$jseol);
								$focus=(IsSet($validations[$validation]["Focus"]) ? $validations[$validation]["Focus"] : $this->inputs[$input]["object"]->focus_input);
								if(strlen($focus))
								{
									$field=$this->GetJavascriptInputObject("theform",$focus);
									$function("\t\tif(".$field.".focus)".$jseol);
									$function("\t\t\t".$field.".focus()".$jseol);
								}
								$function("\t\talert(".$this->EncodeJavascriptString($validations[$validation]["ErrorMessage"]).")".$jseol);
								if(strcmp($resubmit_condition,""))
								$function("\t\t".$this->form_submitted_variable_name."=false".$jseol);
								$function("\t\treturn false".$jseol."\t}".$jseol);
							}
						}
					}
					for($input_part=0;$input_part<count($password_fields);$input_part++)
					{
						$input=$this->inputs[$password_fields[$input_part]];
						$element_name="theform.".$input["InputElement"].".value";
						$function("\tif(".$input["EncodingFunctionVerification"].")\n");
						if(IsSet($input["EncodedField"]))
						{
							$encoded_field=$input["EncodedField"];
							if(!IsSet($this->inputs[$encoded_field]))
							return($this->OutputError("it was not defined the specified password encoded field",$encoded_field));
							if(!IsSet($this->inputs[$encoded_field]["InputElement"]))
							return($this->OutputError("it was not added the specified password encoded field",$encoded_field));
							if(!strcmp($this->inputs[$encoded_field]["InputElement"],""))
							return($this->OutputError("it was specified an unnamed password encoded field",$encoded_field));
							$function("\t{\n\t\ttheform.".$this->inputs[$encoded_field]["InputElement"].".value=".$input["Encoding"]."(".$element_name.")\n\t\t".$element_name."=''\n\t}\n");
						}
						else
						$function("\t{\n\t\t".$element_name."=".$input["Encoding"]."(".$element_name.")\n\t}\n");
					}
					$function("\treturn true".$jseol."}".$jseol);
				}
				if(count($this->functions))
				{
					$form_object=$this->GetFormObject();
					for($function_number=0,Reset($this->functions);$function_number<count($this->functions);Next($this->functions),$function_number++)
					{
						$function($jseol."function ".Key($this->functions)."()".$jseol."{".$jseol);
						$function_data=$this->functions[Key($this->functions)];
						if(strcmp($function_data["Type"],"void"))
						{
							if($this->GetInputAction($form_object, $function_data['Element'], ucfirst($function_data['Type']), $condition, $javascript))
							{
								if(strlen($condition))
								$function("\t".'if('.$condition.'.focus)'.$jseol);
								$function("\t\t".$javascript.$jseol);
							}
							else
							return($this->OutputError('function of type "'.$function_data['Type'].'" is not yet supported', Key($this->functions)));
						}
						$function("}".$jseol);
					}
				}
				$function("// -->".$eol."</script>".$eol."<noscript>".$eol."<div style=\"display: none\"><!-- dummy comment for user agents without Javascript support enabled --></div>".$eol."</noscript>".$eol);
			}
			for($part=0;$part<count($this->parts);$part++)
			{
				switch($this->types[$part])
				{
					case "DATA":
						$function($this->parts[$part]);
						break;
					case "ACCESSIBLE_INPUT":
					case "READ_ONLY_INPUT":
					case "INPUT":
						switch($this->types[$part])
						{
							case "ACCESSIBLE_INPUT":
								$input_read_only=0;
								break;
							case "READ_ONLY_INPUT":
								$input_read_only=1;
								break;
							case "INPUT":
								$input_read_only=$this->ReadOnly;
								break;
						}
						$this->OutputInput($this->inputs[$this->parts[$part]], $this->parts[$part], $input_read_only, $function, $eol, $resubmit_condition);
						break;
					case "HIDDEN_INPUT":
						$input=$this->inputs[$this->parts[$part]];
						Unset($value);
						$name=$input["NAME"];
						switch($input["TYPE"])
						{
							case "textarea":
							case "text":
							case "select":
							case "submit":
							case "image":
							case "reset":
							case "button":
							case "hidden":
							case "password":
								$value=(IsSet($input["VALUE"]) ? $input["VALUE"] : "");
								break;
							case "checkbox":
								if(IsSet($input["MULTIPLE"]))
								$name=$name.="[]";
							case "radio":
								if(IsSet($input["CHECKED"]))
								$value=(IsSet($input["VALUE"]) ? $input["VALUE"] : "on");
								break;
						}
						if(IsSet($value))
						{
							$function("<input type=\"hidden\"");
							if(IsSet($input["NAME"]))
							$function(" name=\"".$this->EncodeHtmlString($name)."\"");
							$function(" value=\"".$this->EncodeHTMLString($value)."\" />");
						}
						break;
					case "LABEL":
					case "READ_ONLY_LABEL":
						$label=$this->parts[$part];
						$style=(IsSet($label["ID"]) ? " id=\"".$this->EncodeHTMLString($label["ID"])."\"" : "").(IsSet($label["CLASS"]) ? " class=\"".$this->EncodeHTMLString($label["CLASS"])."\"" : "").(IsSet($label["STYLE"]) ? " style=\"".$this->EncodeHTMLString($label["STYLE"])."\"" : "");
				  if(($this->ReadOnly
				  || !strcmp($this->types[$part], "READ_ONLY_LABEL"))
				  && (!IsSet($this->inputs[$label["FOR"]]["Part"])
				  || $this->types[$this->inputs[$label["FOR"]]["Part"]]!="ACCESSIBLE_INPUT"))
						$function(strlen($style) ? "<span".$style.">".$this->parts[$part]["LABEL"]."</span>" : $this->parts[$part]["LABEL"]);
						else
						$function("<label for=\"".$this->EncodeHtmlString($label["FOR"])."\"".(IsSet($label["ACCESSKEY"]) ? " accesskey=\"".$this->EncodeHtmlString($label["ACCESSKEY"])."\"" : "").$style.">".$label["LABEL"]."</label>");
						break;
				}
			}
			if(!$this->ReadOnly
			|| $this->hidden_parts
			|| $this->accessible_parts)
			$function("</form>$eol");
			return("");
		}

		Function IsSetGlobal($variable)
		{
			return(IsSet($GLOBALS[$variable]));
		}

		Function GetGlobal($variable)
		{
			return($GLOBALS[$variable]);
		}

		Function SetGlobal($variable,$value)
		{
			$GLOBALS[$variable]=$value;
		}

		Function IsSetValue($variable,$file)
		{
			global $HTTP_POST_FILES,$HTTP_POST_VARS,$HTTP_GET_VARS;

			return(($file ? (IsSet($_FILES[$variable]) || IsSet($HTTP_POST_FILES[$variable])) : (IsSet($_GET[$variable]) || IsSet($HTTP_GET_VARS[$variable]) || IsSet($_POST[$variable]) || IsSet($HTTP_POST_VARS[$variable]))) || IsSet($GLOBALS[$variable]));
		}

		Function GetValue($variable,$file,$multiple=0)
		{
			global $HTTP_SERVER_VARS,$HTTP_POST_FILES,$HTTP_POST_VARS,$HTTP_GET_VARS;

			if($file)
			{
				if(IsSet($_FILES[$variable]))
				return($_FILES[$variable]["tmp_name"]);
				if(IsSet($HTTP_POST_FILES[$variable]))
				return($HTTP_POST_FILES[$variable]["tmp_name"]);
			}
			switch(IsSet($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : (IsSet($HTTP_SERVER_VARS["REQUEST_METHOD"]) ? $HTTP_SERVER_VARS["REQUEST_METHOD"] : ""))
			{
				case "POST":
					if(IsSet($_POST[$variable]))
					{
						$value=$_POST[$variable];
						break;
					}
					if(IsSet($HTTP_POST_VARS[$variable]))
					{
						$value=$HTTP_POST_VARS[$variable];
						break;
					}
				case "GET":
					if(IsSet($_GET[$variable]))
					{
						$value=$_GET[$variable];
						break;
					}
					if(IsSet($HTTP_GET_VARS[$variable]))
					{
						$value=$HTTP_GET_VARS[$variable];
						break;
					}
				default:
					$value=(IsSet($GLOBALS[$variable]) ? $GLOBALS[$variable] : "");
					break;
			}
			if($multiple)
			{
				if(GetType($value)!="array")
				return(array());
			}
			else
			{
				if(GetType($value)!="string")
				return("");
			}
			if(function_exists("ini_get")
			&& intval(ini_get("magic_quotes_gpc")))
			{
				if($multiple)
				{
					for($key=0;$key<count($value);$key++)
					$value[$key]=StripSlashes($value[$key]);
				}
				else
				$value=StripSlashes($value);
			}
			return($value);
		}

		Function SetValue($variable,$value)
		{
			global $HTTP_SERVER_VARS,$HTTP_POST_VARS,$HTTP_GET_VARS;

			switch(IsSet($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : $HTTP_SERVER_VARS["REQUEST_METHOD"])
			{
				case "POST":
					if(IsSet($_POST[$variable]))
					$_POST[$variable]=$value;
					if(IsSet($HTTP_POST_VARS[$variable]))
					$HTTP_POST_VARS[$variable]=$value;
					break;
				case "GET":
					if(IsSet($_GET[$variable]))
					$_GET[$variable]=$value;
					if(IsSet($HTTP_GET_VARS[$variable]))
					$HTTP_GET_VARS[$variable]=$value;
					break;
			}
			if(IsSet($GLOBALS[$variable]))
			$GLOBALS[$variable]=$value;
			return("");
		}

		Function GetFileValues($input,&$values)
		{
			global $HTTP_POST_FILES;

			if($this->inputs[$input]
			&& $this->inputs[$input]["TYPE"]=="file")
			{
				$values=(IsSet($_FILES[$input]) ? $_FILES[$input] : (IsSet($HTTP_POST_FILES[$input]) ? $HTTP_POST_FILES[$input] : ((IsSet($GLOBALS[$input]) && GetType($GLOBALS[$input])=="array") ? $GLOBALS[$input] : array())));
				if(IsSet($values["tmp_name"])
				&& is_uploaded_file($values["tmp_name"])
				&& IsSet($values["name"]))
				return($values["name"]);
			}
			$values=array();
			return("");
		}

		Function GetBooleanInputs()
		{
			for($this->checkbox_inputs=$this->radio_inputs=array(),$input_number=0,Reset($this->inputs);$input_number<count($this->inputs);Next($this->inputs),$input_number++)
			{
				$id=Key($this->inputs);
				switch($this->inputs[$id]["TYPE"])
				{
					case "radio":
						$this->radio_inputs[]=$id;
						break;
					case "checkbox":
						if(IsSet($this->inputs[$id]["MULTIPLE"]))
						$this->checkbox_inputs[]=$id;
						break;
				}
			}
		}

		Function ValidateInput($field, &$field_value, $sub_form="")
		{
			$input=$this->inputs[$field];
			$default_error=(IsSet($input["ValidationErrorMessage"]) ? $input["ValidationErrorMessage"] : "error");
			$input_error="";
			switch($input["TYPE"])
			{
				case "submit":
				case "image":
				case "reset":
				case "button":
					$value="";
					break;
				default:
					switch($input["TYPE"])
					{
						case "checkbox":
						case "radio":
							$value=(IsSet($field_value) ? $field_value : "on");
							break;
						default:
							$value=(IsSet($field_value) ? $field_value : "");
							break;
					}
					/*
					 $value=($this->IsSetValue($input["NAME"],$input["TYPE"]=="file") ? $value : "");
					 */
					break;
			}
			if((!strcmp($input["TYPE"],"select")
			|| $input["ServerValidate"])
			&& ((!$this->ReadOnly
			&& !IsSet($input["Accessible"]))
			|| (IsSet($input["Accessible"])
			&& $input["Accessible"]))
			&& (!strcmp($sub_form,"")
			|| !strcmp($sub_form,$input["SubForm"])))
			{
				switch(GetType($value))
				{
					case "integer":
					case "double":
						$value=strval($value);
					case "string":
						break;
					default:
						$value="";
						break;
				}
				if(IsSet($input["ValidateOptionalValue"])
				&& !strcmp($input["ValidateOptionalValue"],$value))
				return("");
				$validations=array("ValidateAsNotEmpty", "ValidateAsDifferentFromText", "ValidateMinimumLength", "ValidateRegularExpression", "ValidateAsNotRegularExpression", "ValidateAsInteger", "ValidateAsFloat", "ValidateAsEmail", "ValidateAsCreditCard", "ValidateAsEqualTo", "ValidateAsDifferentFrom", "ValidateAsSet", "ValidationServerFunction");
				for($validation=0;$validation<count($validations);$validation++)
				{
					if(IsSet($input[$validations[$validation]]))
					{
						switch($validations[$validation])
						{
							case "ValidateAsEmail":
								if(!eregi($this->email_regular_expression,$value))
								$input_error=(IsSet($input["ValidateAsEmailErrorMessage"]) ? $input["ValidateAsEmailErrorMessage"] : $default_error);
								break;
							case "ValidateAsCreditCard":
								$value=ereg_replace("[- .]","",$value);
								$len=strlen($value);
								$check=0;
								$input_error="";
								if(!strcmp($validation_type=$input["ValidateAsCreditCard"],"field"))
								{
									$type_field=$input["ValidationCreditCardTypeField"];
									$validation_type=(IsSet($this->inputs[$type_field]["VALUE"]) ? $this->inputs[$type_field]["VALUE"] : "");
								}
								else
								$type_field="";
								if($check==0
								&& $len<13)
								$check=1;
								else
								{
									$first=Ord($value[0])-Ord("0");
									$second=Ord($value[1])-Ord("0");
									$third=Ord($value[2])-Ord("0");
									switch($validation_type)
									{
										case "mastercard":
											if($len!=16
											|| $first!=5
											|| $second<1
											|| $second>5)
											$check=1;
											break;
										case "visa":
											if(($len!=16
											&& $len!=13)
											|| $first!=4)
											$check=1;
											break;
										case "amex":
											if($len!=15
											|| $first!=3
											|| ($second!=4
											&& $second!=7))
											$check=1;
											break;
										case "carteblanche":
										case "dinersclub":
											if($len!=14
											|| $first!=3
											|| (($second!=0
											|| $third<0
											|| $third>5)
											&& $second!=6
											&& $second!=8))
											$check=1;
											break;
										case "discover":
											if($len!=16
											|| (($first!=5
											|| $second<1
											|| $second>5)
											&& strcmp(substr($value,0,4),"6011")))
											$check=1;
											break;
										case "enroute":
											if($len!=15
											|| (substr($value,0,4)!="2014"
											&& substr($value,0,4)!="2149"))
											$check=1;
											break;
										case "jcb":
											if(($len!=16
											|| $first!=3)
											&& ($len!=15
											|| (substr($value,0,4)!="2031"
											&& substr($value,0,4)!="1800")))
											$check=1;
											break;
										case "unknown":
											break;
										default:
											if(strcmp($type_field,""))
											{
												$type_input=$this->inputs[$type_field];
												$input_error=(IsSet($type_input["ValidationErrorMessage"]) ? $type_input["ValidationErrorMessage"] : "error");
											}
											$check=1;
											break;
									}
								}
								if($check==0)
								{
									for($odd="0246813579",$zero=Ord("0"),$position=1;$position<=$len;$position++)
									{
										if(($digit=Ord($value[$len-$position])-$zero)>9
										|| $digit<0)
										{
											$check=1;
											break;
										}
										if(!($position % 2))
										$digit=intval($odd[$digit]);
										$check+=$digit;
									}
									$check%=10;
								}
								if($check
								&& !strcmp($input_error,""))
								$input_error=(IsSet($input["ValidateAsCreditCardErrorMessage"]) ? $input["ValidateAsCreditCardErrorMessage"] : $default_error);
								break;
							case "ValidateRegularExpression":
								for($r=0; $r<count($input["ValidateRegularExpression"]); $r++)
								{
									if(!ereg($input["ValidateRegularExpression"][$r],$value))
									{
										$input_error=(IsSet($input["ValidateRegularExpressionErrorMessage"]) ? $input["ValidateRegularExpressionErrorMessage"][$r] : $default_error);
										break;
									}
								}
								break;
							case "ValidateAsNotRegularExpression":
								for($r=0; $r<count($input["ValidateAsNotRegularExpression"]); $r++)
								{
									if(ereg($input["ValidateAsNotRegularExpression"][$r],$value))
									{
										$input_error=(IsSet($input["ValidateAsNotRegularExpressionErrorMessage"]) ? $input["ValidateAsNotRegularExpressionErrorMessage"][$r] : $default_error);
										break;
									}
								}
								break;
							case "ValidateAsNotEmpty":
								if($input["TYPE"]=="file" ? (strlen($this->GetFileValues($input["NAME"],$file_values))==0 || $file_values["size"]==0) : (strlen($value)==0))
								$input_error=(IsSet($input["ValidateAsNotEmptyErrorMessage"]) ? $input["ValidateAsNotEmptyErrorMessage"] : $default_error);
								break;
							case "ValidateAsDifferentFromText":
								if(!strcmp($value, $input["ValidateAsDifferentFromText"]))
								$input_error=(IsSet($input["ValidateAsDifferentFromTextErrorMessage"]) ? $input["ValidateAsDifferentFromTextErrorMessage"] : $default_error);
								break;
							case "ValidateMinimumLength":
								if(strlen($value)<$input["ValidateMinimumLength"])
								$input_error=(IsSet($input["ValidateMinimumLengthErrorMessage"]) ? $input["ValidateMinimumLengthErrorMessage"] : $default_error);
								break;
							case "ValidateAsEqualTo":
								$compare=(IsSet($this->inputs[$input["ValidateAsEqualTo"]]["VALUE"]) ? $this->inputs[$input["ValidateAsEqualTo"]]["VALUE"] : "");
								if(strcmp($compare,$value))
								$input_error=(IsSet($input["ValidateAsEqualToErrorMessage"]) ? $input["ValidateAsEqualToErrorMessage"] : $default_error);
								break;
							case "ValidateAsDifferentFrom":
								$compare=(IsSet($this->inputs[$input["ValidateAsDifferentFrom"]]["VALUE"]) ? $this->inputs[$input["ValidateAsDifferentFrom"]]["VALUE"] : "");
								if(!strcmp($compare,$value))
								$input_error=(IsSet($input["ValidateAsDifferentFromErrorMessage"]) ? $input["ValidateAsDifferentFromErrorMessage"] : $default_error);
								break;
							case "ValidateAsSet":
								$invalid=0;
								switch($input["TYPE"])
								{
									case "radio":
										if(IsSet($input["CHECKED"]))
										break;
										if(!IsSet($this->radio_inputs))
										$this->GetBooleanInputs();
										for($validate_input=0;$validate_input<count($this->radio_inputs);$validate_input++)
										{
											$set_input=$this->inputs[$this->radio_inputs[$validate_input]];
											if(IsSet($set_input["NAME"])
											&& !strcmp($set_input["NAME"],$input["NAME"])
											&& IsSet($set_input["CHECKED"]))
											break;
										}
										if($validate_input>=count($this->radio_inputs))
										$invalid=1;
										break;
									case "checkbox":
										if(IsSet($input["MULTIPLE"]))
										{
											if(IsSet($input["CHECKED"]))
											break;
											if(!IsSet($this->checkbox_inputs))
											$this->GetBooleanInputs();
											for($validate_input=0;$validate_input<count($this->checkbox_inputs);$validate_input++)
											{
												$set_input=$this->inputs[$this->checkbox_inputs[$validate_input]];
												if(IsSet($set_input["NAME"])
												&& !strcmp($set_input["NAME"],$input["NAME"])
												&& IsSet($set_input["CHECKED"]))
												break;
											}
											if($validate_input>=count($this->checkbox_inputs))
											$invalid=1;
											break;
										}
									default:
										if(!$this->IsSetValue($input["NAME"],0))
										$invalid=1;
										break;
								}
								if($invalid)
								$input_error=(IsSet($input["ValidateAsSetErrorMessage"]) ? $input["ValidateAsSetErrorMessage"] : $default_error);
								break;
							case "ValidateAsInteger":
								$integer_value=intval($value);
								if(strcmp($value,strval($integer_value))
								|| (IsSet($input["ValidationLowerLimit"])
								&& $integer_value<$input["ValidationLowerLimit"])
								|| (IsSet($input["ValidationUpperLimit"])
								&& $integer_value>$input["ValidationUpperLimit"]))
								$input_error=(IsSet($input["ValidateAsIntegerErrorMessage"]) ? $input["ValidateAsIntegerErrorMessage"] : $default_error);
								break;
							case "ValidateAsFloat":
								$float_value=doubleval($value);
								if(!ereg(IsSet($input["ValidationDecimalPlaces"]) ? str_replace("PLACES", intval($input["ValidationDecimalPlaces"]), $this->decimal_regular_expression) : $this->float_regular_expression,$value)
								|| (IsSet($input["ValidationLowerLimit"])
								&& $float_value<$input["ValidationLowerLimit"])
								|| (IsSet($input["ValidationUpperLimit"])
								&& $float_value>$input["ValidationUpperLimit"]))
								$input_error=(IsSet($input["ValidateAsFloatErrorMessage"]) ? $input["ValidateAsFloatErrorMessage"] : $default_error);
								break;
							case "ValidationServerFunction":
								if(!$input["ValidationServerFunction"]($value))
								$input_error=(IsSet($input["ValidationServerFunctionErrorMessage"]) ? $input["ValidationServerFunctionErrorMessage"] : $default_error);
								break;
						}
						if(strcmp($input_error,""))
						break;
					}
				}
				if(!strcmp($input_error,"")
				&& !strcmp($input["TYPE"],"select"))
				{
					if(IsSet($input["MULTIPLE"]))
					{
						if(IsSet($input["ValidateAsSet"])
						&& (!IsSet($input["SELECTED"])
						|| count($input["SELECTED"])==0))
						$input_error=(IsSet($input["ValidateAsSetErrorMessage"]) ? $input["ValidateAsSetErrorMessage"] : $default_error);
					}
					else
					{
						if($this->IsSetValue($input["NAME"],0)
						&& !IsSet($input["OPTIONS"][$this->GetValue($input["NAME"],0)])
						&& IsSet($input["ValidationErrorMessage"]))
						$input_error=$default_error;
					}
				}
			}
			return($input_error);
		}

		Function Validate(&$verify,$sub_form="")
		{
			$this->Invalid = array();
			for($inputs=array(), $input_number=0, Reset($this->inputs);$input_number<count($this->inputs);Next($this->inputs), $input_number++)
			$inputs[]=Key($this->inputs);
			for($invalid_parents=$custom=array(),$error="",$input_number=0;$input_number<count($inputs);$input_number++)
			{
				$field=$inputs[$input_number];
				if(strcmp($this->inputs[$field]["TYPE"],"custom"))
				{
					if(!IsSet($this->inputs[$field]["DiscardInvalidValues"])
					|| !$this->inputs[$field]["DiscardInvalidValues"])
					{
						$input_error=$this->ValidateInput($field, $this->inputs[$field]["VALUE"], $sub_form);
						if(strlen($input_error))
						{
							if(IsSet($this->inputs[$field]["parent"]))
							{
								$parent=$this->inputs[$field]["parent"];
								$invalid_parents[$parent]=$field;
								$invalid_field=$parent;
							}
							else
							$invalid_field=$field;
							if(strlen($error)==0)
							$error=$this->ErrorMessagePrefix.$input_error.$this->ErrorMessageSuffix;
							elseif($this->ShowAllErrors)
							$error.=$this->end_of_line.$this->ErrorMessagePrefix.$input_error.$this->ErrorMessageSuffix;
							$verify[$invalid_field]=(IsSet($this->inputs[$invalid_field]["SubForm"]) ? $this->inputs[$invalid_field]["SubForm"] : "");
							$this->Invalid[$invalid_field]=$input_error;
						}
					}
				}
				else
				$custom[]=$field;
			}
			for($input_number=0;$input_number<count($custom);$input_number++)
			{
				$field=$custom[$input_number];
				if(!IsSet($invalid_parents[$field])
				&& (!IsSet($this->inputs[$field]["DiscardInvalidValues"])
				|| !$this->inputs[$field]["DiscardInvalidValues"])
				&& $this->inputs[$field]["ServerValidate"])
				{
					$input_error=$this->inputs[$field]["object"]->ValidateInput($this);
					if(strlen($input_error))
					{
						if(strlen($error)==0)
						$error=$input_error;
						elseif($this->ShowAllErrors)
						$error.=$this->end_of_line.$input_error;
						$verify[$field]=$this->inputs[$field]["SubForm"];
						$this->Invalid[$field]=$input_error;
					}
				}
			}
			return($error);
		}

		Function LoadInputValues($submitted=0)
		{
			for($radio=array(),$input_number=0,Reset($this->inputs);$input_number<count($this->inputs);Next($this->inputs),$input_number++)
			{
				$key=Key($this->inputs);
				if(!strcmp($this->inputs[$key]["TYPE"],"radio"))
				{
					$name=$this->inputs[$key]["NAME"];
					$value=(IsSet($this->inputs[$key]["VALUE"]) ? $this->inputs[$key]["VALUE"] : "on");
					if(IsSet($radio[$name]))
					$radio[$name][$value]=$key;
					else
					$radio[$name]=array($value=>$key);
				}
			}
			for($custom=$this->Changes=array(),$input_number=0,Reset($this->inputs);$input_number<count($this->inputs);Next($this->inputs),$input_number++)
			{
				$key=Key($this->inputs);
				if((IsSet($this->inputs[$key]["Accessible"]) ? !$this->inputs[$key]["Accessible"] : $this->ReadOnly))
				continue;
				$name=(IsSet($this->inputs[$key]["NAME"]) ? $this->inputs[$key]["NAME"] : '');
				switch($this->inputs[$key]["TYPE"])
				{
					case "submit":
					case "image":
					case "reset":
					case "button":
						break;
					case "radio":
						$radio_value=(IsSet($this->inputs[$key]["VALUE"]) ? $this->inputs[$key]["VALUE"] : "on");
						if($this->IsSetValue($name,0))
						{
							$value=$this->GetValue($name,0);
							if(!strcmp(strtolower($value),strtolower($radio_value)))
							{
								if(!IsSet($this->inputs[$key]["CHECKED"]))
								$this->Changes[$key]="";
								$this->inputs[$key]["CHECKED"]=1;
							}
							else
							{
								if(IsSet($radio[$name][$value]))
								{
									if(IsSet($this->inputs[$key]["CHECKED"]))
									$this->Changes[$key]=$radio_value;
									Unset($this->inputs[$key]["CHECKED"]);
								}
							}
						}
						elseif($submitted
						&& IsSet($this->inputs[$key]["CHECKED"]))
						{
							$this->Changes[$key]=$radio_value;
							Unset($this->inputs[$key]["CHECKED"]);
						}
						break;
					case "checkbox":
						$checkbox_value=(IsSet($this->inputs[$key]["VALUE"]) ? $this->inputs[$key]["VALUE"] : "on");
						if(IsSet($this->inputs[$key]["MULTIPLE"]))
						{
							$value=($this->IsSetValue($name,0) ? $this->GetValue($name,0,1) : array());
							if(GetType($value)=="array")
							{
								$checked=0;
								foreach($value as $item)
								{
									if(!strcmp($item,$checkbox_value))
									{
										$checked=1;
										break;
									}
								}
							}
						}
						else
						$checked=$this->IsSetValue($name,0);
						if($checked)
						{
							if(!IsSet($this->inputs[$key]["CHECKED"]))
							$this->Changes[$key]="";
							$this->inputs[$key]["CHECKED"]=1;
						}
						else
						{
							if($submitted)
							{
								if(IsSet($this->inputs[$key]["CHECKED"]))
								$this->Changes[$key]=$checkbox_value;
								Unset($this->inputs[$key]["CHECKED"]);
							}
						}
						break;
					case "select":
						if(IsSet($this->inputs[$key]["MULTIPLE"]))
						{
							if($submitted)
							{
								$this->Changes[$key]=$this->inputs[$key]["SELECTED"];
								for($value_key=0, Reset($this->Changes[$key]); $value_key<count($this->Changes[$key]); Next($this->Changes[$key]), $value_key++)
								$this->Changes[$key][Key($this->Changes[$key])]=0;
								$value=($this->IsSetValue($name,0) ? $this->GetValue($name,0,1) : array());
								$this->inputs[$key]["SELECTED"]=array();
								if(GetType($value)=="array")
								{
									for($value_key=0,Reset($value);$value_key<count($value);Next($value),$value_key++)
									{
										$entry_value=$value[Key($value)];
										if(IsSet($this->inputs[$key]["OPTIONS"][$entry_value])
										|| (IsSet($this->inputs[$key]["DiscardInvalidValues"])
										&& !$this->inputs[$key]["DiscardInvalidValues"]))
										{
											if(IsSet($this->Changes[$key][$entry_value]))
											Unset($this->Changes[$key][$entry_value]);
											else
											$this->Changes[$key][$entry_value]=1;
											$this->inputs[$key]["SELECTED"][$entry_value]=1;
										}
									}
								}
								if(count($this->Changes[$key])==0)
								Unset($this->Changes[$key]);
							}
						}
						else
						{
							$previous_value=$this->inputs[$key]["VALUE"];
							if($this->IsSetValue($name,0))
							{
								$value=$this->GetValue($name,0);
								if(IsSet($this->inputs[$key]["OPTIONS"][$value])
								|| (IsSet($this->inputs[$key]["DiscardInvalidValues"])
								&& !$this->inputs[$key]["DiscardInvalidValues"]))
								$this->inputs[$key]["VALUE"]=$value;
							}
							if(strcmp($this->inputs[$key]["VALUE"],$previous_value))
							$this->Changes[$key]=$previous_value;
						}
						break;
					case "custom":
						$custom[]=$key;
						break;
					default:
						if($this->IsSetValue($name,$this->inputs[$key]["TYPE"]=="file"))
						{
							$value=$this->GetValue($name,$this->inputs[$key]["TYPE"]=="file");
							switch(GetType($value))
							{
								case "string":
								case "integer":
								case "double":
									$set_value=0;
									switch($this->inputs[$key]["TYPE"])
									{
										case "text":
										case "file":
										case "password":
											if(IsSet($this->inputs[$key]["MAXLENGTH"]))
											{

												$max_length=$this->inputs[$key]["MAXLENGTH"];
												if(strlen($value)>$max_length)
												{
													$value=substr($value,0,$max_length);
													$set_value=1;
												}
											}
											break;
									}
									if(IsSet($this->inputs[$key]["Capitalization"]))
									{
										switch($this->inputs[$key]["Capitalization"])
										{
											case "uppercase":
												$function=$this->toupper_function;
												$value=$function($value);
												$set_value=1;
												break;
											case "lowercase":
												$function=$this->tolower_function;
												$value=$function($value);
												$set_value=1;
												break;
											case "words":
												$lower_function=$this->tolower_function;
												$upper_function=$this->toupper_function;
												for($word=1,$position=0;$position<strlen($value);$position++)
												{
													switch($character=$value[$position])
													{
														case " ":
														case "\t":
														case "\n":
														case "\r":
															$word=1;
															break;
														default:
															$value[$position]=($word ? $upper_function($character) : $lower_function($character));
															$word=0;
															break;
													}
												}
												$set_value=1;
												break;
										}
									}
									if(IsSet($this->inputs[$key]["ReplacePatterns"])
									&& count($this->inputs[$key]["ReplacePatterns"])
									&& !IsSet($input["ReplacePatternsOnlyOnClientSide"]))
									{
										for($pattern=0,Reset($this->inputs[$key]["ReplacePatterns"]);$pattern<count($this->inputs[$key]["ReplacePatterns"]);Next($this->inputs[$key]["ReplacePatterns"]),$pattern++)
										{
											$expression=Key($this->inputs[$key]["ReplacePatterns"]);
											$value=ereg_replace($expression,$this->inputs[$key]["ReplacePatterns"][$expression],$value);
											$set_value=1;
										}
									}
									if(IsSet($this->inputs[$key]["DiscardInvalidValues"])
									&& $this->inputs[$key]["DiscardInvalidValues"])
									{
										$input_error=$this->ValidateInput($key,$value);
										if(strlen($input_error))
										{
											if(IsSet($this->inputs[$key]["VALUE"]))
											$this->SetValue($name,$this->inputs[$key]["VALUE"]);
											break;
										}
									}
									$previous_value=(IsSet($this->inputs[$key]["VALUE"]) ? $this->inputs[$key]["VALUE"] : "");
									if(strcmp($value,$previous_value))
									$this->Changes[$key]=$previous_value;
									$this->inputs[$key]["VALUE"]=$value;
									if($set_value)
									$this->SetValue($name,$value);
									break;
							}
						}
				}
			}
			for($input_number=0,Reset($custom);$input_number<count($custom);Next($custom),$input_number++)
			$this->inputs[$custom[$input_number]]["object"]->LoadInputValues($this, $submitted);
		}

		Function SetSelectOptions($input, $options, $selected)
		{
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			switch($this->inputs[$input]["TYPE"])
			{
				case "select":
					if(!IsSet($this->inputs[$input]["MULTIPLE"]))
					{
						if(GetType($selected)!="array"
						|| count($selected)!=1
						|| !IsSet($selected[0]))
						return($this->OutputError("it was not specified an array with a single selected value",$input));
						$selected=$selected[0];
					}
					return($this->SetSelectedOptions($input, $this->inputs[$input], $options, $selected));
				case "custom":
					if(strlen($error=$this->inputs[$input]["object"]->SetSelectOptions($this, $options, $selected)))
					return($this->OutputError($error,$input));
					return("");
				default:
					return($this->OutputError("it was not specified a valid input to set its options",$property));
			}
		}

		Function SetInputProperty($input,$property,$value)
		{
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			switch($property)
			{
				case "ApplicationData":
					$this->inputs[$input][$property]=$value;
					return("");
			}
			if(!strcmp($this->inputs[$input]["TYPE"],"custom"))
			{
				if(strlen($error=$this->inputs[$input]["object"]->SetInputProperty($this, $property, $value)))
				$this->OutputError($error,$input);
				return("");
			}
			switch($property)
			{
				case "ONBLUR":
				case "ONCHANGE":
				case "ONCLICK":
				case "ONDBLCLICK":
				case "ONFOCUS":
				case "ONKEYDOWN":
				case "ONKEYPRESS":
				case "ONKEYUP":
				case "ONMOUSEDOWN":
				case "ONMOUSEMOVE":
				case "ONMOUSEOUT":
				case "ONMOUSEOVER":
				case "ONMOUSEUP":
				case "ONSELECT":
					$this->inputs[$input]["EVENTS"][$property]=$value;
					return("");
				case "ValidationServerFunctionErrorMessage":
					if(!IsSet($this->inputs[$input]["ValidationServerFunction"]))
					return($this->OutputError("this input is not set to validate on the server side with a custom function",$property));
					break;
				case "ACCEPT":
				case "ALT":
				case "SRC":
				case "Accessible":
				case "Capitalization":
				case "ClientScript":
				case "SubForm":
				case "VALUE":
				case "STYLE":
				case "CLASS":
				case "ReadOnlyMark":
				case "ReadOnlyMarkUnchecked":
					break;
				case "COLS":
				case "MAXLENGTH":
				case "ROWS":
				case "SIZE":
				case "TABINDEX":
					$value=intval($value);
					break;
				default:
					return($this->OutputError("it was not specified a valid settable input property",$property));
			}
			$this->inputs[$input][$property]=$value;
			return("");
		}

		Function SetInputValue($input,$value)
		{
			return($this->SetInputProperty($input,"VALUE",$value));
		}

		Function GetInputValue($input)
		{
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			switch($this->inputs[$input]["TYPE"])
			{
				case "custom":
					return($this->inputs[$input]["object"]->GetInputValue($this));
				case "select":
					if(IsSet($this->inputs[$input]["MULTIPLE"]))
					{
						$value=array();
						for(Reset($this->inputs[$input]["SELECTED"]),$selected=0;$selected<count($this->inputs[$input]["SELECTED"]);Next($this->inputs[$input]["SELECTED"]),$selected++)
						$value[]=Key($this->inputs[$input]["SELECTED"]);
						return($value);
					}
				default:
					return(IsSet($this->inputs[$input]["VALUE"]) ? $this->inputs[$input]["VALUE"] : "");
			}
		}

		Function GetInputProperty($input, $property, &$value)
		{
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid input",$input));
			switch($property)
			{
				case "VALUE":
					$value=$this->GetInputValue($input);
					return("");
				case "TYPE":
				case "ApplicationData":
					$value=$this->inputs[$input][$property];
					return("");
				default:
					switch($this->inputs[$input]["TYPE"])
					{
						case "custom":
							return($this->inputs[$input]["object"]->GetInputProperty($this, $property, $value));
						case "select":
							switch($property)
							{
								case "SelectedOption":
									if(!IsSet($this->inputs[$input]["MULTIPLE"])
									&& IsSet($this->inputs[$input]["VALUE"]))
									{
										$value=$this->inputs[$input]["OPTIONS"][$this->inputs[$input]["VALUE"]];
										return("");
									}
									return($this->OutputError("GetInputProperty is not implemented for form input property ".$property,$input));
							}
						default:
							switch($property)
							{
								case 'LABEL':
									if(IsSet($this->inputs[$input]["LABEL"]))
									$value=$this->inputs[$input]["LABEL"];
									else
									Unset($value);
									return("");
							}
							return($this->OutputError("GetInputProperty is not implemented for form input property ".$property,$input));
					}
			}
		}

		Function GetJavascriptInputObject($form_object, $input)
		{
			return($form_object."[".$this->EncodeJavascriptString($input)."]");
		}

		Function GetJavascriptInputValue($form_object, $input)
		{
			switch($this->inputs[$input]["TYPE"])
			{
				case "select":
					if(strlen($field=$this->GetJavascriptInputObject($form_object, $input))==0)
					return("");
					return($field.".options[".$field.".selectedIndex].value");
				case "custom":
					if(strlen($javascript=$this->inputs[$input]["object"]->GetJavascriptInputValue($this, $form_object)))
					return($javascript);
					$this->OutputError("could not retrieve the Javascript input value", $input);
					return("");
				default:
					if(strlen($field=$this->GetJavascriptInputObject($form_object, $input))==0)
					return("");
					return($field.".value");
			}
		}

		Function GetJavascriptSetInputProperty($form_object, $input, $property, $value)
		{
			switch($this->inputs[$input]["TYPE"])
			{
				case "custom":
					if(strlen($javascript=$this->inputs[$input]["object"]->GetJavascriptSetInputProperty($this, $form_object, $property, $value)))
					return($javascript);
					$this->OutputError("could not retrieve the Javascript for setting the property ".$property, $input);
					return("");
				case "select":
				default:
					if(strcmp($property,"VALUE"))
					{
						$this->OutputError("it setting this input ".$property." value is not yet supported", $input);
						return("");
					}
					if(strlen($field=$this->GetJavascriptInputObject($form_object, $input))==0)
					return("");
					return($field.".value=".$value.";");
			}
		}

		Function GetJavascriptSetInputValue($form_object, $input, $value)
		{
			return($this->GetJavascriptSetInputProperty($form_object, $input, "VALUE", $value));
		}

		Function SetCheckedState($input,$checked)
		{
			if(!IsSet($this->inputs[$input])
			|| ($this->inputs[$input]["TYPE"]!="radio"
			&& $this->inputs[$input]["TYPE"]!="checkbox"))
			return($this->OutputError("it was not specified a valid radio or checkbox input",$input));
			if($checked)
			$this->inputs[$input]["CHECKED"]=1;
			else
			Unset($this->inputs[$input]["CHECKED"]);
			return("");
		}

		Function GetCheckedState($input)
		{
			if(!IsSet($this->inputs[$input])
			|| ($this->inputs[$input]["TYPE"]!="radio"
			&& $this->inputs[$input]["TYPE"]!="checkbox"))
			return($this->OutputError("it was not specified a valid radio or checkbox input",$input));
			return(IsSet($this->inputs[$input]["CHECKED"]));
		}

		Function SetCheckedRadio($name, $value)
		{
			for($radio="", $i=0, Reset($this->inputs); $i < count($this->inputs) ; $i++, Next($this->inputs))
			{
				$input=Key($this->inputs);
				if(IsSet($this->inputs[$input]["NAME"])
				&& !strcmp($this->inputs[$input]["NAME"],$name))
				{
					if(strcmp($this->inputs[$input]["TYPE"], "radio"))
					{
						$this->OutputError("the input of NAME \"".$name."\" is not a valid radio input");
						return("");
					}
					if(strcmp($this->inputs[$input]["VALUE"], $value))
					Unset($this->inputs[$input]["CHECKED"]);
					else
					{
						$radio = $input;
						$this->inputs[$input]["CHECKED"]=1;
					}
				}
			}
			if(strlen($radio)==0)
			$this->OutputError("there is no radio input with NAME \"".$name."\" and value \"".$value."\"");
			return($radio);
		}

		Function GetCheckedRadio($name)
		{
			for($i=0, Reset($this->inputs); $i < count($this->inputs) ; $i++, Next($this->inputs))
			{
				$input=Key($this->inputs);
				if(IsSet($this->inputs[$input]["NAME"])
				&& !strcmp($this->inputs[$input]["NAME"],$name))
				{
					if($this->inputs[$input]["TYPE"]!="radio")
					{
						$this->OutputError("the input of NAME \"$name\" is not a valid radio input");
						return("");
					}
					if(IsSet($this->inputs[$input]["CHECKED"]))
					return($input);
				}
			}
			return("");
		}

		Function GetCheckedRadioValue($name,$default="")
		{
			return(strlen($input=$this->GetCheckedRadio($name)) ? $this->inputs[$input]["VALUE"] : $default);
		}

		Function ResetFormParts()
		{
			for($input=0,Reset($this->inputs);$input<count($this->inputs);Next($this->inputs),$input++)
			{
				$input_name=Key($this->inputs);
				Unset($this->inputs[$input_name]["InputElement"]);
				Unset($this->inputs[$input_name]["Part"]);
			}
			$this->parts=$this->types=$this->input_parts=$this->input_elements=$this->functions=$this->label_access_keys=array();
			$this->client_validate=0;
			$this->hidden_parts=$this->accessible_parts=$this->file_parts=0;
		}

		Function AddHiddenInputs($inputs)
		{
			for($input=0,Reset($inputs);$input<count($inputs);Next($inputs),$input++)
			{
				$name=Key($inputs);
				if(strcmp($error=$this->AddInput(array(
				"TYPE"=>"hidden",
				"NAME"=>$name,
				"VALUE"=>$inputs[$name]
				)),""))
				return($error);
			}
			return("");
		}

		Function AddHiddenInputsParts($inputs)
		{
			for($input=0,Reset($inputs);$input<count($inputs);Next($inputs),$input++)
			{
				$name=Key($inputs);
				if(strcmp($error=$this->SetInputValue($name,$inputs[$name]),"")
				|| strcmp($error=$this->AddInputHiddenPart($name),""))
				return($error);
			}
			return("");
		}

		Function WasSubmitted($input="")
		{
			if(strcmp($input,""))
			{
				if(!IsSet($this->inputs[$input]))
				{
					$this->OutputError("it was not specified an existing input",$input);
					return("");
				}
				$name=(IsSet($this->inputs[$input]["NAME"]) ? $this->inputs[$input]["NAME"] : $input);
				if($this->inputs[$input]["TYPE"]=="image")
				return(($this->IsSetValue($name."_x",0) && $this->IsSetValue($name."_y",0)) ? $input : "");
				return($this->IsSetValue($name,$this->inputs[$input]["TYPE"]=="file") ? $input : "");
			}
			for($field=0,Reset($this->inputs);$field<count($this->inputs);Next($this->inputs),$field++)
			{
				$input=Key($this->inputs);
				$name=(IsSet($this->inputs[$input]["NAME"]) ? $this->inputs[$input]["NAME"] : $input);
				switch($this->inputs[$input]["TYPE"])
				{
					case "submit":
						if($this->IsSetValue($name,0))
						return($input);
						break;
					case "image":
						if($this->IsSetValue($name."_x",0)
						&& $this->IsSetValue($name."_y",0))
						return($input);
						break;
				}
			}
			return("");
		}

		Function StartLayoutCapture()
		{
			if($this->capturing)
			return($this->OutputError("the form layout is already being captured","StartLayoutCapture"));
			if(!$this->capturing=ob_start())
			return($this->OutputError("could not start capturing the form layout","StartLayoutCapture"));
			return("");
		}

		Function EndLayoutCapture()
		{
			if(!$this->capturing)
			return($this->OutputError("the form layout was not being captured","EndLayoutCapture"));
			$data=ob_get_contents();
			ob_end_clean();
			$this->capturing=0;
			$this->AddDataPart($data);
			return("");
		}

		Function FetchOutput()
		{
			global $form_output;

			$form_output="";
			$arguments=array(
			"Function"=>"FormAppendOutput",
			"EndOfLine"=>$this->end_of_line
			);
			return(strlen($this->OutputError($this->Output($arguments))) ? "" : $form_output);
		}

		Function DisplayOutput()
		{
			$arguments=array(
			"Function"=>"FormDisplayOutput",
			"EndOfLine"=>$this->end_of_line
			);
			return($this->Output($arguments));
		}

		Function GetInputEventURL($input,$event,$parameters,&$url)
		{
			if(strlen($action=$this->ACTION)==0)
			{
				if(GetType($mark=strpos($uri=GetEnv('REQUEST_URI'),"?"))=="integer")
				$action=substr($uri, $mark);
			}
			$url=$action.(GetType(strpos($action,"?"))=="integer" ? "&" : "?").UrlEncode($this->event_parameter)."=".UrlEncode($event)."&".UrlEncode($this->input_parameter)."=".UrlEncode($input);
			for($parameter=0, Reset($parameters) ; $parameter<count($parameters); $parameter++, Next($parameters))
			{
				$key=Key($parameters);
				$url.=("&".UrlEncode($key)."=".UrlEncode($parameters[$key]));
			}
			return("");
		}

		Function HandleEvent(&$processed)
		{
			global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_SERVER_VARS;

	 	$processed=0;
	 	if(!$this->IsSetValue($this->event_parameter,0)
	 	|| !$this->IsSetValue($this->input_parameter,0))
			return("");
			$input=$this->GetValue($this->input_parameter,0);
			$event=$this->GetValue($this->event_parameter,0);
			if(!IsSet($this->inputs[$input])
			|| strcmp($this->inputs[$input]["TYPE"],"custom"))
			return("");
			switch(IsSet($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : (IsSet($HTTP_SERVER_VARS["REQUEST_METHOD"]) ? $HTTP_SERVER_VARS["REQUEST_METHOD"] : ""))
			{
				case "POST":
					if(IsSet($_POST))
					{
						$parameters=$_POST;
						break;
					}
					elseif(IsSet($HTTP_POST_VARS))
					{
						$parameters=$HTTP_POST_VARS;
						break;
					}
				case "GET":
					if(IsSet($_GET))
					{
						$parameters=$_GET;
						break;
					}
					elseif(IsSet($HTTP_GET_VARS))
					{
						$parameters=$HTTP_GET_VARS;
						break;
					}
				default:
					$parameters=array();
					break;
			}
			if(strlen($error=$this->inputs[$input]["object"]->HandleEvent($this, $event, $parameters, $processed)))
			{
				$this->OutputError($error,$input);
				return($error);
			}
			if($processed)
			return("");
			return($this->PostInputMessages($processed));
		}

		Function Connect($from, $to, $event, $action, $context)
		{
			if(!IsSet($this->inputs[$from]))
			return($this->OutputError("it was not specified a valid event connection origin input",$from));
			if(!IsSet($this->inputs[$to]))
			return($this->OutputError("it was not specified a valid event connection destination input",$to));
			if(strcmp($this->inputs[$from]["TYPE"],"custom"))
			{
				switch($event)
				{
					case "ONBLUR":
					case "ONCHANGE":
					case "ONCLICK":
					case "ONDBLCLICK":
					case "ONFOCUS":
					case "ONKEYDOWN":
					case "ONKEYPRESS":
					case "ONKEYUP":
					case "ONMOUSEDOWN":
					case "ONMOUSEMOVE":
					case "ONMOUSEOUT":
					case "ONMOUSEOVER":
					case "ONMOUSEUP":
					case "ONSELECT":
						break;
					default:
						return($this->OutputError($event." is not a supported event connection type",$from));
				}
				if(!IsSet($this->inputs[$from]["Connections"]))
				$this->inputs[$from]["Connections"][$event]=array();
				$this->inputs[$from]["Connections"][$event][]=array(
				"To"=>$to,
				"Action"=>$action,
				"Context"=>$context
				);
				if(!IsSet($this->inputs[$from]["EVENTS"][$event]))
				$this->inputs[$from]["EVENTS"][$event]="";
				return("");
			}
			else
			{
				if(strlen($error=$this->inputs[$from]["object"]->Connect($this, $to, $event, $action, $context)))
				$this->OutputError($error, $to);
				return($error);
			}
		}

		Function ConnectFormToInput($input, $event, $action, $context)
		{
			if(!IsSet($this->inputs[$input]))
			return($this->OutputError("it was not specified a valid event connection destination input", $input));
			switch($event)
			{
				case "ONSUBMITTING":
				case "ONSUBMIT":
				case "ONRESET":
				case "ONLOAD":
				case "ONUNLOAD":
					break;
				default:
					return($this->OutputError($event." is not a supported event connection type", $input));
			}
			if(!IsSet($this->connections))
			$this->connections[$event]=array();
			$this->connections[$event][]=array(
			"To"=>$input,
			"Action"=>$action,
			"Context"=>$context
			);
			return("");
		}

		Function PostMessage($message)
		{
			if(IsSet($message["Target"]))
			{
				$target=$message["Target"];
				if(!IsSet($this->inputs[$target])
				|| strcmp($this->inputs[$target]["TYPE"],"custom"))
				return($this->OutputError("it was not specified a valid posted message target input",$target));
			}
			$this->messages[]=$message;
			return("");
		}

		Function PostInputMessages(&$processed)
		{
			while(count($this->messages))
			{
				Reset($this->messages);
				$first = Key($this->messages);
				if(!IsSet($this->messages[$first]["Target"]))
				return("");
				$target=$this->messages[$first]["Target"];
				$message = $this->messages[$first];
				UnSet($this->messages[$first]);
				if(!IsSet($this->inputs[$target])
				|| strcmp($this->inputs[$target]["TYPE"],"custom"))
				return($this->OutputError("it was not specified a valid posted message target input",$target));
				if(strlen($error=$this->inputs[$target]["object"]->PostMessage($this, $message, $processed)))
				{
					$this->OutputError($error, $target);
					return($error);
				}
				if($processed)
				return("");
			}
			return("");
		}

		Function GetNextMessage(&$message)
		{
			if(count($this->messages)==0)
			return(0);
			Reset($this->messages);
			$first = Key($this->messages);
			$message = $this->messages[$first];
			UnSet($this->messages[$first]);
			return(1);
		}

		Function ReplyMessage($message, &$processed)
		{
			if(!IsSet($message["ReplyTo"]))
			return($this->OutputError("the message did not specify the reply destination input"));
			$input=$message["ReplyTo"];
			if(strcmp($this->inputs[$input]["TYPE"],"custom"))
			return($this->OutputError("the message reply destination is not custom input", $input));
			if(strlen($error=$this->inputs[$input]["object"]->ReplyMessage($this, $message, $processed)))
			{
				$this->OutputError($error,$input);
				return($error);
			}
			if($processed)
			return("");
			return($this->PostInputMessages($processed));
		}

		Function PageHead()
		{
			$t=count($this->inputs);
			for($i=array(), $f=0, Reset($this->inputs); $f<$t; Next($this->inputs),$f++)
			{
				$input=Key($this->inputs);
				if(!strcmp($this->inputs[$input]["TYPE"],"custom"))
				$i[]=$input;
			}
			$t=count($i);
			for($classes=array(), $h="", $f=0; $f<$t; $f++)
			{
				$input=$i[$f];
				$custom_class=$this->inputs[$input]["object"]->custom_class;
				if(!IsSet($classes[$custom_class]))
				{
					$h.=$this->inputs[$input]["object"]->ClassPageHead($this);
					$classes[$custom_class]="";
				}
				$h.=$this->inputs[$input]["object"]->PageHead($this);
			}
			return($h);
		}

		Function PageLoad()
		{
			$t=count($this->inputs);
			for($i=array(), $f=0, Reset($this->inputs); $f<$t; Next($this->inputs),$f++)
			{
				$c=Key($this->inputs);
				if(!strcmp($this->inputs[$c]["TYPE"],"custom"))
				$i[]=$c;
			}
			$t=count($i);
			for($l="", $f=0; $f<$t; $f++)
			{
				$c=$i[$f];
				$input_prolog=$this->inputs[$c]["object"]->PageLoad($this);
				$l.=$input_prolog;
			}
			if(IsSet($this->connections['ONLOAD'])
			&& count($this->connections['ONLOAD']))
			{
				$form_object=$this->GetFormObject();
				if(strlen($form_object))
				{
					if(strlen($a=$this->GetFormEventActions($form_object, 'ONLOAD')))
					{
						if(strlen($l))
						{
							if(strcmp($l[strlen($l)-1],';'))
							$l.=';';
							$l.=' ';
						}
						$l.=$a;
					}
				}
				else
				$this->OutputError("it was not possible to define a form object expression to generate page load actions", '');
			}
			return($l);
		}

		Function PageUnload()
		{
			$t=count($this->inputs);
			for($i=array(), $f=0, Reset($this->inputs); $f<$t; Next($this->inputs),$f++)
			{
				$c=Key($this->inputs);
				if(!strcmp($this->inputs[$c]["TYPE"],"custom"))
				$i[]=$c;
			}
			$t=count($i);
			for($u="", $f=0; $f<$t; $f++)
			{
				$c=$i[$f];
				$input_prolog=$this->inputs[$c]["object"]->PageUnload($this);
				$u.=$input_prolog;
			}
			if(IsSet($this->connections['ONUNLOAD'])
			&& count($this->connections['ONUNLOAD']))
			{
				$form_object=$this->GetFormObject();
				if(strlen($form_object))
				{
					if(strlen($a=$this->GetFormEventActions($form_object, 'ONUNLOAD')))
					{
						if(strlen($u))
						{
							if(strcmp($u[strlen($u)-1],';'))
							$u.=';';
							$u.=' ';
						}
						$u.=$a;
					}
				}
				else
				$this->OutputError("it was not possible to define a form object expression to generate page unload actions", '');
			}
			return($u);
		}

		Function GetInputs($parent = '')
		{
			$t=count($this->inputs);
			for($i = array(), $f=0, Reset($this->inputs); $f<$t; Next($this->inputs),$f++)
			{
				$c=Key($this->inputs);
				$p = (IsSet($this->inputs[$c]['parent']) ? $this->inputs[$c]['parent'] : '');
				if(!strcmp($parent, $p))
				$i[]=$c;
			}
			return($i);
		}
	};

	Function FormCaptureOutput(&$form,$arguments)
	{
		global $form_output;

		$form_output="";
		$arguments["Function"]="FormAppendOutput";
		return($form->OutputError($form->Output($arguments)) ? "" : $form_output);
	}

}
?>