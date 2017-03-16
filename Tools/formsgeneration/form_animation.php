<?php
/*
 *
 * @(#) $Id: form_animation.php,v 1.13 2008/09/07 06:24:27 mlemos Exp $
 *
 */

class form_animation_class extends form_custom_class
{
	var $server_validate=0;
	var $javascript_path='';

	Function AddInput(&$form, $arguments)
	{
		if(IsSet($arguments['JavascriptPath']))
		{
			$this->javascript_path=$arguments['JavascriptPath'];
			if(($length=strlen($this->javascript_path))
			&& strcmp($this->javascript_path[$length-1], '/'))
				$this->javascript_path.='/';
		}
		return('');
	}

	Function AddInputPart(&$form)
	{
		return('');
	}

	Function ClassPageHead(&$form)
	{
		return('<script type="text/javascript" src="'.HtmlSpecialChars($this->javascript_path).'animation.js"></script>'."\n");
	}

	Function GetJavascriptConnectionAction(&$form, $form_object, $from, $event, $action, &$context, &$javascript)
	{
		switch($action)
		{
			case 'AddAnimation':
				if(!IsSet($context['Effects']))
					return('it were not specified any animation effects');
				$animation='{ ';
				if(IsSet($context['Name']))
					$animation.='name: '.$form->EncodeJavascriptString($context['Name']).', ';
				if(IsSet($context['Debug'])
				&& $context['Debug'])
					$animation.='debug: '.intval($context['Debug']).', ';
				$animation.='effects: [ ';
				for($e = 0; $e<count($context['Effects']); $e++)
				{
					if(!IsSet($context['Effects'][$e]['Type']))
						return('it was not specified the type of animation effect '.$e);
					if($e>0)
						$animation.=', ';
					$type = $context['Effects'][$e]['Type'];
					$animation.='{ type: '.$form->EncodeJavascriptString($type);
					switch($type)
					{
						case 'Show':
						case 'Hide':
							if(IsSet($context['Effects'][$e]['Element']))
								$element = $form->EncodeJavascriptString($context['Effects'][$e]['Element']);
							elseif(IsSet($context['Effects'][$e]['DynamicElement']))
								$element = $context['Effects'][$e]['DynamicElement'];
							else
								return('it was not specified the element of the '.$type.' effect '.$e);
							if(IsSet($context['Effects'][$e]['Visibility']))
							{
								switch(($visibility = $context['Effects'][$e]['Visibility']))
								{
									case 'visibility':
									case 'display':
										break;
									default:
										return('it was not specified a valid visilibity control mode for '.$type.' effect');
								}
							}
							else
								$visibility = 'visibility';
							$animation.=', element: '.$element.
								', visibility: '.$form->EncodeJavascriptString($visibility);
							break;
						case 'FadeIn':
						case 'FadeOut':
							if(IsSet($context['Effects'][$e]['Element']))
								$element = $form->EncodeJavascriptString($context['Effects'][$e]['Element']);
							elseif(IsSet($context['Effects'][$e]['DynamicElement']))
								$element = $context['Effects'][$e]['DynamicElement'];
							else
								return('it was not specified the element of the '.$type.' effect '.$e);
							if(!IsSet($context['Effects'][$e]['Duration']))
								return('it was not specified the duration of the '.$type.' effect '.$e);
							if(IsSet($context['Effects'][$e]['Visibility']))
							{
								switch(($visibility = $context['Effects'][$e]['Visibility']))
								{
									case 'visibility':
									case 'display':
										break;
									default:
										return('it was not specified a valid visilibity control mode for '.$type.' effect');
								}
							}
							else
								$visibility = 'visibility';
							$animation.=', element: '.$element.
								', duration: '.doubleval($context['Effects'][$e]['Duration']).
								', visibility: '.$form->EncodeJavascriptString($visibility);
							break;
						case 'CancelAnimation':
							if(!IsSet($context['Effects'][$e]['Animation']))
								return('it was not specified the animation of the cancel-animation effect '.$e);
							$animation.=', animation: '.$form->EncodeJavascriptString($context['Effects'][$e]['Animation']);
							break;
						case 'AppendContent':
						case 'PrependContent':
						case 'ReplaceContent':
							if(!IsSet($context['Effects'][$e]['Element']))
								return('it was not specified the element of the '.$type.' effect '.$e);
							if(!IsSet($context['Effects'][$e]['Content']))
								return('it was not specified the content of the '.$type.' effect '.$e);
							$animation.=', element: '.$form->EncodeJavascriptString($context['Effects'][$e]['Element']);
							$animation.=', content: '.$form->EncodeJavascriptString($context['Effects'][$e]['Content']);
							break;
						case 'Wait':
							if(!IsSet($context['Effects'][$e]['Duration']))
								return('it was not specified the duration of the '.$type.' effect '.$e);
							$animation.=', duration: '.doubleval($context['Effects'][$e]['Duration']);
							break;
						default:
							return('animation effect of type '.$type.' is not yet supported');
					}
					$animation.=' }';
				}
				$animation.=' ] }';
				$w=(IsSet($context['Window']) ? $context['Window'].'.' : '');
				$javascript='var a=new '.$w.'ML.Animation.Animate(); a.addAnimation('.$animation.');';
				break;
			default:
				return($this->DefaultGetJavascriptConnectionAction($form, $form_object, $from, $event, $action, $context, $javascript));
		}
		return('');
	}

};

?>