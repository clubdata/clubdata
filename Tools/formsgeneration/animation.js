/*
 *
 * @(#) $Id: animation.js,v 1.13 2006/08/06 06:28:31 mlemos Exp $
 *
 */

if(ML === undefined)
	var ML = { }

if(ML.Animation === undefined)
	ML.Animation = { }

if(ML.Animation.Animate === undefined)
{

ML.Animation.animations = []
ML.Animation.running = 0
ML.Animation.poll = null
ML.Animation.frameRate = 120
ML.Animation.fadeStep = 100

ML.Animation.Animate = function()
{
	var steps = 0

	var setOpacity = function(element, value)
	{
		element.style.opacity = element.style['-moz-opacity'] = element.style['-khtml-opacity'] = value
		element.style.filter = 'alpha(opacity=' + Math.round(100*value) + ')'
		if(!element.currentStyle
		|| !element.currentStyle.hasLayout)
			element.style.zoom = 1
	}

	var stepEffects = function()
	{
		for(var a = 0; a<ML.Animation.animations.length; a++)
		{
			if(ML.Animation.animations[a] !== null
			&& ML.Animation.animations[a].running)
			{
				var animation = ML.Animation.animations[a]
				var step = false
				var effect = animation.definition.effects[animation.effect]
				if(effect.type == 'FadeIn')
				{
					var p=(new Date().getTime() - effect.start)/(effect.duration*1000.0)
					if(p>1)
						p = 1
					var opacity = Math.round(p * ML.Animation.fadeStep) / ML.Animation.fadeStep
					if(opacity != effect.opacity)
						setOpacity(effect.node, effect.opacity = opacity)
					step = p < 1
				}
				else
				{
				if(effect.type == 'FadeOut')
				{
					var p=(new Date().getTime() - effect.start)/(effect.duration*1000.0)
					if(p>1)
						p = 1
					var opacity = Math.round((1.0 - p) * ML.Animation.fadeStep) / ML.Animation.fadeStep
					if(opacity != effect.opacity)
						setOpacity(effect.node, effect.opacity = opacity)
					step = p < 1
					if(!step)
						effect.node.style.visibility = 'hidden'
				}
				}
				if(!step)
					advanceAnimation(animation)
			}
		}
	}

	var cancelAnimation = function(animation)
	{
		ML.Animation.animations[animation.animation] = null
		if(--ML.Animation.running == 0)
		{
			clearInterval(ML.Animation.poll)
			ML.Animation.poll = null
		}
	}

	var advanceAnimation = function(animation)
	{
		animation.running = false
		if(++animation.effect < animation.definition.effects.length)
			startEffect(animation)
		else
			cancelAnimation(animation)
	}

	var startEffect = function(animation)
	{
		var effect = animation.definition.effects[animation.effect]
		var type = effect.type
		var advance = 0

		if(type == 'Show')
		{
			var e = document.getElementById(effect.element)
			if(e)
				e.style.visibility = 'visible'
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
			}
			advance = 1
		}
		else
		{
		if(type == 'Hide')
		{
			var e = document.getElementById(effect.element)
			if(e)
				e.style.visibility = 'hidden'
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
			}
			advance = 1
		}
		else
		{
		if(type == 'FadeIn')
		{
			effect.node = document.getElementById(effect.element)
			if(effect.node)
			{
				effect.opacity = 0
				setOpacity(effect.node, effect.opacity)
				effect.node.style.visibility = 'visible'
				effect.start = new Date().getTime()
			}
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
				advance = 1
			}
		}
		else
		{
		if(type == 'FadeOut')
		{
			effect.node = document.getElementById(effect.element)
			if(effect.node)
			{
				effect.opacity = 1.0
				setOpacity(effect.node, effect.opacity)
				effect.start = new Date().getTime()
			}
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
				advance = 1
			}
		}
		else
		{
		if(type == 'CancelAnimation')
		{
			for(var a = 0, c = 0; a<ML.Animation.animations.length; a++)
			{
				if(ML.Animation.animations[a] !== null
				&& animation.animation != a
				&& ML.Animation.animations[a].definition.name
				&& ML.Animation.animations[a].definition.name == effect.animation)
				{
					cancelAnimation(ML.Animation.animations[a])
					c++;
				}
			}
			if(animation.definition.debug
			&& animation.definition.debug >= 2
			&& c == 0)
				alert('Inexisting animation to cancel "' + effect.animation + '" of effect ' + animation.effect);
			advance = 1
		}
		else
		{
		if(type == 'AppendContent')
		{
			var e = document.getElementById(effect.element)
			if(e)
				e.innerHTML += effect.content
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
			}
			advance = 1
		}
		else
		{
		if(type == 'PrependContent')
		{
			var e = document.getElementById(effect.element)
			if(e)
				e.innerHTML = effect.content + e.innerHTML
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
			}
			advance = 1
		}
		else
		{
		if(type == 'ReplaceContent')
		{
			var e = document.getElementById(effect.element)
			if(e)
				e.innerHTML = effect.content
			else
			{
				if(animation.definition.debug)
					alert('Inexisting element "' + effect.element + '" of effect ' + animation.effect + '"' + type + '"');
			}
			advance = 1
		}
		else
		{
			if(animation.definition.debug)
				alert('Unsupported animation type "' + type + '"');
			advance = 1
		}
		}
		}
		}
		}
		}
		}
		}
		if(advance)
			advanceAnimation(animation)
		else
		{
			if(ML.Animation.poll === null)
			{
				var timeout = 1000 / ML.Animation.frameRate
				ML.Animation.poll = setInterval(stepEffects, timeout < 1 ? 1 : timeout)
			}
			animation.running = true
		}
	}
	
	this.addAnimation = function(animation)
	{
		a = ML.Animation.animations.length
		ML.Animation.animations[a] = { definition: animation, animation: a, effect: 0, running: false }
		ML.Animation.running++
		startEffect(ML.Animation.animations[a])
		return a
	}

}

}
