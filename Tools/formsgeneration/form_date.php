<?php
/*
 *
 * @(#) $Id: form_date.php,v 1.34 2013/01/09 10:20:33 mlemos Exp $
 *
 */

if(!defined("PHP_LIBRARY_DATE_FORMS"))
{
	define("PHP_LIBRARY_DATE_FORMS",1);

class form_date_class extends form_custom_class
{
	var $format="{year}-{month}-{day}";
	var $hide_day_format = '{year}-{month}';
	var $choose_format = '{choose} {date}';
	var $validation_start_date="";
	var $validation_start_date_error_message="";
	var $validation_end_date="";
	var $validation_end_date_error_message="";
	var $invalid_date_error_message="It was not specified a valid date.";
	var $invalid_year_error_message="It was not specified a valid year.";
	var $invalid_month_error_message="It was not specified a valid month.";
	var $invalid_day_error_message="It was not specified a valid day.";
	var $client_validate=1;
	var $server_validate=1;
	var $select_years=20;
	var $fixed_day = 0;
	var $hide_day = 0;
	var $ask_age = 0;

	var $year="";
	var $month="";
	var $day="";
	var $optional=0;
	var $choose_control = 0;
	var $choose = 1;
	var $choose_input = '';
	var $value = '';
	var $choice_default_value;

	Function ValidateDateValue($year, $month, $day)
	{
		if(strlen($year))
		{
			while(!strcmp($year[0],"0"))
				$year=substr($year,1);
		}
		if(strcmp($year,intval($year))
		|| intval($year)<=0)
			return($this->invalid_year_error_message);
		switch($month)
		{
			case "01":
			case "03":
			case "05":
			case "07":
			case "08":
			case "10":
			case "12":
				$month_days=31;
				break;
			case "02":
				$is_leap_year=(($year % 4)==0 && (($year % 100)!=0 || ($year % 400)==0));
				$month_days=($is_leap_year ? 29 : 28);
				break;
			case "04":
			case "06":
			case "09":
			case "11":
				$month_days=30;
				break;
			default:
				return($this->invalid_month_error_message);
		}
		if(strlen($day))
		{
			while(!strcmp($day[0],"0"))
				$day=substr($day,1);
		}
		if(strcmp($day,intval($day))
		|| $day>$month_days)
			return($this->invalid_day_error_message);
		return("");
	}

	Function ValidateDate($date, &$year, &$month, &$day)
	{
		if(!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date, $matches))
			return($this->invalid_date_error_message);
		$year=$matches[1];
		$month=$matches[2];
		$day=$matches[3];
		return($this->ValidateDateValue($year, $month, $day));
	}

	Function AddInput(&$form, $arguments)
	{
		$this->year=$this->GenerateInputID($form, $this->input, "year");
		$this->month=$this->GenerateInputID($form, $this->input, "month");
		$this->day=$this->GenerateInputID($form, $this->input, "day");
		$this->valid_marks=array(
			"input"=>array(
				"year"=>$this->year,
				"month"=>$this->month,
				"day"=>$this->day
			)
		);
		$validation_error_message=(IsSet($arguments["ValidationErrorMessage"]) ? $arguments["ValidationErrorMessage"] : "");
		if(IsSet($arguments["ValidationDateErrorMessage"])
		&& strlen($this->invalid_date_error_message = $arguments["ValidationDateErrorMessage"])==0)
			return("it was not specified a valid date validation error message");
		if(IsSet($arguments["ValidationYearErrorMessage"])
		&& strlen($this->invalid_year_error_message = $arguments["ValidationYearErrorMessage"])==0)
			return("it was not specified a valid year validation error message");
		if(IsSet($arguments["ValidationMonthErrorMessage"])
		&& strlen($this->invalid_month_error_message = $arguments["ValidationMonthErrorMessage"])==0)
			return("it was not specified a valid month validation error message");
		if(IsSet($arguments["ValidationDayErrorMessage"])
		&& strlen($this->invalid_day_error_message = $arguments["ValidationDayErrorMessage"])==0)
			return("it was not specified a valid day validation error message");
		$today = strftime('%Y-%m-%d');
		if(IsSet($arguments["ValidationStartDate"]))
		{
			$start_date = $arguments["ValidationStartDate"];
			if(!strcmp($start_date, 'now'))
				$start_date = $today;
			if(strlen($error=$this->ValidateDate($start_date, $start_year, $start_month, $start_day)))
				return($error);
			$this->validation_start_date_error_message=(IsSet($arguments["ValidationStartDateErrorMessage"]) ? $arguments["ValidationStartDateErrorMessage"] : $validation_error_message);
			if(strlen($this->validation_start_date_error_message)==0)
				return("it was not specified a valid start date validation error message");
			$this->validation_start_date=$start_date;
		}
		else
		  $start_year=0;
		if(IsSet($arguments["ValidationEndDate"]))
		{
			$end_date = $arguments["ValidationEndDate"];
			if(!strcmp($end_date, 'now'))
				$end_date = $today;
			if(strlen($error=$this->ValidateDate($end_date, $end_year, $end_month, $end_day)))
				return($error);
			$this->validation_end_date_error_message=(IsSet($arguments["ValidationEndDateErrorMessage"]) ? $arguments["ValidationEndDateErrorMessage"] : $validation_error_message);
			if(strlen($this->validation_end_date_error_message)==0)
				return("it was not specified a valid end date validation error message");
			$this->validation_end_date=$end_date;
		}
		else
		  $end_year=0;
		$this->optional=(IsSet($arguments["Optional"]) && $arguments["Optional"]);
		$this->choose_control=(IsSet($arguments["ChooseControl"]) && $arguments["ChooseControl"]);
		if($this->choose_control)
		{
			$this->choose=(!IsSet($arguments["Choose"]) || $arguments["Choose"]);
			$this->choose_input=$this->GenerateInputID($form, $this->input, "choose");
			$this->valid_marks["input"]["choose"] = $this->choose_input;
			if(IsSet($arguments['ChoiceDefaultValue']))
			{
				$value = $arguments['ChoiceDefaultValue'];
				if(strlen($value))
				{
					if(!strcmp($value, 'now'))
						$value = $today;
					if(strlen($error=$this->ValidateDate($value, $current_year, $current_month, $current_day)))
						return($error);
				}
				$this->choice_default_value = $value;
			}
		}
		if(IsSet($arguments["VALUE"])
		&& strlen($arguments["VALUE"]))
		{
			$value = $arguments["VALUE"];
			if(!strcmp($value, 'now'))
				$value = $today;
			if(strlen($error=$this->ValidateDate($value, $current_year, $current_month, $current_day)))
				return($error);
			$this->value = $value;
		}
		else
			$this->value = $current_year = $current_month = $current_day = "";
		if(IsSet($arguments['AskAge'])
		&& $arguments['AskAge'])
			$this->ask_age = 1;
		if(IsSet($arguments['FixedDay']))
		{
			$fixed_day = $arguments['FixedDay'];
			if($fixed_day <= 0
			|| $fixed_day > 31)
				return("it was not specified a valid fixed day");
			$current_day = ($fixed_day < 10 ? '0' : '').strval($this->fixed_day = $fixed_day);
		}
		if(IsSet($arguments['HideDay'])
		&& $arguments['HideDay'])
		{
			if(!$this->fixed_day)
				return("days can only be hidden if a fixed day was set");
			$this->hide_day = 1;
			
		}
		$format=(IsSet($arguments["Format"]) ? $arguments["Format"] : ($this->hide_day ? $this->hide_day_format : $this->format));
		if($this->choose_control)
		{
			$format = str_replace('{date}', $format, $this->choose_format);
			if(strlen($error = $form->AddInput(array(
				"NAME"=>$this->choose_input,
				"ID"=>$this->choose_input,
				"TYPE"=>"checkbox",
				"CHECKED"=>$this->choose,
				"ONCHANGE"=>$form->GetJavascriptInputObject('this.form', $this->year).'.disabled = '.$form->GetJavascriptInputObject('this.form', $this->month).'.disabled = '.($this->hide_day ? '' : $form->GetJavascriptInputObject('this.form', $this->day).'.disabled = ').'!this.checked;'
			))))
				return($error);
		}
		if(strlen($error=$this->DefaultSetInputProperty($form, "Format", $format)))
			return($error);
		$month_options=array();
		if($this->optional
		|| strlen($current_month) == 0)
			$month_options[''] = '';
		if($this->ask_age)
		{
			for($month=0; $month<12; $month++)
				$month_options[$month]=$month;
		}
		elseif(IsSet($arguments["Months"]))
		{
			for($month=1; $month<=12; $month++)
			{
				$month_value=sprintf("%02d", $month);
				if(!IsSet($arguments["Months"][$month_value]))
					return("it was not specified the value for month ".$month_value);
				$month_options[$month_value]=$arguments["Months"][$month_value];
			}
		}
		else
		{
			for($month=1; $month<=12; $month++)
			{
				$month_value=sprintf("%02d", $month);
				$month_options[$month_value]=$month_value;
			}
		}
		$day_options=array(""=>"");
		for($day=1; $day<=31; $day++)
			$day_options[sprintf("%02d",$day)]=sprintf("%2d",$day);
		if(IsSet($arguments["SelectYears"]))
		{
			if(GetType($select_years=$arguments["SelectYears"])!="integer"
			|| $select_years<0)
				return("it was not specified a valid select years value");
			$this->select_years=$select_years;
		}
		$this_year = intval(substr($today, 0, 4));
		$current_year = ((strlen($current_year) && $this->ask_age) ? $this_year - $current_year : $current_year);
		if(strlen($current_month)
		&& $this->ask_age)
		{
			$this_month = intval(strftime('%m'));
			$current_month = $this_month - $current_month;
			if($current_month < 0)
			{
				$current_month += 12;
				--$current_year;
			}
		}
		if($start_year
		&& $end_year
		&& $start_year<=$end_year
		&& $end_year-$start_year<$this->select_years)
		{
			$years=array();
			if($this->optional
			|| strlen($current_year) == 0)
				$years[""]="";
			if($this->ask_age)
			{
				for($year = $end_year; $year >= $start_year; --$year)
				{
					$age = $this_year - $year;
					$years[strval($age)]=sprintf('%4d', $age);
				}
			}
			else
			{
				for($year=$start_year;$year<=$end_year;$year++)
					$years[strval($year)]=strval($year);
			}
			$year_arguments=array(
				"NAME"=>$this->year,
				"ID"=>$this->year,
				"TYPE"=>"select",
				"VALUE"=>$current_year,
				"OPTIONS"=>$years,
				"ValidationErrorMessage"=>$this->invalid_year_error_message
			);
		}
		else
		{
			$year_arguments=array(
				"NAME"=>$this->year,
				"ID"=>$this->year,
				"TYPE"=>"text",
				"MAXLENGTH"=>4,
				"SIZE"=>5,
				"VALUE"=>$current_year,
				"ValidateAsInteger"=>1,
				"ValidationLowerLimit"=>1,
				"ValidationErrorMessage"=>$this->invalid_year_error_message
			);
		}
		$month_arguments=array(
			"NAME"=>$this->month,
			"ID"=>$this->month,
			"TYPE"=>"select",
			"OPTIONS"=>$month_options,
			"VALUE"=>$current_month,
			"ValidationErrorMessage"=>$this->invalid_month_error_message
		);
		$day_arguments=array(
			"NAME"=>$this->day,
			"ID"=>$this->day,
			"TYPE"=>"select",
			"OPTIONS"=>$day_options,
			"VALUE"=>$current_day,
			"ValidationErrorMessage"=>$this->invalid_day_error_message
		);
		if(IsSet($arguments['DependentValidation']))
			$year_arguments['DependentValidation'] = $month_arguments['DependentValidation'] = $day_arguments['DependentValidation'] = $arguments['DependentValidation'];
		if($this->choose_control)
		{
/*			$day_arguments['DependentValidation'] = $month_arguments['DependentValidation'] = $year_arguments['DependentValidation'] = $this->choose_input;
*/
			if(!$this->choose)
				$day_arguments['ExtraAttributes']['disabled'] = $month_arguments['ExtraAttributes']['disabled'] = $year_arguments['ExtraAttributes']['disabled'] = "disabled";
		}
		if($this->optional)
			$year_arguments["ValidateOptionalValue"]="";
		elseif(!$this->ask_age)
			$month_arguments["ValidateAsNotEmpty"]=$day_arguments["ValidateAsNotEmpty"]=1;
		if(IsSet($arguments["TABINDEX"]))
		{
			$tab_index = $arguments["TABINDEX"];
			$year_arguments["TABINDEX"] = $tab_index++;
			$month_arguments["TABINDEX"] = $tab_index++;
			$day_arguments["TABINDEX"] = $tab_index;
		}
		if(IsSet($arguments["STYLE"]))
			$year_arguments["STYLE"]=$month_arguments["STYLE"]=$day_arguments["STYLE"]=$arguments["STYLE"];
		if(IsSet($arguments["CLASS"]))
			$year_arguments["CLASS"]=$month_arguments["CLASS"]=$day_arguments["CLASS"]=$arguments["CLASS"];
		if(IsSet($arguments["YearClass"]))
			$year_arguments["CLASS"]=$arguments["YearClass"];
		if(IsSet($arguments["YearStyle"]))
			$year_arguments["STYLE"]=$arguments["YearStyle"];
		if(IsSet($arguments["MonthClass"]))
			$month_arguments["CLASS"]=$arguments["MonthClass"];
		if(IsSet($arguments["MonthStyle"]))
			$month_arguments["STYLE"]=$arguments["MonthStyle"];
		if(IsSet($arguments["DayClass"]))
			$day_arguments["CLASS"]=$arguments["DayClass"];
		if(IsSet($arguments["DayStyle"]))
			$day_arguments["STYLE"]=$arguments["DayStyle"];
		if($this->fixed_day)
			$day_arguments['Accessible'] = 0;
		if(strlen($error=$form->AddInput($year_arguments))
		|| strlen($error=$form->AddInput($month_arguments))
		|| strlen($error=$form->AddInput($day_arguments)))
			return($error);
		return("");
	}

	Function GetValue(&$form, &$year, &$month, &$day)
	{
		if($this->choose_control
		&& !$this->choose)
		{
			$value = (IsSet($this->choice_default_value) ? $this->choice_default_value : $this->value);
			if(strlen($value))
			{
				$year = substr($value, 0, 4);
				$month = substr($value, 5, 2);
				$day = substr($value, 8, 2);
			}
			else
				$year = $day = $month = '';
			return;
		}
		$year=$form->GetInputValue($this->year);
		$month=$form->GetInputValue($this->month);
		$day=($this->fixed_day ? ((strlen($year) || strlen($month)) ? $this->fixed_day : '') : $form->GetInputValue($this->day));
		if($this->ask_age)
		{
			$this_year = intval(strftime('%Y'));
			$this_month = intval(strftime('%m'));
			$this_day = intval(strftime('%d'));
			if(strlen($day)
			|| strlen($month)
			|| strlen($year))
			{
				if(!$this->fixed_day)
				{
					if(strlen($day) == 0)
						$day = '0';
					$this_day -= intval($day);
				}
				if(strlen($month) == 0)
					$month='0';
				$this_month -= intval($month);
				if(strlen($year) == 0)
					$year='0';
				$this_year -= intval($year);
				if(!$this->fixed_day)
				{
					while($this_day < 1)
					{
						$this_day += 30;
						--$this_month;
					}
					$day=strval($this_day);
				}
				if(strlen($day < 2))
					$day = '0'.$day;
				while($this_month < 1)
				{
				 $this_month += 12;
				 --$this_year;
				}
				$month = strval($this_month);
				if(strlen($month) < 2)
					$month = '0'.$month;
				$year = strval($this_year);
				if(strlen($year) < 2)
				 $year = '00' + $year;
				if(strlen($year) < 3)
				 $year = '0'.$year;
			}
		}
	}

	Function ValidateInput(&$form)
	{
		$this->GetValue($form, $year, $month, $day);
		if($this->optional
		&& strlen($year)==0
		&& strlen($month)==0
		&& strlen($day)==0)
			return("");
		if(strlen($error=$this->ValidateDateValue($year, $month, $day)))
			return($error);
		$date=sprintf("%04d-%02d-%02d", $year, $month, $day);
		if(strlen($this->validation_start_date)
		&& strcmp($date,$this->validation_start_date)<0)
			return($this->validation_start_date_error_message);
		if(strlen($this->validation_end_date)
		&& strcmp($date,$this->validation_end_date)>0)
			return($this->validation_end_date_error_message);
		return("");
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case "VALUE":
				$today = strftime('%Y-%m-%d');
				if($this->optional
				&& strlen($value)==0)
					$this->value = $year = $month = $day = "";
				else
				{
					if(!strcmp($value, 'now'))
						$value = $today;
					if(strlen($error=$this->ValidateDate($value, $year, $month, $day)))
						return($error);
					if(strlen($this->validation_start_date)
					&& strcmp($value, $this->validation_start_date)<0)
						return($this->validation_start_date_error_message);
					if(strlen($this->validation_end_date)
					&& strcmp($value, $this->validation_end_date)>0)
						return($this->validation_end_date_error_message);
					if($this->ask_age)
					{
						$this_year = intval(substr($today, 0, 4));
						$year = $this_year - intval(substr($value, 0, 4));
						$this_month = intval(substr($today, 5, 2));
						$month = $this_month - intval(substr($value, 5, 2));
						$this_day = intval(substr($today, 8, 2));
						$day = $this_day - intval(substr($value, 8, 2));
						while($day < 0)
						{
							$day += 30;
							--$month;
						}
						while($month < 0)
						{
							$month += 12;
							--$year;
						}
					}
					else
					{
						$this_year = $year;
						$this_month = $month;
						$this_day = $day;
					}
					$this->value = sprintf("%04d-%02d-%02d", $this_year, $this_month, $this_day);
				}
				if(strlen($error=$form->SetInputProperty($this->year, "VALUE", $year))
				|| strlen($error=$form->SetInputProperty($this->month, "VALUE", $month))
				|| (!$this->hide_day
				&& strlen($error=$form->SetInputProperty($this->day, "VALUE", $day))))
					return($error);
				break;
			default:
				return($this->DefaultSetInputProperty($form, $property, $value));
		}
		return("");
	}

	Function GetInputValue(&$form)
	{
		$this->GetValue($form, $year, $month, $day);
		if(strlen($year)==0
		|| strlen($month)==0
		|| strlen($day)==0)
			return("");
		return(sprintf("%04d-%02d-%02d", $year, $month, $day));
	}

	Function GetJavascriptDayValue(&$form, $form_object)
	{
		return($this->fixed_day ? $form->EncodeJavascriptString(sprintf('%02d', $this->fixed_day)) : $form->GetJavascriptInputValue($form_object,$this->day));
	}

	Function GetJavascriptValidations(&$form, $form_object, &$validations)
	{
		if(strlen($day=$this->GetJavascriptDayValue($form, $form_object))==0)
			return("it was not possible to retrieve the day input Javascript value");
		if(strlen($month=$form->GetJavascriptInputValue($form_object,$this->month))==0)
			return("it was not possible to retrieve the day input Javascript value");
		if(strlen($year=$form->GetJavascriptInputValue($form_object,$this->year))==0)
			return("it was not possible to retrieve the day input Javascript value");
		$validations = $commands = array();
		if($this->choose_control)
		{
			$value = (IsSet($this->choice_default_value) ? $this->choice_default_value : $this->value);
			$commands[] = 'var choose='.$form->GetJavascriptCheckedState($form_object, $this->choose_input);
			$year = '(choose ? '.$year.' : '.$form->EncodeJavascriptString(strlen($value) ? substr($value, 0, 4) : '').')';
			$month = '(choose ? '.$month.' : '.$form->EncodeJavascriptString(strlen($value) ? substr($value, 5, 2) : '').')';
			$day = '(choose ? '.$day.' : '.$form->EncodeJavascriptString(strlen($value) ? substr($value, 7, 2) : '').')';
		}
		$commands[] = "var year=".$year;
		$commands[] = "var month=".$month;
		$commands[] = "var day=".($this->fixed_day ? '((year.length || month.length) ? '.$day.' : \'\')' : $day);
		if($this->ask_age)
		{
			if(!$this->optional
			|| $this->choose_format)
			{
				$validations[]=array(
					"Commands"=>$commands,
					"Condition"=>($this->choose_control ? 'choose && ' : '').'!year.length && !month.length'.($this->fixed_day ? '' : ' && !day.length'),
					"ErrorMessage"=>$this->invalid_year_error_message,
					"Focus"=>$this->year
				);
				$commands = array();
			}
			$this_year = intval(strftime('%Y'));
			$this_month = intval(strftime('%m'));
			$this_day = intval(strftime('%d'));
			$commands[] = 'if('.($this->fixed_day ? '' : 'day.length || ').'month.length || year.length)';
			$commands[] = '{';
			if(!$this->fixed_day)
			{
				$commands[] = ' if(day.length==0)';
				$commands[] = '  day=\'0\'';
				$commands[] = ' var this_day='.$this_day.'-parseInt(day)';
			}
			$commands[] = ' if(month.length==0)';
			$commands[] = '  month=\'0\'';
			$commands[] = ' var this_month='.$this_month.'-parseInt(month)';
			$commands[] = ' if(year.length==0)';
			$commands[] = '  year=\'0\'';
			$commands[] = ' var this_year='.$this_year.'-parseInt(year)';
			if(!$this->fixed_day)
			{
				$commands[] = ' while(this_day<1)';
				$commands[] = ' {';
				$commands[] = '  this_day+=30';
				$commands[] = '  --this_month';
				$commands[] = ' }';
				$commands[] = ' day=this_day+\'\'';
				$commands[] = ' if(day.length<2)';
				$commands[] = '  day=\'0\'+day';
			}
			$commands[] = ' while(this_month<1)';
			$commands[] = ' {';
			$commands[] = '  this_month+=12';
			$commands[] = '  --this_year';
			$commands[] = ' }';
			$commands[] = ' month=this_month+\'\'';
			$commands[] = ' if(month.length<2)';
			$commands[] = '  month=\'0\'+month';
			$commands[] = ' year=this_year+\'\'';
			$commands[] = ' if(year.length<2)';
			$commands[] = '  year=\'00\'+year';
			$commands[] = ' if(year.length<3)';
			$commands[] = '  year=\'0\'+year';
			$commands[] = '}';
		}
		$validations[]=array(
			"Commands"=>$commands,
			"Condition"=>'!year.length && ('.($this->choose_control ? 'choose || ' : '').'month.length || day.length)',
			"ErrorMessage"=>$this->invalid_year_error_message,
			"Focus"=>$this->year
		);
		$validations[]=array(
			"Commands"=>array(),
			"Condition"=>'!month.length && ('.($this->choose_control ? 'choose || ' : '').'year.length || day.length)',
			"ErrorMessage"=>$this->invalid_month_error_message,
			"Focus"=>$this->month
		);
		if(!$this->fixed_day)
		{
			$validations[]=array(
				"Commands"=>array(),
				"Condition"=>'!day.length && ('.($this->choose_control ? 'choose || ' : '').'year.length || month.length)',
				"ErrorMessage"=>$this->invalid_day_error_message,
				"Focus"=>$this->day
			);
		}
		if($this->fixed_day)
			$commands = array();
		else
		{
			$commands = array(
				'var month_days',
				"if(month=='04'",
				"|| month=='06'",
				"|| month=='09'",
				"|| month=='11')",
				"\tmonth_days=30",
				"else",
				"{",
				"\tif(month=='02')",
				"\t{",
				"\t\tvar date_year=parseInt(year)",
				"\t\tif((date_year % 4)==0",
				"\t\t&& ((date_year % 100)!=0",
				"\t\t|| (date_year % 400)==0))",
				"\t\t\tmonth_days=29",
				"\t\telse",
				"\t\t\tmonth_days=28",
				"\t}",
				"\telse",
				"\t\tmonth_days=31",
				"}"
			);
		}
		if((!$this->fixed_day
		&& $this->optional)
		|| strlen($this->validation_start_date)
		|| strlen($this->validation_end_date))
			$commands[]="var date=".($this->optional ? "((year.length && month.length && day.length) ? " : "")."(year.length<3 ? '00' : '') + ((year.length % 2) ? '0' : '') + year + '-' + month + '-' + day".($this->optional ? " : '')" : "");
		if(!$this->fixed_day)
		{
			$validations[]=array(
				"Commands"=>$commands,
				"Condition"=>($this->optional ? "date.length && " : "")."month_days<parseInt(day)",
				"ErrorMessage"=>$this->invalid_day_error_message,
				"Focus"=>$this->day
			);
			$commands = array();
		}
		if(strlen($this->validation_start_date))
		{
			$validations[]=array(
				"Commands"=>$commands,
				"Condition"=>($this->optional ? "date.length && " : "")."date<".$form->EncodeJavascriptString($this->validation_start_date),
				"ErrorMessage"=>$this->validation_start_date_error_message
			);
			$commands = array();
		}
		if(strlen($this->validation_end_date))
		{
			$validations[]=array(
				"Commands"=>$commands,
				"Condition"=>($this->optional ? "date.length && " : "").$form->EncodeJavascriptString($this->validation_end_date)."<date",
				"ErrorMessage"=>$this->validation_end_date_error_message
			);
		}
		return("");
	}

	Function GetJavascriptInputValue(&$form, $form_object)
	{
		if($this->ask_age)
		{
			$this->OutputError("retrieve the Javascript input value for date inputs with AskAge option is not yet implemented", $this->input);
			return("");
		}
		if(strlen($day=$this->GetJavascriptDayValue($form, $form_object))==0
		|| strlen($month=$form->GetJavascriptInputValue($form_object,$this->month))==0
		|| strlen($year=$form->GetJavascriptInputValue($form_object,$this->year))==0)
			return("");
		$value = "((".$year.".length && ".$month.".length && ".$day.".length) ? (".$year.".length<3 ? '00' : '') + ((".$year.".length % 2) ? '0' : '') + ".$year." + '-' + ".$month." + '-' + ".$day." : '')";
		if($this->choose_control)
			$value = '('.$form->GetJavascriptCheckedState($form_object, $this->choose_input).' ? '.$value.' '.$form->EncodeJavascriptString(IsSet($this->choice_default_value) ? $this->choice_default_value : $this->value).')';
		return($value);
	}

	Function LoadInputValues(&$form, $submitted)
	{
		if($this->choose_control)
		{
			$choose = $form->GetCheckedState($this->choose_input);
			if(!$this->choose != !$choose)
			{
				$value = ($choose ? array() : array('disabled' => 'disabled'));
				$form->SetInputProperty($this->year, 'ExtraAttributes', $value);
				$form->SetInputProperty($this->month, 'ExtraAttributes', $value);
				$form->SetInputProperty($this->day, 'ExtraAttributes', $value);
				$this->choose = $choose;
			}
		}
		return('');
	}

};

}

?>