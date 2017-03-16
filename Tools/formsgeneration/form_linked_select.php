<?php
/*
 *
 * @(#) $Id: form_linked_select.php,v 1.18 2012/04/19 10:02:00 mlemos Exp $
 *
 */

class form_linked_select_class extends form_custom_class
{
	var $select="";
	var $group="";
	var $switch_group="";
	var $linked_input="";
	var $selected_group="";
	var $groups=array();
	var $server_validate=0;
	var $multiple=0;
	var $dynamic=0;
	var $default_dynamic=0;
	var $group_parameter="___group";

	Function GetGroupOptions(&$options,$group)
	{
		if(IsSet($this->groups[$group]))
			$options=$this->groups[$group];
		else
			Unset($options);
		return("");
	}

	Function GetGroups(&$groups)
	{
		$groups=array();
		Reset($this->groups);
		for($g=0;$g<count($this->groups);$g++)
		{
			$groups[]=strval(Key($this->groups));
			Next($this->groups);
		}
		return("");
	}

	Function ValidateGroups(&$arguments)
	{
		if(!IsSet($arguments["Groups"])
		|| GetType($arguments["Groups"])!="array")
			return("it were not specified the groups of options");
		$this->groups=$arguments["Groups"];
		if(!IsSet($this->groups[$this->selected_group]))
			return("the current linked input value does not match any of the select options groups");
		if(!IsSet($this->groups[$this->selected_group]))
		{
			if((!IsSet($arguments["Group"])
			|| !IsSet($arguments["Groups"][$arguments["Group"]])))
				return("it was not specified a valid group for the current options");
			$this->selected_group=$arguments["Group"];
		}
		UnSet($arguments["Groups"]);
		UnSet($arguments["Group"]);
		return("");
	}

	Function AddInput(&$form, $arguments)
	{
		if(!IsSet($arguments["LinkedInput"]))
			return("it was not specified a valid input to link the select input");
		$this->linked_input=$arguments["LinkedInput"];
		$this->selected_group=$form->GetInputValue($this->linked_input);
		if(strlen($error=$this->ValidateGroups($arguments)))
			return($error);
		$this->dynamic=(IsSet($arguments["Dynamic"]) ? intval($arguments["Dynamic"]) : $this->default_dynamic);
		if(!$this->dynamic
		&& (IsSet($arguments["AutoWidthLimit"])
		|| IsSet($arguments["AutoHeightLimit"])))
		{
			if(strlen($error=$this->GetGroups($groups)))
				return($error);
			$w=$h=0;
			for($g=0;$g<count($groups);$g++)
			{
  			$group=$groups[$g];
				if(strlen($error=$this->GetGroupOptions($options,$group)))
					return($error);
				Reset($options);
				for($o=0;$o<count($options);$o++)
				{
					$option=strval(Key($options));
					$w=max($w,strlen($options[$option]));
					Next($options);
				}
				$h=max($h,$o);
			}
			if(IsSet($arguments["AutoWidthLimit"]))
			{
				if($arguments["AutoWidthLimit"]>0)
					$w=min($w+1,$arguments["AutoWidthLimit"]);
				$arguments["STYLE"]="width: ".strval($w)."em".(IsSet($arguments["STYLE"]) ? "; ".$arguments["STYLE"] : "");
			}
			if(IsSet($arguments["AutoHeightLimit"]))
			{
				if($arguments["AutoHeightLimit"]>0)
					$h=min($h,$arguments["AutoHeightLimit"]);
				$arguments["SIZE"]=strval($h);
			}
		}
		UnSet($arguments["Dynamic"]);
		UnSet($arguments["AutoWidthLimit"]);
		UnSet($arguments["AutoHeightLimit"]);
		if(strlen($error=$this->GetGroupOptions($selected_group,$this->selected_group)))
			return($error);
		$select_arguments=$arguments;
		UnSet($select_arguments["LinkedInput"]);
		$this->select=$this->GenerateInputID($form, $this->input, "select");
		$this->group=$this->GenerateInputID($form, $this->input, "group");
		$this->switch_group=$this->GenerateInputID($form, $this->input, "switch_group");
		$select_arguments["NAME"]=$select_arguments["ID"]=$this->focus_input=$this->select;
		$select_arguments["TYPE"]="select";
		$select_arguments["OPTIONS"]=$selected_group;
		$select_arguments["DiscardInvalidValues"]=0;
		$this->multiple=IsSet($arguments["MULTIPLE"]);
		if($this->multiple)
		{
			$select_arguments["MULTIPLE"]=1;
			if(!IsSet($select_arguments["SELECTED"]))
				$select_arguments["SELECTED"]=array();
		}
		else
			UnSet($select_arguments["MULTIPLE"]);
		UnSet($select_arguments["CustomClass"]);
		if(strlen($error=$form->AddInput($select_arguments))==0
		&& strlen($error=$form->AddInput(array(
			"TYPE"=>"hidden",
			"ID"=>$this->group,
			"NAME"=>$this->group,
			"VALUE"=>$this->selected_group
		)))==0)
			$error=$form->Connect($this->linked_input, $this->input, "ONCHANGE", "SwitchGroup", array("GroupProperty"=>"VALUE"));
		return($error);
	}

	Function AddInputPart(&$form)
	{
		$eol=$form->end_of_line;
		$b="";
		$javascript="<script type=\"text/javascript\" defer=\"defer\">".$eol."<!--\n";
		if($this->dynamic)
		{
			$javascript.="var ".$this->switch_group."_g=null;".$eol;
			$javascript.="var ".$this->switch_group."_f=null;".$eol;
			$javascript.="var ".$this->switch_group."_n=null;".$eol;
		}
		$javascript.="function ".$this->switch_group."(".($this->dynamic ? "" : "g,f").")".$b."{".$b;
		$javascript.="var n, o, i, s, a, b, bi;".$b;
		if($this->dynamic)
			$javascript.="var n=".$this->switch_group."_n;".$b;
		else
		{
			if(strlen($error=$this->GetGroups($groups)))
				return($error);
			for($g=0, $append="";$g<count($groups); $g++)
			{
				$group=$groups[$g];
				if($g>0)
					$javascript.="}".$b."else".$b."{".$b;
				$javascript.="if(g==".$form->EncodeJavascriptString($group).")".$b."{".$b."n=[";
				$append.="}".$b;
				if(strlen($error=$this->GetGroupOptions($options,$group)))
					return($error);
				Reset($options);
				for($o=0;$o<count($options);$o++)
				{
					$option=strval(Key($options));
					if($o>0)
						$javascript.=",";
					$javascript.=$form->EncodeJavascriptString($options[$option]).",".$form->EncodeJavascriptString($option);
					Next($options);
				}
				$javascript.="]".$b;
			}
			$javascript.="}".$b."else".$b."{".$b."n=null".$b.$append;
		}
		$javascript.="if(n!=null)".$b."{".$b;
		if($this->dynamic)
			$javascript.="var g=".$this->switch_group."_g;".$b."f=".$this->switch_group."_f;".$b;
		$javascript.="s=f[".$form->EncodeJavascriptString($this->select)."];".$b."o=s.options;".$b;
		if(!$this->multiple)
			$javascript.="bi=bi=s.selectedIndex;".$b."if(bi>=0) { b=o[bi].value };".$b;
		$javascript.="i=0;".$b."while(i<n.length)".$b."{".$b."o[i/2]=new Option(n[i],n[i+1]);".$b."i=i+2;".$b."}".$b."while(i<o.length*2)".$b."{".$b."o[i/2]=null".$b."}".$b."f[".$form->EncodeJavascriptString($this->group)."].value=g;".$b;
		if(!$this->multiple)
			$javascript.="o[0].selected=true;".$b."a=s.options[ai=s.selectedIndex].value;".$b."if(bi>=0 && a!=b && s.onchange) s.onchange();".$b;
		$javascript.="}".$b;
		if($this->dynamic)
			$javascript.="else".$b."{".$b."setTimeout('".$this->switch_group."()',10)".$b."}".$b;
		$javascript.="}".$eol."// -->".$eol."</script>";
		if($this->dynamic)
			$javascript.="<iframe id=\"".$this->switch_group."_i\" width=\"0\" height=\"0\" frameborder=\"0\"></iframe>";
		if(strlen($error=$form->AddDataPart($javascript))==0
		&& strlen($error=$form->AddInputPart($this->select))==0)
			$error=$form->AddInputPart($this->group);
		return($error);
	}

	Function GetInputValue(&$form)
	{
		return($form->GetInputValue($this->select));
	}

	Function Connect(&$form, $to, $event, $action, &$context)
	{
		return($form->Connect($this->select, $to, $event, $action, $context));
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case "SwitchGroup":
				$property=(IsSet($context["GroupProperty"]) ? $context["GroupProperty"] : "VALUE");
				if(strcmp($property, "VALUE"))
					return("it is not supported to switch to a group defined by property ".$property);
				$value=$form->GetJavascriptInputValue($form_object, $from);
				if(strlen($value)==0)
					return("it was not possible to determine how to retrieve ".$property." value");
				if($this->dynamic)
				{
					if(strlen($error=$form->GetInputEventURL($this->input,"getoptions",array($this->group_parameter=>"GROUP"),$iframe_url)))
						return($error);
					$javascript="if(document.getElementById && (f=document.getElementById('".$this->switch_group."_i'))){g=".$value.";".$this->switch_group."_g=g;".$this->switch_group."_f=".$form_object.";".$this->switch_group."_n=null;"."g=escape(g);while((p=g.indexOf('+'))!=-1){g=g.substring(0,p)+'%2B'+g.substring(p+1,g.length)}f.src='".str_replace("GROUP", "'+g+'", $iframe_url)."';setTimeout('".$this->switch_group."()',10)}";
				}
				else
					$javascript=$this->switch_group."(".$value.",".$form_object.")";
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return("");
	}

	Function LoadInputValues(&$form, $submitted)
	{
		$group=$form->GetInputValue($this->linked_input);
		$selected_group=$form->GetInputValue($this->group);
		$this->GetGroupOptions($options,$group);
		if(!IsSet($options)
		&& strcmp($group,$selected_group))
		{
			$group=$selected_group;
			$this->GetGroupOptions($options,$group);
		}
		if(IsSet($options))
		{
			if($this->multiple)
			{
				$selected=$form->GetInputValue($this->select);
				for($option=0; $option<count($selected); $option++)
				{
					if(!IsSet($options[$selected[$option]]))
						break;
				}
				if($option<count($selected))
				{
					$selected=array();
					$group=$this->selected_group;
					$this->GetGroupOptions($options,$group);
				}
				else
					$this->selected_group=$group;
				$form->SetSelectOptions($this->select, $options, $selected);
			}
			else
			{
				$option=$form->GetInputValue($this->select);
				if(IsSet($options[$option]))
				{
					$this->selected_group=$group;
					$form->SetSelectOptions($this->select, $options, array($option));
				}
				else
				{
					$this->GetGroupOptions($options,$this->selected_group);
					Reset($options);
					$option=Key($options);
					$form->SetInputValue($this->select, $option);
				}
			}
		}
		if(strcmp($group, $selected_group))
			$form->SetInputValue($this->group, $this->selected_group=$group);
		return('');
	}

	Function HandleEvent(&$form, $event, $parameters, &$processed)
	{
		switch($event)
		{
			case "getoptions":
				if($this->dynamic)
				{
					if(!IsSet($parameters[$this->group_parameter])
					&& GetType($parameters[$this->group_parameter])=="string")
						return("the group parameter is not being passed to the linked select input getoptions event handler");
					if(strlen($error=$this->GetGroupOptions($g,$parameters[$this->group_parameter])))
						return($error);
					$c=count($g);
					$v="";
					for($o=0;$o<$c;$o++)
					{
						if($o>0)
							$v.=",\n";
						$k=Key($g);
						$v.=$form->EncodeJavascriptString($g[$k]).",".$form->EncodeJavascriptString($k);
						Next($g);
					}
					Header("Content-Type: text/html");
					echo "<html><head><title>getoptions</title><script type=\"text/javascript\"><!--\nfunction l()\n{\nparent.".$this->switch_group."_n=[\n".$v."\n];\n}\n// -->\n</script></head><body onload=\"l()\"></body></html>";
					$processed=1;
					break;
				}
			default:
				return($this->DefaultHandleEvent($form,$event,$parameters,$processed));
		}
		return("");
	}

	Function GetInputProperty(&$form, $property, &$value)
	{
		switch($property)
		{
			case "SelectedOption":
				return($form->GetInputProperty($this->select, $property, $value));
			default:
				return($this->DefaultGetInputProperty($form, $property, $value));
		}
	}

	Function GetJavascriptInputValue(&$form, $form_object)
	{
		return($form->GetJavascriptInputValue($form_object, $this->select));
	}
};

?>