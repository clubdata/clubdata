<?php
/*
 *
 * @(#) $Id: form_auto_complete.php,v 1.24 2012/06/22 02:51:44 mlemos Exp $
 *
 */

class form_auto_complete_class extends form_custom_class
{
	var $text='';
	var $complete='';
	var $ajax='';
	var $minimum_complete=3;
	var $dynamic=1;
	var $complete_delay=500;
	var $complete_values=array();
	var $menu_style='background-color: #ffffff; border-width: 1px; border-color: #000000; border-style: solid; padding: 1px;';
	var $menu_class='';
	var $item_style='padding: 1px; color: #000000;';
	var $item_class='';
	var $selected_item_style='padding: 1px; color: #ffffff; background-color: #000080;';
	var $selected_item_class='';
	var $button='';
	var $server_validate=0;

	var $item_style_attributes='';

	Function SerializeItems(&$form, $items)
	{
		Reset($items);
		for($results='[', $f = 0; $f<count($items); Next($items), $f++)
		{
			if($f>0)
				$results.=', ';
			$v=Key($items);
			$results.='{ "v": '.$form->EncodeJavascriptString($v).', "e": '.$form->EncodeJavascriptString($form->EncodeJavascriptString(HtmlSpecialChars($v))).', "d": '.$form->EncodeJavascriptString($items[$v]).' }';
		}
		return($results.']');
	}

	Function GetCompleteValues(&$form, $arguments)
	{
		if(!IsSet($arguments['CompleteValues'])
		|| GetType($complete_values=$arguments['CompleteValues'])!='array'
		|| count($complete_values)==0)
			return('it were not specified valid complete values');
		$this->complete_values=$complete_values;
		return('');
	}

	Function SearchCompleteValues(&$form, $text, &$found)
	{
		if(strlen($text)==0)
			$found=$this->complete_values;
		else
		{
			$t=strtolower($text);
			for($found=array(), Reset($this->complete_values), $v=0; $v<count($this->complete_values); $v++, Next($this->complete_values))
			{
				$c=Key($this->complete_values);
				if(!strcmp($t, strtolower(substr($c, 0, strlen($t)))))
					$found[$c]=$this->complete_values[$c];
			}
		}
		return('');
	}

	Function AddInput(&$form, $arguments)
	{
		if(!IsSet($arguments['CompleteInput'])
		|| strlen($arguments['CompleteInput'])==0)
			return('it was not specified a valid text input to complete');
		$this->text=$arguments['CompleteInput'];
		if(IsSet($arguments['CompleteMinimumLength']))
		{
			$minimum_complete=intval($arguments['CompleteMinimumLength']);
			if($minimum_complete<=0)
				return('it was not specified a valid minimum length to complete the text');
			$this->minimum_complete=$minimum_complete;
		}
		if(IsSet($arguments['CompleteDelay']))
		{
			$complete_delay=intval($arguments['CompleteDelay']*1000);
			if($complete_delay<=0)
				return('it was not specified a valid complete delay period');
			$this->complete_delay=$complete_delay;
		}
		if(IsSet($arguments['ShowButton']))
		{
			$button=$arguments['ShowButton'];
			if(strlen($button)==0)
				return('it was not specified a valid button input to show all options');
			$this->button=$button;
		}
		if(IsSet($arguments['Dynamic'])
		&& !$arguments['Dynamic'])
			$this->dynamic=0;
		if(IsSet($arguments['MenuClass']))
			$this->menu_class=$arguments['MenuClass'];
		if(IsSet($arguments['MenuStyle']))
			$this->menu_style=$arguments['MenuStyle'];
		if(IsSet($arguments['ItemClass']))
			$this->item_class=$arguments['ItemClass'];
		if(IsSet($arguments['ItemStyle']))
			$this->item_style=$arguments['ItemStyle'];
		if(IsSet($arguments['SelectedItemClass']))
			$this->selected_item_class=$arguments['SelectedItemClass'];
		if(IsSet($arguments['SelectedItemStyle']))
			$this->selected_item_style=$arguments['SelectedItemStyle'];
		$this->complete=$this->GenerateInputID($form, $this->input, '_');
		if($this->dynamic)
		{
			$this->ajax=$this->complete.'ajax';
			$ajax_arguments=array(
				'TYPE'=>'custom',
				'NAME'=>$this->ajax,
				'ID'=>$this->ajax,
				'CustomClass'=>'form_ajax_submit_class',
				'TargetInput'=>$this->input
			);
			if(IsSet($arguments['Timeout']))
				$ajax_arguments['Timeout']=intval($arguments['Timeout']);
			if(IsSet($arguments['FeedbackElement']))
			{
				$ajax_arguments['FeedbackElement']=$arguments['FeedbackElement'];
				if(IsSet($arguments['SubmitFeedback']))
					$ajax_arguments['SubmitFeedback']=$arguments['SubmitFeedback'];
				if(IsSet($arguments['TimeoutFeedback']))
				{
					$ajax_arguments['TimeoutFeedback']=$arguments['TimeoutFeedback'];
					$ajax_arguments['ONTIMEOUT']='';
				}
				if(IsSet($arguments['CompleteFeedback']))
					$ajax_arguments['CompleteFeedback']=$arguments['CompleteFeedback'];
			}
		}
		if(strlen($error=$this->GetCompleteValues($form, $arguments)))
			return($error);
		if((!$this->dynamic
		|| (strlen($error=$form->AddInput($ajax_arguments))==0
		&& strlen($error=$form->Connect($this->ajax, $this->input, 'ONCOMPLETE', 'Reposition', array()))==0))
		&& (strlen($this->button)==0
		|| ((!$this->dynamic
		|| strlen($error=$form->AddInput(array(
			'TYPE'=>'hidden',
			'NAME'=>$this->complete.'t',
			'ID'=>$this->complete.'t',
			'VALUE'=>''
		)))==0)
		&& strlen($error=$form->Connect($this->button, $this->input, 'ONCLICK', 'Show', array()))==0))
		&& strlen($error=$form->Connect($this->text, $this->input, 'ONBLUR', 'Hide', array('Delay'=>0.2)))==0
		&& strlen($error=$form->Connect($this->text, $this->input, 'ONKEYDOWN', 'ControlKeys', array()))==0)
			$error=$form->Connect($this->text, $this->input, 'ONKEYUP', 'Complete', array());
		return($error);
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'Complete':
				$value=$form->GetJavascriptInputValue($form_object, $this->text);
				if(strlen($value)==0)
					return('it was not possible to determine how to retrieve value of '.$this->text);
				$javascript='if('.(strcmp($event,'ONKEYUP') ? '' : 'event.keyCode!=40 && event.keyCode!=38 && event.keyCode!=27 && event.keyCode!=13 && ').$value.'.length>='.$this->minimum_complete.'){ '.$this->complete.'w++; '.$this->complete.'f='.$form_object.'; setTimeout('."'".$this->complete."()',".$this->complete_delay.'); return false;};';
				break;
			case 'Hide':
				$javascript=$this->complete.'h();';
				$delay=(IsSet($context['Delay']) ? intval($context['Delay']*1000) : 0);
				if($delay)
					$javascript='setTimeout('.$form->EncodeJavascriptString($javascript).', '.$delay.');';
				break;
			case 'Show':
				if($this->dynamic)
				{
					$submit_context=array('Validate'=>0);
					if(strlen($error=$form->GetJavascriptConnectionAction($form_object, $this->input, $this->ajax, 'ONSHOW', 'Submit', $submit_context, $complete_javascript)))
						return($error);
					$javascript=$this->complete.'f='.$form_object.'; '.$form->GetJavascriptSetInputValue($this->complete.'f', $this->complete.'t', $form->EncodeJavascriptString('a')).' '.$complete_javascript.';';
				}
				else
					$javascript=$this->complete.'f='.$form_object.'; '.$this->complete.'bm('.$this->complete.'i, false); '.$form->GetJavascriptInputObject($form_object, $this->text).'.focus();';
				$javascript='if(!'.$this->complete.'o) {'.$javascript.'}  return false;';
				break;
			case 'Reposition':
				$javascript=$this->complete.'rp(document.getElementById('.$form->EncodeJavascriptString($this->complete.'m').'), '.$form->GetJavascriptInputObject($form_object, $this->text).');';
				break;
			case 'ControlKeys':
 				$javascript='if('.$this->complete.'o) { if(event.keyCode==40 && '.$this->complete.'is<'.$this->complete.'co.length-1) { '.$this->complete.'si('.$this->complete.'is+1); '.$this->complete.'so(t,'.$this->complete.'is); return false; } if(event.keyCode==38 && '.$this->complete.'is>0) { '.$this->complete.'si('.$this->complete.'is-1);'.$this->complete.'so(t,'.$this->complete.'is); return false; } if(event.keyCode==27) { '.$this->complete.'h(); return false; }  if(event.keyCode==13) { '.$this->complete.'h(); return true; } };';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}

	Function AddInputPart(&$form)
	{
		if($this->dynamic)
		{
			$submit_context=array('Validate'=>0);
			if(strlen($error=$form->GetJavascriptConnectionAction($this->complete.'f', $this->input, $this->ajax, 'ONCOMPLETE', 'Submit', $submit_context, $complete_javascript)))
				return($error);
		}
		$eol=$form->end_of_line;
		$b="\n";
		$item_style=(strlen($this->item_style) ? $this->item_style : ';');
		$selected_item_style=(strlen($this->selected_item_style) ? $this->selected_item_style : ';');
		if(strlen($this->item_style_attributes)==0)
		{
			$this->item_style_attributes=(strlen($this->item_class) ? ' class="'.HtmlSpecialChars($this->item_class).'"' : '').((strlen($this->item_style) || strlen($this->selected_item_style)) ? ' style="'.HtmlSpecialChars($item_style).'"' : '');
		}
		$menu=$form->EncodeJavascriptString($this->complete.'m');
		$text_object = $form->GetJavascriptInputObject($this->complete.'f', $this->text);
		$html='<div id="'.HtmlSpecialChars($this->complete.'m').'"'.(strlen($this->menu_class) ? ' class="'.HtmlSpecialChars($this->menu_class).'"' : '').' style="display: block; position: absolute; overflow: auto; visibility: hidden;'.HtmlSpecialChars($this->menu_style).'"></div>'.$b.
			'<script type="text/javascript" defer="defer">'.$eol.'<!--'."\n".
			'var '.$this->complete.'w=0;'.$b.
			'var '.$this->complete.'s=\'\';'.$b.
			'var '.$this->complete.'f;'.$b.
			'var '.$this->complete.'i'.($this->dynamic ? '' : '='.$this->SerializeItems($form, $this->complete_values)).';'.$b.
			'var '.$this->complete.'is=-1;'.$b.
			'var '.$this->complete.'o=false;'.$b.
			'var '.$this->complete.'co=[];'.$b.
			'var '.$this->complete.'l=0;'.$b.
			(
					$this->dynamic
				?
					'var '.$this->complete.'c={};'.$b
				:
					''
			).
			$eol.
			'function '.$this->complete.'()'.$b.
			'{'.$b.
			'if(--'.$this->complete.'w==0)'.$b.
			'{'.$b.
			's='.$form->GetJavascriptInputValue($this->complete.'f', $this->text).'.toLowerCase();'.$b.
			'if('.$this->complete.'s!=s)'.$b.
			'{'.$b.
			'm=document.getElementById('.$menu.');'.$b.
			'm.style.visibility=\'hidden\'; '.$b.
			$this->complete.'o=false;'.$b.
			$this->complete.'is=-1;'.$b.
			'if(s.length>='.$this->minimum_complete.')'.$b.
			'{'.$b.
			(
					$this->dynamic
				?
					'if('.$this->complete.'c[s])'.$b.
					'{'.$b.
					$this->complete.'s=s;'.$b.
					$this->complete.'bm('.$this->complete.'c[s], true);'.$b.
					'}'.$b.
					'else'.$b.
					'{'.$b.
					$this->complete.'s=s;'.$b.
					(
							strlen($this->button)
						?
							$form->GetJavascriptSetInputValue($this->complete.'f', $this->complete.'t', $form->EncodeJavascriptString('')).$b
						:
							''
					).
					$complete_javascript.
					'}'.$b
				:
					'o=[];'.$b.
					'for (var i=0; i<'.$this->complete.'i.length; i++)'.$b.
					'{'.$b.
					'if('.$this->complete.'i[i].v.toLowerCase().substr(0,s.length)==s)'.$b.
					'o[o.length]='.$this->complete.'i[i];'.$b.
					'}'.$b.
					'if(o.length)'.$b.
					''.$this->complete.'bm(o, true);'.$b
			).
			'}'.$b.
			'}'.$b.
			'}'.$b.
			'}'.$eol.
			'function '.$this->complete.'ss(e,s)'.$b.
			'{'.$b.
			'if(e.currentStyle)'.$b.
			'{'.$b.
			'e.style.cssText=s;'.$b.
			'}'.$b.
			'else'.$b.
			'{'.$b.
			'e.setAttribute(\'style\', s);'.$b.
			'}'.$b.
			'}'.$eol.
			'function '.$this->complete.'rp(m,t)'.$b.
			'{'.$b.
			'if(document.getBoxObjectFor)'.$b.
			'{'.$b.
			'b=document.getBoxObjectFor(t);'.$b.
			'x=b.x;'.$b.
			'y=b.y+b.height;'.$b.
			'w=b.width;'.$b.
			'if(window.getComputedStyle)'.$b.
			'{'.$b.
			's=window.getComputedStyle(t,null);'.$b.
			'x-=parseInt(s.borderLeftWidth);'.$b.
			'y-=parseInt(s.borderTopWidth);'.$b.
			'w-=parseInt(s.borderLeftWidth)+parseInt(s.borderRightWidth);'.$b.
			's=window.getComputedStyle(m,null);'.$b.
			'w+=parseInt(s.borderLeftWidth)+parseInt(s.borderRightWidth)-parseInt(s.paddingLeft)-parseInt(s.paddingRight);'.$b.
			'}'.$b.
			'}'.$b.
			'else'.$b.
			'{'.$b.
			'p=t.style.position;'.$b.
			't.style.position="relative";'.$b.
			'x=t.offsetLeft;'.$b.
			'y=t.offsetTop+t.offsetHeight;'.$b.
			'w=t.offsetWidth;'.$b.
			't.style.position=p;'.$b.
			'}'.$b.
			'm.style.left=x+"px";'.$b.
			'm.style.top=y+"px";'.$b.
			'm.style.width=w+"px";'.$b.
			'}'.$eol.
			'function '.$this->complete.'si(i)'.$b.
			'{'.$b.
			'if('.$this->complete.'is!=-1)'.$b.
			'{'.$b.
			's=document.getElementById('.$form->EncodeJavascriptString($this->complete.'m').' + '.$this->complete.'is);'.$b.
			 ((strlen($this->item_class) || strlen($this->selected_item_class)) ? 's.className='.$form->EncodeJavascriptString($this->item_class).';'.$b : '').
			 ((strlen($this->item_style) || strlen($this->selected_item_style)) ? $this->complete.'ss(s, '.$form->EncodeJavascriptString($item_style).');'.$b : '').
			'}'.$b.
			'if(i!=-1)'.$b.
			'{'.$b.
			's=document.getElementById('.$form->EncodeJavascriptString($this->complete.'m').' + i);'.$b.
			((strlen($this->selected_item_class) || strlen($this->item_class)) ? 's.className='.$form->EncodeJavascriptString($this->selected_item_class).';'.$b : '').
			((strlen($this->selected_item_style) || strlen($this->item_style)) ? $this->complete.'ss(s, '.$form->EncodeJavascriptString($selected_item_style).');'.$b : '').
			'}'.$b.
			$this->complete.'is=i;'.$b.
			'}'.$eol.
			'function '.$this->complete.'so(t,i)'.$b.
			'{'.$b.
			'o='.$this->complete.'co;'.$b.
			$this->complete.'si(i);'.$b.
			'var b=t.value;'.$b.
			't.value=o[i].v;'.$b.
			'if(b != t.value && t.onchange)'.$b.
			' t.onchange()'.$b.
			'if(t.createTextRange)'.$b.
			'{'.$b.
			'if(r=t.createTextRange())'.$b.
			'{'.$b.
			'r.collapse(true);'.$b.
			'r.moveEnd(\'character\', o[i].v.length);'.$b.
			'r.moveStart(\'character\', '.$this->complete.'l);'.$b.
			'r.select();'.$b.
			'}'.$b.
			'}'.$b.
			'else'.$b.
			'{'.$b.
			'if(t.setSelectionRange)'.$b.
			't.setSelectionRange('.$this->complete.'l,o[i].v.length);'.$b.
			'else'.$b.
			'{'.$b.
			't.selectionStart='.$this->complete.'l;'.$b.
			't.selectionEnd=o[i].v.length;'.$b.
			'}'.$b.
			'}'.$b.
			'}'.$eol.
			'function '.$this->complete.'bm(o, sv)'.$b.
			'{'.$b.
			'for(d=\'\',i=0; i<o.length; i++)'.$b.
			'{'.$b.
			'd+='.$form->EncodeJavascriptString('<div id="'.$this->complete.'m').' + i + '.$form->EncodeJavascriptString('"'.$this->item_style_attributes.' onmouseover="'.$this->complete.'si(').' + i +'.$form->EncodeJavascriptString(');" onmouseout="'.$this->complete.'si(-1);" onmousedown="'.$this->complete.'s=\'\'; var b='.$text_object.'.value;'.$text_object.'.value=').'+o[i].e+'.$form->EncodeJavascriptString('; document.getElementById('.$menu.').style.visibility=\'hidden\';'.$this->complete.'o=false; '.$this->complete.'is=-1; if('.$text_object.'.value != b && '.$text_object.'.onchange) '.$text_object.'.onchange();">').'+o[i].d+'.$form->EncodeJavascriptString('</div>'.$b).';'.$b.
			'}'.$b.
			'm=document.getElementById('.$menu.');'.$b.
			'm.innerHTML=d;'.$b.
			't='.$text_object.';'.$b.
			$this->complete.'rp(m,t);'.$b.
			'm.style.visibility=\'visible\';'.$b.
			$this->complete.'o=true;'.$b.
			$this->complete.'co=o;'.$b.
			'if(sv)'.$b.
			'{'.$b.
			$this->complete.'l=t.value.length'.$b.
			$this->complete.'so(t,0);'.$b.
			'}'.$b.
			'else'.$b.
			'{'.$b.
			$this->complete.'l=0'.$b.
			$this->complete.'is=-1;'.$b.
			'}'.$b.
			'}'.$eol.
			'function '.$this->complete.'h()'.$b.
			'{'.$b.
			$this->complete.'s=\'\';'.$b.
			'm=document.getElementById('.$form->EncodeJavascriptString($this->complete.'m').');'.$b.
			'm.style.visibility=\'hidden\';'.$b.
			$this->complete.'o=false;'.$b.
			$this->complete.'is=-1;'.$b.
			'}'.$eol.
			'// -->'.$eol.'</script>';
		if(strlen($error=$form->AddDataPart($html))
		|| ($this->dynamic
		&& strlen($error=$form->AddInputPart($this->ajax)))
		|| (strlen($this->button)
		&& (strlen($error=$form->AddInputPart($this->button))
		|| ($this->dynamic
		&& strlen($error=$form->AddInputPart($this->complete.'t'))))))
			return($error);
		return('');
	}

	Function Connect(&$form, $to, $event, $action, &$context)
	{
		switch($action)
		{
			case 'Complete':
			case 'Hide':
			case 'Show':
			case 'ControlKeys':
				return('');
			default:
				return($this->DefaultConnect($form, $to, $event, $action, $context));
		}
	}

	Function PostMessage(&$form, $message, &$processed)
	{
		if(strlen($error = $form->LoadInputValues()))
			return($error);
		$text=$form->GetInputValue($this->text);
		$all=(strlen($this->button) && !strcmp($form->GetInputValue($this->complete.'t'), 'a'));
		$found=array();
		if(($all
		|| strlen($text)>=$this->minimum_complete)
		&& strlen($error=$this->SearchCompleteValues($form, $s=($all ? '' : strtolower($text)), $found)))
			$form->OutputError($error, $this->input);
		elseif(count($found))
		{
			$results=$this->SerializeItems($form, $found);
			$s=$form->EncodeJavascriptString($s);
			$command=$message['Window'].'.'.$this->complete.'i='.$message['Window'].'.'.$this->complete.'c['.$s.']='.$results.'; if('.$message['Window'].'.'.$this->complete.'s.toLowerCase()=='.$s.') {'.$message['Window'].'.'.$this->complete.'bm('.$message['Window'].'.'.$this->complete.'i, '.($all ? 'false' : 'true').'); '.$form->GetJavascriptInputObject($message['Form'], $this->text).'.focus();}';
			$message['Actions']=array(
				array(
					'Action'=>'Command',
					'Command'=>$command
				)
			);
		}
		return($form->ReplyMessage($message, $processed));
	}
};

?>