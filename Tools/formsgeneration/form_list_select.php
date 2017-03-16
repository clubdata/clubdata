<?php
/*
 *
 * @(#) $Id: form_list_select.php,v 1.20 2016/04/29 02:14:08 mlemos Exp $
 *
 */

class form_list_select_class extends form_custom_class
{
	var $server_validate=0;
	var $options = array();
	var $name = '';

	var $STYLE = 'border-style: solid; border-color: #808080 #ffffff #ffffff #808080; border-width: 1px';
	var $CLASS = '';
	var $VALUE = '';
	var $SIZE;
	var $columns = array(
		array(
			'Type'=>'Input',
		),
		array(
			'Type'=>'Option',
		),
	);
	var $get_selected_option = '';
	var $select_all = '';
	var $select_all_input = '';
	var $multiple = 0;
	var $selected = array();
	var $select_all_text = 'Select all';
	var $accessible;
	var $read_only_mark;
	var $show_select_all = 1;

	Function AddInput(&$form, $arguments)
	{
		if(!IsSet($arguments['OPTIONS'])
		|| GetType($arguments['OPTIONS'])!='array'
		|| count($this->options = $arguments['OPTIONS']) == 0)
			return('it were not specified the list select options');
		$this->multiple = (IsSet($arguments['MULTIPLE']) && $arguments['MULTIPLE']);
		if($this->multiple)
		{
			$selected = (IsSet($arguments['SELECTED']) ? $arguments['SELECTED'] : array());
			if(strcmp(GetType($selected),"array"))
				return("it was not defined a valid selected options array");
			$this->selected=array();
			$ts = count($selected);
			for($option = 0; $option < $ts; ++$option)
			{
				$option_value = $selected[$option];
				if(!IsSet($this->options[$option_value]))
					return("it was specified a selected (".$option_value.") that is not a valid option");
				if(IsSet($this->selected[$option_value]))
					return("it specified a repeated selected option value");
				$this->selected[$option_value]=1;
			}
			$validate_as_set = (IsSet($arguments['ValidateAsSet']) && $arguments['ValidateAsSet']);
			if(IsSet($arguments['ShowSelectAll'])
			&& !$arguments['ShowSelectAll'])
				$this->show_select_all = 0;
			if($this->show_select_all)
			{
				$this->select_all = $this->GenerateInputID($form, $this->input, 'sa');
				$this->select_all_input = $this->GenerateInputID($form, $this->input, 'select_all');
				if(IsSet($arguments['SelectAllText']))
					$this->select_all_text = $arguments['SelectAllText'];
				if(strlen($error = $form->AddInput(array(
					'ID'=>$this->select_all_input,
					'TYPE'=>'checkbox',
					'ONCHANGE'=>$this->select_all.'(this.form, this.checked);',
					'TITLE'=>$this->select_all_text,
				))))
					return($error);
			}
		}
		else
		{
			if(!IsSet($arguments['VALUE'])
			|| !IsSet($this->options[$this->VALUE = strval($arguments['VALUE'])]))
				return('it was not specified a valid list select value');
		}
			
		if(IsSet($arguments['Columns'])
		&& (GetType($arguments['Columns'])!='array'
		|| count($this->columns = $arguments['Columns']) == 0))
			return('it was not specified a valid list select columns');
		if(IsSet($arguments['Rows']))
		{
			if(GetType($arguments['Rows'])!='array')
				return('it was not specified a valid list select rows');
			$this->rows = $arguments['Rows'];
		}
		if(IsSet($arguments['CLASS']))
			$this->CLASS = $arguments['CLASS'];
		if(IsSet($arguments['STYLE']))
			$this->STYLE = $arguments['STYLE'];
		if(IsSet($arguments['SIZE']))
			$this->SIZE = $arguments['SIZE'];
		$onchange = (IsSet($arguments['ONCHANGE']) ? $arguments['ONCHANGE'] : '');
		if(IsSet($arguments['Accessible']))
		{
			$accessible = $arguments['Accessible'];
		}
		if(IsSet($arguments['ReadOnlyMark']))
			$this->read_only_mark = $arguments['ReadOnlyMark'];
		if(IsSet($arguments['OptionReadOnlyMark']))
			$read_only_mark = $arguments['OptionReadOnlyMark'];
		$this->name = $this->GenerateInputID($form, $this->input, $this->multiple ? 'checkbox' : 'radio');
		$to = count($this->options);
		$select_all = $this->multiple;
		for($o = 0, Reset($this->options); $o < $to; Next($this->options), ++$o)
		{
			$option = Key($this->options);
			$a = array(
				'NAME'=>$this->name,
				'ID'=>$this->name.'_'.$option,
				'VALUE'=>$option,
			);
			if($o == 0)
			{
				if(IsSet($arguments['LABEL']))
					$a['LABEL'] = $arguments['LABEL'];
				if(IsSet($arguments['ACCESSKEY']))
					$a['ACCESSKEY'] = $arguments['ACCESSKEY'];
			}
			if($this->multiple)
			{
				$a['TYPE']='checkbox';
				$a['MULTIPLE']=1;
				if(IsSet($this->selected[$option]))
					$a['CHECKED'] = 1;
				else
					$select_all = false;
				if($o == 0
				&& $validate_as_set)
				{
					$a['ValidateAsSet'] = 1;
					if(IsSet($arguments['ValidateAsSetErrorMessage']))
						$a['ValidateAsSetErrorMessage'] = $arguments['ValidateAsSetErrorMessage'];
					if(IsSet($arguments['ValidationErrorMessage']))
						$a['ValidationErrorMessage'] = $arguments['ValidationErrorMessage'];
				}
			}
			else
			{
				$a['TYPE']='radio';
				if(!strcmp($option, $this->VALUE))
					$a['CHECKED'] = 1;
			}
			if(strlen($onchange))
				$a['ONCHANGE'] = $onchange;
			if(IsSet($accessible))
				$a['Accessible'] = $accessible;
			if(IsSet($read_only_mark))
				$a['ReadOnlyMark'] = $read_only_mark;
			if(strlen($error = $form->AddInput($a)))
				return($error);
		}
		if($select_all
		&& $this->show_select_all)
			$form->SetCheckedState($this->select_all_input, true);
		Reset($this->options);
		$this->focus_input = $this->name.'_'.Key($this->options);
		return('');
	}

	Function AddInputPart(&$form)
	{
		if(IsSet($this->accessible)
		&& !$this->accessible
		&& IsSet($this->read_only_mark))
			return($form->AddDataPart($this->read_only_mark));
		$co = $this->columns;
		$tc = count($co);
		for($start = '', $header = '<tr>', $h = $this->multiple, $i = $c = 0; $c < $tc; ++$c)
		{
			if(!IsSet($co[$c]['Type']))
				return('list select column '.$c.' does not have a type');
			$input = $co[$c]['Type'] == 'Input';
			if(IsSet($co[$c]['Header']))
			{
				$header .= '<th>'.$co[$c]['Header'];
				$end = '</th>';
				$h = 1;
			}
			else
			{
				$header .= '<td>';
				if(!$this->multiple
				|| !$input)
					$header .= '&nbsp;';
				$end = '</td>';
			}
			if($input)
			{
				++$i;
				if($this->multiple)
				{
					$start = $header;
					$header = '';
				}
			}
			$header .= $end;
		}
		if($i == 0)
			return('it was not specified any column with type Input');
		if($i > 1)
			return('it was specified more than one column with type Input');
		if(strlen($this->get_selected_option)
		|| $this->multiple)
		{
			$begin = $end = $set = '';
			$total = count($this->options);
			for($o = 0, Reset($this->options); $o < $total; Next($this->options), ++$o)
			{
				$option = Key($this->options);
				if(strlen($this->get_selected_option))
				{
					$checked = $form->GetJavascriptCheckedState('f', $this->name.'_'.$option);
					if(strlen($checked)==0)
						return('could not get Javascript to get the checked state of checkbox');
					$begin .= '('.$checked.' ? '.$form->EncodeJavascriptString($option).' : ';
					$end.=')';
				}
				if($this->multiple)
				{
					$checked = $form->GetJavascriptSetCheckedState('f', $this->name.'_'.$option, 'c');
					if(strlen($checked)==0)
						return('could not get Javascript to set the checked state of checkbox');
					$set .= $checked."\n";
				}
			}
			if(strlen($error = $form->AddDataPart('<script type="text/javascript"><!--'."\n".($this->get_selected_option ? 'function '.$this->get_selected_option.'(f)'."\n".'{'."\n".'return('.$begin.' null'.$end.')'."\n".'}'."\n" : '').(($this->multiple && $this->show_select_all) ? 'function '.$this->select_all.'(f, c)'."\n".'{'."\n".$set.'}'."\n" : '').'// --></script>'."\n")))
				return($error);
		}
		$to = count($this->options);
		if(strlen($error = $form->AddDataPart('<div style="'.(IsSet($this->SIZE) ? 'height: '.$this->SIZE.'em; ' : '').'overflow: auto;'.HtmlSpecialChars($this->STYLE).'"'.(strlen($this->CLASS) ? ' CLASS="'.HtmlSpecialChars($this->CLASS).'"' : '').'><table>')))
			return($error);
		if($h
		&& ((strlen($start)
		&& strlen($error = $form->AddDataPart($start)))
		|| ($this->multiple
		&& $this->show_select_all
		&& strlen($error = $form->AddInputPart($this->select_all_input)))
		|| strlen($error = $form->AddDataPart($header.'</tr>'))))
			return($error);
		for($o = 0, Reset($this->options); $o < $to; Next($this->options), ++$o)
		{
			if(strlen($error = $form->AddDataPart('<tr>')))
				return($error);
			$option = Key($this->options);
			for($c = 0; $c < $tc; ++$c)
			{
				if(strlen($error = $form->AddDataPart('<td>')))
					return($error);
				switch($co[$c]['Type'])
				{
					case 'Input':
						if(strlen($error = $form->AddInputPart($this->name.'_'.$option)))
							return($error);
						break;
					case 'Option':
						if(strlen($error = $form->AddDataPart(HtmlSpecialChars($this->options[$option]))))
							return($error);
						break;
					case 'Data':
						if(IsSet($co[$c]['Row'])
						&& IsSet($this->rows[$option][$row = $co[$c]['Row']])
						&& strlen($error = $form->AddDataPart($this->rows[$option][$row])))
							return($error);
						break;
				}
				if(strlen($error = $form->AddDataPart('</td>')))
					return($error);
			}
			if(strlen($error = $form->AddDataPart('</tr>')))
				return($error);
		}
		if(strlen($error = $form->AddDataPart('</table></div>')))
			return($error);
		return('');
	}

	Function AddInputHiddenPart(&$form)
	{
		$to = count($this->options);
		for($o = 0, Reset($this->options); $o < $to; Next($this->options), ++$o)
		{
			$option = Key($this->options);
			if(strlen($error = $form->AddInputHiddenPart($this->name.'_'.$option)))
				return($error);
		}
		return('');
	}

	Function GetInputValue(&$form)
	{
		if($this->multiple)
		{
			$total = count($this->options);
			$selected = array();
			for($o = 0, Reset($this->options); $o < $total; Next($this->options), ++$o)
			{
				$option = Key($this->options);
				if($form->GetCheckedState($this->name.'_'.$option))
					$selected[] = $option;
			}
			return($selected);
		}
		else
			return($form->GetCheckedRadioValue($this->name));
	}
	
	Function Connect(&$form, $to, $event, $action, &$context)
	{
		switch($event)
		{
			case 'ONCHANGE':
				$total = count($this->options);
				for($o = 0, Reset($this->options); $o < $total; Next($this->options), ++$o)
				{
					$option = Key($this->options);
					if(strlen($error = $form->Connect($this->name.'_'.$option, $to, $event, $action, $context)))
						return($error);
				}
				return('');
		}
		return($this->DefaultConnect($form, $to, $event, $action, $context));
	}

	Function GetJavascriptSelectedOption(&$form, $form_object)
	{
		if(strlen($this->get_selected_option) == 0)
			$this->get_selected_option = $this->GenerateInputID($form, $this->input, 'gso');
		return($this->get_selected_option.'('.$form_object.')');
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case 'ONCHANGE':
				$total = count($this->options);
				for($o = 0, Reset($this->options); $o < $total; Next($this->options), ++$o)
				{
					$option = Key($this->options);
					if(strlen($error = $form->SetInputProperty($this->name.'_'.$option, 'ONCHANGE', $value)))
						return($error);
				}
				return('');
			case 'VALUE':
				if($this->multiple)
				{
					if(GetType($value) != 'array')
						return("it was not defined a valid selected options array");
					$options = $this->options;
					$tv = count($value);
					for($v = 0; $v < $tv; ++$v)
					{
						$option = $value[$v];
						if(!IsSet($options[$option]))
							return($option.' is not a valid option to select');
						$form->SetCheckedState($this->name.'_'.$option, 1);
						UnSet($options[$option]);
					}
					$tv = count($options);
					for(Reset($options), $v = 0; $v < $tv; Next($options), ++$v)
						$form->SetCheckedState($this->name.'_'.Key($options), 0);
					if($this->show_select_all)
						$form->SetCheckedState($this->select_all_input, $tv === 0);
				}
				else
				{
					$old = $this->VALUE;
					if(!IsSet($this->options[$this->VALUE = strval($value)]))
						return('it was not specified a valid list select value');
					if(strlen($error = $form->SetCheckedState($this->name.'_'.$this->VALUE, 1))
					|| strlen($error = $form->SetCheckedState($this->name.'_'.$old, 0)))
						return($error);
				}
				return('');
			case 'Accessible':
				$error =  $this->DefaultSetInputProperty($form, $property, $value);
				if(strlen($error) == 0)
					$this->accessible = IsSet($value) ? intval($value) : $value;
				return($error);
			case 'ReadOnlyMark':
				$this->read_only_mark = $value;
				return('');
		}
		return($this->DefaultSetInputProperty($form, $property, $value));
	}

	Function LoadInputValues(&$form, $submitted)
	{
		$value = $this->GetInputValue($form);
		if($this->multiple)
		{
			if($submitted)
			{
				$selected=array();
				$changes=$this->selected;
				for($value_key=0, Reset($changes); $value_key<count($changes); Next($changes), $value_key++)
					$changes[Key($changes)]=0;
				if(GetType($value)=="array")
				{
					for($value_key=0,Reset($value);$value_key<count($value);Next($value),$value_key++)
					{
						$entry_value=$value[Key($value)];
						if(IsSet($changes[$entry_value]))
							Unset($changes[$entry_value]);
						else
							$changes[$entry_value]=1;
						$selected[$entry_value]=1;
					}
				}
				$this->selected = $selected;
				if(count($changes)==0)
					Unset($form->Changes[$this->input]);
				else
					$form->Changes[$this->input] = $changes;
				if($this->show_select_all)
					$form->SetCheckedState($this->select_all_input, count($this->selected) === count($this->options));
			}
		}
		else
		{
			if(strcmp($this->VALUE, $value))
				$form->Changes[$this->input] = $value;
			else
				Unset($form->Changes[$this->input]);
			$this->VALUE = $value;
		}
		return('');
	}
};

?>