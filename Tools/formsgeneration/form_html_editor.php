<?php
/*
 *
 * @(#) $Id: form_html_editor.php,v 1.31 2010/05/07 09:32:54 mlemos Exp $
 *
 */

class form_html_editor_class extends form_custom_class
{
	var $server_validate = 0;
	var $javascript_path = '';
	var $external_css = array();
	var $textarea = array();
	var $debug = 1;
	var $template_variables = array();
	var $mode = 'visual';
	var $show_toolbars = 1;

	Function AddInput(&$form, $arguments)
	{
		if(IsSet($arguments['TemplateVariables']))
			$this->template_variables = $arguments['TemplateVariables'];
		if(IsSet($arguments['Debug']))
			$this->debug = intval($arguments['Debug']);
		if(IsSet($arguments['ShowToolbars']))
			$this->show_toolbars = intval($arguments['ShowToolbars']);
		$this->textarea = $arguments;
		$this->textarea['TYPE'] = 'textarea';
		if(IsSet($arguments['JavascriptPath']))
		{
			$this->javascript_path = $arguments['JavascriptPath'];
			if(($length = strlen($this->javascript_path))
			&& strcmp($this->javascript_path[$length - 1], '/'))
				$this->javascript_path .= '/';
			UnSet($this->textarea['JavascriptPath']);
		}
		if(IsSet($arguments['ExternalCSS']))
		{
			$this->external_css = $arguments['ExternalCSS'];
			UnSet($this->textarea['ExternalCSS']);
		}
		if(IsSet($arguments['Mode']))
		{
			switch($mode = $arguments['Mode'])
			{
				case 'visual':
				case 'html':
					$this->mode = $mode;
					break;
				default:
					return($mode.' is not support editing mode');
			}
		}
		UnSet($this->textarea['CustomClass']);
		$this->focus_input = $this->textarea['ID'] = $this->GenerateInputID($form, $this->input, 'textarea');
		if(!IsSet($this->textarea['NAME']))
			$this->textarea['NAME'] = $this->textarea['ID'];
		if(strlen($error = $form->AddInput($this->textarea)))
			return($error);
		$context = array();
		return($form->ConnectFormToInput($this->input, 'ONSUBMIT', 'Synchronize', $context));
	}

	Function AddInputPart(&$form)
	{
		if(strlen($error = $form->AddDataPart('<div id="'.$this->input.'"><noscript>'))
		|| strlen($error = $form->AddInputPart($this->textarea['ID']))
		|| strlen($error = $form->AddDataPart('</noscript></div>')))
			return($error);
		return('');
	}

	Function ClassPageHead(&$form)
	{
		return('<script type="text/javascript" src="'.HtmlSpecialChars($this->javascript_path).'html_editor.js"></script>'."\n");
	}

	Function PageLoad(&$form)
	{
		$tv = $this->template_variables;
		$ttv = count($tv);
		for($t = '', Reset($tv), $v = 0; $v < $ttv; Next($tv), ++$v)
		{
			$k = Key($tv);
			if($v > 0)
				$t .= ',';
			$t .= ' '.$form->EncodeJavascriptString($k).': { ';
			if(IsSet($tv[$k]['Inline']))
			{
				$t .= 'inline: '.($tv[$k]['Inline'] ? 'true' : 'false');
				if(IsSet($tv[$k]['Preview']))
					$t .= ', preview: '.$form->EncodeJavascriptString($tv[$k]['Preview']);
			}
			else
				$t .= 'value: '.$form->EncodeJavascriptString(IsSet($tv[$k]['Value']) ? $tv[$k]['Value'] : $k);
			if(IsSet($tv[$k]['Title']))
			  $t .=', title: '.$form->EncodeJavascriptString($tv[$k]['Title']);
			if(IsSet($tv[$k]['Alternatives']))
			{
				$t .= ', alternatives: {';
				$va = $tv[$k]['Alternatives'];
				$tva = count($va);
				for(Reset($va), $a = 0; $a < $tva; Next($va), ++$a)
				{
					$ka = Key($va);
					if($a > 0)
						$t .= ', ';
					$t .= ' '.$form->EncodeJavascriptString($ka).': { ';
					if(IsSet($tv[$k]['Inline']))
					{
						if(IsSet($va[$ka]['Preview']))
							$t .= 'preview: '.$form->EncodeJavascriptString($va[$ka]['Preview']);
						if(IsSet($va[$ka]['Title']))
						{
							if(IsSet($va[$ka]['Preview']))
								$t .= ', ';
							$t .= 'title: '.$form->EncodeJavascriptString($va[$ka]['Title']);
						}
					}
					else
						$t .= 'value: '.$form->EncodeJavascriptString(IsSet($va[$ka]['Value']) ? $va[$ka]['Value'] : $ka);
					$t .= ' }';
				}
				$t .= ' }';
			}
			$t .= ' }';
		}
		$css = $this->external_css;
		$tc = count($css);
		for($e = '', $c = 0; $c < $tc; ++$c)
		{
			if($c > 0)
				$e .= ',';
			$e .= ' '.$form->EncodeJavascriptString($css[$c]);
		}
		$editor = $form->EncodeJavascriptString($this->input);
		return('if(document.getElementById('.$editor.')) { var e = new ML.HTMLEditor.Editor();'."\n".'e.debug = '.($this->debug ? 'true' : 'false').'; e.showToolbars = '.($this->show_toolbars ? 'true' : 'false').'; e.mode = '.$form->EncodeJavascriptString($this->mode).';'.(strlen($t) ? ' e.templateVariables = {'.$t.'};' : '').(strlen($e) ? ' e.externalCSS = ['.$e.'];' : '').' e.insertEditor('.$editor.', { id: '.$form->EncodeJavascriptString($this->textarea['ID']).', name: '.$form->EncodeJavascriptString($this->textarea['NAME']).(IsSet($this->textarea['VALUE']) ? ', value: '.$form->EncodeJavascriptString($this->textarea['VALUE']) : '').(IsSet($this->textarea['ROWS']) ? ', rows: '.$form->EncodeJavascriptString($this->textarea['ROWS']) : '').(IsSet($this->textarea['COLS']) ? ', cols: '.$form->EncodeJavascriptString($this->textarea['COLS']) : '').(IsSet($this->textarea['STYLE']) ? ', style: '.$form->EncodeJavascriptString($this->textarea['STYLE']).(IsSet($this->textarea['CLASS']) ? ', className: '.$form->EncodeJavascriptString($this->textarea['CLASS']) : '') : '').' }); }');
	}

	Function GetInputValue(&$form)
	{
		return($this->textarea['VALUE'] = $form->GetInputValue($this->textarea['ID']));
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case 'VALUE':
				if(strlen($error = $form->SetInputValue($this->textarea['ID'], $value)) == 0)
					$this->textarea['VALUE'] = $value;
				return($error);
			case 'Mode':
				switch($value)
				{
					case 'visual':
					case 'html':
						$this->mode = $value;
						return('');
					default:
						return($value.' is not support editing mode');
				}
			case 'ShowToolbars':
				$this->show_toolbars = intval($value);
				return('');
			case 'TemplateVariables':
				$this->template_variables = $value;
				return('');
			default:
				return($this->DefaultSetInputProperty($form, $property, $value));
		}
	}

	Function GetJavascriptSetInputProperty(&$form, $form_object, $property, $value)
	{
		switch($property)
		{
			case 'VALUE':
				return('var e = ('.$form_object.'.ownerDocument.defaultView ? '.$form_object.'.ownerDocument.defaultView : '.$form_object.'.ownerDocument.parentWindow).ML.HTMLEditor.HTMLEditors['.$form->EncodeJavascriptString($this->textarea['ID']).']; e.setValue('.$value.');');
			default:
				return('');
		}
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'Synchronize':
				$javascript = 'var e = ML.HTMLEditor.HTMLEditors['.$form->EncodeJavascriptString($this->textarea['ID']).']; e.synchronize();';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}
};

?>