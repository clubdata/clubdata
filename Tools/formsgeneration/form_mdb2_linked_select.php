<?php
/*
 * form_mdb2_linked_select.php
 *
 * @(#) $Id: form_mdb2_linked_select.php,v 1.1 2006/01/16 16:43:10 mlemos Exp $
 *
 */

class form_mdb2_linked_select_class extends form_linked_select_class
{
	var $connection = 0;
	var $groups_query = '';
	var $options_statement = 0;
	var $default_option;
	var $default_option_value;

	function GetGroupOptions(&$o, $group)
	{
		$o = array();
		if (isset($this->default_option)) {
			$o[$this->default_option] = $this->default_option_value;
		}
		$result =& $this->options_statement->execute(array($group));
		$error = '';
		if (PEAR::isError($result)) {
			$error="Options query execution failed: ".$result->getMessage();
		} else {
			$data = $result->fetchAll(MDB2_FETCHMODE_ORDERED, true, false);
			if (PEAR::isError($data)) {
				$data = $result->getMessage();
			} else {
				$o+= $data;
			}
			if (count($o) == 0) {
				$error = "there are no options for group $group";
			}
		}
		$result->free();
		if (strlen($error)) {
			unset($o);
		}
		return $error;
	}

	function GetGroups(&$g)
	{
		if (strlen($this->groups_query) == 0) {
			return("it was not specified a valid query to retrieve all the options groups");
		}
		$g = array();
		if (isset($this->default_option)) {
			$g[] = $this->default_option;
		}
		$error = '';
		$data = $this->connection->queryCol($this->groups_query);
		if (PEAR::isError($data)) {
			$error="Groups query execution failed: ".$data->getMessage();
		} else {
			$g+= $data;
			if(count($g) == 0) {
				$error="there are no group options";
			}
		}
		if (strlen($error)) {
			unset($g);
		}
		return $error;
	}

	function ValidateGroups(&$arguments)
	{
		if (!isset($arguments["Connection"]) || !$arguments["Connection"]) {
			return "it was not specified the database connection";
		}
		$this->connection =& $arguments["Connection"];
		if (isset($arguments["GroupsQuery"])) {
			$this->groups_query = $arguments["GroupsQuery"];
		}
		if (!isset($arguments["OptionsQuery"])) {
			return "it was not specified the query to retrieve the options";
		}
		$this->options_statement =& $this->connection->prepare($arguments["OptionsQuery"], array('text'));
		if (PEAR::isError($this->options_statement)) {
			return "Options query preparation failed: ".$this->options_statement->getMessage();
		}
		if (isset($arguments["DefaultOption"])) {
			$this->default_option = $arguments["DefaultOption"];
			if (isset($arguments["DefaultOptionValue"])) {
				$this->default_option_value = $arguments["DefaultOptionValue"];
			}
		}
		return "";
	}
};

?>