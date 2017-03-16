<?php
/*
 *
 * @(#) $Id: form_metabase_auto_complete.php,v 1.1 2006/07/12 00:35:06 mlemos Exp $
 *
 */

class form_metabase_auto_complete_class extends form_auto_complete_class
{
	var $connection=0;
	var $complete_values_query='';
	var $complete_expression='';
	var $complete_values_limit=0;

	Function GetCompleteValues(&$form, $arguments)
	{
		if(!IsSet($arguments['Connection'])
		|| !$arguments['Connection'])
			return('it was not specified the database connection');
		$this->connection=$arguments['Connection'];
		if(!IsSet($arguments['CompleteValuesQuery'])
		|| strlen($this->complete_values_query=$arguments['CompleteValuesQuery'])==0)
			return('it was not specified valid complete values query');
		if(!IsSet($arguments['CompleteValuesLimit'])
		|| ($this->complete_values_limit=$arguments['CompleteValuesLimit'])<0)
			return('it was not specified valid complete values limit');
		return('');
	}

	Function FormatCompleteValue($result)
	{
		return(HtmlSpecialChars($result[0]));
	}

	Function SearchCompleteValues(&$form, $text, &$found)
	{
		$error='';
		$found=array();
		if(strlen($complete_expression=MetabaseBeginsWith($this->connection, $text))==0)
			return('it was not possible to build the complete query expression: '.MetabaseError($this->connection));
		if(!strcmp($complete_values_query=str_replace('{BEGINSWITH}', $complete_expression, $this->complete_values_query), $this->complete_values_query))
			return('the complete values query does not contain the {BEGINSWITH} mark to insert the complete expression');
		if(strlen($text)
		&& $this->complete_values_limit)
			MetabaseSetSelectedRowRange($this->connection, 0, $this->complete_values_limit);
		if(($r=MetabaseQuery($this->connection, $complete_values_query)))
		{
			for($l=0; !MetabaseEndOfResult($this->connection, $r); $l++)
			{
				if(!MetabaseFetchResultArray($this->connection, $r, $d, $l))
				{
					$error='Could not retrieve the complete values: '.MetabaseError($this->connection);
					break;
				}
				$found[$d[0]]=$this->FormatCompleteValue($d);
			}
			MetabaseFreeResult($this->connection, $r);
		}
		else
			$error='Complete values query execution failed: '.MetabaseError($this->connection);
		return($error);
	}
};

?>