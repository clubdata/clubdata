<?php
/*
 *
 * @(#) $Id: form_recaptcha.php,v 1.1 2012/09/16 11:19:33 mlemos Exp $
 *
 */

class form_recaptcha_class extends form_custom_class
{
	var $format = '<div>{image}</div>
<div>{instructions_visual}{instructions_audio} {input}</div>
<div>{refresh_btn} {visual_challenge}{audio_challenge} {help_btn}</div>';
	var $validation_error_message = 'It was not entered the correct text.';
	var $key = '';
	var $private_key = '';
	var $valid = 0;
	var $widget = '';
	var $challenge = '';
	var $response = '';
	var $text = array(
		'instructions_visual'=>'Enter the words above',
		'instructions_audio'=>'Enter the numbers you hear',
		'visual_challenge'=>'Enter text in an image instead',
		'audio_challenge'=>'Enter numbers you hear instead',
		'refresh_btn'=>'Try another',
		'help_btn'=>'Help',
		'play_again'=>'Play the sound again',
		'cant_hear_this'=>'Download the sound as a MP3 file',
		'image_alt_text'=>'Image with text to enter'
	);
	var $tab_index = '';

	Function CheckRequirements()
	{
		if(!class_exists('http_class'))
			return('the HTTP class to access the Recaptcha server is not available');
		return('');
	}

	Function AddInput(&$form, $arguments)
	{
		if(strlen($error = $this->CheckRequirements()))
			return($error);
		if(!IsSet($arguments['Key'])
		|| strlen($arguments['Key']) != 40)
			return('it was not specified a valid Recaptcha public key');
		$this->key = $arguments['Key'];
		if(!IsSet($arguments['PrivateKey'])
		|| strlen($arguments['PrivateKey']) != 40)
			return('it was not specified a valid Recaptcha private key');
		$this->private_key = $arguments['PrivateKey'];
		if(IsSet($arguments['ValidationErrorMessage']))
		{
			if(strlen($arguments['ValidationErrorMessage']))
				$this->validation_error_message = $arguments['ValidationErrorMessage'];
			else
				return('it was not specified a valid validation error message');
		}
		if(IsSet($arguments['Text']))
		{
			if(GetType($text = $arguments['Text']) != 'array')
				return('it was not specified a valid array with Recaptcha text definitions');
			Reset($text);
			$tt = count($text);
			for($t = 0; $t < $tt; ++$t)
			{
				$k = Key($text);
				if(!IsSet($this->text[$k]))
					return($k.' is not a supported reCAPTCHA text definition');
				$this->text[$k] = $text[$k];
				Next($text);
			}
		}
		Reset($this->text);
		$this->widget = $this->GenerateInputID($form, $this->input, 'widget');
		$this->challenge = 'recaptcha_challenge_field';
		$this->focus_input = $this->response = 'recaptcha_response_field';
		$input_arguments = array(
			'NAME'=>$this->challenge,
			'TYPE'=>'textarea',
			'COLS'=>40,
			'ROWS'=>3
		);
		if(strlen($error = $form->AddInput($input_arguments)))
			return($error);
		$input_arguments = array(
			'NAME'=>$this->response,
			'ID'=>$this->response,
			'TYPE'=>'text',
			'ValidateAsNotEmpty'=>1,
			'ValidationErrorMessage'=>$this->validation_error_message,
			'ONKEYPRESS'=>'return(event.keyCode!=13)'
		);
		if(IsSet($arguments['DependentValidation']))
			$input_arguments['DependentValidation'] = $arguments['DependentValidation'];
		if(IsSet($arguments['InputClass']))
			$input_arguments['CLASS'] = $arguments['InputClass'];
		if(IsSet($arguments['InputStyle']))
			$input_arguments['STYLE'] = $arguments['InputStyle'];
		if(IsSet($arguments['InputTabIndex']))
			$this->tab_index = $input_arguments['TABINDEX'] = $arguments['InputTabIndex'];
		if(IsSet($arguments['InputExtraAttributes']))
			$input_arguments['ExtraAttributes'] = $arguments['InputExtraAttributes'];
		if(strlen($error = $form->AddInput($input_arguments)))
			return($error);
		if(IsSet($arguments['Format']))
			$this->format = $arguments['Format'];
		$this->valid_marks = array(
			'input'=>array(
				'input'=>$this->response,
			),
			'data'=>array(
				'image'=>'<div id="recaptcha_image"></div>',
				'instructions_visual'=>'<span class="recaptcha_only_if_image">'.HtmlSpecialChars($this->text['instructions_visual']).'</span>',
				'instructions_audio'=>'<span class="recaptcha_only_if_audio">'.HtmlSpecialChars($this->text['instructions_audio']).'</span>',
				'refresh_btn'=>'<span><a href="javascript:Recaptcha.reload()">'.HtmlSpecialChars($this->text['refresh_btn']).'</a></span>',
				'audio_challenge'=>'<span class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">'.HtmlSpecialChars($this->text['audio_challenge']).'</a></span>',
				'visual_challenge'=>'<span class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">'.HtmlSpecialChars($this->text['visual_challenge']).'</a></span>',
				'help_btn'=>'<span><a href="javascript:Recaptcha.showhelp()">'.HtmlSpecialChars($this->text['help_btn']).'</a></span>'
			)
		);
		return '';
	}

	Function LoadInputValues(&$form, $submitted)
	{
		if(!$submitted)
			return('');
		$http = new http_class;
		$http->timeout = 60;
		$http->data_timeout = 60;
		$http->debug = 0;
		$http->log_debug = 1;
		$http->html_debug = 0;
		$error = $http->GetRequestArguments('http://www.google.com/recaptcha/api/verify', $arguments);
		$arguments['RequestMethod'] = 'POST';
		$arguments['PostValues'] = array(
			'privatekey'=>$this->private_key,
			'remoteip'=>GetEnv('REMOTE_ADDR'),
			'challenge'=>$form->GetInputValue($this->challenge),
			'response'=>$form->GetInputValue($this->response),
		);
		if(strlen($error = $http->Open($arguments)))
			return('recaptcha-not-reachable: '.$error);
		if(strlen($error = $http->SendRequest($arguments)) == 0)
			$error = $http->ReadWholeReplyBody($response);
		$http->Close();
		if(strlen($error))
			return('recaptcha-not-reachable: '.$error);
		$this->valid = !strcmp(strtok($response,"\n"), 'true');
		if(!$this->valid)
		{
			switch($error = strtok("\n"))
			{
				case 'incorrect-captcha-sol':
					break;
				case 'invalid-site-private-key':
					return($error.': the specified reCAPTCHA private key is incorrect');
				case 'invalid-request-cookie':
					return($error.': the reCAPTCHA challenge parameter is corrupted');
				case 'recaptcha-not-reachable':
					return($error.': it was not possible to access the reCAPTCHA server');
				default:
					return($error.': not yet supported reCAPTCHA error');
			}
		}
		return('');
	}

	Function ValidateInput(&$form)
	{
		return($this->valid ? '' : $this->validation_error_message);
	}

	Function AddInputPart(&$form)
	{
		if(strlen($error = $form->AddDataPart('<div id="'.HtmlSpecialChars($this->widget).'" style="display:none">'))
		|| ($error = parent::AddInputPart($form))
		|| ($error = $form->AddDataPart('</div><script type="text/javascript"><!--
var RecaptchaOptions = {
 theme : "custom",
 custom_theme_widget: '.$form->EncodeJavaScriptString($this->widget).',
 custom_translations: {
  play_again: '.$form->EncodeJavaScriptString($this->text['play_again']).',
  cant_hear_this: '.$form->EncodeJavaScriptString($this->text['cant_hear_this']).',
  image_alt_text: '.$form->EncodeJavaScriptString($this->text['image_alt_text']).'
 }'.(strlen($this->tab_index) ? ',
 tabindex: '.$form->EncodeJavaScriptString($this->tab_index) : '').'
};
// --></script><script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.HtmlSpecialChars($this->key).'"></script><noscript><iframe src="http://www.google.com/recaptcha/api/noscript?k='.HtmlSpecialChars($this->key).'" height="300" width="500" frameborder="0"></iframe>'))
		|| strlen($error = $form->AddInputPart($this->challenge))
		|| strlen($error = $form->AddDataPart('<input type="hidden" name="'.HtmlSpecialChars($this->response).'" value="manual_challenge"></noscript>')))
			return($error);
		return('');
	}
};

?>