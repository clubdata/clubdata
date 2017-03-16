<?php
/*
 *
 * @(#) $Id: form_mysql_auto_complete.php,v 1.2 2006/12/20 06:05:52 mlemos Exp $
 *
 */

class form_mysql_auto_complete_class extends form_auto_complete_class
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
		$complete_expression="LIKE '".str_replace("_", "\\_", str_replace("%", "\\%", str_replace("'", "\\'", $text)))."%'";
		if(!strcmp($complete_values_query=str_replace('{BEGINSWITH}', $complete_expression, $this->complete_values_query), $this->complete_values_query))
			return('the complete values query does not contain the {BEGINSWITH} mark to insert the complete expression');
		if(strlen($text)
		&& $this->complete_values_limit)
			$complete_values_query.=' LIMIT 0, '.$this->complete_values_limit;
		if(($r=@mysql_query($complete_values_query, $this->connection)))
		{
			while(($d=@mysql_fetch_array($r)))
				$found[$d[0]]=$this->FormatCompleteValue($d);
			mysql_free_result($r);
		}
		else
			$error='Complete values query execution failed: '.@mysql_error($this->connection);
		return($error);
	}
};

?>