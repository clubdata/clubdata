<?php
/*
 * Example class to demonstrate customize details of presentation of
 * scaffolding forms and listings.
 *
 * @(#) $Id: blog_post_view.php,v 1.6 2012/12/31 10:56:10 mlemos Exp $
 *
 */

class blog_post_view_class
{
	var $error = '';
	var $page_entries = 10;
	var	$columns = array(
		array(
			'Header'=>'ID',
			'Style'=>'text-align: center; font-family: monospace; font-weight: bold',
		),
		array(
			'Header'=>'Title',
			'HTML'=>1
		),
	);
	var $id_column = 0;
	var $post_format = '<div align="center"><div class="article"><h2 class="articletitle">{title}</h2><div class="articlebody">{body}</div></div></div>';
	var $post_format_properties = array(
		'title'=>array(
			'HTML'=>1,
		),
		'body'=>array(
			'HTML'=>1,
		),
	);
	var $error_message_format = '<div align="center"><table class="errormessage"><tr><td>{errormessage}</td></tr></table></div>';
	var $form_header = '<center><table class="form" summary="Form">
<tr>
<td class="formtitle">Blog post</td>
</tr>

<tr>
<td>';
	var $form_footer = '</td>
</tr>
</table></center>';
	var $invalid_mark = '<span class="invalidmark">X</span>';
	var $invalid_inputs_class = 'invalid';
	var $css_styles =
".rounded, .box, .article, .errormessage, .invalidmark { border-radius: 8px ; -moz-border-radius: 8px; -webkit-border-radius: 8px; }
.box, .article, .form, .invalidmark, .errormessage { border-style: solid ; border-top-color: #fcfcff ; border-left-color: #fcfcff ; border-bottom-color: #707078 ; border-right-color: #707078 ; border-width: 1px ; }
.listing { background-color: #e4e4e8; padding: 4px; margin: 4px }
.highlightrow { background-color: #b0e0b0 }
.oddrow { background-color: #d0d0d4 }
.evenrow { background-color: #dcdce0 }
.article { text-align: left; background-color: #e4e4e8; margin: 4px; width: 40em }
.articletitle { padding: 4px ; margin: 0px; text-align: left }
.articlebody { padding: 4px; text-align: left  }
.form { background-color: #e4e4e8 }
.formtitle { background-color: #000080; border-style: none; color: #ffffff; font-weight: bold; padding: 2px }
.errormessage, .invalidmark { background-color: #ffb366 }
.invalid { background-color: #ffcccc }
.errormessage { font-weight: bold; padding: 4px; margin: 4px; text-align: left }
.invalidmark { font-weight: bold; padding: 3px; margin: 0px; display: inline; vertical-align: top }
";

	/*
	 *  Initialize the class to initialize resources that may be necessary.
	 */
	Function Initialize()
	{
		return(1);
	}

	/*
	 *  Get the options that define how post listings will appear, like the
	 *  the listing table columns, number of the column that contains the
	 *  listing entry identifiers and the number of entries to display per
	 *  page.
	 */
	Function GetPostListingFormat(&$columns, &$id_column, &$page_entries)
	{
		$columns = $this->columns;
		$id_column = $this->id_column;
		$page_entries = $this->page_entries;
		return(1);
	}

	/*
	 *  Get the options that define how post listings will appear, like the
	 *  the listing table columns, number of the column that contains the
	 *  listing entry identifiers and the number of entries to display per
	 *  page.
	 */
	Function GetPostFormat(&$template, &$properties)
	{
		$template = $this->post_format;
		$properties = $this->post_format_properties;
		return(1);
	}
	
	/*
	 *  Generate HTML to show how an entry will appear.
	 */
	Function GetPostOutput($entry, &$output)
	{
		$output = str_replace(
			'{title}', HtmlSpecialChars($entry['title']), str_replace(
			'{body}', nl2br(HtmlSpecialChars($entry['body'])),
			$this->entry_format));
		return(1);
	}

	/*
	 *  Get the HTML that defines how the validation error messages will be
	 *  presented.
	 */
	Function GetErrorMessageFormat()
	{
		return($this->error_message_format);
	}

	/*
	 *  Get the HTML that defines the beginning of a section within which
	 *  the create, update and delete entry form will appear.
	 */
	Function GetFormHeader()
	{
		return($this->form_header);
	}

	/*
	 *  Get the HTML that defines the end of a section within which the
	 *  create, update and delete entry form will appear.
	 */
	Function GetFormFooter()
	{
		return($this->form_footer);
	}

	/*
	 *  Get the HTML that defines how will appear the marks that identify
	 *  invalid form fields.
	 */
	Function GetInvalidMark()
	{
		return($this->invalid_mark);
	}

	/*
	 *  Get the name of CSS style that will be used to denote invalid form
	 *  fields.
	 */
	Function GetInvalidInputsClass()
	{
		return($this->invalid_inputs_class);
	}

	/*
	 *  Get the definition of CSS styles that are used in the different HTML
	 *  templates.
	 */
	Function GetCSSStyles()
	{
		return($this->css_styles);
	}

	/*
	 *  Finalize the class to free resources that may have been allocated.
	 */
	Function Finalize()
	{
		return(1);
	}
};