<?php
/*
 *
 * @(#) $Id: form_crud.php,v 1.17 2016/12/24 03:33:03 mlemos Exp $
 *
 */

class form_crud_data_source_class
{
	var $crud = '';
	var $scaffolding_input = '';
	var $placeholder_start = '{';
	var $placeholder_end = '}';
	var $placeholder_section_end = '{/';
	var $entry_template = 'the entry template was not defined';
	var $entry_template_properties = array(
	);

	Function Initialize(&$form, $arguments)
	{
		return('');
	}

	Function Finalize(&$form)
	{
	}

	Function GetListing(&$form, &$listing)
	{
		return('data source object does not implement the GetListing function');
	}

	Function InitializeEntry(&$form, &$entry, &$invalid, &$values)
	{
		$invalid = 0;
		return('');
	}

	Function GetEntry(&$form, &$entry, &$invalid, &$values)
	{
		return('data source object does not implement the GetEntry function');
	}
	
	Function GetSaveInputs(&$form, &$inputs)
	{
		return($form->GetInputProperty($this->scaffolding_input, 'EntryFieldNames', $inputs));
	}

	Function SaveEntry(&$form, $creating, &$entry, &$values)
	{
		return('data source object does not implement the SaveEntry function');
	}

	Function ViewEntry(&$form, &$entry, &$values)
	{
		$output = $this->entry_template;
		$s = $this->placeholder_start;
		$e = $this->placeholder_end;
		$se = $this->placeholder_section_end;
		$properties = $this->entry_template_properties;
		$tv = count($properties);
		for($v = 0, Reset($properties); $v < $tv; Next($properties), ++$v)
		{
			$p = Key($properties);
			if(IsSet($properties[$p]['ConditionalSection']))
				$output = preg_replace('/'.str_replace('/', '\\/', preg_quote($s.$p.$e)).'(.*)'.str_replace('/', '\\/', preg_quote($se.$p.$e)).'/', IsSet($values[$p]) ? '\\1' : '', $output);
		}
		$tv = count($values);
		for($v = 0, Reset($values); $v < $tv; Next($values), ++$v)
		{
			$placeholder = Key($values);
			$value = $values[$placeholder];
			if(!IsSet($properties[$placeholder]['HTML'])
			|| $properties[$placeholder]['HTML'])
				$value = nl2br(HtmlSpecialChars($value));
			$output = str_replace($s.$placeholder.$e, $value, $output);
		}
		$entry['EntryOutput'] = $output;
		return('');
	}

	Function DeleteEntry(&$form, &$entry)
	{
		return('data source object does not implement the DeleteEntry function');
	}
};

class form_crud_class extends form_custom_class
{
	var $source;
	var $scaffolding_input;
	var $server_validate=0;

	Function SetInputProperties(&$form, $properties, $values)
	{
		$tp = count($properties);
		for($p = 0; $p < $tp; ++$p)
		{
			$property = $properties[$p];
			if(IsSet($values[$property])
			&& strlen($error = $form->SetInputProperty($this->scaffolding_input, $property, $values[$property])))
				return($error);
		}
		return('');
	}

	Function SaveEntry(&$form, $creating, $checkable, &$entry, &$values)
	{
		if(strlen($error_message = $form->Validate($verify, $this->scaffolding_input.'-submit')) == 0)
		{
			$tv = count($values);
			for(Reset($values), $v = 0; $v < $tv; Next($values), ++$v)
			{
				$value = Key($values);
				if(!IsSet($checkable[$value])
				&& strlen($error = $form->GetCheckable($value, $checkable[$value])))
					return($error);
				$values[$value] = ($checkable[$value] ? $form->GetCheckedState($value) : $form->GetInputValue($value));
			}
			if(strlen($error = $this->source->SaveEntry($form, $creating, $entry, $values)))
				return($error);
			$properties = array(
				$creating ? 'CreatedMessage' : 'UpdatedMessage',
			);
			$error = $this->SetInputProperties($form, $properties, $entry);
		}
		else
			$error = '';
		return($error);
	}

	Function AddInput(&$form, $arguments)
	{
		if(!IsSet($arguments['DataSourceClass']))
			return('it was not defined the CRUD data source class');
		if(function_exists('class_exists')
		&& !class_exists($arguments['DataSourceClass']))
			return('it was specified an inexisting CRUD data source class '.$arguments['DataSourceClass']);
		$this->source = new $arguments['DataSourceClass'];
		if(!IsSet($arguments['ScaffoldingInput']))
			return('it was not specified the scaffolding input identifier');
		$this->source->scaffolding_input = $this->scaffolding_input = $arguments['ScaffoldingInput'];
		return($this->source->Initialize($form, $arguments));
	}

	Function PostMessage(&$form, $message, &$processed)
	{
		switch($message['Event'])
		{
			case 'listing':
				$listing = array(
					'Page'=>$message['Page'],
				);
				if(strlen($error = $this->source->GetListing($form, $listing)))
					return($error);
				$properties = array(
					'Rows',
					'IDColumn',
					'Columns',
					'TotalEntries',
					'PageEntries',
					'Page',
					'ListingMessage'
				);
				if(strlen($error = $this->SetInputProperties($form, $properties, $listing)))
					return($error);
				break;

			case 'viewing':
				$invalid = 0;
				$entry = $values = array();
				if(strlen($error = $form->GetInputProperty($this->scaffolding_input, 'Entry', $entry['ID']))
				|| strlen($error = $this->source->GetEntry($form, $entry, $invalid, $values))
				|| (!$invalid
				&& strlen($error = $this->source->ViewEntry($form, $entry, $values))))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				$properties = array(
					'ViewingMessage',
					'EntryOutput'
				);
				if(strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				break;

			case 'creating':
				$invalid = 0;
				$entry = $values = array();
				if(strlen($error = $this->source->InitializeEntry($form, $entry, $invalid, $values)))
					return($error);
				$properties = array(
					'CreateCanceledMessage',
					'CreateMessage',
				);
				if(count($entry)
				&& strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				$tv = count($values);
				$checkable = array();
				for(Reset($values), $v = 0; $v < $tv; Next($values), ++$v)
				{
					$value = Key($values);
					if(strlen($error = $form->GetCheckable($value, $checkable[$value]))
					|| strlen($error = ($checkable[$value] ? $form->SetCheckedState($value, $values[$value]) : $form->SetInputValue($value, $values[$value]))))
						return($error);
				}
				if(strlen($error = $form->LoadInputValues(strlen($form->WasSubmitted('')) != 0)))
					return($error);
				break;

			case 'created':
				$invalid = 0;
				$entry = $values = array();
				if(strlen($error = $this->source->InitializeEntry($form, $entry, $invalid, $values)))
					return($error);
				$properties = array(
					'CreateCanceledMessage',
					'CreatedMessage',
				);
				if(count($entry)
				&& strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				if(strlen($error = $this->source->GetSaveInputs($form, $inputs)))
					return($error);
				$values = array();
				$tv = count($inputs);
				for($v = 0; $v < $tv; ++$v)
					$values[$inputs[$v]] = '';
				$checkable = array();
				$properties = array(
					'CreatedMessage',
					'CreateCanceledMessage',
					'SubmitLabel',
					'CancelLabel',
				);
				if(strlen($error = $this->SaveEntry($form, 1, $checkable, $entry, $values))
				|| strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				break;

			case 'updating':
			case 'updated':
				$invalid = 0;
				$entry = $values = array();
				if(strlen($error = $form->GetInputProperty($this->scaffolding_input, 'Entry', $entry['ID']))
				|| strlen($error = $this->source->GetEntry($form, $entry, $invalid, $values)))
					return($error);
				$properties = array(
					'UpdateCanceledMessage',
					'UpdateMessage',
					'UpdatedMessage',
					'SubmitLabel',
					'CancelLabel'
				);
				if(strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				$tv = count($values);
				$checkable = array();
				for(Reset($values), $v = 0; $v < $tv; Next($values), ++$v)
				{
					$value = Key($values);
					if(strlen($error = $form->GetCheckable($value, $checkable[$value]))
					|| strlen($error = ($checkable[$value] ? $form->SetCheckedState($value, $values[$value]) : $form->SetInputValue($value, $values[$value]))))
						return($error);
				}
				if(strlen($error = $form->LoadInputValues(strlen($form->WasSubmitted('')) != 0)))
					return($error);
				if(!strcmp($message['Event'], 'updated'))
				{
					$properties = array(
						'UpdatedMessage',
					);
					if(strlen($error = $this->SaveEntry($form, 0, $checkable, $entry, $values))
					|| strlen($error = $this->SetInputProperties($form, $properties, $entry)))
						return($error);
				}
				break;

			case 'deleted':
			case 'deleting':
				$invalid = 0;
				$entry = $values = array();
				if(strlen($error = $form->GetInputProperty($this->scaffolding_input, 'Entry', $entry['ID']))
				|| strlen($error = $this->source->GetEntry($form, $entry, $invalid, $values)))
					return($error);
				$properties = array(
					'DeleteMessage',
					'DeleteCanceledMessage',
					'DeletedMessage',
					'SubmitLabel',
					'CancelLabel',
				);
				if(strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
				if($invalid)
				{
					$message['Cancel'] = 1;
					break;
				}
				if(strlen($error_message = $form->Validate($verify, $this->scaffolding_input.'-delete')) == 0
				&& !strcmp($message['Event'], 'deleted'))
				{
					$properties = array(
						'DeletedMessage',
					);
					if(strlen($error = $this->source->DeleteEntry($form, $entry))
					|| strlen($error = $this->SetInputProperties($form, $properties, $entry)))
						return($error);
				}
				break;

			case 'update_canceled':
			case 'delete_canceled':
				if(strlen($error = $form->GetInputProperty($this->scaffolding_input, 'Entry', $entry['ID']))
				|| strlen($error = $this->source->GetEntry($form, $entry, $invalid, $values)))
					return($error);
				$properties = array(
					'UpdateCanceledMessage',
					'DeleteMessage',
					'DeleteCanceledMessage',
					'DeletedMessage',
					'SubmitLabel',
					'CancelLabel',
				);
				if(strlen($error = $this->SetInputProperties($form, $properties, $entry)))
					return($error);
			case 'create_canceled':
				break;

			default:
				return($form->OutputError('form custom input is not yet ready to handle '.$message['Event'].' events', $this->input));
		}
		return($form->ReplyMessage($message, $processed));
	}
};

?>