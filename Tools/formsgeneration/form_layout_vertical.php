<?php
/*
 *
 * @(#) $Id: form_layout_vertical.php,v 1.16 2013/08/12 13:32:10 mlemos Exp $
 *
 */

class form_layout_vertical_class extends form_custom_class
{
	var $inputs = array();
	var $columns = array();
	var $data = array();
	var $properties = array();
	var $header = '<table>';
	var $footer = '</table>';
	var $columns_header = '<table><tr>';
	var $columns_footer = '</tr></table>';
	var $column_start = '<td valign="top">';
	var $column_end = '</td>';
	var $input_format = "<tr><td>{label}:</td><td>{input}&nbsp;<span id=\"mark_{id}\">{mark}</span></td></tr>\n";
	var $switched_position_input_format = "<tr><td align=\"right\">{input}</td><td>{label}&nbsp;<span id=\"mark_{id}\">{mark}</span></td></tr>\n";
	var $no_label_input_format = "<tr><td colspan=\"2\" align=\"center\">{input}</td></tr>\n";
 	var $centered_group_left_input_format='<tr><td colspan="2" align="center">{input}';
 	var $centered_group_middle_input_format="&nbsp;{input}";
 	var $centered_group_right_input_format="&nbsp;{input}</td></tr>\n";
	var $invalid_mark = '[X]';
	var $default_mark = '';
	var $server_validate = 0;
	var $mark_prefix = 'mark_';

	Function AddInput(&$form, $arguments)
	{
		if(IsSet($arguments['Columns']))
		{
			if(GetType($arguments['Columns']) != 'array'
			|| count(($arguments['Columns']))==0)
				return('it was not specified a valid list of input columns to layout');
			$this->columns = $arguments['Columns'];
		}
		elseif(!IsSet($arguments['Inputs'])
		|| GetType($arguments['Inputs']) != 'array'
		|| count(($this->inputs = $arguments['Inputs']))==0)
			return('it was not specified a valid list of inputs to layout');
		if(IsSet($arguments['Data']))
		{
			if(GetType($arguments['Data']) != 'array')
				return('it was not specified a valid list of data elements to layout');
			$this->data = $arguments['Data'];
		}
		if(IsSet($arguments['Properties']))
		{
			if(GetType($arguments['Properties']) != 'array')
				return('it was not specified a valid list of input properties');
			$this->properties = $arguments['Properties'];
		}
		if(IsSet($arguments['DefaultMark']))
			$this->default_mark = $arguments['DefaultMark'];
		if(IsSet($arguments['InvalidMark']))
		{
			if(strlen($arguments['InvalidMark'])==0)
				return('it was not specified a valid input invalid mark');
			$this->invalid_mark = $arguments['InvalidMark'];
		}
		if(IsSet($arguments['InputFormat']))
		{
			if(strlen($arguments['InputFormat'])==0)
				return('it was not specified a valid input format template');
			$this->input_format = $arguments['InputFormat'];
		}
		if(IsSet($arguments['SwitchedPositionInputFormat']))
		{
			if(strlen($arguments['SwitchedPositionInputFormat'])==0)
				return('it was not specified a valid switched position input format template');
			$this->switched_position_input_format = $arguments['SwitchedPositionInputFormat'];
		}
		if(IsSet($arguments['CenteredGroupLeftInputFormat']))
		{
			if(strlen($arguments['CenteredGroupLeftInputFormat'])==0)
				return('it was not specified a valid centered group left input format template');
			$this->centered_group_left_input_format = $arguments['CenteredGroupLeftInputFormat'];
		}
		if(IsSet($arguments['CenteredGroupMiddleInputFormat']))
		{
			if(strlen($arguments['CenteredGroupMiddleInputFormat'])==0)
				return('it was not specified a valid centered group middle input format template');
			$this->centered_group_middle_input_format = $arguments['CenteredGroupMiddleInputFormat'];
		}
		if(IsSet($arguments['CenteredGroupRightInputFormat']))
		{
			if(strlen($arguments['CenteredGroupRightInputFormat'])==0)
				return('it was not specified a valid centered group right input format template');
			$this->centered_group_right_input_format = $arguments['CenteredGroupRightInputFormat'];
		}
		if(IsSet($arguments['NoLabelInputFormat']))
		{
			if(strlen($arguments['NoLabelInputFormat'])==0)
				return('it was not specified a valid no label input format template');
			$this->no_label_input_format = $arguments['NoLabelInputFormat'];
		}
		if(IsSet($arguments['Header']))
		{
			if(strlen($arguments['Header'])==0)
				return('it was not specified a valid header template');
			$this->header = $arguments['Header'];
		}
		if(IsSet($arguments['Footer']))
		{
			if(strlen($arguments['Footer'])==0)
				return('it was not specified a valid footer template');
			$this->footer = $arguments['Footer'];
		}
		return('');
	}

	Function AddInputParts(&$form, $inputs)
	{
		if(strlen($error = $form->AddDataPart($this->header)))
			return($error);
		$ti = count($inputs);
		$valid_marks=array(
			'dynamicinput'=>array(
				'input'=>'input',
			),
			'dynamiclabel'=>array(
				'label'=>'input'
			),
			'dynamicdata'=>array(
				'mark'=>'mark',
				'id'=>'id'
			)
		);
		$parsed = $parsed_switched_position = $parsed_no_label = $parsed_centered_group_left = $parsed_centered_group_middle = $parsed_centered_group_right = 0;
		$hidden = array();
		for($i = 0; $i < $ti; $i++)
		{
			$input = $inputs[$i];
			if(IsSet($this->properties[$input]['Hidden'])
			&& $this->properties[$input]['Hidden'])
			{
				$hidden[] = $input;
				continue;
			}
			if(IsSet($this->properties[$input]['Visible'])
			&& !$this->properties[$input]['Visible'])
				continue;
			if(IsSet($this->data[$input]))
			{
				if(strlen(($error=$form->AddDataPart($this->data[$input]))))
					return($error);
				continue;
			}
			$read_only=(IsSet($this->properties[$input]['ReadOnly']) && $this->properties[$input]['ReadOnly']);
			$dynamic=array(
				'input'=>$input,
				'mark'=>(IsSet($form->Invalid[$input]) ? (IsSet($this->properties[$input]['InvalidMark']) ? $this->properties[$input]['InvalidMark'] : $this->invalid_mark) : (IsSet($this->properties[$input]['DefaultMark']) ? $this->properties[$input]['DefaultMark'] : $this->default_mark)),
				'id'=>$input
			);
			if(IsSet($this->properties[$input]['InputFormat']))
			{
				if(strlen($error = $this->ParseFormat($this->properties[$input]['InputFormat'], $valid_marks, $custom_data, $custom_marks))
				|| strlen($error = $this->AddFormattedDynamicPart($form, $custom_data, $custom_marks, 0, $read_only, $dynamic)))
					return($error);
			}
			else
			{
				UnSet($label);
				$form->GetInputProperty($input, 'LABEL', $label);
				if(IsSet($label))
				{
					if(IsSet($this->properties[$input]['SwitchedPosition'])
					&& $this->properties[$input]['SwitchedPosition'])
					{
						if(!$parsed_switched_position)
						{
							if(strlen($error = $this->ParseFormat($this->switched_position_input_format, $valid_marks, $data_switched_position, $marks_switched_position)))
								return($error);
							$parsed_switched_position = 1;
						}
						if(strlen($error = $this->AddFormattedDynamicPart($form, $data_switched_position, $marks_switched_position, 0, $read_only, $dynamic)))
							return($error);
					}
					else
					{
						if(!$parsed)
						{
							if(strlen($error = $this->ParseFormat($this->input_format, $valid_marks, $data, $marks)))
								return($error);
							$parsed = 1;
						}
						if(strlen($error = $this->AddFormattedDynamicPart($form, $data, $marks, 0, $read_only, $dynamic)))
							return($error);
					}
				}
				else
				{
					if(IsSet($this->properties[$input]['CenteredGroup']))
					{
						switch($this->properties[$input]['CenteredGroup'])
						{
							case 'left':
								if(!$parsed_centered_group_left)
								{
									if(strlen($error = $this->ParseFormat($this->centered_group_left_input_format, $valid_marks, $data_centered_group_left, $marks_centered_group_left)))
										return($error);
									$parsed_centered_group_left = 1;
								}
								if(strlen($error = $this->AddFormattedDynamicPart($form, $data_centered_group_left, $marks_centered_group_left, 0, $read_only, $dynamic)))
									return($error);
								break;
							case 'middle':
								if(!$parsed_centered_group_middle)
								{
									if(strlen($error = $this->ParseFormat($this->centered_group_middle_input_format, $valid_marks, $data_centered_group_middle, $marks_centered_group_middle)))
										return($error);
									$parsed_centered_group_middle = 1;
								}
								if(strlen($error = $this->AddFormattedDynamicPart($form, $data_centered_group_middle, $marks_centered_group_middle, 0, $read_only, $dynamic)))
									return($error);
								break;
							case 'right':
								if(!$parsed_centered_group_right)
								{
									if(strlen($error = $this->ParseFormat($this->centered_group_right_input_format, $valid_marks, $data_centered_group_right, $marks_centered_group_right)))
										return($error);
									$parsed_centered_group_right = 1;
								}
								if(strlen($error = $this->AddFormattedDynamicPart($form, $data_centered_group_right, $marks_centered_group_right, 0, $read_only, $dynamic)))
									return($error);
								break;
						}
					}
					else
					{
						if(!$parsed_no_label)
						{
							if(strlen($error = $this->ParseFormat($this->no_label_input_format, $valid_marks, $data_no_label, $marks_no_label)))
								return($error);
							$parsed_no_label = 1;
						}
						if(strlen($error = $this->AddFormattedDynamicPart($form, $data_no_label, $marks_no_label, 0, $read_only, $dynamic)))
							return($error);
					}
				}
			}
		}
		if(strlen($error = $form->AddDataPart($this->footer)))
			return($error);
		$ti = count($hidden);
		for($i = 0; $i < $ti; ++$i)
		{
			if(strlen($error = $form->AddInputHiddenPart($hidden[$i])))
				return($error);
		}
		return '';
	}

	Function MarkValidated(&$form, $form_object, $document_object, $event, $context, $inputs, &$javascript)
	{
		$ti = count($inputs);
		for($i = 0; $i < $ti; $i++)
		{
			$input = $inputs[$i];
			if((IsSet($this->properties[$input]['Visible'])
			&& !$this->properties[$input]['Visible'])
			|| IsSet($this->data[$input]))
				continue;
			$mark = (IsSet($form->Invalid[$input]) ? (IsSet($this->properties[$input]['InvalidMark']) ? $this->properties[$input]['InvalidMark'] : $this->invalid_mark) : (IsSet($this->properties[$input]['DefaultMark']) ? $this->properties[$input]['DefaultMark'] : $this->default_mark));
			$id = $this->mark_prefix.$input;
			if(strlen($error = $form->GetJavascriptConnectionAction($form_object, $this->input, $input, $event, 'MarkValidated', $context, $js)))
				return($error);
			$javascript .= $js.'if(d='.$document_object.'.getElementById('.$form->EncodeJavascriptString($id).')) d.innerHTML='.$form->EncodeJavascriptString($mark).';';
		}
		return('');
	}

	Function AddInputPart(&$form)
	{
		if(($tc = count($this->columns)))
		{
			if(strlen($error = $form->AddDataPart($this->columns_header)))
				return($error);
			for($c = 0; $c < $tc; ++$c)
			{
				if(strlen($error = $form->AddDataPart($this->column_start))
				|| strlen($error = $this->AddInputParts($form, $this->columns[$c]))
				|| strlen($error = $form->AddDataPart($this->column_end)))
					return($error);
			}
			return($form->AddDataPart($this->columns_footer));
		}
		return($this->AddInputParts($form, $this->inputs));
	}

	Function GetInputsContainedInputs(&$form, $kind, &$contained, $inputs)
	{
		$ti = count($inputs);
		for($i = 0; $i < $ti; ++$i)
		{
			$input = $inputs[$i];
			if(IsSet($this->data[$input]))
				continue;
			if(strlen($error = $form->GetContainedInputs($input, $kind, $input_contained)))
				return($error);
			$tc = count($input_contained);
			for($c = 0; $c < $tc; ++$c)
				$contained[] = $input_contained[$c];
		}
	}
	
	Function GetContainedInputs(&$form, $kind, &$contained)
	{
		$contained = array($this->input);
		if(($tc = count($this->columns)))
		{
			for($c = 0; $c < $tc; ++$c)
			{
				if(strlen($error = $this->GetInputsContainedInputs($form, $kind, $contained, $this->columns[$c])))
					return($error);
			}
			return('');
		}
		return($this->GetInputsContainedInputs($form, $kind, $contained, $this->inputs));
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'MarkValidated':
				$document_object = (IsSet($context['Document']) ? $context['Document'] : 'document');
				$javascript = 'var d;';
				if(($tc = count($this->columns)))
				{
					for($c = 0; $c < $tc; ++$c)
					{
						if(strlen($error = $this->MarkValidated($form, $form_object, $document_object, $event, $context, $this->columns[$c], $javascript)))
							return($error);
					}
				}
				return($this->MarkValidated($form, $form_object, $document_object, $event, $context, $this->inputs, $javascript));

			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $document_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}
};

?>