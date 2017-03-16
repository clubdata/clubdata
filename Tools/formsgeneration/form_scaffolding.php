<?php
/*
 *
 * @(#) $Id: form_scaffolding.php,v 1.71 2017/01/28 10:24:57 mlemos Exp $
 *
 */

class form_scaffolding_class extends form_custom_class
{
	var $server_validate = 0;
	var $format = '<h2 align="center">{result}</h2><h2 align="center" id="{message_id}">{message}</h2><div align="center">{toppagination}</div><div align="center">{listingprefix}{listing}{listingsuffix}</div><div align="center">{bottompagination}</div><div><div id="{view_id}">{view}</div><div id="{error_id}">{errormessage}</div>{formheader}{form}{formfooter}<div align="center">{returnlinkprefix}{returnlink}{returnlinksuffix}</div></div>';
	var $error_message_format = '<div style="text-align: center; font-weight: bold">{errormessage}</div>';
	var $form_header = '';
	var $form_footer = '';
	var $page_entries = 10;
	var $total_entries = 0;
	var $page = 1;
	var $listing_message = 'All entries';
	var $no_entries_message = 'There are no entries to display.';
	var $viewing_message = 'Viewing entry';
	var $create_message = 'Create a new entry';
	var $create_canceled_message = 'Creating a new entry was canceled.';
	var $created_message = 'A new entry was created successfully.';
	var $update_message = 'Update this entry';
	var $update_canceled_message = 'Updating an entry was canceled.';
	var $updated_message = 'The entry was updated successfully.';
	var $delete_message = 'Are you sure you want to delete this entry?';
	var $delete_canceled_message = 'Deleting the entry was canceled.';
	var $deleted_message = 'The entry was deleted successfully.';
	var $create_preview_message = 'New entry preview';
	var $update_preview_message = 'Updated entry preview';
	var $append_message = '';
	var $preview_input;
	var $preview_label = 'Preview';
	var $save_input;
	var $save_label = 'Save';
	var $submit_input;
	var $submit_label = 'Submit';
	var $cancel_input;
	var $cancel_label = 'Cancel';
	var $delete_input;
	var $delete_label = 'Delete';
	var $delete_cancel_input;
	var $invalid_mark = '[X]';
	var $fields = array();
	var $delete_fields = array();
	var $columns = array();
	var $rows = array();
	var $row_style_column = '';
	var $row_class_column = '';
	var $listing_id = '';
	var $listing_prefix = '';
	var $listing_suffix = '';
	var $listing_style = '';
	var $listing_class = '';
	var $listing_spacing = 0;
	var $listing_padding = 2;
	var $highlight_row_color = '';
	var $highlight_row_class = '';
	var $odd_row_color = '';
	var $odd_row_class = '';
	var $even_row_color = '';
	var $even_row_class = '';
	var $create = 1;
	var $read = 1;
	var $update = 1;
	var $delete = 1;
	var $view = 0;
	var $save = 0;
	var $preview = 0;
	var $update_label = 'Update';
	var $view_label = 'View';
	var $id_column;
	var $entry_output = '';
	var $return_link_prefix = '';
	var $return_link_suffix = '';
	var $custom_parameters = array();
	var $external_parameters = array();
	var $field_layout_properties = array();
	var $field_data_properties = array();

	var $create_parameter;
	var $page_parameter;
	var $update_parameter;
	var $delete_parameter;
	var $state = 'listing';
	var $next_state = '';
	var $entry;
	var $ajax_input = '';
	var $action_input = '';
	var $view_id = '';
	var $message_id = '';
	var $error_id = '';
	var $layout = '';

	var $pagination_data = '{page}';
	var $pagination_tab_limit = 10;
	var $ajax_response = 0;
	var $ajax_message = array();
	var $loaded = 0;
	var $update_inputs = array();
	var $target_input = '';
	var $entry_field_names = array();

	Function GeneratePagination(&$form)
	{
		$e = $this->page_entries;
		$t = $this->total_entries;
		if($e == 0)
		{
			$pages = array(
				array(
					'first'=>1,
					'last'=>$t,
					'page'=>1,
					'data'=>str_replace('{first}', 1, str_replace('{last}', $t, str_replace('{page}', 1, $this->pagination_data)))
				)
			);
			return $pages;
		}
		$l = $this->pagination_tab_limit;
		if(intval(($t + $e - 1) / $e) > $l)
		{
			$start = (max($this->page - intval($l / 2), 1) - 1) * $e + 1;
			$end = $start + $l * $e - 1;
			if($end > $t)
			{
				$start = max((intval($t / $e) - $l + 1) * $e + 1, 1);
				$end = $t;
			}
		}
		else
		{
			$start = 1;
			$end = $t;
		}
		for($pages = array(), $entry = $start; $entry <= $end; $entry += $e)
		{
			$page = ($entry - 1) / $e + 1;
			$p = count($pages);
			$last = min($t, $entry + $e - 1);
			$pages[$p]=array(
				'first'=>$entry,
				'last'=>$last,
				'page'=>$page,
				'data'=>str_replace('{first}', $entry, str_replace('{last}', $last, str_replace('{page}', $page, $this->pagination_data)))
			);
			if($page != $this->page)
			{
				$pages[$p]['url'] = $this->AddActionParameter($form, $this->page_parameter, $page);
				$pages[$p]['tab'] = 1;
			}
		}
		return($pages);
	}

	Function DisplayPagination($form, $pages, $position)
	{
		$o = '';
		$tp = count($pages);
		$page = '<span style="margin: 2px"><a href="{url}">{data}</a></span>';
		$tab = '<span style="margin: 2px; font-weight: bold">{data}</span>';
		for($p = 0; $p < $tp; ++$p)
		{
			$u = IsSet($pages[$p]['url']);
			$o .= str_replace('{data}', $pages[$p]['data'], str_replace('{url}', $u ? HtmlSpecialChars($pages[$p]['url']) : '', $u ? $page : $tab));
		}
		return($o);
	}

	Function HasEntries(&$has_entries)
	{
		$has_entries = (count($this->rows)!=0);
		return('');
	}

	Function AddActionParameters(&$form, $parameters)
	{
		$url = $form->ACTION;
		$tp = count($parameters);
		for(Reset($parameters), $p = 0; $p < $tp; Next($parameters), ++$p)
		{
			$parameter = Key($parameters);
			$url .= (GetType(strpos($url, '?')) == 'integer' ? (strlen($url) > 1 ? '&' : '') : '?').UrlEncode($parameter).'='.UrlEncode($parameters[$parameter]);
		}
		$p = $this->custom_parameters;
		$tc = count($p);
		for(Reset($p), $c = 0; $c < $tc; Next($p), ++$c)
		{
			$k = Key($p);
			$url .= '&'.UrlEncode($k).'='.UrlEncode($p[$k]);
		}
		$p = $this->external_parameters;
		$tc = count($p);
		for(Reset($p), $c = 0; $c < $tc; Next($p), ++$c)
		{
			$k = Key($p);
			$this->external_parameters[$k] = $form->GetInputValue($k);
			$url .= '&'.UrlEncode($k).'='.UrlEncode($this->external_parameters[$k]);
		}
		return($url);
	}

	Function AddActionParameter(&$form, $parameter, $value)
	{
		return($this->AddActionParameters($form, array($parameter => $value)));
	}

	/*
	 * $c - column
	 * $r - row
	 *
	 */

	Function GetListingData($c, $r, $d, $default, &$template)
	{
		$ro = $this->rows;
		$co = $this->columns;
		UnSet($data);
		if(($i = IsSet($co[$c]['InputColumn']))
		|| IsSet($co[$c]['LabelColumn']))
		{
			$column = ($i ? $co[$c]['InputColumn'] : $co[$c]['LabelColumn']);
			if(IsSet($ro[$r][$column]))
			{
				$input = $ro[$r][$column];
				$mark = 'i_'.$input;
				$template = array(
					'format'=>'{'.$mark.'}',
					'marks'=>array(
						($i ? 'input' : 'label')=>array(
							$mark=>$input
						)
					)
				);
				return;
			}
		}
		if(IsSet($co[$c]['FormatMap'])
		&& IsSet($ro[$r][$d]))
		{
			$map = $co[$c]['FormatMap'];
			$v = $ro[$r][$d];
			if(IsSet($map[$v]))
				$format = $map[$v];
		}
		if(!IsSet($format)
		&& IsSet($co[$c]['Format']))
			$format = $co[$c]['Format'];
		if(IsSet($format)
		&& (!IsSet($co[$c]['MapNull'])
		|| IsSet($ro[$r][$d])))
		{
			$data = $format;
			$f = (IsSet($co[$c]['FormatParameters']) ? $co[$c]['FormatParameters'] : array());
			$tf = count($f);
			$inputs = $labels = array();
			$ms = strlen($this->mark_start);
			$me = strlen($this->mark_end);
			for(Reset($f), $p = 0; $p < $tf; Next($f), ++$p)
			{
				$k = Key($f);
				if(($i = IsSet($f[$k]['InputColumn'])
				&& IsSet($ro[$r][$column = $f[$k]['InputColumn']]))
				|| (IsSet($f[$k]['LabelColumn'])
				&& IsSet($ro[$r][$column = $f[$k]['LabelColumn']])))
				{
					$mark = $k;
					if(substr($mark, 0, $ms) != $this->mark_start)
						$mark = $this->mark_start.$mark;
					if(substr($mark, -$me, $me) != $this->mark_end)
						$mark .= $this->mark_end;
					if(strcmp($mark, $k))
						$data = str_replace($k, $mark, $data);
					$mark = substr($mark, $ms, -$me);
					if($i)
						$inputs[$mark] = $ro[$r][$column];
					else
						$labels[$mark] = $ro[$r][$column];
				}
				else
				{
					$d = (IsSet($f[$k]['Column']) ? $f[$k]['Column'] : $c);
					if(IsSet($ro[$r][$d]))
						$v = $ro[$r][$d];
					else
						$v = (IsSet($f[$k]['MapNull']) ? $f[$k]['MapNull'] : '');
					if(IsSet($f[$k]['Map'][$v]))
						$v = $f[$k]['Map'][$v];
					if(IsSet($f[$k]['Replace']))
					{
						$re = $f[$k]['Replace'];
						$tre = count($re);
						for(Reset($re), $e = 0; $e < $tre; Next($re), ++$e)
						{
							$ke = Key($re);
							$v = preg_replace('/'.str_replace('/', '\\/', $ke).'/', $re[$ke], $v);
						}
					}
					if(IsSet($f[$k]['HTML']))
						$v = HtmlSpecialChars($v);
					$data = str_replace($k, $v, $data);
				}
			}
			if(count($inputs)
			|| count($labels))
			{
				$template = array(
					'format'=>$data,
					'marks'=>array(
						'input'=>$inputs,
						'label'=>$labels
					)
				);
				return;
			}
		}
		else
		{
			if(IsSet($ro[$r][$d]))
				$data = $ro[$r][$d];
			elseif(IsSet($co[$c]['MapNull']))
				$data = $co[$c]['MapNull'];
			else
				$data = $default;
			if(IsSet($co[$c]['Map'][$data]))
				$data = $co[$c]['Map'][$data];
			if(IsSet($co[$c]['Replace']))
			{
				$re = $co[$c]['Replace'];
				$tre = count($re);
				for(Reset($re), $e = 0; $e < $tre; Next($re), ++$e)
				{
					$ke = Key($re);
					$data = preg_replace('/'.str_replace('/', '\\/', $ke).'/', $re[$ke], $data);
				}
			}
			if(IsSet($co[$c]['HTML']))
				$data = HtmlSpecialChars($data);
		}
		$template = array(
			'format'=>'{data}',
			'marks'=>array(
				'data'=>array(
					'data'=>$data
				)
			)
		);
		return '';
	}

	Function AppendTemplateData(&$template, $data, &$last_data)
	{
		if(strlen($data) == 0)
			return;
		$block = count($template['marks']['data']);
		if(strlen($template['format']) == 0)
			$template['format'] = '{'.($mark = 'd0').'}';
		else
		{
			if($last_data)
				$mark = 'd'.($block - 1);
			else
			{
				$mark = 'd'.$block;
				$template['format'] .= '{'.$mark.'}';
			}
		}
		if($last_data)
			$template['marks']['data'][$mark] .= $data;
		else
			$template['marks']['data'][$mark] = $data;
		$last_data = 1;
	}

	Function AppendTemplate(&$template, $t, &$last_data)
	{
		if(!IsSet($t['marks']['input'])
		&& !IsSet($t['marks']['label']))
		{
			if(IsSet($t['marks']['data']))
				$this->AppendTemplateData($template, $t['marks']['data'][substr($t['format'], 1, -1)], $last_data);
			return;
		}
		$count = count($template['marks']['template']);
		$template['format'] .= '{'.($mark = 't'.$count).'}';
		$template['marks']['template'][$mark] = $t;
		$last_data = 0;
	}

	Function GetListing(&$form, &$template)
	{
		$template = array(
			'format' => '',
			'marks' => array(
				'data'=>array(),
				'template'=>array()
			)
		);
		$last_data = 0;
		$ro = $this->rows;
		$co = $this->columns;
		$tc = count($co);
		$listing = '<table'.(strlen($this->listing_id) ? ' id="'.HtmlSpecialChars($this->listing_id).'"' : '').(strlen($this->listing_class) ? ' class="'.HtmlSpecialChars($this->listing_class).'"' : '').(strlen($this->listing_style) ? ' style="'.HtmlSpecialChars($this->listing_style).'"' : '').(IsSet($this->listing_spacing) ? ' cellspacing="'.HtmlSpecialChars($this->listing_spacing).'"' : '').(IsSet($this->listing_padding) ? ' cellpadding="'.HtmlSpecialChars($this->listing_padding).'"' : '').">\n<tr>\n";
		for($style = array(), $c = 0; $c < $tc; ++$c)
		{
			$style[$c] = (IsSet($co[$c]['Style']) ? ' style="'.HtmlSpecialChars($co[$c]['Style']).'"' : '').(IsSet($co[$c]['Class']) ? ' class="'.HtmlSpecialChars($co[$c]['Class']).'"' : '');
			if(IsSet($co[$c]['HTML'])
			&& !$co[$c]['HTML'])
				Unset($co[$c]['HTML']);
			$listing .= '<th'.$style[$c].'>';
			if(IsSet($co[$c]['Header']))
				$listing .= $co[$c]['Header'];
			$listing .= "</th>\n";
		}
		$listing .= "</tr>\n";
		$highlight = ((strlen($this->highlight_row_color) || strlen($this->highlight_row_class)) ? ' onmouseover="'.HtmlSpecialChars((strlen($this->highlight_row_class) ? 'this.className='.$form->EncodeJavascriptString($this->highlight_row_class).';' : '').(strlen($this->highlight_row_color) ? 'this.style.backgroundColor='.$form->EncodeJavascriptString($this->highlight_row_color).';' : '')).'"' : '');
		$lowlight = ((strlen($this->odd_row_color) || strlen($this->odd_row_class)) ? ' onmouseout="'.HtmlSpecialChars((strlen($this->odd_row_class) ? 'this.className='.$form->EncodeJavascriptString($this->odd_row_class).';' : '').(strlen($this->odd_row_color) ? 'this.style.backgroundColor='.$form->EncodeJavascriptString($this->odd_row_color).';' : '')).'"' : '');
		$odd_rows = (strlen($this->odd_row_class) ? ' class="'.$this->odd_row_class.'"' : '').(strlen($this->odd_row_color) ? ' style="background-color: '.$this->odd_row_color.'"' : '').$highlight.$lowlight;
		$lowlight = ((strlen($this->even_row_color) || strlen($this->even_row_class)) ? ' onmouseout="'.HtmlSpecialChars((strlen($this->even_row_class) ? 'this.className='.$form->EncodeJavascriptString($this->even_row_class).';' : '').(strlen($this->even_row_color) ? 'this.style.backgroundColor='.$form->EncodeJavascriptString($this->even_row_color).';' : '')).'"' : '');
		$even_rows = (strlen($this->even_row_class) ? ' class="'.$this->even_row_class.'"' : '').(strlen($this->even_row_color) ? ' style="background-color: '.$this->even_row_color.'"' : '').$highlight.$lowlight;
		$tr = count($ro);
		for($r = 0; $r < $tr; ++$r)
		{
			$s = '';
			if(strlen($this->row_style_column)
			&& IsSet($ro[$r][$this->row_style_column]))
				$s = ' style="'.HtmlSpecialChars($ro[$r][$this->row_style_column]).'"';
			$c = (($r % 2) ? $even_rows : $odd_rows);
			if(strlen($this->row_class_column)
			&& IsSet($ro[$r][$this->row_class_column]))
			{
				$class = $ro[$r][$this->row_class_column];
				$c = ' class="'.HtmlSpecialChars($class).'"'.$highlight.' onmouseout="'.HtmlSpecialChars('this.className='.$form->EncodeJavascriptString($class)).'"';
			}
			$listing .= '<tr'.$c.$s.">\n";
			for($c = 0; $c < $tc; ++$c)
			{
				$listing .= '<td'.$style[$c].'>';
				$d = (IsSet($co[$c]['Column']) ? $co[$c]['Column'] : $c);
				if($this->view
				&& IsSet($co[$c]['View']))
				{
					$id = $co[$c]['View'];
					$parameters = (IsSet($co[$c]['CustomParameters']) ? $co[$c]['CustomParameters'] : array());
					$parameters[$this->view_parameter] = IsSet($ro[$r][$id]) ? $ro[$r][$id] : $r;
					if($this->read)
						$parameters[$this->page_parameter] = $this->page;
					$listing .= '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $parameters)).'">';
					$this->AppendTemplateData($template, $listing, $last_data);
					$this->GetListingData($c, $r, $d, HtmlSpecialChars(IsSet($co[$c]['ViewLabel']) ? $co[$c]['ViewLabel'] : $this->view_label), $t);
					$this->AppendTemplate($template, $t, $last_data);
					$listing = '</a>';
				}
				elseif($this->update
				&& IsSet($co[$c]['Update']))
				{
					
					$id = $co[$c]['Update'];
					$parameters = (IsSet($co[$c]['CustomParameters']) ? $co[$c]['CustomParameters'] : array());
					$parameters[$this->update_parameter] = IsSet($ro[$r][$id]) ? $ro[$r][$id] : $r;
					if($this->read)
						$parameters[$this->page_parameter] = $this->page;
					$listing .= '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $parameters)).'">';
					$this->AppendTemplateData($template, $listing, $last_data);
					$this->GetListingData($c, $r, $d, HtmlSpecialChars(IsSet($co[$c]['UpdateLabel']) ? $co[$c]['UpdateLabel'] : $this->update_label), $t);
					$this->AppendTemplate($template, $t, $last_data);
					$listing = '</a>';
				}
				elseif($this->delete
				&& IsSet($co[$c]['Delete']))
				{
					$id = $co[$c]['Delete'];
					$parameters = (IsSet($co[$c]['CustomParameters']) ? $co[$c]['CustomParameters'] : array());
					$parameters[$this->delete_parameter] = IsSet($ro[$r][$id]) ? $ro[$r][$id] : $r;
					if($this->read)
						$parameters[$this->page_parameter] = $this->page;
					$listing .= '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $parameters)).'">';
					$this->AppendTemplateData($template, $listing, $last_data);
					$this->GetListingData($c, $r, $d, HtmlSpecialChars(IsSet($co[$c]['DeleteLabel']) ? $co[$c]['DeleteLabel'] : $this->delete_label), $t);
					$this->AppendTemplate($template, $t, $last_data);
					$listing = '</a>';
				}
				else
				{
					$this->AppendTemplateData($template, $listing, $last_data);
					$this->GetListingData($c, $r, $d, '', $t);
					$this->AppendTemplate($template, $t, $last_data);
					$listing = '';
				}
				$listing .= "</td>\n";
			}
			$listing .= "</tr>\n";
		}
		$listing .= "</table>\n";
		$this->AppendTemplateData($template, $listing, $last_data);
		return('');
	}

	Function SetColumns($columns)
	{
		if($this->update
		|| $this->delete
		|| $this->view)
		{
			Unset($id);
			$tc = count($columns);
			$has_view = $has_update = $has_delete = array();
			for($c = 0; $c < $tc; ++$c)
			{
				if(IsSet($columns[$c]['Update']))
					$has_update[] = $c;
				elseif(IsSet($columns[$c]['Delete']))
					$has_delete[] = $c;
				elseif(IsSet($columns[$c]['View']))
					$has_view[] = $c;
				if(IsSet($columns[$c]['ID']))
					$id = $c;
			}
			if(!IsSet($id))
			{
				if(!IsSet($this->id_column))
					return('it was not specified the column from where the ID value would be taken to update entries');
				$id = $this->id_column;
			}
			if($this->view)
			{
				if(count($has_view))
				{
					foreach($has_view as $view)
						$columns[$view]['View'] = $id;
				}
				else
					$columns[] = array(
						'View'=>$id,
						'Column'=>-1
					);
			}
			if($this->update)
			{
				if(count($has_update))
				{
					foreach($has_update as $update)
						$columns[$update]['Update'] = $id;
				}
				else
					$columns[] = array(
						'Update'=>$id,
						'Column'=>-1
					);
			}
			if($this->delete)
			{
				if(count($has_delete))
				{
					foreach($has_delete as $delete)
						$columns[$delete]['Delete'] = $id;
				}
				else
					$columns[] = array(
						'Delete'=>$id,
						'Column'=>-1
					);
			}
		}
		$this->columns = $columns;
		return('');
	}

	Function SetIDColumn($column)
	{
		switch(GetType($column))
		{
			case 'string':
				break;
			case 'integer':
				if($column >= 0)
					break;
			default:
				return('it was not specified a valid id column');
		}
		$this->id_column = $column;
		return('');
	}

	Function GetErrorMessage(&$form)
	{
		$i = $form->Invalid;
		$ti = count($i);
		if($ti == 0)
			return('');
		Reset($i);
		if($form->ShowAllErrors)
		{
			$p = HtmlSpecialChars($form->ErrorMessagePrefix);
			$s = HtmlSpecialChars($form->ErrorMessageSuffix);
			for($e = '', $f = 0; $f < $ti; ++$f, Next($i))
			{
				$error = $i[Key($i)];
				if(strlen($error))
					$e .= (strlen($e) > 0 ? "<br />\n" : '').$p.HtmlSpecialChars($error).$s;
			}
		}
		else
		{
			$error = $i[Key($i)];
			$e = (strlen($error) ? HtmlSpecialChars($error) : '');
		}
		return(strlen($e) ? str_replace('{errormessage}', $e, $this->error_message_format) : '');
	}

	Function AddLayoutInput(&$form, $state)
	{
		if(strlen($this->layout))
			return('');
		switch($state)
		{
			case 'create_previewing':
			case 'creating':
			case 'created':
			case 'update_previewing':
			case 'updating':
			case 'updated':
				$updating = 1;
				$fields = $this->fields;
				break;
			case 'deleting':
				$updating = 0;
				$fields = $this->delete_fields;
				break;
			default:
				return('unexpected state '.$state);
		}
		$inputs = array();
		$properties = $this->field_layout_properties;
		$tf = count($fields);
		for($f = 0, Reset($fields); $f < $tf; Next($fields), ++$f)
		{
			switch($input = Key($fields))
			{
				case $this->save_input:
				case $this->preview_input:
				case $this->submit_input:
				case $this->cancel_input:
				case $this->delete_input:
				case $this->delete_cancel_input:
					continue 2;
			}
			$inputs[] = $input;
			switch($fields[$input]['TYPE'])
			{
				case 'checkbox':
				case 'radio':
					$properties[$input]['SwitchedPosition'] = 1;
					break;
			}
		}
		if($updating)
		{
			$next = 'left';
			if($this->preview)
			{
				$inputs[] = $this->preview_input;
				$properties[$this->preview_input] = array(
					'CenteredGroup'=>'left',
				);
				$next = 'middle';
			}
			if($this->save)
			{
				$inputs[] = $this->save_input;
				$properties[$this->save_input] = array(
					'CenteredGroup'=>$next,
				);
				$next = 'middle';
			}
			$inputs[] = $this->submit_input;
			$properties[$this->submit_input] = array(
				'CenteredGroup'=>$next,
			);
			$inputs[] = $this->cancel_input;
			$properties[$this->cancel_input] = array(
				'CenteredGroup'=>'right',
			);
		}
		else
		{
			$inputs[] = $this->delete_input;
			$properties[$this->delete_input] = array(
				'CenteredGroup'=>'left',
			);
			$inputs[] = $this->delete_cancel_input;
			$properties[$this->delete_cancel_input] = array(
				'CenteredGroup'=>'right',
			);
		}
		$this->layout = $this->GenerateInputID($form, $this->input, 'layout');
		return($form->AddInput(array(
			'ID'=>$this->layout,
			'TYPE'=>'custom',
			'CustomClass'=>'form_layout_vertical_class',
			'Inputs'=>$inputs,
			'Properties'=>$properties,
			'Data'=>$this->field_data_properties,
			'InvalidMark'=>$this->invalid_mark,
		)));
	}

	Function AddInput(&$form, $arguments)
	{
		if(IsSet($arguments['Create']))
			$this->create = intval($arguments['Create']);
		if(IsSet($arguments['Read']))
			$this->read = intval($arguments['Read']);
		if(IsSet($arguments['Update']))
			$this->update = intval($arguments['Update']);
		if(IsSet($arguments['Delete']))
			$this->delete = intval($arguments['Delete']);
		if(IsSet($arguments['View']))
			$this->view = intval($arguments['View']);
		if($this->create
		|| $this->update)
		{
			if(IsSet($arguments['Save']))
				$this->save = intval($arguments['Save']);
			if(IsSet($arguments['Preview']))
				$this->preview = intval($arguments['Preview']);
		}
		if(IsSet($arguments['CustomParameters']))
			$this->custom_parameters = $arguments['CustomParameters'];
		if(IsSet($arguments['ExternalParameters']))
			$this->external_parameters = $arguments['ExternalParameters'];
		if(IsSet($arguments['FieldLayoutProperties']))
			$this->field_layout_properties = $arguments['FieldLayoutProperties'];
		if(IsSet($arguments['FieldDataProperties']))
			$this->field_data_properties = $arguments['FieldDataProperties'];
		$this->ajax_input = $this->GenerateInputID($form, $this->input, 'ajax');
		$this->action_input = $this->GenerateInputID($form, $this->input, 'action');
		$this->state_input = $this->GenerateInputID($form, $this->input, 'state');
		$this->view_id = $this->GenerateInputID($form, $this->input, 'view');
		$this->message_id = $this->GenerateInputID($form, $this->input, 'message');
		$this->error_id = $this->GenerateInputID($form, $this->input, 'error');
		if(strlen($error = $form->AddInput(array(
			'TYPE'=>'custom',
			'ID'=>$this->ajax_input,
			'CustomClass'=>'form_ajax_submit_class',
			'TargetInput'=>$this->input,
		)))
		|| strlen($error = $form->AddInput(array(
			'TYPE'=>'hidden',
			'ID'=>$this->action_input,
			'NAME'=>$this->action_input,
			'VALUE'=>''
		))))
			return($error);
		if($this->create
		|| $this->update)
		{
			$this->save_input = $this->input.'-save';
			$this->submit_input = $this->input.'-submit';
			$this->cancel_input = $this->input.'-cancel';
		}
		if($this->create)
		{
			if(IsSet($arguments['CreateMessage']))
				$this->create_message = $arguments['CreateMessage'];
			if(IsSet($arguments['CreatePreviewMessage']))
				$this->create_preview_message = $arguments['CreatePreviewMessage'];
			if(IsSet($arguments['CreateCanceledMessage']))
				$this->create_canceled_message = $arguments['CreateCanceledMessage'];
			if(IsSet($arguments['CreatedMessage']))
				$this->created_message = $arguments['CreatedMessage'];
			$this->create_parameter = $this->GenerateInputID($form, $this->input, 'create');
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$this->create_parameter,
				'ID'=>$this->create_parameter,
				'VALUE'=>'1',
				'Subform'=>$this->submit_input
			))))
				return($error);
		}
		if($this->read)
		{
			if(IsSet($arguments['ListingMessage']))
				$this->listing_message = $arguments['ListingMessage'];
			if(IsSet($arguments['NoEntriesMessage']))
				$this->no_entries_message = $arguments['NoEntriesMessage'];
			if(IsSet($arguments['ListingID']))
				$this->listing_id = $arguments['ListingID'];
			if(IsSet($arguments['ListingPrefix']))
				$this->listing_prefix = $arguments['ListingPrefix'];
			if(IsSet($arguments['ListingSuffix']))
				$this->listing_suffix = $arguments['ListingSuffix'];
			if(IsSet($arguments['ListingClass']))
				$this->listing_class = $arguments['ListingClass'];
			if(IsSet($arguments['ListingStyle']))
				$this->listing_style = $arguments['ListingStyle'];
			if(IsSet($arguments['ListingSpacing']))
				$this->listing_spacing = $arguments['ListingSpacing'];
			if(IsSet($arguments['ListingPadding']))
				$this->listing_padding = $arguments['ListingPadding'];
			if(IsSet($arguments['HighlightRowListingClass']))
				$this->highlight_row_class = $arguments['HighlightRowListingClass'];
			if(IsSet($arguments['HighlightRowListingColor']))
				$this->highlight_row_color = $arguments['HighlightRowListingColor'];
			if(IsSet($arguments['OddRowListingClass']))
				$this->odd_row_class = $arguments['OddRowListingClass'];
			if(IsSet($arguments['OddRowListingColor']))
				$this->odd_row_color = $arguments['OddRowListingColor'];
			if(IsSet($arguments['EvenRowListingClass']))
				$this->even_row_class = $arguments['EvenRowListingClass'];
			if(IsSet($arguments['EvenRowListingColor']))
				$this->even_row_color = $arguments['EvenRowListingColor'];
			if(IsSet($arguments['ErrorMessageFormat']))
				$this->error_message_format = $arguments['ErrorMessageFormat'];
			if(IsSet($arguments['FormHeader']))
				$this->form_header = $arguments['FormHeader'];
			if(IsSet($arguments['FormFooter']))
				$this->form_footer = $arguments['FormFooter'];
			if(IsSet($arguments['Rows']))
				$this->rows = $arguments['Rows'];
			if(IsSet($arguments['RowStyleColumn']))
				$this->row_style_column = $arguments['RowStyleColumn'];
			if(IsSet($arguments['RowClassColumn']))
				$this->row_class_column = $arguments['RowClassColumn'];
			if(IsSet($arguments['IDColumn'])
			&& strlen($error = $this->SetIDColumn($arguments['IDColumn'])))
				return($error);
			if(IsSet($arguments['Columns'])
			&& strlen($error = $this->SetColumns($arguments['Columns'])))
				return($error);
			if(IsSet($arguments['TotalEntries']))
				$this->total_entries = $arguments['TotalEntries'];
			if(IsSet($arguments['PageEntries']))
				$this->page_entries = $arguments['PageEntries'];
			if($this->page_entries < 0)
				return('it was not specified a valid number of entries to list per page');
			if(IsSet($arguments['Page']))
				$this->page = $arguments['Page'];
			$this->page_parameter = $this->GenerateInputID($form, $this->input, 'page');
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$this->page_parameter,
				'ID'=>$this->page_parameter,
				'VALUE'=>'',
			))))
				return($error);
		}
		if($this->update)
		{
			if(IsSet($arguments['UpdateLabel']))
				$this->update_label = $arguments['UpdateLabel'];
			if(IsSet($arguments['UpdateMessage']))
				$this->update_message = $arguments['UpdateMessage'];
			if(IsSet($arguments['UpdatePreviewMessage']))
				$this->update_preview_message = $arguments['UpdatePreviewMessage'];
			if(IsSet($arguments['UpdateCanceledMessage']))
				$this->update_canceled_message = $arguments['UpdateCanceledMessage'];
			if(IsSet($arguments['UpdatedMessage']))
				$this->updated_message = $arguments['UpdatedMessage'];
			$this->update_parameter = $this->GenerateInputID($form, $this->input, 'update');
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$this->update_parameter,
				'ID'=>$this->update_parameter,
				'VALUE'=>'1',
				'Subform'=>$this->submit_input
			))))
				return($error);
		}
		if($this->create
		|| $this->update)
		{
			if(IsSet($arguments['InvalidMark']))
			{
				if(strlen($arguments['InvalidMark']) == 0)
					return('it was not specified an invalid mark');
				$this->invalid_mark = $arguments['InvalidMark'];
			}
			if(!IsSet($arguments['EntryFields'])
			|| ($tf = count($fields = $arguments['EntryFields'])) == 0)
				return('it were not specified any entry fields');
			for($f = 0; $f < $tf; ++$f)
			{
				$field_arguments = $fields[$f];
				if(!IsSet($field_arguments[$key = 'ID'])
				&& !IsSet($field_arguments[$key = 'NAME']))
					return('it was not specified the identifier of the field '.$f);
				$id = $field_arguments[$key];
				if(!IsSet($this->field_data_properties[$id]))
				{
					$this->entry_field_names[] = $id;
					$field_arguments['SubForm'] = (strcmp($id, $this->cancel_input) ? $this->submit_input : $this->cancel_input);
					$field_arguments['NoParent'] = 1;
					if(strlen($error = $form->AddInput($field_arguments)))
						return($error);
				}
				$this->fields[$id] = $field_arguments;
			}
			if($this->save)
			{
				if(IsSet($arguments['SaveLabel']))
					$this->save_label = $arguments['SaveLabel'];
				if(!IsSet($this->fields[$this->save_input])
				&& strlen($error = $form->AddInput(array(
					'TYPE'=>'submit',
					'NAME'=>$this->save_input,
					'ID'=>$this->save_input,
					'VALUE'=>$this->save_label,
					'SubForm'=>$this->submit_input
				))))
					return($error);
			}
			if($this->preview)
			{
				$this->preview_input = $this->input.'-preview';
				if(IsSet($arguments['EntryOutput']))
					$this->entry_output = $arguments['EntryOutput'];
				if(IsSet($arguments['ReturnLinkPrefix']))
					$this->return_link_prefix = $arguments['ReturnLinkPrefix'];
				if(IsSet($arguments['ReturnLinkSuffix']))
					$this->return_link_suffix = $arguments['ReturnLinkSuffix'];
				if(IsSet($arguments['PreviewLabel']))
					$this->preview_label = $arguments['PreviewLabel'];
				if(!IsSet($this->fields[$this->preview_input])
				&& strlen($error = $form->AddInput(array(
					'TYPE'=>'submit',
					'NAME'=>$this->preview_input,
					'ID'=>$this->preview_input,
					'VALUE'=>$this->preview_label,
					'SubForm'=>$this->submit_input
				))))
					return($error);
			}
			if(!IsSet($this->fields[$this->submit_input]))
			{
				if(IsSet($arguments['SubmitLabel']))
					$this->submit_label = $arguments['SubmitLabel'];
				if(strlen($error = $form->AddInput(array(
					'TYPE'=>'submit',
					'NAME'=>$this->submit_input,
					'ID'=>$this->submit_input,
					'VALUE'=>$this->submit_label,
					'SubForm'=>$this->submit_input
				))))
					return($error);
			}
			if(!IsSet($this->fields[$this->cancel_input]))
			{
				if(IsSet($arguments['CancelLabel']))
					$this->cancel_label = $arguments['CancelLabel'];
				if(strlen($error = $form->AddInput(array(
					'TYPE'=>'submit',
					'NAME'=>$this->cancel_input,
					'ID'=>$this->cancel_input,
					'VALUE'=>$this->cancel_label,
					'SubForm'=>$this->cancel_input
				))))
					return($error);
			}
		}
		if($this->delete)
		{
			$this->delete_input = $this->input.'-delete';
			$this->delete_cancel_input = $this->input.'-delete-cancel';
			if(IsSet($arguments['DeleteLabel']))
				$this->delete_label = $arguments['DeleteLabel'];
			if(IsSet($arguments['DeleteMessage']))
				$this->delete_message = $arguments['DeleteMessage'];
			if(IsSet($arguments['DeleteCanceledMessage']))
				$this->delete_canceled_message = $arguments['DeleteCanceledMessage'];
			if(IsSet($arguments['DeletedMessage']))
				$this->deleted_message = $arguments['DeletedMessage'];
			$fields = (IsSet($arguments['DeleteFields']) ? $arguments['DeleteFields'] : array());
			$tf = count($fields);
			for($f = 0; $f < $tf; ++$f)
			{
				$field_arguments = $fields[$f];
				if(!IsSet($field_arguments[$key = 'ID'])
				&& !IsSet($field_arguments[$key = 'NAME']))
					return('it was not specified the identifier of the field '.$f);
				$id = $field_arguments[$key];
				$field_arguments['SubForm'] = (strcmp($id, $this->delete_cancel_input) ? $this->delete_input : $this->delete_cancel_input);
				$field_arguments['NoParent'] = 1;
				if(strlen($error = $form->AddInput($field_arguments)))
					return($error);
				$this->delete_fields[$id] = $field_arguments;
			}
			if(!IsSet($this->delete_fields[$this->delete_input])
			&& strlen($error = $form->AddInput(array(
				'TYPE'=>'submit',
				'NAME'=>$this->delete_input,
				'ID'=>$this->delete_input,
				'VALUE'=>$this->delete_label,
				'SubForm'=>$this->delete_input
			))))
				return($error);
			if(!IsSet($this->delete_fields[$this->delete_cancel_input]))
			{
				if(IsSet($arguments['CancelLabel']))
					$this->cancel_label = $arguments['CancelLabel'];
				if(strlen($error = $form->AddInput(array(
					'TYPE'=>'submit',
					'NAME'=>$this->delete_cancel_input,
					'ID'=>$this->delete_cancel_input,
					'VALUE'=>$this->cancel_label,
					'SubForm'=>$this->delete_cancel_input
				))))
					return($error);
			}
			$this->delete_parameter = $this->GenerateInputID($form, $this->input, 'delete');
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$this->delete_parameter,
				'ID'=>$this->delete_parameter,
				'VALUE'=>'1',
				'SubForm'=>$this->delete_input
			))))
				return($error);
		}
		if($this->view)
		{
			if(IsSet($arguments['ViewLabel']))
				$this->view_label = $arguments['ViewLabel'];
			if(IsSet($arguments['ViewingMessage']))
				$this->viewing_message = $arguments['ViewingMessage'];
			$this->view_parameter = $this->GenerateInputID($form, $this->input, 'view');
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$this->view_parameter,
				'ID'=>$this->view_parameter,
				'VALUE'=>'1',
				'Subform'=>$this->submit_input
			))))
				return($error);
		}
		switch($value = (IsSet($arguments['State']) ? $arguments['State'] : $this->state))
		{
			case 'listing':
				if(!$this->read)
					return($value.' is not a valid scaffolding state because reading entries is disallowed');
				break;

			case 'create_previewing':
			case 'created':
			case 'create_canceled':
			case 'creating':
				if(!$this->create)
					return($value.' is not a valid scaffolding state because creating entries is disallowed');
				break;

			case 'update_previewing':
			case 'updated':
			case 'update_canceled':
			case 'updating':
				if(!$this->update)
					return($value.' is not a valid scaffolding state because updating entries is disallowed');
				break;

			case 'deleted':
			case 'delete_canceled':
			case 'deleting':
				if(!$this->delete)
					return($value.' is not a valid scaffolding state because deleting entries is disallowed');
				break;

			case 'viewing':
				if(!$this->view)
					return($value.' is not a valid scaffolding state because viewing entries is disallowed');
				break;

			default:
				return($value.' is not a valid scaffolding state');
		}
		$this->state = $value;
		if(strlen($error = $form->AddInput(array(
			'TYPE'=>'hidden',
			'ID'=>$this->state_input,
			'NAME'=>$this->state_input,
			'VALUE'=>$this->state
		))))
			return($error);
		if((IsSet($arguments['Entry'])
		&& strlen($error = $this->SetInputProperty($form, 'Entry', $arguments['Entry']))))
			return($error);
		if(IsSet($arguments['AppendMessage']))
			$this->append_message = $arguments['AppendMessage'];
		if(IsSet($arguments['TargetInput']))
		{
			if(strlen($arguments['TargetInput'])==0)
				return('it was not specified a valid target input identifier');
			$this->target_input = $arguments['TargetInput'];
		}
		return('');
	}

	Function AddInputPart(&$form)
	{
		$this->valid_marks['data']['message_id'] = $this->message_id;
		$this->valid_marks['data']['view_id'] = $this->view_id;
		$this->valid_marks['data']['error_id'] = $this->error_id;
		switch($this->state)
		{
			case 'create_previewing':
			case 'update_previewing':
			case 'creating':
			case 'updating':
				$form->SetInputValue($this->state_input, $this->state);
				if(($this->save
				&& strlen($error = $form->Connect($this->save_input, $this->ajax_input, 'ONCLICK', 'Submit', array(
					'SubForm'=>$this->submit_input,
					'SetInputValue'=>array(
						$this->action_input=>'save'
					)
				))))
				|| ($this->preview
				&& strlen($error = $form->Connect($this->preview_input, $this->ajax_input, 'ONCLICK', 'Submit', array(
					'SubForm'=>$this->submit_input,
					'SetInputValue'=>array(
						$this->action_input=>'preview'
					)
				))))
				|| strlen($error = $form->AddInputPart($this->action_input))
				|| strlen($error = $form->AddInputPart($this->state_input))
				|| strlen($error = $form->AddInputPart($this->ajax_input)))
					return($error);
			case 'deleting':
				$previewing = 0;
				switch($this->state)
				{
					case 'create_previewing':
						$previewing = 1;
					case 'creating':
						$creating = 1;
						$updating = 0;
						break;
					case 'update_previewing':
						$previewing = 1;
					case 'updating':
						$creating = 0;
						$updating = 1;
						break;
					case 'deleting':
						$creating = 0;
						$updating = 0;
						break;
				}
				if(strlen($error = $this->AddLayoutInput($form, $this->state))
				|| ($this->read
				&& strlen($error = $form->AddInputPart($this->page_parameter))))
					return($error);
				$this->valid_marks['data']['listing'] =
				$this->valid_marks['data']['listingprefix'] = $this->listing_prefix;
				$this->valid_marks['data']['listingsuffix'] = $this->listing_suffix;
				$this->valid_marks['data']['toppagination'] =
				$this->valid_marks['data']['bottompagination'] = '';
				$this->valid_marks['data']['returnlinkprefix'] = $this->return_link_prefix;
				$this->valid_marks['data']['returnlinksuffix'] = $this->return_link_suffix;
				$this->valid_marks['data']['returnlink'] = 
				$this->valid_marks['data']['result'] = '';
				$this->valid_marks['data']['message'] = ($previewing ? ($creating ? $this->create_preview_message : $this->update_preview_message) : ($creating ? $this->create_message : ($updating ? $this->update_message : $this->delete_message))).$this->append_message;
				$this->valid_marks['data']['errormessage'] = $error_message = $this->GetErrorMessage($form);
				$this->valid_marks['data']['view'] = (($previewing && strlen($error_message) == 0) ? $this->entry_output : '');
				$this->valid_marks['data']['formheader'] = $this->form_header;
				$this->valid_marks['data']['formfooter'] = $this->form_footer;
				$this->valid_marks['input']['form'] = $this->layout;
				if($creating)
				{
					if(strlen($error = $form->AddInputPart($this->create_parameter))
					|| ($this->update
					&& strlen($error = $form->AddInputPart($this->update_parameter))))
						return($error);
				}
				elseif($updating)
				{
					if(strlen($error = $form->SetInputValue($this->update_parameter, $this->entry))
					|| strlen($error = $form->AddInputPart($this->update_parameter)))
						return($error);
				}
				else
				{
					if(strlen($error = $form->SetInputValue($this->submit_input, $this->delete_label))
					|| strlen($error = $form->SetInputValue($this->delete_parameter, $this->entry))
					|| strlen($error = $form->AddInputPart($this->delete_parameter)))
						return($error);
				}
				break;

			default:
				if($this->read
				&& strlen($error = $this->HasEntries($has_entries)))
					return($error);
				$viewing = 0;
				switch($this->state)
				{
					case 'created':
						$message = $this->created_message;
						break;
					case 'updated':
						$message = $this->updated_message;
						break;
					case 'deleted':
						$message = $this->deleted_message;
						break;
					case 'create_canceled':
						$message = $this->create_canceled_message;
						break;
					case 'update_canceled':
						$message = $this->update_canceled_message;
						break;
					case 'delete_canceled':
						$message = $this->delete_canceled_message;
						break;
					case 'viewing':
						$viewing = $this->view;
					default:
						$message = '';
						break;
				}
				$this->valid_marks['data']['result'] = $message.$this->append_message;
				if($viewing)
				{
					$update_parameters = array(
						$this->update_parameter => $this->entry,
					);
					$delete_parameters = array(
						$this->delete_parameter => $this->entry,
					);
					$listing_parameters = array(
					);
					if($this->read)
						$update_parameters[$this->page_parameter] = $delete_parameters[$this->page_parameter] = $listing_parameters[$this->page_parameter] = $this->page;
					$this->valid_marks['data']['view'] = $this->entry_output;
					$this->valid_marks['data']['message'] = $this->viewing_message;
					$this->valid_marks['data']['returnlinkprefix'] = $this->return_link_prefix;
					$this->valid_marks['data']['returnlinksuffix'] = $this->return_link_suffix;
					$this->valid_marks['data']['returnlink'] = ($this->update ? '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $update_parameters)).'">'.HtmlSpecialChars($this->update_label).'</a> ' : '').($this->delete ? '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $delete_parameters)).'">'.HtmlSpecialChars($this->delete_label).'</a> ' : '').($this->read ? '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $listing_parameters)).'">'.$this->listing_message.'</a>' : '');
					$this->valid_marks['data']['listing'] = 
					$this->valid_marks['data']['toppagination'] =
					$this->valid_marks['data']['bottompagination'] = '';
					$this->valid_marks['data']['listingprefix'] = $this->listing_prefix;
					$this->valid_marks['data']['listingsuffix'] = $this->listing_suffix;
				}
				elseif(!$this->read
				|| !$has_entries)
				{
					$this->valid_marks['data']['view'] = 
					$this->valid_marks['data']['listing'] = 
					$this->valid_marks['data']['toppagination'] =
					$this->valid_marks['data']['bottompagination'] = '';
					$this->valid_marks['data']['listingprefix'] = $this->listing_prefix;
					$this->valid_marks['data']['listingsuffix'] = $this->listing_suffix;
					$this->valid_marks['data']['message'] = ($this->read ? $this->no_entries_message : '');
				}
				else
				{
					if(strlen($error = $this->GetListing($form, $this->valid_marks['template']['listing'])))
						return($error);
					$this->valid_marks['data']['listingprefix'] = $this->listing_prefix;
					$this->valid_marks['data']['listingsuffix'] = $this->listing_suffix;
					$pages = $this->GeneratePagination($form);
					if(count($pages) == 1)
					{
						$this->valid_marks['data']['toppagination'] =
						$this->valid_marks['data']['bottompagination'] = '';
					}
					else
					{
						$this->valid_marks['data']['toppagination'] = $this->DisplayPagination($form, $pages, 'top');
						$this->valid_marks['data']['bottompagination'] = $this->DisplayPagination($form, $pages, 'bottom');
					}
					$this->valid_marks['data']['message'] = $this->listing_message;
					$this->valid_marks['data']['view'] = '';
				}
				$this->valid_marks['data']['formheader'] =
				$this->valid_marks['data']['form'] =
				$this->valid_marks['data']['formfooter'] =
				$this->valid_marks['data']['errormessage'] = '';
				if(!$viewing)
				{
					$parameters = array(
						$this->create_parameter => '1'
					);
					if($this->read)
						$parameters[$this->page_parameter] = $this->page;
					$this->valid_marks['data']['returnlinkprefix'] = $this->return_link_prefix;
					$this->valid_marks['data']['returnlinksuffix'] = $this->return_link_suffix;
					$this->valid_marks['data']['returnlink'] = ($this->create ? '<a href="'.HtmlSpecialChars($this->AddActionParameters($form, $parameters)).'">'.$this->create_message.'</a>' : '');
				}
				break;
		}
		$p = $this->custom_parameters;
		$tc = count($p);
		for(Reset($p), $c = 0; $c < $tc; Next($p), ++$c)
		{
			$k = Key($p);
			if(IsSet($this->external_parameters[$k]))
				continue;
			if(strlen($error = $form->AddInput(array(
				'TYPE'=>'hidden',
				'NAME'=>$k,
				'VALUE'=>$p[$k],
			)))
			|| strlen($error = $form->AddInputPart($k)))
				return($error);
		}
		return(parent::AddInputPart($form));
	}

	Function LoadInputValues(&$form, $submitted)
	{
		if(count($this->ajax_message)
		|| $this->loaded)
			return;
		$this->loaded = 1;
		$state = $form->GetSubmittedValue($this->state_input, 'listing');
		$force = 0;
		if($this->create
		&& strlen($form->GetSubmittedValue($this->create_parameter)))
		{
			if(strlen($form->WasSubmitted($this->cancel_input)))
				$this->state = 'create_canceled';
			elseif(strlen($form->WasSubmitted($this->submit_input)))
			{
				$this->state = 'created';
				switch($state)
				{
					case 'creating':
					case 'create_previewing':
						break;
					default:
						if(strlen($value = $form->GetSubmittedValue($this->update_parameter)))
						{
							$this->entry = $value;
							$this->state = 'updated';
						}
						break;
				}
			}
			elseif($this->save
			&& strlen($form->WasSubmitted($this->save_input)))
			{
				$this->state = 'created';
				$this->next_state = 'updating';
				switch($state)
				{
					case 'create_previewing':
						$this->next_state = 'update_previewing';
					case 'creating':
						break;
					default:
						if(strlen($value = $form->GetSubmittedValue($this->update_parameter)))
						{
							$this->entry = $value;
							$this->state = 'updated';
						}
						break;
				}
			}
			elseif($this->preview
			&& strlen($form->WasSubmitted($this->preview_input)))
				$this->state = 'create_previewing';
			else
				$this->state = 'creating';
		}
		elseif($this->update
		&& strlen($value = $form->GetSubmittedValue($this->update_parameter)))
		{
			$this->entry = $value;
			if(strlen($form->WasSubmitted($this->cancel_input)))
				$this->state = 'update_canceled';
			elseif(strlen($form->WasSubmitted($this->submit_input)))
				$this->state = 'updated';
			elseif($this->save
			&& strlen($form->WasSubmitted($this->save_input)))
			{
				$this->state = 'updated';
				$this->next_state = 'updating';
				switch($state)
				{
					case 'update_previewing':
						$this->next_state = 'update_previewing';
						break;
				}
			}
			elseif($this->preview
			&& strlen($form->WasSubmitted($this->preview_input)))
			{
				$this->state = 'update_previewing';
				$force = 1;
			}
			else
				$this->state = 'updating';
		}
		elseif($this->delete
		&& strlen($value = $form->GetSubmittedValue($this->delete_parameter)))
		{
			$this->entry = $value;
			if(strlen($form->WasSubmitted($this->delete_cancel_input)))
				$this->state = 'delete_canceled';
			elseif(strlen($form->WasSubmitted($this->delete_input)))
				$this->state = 'deleted';
			else
				$this->state = 'deleting';
		}
		elseif($this->view
		&& strlen($value = $form->GetSubmittedValue($this->view_parameter)))
		{
			$this->entry = $value;
			$this->state = 'viewing';
			$this->next_state = '';
		}
		if($this->read
		&& strlen($value = $form->GetSubmittedValue($this->page_parameter)))
		{
			$page = intval($value);
			if($page >= 1)
				$this->page = $page;
		}
		$previewing = 0;
		$listing = 1;
		switch($this->state)
		{
			case 'create_previewing':
			case 'update_previewing':
				$previewing = 1;
			case 'creating':
			case 'updating':
			case 'deleting':
			case 'viewing':
				$listing = 0;
				break;
		}
		if(strcmp($this->state, $state)
		|| $force)
		{
			$message = array(
				'Event'=>$this->state,
				'From'=>$this->input,
				'ReplyTo'=>$this->input,
			);
			if(strlen($this->target_input))
				$message['Target'] = $this->target_input;
			switch($this->state)
			{
				case 'updating':
				case 'updated':
				case 'update_previewing':
				case 'deleting':
				case 'deleted':
				case 'viewing':
					$message['Entry'] = $this->entry;
					break;
				case 'listing':
					$message['Page'] = $this->page;
					break;
			}
			if(strlen($error = $form->PostMessage($message)))
				return($error);
		}
		if($listing)
		{
			$message = array(
				'Event'=>'listing',
				'From'=>$this->input,
				'ReplyTo'=>$this->input,
				'Page'=>$this->page,
			);
			if(strlen($this->target_input))
				$message['Target'] = $this->target_input;
			if(strlen($error = $form->PostMessage($message)))
				return($error);
		}
		return('');
	}

	Function SetInputProperty(&$form, $property, $value)
	{
		switch($property)
		{
			case 'FormHeader':
				$this->form_header = $value;
				break;

			case 'FormFooter':
				$this->form_footer = $value;
				break;

			case 'Rows':
				$this->rows = $value;
				break;
				
			case 'RowStyleColumn':
				$this->row_style_column = $value;
				break;

			case 'RowClassColumn':
				$this->row_class_column = $value;
				break;

			case 'IDColumn':
				return($this->SetIDColumn($value));

			case 'Columns':
				return($this->SetColumns($value));

			case 'TotalEntries':
				$this->total_entries = $value;
				break;

			case 'PageEntries':
				if($value < 0)
					return('it was not specified a valid number of entries to list per page');
				$this->page_entries = $value;
				break;

			case 'Page':
				$this->page = $value;
				break;

			case 'Entry':
				$this->entry = $value;
				break;

			case 'EntryOutput':
				$this->entry_output = $value;
				break;

			case 'ReturnLinkPrefix':
				$this->return_link_prefix = $value;
				break;

			case 'ReturnLinkSuffix':
				$this->return_link_suffix = $value;
				break;

			case 'ListingPrefix':
				$this->listing_prefix = $value;
				break;

			case 'ListingSuffix':
				$this->listing_suffix = $value;
				break;

			case 'ListingMessage':
				$this->listing_message = $value;
				break;

			case 'NoEntriesMessage':
				$this->no_entries_message = $value;
				break;

			case 'ViewingMessage':
				$this->viewing_message = $value;
				break;

			case 'CreateMessage':
				$this->create_message = $value;
				break;

			case 'UpdateMessage':
				$this->update_message = $value;
				break;

			case 'DeleteMessage':
				$this->delete_message = $value;
				break;

			case 'CreatedMessage':
				$this->created_message = $value;
				break;

			case 'UpdatedMessage':
				$this->updated_message = $value;
				break;

			case 'DeletedMessage':
				$this->deleted_message = $value;
				break;

			case 'CreateCanceledMessage':
				$this->create_canceled_message = $value;
				break;

			case 'UpdateCanceledMessage':
				$this->update_canceled_message = $value;
				break;

			case 'DeleteCanceledMessage':
				$this->delete_canceled_message = $value;
				break;

			case 'CreatePreviewMessage':
				$this->create_preview_message = $value;
				break;

			case 'UpdatePreviewMessage':
				$this->update_preview_message = $value;
				break;
				
			case 'UpdateInput':
				$this->update_inputs[] = $value;
				break;

			case 'AppendMessage':
				$this->append_message = $value;
				break;

			case 'Loaded':
				$this->loaded = intval($value);
				break;
				
			case 'FieldLayoutProperties':
				$this->field_layout_properties = $value;
				break;

			case 'Create':
				$this->create = $value;
				break;

			case 'Read':
				$this->read = $value;
				break;

			case 'Update':
				$this->update = $value;
				break;

			case 'Delete':
				$this->update = $value;
				break;

			case 'SubmitLabel':
				$this->submit_label = $value;
				if(IsSet($this->submit_input))
				{
					if(strlen($error = $form->SetInputValue($this->submit_input, $value)))
						return $error;
				}
				break;

			case 'CancelLabel':
				$this->cancel_label = $value;
				if(IsSet($this->cancel_input))
				{
					if(strlen($error = $form->SetInputValue($this->cancel_input, $value)))
						return $error;
				}
				break;

			default:
				return($this->DefaultSetInputProperty($form, $property, $value));
		}
		return('');
	}

	Function GetInputProperty(&$form, $property, &$value)
	{
		switch($property)
		{
			case 'Entry':
				$value = $this->entry;
				break;

			case 'Page':
				$value = $this->page;
				break;

			case 'Editing':
				switch($this->state)
				{
					case 'listing':
					case 'create_canceled':
					case 'update_canceled':
					case 'delete_canceled':
					case 'created':
					case 'updated':
					case 'deleted':
					case 'viewing':
						$value = 0;
						break;
					case 'create_previewing':
					case 'update_previewing':
					case 'creating':
					case 'updating':
					case 'deleting':
					default:
						$value = 1;
						break;
				}
				break;

			case 'EntryFieldNames':
				$value = $this->entry_field_names;
				break;

			default:
				return($this->DefaultGetInputProperty($form, $property, $value));
		}
		return('');
	}

	Function ReplyMessage(&$form, $message, &$processed)
	{
		$next_state = '';
		if(IsSet($message['Cancel'])
		&& $message['Cancel'])
		{
			switch($this->state)
			{
				case 'creating':
				case 'created':
				case 'create_previewing':
					$next_state = 'create_canceled';
					break;
				case 'updating':
				case 'updated':
				case 'update_previewing':
					$next_state = 'update_canceled';
					break;
				case 'deleting':
				case 'deleted':
					$next_state = 'delete_canceled';
					break;
			}
			$this->state = $next_state;
			$this->next_state = 'listing';
		}
		elseif(count($form->Invalid))
		{
			switch($this->state)
			{
				case 'created':
					$this->next_state = 'creating';
					break;
				case 'updated':
					$this->next_state = 'updating';
					break;
				case 'deleted':
					$this->next_state = 'deleting';
					break;
			}
		}
		$ajax = IsSet($message['AJAX']);
		if($ajax)
		{
			switch($this->state)
			{
				case 'create_previewing':
				case 'update_previewing':
					if(strlen($error = $this->AddLayoutInput($form, $this->state)))
						return($error);
					$error_message = $this->GetErrorMessage($form);
					$actions = array();
					$actions[] = array(
						'Action'=>'SetInputValue',
						'Input'=>$this->state_input,
						'Value'=>$form->EncodeJavascriptString($this->state)
					);
					$actions[] = array(
						'Action'=>'ReplaceContent',
						'Container'=>$this->message_id,
						'Content'=>(strcmp($this->state, 'create_previewing') ? $this->update_preview_message : $this->create_preview_message).$this->append_message
					);
					$actions[] = array(
						'Action'=>'ReplaceContent',
						'Container'=>$this->error_id,
						'Content'=>$error_message
					);
					$actions[] = array(
						'Action'=>'ReplaceContent',
						'Container'=>$this->view_id,
						'Content'=>(strlen($error_message) ? '' : $this->entry_output)
					);
					$actions[] = array(
						'Action'=>'Connect',
						'To'=>$this->layout,
						'ConnectAction'=>'MarkValidated',
						'Context'=>array(
							'Document'=>'_d'
						)
					);
					$this->ajax_message['Actions'] = $actions;
					break;

				case 'created':
				case 'updated':
					if(strlen($error = $this->AddLayoutInput($form, $this->state)))
						return($error);
					$error_message = $this->GetErrorMessage($form);
					$actions = array();
					if(IsSet($message['Entry']))
					{
						$this->entry = $message['Entry'];
						$actions[] = array(
							'Action'=>'SetInputValue',
							'Input'=>$this->update_parameter,
							'Value'=>$form->EncodeJavascriptString($this->entry)
						);
					}
					if(strlen($this->next_state))
					{
						$actions[] = array(
							'Action'=>'SetInputValue',
							'Input'=>$this->state_input,
							'Value'=>$form->EncodeJavascriptString($this->next_state)
						);
					}
					if(count($form->Invalid))
					{
						$actions[] = array(
							'Action'=>'ReplaceContent',
							'Container'=>$this->message_id,
							'Content'=>(strcmp($this->next_state, 'creating') ? $this->update_message : $this->create_message).$this->append_message
						);
					}
					else
					{
						$actions[] = array(
							'Action'=>'ReplaceContent',
							'Container'=>$this->message_id,
							'Content'=>(strcmp($this->state, 'created') ? $this->updated_message : $this->created_message).$this->append_message
						);
					}
					$actions[] = array(
						'Action'=>'ReplaceContent',
						'Container'=>$this->error_id,
						'Content'=>$error_message
					);
					$actions[] = array(
						'Action'=>'Connect',
						'To'=>$this->layout,
						'ConnectAction'=>'MarkValidated',
						'Context'=>array(
							'Document'=>'_d'
						)
					);
					$this->ajax_message['Actions'] = $actions;
					break;

				case 'create_canceled':
				case 'update_canceled':
				case 'delete_canceled':
					$actions = array();
					$actions[] = array(
						'Action'=>'ReplaceContent',
						'Container'=>$this->message_id,
						'Content'=>(strcmp($this->state, 'create_canceled') ? (strcmp($this->state, 'delete_canceled') ? $this->update_canceled_message : $this->delete_canceled_message) : $this->create_canceled_message).$this->append_message
					);
					$this->ajax_message['Actions'] = $actions;
					break;
			}
			$ti = count($this->update_inputs);
			for($i = 0; $i < $ti; ++$i)
			{
				$input = $this->update_inputs[$i];
				$this->ajax_message['Actions'][] = array(
					'Action'=>'SetInputValue',
					'Input'=>$input,
					'Value'=>$form->EncodeJavascriptString($form->GetInputValue($input))
				);
			}
			$this->ajax_message['More'] = (strlen($this->next_state) > 0);
			if(strlen($error = $form->ReplyMessage($this->ajax_message, $processed)))
				return($error);
		}
		if(strlen($this->next_state))
		{
			if(IsSet($message['Entry']))
				$this->entry = $message['Entry'];
			elseif(count($form->Invalid) == 0
			&& !strcmp($this->state, 'created'))
			{
				$form->OutputError('the created message reply does not have the created entry identifier set', $this->input);
				$this->state = 'listing';
				$this->next_state = '';
				return('');
			}
			$this->state = $this->next_state;
			$this->next_state = $next_state;
			$message = array(
				'Event'=>$this->state,
				'From'=>$this->input,
				'ReplyTo'=>$this->input,
			);
			if(strlen($this->target_input))
				$message['Target'] = $this->target_input;
			if($ajax)
				$message['AJAX'] = 1;
			switch($this->state)
			{
				case 'updating':
				case 'updated':
				case 'update_previewing':
				case 'deleting':
				case 'deleted':
				case 'viewing':
					if(IsSet($this->entry))
						$message['Entry'] = $this->entry;
					break;
				case 'listing':
					$message['Page'] = $this->page;
					break;
			}
			if(strlen($error = $form->PostMessage($message)))
				return($error);
		}
		return('');
	}

	Function PostMessage(&$form, $message, &$processed)
	{
		if(!strcmp($message['From'], $this->ajax_input))
		{
			if(count($this->ajax_message))
				return('');
			$action = $form->GetSubmittedValue($this->action_input);
			$state = $form->GetSubmittedValue($this->state_input);
			$action_message = array(
				'Event'=>$action,
				'From'=>$this->input,
				'ReplyTo'=>$this->input,
				'AJAX'=>1
			);
			if(strlen($this->target_input))
				$message['Target'] = $this->target_input;
			switch($action)
			{
				case 'preview':
					$event = 'create_previewing';
					switch($state)
					{
						case 'update_previewing':
						case 'updating':
							$action_message['Entry'] = $this->entry = $form->GetSubmittedValue($this->update_parameter);
							$event = 'update_previewing';
						case 'create_previewing':
						case 'creating':
							$action_message['Event'] = $this->state = $event;
							if(strlen($error = $form->PostMessage($action_message)))
								return($error);
							$this->ajax_message = $message;
							return('');
					}
					break;
				case 'save':
					$event = 'created';
					switch($state)
					{
						case 'create_previewing':
						case 'update_previewing':
							$next_state = 'update_previewing';
							break;
						case 'updating':
							$next_state = '';
							break;
						case 'creating':
							$next_state = 'updating';
							break;
					}
					switch($state)
					{
						case 'update_previewing':
						case 'updating':
							if(strlen($value = $form->GetSubmittedValue($this->update_parameter)))
								$action_message['Entry'] = $this->entry = $value;
							$event = 'updated';
						case 'creating':
						case 'create_previewing':
							$this->next_state = $next_state;
							$action_message['Event'] = $this->state = $event;
							if(strlen($error = $form->PostMessage($action_message)))
								return($error);
							$this->ajax_message = $message;
							return('');
					}
					break;
			}
		}
		return($form->ReplyMessage($message, $processed));
	}
};

?>