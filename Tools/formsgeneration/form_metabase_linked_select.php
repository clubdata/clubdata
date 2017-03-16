<?php
/*
 * form_metabase_linked_select.php
 *
 * @(#) $Id: form_metabase_linked_select.php,v 1.4 2006/01/02 01:14:11 mlemos Exp $
 *
 */

class form_metabase_linked_select_class extends form_linked_select_class
{
	var $connection=0;
	var $groups_query="";
	var $options_statement=0;
	var $default_option;
	var $default_option_value;
	var $default_dynamic=1;

	Function GetGroupOptions(&$o,$group)
	{
		$o=array();
		if(IsSet($this->default_option))
			$o[$this->default_option]=$this->default_option_value;
		$error="";
		MetabaseQuerySetText($this->connection, $this->options_statement, 1, $group);
		if(($r=MetabaseExecuteQuery($this->connection, $this->options_statement)))
		{
			for($l=0; !MetabaseEndOfResult($this->connection, $r); $l++)
			{
				if(!MetabaseFetchResultArray($this->connection, $r, $d, $l))
				{
					$error="Could not retrieve the group ".$group." options: ".MetabaseError($this->connection);
					break;
				}
				$o[$d[0]]=$d[1];
			}
			if(count($o)==0
			&& strlen($error)==0)
				$error="there are no options for group ".$group;
			MetabaseFreeResult($this->connection, $r);
		}
		else
			$error="Options query execution failed: ".MetabaseError($this->connection);
		if(strlen($error))
			UnSet($o);
		return($error);
	}

	Function GetGroups(&$g)
	{
		if(strlen($this->groups_query)==0)
			return("it was not specified a valid query to retrieve all the options groups");
		$g=array();
		if(IsSet($this->default_option))
			$g[]=$this->default_option;
		$error="";
		if(($r=MetabaseQuery($this->connection, $this->groups_query)))
		{
			for($l=0; !MetabaseEndOfResult($this->connection, $r); $l++)
			{
				if(!MetabaseFetchResultArray($this->connection, $r, $d, $l))
				{
					$error="Could not retrieve the options group: ".MetabaseError($this->connection);
					break;
				}
				$g[]=$d[0];
			}
			if(count($g)==0
			&& strlen($error)==0)
				$error="there are no group options";
			MetabaseFreeResult($this->connection, $r);
		}
		else
			$error="Groups query execution failed: ".MetabaseError($this->connection);
		if(strlen($error))
			UnSet($g);
		return($error);
	}

	Function ValidateGroups(&$arguments)
	{
		if(!IsSet($arguments["Connection"])
		|| !$arguments["Connection"])
			return("it was not specified the database connection");
		$this->connection=$arguments["Connection"];
		if(IsSet($arguments["GroupsQuery"]))
			$this->groups_query=$arguments["GroupsQuery"];
		if(!IsSet($arguments["OptionsQuery"]))
			return("it was not specified the query to retrieve the options");
		if(!($this->options_statement=MetabasePrepareQuery($this->connection, $arguments["OptionsQuery"])))
			return("Options query preparation failed: ".MetabaseError($this->connection));
		if(IsSet($arguments["DefaultOption"]))
		{
			$this->default_option=$arguments["DefaultOption"];
			if(IsSet($arguments["DefaultOptionValue"]))
				$this->default_option_value=$arguments["DefaultOptionValue"];
		}
		return("");
	}
};

?>