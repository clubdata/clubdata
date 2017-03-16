/*
 *
 * @(#) $Id: html_editor.js,v 1.51 2014/09/28 02:58:16 mlemos Exp $
 *
 */

/*jslint browser: true, devel: true, plusplus: true, sloppy: true, white: true */

var ML;

if(ML === undefined)
{
	ML = {};
}

if(ML.HTMLEditor === undefined)
{
	ML.HTMLEditor = {};
}

if(ML.HTMLEditor.Editor === undefined)
{

ML.HTMLEditor.HTMLEditors = {};

ML.HTMLEditor.defaultVisualToolbar = {
	'character': [
		{
			type: 'bold'
		},
		{
			type: 'italic'
		},
		{
			type: 'underline'
		},
		{
			type: 'strikethrough'
		},
		{
			type: 'createlink'
		},
		{
			type: 'unlink'
		}
	],
	'paragraph': [
		{
			type: 'justifyleft'
		},
		{
			type: 'justifycenter'
		},
		{
			type: 'justifyright'
		},
		{
			type: 'justifyfull'
		},
		{
			type: 'space'
		},
		{
			type: 'formatblock'
		},
		{
			type: 'inserttemplate'
		}
	],
	'document': [
		{
			type: 'copy'
		},
		{
			type: 'cut'
		},
		{
			type: 'paste'
		},
		{
			type: 'delete'
		},
		{
			type: 'space'
		},
		{
			type: 'undo'
		},
		{
			type: 'redo'
		},
		{
			type: 'space'
		},
		{
			type: 'html'
		}
	]
};

ML.HTMLEditor.defaultHTMLToolbar = {
	'document': [
		{
			type: 'visual'
		}
	]
};

ML.HTMLEditor.linkEmulationStyle = 'color: #0000FF; text-decoration: underline';

ML.HTMLEditor.Editor = function()
{
	this.id = '';
	this.error = '';
	this.debug = true;
	this.editorStyle = 'background-color: #ffffff; border-style: solid; border-width: 1px; margin: 0px; border-color:  #707070 #e0e0e0 #e0e0e0 #707070';
	this.templateVariableStyle = 'border-style: dashed; border-width: 1px; margin: 1px; padding: 1px';
	this.templateMarkStyle = 'background-color: #cedee6; border-style: solid; border-width: 1px; margin: 0px; padding: 2px; border-color: #e0e0e0 #707070 #707070 #e0e0e0; font-size: 8pt; opacity: 0.75; filter:alpha(opacity=75)';
	this.menuStyle = 'background-color: #d0d0d0; border-style: solid; border-width: 1px; margin: 0px; border-color: #e0e0e0 #707070 #707070 #e0e0e0';
	this.itemStyle = 'padding: 4px; color: #000000';
	this.itemSelectStyle = 'padding: 4px; color: #ffffff; background-color: #000080';
	this.mode = 'visual';
	this.showToolbars = true;
	this.templateVariables = {};
	this.externalCSS = [];
	this.openVariable = '{';
	this.closeVariable = '}';
	this.alternativesMark = '+';

	/*
	 *  private variables
	 */
	this.editor = null;
	this.textarea = null;
	this.iframe = null;
	this.editorDocument = null;
	this.htmlEditor = null;
	this.visualEditor = null;
	this.lastMenu = '';
	this.lastMenuTime = 0;
	this.menuDelayTime = 100;
	this.lastOpenedMenu = null;

	var hasAttribute = function (element, attribute)
	{
		if(element.hasAttribute)
		{
			return(element.hasAttribute(attribute));
		}
		return element.attributes[attribute] !== undefined;
	},

	getElementsByName = function(element, name, tags)
	{
		var l = [], t, e, i;

		for(t = 0; t < tags.length; ++t)
		{
			e = element.getElementsByTagName(tags[t]);
			for(i = 0; i < e.length; ++i)
			{
				if(e[i].getAttribute('name') === name)
				{
					l[l.length] = e[i];
				}
			}
		}
		return l;
	},

	getElementBox = function (element)
	{
		var b, s, o, p, box = {},
			d = element.ownerDocument,
			win = d.defaultView || d.parentWindow;

		if(element.getBoundingClientRect)
		{
			b = element.getBoundingClientRect();
			box.x = b.left + d.body.scrollLeft;
			box.y = b.top + d.body.scrollTop;
			box.width = b.right - b.left + 1;
			box.height = b.bottom - b.top + 1;
		}
		else
		{
			if(d.getBoxObjectFor)
			{
				b = d.getBoxObjectFor(element);
				box.x = b.x;
				box.y = b.y;
				box.width = b.width;
				box.height = b.height;
				if(win.getComputedStyle)
				{
					s = win.getComputedStyle(element, null);
					o = parseInt(s.borderLeftWidth, 10) + parseInt(s.borderRightWidth, 10);
					box.x -= o;
					box.width += o;
					o = parseInt(s.borderTopWidth, 10) + parseInt(s.borderBottomWidth, 10);
					box.y -= o;
					box.height += o;
				}
			}
			else
			{
				p = element.style.position;
				element.style.position = 'relative';
				box.x = element.offsetLeft;
				box.y = element.offsetTop;
				box.width = element.offsetWidth;
				box.height = element.offsetHeight;
				element.style.position = p;
			}
		}
		return box;
	},

	getElementSize = function (element)
	{
		var box = getElementBox(element);

		return { width: box.width, height: box.height };
	},

	repositionElement = function (element, parent, frame)
	{
		var s,
			b = getElementBox(parent),
			x = b.x,
			y = b.y + b.height,
			w = parseInt(b.width, 10);

		if(!isNaN(w))
		{
			s = getElementSize(element);
			if(!isNaN(parseInt(s.width, 10)))
			{
				x += (w - parseInt(s.width, 10)) / 2;
				if(x < 0)
				{
					x = 0;
				}
			}
		}
		if(frame)
		{
			b = getElementBox(frame);
			x += b.x;
			y += b.y;
			if(typeof(frame.contentWindow.pageXOffset) === 'number')
			{
				x -= frame.contentWindow.pageXOffset;
				y -= frame.contentWindow.pageYOffset;
			}
			else
			{
				if(frame.contentWindow.document.documentElement && frame.contentWindow.document.compatMode && frame.contentWindow.document.compatMode !== "BackCompat")
				{
					x -= frame.contentWindow.document.documentElement.scrollLeft;
					y -= frame.contentWindow.document.documentElement.scrollTop;
				}
				else
				{
					x -= frame.contentWindow.document.body.scrollLeft;
					y -= frame.contentWindow.document.body.scrollTop;
				}
			}
		}
		element.style.left = x + 'px';
		element.style.top = y + 'px';
	},

	addEventListener = function(element, event, listener, capture)
	{
		if(element.addEventListener)
		{
			element.addEventListener(event, listener, capture);
		}
		else
		{
			if(element.attachEvent)
			{
				element.attachEvent('on' + event, listener);
			}
		}
	},

	replaceStrings = function(value, replace)
	{
		var v, c, p, f;

		for(v in replace)
		{
			c = '';
			p = 0;
			while(p < value.length)
			{
				f = value.indexOf(v, p);
				if(f === -1)
				{
					c += value.substring(p);
					break;
				}
				c += value.substring(p, f) + replace[v];
				p = f + v.length;
			}
			value = c;
		}
		return value;
	},

	encodeHTML = function(value)
	{
		return replaceStrings(value, {
			'&': '&amp;',
			'"': '&quot;',
			'<': '&lt;',
			'>': '&gt;'
		});
	},

	escapeHTML = function(value)
	{
		return value.replace(/<a([^>]*)>([^<]*)<\/a>/gi, '<span style="' + ML.HTMLEditor.linkEmulationStyle + '">$2</span>');
	},

	encodeString = function(value)
	{
		return "'" + replaceStrings(value, {
			"'": "\\'"
		}) + "'";
	},

	expandTemplates = function(e, value, insert)
	{
		var v, r, i, n, p, b, f, s, tv, style, a, h, t, expand, c, variables = {}, created = {};

		if(insert)
		{
			for(v in e.templateVariables)
			{
				v = e.openVariable + v + e.closeVariable;
				r = getElementsByName(e.editorDocument, v, ['div', 'span']);
				for(i = 0; i < r.length; ++i)
				{
					n = (variables[v] ? variables[v].length : 0);
					if(n === 0)
					{
						variables[v] = [];
					}
					variables[v][n] = r[i].id;
				}
			}
		}
		style = e.templateVariableStyle.length ? ' style="' + encodeHTML(e.templateVariableStyle) + '"' : '';
		v = value;
		value = '';
		p = 0;
		while(p < v.length)
		{
			b = v.indexOf(e.openVariable, p);
			if(b === -1)
			{
				value += v.substring(p);
				break;
			}
			b += e.openVariable.length;
			f = v.indexOf(e.closeVariable, b);
			if(f === -1)
			{
				value += v.substring(p);
				break;
			}
			s = v.indexOf(' ', b);
			if(s === -1 || s > f)
			{
				s = f;
			}
			tv = v.substring(b, s);
			if(e.templateVariables[tv])
			{
				if(e.templateVariables[tv].alternatives)
				{
					a = (s === f ? null : v.substring(s + 1, f));
					if(a && !e.templateVariables[tv].alternatives[a])
					{
						a = null;
					}
				}
				else
				{
					a = null;
				}
				h = (e.templateVariables[tv].inline !== undefined);
				if(h)
				{
					t = (e.templateVariables[tv].inline ? 'span' : 'div');
					i = encodeHTML(e.openVariable + tv + e.closeVariable);
					n = (variables[i] ? variables[i].length : 0);
				  expand = '<' + t + ' id="' + i + '_' + n +'" name="' + i + '"' + style + ' contentEditable="false"' + (e.templateVariables[tv].title ? ' title="' + encodeHTML(e.templateVariables[tv].title) + '"' : '') + (a ? ' data="' + encodeHTML(a) + '"' : '') + '></' + t + '>';
				}
				else
				{
					expand = (a ? e.templateVariables[tv].alternatives[a].value : e.templateVariables[tv].value);
				}
				value += v.substring(p, b - e.openVariable.length) + expand;
				p = f + e.closeVariable.length;
				if(h)
				{
					if(n === 0)
					{
						variables[i] = [];
					}
					c = (created[tv] ? created[tv].length : 0);
					if(c === 0)
					{
						created[tv] = [];
					}
					variables[i][n] = created[tv][c] = i + '_' + n;
				}
			}
			else
			{
				value += v.substring(p, b);
				p = b;
			}
		}
		return { value: value, created: created };
	},

	handleMenus = function(e)
	{
		return function(event)
		{
			var id, parent, now, menu;

			if(event.target)
			{
				parent = event.target;
				id = parent.id;
			}
			else
			{
				if(event.srcElement)
				{
					parent = event.srcElement;
					id = parent.id;
				}
				else
				{
					return;
				}
			}
			if(id.length === 0 || !parent)
			{
				return;
			}
			now = (new Date()).getTime();
			if(e.lastMenu !== id || now - e.lastMenuTime > e.menuDelayTime)
			{
				menu = e.visualEditor.ownerDocument.getElementById(id + '_menu');
				if(e.toggleMenu(menu, parent, e.iframe))
				{
					menu.setAttribute('data', parent.parentNode.id);
				}
				e.lastMenu = id;
				e.lastMenuTime = now;
			}
			if(event)
			{
				if(event.preventDefault)
				{
					event.preventDefault();
				}
				event.cancelBubble = true;
				event.returnValue = false;
			}
		};
	},

	convertValue = function(e, toEditor)
	{
		var value, v, n, r, t, a, replace;

		if(toEditor)
		{
			value = expandTemplates(e, e.textarea.value, false).value;
			e.editorDocument.body.innerHTML = value;
			return value;
		}
		t = document.getElementById(e.id + '_temporary');
		t.innerHTML = e.editorDocument.body.innerHTML;
		for(v in e.templateVariables)
		{
			if(e.templateVariables[v].inline !== undefined)
			{
				r = getElementsByName(t, e.openVariable + v + e.closeVariable, ['div', 'span']);
				for(n = 0; n < r.length; ++n)
				{
					r[n].parentNode.replaceChild(document.createTextNode(e.openVariable + v + (hasAttribute(r[n], 'data') ? ' ' + r[n].getAttribute('data') : '') + e.closeVariable), r[n]);
				}
			}
		}
		value = t.innerHTML;
		t.innerHTML = '';
		for(v in e.templateVariables)
		{
			if(e.templateVariables[v].inline === undefined)
			{
				replace = {};
				replace[e.templateVariables[v].value] = e.openVariable + v + e.closeVariable;
				if(e.templateVariables[v].alternatives)
				{
					for(a in e.templateVariables[v].alternatives)
					{
						replace[e.templateVariables[v].alternatives[a].value] = e.openVariable + v + ' ' + a + e.closeVariable;
					}
				}
				value = replaceStrings(value, replace);
			}
		}
		return value;
	},

	renderToolbar = function(e, toolbars)
	{
		var t, b, type, setup, action, o, oo, menu, comma, id, v, content, title, render = '<table style="display: ' + (e.showToolbars ? 'block' : 'none') + '">';

		setup = 'var e = ML.HTMLEditor.HTMLEditors[\'' + e.id + '\']; ';
		for(t in toolbars)
		{
			render += '<tr><td>';
			for(b = 0; b < toolbars[t].length; ++b)
			{
				type = toolbars[t][b].type;
				switch(type)
				{
					case 'bold':
					case 'italic':
					case 'underline':
					case 'strikethrough':
					case 'createlink':
					case 'unlink':
					case 'copy':
					case 'cut':
					case 'paste':
					case 'delete':
					case 'undo':
					case 'redo':
					case 'html':
					case 'visual':
					case 'justifyleft':
					case 'justifycenter':
					case 'justifyright':
					case 'justifyfull':
						action = setup + 'if(!e.execCommand("' +  type + '", true) && !e.debug) { alert("Could not execute the ' + type + ' command in this browser.") }';
						switch(type)
						{
							case 'bold':
								content = '<span style="font-weight: bold">B</span>';
								title = 'Bold';
								break;
							case 'italic':
								content = '<span style="font-style: italic">I</span>';
								title = 'Italic';
								break;
							case 'underline':
								content = '<span style="text-decoration: underline">U</span>';
								title = 'Underline';
								break;
							case 'strikethrough':
								content = '<span style="text-decoration: line-through">S</span>';
								title = 'Strike-through';
								break;
							case 'createlink':
								content = '<span style="text-decoration: underline; color: #0000ff">www</span>';
								title = 'Create link';
								action = 'var url = prompt("Link URL:", "http://www."); if(url === null || url === "") return false; ' + setup + 'if(!e.execCommand("' +  type + '", url) && !e.debug) { alert("Could not execute the ' +  type + ' command in this browser.") }';
								break;
							case 'unlink':
								content = '<span style="text-decoration: underline line-through; color: #0000ff">www</span>';
								title = 'Remove link';
								break;
							case 'copy':
								content = 'Copy';
								title = 'Copy selected';
								break;
							case 'cut':
								content = 'Cut';
								title = 'Cut selected';
								break;
							case 'paste':
								content = 'Paste';
								title = 'Paste copied';
								break;
							case 'delete':
								content = 'Delete';
								title = 'Delete selected';
								break;
							case 'undo':
								content = 'Undo';
								title = 'Undo';
								break;
							case 'redo':
								content = 'Redo';
								title = 'Redo';
								break;
							case 'justifyleft':
								content = 'Left';
								title = 'Justify left';
								break;
							case 'justifycenter':
								content = 'Center';
								title = 'Justify center';
								break;
							case 'justifyright':
								content = 'Right';
								title = 'Justify right';
								break;
							case 'justifyfull':
								content = 'Full';
								title = 'Justify full';
								break;
							case 'visual':
								content = 'Visual';
								title = 'Edit visually';
								action = setup + 'e.setEditMode("visual");';
								break;
							case 'html':
								content = 'HTML';
								title = 'Edit HTML';
								action = setup + 'e.setEditMode("html");';
								break;
							default:
								if(e.debug)
								{
									alert('toolbar element of type ' +  type + ' is not implemented');
								}
								action = '';
								content = title = type;
								break;
						}
						if(action.length)
						{
							render += '<button onclick="' + encodeHTML(action) + ' return false" title="' + encodeHTML(title) + '">' + content + '</button>';
						}
						break;

					case 'justify':
					case 'inserttemplate':
					case 'formatblock':
						switch(type)
						{
							case 'justify':
								menu = 'Justify';
								o = {
									'justifyleft': 'Left',
									'justifycenter': 'Center',
									'justifyright': 'Right',
									'justifyfull': 'Full'
								};
								action = 'if(!e.execCommand({item}, true) && !e.debug) { alert("Could not execute the justify command in this browser.") }';
								break;

							case 'inserttemplate':
								menu = 'Insert template';
								o = {};
								if(e.templateVariables.length === 0)
								{
									break;
								}
								comma = '';
								for(v in e.templateVariables)
								{
									if(e.templateVariables[v].inline !== undefined)
									{
										o[e.openVariable + v + e.closeVariable] = (e.templateVariables[v].title || v);
									}
									comma = ', ';
								}
								action = 'if(!e.execCommand("inserthtml", {item}) && !e.debug) { alert("Could not execute the inserthtml command in this browser.") }; e.loadTemplates();';
								break;

							case 'formatblock':
								menu = 'Format block';
								o = {
									'p': '<p style="margin: 0px">Paragraph</p>',
									'pre': '<pre style="margin: 0px">Preformatted</pre>',
									'address': '<address style="margin: 0px">Address</address>',
									'h1': '<h1 style="margin: 0px">Heading 1</h1>',
									'h2': '<h2 style="margin: 0px">Heading 2</h2>',
									'h3': '<h3 style="margin: 0px">Heading 3</h3>',
									'h4': '<h4 style="margin: 0px">Heading 4</h4>',
									'h5': '<h5 style="margin: 0px">Heading 5</h5>',
									'h6': '<h6 style="margin: 0px">Heading 6</h6>'
								};
								action = 'if(!e.execCommand("formatblock", {item} ) && !e.debug) { alert("Could not execute the formatblock command in this browser.") }';
								break;
						}
						id = e.id + '_' + type;
						oo = '';
						for(v in o)
						{
							oo += '<div style="' +  encodeHTML(e.itemStyle) + '" onmouseover="' + encodeHTML('var s = ' + encodeString(e.itemSelectStyle) + '; if(this.currentStyle) { this.style.cssText = s } else { this.setAttribute(\'style\', s) }') + '" onmouseout="' + encodeHTML('var s = ' + encodeString(e.itemStyle) + '; if(this.currentStyle) { this.style.cssText = s } else { this.setAttribute(\'style\', s) }') + '" onmousedown="' + setup + encodeHTML(replaceStrings(action, { '{item}': encodeString(v) })) + '; e.hideMenu(this.ownerDocument.getElementById(' + encodeString(id) + ')); return false">' + o[v] + '</div>';
						}
						render += '<div id="' + id + '" style="' + encodeHTML(e.menuStyle) + '; position: absolute; visibility: hidden; white-space: nowrap">' + oo + '</div><button onkeydown="if(event.keyCode === 27) { ' + setup + 'e.hideMenu(this.ownerDocument.getElementById(' + encodeString(id) + ')); return false }" onclick="' + setup + 'e.toggleMenu(this.ownerDocument.getElementById(' + encodeString(id) + '), this, null); return false" title="' + encodeHTML(menu) + '">' + menu + '</button>';
						break;

					case 'separator':
						render += (toolbars[t][b].content || ' | ');
						break;

					case 'space':
						render += (toolbars[t][b].content || '&nbsp;');
						break;

					default:
						if(e.debug)
						{
							alert('toolbar element of type ' +  type + ' is not implemented');
						}
						break;
				}
			}
			render += '</td></tr>';
		}
		render += '</table>';
		return render;
	};

	this.formatMark = function(variable, alternative)
	{
		var e = this;

		return '<span style="float: left; ' + encodeHTML(e.templateMarkStyle) + '">' + encodeHTML(e.openVariable + variable + (alternative ? ' ' + alternative : '') + e.closeVariable + (e.templateVariables[variable].alternatives ? ' ' + e.alternativesMark : '')) + '</span><br style="clear: both">' + escapeHTML(alternative ? e.templateVariables[variable].alternatives[alternative].preview : e.templateVariables[variable].preview);
	};

	this.changeMark = function(mark, variable, alternative)
	{
		var e = this, s, i = e.editorDocument.createElement('button');

		i.setAttribute('id', e.id + '_' + variable);
		s = (e.templateVariables[variable].inline ? '' : 'display: block; width: 100%; text-align: left; ') + 'background-color: inherit; border-style: none; border-width: 0px; padding: 0px; font-family: inherit; font-size: inherit; color: inherit';
		if(i.currentStyle)
		{
			i.style.cssText = s;
		}
		else
		{
			i.setAttribute('style', s);
		}
		i.setAttribute('contentEditable', 'false');
		i.innerHTML = e.formatMark(variable, alternative);
		mark.innerHTML = '';
		mark.appendChild(i);
	};

	this.setAlternative = function(mark, variable, alternative)
	{
		var e = this, p;

		p = e.editorDocument.getElementById(mark);
		if(alternative)
		{
			p.setAttribute('data', alternative);
		}
		else
		{
			p.setAttribute('data', '');
			p.removeAttribute('data');
		}
		e.changeMark(p, variable, alternative);
	};

	this.loadTemplates = function()
	{
		var v, r, n, i, s, o, a, oo, id, 
			e = this, setup = 'var e = ML.HTMLEditor.HTMLEditors[\'' + e.id + '\']; ';

		for(v in e.templateVariables)
		{
			r = getElementsByName(e.editorDocument, e.openVariable + v + e.closeVariable, [ 'div', 'span' ]);
			for(n = 0; n < r.length; ++n)
			{
				id = e.id + '_' + v;
				r[n].innerHTML = '';
				if(e.templateVariables[v].alternatives)
				{
					i = e.visualEditor.ownerDocument.createElement('div');
					i.setAttribute('id', id + '_menu');
					e.visualEditor.appendChild(i);
					s = e.menuStyle + '; position: absolute; visibility: hidden';
					if(i.currentStyle)
					{
						i.style.cssText = s;
					}
					else
					{
						i.setAttribute('style', s);
					}
					o = {};
					o[v] = e.templateVariables[v].title;
					for(a in e.templateVariables[v].alternatives)
					{
						o[a] = e.templateVariables[v].alternatives[a].title;
					}
					oo = '';
					for(a in o)
					{
						oo += '<div style="' +  encodeHTML(e.itemStyle) + '" onmouseover="' + encodeHTML('var s = ' + encodeString(e.itemSelectStyle) + '; if(this.currentStyle) { this.style.cssText = s } else { this.setAttribute(\'style\', s) }') + '" onmouseout="' + encodeHTML('var s = ' + encodeString(e.itemStyle) + '; if(this.currentStyle) { this.style.cssText = s } else { this.setAttribute(\'style\', s) }') + '" onmousedown="' + setup + 'e.setAlternative(this.ownerDocument.getElementById(' + encodeString(id + '_menu') + ').getAttribute(\'data\'), ' + encodeHTML(encodeString(v) + ', ' + (oo.length ? encodeString(a) : 'null')) +'); e.hideMenu(this.ownerDocument.getElementById(' + encodeString(id + '_menu') + ')); return false">' + o[a] + '</div>';
					}
					i.innerHTML = oo;
				}
				e.changeMark(r[n], v, (e.templateVariables[v].alternatives && hasAttribute(r[n],'data')) ? r[n].getAttribute('data') : null);
				if(e.templateVariables[v].alternatives)
				{
					addEventListener(r[n], 'click', handleMenus(e), true);
				}
			}
		}
	};

	this.hideMenu = function(menu)
	{
		if(menu)
		{
			menu.style.visibility = 'hidden';
			this.lastOpenedMenu = null;
		}
	};

	this.showMenu = function(menu, parent, frame)
	{
		if(menu)
		{
			if(this.lastOpenedMenu && this.lastOpenedMenu.id !== menu.id)
			{
				this.hideMenu(this.lastOpenedMenu);
			}
			repositionElement(menu, parent, frame);
			menu.style.visibility = 'visible';
			this.lastOpenedMenu = menu;
			return true;
		}
		return false;
	};

	this.toggleMenu = function(menu, parent, frame)
	{
		if(menu)
		{
			if(menu.style.visibility === 'visible')
			{
				this.hideMenu(menu);
			}
			else
			{
				return this.showMenu(menu, parent, frame);
			}
		}
		return false;
	};

	this.synchronize = function()
	{
		var e = this, converted;

		converted = convertValue(e, false);
		if(converted !== e.textarea.value)
		{
			if(e.mode === 'visual')
			{
				e.textarea.value = converted;
				if(typeof(e.textarea.onchange) === 'function')
				{
					e.textarea.onchange();
				}
			}
			else
			{
				convertValue(e, true);
			}
		}
	};

	this.setError = function(error)
	{
		this.error = error;
		if(this.debug)
		{
			alert(error);
		}
		return false;
	};

	this.insertEditor = function(editor, textarea)
	{
		var l, e = this, editor_document, html_editor, visual_editor, c, html_size, visual_size, size, i;

		l = document.getElementById(editor);
		if(!l)
		{
			return e.setError('editor block "' + editor + '" was not found in this document.');
		}
		e.id = textarea.id;
		editor_document =  editor + '_iframe';
		html_editor = editor + '_html';
		visual_editor = editor + '_visual';
		l.innerHTML = '<div id="' + encodeHTML(html_editor) + '">' + renderToolbar(e, ML.HTMLEditor.defaultHTMLToolbar) + '<div><textarea id="' + encodeHTML(textarea.id) + '" name="' + encodeHTML(textarea.name) + (textarea.rows ? '" rows="' + textarea.rows : '') + (textarea.cols ? '" cols="' + textarea.cols : '') + (textarea.className ? '" class="' + encodeHTML(textarea.className) : '') + (textarea.style ? '" style="' + encodeHTML(textarea.style) : '') + '">' + (textarea.value ? encodeHTML(textarea.value) : '') + '</textarea></div></div><div id="' + encodeHTML(visual_editor) + '">' + renderToolbar(e, ML.HTMLEditor.defaultVisualToolbar) + '<div><iframe id="' + encodeHTML(editor_document) + '" src="javascript:;" frameborder="0" marginwidth="0" marginheight="0" style="' + encodeHTML(textarea.style || e.editorStyle) + '"></iframe></div></div><div id="' + e.id + '_temporary" style="display: none"></div>';
		e.htmlEditor = document.getElementById(html_editor);
		if(!e.htmlEditor)
		{
			return e.setError('could not create the HTML editor section');
		}
		e.visualEditor = document.getElementById(visual_editor);
		if(!e.visualEditor)
		{
			return e.setError('could not create the visual editor section');
		}
		e.textarea = document.getElementById(textarea.id);
		if(!e.textarea)
		{
			return e.setError('could not create the editor textarea');
		}
		l = e.iframe = document.getElementById(editor_document);
		if(!l)
		{
			return e.setError('could not create the editor iframe');
		}
		if(!l.contentWindow || !l.contentWindow.document)
		{
			return e.setError('could not access the editor iframe');
		}
		e.editorDocument = l.contentWindow.document;
		e.editorDocument.open();
		e.editorDocument.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><title>Editor</title>');
		if(e.externalCSS.length)
		{
			for(c = 0; c < e.externalCSS.length; ++c)
			{
				e.editorDocument.write('<link rel="stylesheet" type="text/css" href="' + encodeHTML(e.externalCSS[c]) + '">');
			}
		}
		e.editorDocument.write('</head><body></body></html>');
		e.editorDocument.close();
		html_size = getElementSize(e.htmlEditor);
		visual_size = getElementSize(e.visualEditor);
		size = getElementSize(e.textarea);
		i = getElementSize(e.iframe);
		l.style.width = size.width + 'px';
		l.style.height = (i.height + html_size.height - visual_size.height) + 'px';
		if(e.mode === 'visual')
		{
			e.htmlEditor.style.display = 'none';
		}
		else
		{
			e.visualEditor.style.display = 'none';
		}
		convertValue(e, true);
		if(typeof(e.editorDocument.designMode) === 'string' && e.editorDocument.designMode === 'off')
		{
			e.editorDocument.designMode = 'on';
		}
		else
		{
			if(e.editorDocument.body.contentEditable !== undefined)
			{
				e.editorDocument.body.contentEditable = true;
			}
			else
			{
				return e.setError('HTML editing in this browser is not yet supported');
			}
		}
		addEventListener(e.textarea, 'change', function()
			{
				e.synchronize();
			}, false
		);
		addEventListener(e.editorDocument.body, 'blur', function()
			{
				e.synchronize();
			}, false
		);
		ML.HTMLEditor.HTMLEditors[textarea.id] = e;
		e.loadTemplates();
		return true;
	};

	this.execCommand = function(command, argument)
	{
		var e = this, enabled, expanded, r;

		if(e.editorDocument === null)
		{
			return e.setError('the HTML editor elements are not yet setup');
		}
		try
		{
			switch(command)
			{
				case 'inserthtml':
					try
					{
						enabled = e.editorDocument.queryCommandEnabled(command);
					}
					catch(exception)
					{
						enabled = false;
					}
					expanded = expandTemplates(e, argument, true);
					argument = expanded.value;
					break;
				default:
					enabled = true;
			}
			if(enabled)
			{
				e.editorDocument.execCommand(command, false, argument);
			}
			switch(command)
			{
				case 'copy':
				case 'cut':
				case 'paste':
					if(!e.editorDocument.queryCommandSupported(command))
					{
						throw('Not supported');
					}
					break;
				case 'inserthtml':
					if(!enabled)
					{
						e.editorDocument.body.focus();
						r = e.editorDocument.selection.createRange();
						r.pasteHTML(argument);
					}
/*
					for(var v in expanded.created)
					{
						if(e.templateVariables[v].alternatives)
						{
							for(var i = 0; i < expanded.created[v].length; ++i)
							{
								addEventListener(e.editorDocument.getElementById(expanded.created[v][i]), 'click', handleMenus(e), true);
							}
						}
					}
*/
					break;
			}
		}
		catch(exception)
		{
			return e.setError(command + ' command is not allowed in this browser: ' + exception.message);
		}
		return true;
	};

	this.setEditMode = function(mode)
	{
		switch(mode)
		{
			case 'visual':
				this.textarea.blur();
				this.htmlEditor.style.display = 'none';
				this.synchronize();
				this.visualEditor.style.display = 'block';
				this.loadTemplates();
				this.editorDocument.body.focus();
				break;

			case 'html':
				this.editorDocument.body.blur();
				this.visualEditor.style.display = 'none';
				this.synchronize();
				this.htmlEditor.style.display = 'block';
				this.textarea.focus();
				break;

			default:
				return this.setError(mode + ' is not valid edit mode');
		}
		this.mode = mode;
		return true;
	};

	this.setValue = function(value)
	{
		var e = this;

		e.textarea.value = value;
		convertValue(e, true);
	};
};

}
