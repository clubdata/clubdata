/*
 *
 * @(#) $Id: animation.js,v 1.29 2012/12/27 06:12:20 mlemos Exp $
 *
 */

var ML;

if(ML === undefined)
{
	ML = { };
}

if(ML.Animation === undefined)
{
	ML.Animation = { };
}

if(ML.Animation.Utility === undefined)
{

ML.Animation.Utility =
{
	setOpacity: function(element, value)
	{
		element.style.opacity = element.style['-moz-opacity'] = element.style['-khtml-opacity'] = value;
		element.style.filter = 'alpha(opacity=' + Math.round(100*value) + ')';
		if(!element.currentStyle
		|| !element.currentStyle.hasLayout)
		{
			element.style.zoom = '100%';
		}
	},

	getElementPosition: function(element)
	{
		var position = { x: 0, y: 0};
		do
		{
			position.x += element.offsetLeft;
			position.y += element.offsetTop;
			element = element.offsetParent;
			if(!element)
			{
				return position;
			}
		}
		while(element.nodeName !== 'HTML');
		position.x += element.offsetLeft;
		position.y += element.offsetTop;
		return position;
	},

	getElementSize: function(element, inner)
	{
		if(inner)
		{
			if(element.innerWidth)
			{
				return { width: element.innerWidth, height: element.innerHeight };
			}
			if(element.clientWidth)
			{
				return { width: element.clientWidth, height: element.clientHeight };
			}
		}
		else
		{
			if(element.clip !== undefined)
			{
				return { width: element.clip.width, height: element.clip.height };
			}
		}
		return { width: element.offsetWidth, height: element.offsetHeight };
	},

	getWindowViewport: function()
	{
		var viewport;

		if(window.pageXOffset !== undefined)
		{
			viewport = { x: window.pageXOffset, y: window.pageYOffset };
		}
		else
		{
			if(document.documentElement
			&& document.documentElement.scrollLeft !== undefined)
			{
				viewport = { x: document.documentElement.scrollLeft, y: document.documentElement.scrollTop };
			}
			else
			{
				if(document.body)
				{
					viewport = { x: document.body.scrollLeft, y: document.body.scrollTop };
				}
				else
				{
					viewport = { x: 0, y: 0 };
				}
			}
		}
		if(window.innerHeight !== undefined)
		{
			viewport.width = window.innerWidth;
			viewport.height = window.innerHeight;
		}
		else
		{
			if(document.documentElement
			&& document.documentElement.clientHeight !== undefined)
			{
				viewport.width = document.documentElement.clientWidth;
				viewport.height = document.documentElement.clientHeight;
			}
			else
			{
				if(document.body)
				{
					viewport.width = document.body.clientWidth;
					viewport.height = document.body.clientHeight;
				}
				else
				{
					viewport.width = 0;
					viewport.height = 0;
				}
			}
		}
		return viewport;
	}
};

}

if(ML.Animation.Effects === undefined)
{
ML.Animation.Effects = {

	AppendContent: function(effect)
	{
		this.start = function()
		{
			var e = document.getElementById(effect.element);
			if(e)
			{
				e.innerHTML += effect.content;
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return true;
		};
	},

	CancelAnimation: function(effect)
	{
		this.start = function()
		{
			var a, c;

			for(a = 0, c = 0; a<ML.Animation.animations.length; a++)
			{
				if(ML.Animation.animations[a] !== null
				&& this.animation.animation !== a
				&& ML.Animation.animations[a].definition.name
				&& ML.Animation.animations[a].definition.name === effect.animation)
				{
					ML.Animation.cancelAnimation(ML.Animation.animations[a]);
					c++;
				}
			}
			if(c === 0
			&& this.debug
			&& this.debug >= 2)
			{
				alert('Inexisting animation to cancel "' + effect.animation + '" of effect ' + this.animation.effect);
			}
			return true;
		};
	},

	Emphasize: function(effect)
	{
		this.context = null;
		this.angle = Math.PI / 6;
		this.angleGap = Math.PI / 48;
		this.underlineAngle = Math.PI / 90;
		this.lineWidth = (effect.lineWidth !== undefined ? effect.lineWidth : 4);
		this.opacity = (effect.opacity !== undefined ? effect.opacity : 0.5);
		this.strokeStyle = (effect.strokeStyle !== undefined ? effect.strokeStyle : '#0000ff');
		this.lastAngle = this.angle;
		this.lastStep = 0;

		function drawEllipse(context, x, y, w, h, start, end, anticlockwise)
		{
			var scale;

			if((w <= 0
			|| h <= 0)
			&& this.debug)
			{
				alert('Inexisting animation to cancel "' + effect.animation + '" of effect ' + this.animation.effect);
				return;
			}
			scale = h / w;
			context.save();
			context.scale(1, scale);
			context.beginPath();
			context.arc(x, y / scale, w, start, end, anticlockwise);
			context.restore();
			context.stroke();
		}
		
		this.start = function()
		{
			var canvas, e = document.getElementById(effect.element);

			if(e)
			{
				canvas = document.createElement('canvas');
				if(!canvas
				|| !canvas.getContext
				|| (this.context = canvas.getContext('2d')) === null)
				{
					if(this.debug)
					{
						alert('Canvas elements are not supported.');
					}
					return true;
				}
				if(effect.canvas !== undefined)
				{
					canvas.id = effect.canvas;
				}
				this.size = ML.Animation.Utility.getElementSize(e, false);
				if(effect.method === 'circle')
				{
					canvas.width = this.size.width / Math.cos(this.angle) + this.lineWidth;
					canvas.style.left = (this.size.width - canvas.width)/2 + 'px';
					this.size.width = canvas.width;
					canvas.height = this.size.height / Math.sin(this.angle) + this.lineWidth;
					canvas.style.top = (this.size.height - canvas.height)/2 + 'px';
					this.size.height = canvas.height;
					e.insertBefore(canvas, e.firstChild);
					this.lastAngle = -this.angle;
				}
				else
				{
					if(effect.method === 'underline'
					|| effect.method === 'double-underline')
					{
						canvas.width = this.size.width + this.lineWidth;
						canvas.style.left = (this.lineWidth / 2) + 'px';
						canvas.height = (1 - Math.cos(this.underlineAngle)) * this.size.width / Math.sin(this.underlineAngle) + this.lineWidth * 1.5;
						canvas.style.top = this.size.height;
						if(effect.method === 'double-underline')
						{
							canvas.height += this.lineWidth * 2;
						}
						this.size.height = this.size.width / Math.sin(this.underlineAngle) - this.lineWidth;
						e.insertBefore(canvas, e.firstChild);
						this.lastAngle = -Math.PI / 2 - this.underlineAngle;
					}
					else
					{
						if(this.debug)
						{
							alert('It was not specified a valid method for effect ' + this.animation.effect + ' "' + effect.type + '"');
						}
						return true;
					}
				}
				if(e.style.zIndex.length === 0)
				{
					e.style.zIndex = 0;
				}
				e.style.position = "relative";
				canvas.style.zIndex = parseInt(e.style.zIndex, 10) - 1;
				canvas.style.position = 'absolute';
				canvas.style.pointerEvents = 'none';
				ML.Animation.Utility.setOpacity(canvas, this.opacity);
				this.context.lineWidth = this.lineWidth;
				this.context.lineCap = 'round';
				this.context.strokeStyle = this.strokeStyle;
				return false;
			}
			if(this.debug)
			{
				alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
			}
			return true;
		};

		this.step = function()
		{
			var p, angle;

			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			if(effect.method === 'circle')
			{
				angle = p * p * (-2 * Math.PI + this.angleGap) - this.angle;
				drawEllipse(this.context, this.size.width/2, this.size.height/2, this.size.width/2 - this.lineWidth, this.size.height/2 - this.lineWidth, this.lastAngle, angle, true);
				this.lastAngle = angle;
			}
			else
			{
				if(effect.method === 'underline')
				{
					angle = -Math.PI / 2 - (1 - p * p) * this.underlineAngle;
					drawEllipse(this.context, this.size.width + this.lineWidth / 2, this.size.height + this.lineWidth / 2, this.size.height, this.size.height, this.lastAngle, angle, false);
					this.lastAngle = angle;
				}
				else
				{
					if(effect.method === 'double-underline')
					{
						if(p <= 0.5)
						{
							angle = -Math.PI / 2 - (1 - 4 * p * p) * this.underlineAngle;
							drawEllipse(this.context, this.size.width + this.lineWidth / 2, this.size.height + this.lineWidth / 2, this.size.height, this.size.height, this.lastAngle, angle, false);
						}
						else
						{
							angle = -Math.PI / 2 - (1 - 4 * (p - 0.5) * (p - 0.5)) * this.underlineAngle;
							if(this.lastStep < 0.5)
							{
								drawEllipse(this.context, this.size.width + this.lineWidth / 2, this.size.height + this.lineWidth / 2, this.size.height, this.size.height, this.lastAngle, -Math.PI / 2, false);
								this.lastAngle = -Math.PI / 2 - this.underlineAngle;
							}
							drawEllipse(this.context, this.size.width + this.lineWidth / 2, this.size.height + this.lineWidth * 2.5, this.size.height, this.size.height, this.lastAngle, angle, false);
						}
						this.lastAngle = angle;
						this.lastStep = p;
					}
				}
			}
			return(p < 1);
		};
	},

	FadeIn: function(effect)
	{
		this.node = null;
		this.opacity = 0;

		this.start = function()
		{
			this.node = document.getElementById(effect.element);
			if(this.node)
			{
				this.opacity = 0;
				ML.Animation.Utility.setOpacity(this.node, this.opacity);
				if(effect.visibility === 'display')
				{
					this.node.style.display = 'block';
				}
				else
				{
					this.node.style.visibility = 'visible';
				}
				return false;
			}
			if(this.debug)
			{
				alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
			}
			return true;
		};

		this.step = function()
		{
			var p, opacity;

			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			opacity = Math.round(p * ML.Animation.fadeStep) / ML.Animation.fadeStep;
			if(opacity !== this.opacity)
			{
				ML.Animation.Utility.setOpacity(this.node, this.opacity = opacity);
			}
			return (p < 1);
		};
	},

	FadeOut: function(effect)
	{
		this.node = null;
		this.opacity = 0;

		this.start = function()
		{
			this.node = document.getElementById(effect.element);
			if(this.node)
			{
				this.opacity = 1.0;
				ML.Animation.Utility.setOpacity(this.node, this.opacity);
				return false;
			}
			if(this.debug)
			{
				alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
			}
			return true;
		};

		this.step = function()
		{
			var p, opacity, step;

			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			opacity = Math.round((1.0 - p) * ML.Animation.fadeStep) / ML.Animation.fadeStep;
			if(opacity !== this.opacity)
			{
				ML.Animation.Utility.setOpacity(this.node, this.opacity = opacity);
			}
			step = p < 1;
			if(!step)
			{
				if(effect.visibility === 'display')
				{
					this.node.style.display = 'none';
				}
				else
				{
					this.node.style.visibility = 'hidden';
				}
			}
			return step;
		};
	},

	Hide: function(effect)
	{
		this.start = function()
		{
			var e = document.getElementById(effect.element);
			if(e)
			{
				if(effect.visibility === 'display')
				{
					e.style.display = 'none';
				}
				else
				{
					e.node.style.visibility = 'hidden';
				}
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return true;
		};
	},

	MakeVisible: function(effect)
	{
		this.startX = 0;
		this.startY = 0;

		this.start = function()
		{
			var viewport, e = document.getElementById(effect.element);
			if(e)
			{
				viewport = ML.Animation.Utility.getWindowViewport();
				this.startX = viewport.x;
				this.startY = viewport.y;
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return false;
		};

		this.step = function()
		{
			var p, e, viewport, position, size, x, y, limit;

			e = document.getElementById(effect.element);
			viewport = ML.Animation.Utility.getWindowViewport();
			position = ML.Animation.Utility.getElementPosition(e);
			size = ML.Animation.Utility.getElementSize(e, false);
			x = viewport.x;
			limit = position.x + size.width - viewport.width;
			if(limit > x)
			{
				x = limit;
			}
			if(x > position.x)
			{
				x = position.x;
			}
			y = viewport.y;
			limit = position.y + size.height - viewport.height;
			if(limit > y)
			{
				y = limit;
			}
			if(y > position.y)
			{
				y = position.y;
			}
			if(x === viewport.x
			&& y === viewport.y)
			{
				return false;
			}
			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			x = this.startX + Math.round((x - this.startX) * p * p);
			y = this.startY + Math.round((y - this.startY) * p * p);
			window.scrollTo(x, y);
			return (p !== 1);
		};
	},

	PrependContent: function(effect)
	{
		this.start = function()
		{
			var e = document.getElementById(effect.element);
			if(e)
			{
				e.innerHTML = effect.content + e.innerHTML;
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return true;
		};
	},

	ReplaceContent: function(effect)
	{
		this.start = function()
		{
			var e = document.getElementById(effect.element);
			if(e)
			{
				e.innerHTML = effect.content;
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return true;
		};
	},

	Show: function(effect)
	{
		this.start = function()
		{
			var e = document.getElementById(effect.element);
			if(e)
			{
				if(effect.visibility === 'display')
				{
					e.style.display = 'block';
				}
				else
				{
					e.style.visibility = 'visible';
				}
			}
			else
			{
				if(this.debug)
				{
					alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
				}
			}
			return true;
		};
	},

	SlideIn: function(effect)
	{
		this.node =
		this.parent =
		this.edge = 
		this.size = 
		this.oldEdge =
		this.oldPosition = null;

		this.start = function()
		{
			var size;

			this.node = document.getElementById(effect.element);
			if(this.node)
			{
				this.parent = document.createElement('div');
				this.parent.style.overflow = 'hidden';
				this.node.style.display = 'block';
				size = ML.Animation.Utility.getElementSize(this.node, false);
				switch(effect.edge)
				{
					case 'top':
						this.edge = this.size = size.height;
						this.oldEdge = this.node.style.bottom;
						this.node.style.bottom = this.edge + 'px';
						this.parent.style.height = '0';
						break;

					case 'bottom':
						this.edge = this.size = size.height;
						this.oldEdge = this.node.style.top;
						this.node.style.top = this.edge + 'px';
						this.parent.style.height = '0';
						break;

					case 'left':
						this.edge = this.size = size.width;
						this.oldEdge = this.node.style.right;
						this.node.style.right = this.edge + 'px';
						this.parent.style.width = '0';
						break;

					case 'right':
						this.edge = this.size = size.width;
						this.oldEdge = this.node.style.left;
						this.node.style.left = this.edge + 'px';
						this.parent.style.width = '0';
						break;

					default:
						if(this.debug)
						{
							alert('Invalid ' + effect.type + ' effect edge "' + effect.edge);
							this.parent = null;
							return true;
						}
				}
				this.oldPosition = this.node.style.position;
				this.node.style.position = 'relative';
				this.node.parentNode.insertBefore(this.parent, this.node);
				this.node.parentNode.removeChild(this.node);
				this.parent.appendChild(this.node);
				return false;
			}
			if(this.debug)
			{
				alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
			}
			return true;
		};

		this.step = function()
		{
			var p, edge, step;

			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			edge = Math.round(this.size * (1 - p * p));
			if(edge !== this.edge)
			{
				this.edge = edge;
				switch(effect.edge)
				{
					case 'top':
						this.node.style.bottom = this.edge + 'px';
						this.parent.style.height = (this.size - this.edge) + 'px';
						break;

					case 'bottom':
						this.node.style.top = this.edge + 'px';
						this.parent.style.height = (this.size - this.edge) + 'px';
						break;

					case 'left':
						this.node.style.right = this.edge + 'px';
						this.parent.style.width = (this.size - this.edge) + 'px';
						break;

					case 'right':
						this.node.style.left = this.edge + 'px';
						this.parent.style.width = (this.size - this.edge) + 'px';
						break;
				}
			}
			step = (p < 1);
			if(!step)
			{
				this.parent.removeChild(this.node);
				this.parent.parentNode.insertBefore(this.node, this.parent);
				switch(effect.edge)
				{
					case 'top':
						this.node.style.bottom = this.oldEdge;
						break;

					case 'bottom':
						this.node.style.top = this.oldEdge;
						break;

					case 'left':
						this.node.style.right = this.oldEdge;
						break;

					case 'right':
						this.node.style.left = this.oldEdge;
						break;
				}
				this.node.style.position = this.oldPosition;
				this.parent.parentNode.removeChild(this.parent);
				this.parent = null;
			}
			return step;
		};
	},

	SlideOut: function(effect)
	{
		this.node =
		this.parent =
		this.edge = 
		this.size = 
		this.oldEdge =
		this.oldPosition = null;

		this.start = function()
		{
			var size;

			this.node = document.getElementById(effect.element);
			if(this.node)
			{
				this.parent = document.createElement('div');
				this.parent.style.overflow = 'hidden';
				this.node.style.display = 'block';
				size = ML.Animation.Utility.getElementSize(this.node, false);
				switch(effect.edge)
				{
					case 'top':
						this.parent.style.height = this.edge = this.size = size.height;
						this.oldEdge = this.node.style.bottom;
						this.node.style.bottom = this.edge + 'px';
						break;

					case 'bottom':
						this.parent.style.height = this.edge = this.size = size.height;
						this.oldEdge = this.node.style.top;
						this.node.style.top = this.edge + 'px';
						break;

					case 'left':
						this.parent.style.width = this.edge = this.size = size.width;
						this.oldEdge = this.node.style.right;
						this.node.style.right = this.edge + 'px';
						break;

					case 'right':
						this.parent.style.width = this.edge = this.size = size.width;
						this.oldEdge = this.node.style.left;
						this.node.style.left = this.edge + 'px';
						break;

					default:
						if(this.debug)
						{
							alert('Invalid ' + effect.type + ' effect edge "' + effect.edge);
							this.parent = null;
							return true;
						}
				}
				this.oldPosition = this.node.style.position;
				this.node.style.position = 'relative';
				this.node.parentNode.insertBefore(this.parent, this.node);
				this.node.parentNode.removeChild(this.node);
				this.parent.appendChild(this.node);
				return false;
			}
			if(this.debug)
			{
				alert('Inexisting element "' + effect.element + '" of effect ' + this.animation.effect + ' "' + effect.type + '"');
			}
			return true;
		};

		this.step = function()
		{
			var p, edge, step;

			p = (new Date().getTime() - this.startTime)/(this.duration*1000.0);
			if(p>1)
			{
				p = 1;
			}
			edge = Math.round(this.size * p * p);
			if(edge !== this.edge)
			{
				this.edge = edge;
				switch(effect.edge)
				{
					case 'top':
						this.node.style.bottom = this.edge + 'px';
						this.parent.style.height = (this.size - this.edge) + 'px';
						break;

					case 'bottom':
						this.node.style.top = this.edge + 'px';
						this.parent.style.height = (this.size - this.edge) + 'px';
						break;

					case 'left':
						this.node.style.right = this.edge + 'px';
						this.parent.style.width = (this.size - this.edge) + 'px';
						break;

					case 'right':
						this.node.style.left = this.edge + 'px';
						this.parent.style.width = (this.size - this.edge) + 'px';
						break;
				}
			}
			step = (p < 1);
			if(!step)
			{
				this.parent.removeChild(this.node);
				this.parent.parentNode.insertBefore(this.node, this.parent);
				switch(effect.edge)
				{
					case 'top':
						this.node.style.bottom = this.oldEdge;
						break;

					case 'bottom':
						this.node.style.top = this.oldEdge;
						break;

					case 'left':
						this.node.style.right = this.oldEdge;
						break;

					case 'right':
						this.node.style.left = this.oldEdge;
						break;
				}
				this.node.style.position = this.oldPosition;
				this.parent.parentNode.removeChild(this.parent);
				this.parent = null;
				this.node.style.display = 'none';
			}
			return step;
		};
	},

	Wait: function(effect)
	{
		this.start = function()
		{
			return false;
		};

		this.step = function()
		{
			return (new Date().getTime() - this.startTime < this.duration*1000.0);
		};
	}

};

ML.Animation.registerEffects = function(name, effects)
{
	if(ML.Animation.Effects[name] !== undefined)
	{
		return false;
	}
	ML.Animation.Effects[name] = effects;
	return true;
};

}

if(ML.Animation.Animate === undefined)
{
ML.Animation.animations = [];
ML.Animation.running = 0;
ML.Animation.poll = null;
ML.Animation.frameRate = 120;
ML.Animation.fadeStep = 100;
ML.Animation.defaultDuration = 1;

ML.Animation.cancelAnimation = function(animation)
{
	ML.Animation.animations[animation.animation] = null;
	if(--ML.Animation.running === 0)
	{
		clearInterval(ML.Animation.poll);
		ML.Animation.poll = null;
	}
};

ML.Animation.Animate = function()
{
	var advanceAnimation,
		stepEffects,
		startEffect;

	advanceAnimation = function(animation)
	{
		animation.running = false;
		if(++animation.effect < animation.definition.effects.length)
		{
			startEffect(animation);
		}
		else
		{
			ML.Animation.cancelAnimation(animation);
		}
	};

	stepEffects = function()
	{
		var a, animation, effect, step;

		for(a = 0; a<ML.Animation.animations.length; a++)
		{
			if(ML.Animation.animations[a] !== null
			&& ML.Animation.animations[a].running)
			{
				animation = ML.Animation.animations[a];
				effect = animation.definition.effects[animation.effect];
				step = effect.effect.step();
				if(!step)
				{
					advanceAnimation(animation);
				}
			}
		}
	};

	startEffect = function(animation)
	{
		var effect = animation.definition.effects[animation.effect],
			type = effect.type,
			advance, e, timeout;

		if(ML.Animation.Effects[type] === undefined)
		{
			if(animation.definition.debug)
			{
				alert('Unsupported animation type "' + type + '"');
			}
			advance = true;
		}
		else
		{
			e = effect.effect = new ML.Animation.Effects[type](effect);
			e.animation = animation;
			e.debug = animation.definition.debug;
			e.startTime = new Date().getTime();
			e.duration = (effect.duration === undefined ? ML.Animation.defaultDuration : effect.duration);
			advance = e.start();
		}
		if(advance)
		{
			effect.effect = null;
			advanceAnimation(animation);
		}
		else
		{
			if(ML.Animation.poll === null)
			{
				timeout = 1000 / ML.Animation.frameRate;
				ML.Animation.poll = setInterval(stepEffects, timeout < 1 ? 1 : timeout);
			}
			animation.running = true;
		}
	};

	this.addAnimation = function(animation)
	{
		var a = ML.Animation.animations.length;

		ML.Animation.animations[a] = { definition: animation, animation: a, effect: 0, running: false };
		ML.Animation.running++;
		startEffect(ML.Animation.animations[a]);
		return a;
	};
};

}
