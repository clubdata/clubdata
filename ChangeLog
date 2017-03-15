2015-06-17 Franz Domes <franz.domes@gmx.de>
  * change standard from iso-8859-1 to UTF-8. Prepared Clubdata2 to remove iso-8859-1 support in the future
  * moved ISO-8859-1 language files to subdirectory ISO-8859-1
  * configured UTF8 language files as fallback language files

2015-06-14 Franz Domes <franz.domes@gmx.de>
  * Readded firm address to member overview
  * added all addresses to Member Overview for new css3 template
  
2015-06-14 Franz Domes <franz.domes@gmx.de>
  * Added new style css3. This style will simplify the styles by using a lot of new css3. This makes it possible to start on repsonsive design
  * Set header text for impressum by main.php. No special v_Impressum.header.tpl is needed anymore
  * Fixed warnings in error.log
  * found more occurences of max_length and replaced them by length
  * corrected missing constant DEFAULT_LANGUAGE 
  * added buttons to conference search
  * updated to jquery 1.11.3

2015-06-11 Franz Domes <franz.domes@gmx.de>
  * removed old files
  * Updated INSTALL.txt

2015-06-10 Franz Domes <franz.domes@gmx.de>
  * dbtable.class.php: BUGFIX: field max_length replaced by length (Thanks to Richard)

2015-05-12 Franz Domes <franz.domes@gmx.de>
  * replaced adodb 5.11 by adodb 5.19
  * changed order of test if no configuration file exists and driver is mysqli in index.php
  * removed *.php5 files as PHP5.X is now assumed as standard

2015-05-11 Franz Domes <franz.domes@gmx.de>
  * V2.03 BETA 8 released
  * removed call to register_session() as PHP 5.X does not support it anymore
  * changed call to Table::Table to avoid "Call-time pass-by-reference has been removed" error
  * changed database-driver from mysql to mysqli (Change of configuration.php needed!!)
  
2012-04-11 Franz Domes <franz.domes@gmx.de>
  * V2.03 BETA 7 released

2011-05-14 Franz Domes <franz.domes@gmx.de>
  * Fixed bug 3302118 (Attributes not shown when switching from advanced search to simple search in search for e-mails)
  * Changed search presets in search for conferences

2011-05-01 Franz Domes <franz.domes@gmx.de>
  * Replaced adodb 4.66 by adodb 5.11
  * Changed error-handling in getFieldList() to differentiate between addresstabs which do not exists and database errors.
    (One prerequisite to be able to delete the firm tab if not needed)
  * Changed default view of module list to Memberlist (instead of search)
  * Changed index.php to always show main module if not logged in
  * Improved selection of startpage (via configuration parameter 27). Now, you can either give the name of a module (like main)
    or you can give a parameter string as passed in the URL (this string must start by a question mark and must contain 
    a entry mod=<modulename>).
    (e.g. ?mod=list&view=Memberlist&InitView=1 to start with a list of all members)

2011-04-30 Franz Domes <franz.domes@gmx.de>
  * Replaced htmlarea by tiny_mce as htmlarea is no longer supported and makes problem under windows
  
2011-04-18 Franz Domes <franz.domes@gmx.de>
  * Fixed Bug 3206981: Fatal error when exporting member summary to Excel
  
2011-03-06 Franz Domes <franz.domes@gmx.de>
  * Added function is__writable (with two underscores) to avoid windows bug in function is_writable (with one underscore)
  * Updated installation function (install.php and install.php5) to work with Windows (XAMPP)

2011-03-05 Franz Domes <franz.domes@gmx.de>
  * Cropped Logo to support smaller screen resolutions
  * Changed index.php and index.php5 to make configuration parameter work (at least to a certain degree)

2011-02-17 Franz Domes <franz.domes@gmx.de>
  * BUGFIX: changed regexp of globVar sort in v_Memberlist.php to avoid error when paging
   
2011-01-06 Franz Domes <franz.domes@gmx.de>
  * enhanced creation of emails
  * fixed bug where mailing type was not stored with saved emails
  * Members may see email-details, but without BCC list
  
2011-01-05 Franz Domes <franz.domes@gmx.de>
  * fixed bug where members couldn't save their data. Needed to change handling of attributes.
  * fixed links which leads to unauthorized functions when logged in as member
  * fixed php incompatibility in calendar.js.php

2011-01-04 Franz Domes <franz.domes@gmx.de>
  * replaced preg_filter in install.php by preg_grep and preg_replace as this function exists only since PHP V5.3

2011-01-02 Franz Domes <franz.domes@gmx.de>
  * V2.03 BETA 6 released
  * Separated Clubdata V2 and Documentation project to reduce size of each project

2011-01-01  Franz Domes <franz.domes@gmx.de>
  * Changed captureAndProcessOutput() to display detail output correctly
  * Updated td.description height to make detail pane looks better
  * Updated member overview.
  * Changed search class to show headlines correctly
  * search.inc.tpl doesn't show default titles anymore if no head line exists.
  * Changed getAllLang2.pl to initiate text hash by phrases which should always exist in translation files.
    This was done to translate table names for search headlines, but may be usefull for many other things
  * Added constant DEFAULT_LANGUAGE to configuration file to define default language for language selections.
    The initial language is also set to DEFAULT_LANGUAGE
  * Changed member insertion action to select DEFAULT_LANGUAGE for new members
  * Changed admin add function to select DEFAULT_LANGUAGE for new users
  * Added DbTable-class to support preset values for selections (bug fix)
  * Corrected function in list.inc.tpl to show summary lines (bug fix)
  * Corrected member statistic to show also unassigned member types
  * Corrected NotInSelection to work properly (bug fix)
  * Corrected search class to avoid multiple occurences of memberId in advanced search
  
2010-12-30 Franz Domes <franz.domes@gmx.de>
  * Changed index.php, so that the main page is displayed if you do not have the permission to do a task
  * Changed main.tpl to make error messages in main page looks nicer (in the area and not above)
  * Changed tooltip generation to translate header text to native language
  * Changed column setting, so that tooltips work correctly
  * Changed title text in settings
  * Added tabulator-less header to standard tabulator.inc and deleted all *.tabulator.inc
  * Deleted unnecessary, old tabulator definitions from module files
  * Updated End of Year Batch-Job
  * Corrected errors in Backup-class. Added new configuration parameter BACKUPDIR
  * Updated translations to new texts

2010-12-26 Franz Domes <franz.domes@gmx.de>
  * Added check for writables directories/files to the installation routine
  * Changed style.css to better display buttons
  * Changed password field in installation routine to type password, so the password is no longer displayed in clear text
  * Added Mailingtypes to the database administration menu
  
2010-12-11 Franz Domes <franz.domes@gmx.de>
  * Added dummy files to logos,cache and templates_c directory to satisfy subversion
  
2010-11-27 Franz Domes <franz.domes@gmx.de>
  * Added new help texts to table ###_Help. Deleted unused ones.
  * Changed update function of V2.03 to reread Installation/Clubdata2-help.mysql.sql
  * Updated tooltips to show section and subsection on undefined help entries. So it will be easier to add new help texts
  * Updated tooltips to use header text instead of additional header in the text area
  * Moved tootips a little bit upper to point better to the help item
   
2010-11-18 Franz Domes <franz.domes@gmx.de>
  * V2.03
  * Changed primary key of table Help and added id field
  * Added Help table to Administration -> Database
  * Changed input field to multiline if max length of a input field is longer than 1024 characters
  * Supported languages are defined by database and no longer by array in function.php
  * Installation: Added check for proper SCRIPTROOT and LINKROOT.
  
2010-11-14 Franz Domes <franz.domes@gmx.de>
  * V2.02 BETA 5 released
  
2010-11-13 Franz Domes <franz.domes@gmx.de>
  * Corrected an error when creating a new language. Now new columns are created successfully
  
2010-09-20 Franz Domes <franz.domes@gmx.de>
  * Changed login procedure. Now the normal Clubdata screen is shown at the beginning
  * Corrected errors in member login
  * Corrected error when searching for Mailingtypes
  * Created "demo version" output via hidden configuration parameter

2010-09-20 Franz Domes <franz.domes@gmx.de>
  * Added class clubdata_rs_ext_mysql to support ADODB extensions
  
2010-09-04 Franz Domes <franz.domes@gmx.de>
  * Invoices can now be generated correctly (i.e. function Communication -> Send Invoice works as expected)
  * New button style (Thanks to Alex Griffioen and his tutorial at http://www.oscaralexander.com/tutorials/how-to-make-sexy-buttons-with-css.html)
  * Added tooltip in addition to open a new window for showing help text (http://plugins.learningjquery.com/cluetip/)
  * Started Settings-> Personal settings, where user dependend settings can be changed by each user. Tooltips can be switched on and off

2010-09-03 Franz Domes <franz.domes@gmx.de>
  * Changed navigator behavour. The submenues stays open, until a new mein menu is selected (added smarty-variable $navigatorMenu)

2010-09-01 Franz Domes <franz.domes@gmx.de>
  * Changed style of list headers
  * Added support to sort lists by different columns

2010-08-24 Franz Domes <franz.domes@gmx.de>
  * Added formular for invoice year to "Send Invoice".
  * Changed Clubdata logo
  * Moved Logoff out of Home section. It is now a major section to encourage user to log off
  * Changed icon of conferences section.
  * Corrected funtion resolveField() to support columns delimited by `. This also impacts to the display
    of column names in Clubdata (mostly labels)

2010-08-22 Franz Domes <franz.domes@gmx.de>
  * V2.01 BETA 4 released
  * Included Help-System from Version 1, adjusted install.php/install.php5 and module help.
  * Added error text, if include/configuration.php cannot be found
  * Added error text, if connection to database fails

2010-06-20  Franz Domes <franz.domes@gmx.de>
  * Added ajax switch to js_main.js ( true = ajax calls via jquery will be used, false = pages will be reloaded)
  * Implement ajax calls to toggleChecked() in js_main.js
  * changed call to intval to doubleval in generateExcelList() in dblist.class.php

2010-06-06  Franz Domes <franz.domes@gmx.de>
  * Added tablename in function exportExcel() if no tablename is given for column MemberID

2010-02-12  Franz Domes <franz.domes@gmx.de>
  * Corrected some warnings in createPDF.class.php
  * Deleted a debug message if login failed, as it would give too much information to hackers

2009-12-30  Franz Domes <franz.domes@gmx.de>
  * Added set_magic_quotes_runtime(0); to configuration.sample.php and install.php[5].
  * Added function array_lsearch for case insensitive array_search to function.php
  * Changed _connect in Auth.php to do case insensitive table name search as mysql may return table names  in lower case allways

2009-09-26  Franz Domes <franz.domes@gmx.de>
  * Fixed bug in install.php (DB_TABLEPREFIX was not saved)
  * Two bugs fixed in search.class.php and v_Invoice.php and v_Infoletter.php, which prevented sending infoletter and invoices
  * Added function array_change_key(...) to function.php
  * Fixed some bugs in conference module

2009-09-12  Franz Domes <franz.domes@gmx.de>
  * A lot of bug fixes
  * Support for database table prefix. So multiple instances of Clubdata can run in one database
  * New installation routine to simplify use of table prefixes (see INSTALL.TXT how to use it)
  * Adding a new language (Administration -> Database -> Language ) adds also the necessary database columns to all tables
    (Needs ALTER TABLE rights).
    Deleting a language removes database columns !!
  * Changed Database version to 2.01
  * Started Sourcecode documentation of Clubdata using phpdoc (see http://<hostname>/Clubdata2/Documentation/PHPDOC))

2009-07-02  Franz Domes <franz.domes@gmx.de>
  * Beta 1 released

2005-02-20  Franz Domes <domes@frado7>
  * Started Clubdata2 based on Clubdata
