<?php
/*
 * form_upload_progress.php
 *
 * @(#) $Id: form_upload_progress.php,v 1.4 2011/03/14 08:38:29 mlemos Exp $
 *
 */

class form_upload_progress_class extends form_custom_class
{
	var $server_validate=0;

	var $upload_identifier='';
	var $monitor='';
	var $monitor_action='';
	var $feedback_element='';
	var $feedback_format='{PROGRESS}%';
	var $wait_time=10;
	var $started = 0;
	var $uploaded=0;
	var $total=0;
	var $remaining=0;
	var $average_speed=0;
	var $current_speed=0;
	var $last_feedback='';
	var $start_time=0;

	Function AddInput(&$form, $arguments)
	{
		if(!function_exists('uploadprogress_get_info'))
			return('the upload progress extension is not available in this PHP installation');
		if(IsSet($arguments['FeedbackElement']))
		{
			if(strlen($arguments['FeedbackElement'])==0)
				return('it was not specified a valid feedback element identifier');
			if(IsSet($arguments['FeedbackFormat']))
			{
				if(strlen($arguments['FeedbackFormat'])==0)
					return('it was not specified a valid feedback format');
				$this->feedback_format=$arguments['FeedbackFormat'];
			}
			$this->feedback_element=$arguments['FeedbackElement'];
		}
		$this->upload_identifier=uniqid($this->input);
		$this->monitor=$this->GenerateInputID($form, $this->input, 'monitor');
		if(strlen($error=$form->ConnectFormToInput($this->input, 'ONSUBMITTING', 'Monitor', array()))
		|| strlen($error=$form->AddInput(array(
			'TYPE'=>'hidden',
			'ID'=>'UPLOAD_IDENTIFIER',
			'NAME'=>'UPLOAD_IDENTIFIER',
			'VALUE'=>$this->upload_identifier
		)))
		|| strlen($error=	$form->AddInput(array(
			'TYPE'=>'custom',
			'NAME'=>$this->monitor,
			'ID'=>$this->monitor,
			'CustomClass'=>'form_ajax_submit_class',
			'TargetInput'=>$this->input,
			'ONTIMEOUT'=>''
		))))
			return($error);
		return('');
	}

	Function AddInputPart(&$form)
	{
		$context=array(
			'Parameters'=>array(
				'UPLOAD_IDENTIFIER'=>$this->upload_identifier
			),
			'RandomParameter'=>'r'
		);
		if(strlen($error=$form->GetJavascriptConnectionAction('f', $this->input, $this->monitor, 'ONMONITOR', 'Load', $context, $monitor)))
			return($error);
		$eol=$form->end_of_line;
		$javascript='<script type="text/javascript" defer="defer">'.$eol."<!--\n";
		$javascript.='function '.$this->input.'_monitor(f)'.$eol.'{'.$eol;
		$javascript.=$monitor;
		$javascript.='}'.$eol.'// -->'.$eol.'</script>';
		if(strlen($error=$form->AddDataPart($javascript))
		|| strlen($error=$form->AddInputPart('UPLOAD_IDENTIFIER'))
		|| strlen($error=$form->AddInputPart($this->monitor)))
			return($error);
		return('');
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'Monitor':
				$javascript='window.setTimeout('.$form->EncodeJavascriptString($this->input.'_monitor();').', 10);';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}

	Function FormatNumber($number)
	{
		if($number<1024)
			return($number);
		$number=intval($number/1024);
		if($number<1024)
			return($number.'K');
		$number=intval($number/1024);
		if($number<1024)
			return($number.'M');
		$number=intval($number/1024);
		return($number.'G');
	}

	Function FormatTime($time)
	{
		if($time<60)
			return(sprintf('00:%02d', $time));
		if($time<3600)
			return(sprintf('00:%02d:%02d', intval($time/60), $time % 60));
		return(sprintf('%02d:%02d:%02d', intval($time/3600), intval($time/60) % 60, $time % 60));
	}

	Function PostMessage(&$form, $message, &$processed)
	{
		switch($message['Event'])
		{
			case 'load':
				$more=0;
				$id=$_REQUEST['UPLOAD_IDENTIFIER'];
				if($this->started)
				{
					$progress=uploadprogress_get_info($id);
					if(IsSet($progress))
						$more=1;
					else
						$this->uploaded=$this->total;
				}
				else
				{
					if($this->start_time==0)
					{
						$this->uploaded=$this->total=$this->remaining=$this->average_speed=$this->current_speed=0;
						$this->last_feedback='';
						$this->start_time=time();
					}
					$progress=uploadprogress_get_info($id);
					if(IsSet($progress))
					{
						$this->started=1;
						$more=1;
					}
					elseif(time()-$this->start_time<$this->wait_time)
						$more=1;
				}
				$message['Actions']=array();
				if(strlen($this->feedback_element))
				{
					if(IsSet($progress))
					{
						$this->uploaded=$progress['bytes_uploaded'];
						$this->total=$progress['bytes_total'];
						$this->remaining=$progress['est_sec'];
						$this->average_speed=$progress['speed_average'];
						$this->current_speed=$progress['speed_last'];
					}
					$feedback=($this->total ? str_replace('{PROGRESS}', intval($this->uploaded*100.0/$this->total), str_replace('{ACCURATE_PROGRESS}', $this->uploaded*100.0/$this->total, str_replace('{UPLOADED}', $this->FormatNumber($this->uploaded), str_replace('{TOTAL}', $this->FormatNumber($this->total), str_replace('{REMAINING}', $this->FormatTime($this->remaining), str_replace('{AVERAGE_SPEED}', $this->FormatNumber($this->average_speed), str_replace('{CURRENT_SPEED}', $this->FormatNumber($this->current_speed), $this->feedback_format))))))) : '');
					if(strcmp($this->last_feedback, $feedback))
					{
						$message['Actions'][]=array(
							'Action'=>'ReplaceContent',
							'Container'=>$this->feedback_element,
							'Content'=>$feedback
						);
						$this->last_feedback=$feedback;
					}
				}
				if($more)
				{
					$message['Actions'][]=array(
						'Action'=>'Wait',
						'Time'=>1
					);
				}
				$message['More']=$more;
				break;
		}
		return($form->ReplyMessage($message, $processed));
	}

	Function LoadInputValues(&$form, $submitted)
	{
		return($form->SetInputValue('UPLOAD_IDENTIFIER', $this->upload_identifier));
	}

};
?>