<?php
/*
 * Example class to demonstrate how to store and retrieve information of
 * the entries to be manipulated by the scaffolding plug-in.
 *
 * A real model access class would probably be a sub-class of a base class
 * that would provide common functionality to create, read, update and
 * delete database records. The sub-class would customize the details that
 * vary from case to case, like the name of the table, name of the fields,
 * condition clauses, etc..
 *
 * @(#) $Id: blog_post_model.php,v 1.7 2012/12/31 10:56:45 mlemos Exp $
 *
 */

class blog_post_model_class
{
	var $error = '';
	var $page_entries = 10;
	var $session = 'posts_storage';

	/*
	 *  Initialize the class to prepare the access to the storage container.
	 *  If the storage container is a database, here you would probably
	 *  establish the database connection.
	 */
	Function Initialize()
	{
		if(!session_start())
		{
			$this->error = 'could not start a session';
			return(0);
		}
		if(!IsSet($_SESSION[$this->session]))
			$_SESSION[$this->session] = array();
		return(1);
	}

	/*
	 *  Get a bidimensional array with all entries to be listed in the
	 *  current page.
	 *  If the storage container is a database, here you would execute a
	 *  database query to retrieve the records to be listed in the current
	 *  page and probably another query to count the total number of
	 *  accessible records.
	 */
	Function GetEntries(&$page, &$entries, &$total_entries)
	{
		$all = $_SESSION[$this->session];
		$total_entries = count($all);
		if($total_entries == 0)
		{
			$page = 1;
			$entries = array();
		}
		else
		{
			for($g = $e = 0; $e < $total_entries; ++$e)
			{
				if(IsSet($all[$e]))
					++$g;
			}
			$t = $total_entries;
			$total_entries = $g;
			$p = $this->page_entries;
			if($page < 1)
				$page = 1;
			if(($page - 1) * $p > $total_entries)
				$page = intval($total_entries / $p) + 1;
			$start = ($page - 1) * $p;
			$entries = array();
			for($e = $g = 0; $g < $start && $e < $t; ++$e)
			{
				if(IsSet($all[$e]))
					++$g;
			}
			for($g = 0; $g < $p && $e < $t; ++$e)
			{
				if(IsSet($all[$e]))
				{
					$entries[] = array(
						$e + 1,
						$all[$e]['title']
					);
					++$g;
				}
			}
		}
		return(1);
	}

	/*
	 *  Create a new entry with the given entry values.
	 *  If the storage container is a database, here you would execute a
	 *  database insert query to store a new record. The new record
	 *  identifier should be set to the id entry value.
	 */
	Function CreateEntry(&$entry)
	{
		$id = count($_SESSION[$this->session]);
		$entry['id'] = $id + 1;
		$_SESSION[$this->session][$id] = $entry;
		return(1);
	}

	/*
	 *  Get a array with values of entry with a given identifier.
	 *  If the storage container is a database, here you would execute a
	 *  database query to retrieve the record with the given identifier.
	 */
	Function ReadEntry($id, &$entry)
	{
		--$id;
		if(!IsSet($_SESSION[$this->session][$id]))
		{
			$entry = null;
			return(1);
		}
		$entry = $_SESSION[$this->session][$id];
		return(1);
	}

	/*
	 *  Search for an entry with a given title and return an array with
	 *  values of entry if found.
	 */
	Function FindEntryByTitle($title, &$entry)
	{
		Reset($_SESSION[$this->session]);
		$total = Count($_SESSION[$this->session]);
		for($id = 0; $id < $total; Next($_SESSION[$this->session]), ++$id)
		{
			if(!strcmp($_SESSION[$this->session][$id]['title'], $title))
			{
				$entry = $_SESSION[$this->session][$id];
				$entry['id'] = $id + 1;
				return(1);
			}
		}
		$entry = null;
		return(1);
	}

	/*
	 *  Update an existing entry with the given entry values.
	 *  If the storage container is a database, here you would execute a
	 *  database update query to store the record changes.
	 */
	Function UpdateEntry($id, &$entry)
	{
		--$id;
		if(!IsSet($_SESSION[$this->session][$id]))
		{
			$this->error = 'the entry does not exist';
			return(1);
		}
		$_SESSION[$this->session][$id] = $entry;
		return(1);
	}

	/*
	 *  Delete an existing entry with a given identifier.
	 *  If the storage container is a database, here you would execute a
	 *  database delete query to remove the record.
	 */
	Function DeleteEntry($id)
	{
		--$id;
		if(!IsSet($_SESSION[$this->session][$id]))
		{
			$this->error = 'the entry does not exist';
			return(1);
		}
		$_SESSION[$this->session][$id] = null;
		return(1);
	}

	/*
	 *  Finalize the class to free any resources allocated during the access
	 *  to the storage container.
	 *  If the storage container is a database, here you would probably
	 *  close the database connection.
	 */
	Function Finalize()
	{
		return(1);
	}
};