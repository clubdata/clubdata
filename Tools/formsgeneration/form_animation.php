<?php
/*
 *
 * @(#) $Id: form_animation.php,v 1.10 2006/08/06 06:29:06 mlemos Exp $
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
							if(!IsSet($context['Effects'][$e]['Element']))
							return('it was not specified the element of the '.$type.' effect '.$e);
							$animation.=', element: '.$form->EncodeJavascriptString($context['Effects'][$e]['Element']);
							break;
						case 'FadeIn':
						case 'FadeOut':
							if(!IsSet($context['Effects'][$e]['Element']))
							return('it was not specified the element of the '.$type.' effect '.$e);
							if(!IsSet($context['Effects'][$e]['Duration']))
							return('it was not specified the duration of the '.$type.' effect '.$e);
							$animation.=', element: '.$form->EncodeJavascriptString($context['Effects'][$e]['Element']);
							$animation.=', duration: '.doubleval($context['Effects'][$e]['Duration']);
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