<?php
/*
 * form_mysql_linked_select.php
 *
 * @(#) $Id: form_mysql_linked_select.php,v 1.4 2006/01/02 01:14:11 mlemos Exp $
 *
 */

class form_mysql_linked_select_class extends form_linked_select_class
{
	var $connection=0;
	var $groups_query="";
	var $options_query="";
	var $default_option;
	var $default_option_value;
	var $default_dynamic=1;

	Function GetGroupOptions(&$o,$group)
	{
		$o=array();
		if(IsSet($this->default_option))
			$o[$this->default_option]=$this->default_option_value;
		$error="";
		$g=str_replace("'","\\'",str_replace("\\","\\\\",$group));
		$query=str_replace("{GROUP}", "'".$g."'", $this->options_query);
		if(($r=@mysql_query($query,$this->connection)))
		{
			while(($d=@mysql_fetch_array($r)))
			{
				$o[$d[0]]=$d[1];
			}
			if(count($o)==0)
				$error="there are no options for group ".$group;
			mysql_free_result($r);
		}
		else
			$error="Options query execution failed: ".@mysql_error($this->connection);
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
		if(($r=@mysql_query($this->groups_query,$this->connection)))
		{
			while(($d=@mysql_fetch_array($r)))
				$g[]=$d[0];
			if(count($g)==0
			&& strlen($error)==0)
				$error="there are no group options";
			mysql_free_result($r);
		}
		else
			$error="Groups query execution failed: ".@mysql_error($this->connection);
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
		$this->options_query=$arguments["OptionsQuery"];
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