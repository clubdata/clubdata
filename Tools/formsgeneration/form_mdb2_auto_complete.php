<?php
/*
 *
 * @(#) $Id: form_mdb2_auto_complete.php,v 1.1 2006/07/17 07:26:36 mlemos Exp $
 *
 */

class form_mdb2_auto_complete_class extends form_auto_complete_class
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
		$this->connection->loadModule('Datatype');
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
		$complete_expression=$this->connection->datatype->matchPattern(array($text, '%'), 'LIKE');
		if(PEAR::isError($complete_expression))
			return('it was not possible to build the complete query expression: '.$complete_expression->getMessage().' - '.$complete_expression->getUserinfo());
		$complete_expression= $complete_expression;
		if(!strcmp($complete_values_query=str_replace('{BEGINSWITH}', $complete_expression, $this->complete_values_query), $this->complete_values_query))
			return('the complete values query does not contain the {BEGINSWITH} mark to insert the complete expression');
		if(strlen($text)
		&& $this->complete_values_limit)
			$this->connection->setLimit($this->complete_values_limit, 0);
		$r=$this->connection->query($complete_values_query);
		if(!PEAR::isError($r))
		{
			while(($d = $r->fetchRow()))
			{
				$found[$d[0]]=$this->FormatCompleteValue($d);
			}
			$r->free();
		}
		else
			$error='Complete values query execution failed: '.$r->getMessage().' - '.$r->getUserinfo();
		return($error);
	}
};

?>