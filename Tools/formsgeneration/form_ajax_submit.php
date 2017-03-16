<?php
/*
 *
 * @(#) $Id: form_ajax_submit.php,v 1.38 2011/03/17 09:58:34 mlemos Exp $
 *
 */

class form_ajax_submit_class extends form_custom_class
{
	var $timeout = 60.000;
	var $poll_interval = 10;
	var $feedback_element = '';
	var $submit_feedback = '';
	var $complete_feedback;
	var $timeout_feedback = '';
	var $target_input = '';
	var $sub_form = '';
	var $debug_console = '';
	var $response_header='';
	var $connections=array(
		"ONCOMPLETE"=>array(),
		"ONSUBMITTED"=>array(),
		"ONTIMEOUT"=>array()
	);
	var $events=array(
		"ONTIMEOUT"=>"alert('The communication with the server has timed out.');"
	);
	var $debug_console_template='<fieldset style="width: 80ex; margin: auto">
<legend><b>Debug console</b></legend>
<div id="{ELEMENT}" style="background-color: black; color: white; padding: 4px; font-family: monospace; height: 10em; overflow: auto"></div>
</fieldset>';
	var $sub_form_parameter="___sub_form";

	var $submit_form = '';
	var $actions=array();
	var $action_header_sent = 0;
	var $start_script_sent = 0;
	var $sending_ajax_response = 0;

	Function AddInput(&$form, $arguments)
	{
		if(IsSet($arguments["Timeout"]))
		{
			$timeout=doubleval($arguments["Timeout"]);
			if(strcmp($arguments["Timeout"], $timeout)
			|| $timeout<0)
				return("it was not specified a valid timeout value");
			$this->timeout = $timeout;
		}
		if(IsSet($arguments["ONTIMEOUT"]))
			$this->events["ONTIMEOUT"] = $arguments["ONTIMEOUT"];
		if(IsSet($arguments["ONCOMPLETE"]))
			$this->events["ONCOMPLETE"] = $arguments["ONCOMPLETE"];
		if(IsSet($arguments["ONSUBMITTED"]))
			$this->events["ONSUBMITTED"] = $arguments["ONSUBMITTED"];
		if(IsSet($arguments["FeedbackElement"]))
		{
			if(strlen($arguments["FeedbackElement"])==0)
				return("it was not specified a valid feedback element identifier");
			$this->feedback_element = $arguments["FeedbackElement"];
			if(IsSet($arguments["SubmitFeedback"]))
				$this->submit_feedback = $arguments["SubmitFeedback"];
			if(IsSet($arguments["TimeoutFeedback"]))
				$this->timeout_feedback = $arguments["TimeoutFeedback"];
			if(IsSet($arguments["CompleteFeedback"]))
				$this->complete_feedback = $arguments["CompleteFeedback"];
		}
		if(IsSet($arguments["TargetInput"]))
		{
			if(strlen($arguments["TargetInput"])==0)
				return("it was not specified a valid target input identifier");
			$this->target_input = $arguments["TargetInput"];
		}
		if(IsSet($arguments["DebugConsole"]))
		{
			if(strlen($arguments["DebugConsole"])==0)
				return("it was not specified a valid debug console identifier");
			$this->debug_console = $arguments["DebugConsole"];
		}
		if(IsSet($arguments["ResponseHeader"]))
		{
			if(strlen($arguments["ResponseHeader"])==0)
				return("it was not specified a valid response header");
			$this->response_header = $arguments["ResponseHeader"];
		}
		$this->submit_form = $this->GenerateInputID($form, $this->input, "sf");
		return("");
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case "FeedbackElement":
				if(strlen($value)==0)
					return("it was not specified a valid feedback element identifier");
				$this->feedback_element = $value;
				break;
			case "Feedback":
				if(strlen($this->feedback_element)==0)
					return("the feedback element identifier is not set");
				if(!$this->sending_ajax_response)
					return("it is only possible to update the feedback element when the AJAX response is being sent");
				$this->actions[]=array(
					"Action"=>"ReplaceContent",
					"Container"=>$this->feedback_element,
					"Content"=>$value
				);
				if(strlen($error = $this->FlushActions($form, 1)))
					return($error);
				break;
			default:
				return($this->DefaultSetInputProperty($form, $property, $value));
		}
		return("");
	}

	Function AddInputPart(&$form)
	{
		if(strcmp(strtolower($form->METHOD),"post"))
			return("currently the AJAX submit input only supports forms submitted with the POST method");
		$eol=$form->end_of_line;
		$b="";
		$javascript="<script type=\"text/javascript\" defer=\"defer\">".$eol."<!--\n";
		$javascript.="var ".$this->submit_form."_s=false;".$eol;
		$javascript.="var ".$this->submit_form."_r=false;".$eol;
		$javascript.="var ".$this->submit_form."_t=0;".$eol;
		$javascript.="var ".$this->submit_form."_o=".intval($this->timeout*1000).";".$eol;
		$javascript.="var ".$this->submit_form."_f;".$eol;
		$javascript.="function ".$this->submit_form."()".$b."{".$b;
		$javascript.="if(!".$this->submit_form."_s)".$b."return;".$b;
		$javascript.="if(".$this->submit_form."_r)".$b."{".$b."if((i=document.getElementById(".$form->EncodeJavascriptString($this->submit_form."_i").")))".$b."i.src='';".$b.$this->submit_form."_s=false;".$b.$this->GetEventActions($form, $this->submit_form."_f", "ONCOMPLETE").$b."return;".$b."}".$b;
		$javascript.=$this->submit_form."_t+=".$this->poll_interval.";".$b;
		$javascript.="if(".$this->submit_form."_t>=".$this->submit_form."_o)".$b."{".$b.$this->submit_form."_s=false;".$b."if((i=document.getElementById(".$form->EncodeJavascriptString($this->submit_form."_i").")))".$b."i.src='';".$b.(strlen($this->feedback_element) ? "if((fb=document.getElementById(".$form->EncodeJavascriptString($this->feedback_element)."))) { fb.innerHTML=".$form->EncodeJavascriptString($this->timeout_feedback).';} ;'.$b :'').$this->GetEventActions($form, $this->submit_form."_f", "ONTIMEOUT").$b."return;".$b."}".$b;
		$javascript.="setTimeout('".$this->submit_form."()',".$this->poll_interval.");".$b;
		$javascript.="}".$eol."// -->".$eol."</script>";
		$javascript.="<iframe id=\"".$this->submit_form."_i\" name=\"".$this->submit_form."_i\" width=\"0\" height=\"0\" frameborder=\"0\"></iframe>";
		return($form->AddDataPart($javascript));
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case "Submit":
				$parameters=array();
				if(IsSet($context["SubForm"]))
				{
					if(strlen($sub_form=$form->GetJavascriptSetFormProperty($form_object, "SubForm", $form->EncodeJavascriptString($context["SubForm"])))==0)
						return("could not set the AJAX form submit SubForm");
					$sub_form.="; ";
					$parameters[$this->sub_form_parameter]=$context["SubForm"];
				}
				else
					$sub_form='';
				if(strlen($error=$form->GetInputEventURL($this->input,"submit",$parameters,$form_action)))
						return($error);
				$javascript="if(".$this->submit_form."_s) return false; ".$this->submit_form."_r=false; ".$this->submit_form."_f=f=".$form_object.";";
				if(IsSet($context["SetInputValue"]))
				{
					for($i=0, Reset($context["SetInputValue"]); $i<count($context["SetInputValue"]); Next($context["SetInputValue"]), $i++)
					{
						$input=Key($context["SetInputValue"]);
						if(strlen($j = $form->GetJavascriptSetInputProperty("f", $input, "VALUE", $form->EncodeJavascriptString($context["SetInputValue"][$input])))==0)
						{
							$javascript="";
							return("could not set the value of the input \"".$input."\"");
						}
						$javascript.=" ".$j;
					}
				}
				$validate = (!IsSet($context['Validate']) || $context['Validate']);
				$javascript.=" ".$sub_form.($validate ? "if(f.onsubmit && !f.onsubmit()) return false; " : "")."t=f.target; a=f.action; f.target='".$this->submit_form."_i'; f.action=".$form->EncodeJavascriptString($form_action)."; f.submit(); f.action=a; f.target=t; ".$this->submit_form."_t=0; ".$this->submit_form."_s=true; ".$this->submit_form."(); ".(strlen($this->feedback_element) ? "if((fb=document.getElementById(".$form->EncodeJavascriptString($this->feedback_element)."))) { fb.innerHTML=".$form->EncodeJavascriptString($this->submit_feedback).';} ;' :'').($s=$this->GetEventActions($form, "f", "ONSUBMITTED")).'return false';
				break;

			case "Load":
				if(IsSet($context["Parameters"]))
					$parameters=$context["Parameters"];
				else
					$parameters=array();
				if(IsSet($context['RandomParameter']))
					$parameters[$context['RandomParameter']]=uniqid($this->input);
				if(strlen($error=$form->GetInputEventURL($this->input,"load",$parameters,$form_action)))
						return($error);
				$javascript="if(".$this->submit_form."_s) return false; ".$this->submit_form."_r=false; ".$this->submit_form."_f=f=".$form_object.";";
				$javascript.=" if((i=document.getElementById(".$form->EncodeJavascriptString($this->submit_form."_i")."))) i.src=".$form->EncodeJavascriptString($form_action)."; ".$this->submit_form."_t=0; ".$this->submit_form."_s=true; ".$this->submit_form."(); ".(strlen($this->feedback_element) ? "if((fb=document.getElementById(".$form->EncodeJavascriptString($this->feedback_element)."))) { fb.innerHTML=".$form->EncodeJavascriptString($this->submit_feedback).';} ;' :'').'return false';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return("");
	}

	Function ValidateInput(&$form)
	{
		return("");
	}

	Function SetupMessage(&$message, $event)
	{
		$message=array(
			"Event"=>$event,
			"From"=>$this->input,
			"ReplyTo"=>$this->input,
			"More"=>0,
			"Window"=>"_p",
			"Document"=>"_d",
			"Form"=>"_p.".$this->submit_form."_f",
		);
		if(strlen($this->target_input))
			$message["Target"]=$this->target_input;
		if(strlen($this->sub_form))
			$message["SubForm"]=$this->sub_form;
	}

	Function HandleEvent(&$form, $event, $parameters, &$processed)
	{
		global $HTTP_GET_VARS;

		switch($event)
		{
			case "submit":
				if(IsSet($_GET[$this->sub_form_parameter]))
					$this->sub_form=$_GET[$this->sub_form_parameter];
				elseif(IsSet($HTTP_GET_VARS[$this->sub_form_parameter]))
					$this->sub_form=$HTTP_GET_VARS[$this->sub_form_parameter];
			case "load":
				$this->sending_ajax_response = 1;
				$this->SetupMessage($message, $event);
				if(strlen($error=$form->PostMessage($message)))
					return($error);
				$processed=0;
				break;
			default:
				return($this->DefaultHandleEvent($form,$event,$parameters,$processed));
		}
		return("");
	}

	Function Output($output)
	{
		echo $output;
	}

	Function DebugOutput(&$form, $output)
	{
		if(strlen($this->debug_console))
		{
			$content=$form->EncodeJavascriptString(HtmlSpecialChars($output)."<br />\n");
			$this->Output("if(_g\n&& (_de=_d.getElementById(".$form->EncodeJavascriptString($this->debug_console.'_output').")))\n");
			$this->Output("{\n _de.innerHTML+=".$content.";\n if('undefined' != typeof _de.scrollTop)\n  _de.scrollTop=_de.scrollHeight;\n}\n");
			flush();
		}
	}

	Function SendStartScript()
	{
		if(!$this->start_script_sent)
		{
			$this->Output("<script type=\"text/javascript\"><!--\nif(parent.".$this->submit_form."_s)\n{\n");
			$this->start_script_sent = 1;
		}
	}

	Function SendEndScript(&$form, $flush)
	{
		if($this->start_script_sent)
		{
			$this->Output("_p.".$this->submit_form."_t=0;\n");
			$this->Output("}\n// --></script>\n");
			$this->start_script_sent = 0;
			if($flush)
				flush();
		}
	}

	Function SendActionHeader(&$form)
	{
		$start=!$this->action_header_sent;
		if($start)
		{
			Header("Content-Type: text/html");
			if(strlen($this->response_header))
				Header($this->response_header);
			$this->Output("<html><head><title>submit</title></head><body>");
			$this->action_header_sent = 1;
		}
		$this->SendStartScript();
		$this->Output("_p=parent;\n_d=_p.document;\n_g=_d.getElementById;\n");
		if($start
		&& strlen($this->debug_console))
		{
			$element=$this->debug_console.'_output';
			$content=$form->EncodeJavascriptString(str_replace('{ELEMENT}', $element, $this->debug_console_template));
			$this->Output("if(_g\n&& (_de=_d.getElementById(".$form->EncodeJavascriptString($this->debug_console).")))\n");
			$this->Output(" _de.innerHTML=".$content.";\n");
			flush();
		}
	}

	Function SendAction(&$form, $action)
	{
		switch($this->actions[$action]["Action"])
		{
			case "AppendContent":
			case "PrependContent":
			case "ReplaceContent":
				if(!IsSet($this->actions[$action]["Container"]))
					return("it was not specified the container element to replace the content");
				if(!IsSet($this->actions[$action]["Content"]))
					return("it was not specified the content to the content in the container");
				$this->Output("if(_g\n&& (_e=_d.getElementById(".$form->EncodeJavascriptString($this->actions[$action]["Container"]).")))\n");
				$content=$form->EncodeJavascriptString($this->actions[$action]["Content"]);
				switch($this->actions[$action]["Action"])
				{
					case "AppendContent":
						if(strlen($this->debug_console))
							$this->DebugOutput($form, 'AppendContent(Container='.$this->actions[$action]["Container"].')');
						$this->Output(" _e.innerHTML+=".$content.";\n");
						break;
					case "PrependContent":
						if(strlen($this->debug_console))
							$this->DebugOutput($form, 'PrependContent(Container='.$this->actions[$action]["Container"].')');
						$this->Output(" _e.innerHTML=".$content."+_e.innerHTML;\n");
						break;
					case "ReplaceContent":
						if(strlen($this->debug_console))
							$this->DebugOutput($form, 'ReplaceContent(Container='.$this->actions[$action]["Container"].')');
						$this->Output(" _e.innerHTML=".$content.";\n");
						break;
				}
				break;
			case "SetValue":
				if(!IsSet($this->actions[$action]["Element"]))
					return("it was not specified the element to set the value");
				if(!IsSet($this->actions[$action]["Property"]))
					return("it was not specified the property of the element to set");
				if(!IsSet($this->actions[$action]["Value"]))
					return("it was not specified the property value of the element to set");
				switch(IsSet($this->actions[$action]["Type"]) ? $this->actions[$action]["Type"] : "string")
				{
					case "string":
						$value=$form->EncodeJavascriptString($this->actions[$action]["Value"]);
						break;
					case "number":
					case "integer":
					case "float":
					case "boolean":
					case "opaque":
						$value=$this->actions[$action]["Value"];
						break;
					case "null":
					case "undefined":
						$value=$this->actions[$action]["Type"];
						break;
					default:
						return($this->actions[$action]["Value"]." is not a valid element property value type");
				}
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'SetValue(Element='.$this->actions[$action]["Element"].', Property='.$this->actions[$action]["Property"].', Value='.$value.')');
				$this->Output("if(_g\n&& (_e=_d.getElementById(".$form->EncodeJavascriptString($this->actions[$action]["Element"]).")))\n_e.".$this->actions[$action]["Property"].'='.$value.";\n");
				break;
			case "Redirect":
				if(!IsSet($this->actions[$action]["URL"])
				|| strlen($this->actions[$action]["URL"])==0)
					return("it was not specified the redirection URL");
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'Redirect(URL='.$this->actions[$action]["URL"].')');
				$this->Output("_d.location=".$form->EncodeJavascriptString($this->actions[$action]["URL"])."\n");
				break;
			case "Wait":
				if(!IsSet($this->actions[$action]["Time"])
				|| doubleval($this->actions[$action]["Time"])==0.0)
					return("it was not specified a valid wait time period");
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'Wait(Time='.$this->actions[$action]["Time"].')');
				$this->SendEndScript($form, 1);
				usleep($this->actions[$action]["Time"]*1000000);
				$this->SendStartScript();
				break;
			case "Command":
				if(!IsSet($this->actions[$action]["Command"]))
					return("it was not specified the command to execute");
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'Command(Command='.$this->actions[$action]["Command"].')');
				$this->Output($this->actions[$action]["Command"]);
				break;
			case "Connect":
				$to=(IsSet($this->actions[$action]["To"]) ? $this->actions[$action]["To"] : "");
				$connect_action=(IsSet($this->actions[$action]["ConnectAction"]) ? $this->actions[$action]["ConnectAction"] : "");
				$context=(IsSet($this->actions[$action]["Context"]) ? $this->actions[$action]["Context"] : array());
				if(strlen($error=$form->GetJavascriptConnectionAction(($this->sending_ajax_response ? '_p.' : '').$this->submit_form."_f", $this->input, $to, "", $connect_action, $context, $javascript)))
					return($error);
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'Connect(To='.$to.', ConnectAction='.$connect_action.')');
				$this->Output($javascript);
				$this->Output("\n");
				break;

			case "SetInputValue":
				if(!IsSet($this->actions[$action]["Input"]))
					return("it was not specified the input to set the value");
				if(!IsSet($this->actions[$action]["Value"]))
					return("it was not specified the value of the input to set");
				$input = $this->actions[$action]["Input"];
				$value = $this->actions[$action]["Value"];
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'SetInputValue(Input='.$input.', Value='.$value.')');
				$this->Output($form->GetJavascriptSetInputValue(($this->sending_ajax_response ? '_p.' : '').$this->submit_form."_f", $input, $value));
				break;

			case "SetInputProperty":
				if(!IsSet($this->actions[$action]["Input"]))
					return("it was not specified the input to set the value");
				if(!IsSet($this->actions[$action]["Property"]))
					return("it was not specified the property of the input to set");
				if(!IsSet($this->actions[$action]["Value"]))
					return("it was not specified the value of the input to set");
				$input = $this->actions[$action]["Input"];
				$property = $this->actions[$action]["Property"];
				$value = $this->actions[$action]["Value"];
				if(strlen($this->debug_console))
					$this->DebugOutput($form, 'SetInputProperty(Input='.$input.', Property='.$property.', Value='.$value.')');
				$this->Output($form->GetJavascriptSetInputProperty(($this->sending_ajax_response ? '_p.' : '').$this->submit_form."_f", $input, $property, $value));
				break;

			default:
				$error = "AJAX action ".$this->actions[$action]["Action"]." is not supported";
				if(strlen($this->debug_console))
					$this->DebugOutput($form, $error);
				return($error);
		}
	}

	Function SendActionFooter(&$form, $end)
	{
		if($end)
		{
			if(strlen($this->feedback_element)
			&& IsSet($this->complete_feedback))
				$this->Output("if(_g\n&&\n(_fb=_d.getElementById(".$form->EncodeJavascriptString($this->feedback_element).")))\n{\n_fb.innerHTML=".$form->EncodeJavascriptString($this->complete_feedback).';'."\n".'}'."\n");
			$this->Output("_p.".$this->submit_form."_r=true;\n");
		}
		$this->SendEndScript($form, !$end);
		if($end)
		{
			$this->Output("</body></html>\n");
			flush();
		}
	}

	Function FlushActions(&$form, $more)
	{
		$this->SendActionHeader($form);
		for($error = '', $action = 0; $action<count($this->actions); $action++)
		{
			if(strlen($error=$this->SendAction($form, $action)))
				break;
		}
		$this->actions=array();
		$this->SendActionFooter($form, !$more);
		return($error);
	}

	Function ReplyMessage(&$form, $message, &$processed)
	{
		if(!$this->sending_ajax_response)
			return("the AJAX response is not being sent");
		if(!IsSet($message["Event"])
		|| (strcmp($message["Event"], "submit")
		&& strcmp($message["Event"], "load")))
			return("it was specified an invalid message event to reply");
		if(IsSet($message["Actions"]))
		{
			for($new = 0; $new<count($message["Actions"]); $new++)
			{
				$this->actions[] = $message["Actions"][$new];
				if(!strcmp($message["Actions"][$new]["Action"],"Wait"))
				{
					$this->SendActionHeader($form);
					for($action = 0; $action<count($this->actions); $action++)
					{
						if(strlen($error=$this->SendAction($form, $action)))
							return($error);
					}
					$this->actions=array();
				}
			}
		}
		$more = (IsSet($message["More"]) && $message["More"]);
		$immediate = (IsSet($message["Immediate"]) && $message["Immediate"]);
		if($more)
		{
			$this->SetupMessage($next_message, $message["Event"]);
			if(strlen($error=$form->PostMessage($next_message)))
				return($error);
			$processed = 0;
		}
		else
		{
			$immediate = 1;
			$processed = 1;
		}
		if($immediate
		&& strlen($error = $this->FlushActions($form, $more)))
			return($error);
		if($processed)
			$this->sending_ajax_response = 0;
		return("");
	}
};

?>