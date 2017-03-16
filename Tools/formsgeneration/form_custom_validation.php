<?php
/*
 *
 * @(#) $Id: form_custom_validation.php,v 1.4 2008/06/04 20:55:06 mlemos Exp $
 *
 */

if(!defined("PHP_LIBRARY_CUSTOM_VALIDATION_FORMS"))
{
	define("PHP_LIBRARY_CUSTOM_VALIDATION_FORMS",1);

class form_custom_validation_class extends form_custom_class
{
	/*
	 *  Tell the forms class that this custom input will perform both server
	 *  side and client side validation.
	 */
	var $client_validate=1;
	var $server_validate=1;

	var $first='';
	var $second='';
	var $first_validation_error_message='The first input value is contained in the second.';
	var $second_validation_error_message='The second input value is contained in the first.';

	Function AddInput(&$form, $arguments)
	{
		/*
		 *  Get the identifiers of the inputs to validate
		 */
		if(!IsSet($arguments['FirstInput'])
		|| strlen($this->first=$arguments['FirstInput'])==0)
				return('It was not specified a valid first input identifier');
		if(!IsSet($arguments['SecondInput'])
		|| strlen($this->second=$arguments['SecondInput'])==0)
				return('It was not specified a valid second input identifier');

		/*
		 *  Get the error messages to assign when the inputs are invalid.
		 */
		if(IsSet($arguments['FirstValidationErrorMessage']))
		{
			if(strlen($arguments['FirstValidationErrorMessage'])==0)
				return('It was not specified a valid first validation error message');
			$this->first_validation_error_message=$arguments['FirstValidationErrorMessage'];
		}
		if(IsSet($arguments['SecondValidationErrorMessage']))
		{
			if(strlen($arguments['SecondValidationErrorMessage'])==0)
				return('It was not specified a valid second validation error message');
			$this->second_validation_error_message=$arguments['SecondValidationErrorMessage'];
		}

		return('');
	}

	Function ValidateInput(&$form)
	{
		/*
		 *  Perform server side validation by checking whether one of the
		 *  input values contains the other.
		 *
		 *  This function is called after all validations were performed on
		 *  all basic inputs.
		 */
		$first=$form->GetInputValue($this->first);
		$second=$form->GetInputValue($this->second);
		if(strlen($first)
		&& strstr($second, $first))
		{
			$form->FlagInvalidInput($this->first, $this->first_validation_error_message);
			return('');
		}
		if(strlen($second)
		&& strstr($first, $second))
		{
			$form->FlagInvalidInput($this->second, $this->second_validation_error_message);
			return('');
		}
		return('');
	}

	Function GetJavascriptValidations(&$form, $form_object, &$validations)
	{
		/*
		 *  Generate all the necessary Javascript to perform client side
		 *  validation.
		 */
		if(strlen($first=$form->GetJavascriptInputValue($form_object,$this->first))==0)
			return('it was not possible to retrieve the first input Javascript value');
		if(strlen($second=$form->GetJavascriptInputValue($form_object,$this->second))==0)
			return('it was not possible to retrieve the second input Javascript value');

		/*
		 *  Return an array with a list of all validation checks to be
		 *  performed.
		 */
		$validations=array();
		$validations[]=array(

			/*
			 *  Each.validation check may be preceed by a list of Javascript
			 *  commands that are executed before each validation is performed.
			 */
			'Commands'=>array(
				'first='.$first,
				'second='.$second,
			),

			/*
			 *  The condition is a boolean Javascript expression that is true
			 *  when the input is invalid.
			 */
			'Condition'=>'second.indexOf(first) != -1',

			/*
			 *  Error message associated to the invalid input
			 */
			'ErrorMessage'=>$this->first_validation_error_message,

			/*
			 *  Input that gets the user input focus so the user fixes its value
			 *  to make the input valid
			 */
			'Focus'=>$this->first
		);
		$validations[]=array(
			'Commands'=>array(),
			'Condition'=>'first.indexOf(second) != -1',
			'ErrorMessage'=>$this->second_validation_error_message,
			'Focus'=>$this->second
		);
		return('');
	}

	Function AddInputPart(&$form)
	{
		/*
		 *  Inputs that do not appear in the form must implement an empty
		 *  AddInputPart function.
		 */
		return('');
	}
};

}

?>