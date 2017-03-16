<?php
/*
 * Example class to demonstrate how to create a data source class to store
 * and retrieve information of entries to be viewed and manipulated with
 * the scaffolding and crud plug-ins.
 *
 * @(#) $Id: blog_post_data_source.php,v 1.1 2012/12/31 11:07:36 mlemos Exp $
 *
 */

class blog_post_data_source_class extends form_crud_data_source_class
{
	var $model;
	var $view;

	/*
	 * The Initialize function can be used to initialize some variables that
	 * the class may need in the future to access the records of information
	 * to be viewed or manipulated using the CRUD user interface.
	 * 
	 * The $arguments parameter is the array of parameters passed to the
	 * AddInput function
	 */
	Function Initialize(&$form, $arguments)
	{
		if(!IsSet($arguments['Model']))
			return('it was not specified the posts model object');
		$this->model = &$arguments['Model'];
		if(!IsSet($arguments['View']))
			return('it was not specified the posts view object');
		$this->view = &$arguments['View'];
		return('');
	}

	/*
	 *  Retrieve the records of the entries to list, as well the
	 *  configuration to generate the listing
	 */
	Function GetListing(&$form, &$listing)
	{
		$page = $listing['Page'];
		if(!$this->model->GetEntries($page, $posts, $total_posts))
		{
			return($model->error);
		}
		if(!$this->view->GetPostListingFormat($columns, $id_column, $page_entries))
		{
			return($this->view->error);
		}
		$listing['Rows'] = $posts;
		$listing['TotalEntries'] = $total_posts;
		$listing['PageEntries'] = $page_entries;
		$listing['IDColumn'] = $id_column;
		$listing['Columns'] = $columns;
		return('');
	}

	/*
	 * Generate the output to view a single entry.
	 * The base class can generate the output using a template set to the
	 * entry_template class variable. The entry_template_properties variable
	 * can be set to configure details of presentation of entry property.
	 */
	Function ViewEntry(&$form, &$entry, &$values)
	{
		if(!$this->view->GetPostFormat($this->entry_template, $this->entry_template_properties))
		{
			return($this->view->error);
		}
		return(parent::ViewEntry($form, $entry, $values));
	}

	/*
	 *  Retrieve the values of an entry for display, editing or deleting
	 */
	Function GetEntry(&$form, &$entry, &$invalid, &$values)
	{
			/*
			 *  If the entry must be an integer, verify that the requested value
			 *  is an integer and cancel the entry retrieval otherwise.
			 */
		$id = intval($entry['ID']);
		if(strcmp($id, $entry['ID']))
		{
			$invalid = true;
			return '';
		}
		if(!$this->model->ReadEntry($id, $values))
		{
			return($this->model->error);
		}
		if(!IsSet($values))
		{
			/*
			 *  If the entry does not exist or maybe the user does not have
			 *  permissions to access it, cancel the entry access as this may be
			 *  an attempt to access unauthorized information.
			 */
			$invalid = true;
			return '';
		}
		UnSet($values['id']);
		return '';
	}

	/*
	 *  Save an entry that is being created or updated
	 */
	Function SaveEntry(&$form, $creating, &$entry, &$values)
	{
		/*
		 *  First validate the entry values
		 *  In this example it is tested if there is already an entry with the
		 *  same title
		 */
		if(!$this->model->FindEntryByTitle($values['title'], $duplicated))
		{
			/*
			 *  If there was a problem searching for the entry, return the
			 *  error, so the application can deal with it.
			 */
			return($this->model->error);
		}
		if(IsSet($duplicated)
		&& ($creating
		|| $entry['ID'] != $duplicated['id']))
		{
			/*
			 *  Flag the title input as invalid because there is already an
			 *  entry with the specified title.
			 */
			$form->FlagInvalidInput('title', 'There is already a post with the title '.$values['title']);
			return('');
		}

		/*
		 *  Are we creating a new entry or updating an existing entry?
		 */
		if($creating)
		{
			if(!$this->model->CreateEntry($values))
			{
				/*
				 *  If there was a problem creating an entry, return the error,
				 *  so the application can deal with it.
				 */
				return($this->model->error);
			}
			/*
			 *  If the entry was created successfully, the entry ID parameter
			 *  must be set with the new entry record identifier.
			 */
			$entry['ID'] = $values['id'];
		}
		else
		{
			if(!$this->model->UpdateEntry($entry['ID'], $values))
			{
				/*
				 *  If there was a problem updating an entry, return the error,
				 *  so the application can deal with it.
				 */
				return($this->model->error);
			}
		}
		return('');
	}

	/*
	 *  Delete a given entry
	 */
	Function DeleteEntry(&$form, &$entry)
	{
		if(!$this->model->DeleteEntry($entry['ID']))
		{
			/*
			 *  If there was a problem deleting an entry, return the error,
			 *  so the application can deal with it.
			 */
			return($this->model->error);
		}
		return('');
	}
};

?>