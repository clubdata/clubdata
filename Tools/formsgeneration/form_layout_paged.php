<?php
/*
 *
 * @(#) $Id: form_layout_paged.php,v 1.28 2014/09/28 04:44:56 mlemos Exp $
 *
 */

class form_layout_paged_class extends form_custom_class
{
	var $pages = array();
	var $current_page = '';
	var $styles = array();
	var $side = 'top';
	var $border_width = 1;
	var $page_border_width = 2;
	var $border_radius=8;
	var $border_color = '';
	var $foreground_color = '';
	var $background_color = '';
	var $lighter_border_color = '#eeeeee';
	var $darker_border_color = '#777777';
	var $color_offset = 50;
	var $tab_padding = 2;
	var $row_gap = 4;
	var $tab = '';
	var $button = '';
	var $page = '';
	var $switch = '';
	var $class = '';
	var $page_class = '';
	var $tab_class = '';
	var $gap_class = '';
	var $page_button_class = '';
	var $tab_button_class = '';
	var $fade_pages_time = 0;
	var $contained = array();
	var $caption = '';
	var $caption_header = '<div style="margin-top: 1ex; text-align: center; font-weight: bold">{caption}</div>';
	var $caption_footer = '';
	var $auto_adjust_size = 0;
	var $adjust = '';
	var $show_tabs = 1;

	Function ColorChangeIntensity($color,$intensity_offset)
	{
		if(preg_match('/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/', $color, $components) != 7)
			return($color);
		if(($red = intval(HexDec($components[1]) * (100 + $intensity_offset) / 100)) > 255)
			$red = 255;
		if(($green = intval(HexDec($components[2]) * (100 + $intensity_offset) / 100)) > 255)
			$green = 255;
		if(($blue = intval(HexDec($components[3]) * (100 + $intensity_offset) / 100)) > 255)
			$blue = 255;
		return(sprintf('#%02X%02X%02X', $red, $green, $blue));
	}

	Function SetStyles()
	{
		$border=' border-width: '.strval($this->border_width).'px ;';
		$page_border=' border-width: '.strval($this->page_border_width).'px ;';
		$border_color=(strlen($this->border_color) ? $this->border_color : (strlen($this->foreground_color) ? $this->foreground_color : (strlen($this->background_color) ? $this->background_color : '')));
		$lighter=(strlen($this->lighter_border_color) ? $this->lighter_border_color : (strlen($border_color) ? $this->ColorChangeIntensity($border_color, $this->color_offset) : ''));
		$darker=(strlen($this->darker_border_color) ? $this->darker_border_color : (strlen($border_color) ? $this->ColorChangeIntensity($border_color, -$this->color_offset) : ''));
		$nowrap=' white-space: nowrap ;';
		switch($this->side)
		{
			case 'bottom':
				$page_style=' border-bottom-style: solid;  border-bottom-color: '.$darker.'; border-top-style: none; border-left-style: solid; border-left-color: '.$lighter.'; border-right-style: solid; border-right-color: '.$darker.'; border-bottom-left-radius: {BORDERRADIUS} ; border-bottom-right-radius: {BORDERRADIUS} ; -moz-border-radius-bottomright: {BORDERRADIUS} ; -moz-border-radius-bottomleft: {BORDERRADIUS} ; -webkit-border-bottom-right-radius: {BORDERRADIUS} ; -webkit-border-bottom-left-radius: {BORDERRADIUS}';
				$tab_style=' border-style: solid;  border-bottom-color: '.$darker.'; border-top-color: '.$darker.'; border-left-color: '.$lighter.'; border-right-color: '.$darker.'; border-bottom-left-radius: {BORDERRADIUS} ; border-bottom-right-radius: {BORDERRADIUS} ; -moz-border-radius-bottomright: {BORDERRADIUS} ; -moz-border-radius-bottomleft: {BORDERRADIUS} ; -webkit-border-bottom-right-radius: {BORDERRADIUS} ; -webkit-border-bottom-left-radius: {BORDERRADIUS}';
				$gap_style=' padding: 0px ; border-bottom-style: none; border-top-style: solid; border-top-color: '.$darker.'; border-left-style: none; border-right-style: none';
				break;
			case 'top':
			default:
				$page_style=' border-top-style: solid; border-top-color: '.$lighter.'; border-bottom-style: none; border-left-style: solid; border-left-color: '.$lighter.'; border-right-style: solid; border-right-color: '.$darker.'; border-top-left-radius: {BORDERRADIUS} ; border-top-right-radius: {BORDERRADIUS} ; -moz-border-radius-topright: {BORDERRADIUS} ; -moz-border-radius-topleft: {BORDERRADIUS} ; -webkit-border-top-right-radius: {BORDERRADIUS} ; -webkit-border-top-left-radius: {BORDERRADIUS}';
				$tab_style=' border-style: solid; border-top-color: '.$lighter.'; border-bottom-color: '.$lighter.'; border-left-color: '.$lighter.'; border-right-color: '.$darker.'; border-top-left-radius: {BORDERRADIUS} ; border-top-right-radius: {BORDERRADIUS} ; -moz-border-radius-topright: {BORDERRADIUS} ; -moz-border-radius-topleft: {BORDERRADIUS} ; -webkit-border-top-right-radius: {BORDERRADIUS} ; -webkit-border-top-left-radius: {BORDERRADIUS}';
				$gap_style=' padding: 0px ; border-top-style: none; border-bottom-style: solid; border-bottom-color: '.$lighter.'; border-left-style: none; border-right-style: none';
				break;
		}
		$this->styles=array(
			'page'=>$page_border.$nowrap.str_replace('{BORDERRADIUS}',$this->border_radius.'px',$page_style),
			'tab'=>$border.$nowrap.str_replace('{BORDERRADIUS}',$this->border_radius.'px',$tab_style),
			'gap'=>$border.$nowrap.$gap_style,
			'page_button'=>'border-width: 0px; font-weight: bold; background-color: inherit',
			'tab_button'=>'border-width: 0px; background-color: inherit'
		);
	}

	Function AddInput(&$form, $arguments)
	{
		if(!IsSet($arguments['Pages'])
		|| GetType($arguments['Pages']) != 'array'
		|| count(($arguments['Pages']))==0)
			return('it was not specified a valid list of pages to layout');
		if(IsSet($arguments['AutoAdjustSize'])
		&& $arguments['AutoAdjustSize'])
			$this->auto_adjust_size = 1;
		if(IsSet($arguments['ShowTabs'])
		&& !$arguments['ShowTabs'])
			$this->show_tabs = 0;
		$this->pages = $arguments['Pages'];
		if(IsSet($arguments['CurrentPage']))
		{
			$this->current_page = $arguments['CurrentPage'];
			if(!IsSet($this->pages[$this->current_page]))
				return($this->current_page.' is not a valid current page');
		}
		else
		{
			Reset($this->pages);
			$this->current_page = Key($this->pages);
		}
		$this->tab = $this->GenerateInputID($form, $this->input, 'tab');
		$this->button = $this->GenerateInputID($form, $this->input, 'button');
		$this->page = $this->GenerateInputID($form, $this->input, 'page');
		$this->switch = $this->GenerateInputID($form, $this->input, 'switch');
		if($this->auto_adjust_size)
			$this->adjust = $this->GenerateInputID($form, $this->input, 'adjust');
		$this->class = $this->GenerateInputID($form, $this->input, 'class');
		if(strlen($error = $form->AddInput(array(
			'TYPE'=>'hidden',
			'ID'=>$this->page,
			'NAME'=>$this->page,
			'VALUE'=>$this->current_page,
			'Accessible'=>1
		))))
			return($error);
		if(IsSet($arguments['PageClass']))
			$this->page_class = $arguments['PageClass'];
		if(IsSet($arguments['TabClass']))
			$this->tab_class = $arguments['TabClass'];
		if(IsSet($arguments['GapClass']))
			$this->gap_class = $arguments['GapClass'];
		if(IsSet($arguments['PageButtonClass']))
			$this->page_button_class = $arguments['PageButtonClass'];
		if(IsSet($arguments['TabButtonClass']))
			$this->tab_button_class = $arguments['TabButtonClass'];
		$this->SetStyles();
		$t = count($this->pages);
		for(Reset($this->pages), $p = 0; $p < $t; Next($this->pages), ++$p)
		{
			$page = Key($this->pages);
			if(strlen($page) == 0)
				return('it was specified a page with an empty name');
			if(IsSet($this->pages[$page]['Caption'])
			&& strlen($this->pages[$page]['Caption']) == 0)
				return('it was not specified a valid caption for page '.$page);
			if(IsSet($this->pages[$page]['Break']))
			{
				switch($this->pages[$page]['Break'])
				{
					case 'before':
					case 'after':
						break;
					default:
						return('it was not specified a valid break mode for page '.$page);
				}
			}
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'submit',
				'ID'=>$this->button.$page,
				'NAME'=>$this->button.$page,
				'VALUE'=>(IsSet($this->pages[$page]['Name']) ? $this->pages[$page]['Name'] : $page),
				'SubForm'=>(IsSet($this->pages[$page]['SubForm']) ? $this->pages[$page]['SubForm'] : $this->button.'_sub_form'),
				'IgnoreAnonymousSubmitCheck'=>1,
				'DisableResubmitCheck'=>1,
				'ONMOUSEUP'=>'this.clicked = true',
				'ONKEYDOWN'=>'this.clicked = (event.keyCode == 13)',
				'ONCLICK'=>'if(!this.clicked) return false; this.clicked = false; '.$this->switch.'(this.form, '.$form->EncodeJavascriptString($page).'); return false;',
				'Accessible'=>1
			))))
				return($error);
		}
		if(IsSet($arguments['FadePagesTime']))
		{
			$time_type = GetType($this->fade_pages_time = $arguments['FadePagesTime']);
			if((strcmp($time_type,'double')
			&& strcmp($time_type,'integer'))
			|| $this->fade_pages_time < 0)
				return('it was not specified a valid fade pages time');
		}
		if($this->fade_pages_time > 0
		&& strlen($error = $form->AddInput(array(
				'TYPE'=>'custom',
				'ID'=>$this->page.'animation',
				'CustomClass'=>'form_animation_class',
				'JavascriptPath'=>(IsSet($arguments['JavascriptPath']) ? $arguments['JavascriptPath'] : '')
			))))
			return($error);
		return($form->ConnectFormToInput($this->input, 'ONERROR', 'SwitchPage', array('InputsPage'=>'Invalid')));
	}

	Function AddInputPart(&$form)
	{
		$page_class = (strlen($this->page_class) ? $this->page_class : $this->class.'page');
		$tab_class = (strlen($this->tab_class) ? $this->tab_class : $this->class.'tab');
		$gap_class = (strlen($this->gap_class) ? $this->gap_class : $this->class.'gap');
		$page_button_class = (strlen($this->page_button_class) ? $this->page_button_class : $this->class.'page_button');
		$tab_button_class = (strlen($this->tab_button_class) ? $this->tab_button_class : $this->class.'tab_button');
		$row_start = '<table width="100%" cellpadding="'.$this->tab_padding.'" cellspacing="0" style="margin-bottom: '.$this->row_gap.'px"><tr>';
		$row_end = '<td class="'.HtmlSpecialChars($gap_class).'" width="99%">&nbsp;</td></tr></table>';
		if(strlen($error = $form->AddInputPart($this->page)))
			return($error);
		$t = count($this->pages);
		if($this->show_tabs)
		{
			if(strlen($error = $form->AddDataPart($row_start)))
				return($error);
			for(Reset($this->pages), $p = 0; $p < $t; Next($this->pages), ++$p)
			{
				$page = Key($this->pages);
				$button  = $this->button.$page;
				$is_page = !strcmp($page, $this->current_page);
				$break = (IsSet($this->pages[$page]['Break']) ? $this->pages[$page]['Break'] : '');
				if(strlen($error = $form->SetInputProperty($button, 'CLASS', $is_page ? $page_button_class : $tab_button_class))
				|| (!strcmp($break, 'before')
				&& strlen($error = $form->AddDataPart($row_end.$row_start)))
				|| strlen($error = $form->AddDataPart('<td class="'.HtmlSpecialChars($gap_class).'">&nbsp;</td><td id="'.HtmlSpecialChars($this->tab.$page).'" class="'.HtmlSpecialChars($is_page ? $page_class : $tab_class).'">'))
				|| strlen($error = $form->AddInputPart($button))
				|| strlen($error = $form->AddDataPart('</td>'))
				|| (!strcmp($break, 'after')
				&& strlen($error = $form->AddDataPart($row_end.$row_start))))
					return($error);
			}
			if(strlen($error = $form->AddDataPart($row_end)))
				return($error);
		}
		if($this->auto_adjust_size
		&& strlen($error = $form->AddDataPart('<div id="'.HtmlSpecialChars($this->page).'_parent">')))
			return($error);
		for(Reset($this->pages), $p = 0; $p < $t; Next($this->pages), ++$p)
		{
			$page = Key($this->pages);
			$is_page = !strcmp($page, $this->current_page);
			$header = '<div id="'.HtmlSpecialChars($this->page.$page).'" style="display: '.($is_page ? 'block' : 'none').'">';
			$footer = '</div>';
			if(IsSet($this->pages[$page]['Caption'])
			&& strlen($caption = $this->pages[$page]['Caption']))
			{
				$header .= str_replace('{caption}', $caption, $this->caption_header);
				$footer = str_replace('{caption}', $caption, $this->caption_footer).$footer;
			}
			if(strlen($error = $form->AddDataPart($header))
			|| strlen($error = $this->AddPagePart($form, $page))
			|| strlen($error = $form->AddDataPart($footer)))
				return($error);
		}
		if($this->auto_adjust_size
		&& strlen($error = $form->AddDataPart('</div>')))
			return($error);
		return('');
	}

	Function LoadInputValues(&$form, $submitted)
	{
		$t = count($this->pages);
		for(Reset($this->pages), $p = 0; $p < $t; Next($this->pages), ++$p)
		{
			$page = Key($this->pages);
			if($form->WasSubmitted($this->button.$page))
			{
				$form->SetInputValue($this->page, $this->current_page = $page);
				return;
			}
		}
		$page = $form->GetInputValue($this->page);
		if(IsSet($this->pages[$page]))
			$this->current_page = $page;
		else
			$form->SetInputValue($this->page, $this->current_page);
		return('');
	}

	Function PageHead(&$form)
	{
		$eol = $form->end_of_line;
		$page_class = (strlen($this->page_class) ? $this->page_class : $this->class.'page');
		$tab_class = (strlen($this->tab_class) ? $this->tab_class : $this->class.'tab');
		$page_button_class = (strlen($this->page_button_class) ? $this->page_button_class : $this->class.'page_button');
		$tab_button_class = (strlen($this->tab_button_class) ? $this->tab_button_class : $this->class.'tab_button');
		Reset($this->pages);
		$context=array(
			'Name'=>'Fade tab',
			'Effects'=>array(
				array(
					'Type'=>'CancelAnimation',
					'Animation'=>'Fade tab'
				),
				array(
					'Type'=>'FadeIn',
					'DynamicElement'=>'\''.$this->page.'\' + page',
					'Duration'=>$this->fade_pages_time,
					'Visibility'=>'display'
				),
			)
		);
		if($this->fade_pages_time>0
		&& strlen($fade_error = $form->GetJavascriptConnectionAction('form', $this->input, $this->page.'animation', 'ONCHANGE', 'AddAnimation', $context, $fade_javascript)))
			$form->OutputDebug('could not setup fade animation for paged layout input '.$this->input.': '.$fade_error);
		if($this->auto_adjust_size)
		{
			$tp = count($this->pages);
			for($pages = '', Reset($this->pages), $p = 0; $p < $tp; ++$p, Next($this->pages))
			{
				if($p > 0)
					$pages .= ', ';
				$pages .= '\''.Key($this->pages).'\'';
			}
		}
		$head = '<script type="text/javascript"><!--'.$eol.
			'function '.$this->switch.'(form, page)'.$eol.
			'{'.$eol.
			' var old_page'.$eol.
			' var e'.$eol.$eol.
			' old_page = '.$form->GetJavascriptInputValue('form', $this->page).$eol.
			' '.$form->GetJavascriptSetInputValue('form', $this->page, 'page').$eol.
			' if((e = document.getElementById(\''.$this->tab.'\' + old_page)))'.$eol.
			'  e.className = \''.$tab_class.'\''.$eol.
			' if((e = document.getElementById(\''.$this->button.'\' + old_page)))'.$eol.
			'  e.className = \''.$tab_button_class.'\''.$eol.
			' if((e = document.getElementById(\''.$this->page.'\' + old_page)))'.$eol.
			'  e.style.display = \'none\''.$eol.
			' if((e = document.getElementById(\''.$this->tab.'\' + page)))'.$eol.
			'  e.className = \''.$page_class.'\''.$eol.
			' if((e = document.getElementById(\''.$this->button.'\' + page)))'.$eol.
			'  e.className = \''.$page_button_class.'\''.$eol.
			(($this->fade_pages_time>0 && strlen($fade_error) == 0) ?
			' if(page != old_page)'.$eol.
			' {'.$eol.
			'  '.$fade_javascript.$eol.
			' }'.$eol
			 : '').
			' if((e = document.getElementById(\''.$this->page.'\' + page)))'.$eol.
			'  e.style.display = \'block\''.$eol.
			'}'.$eol.
			($this->auto_adjust_size ? $eol.
			'function '.$this->adjust.'_size(e)'.$eol.
			'{'.$eol.
			' if(document.getBoxObjectFor)'.$eol.
			' {'.$eol.
			'  var b=document.getBoxObjectFor(e)'.$eol.
			'  var size={width: parseInt(b.width), height: parseInt(b.height)}'.$eol.
			'  if(window.getComputedStyle)'.$eol.
			'  {'.$eol.
			'   var s=window.getComputedStyle(e,null)'.$eol.
			'   size.width-=parseInt(s.borderLeftWidth)+parseInt(s.borderRightWidth)+parseInt(s.paddingLeft)+parseInt(s.paddingRight)'.$eol.
			'   size.height-=parseInt(s.borderTopWidth)+parseInt(s.borderBottomWidth)+parseInt(s.paddingTop)+parseInt(s.paddingRight)'.$eol.
			'  }'.$eol.
			' }'.$eol.
			' else'.$eol.
			'  var size={width: parseInt(e.offsetWidth), height: parseInt(e.offsetHeight)}'.$eol.
			' return(size)'.$eol.
			' }'.$eol.$eol.
			'function '.$this->adjust.'()'.$eol.
			'{'.$eol.
			' var width = 0'.$eol.
			' var height = 0'.$eol.
			' var pages = ['.$pages.'];'.$eol.
			' var parent = document.getElementById(\''.$this->page.'_parent\')'.$eol.
			' var ad = [];'.$eol.
			' var l = 0;'.$eol.
			' for(var a = parent; a && a.style; a = a.parentNode, ++l)'.$eol.
			' {'.$eol.
			'  if((ad[l] = a.style.display) == \'none\')'.$eol.
			'   a.style.display = \'block\''.$eol.
			' }'.$eol.
			' for(var p = 0; p < pages.length; ++p)'.$eol.
			' {'.$eol.
			'  var e = document.getElementById(\''.$this->page.'\' + pages[p])'.$eol.
			'  if(!e) continue'.$eol.
			'  var d = e.style.display'.$eol.
			'  if(d == \'none\')'.$eol.
			'  {'.$eol.
			'   e.style.visibility = \'hidden\''.$eol.
			'   e.style.display = \'block\''.$eol.
			'  }'.$eol.
			'  var s = '.$this->adjust.'_size(e)'.$eol.
			'  width = Math.max(width, s.width)'.$eol.
			'  height = Math.max(height, s.height)'.$eol.
			'  if(d == \'none\')'.$eol.
			'  {'.$eol.
			'   e.style.display = d'.$eol.
			'   e.style.visibility = \'\''.$eol.
			'  }'.$eol.
			' }'.$eol.
			' l = 0'.$eol.
			' for(var a = parent; a && a.style; a = a.parentNode, ++l)'.$eol.
			' {'.$eol.
			'  if(ad[l]  == \'none\')'.$eol.
			'   a.style.display = \'none\''.$eol.
			' }'.$eol.
			' if(parent)'.$eol.
			' {'.$eol.
			'  if(width)'.$eol.
			'   parent.style.width = width + "px"'.$eol.
			'  if(height)'.$eol.
			'   parent.style.height = height + "px"'.$eol.
			' }'.$eol.
			'}'.$eol
			: '').
			'// --></script>'.$eol;
		if(strlen($this->page_class)==0
		|| strlen($this->tab_class)==0
		|| strlen($this->gap_class)==0
		|| strlen($this->page_button_class)==0
		|| strlen($this->tab_button_class)==0)
		{
			$head .= '<style type="text/css"><!--'.$eol.
				(strlen($this->page_class) ? '' : '.'.$this->class.'page {'.$this->styles['page'].' }'.$eol).
				(strlen($this->tab_class) ? '' : '.'.$this->class.'tab {'.$this->styles['tab'].' }'.$eol).
				(strlen($this->gap_class) ? '' : '.'.$this->class.'gap {'.$this->styles['gap'].' }'.$eol).
				(strlen($this->page_button_class) ? '' : '.'.$this->class.'page_button {'.$this->styles['page_button'].' }'.$eol).
				(strlen($this->tab_button_class) ? '' : '.'.$this->class.'tab_button {'.$this->styles['tab_button'].' }'.$eol).
				'// --></style>'.$eol;
		}
		return($head);
	}

	Function PageLoad(&$form)
	{
		return($this->auto_adjust_size ? $this->adjust.'();' : '');
	}

	Function AddPagePart(&$form, $page)
	{
		return($form->AddInputPart($page));
	}

	Function GetContainedPageInputs(&$form, $page, &$contained)
	{
		if(IsSet($this->contained[$page]))
		{
			$contained = $this->contained[$page];
			return('');
		}
		if(strlen($error = $form->GetContainedInputs($page, '', $contained)))
			return($error);
		$this->contained[$page] = $contained ;
		return('');
	}


	Function ValidateInput(&$form)
	{
		if(count($form->Invalid) == 0)
			return('');
		Reset($form->Invalid);
		$invalid = Key($form->Invalid);
		$flip = function_exists('array_flip');
		$tp = count($this->pages);
		for($found = 0, Reset($this->pages), $p = 0; $p < $tp; ++$p, Next($this->pages))
		{
			$page = Key($this->pages);
			if(strlen($error = $this->GetContainedPageInputs($form, $page, $page_contained)))
				return($error);
			if($flip)
			{
				$contained = array_flip($page_contained);
				if(($found = IsSet($contained[$invalid])))
					break;
			}
			else
			{
				$tc = count($page_contained);
				for($c = 0; $c < $tc; ++$c)
				{
					if(($found = !strcmp($invalid, $page_contained[$c])))
						break 2;
				}
					
			}
		}
		return($found ? $form->SetInputValue($this->page, $this->current_page = $page) : '');
	}

	Function GetContainedInputs(&$form, $kind, &$contained)
	{
		$contained = array($this->input);
		$tp = count($this->pages);
		for(Reset($this->pages), $p = 0; $p < $tp; ++$p, Next($this->pages))
		{
			$page = Key($this->pages);
			if(strlen($kind) == 0)
			{
				if(strlen($error = $this->GetContainedPageInputs($form, $page, $page_contained)))
					return($error);
			}
			elseif(strlen($error = $form->GetContainedInputs($page, $kind, $page_contained)))
				return($error);
			$tc = count($page_contained);
			for($c = 0; $c < $tc; ++$c)
				$contained[] = $page_contained[$c];
		}
		return('');
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'SwitchPage':
				$javascript = '';
				if(IsSet($context['Page']))
				{
					if(!IsSet($this->pages[$context['Page']]))
						return($context['Page'].' is not a valid page to switch');
					$page = $form->EncodeJavascriptString($context['Page']);
					$conditional = 0;
				}
				elseif(IsSet($context['PageValue']))
				{
					if(strlen($context['PageValue']) == 0)
						return($context['PageValue'].' is not a valid expression of a page to switch');
					$page = $context['PageValue'];
					$conditional = 1;
				}
				elseif(IsSet($context['InputsPage']))
				{
					$inputs = $context['InputsPage'];
					$javascript.='var pages = {';
					$tp = count($this->pages);
					for(Reset($this->pages), $p = 0; $p < $tp; ++$p, Next($this->pages))
					{
						$page = Key($this->pages);
						if(strlen($error = $this->GetContainedPageInputs($form, $page, $contained)))
							return($error);
						$page_value = $form->EncodeJavascriptString($page);
						$tc = count($contained);
						for($c = 0; $c < $tc; ++$c)
						{
							if($c > 0
							|| $p > 0)
								$javascript.=', ';
							$javascript.=$form->EncodeJavascriptString($contained[$c]).': '.$page_value;
						}
					}
					$javascript.=' }; var page = \'\'; for(var i in '.$inputs.') { if(pages[i]) { page = pages[i]; break; } }; ';
					$page = 'page';
					$conditional = 1;
				}
				else
					return('it was not specified a valid page to switch');
				$javascript .= ($conditional ? 'if('.$page.'.length) ' : '').$this->switch.'('.$form_object.', '.$page.');';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case 'CurrentPage':
				if(!IsSet($this->pages[$value]))
					return($value.' is not a valid current page');
				$this->current_page = $value;
				break;
			default:
				return($this->DefaultSetInputProperty($form, $property, $value));
		}
		return("");
	}
};

?>