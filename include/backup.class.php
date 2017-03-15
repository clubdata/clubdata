<?php
/*
 function.php: Central include-file with a lot of function definitions
 Copyright (C) 2003 Franz Domes

 This code derived from some work of
 (c) KLIK! Klein Informatik  www.klik-info.ch  rklein@mus.ch

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 */
/**
 * @package Clubdata
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class Backup
 *
 * @package Clubdata
 */
class Backup
{
	var $db;

	var $path = './backups/';
	var $filetype = 'txt';
	var $filetype2 = 'sql';

	var $compressions = array ('' => array('Content-Type' => 'application/octet-stream',
                                                'compFunc' => '',
                                                'extension' => ''),
                               'GZIP' => array('Content-Type' => 'application/x-gzip',
                                                'compFunc' => 'gzencode',
                                                'extension' => '.gz'),
                               'BZIP' => array('Content-Type' => 'application/x-bzip',
                                                'compFunc' => 'bzcompress',
                                                'extension' => '.bz'),
	);
	function Backup($db, $serverPath = null)
	{
		$this->db = $db;
		
		if ( !is_null($serverPath) )
		{
			$this->path = $serverPath;
		}
		if ( substr($this->path,-1) != '/' && substr($this->path,-1) != '\\')
		{
			$this->path .= "/";
		}
		
		print("BACKUPPFAD: $this->path");
	}

	function get_def($table) {
		$def = '';
		$def .= "DROP TABLE IF EXISTS `$table`;\n";
		$def .= "CREATE TABLE `$table` (\n";
		$result = $this->db->Execute('SHOW FIELDS FROM '.$table);
		while($row = $result->FetchRow()) {
			$def .= '    `'.$row['Field'].'` '.$row['Type'];
			if ($row['Default'] != '') $def .= ' DEFAULT "'.$row['Default'].'"';
			if ($row['Null'] != 'YES') $def .= ' NOT NULL';
			if ($row['Extra'] != '') $def .= ' '.$row['Extra'];
			$def .= ",\n";
		}
		$def = ereg_replace(",\n$",'', $def);
		$result = $this->db->Execute('SHOW KEYS FROM '.$table);
		while($row = $result->FetchRow()) {
			$kname=$row['Key_name'];
			if(($kname != 'PRIMARY') && ($row['Non_unique'] == 0)) $kname='UNIQUE|'.$kname;
			if($row['Index_type'] == 'FULLTEXT') $kname='FULLTEXT|'.$kname;
			if(!isset($index[$kname])) $index[$kname] = array();
			$index[$kname][] = $row['Column_name'];
		}
		while(list($x, $columns) = @each($index)) {
			$def .= ",\n";
			if($x == 'PRIMARY') $def .= '   PRIMARY KEY (`'.implode($columns, '`, `') . '`)';
			else if (substr($x,0,6) == 'UNIQUE') $def .= '   UNIQUE KEY `'.substr($x,7).'` (`' . implode($columns, '`, `') . '`)';
			else if (substr($x,0,8) == 'FULLTEXT') $def .= '   FULLTEXT KEY `'.substr($x,9).'` (`' . implode($columns, '`, `') . '`)';
			else $def .= '   KEY `'.$x.'` (`' . implode($columns, '`, `') . '`)';
		}
		$def .= "\n) ";
		$result = $this->db->Execute('SHOW TABLE STATUS LIKE \''.$table . '\'');
		if ( $result === false )
		{
			print ("ERROR: " . $this->db->ErrorMsg());
		}
		while($row = $result->FetchRow()) {
			$commentTxt = (!empty($row['Comment'])) ? " COMMENT='$row[Comment]'" : '';
			$autoInrTxt = (!empty($row['Auto_increment'])) ? " AUTO_INCREMENT=$row[Auto_increment]" : '';
			$def .= "TYPE=$row[Type]$commentTxt$autoInrTxt ;";
		}
		return (stripslashes($def));
	}

	function get_content($table) {
		$content='';
		$result = $this->db->Execute('SELECT * FROM '.$table);
		while($row = $result->FetchRow()) {
			$insert = 'INSERT INTO `' . $table . '` VALUES (';
			for($j=0; $j<$result->FieldCount();$j++) {
				if(!isset($row[$j])) $insert .= 'NULL,';
				else if($row[$j] != '') $insert .= '"'.addslashes($row[$j]).'",';
				else $insert .= '"",';
			}
			$insert = ereg_replace(",$",'',$insert);
			$insert .= ");\n";
			$content .= $insert;
		}
		return $content;
	}

	function generateBackup($compression)
	{
		$newfile = '';
		$cur_time=date('d.m.Y H:i');
		$newfile.="#----------------------------------------------\n";
		$newfile.="# " . lang('Backup of database') . ' ' .$this->db->database."\n";
		$newfile.="# " . lang('Created') . ' ' .$cur_time."\n";
		$newfile.="#----------------------------------------------\n\n\n";

		$tables = $this->db->MetaTables('TABLES');
		$tables = array_filter($tables, create_function('$a', 'return !strncmp($a,DB_TABLEPREFIX, strlen(DB_TABLEPREFIX));'));
		// print 'return !strncmp($a,"'.DB_TABLEPREFIX.'", strlen("'.DB_TABLEPREFIX.'"))';
		$num_tables = count($tables);
		$i = 0;
		foreach ($tables as $table)
		{
			$newfile .= "\n# ----------------------------------------------------------\n#\n";
			$newfile .= "# " . lang('Structure of table') . " '$table'\n#\n";
			$newfile .= $this->get_def($table);
			$newfile .= "\n\n";
			$newfile .= "#\n# " . lang('Data of table') . " '$table'\n#\n";
			$newfile .= $this->get_content($table);
			$newfile .= "\n\n";
			//}
			$i++;
		}

		if ( !empty($this->compressions[$compression]['compFunc'] ) )
		{
			return $this->compressions[$compression]['compFunc']($newfile);
		}
		else
		{
			return $newfile;
		}
	}

	function doBackupFile($file = 'Backup', $compression = '')
	{
		$newfile = $this->generateBackup($compression);
		$datei = $this->path.date('Ymd'). "$backup.{$this->filetype}{$this->compressions[$compression][extension]}";
		$datei_neu = $this->path.date('Ymd'). '.' . $this->db->database . ".$file.{$this->filetype2}{$this->compressions[$compression][extension]}";
		$fp = fopen ($datei,'w');
		fwrite ($fp,$newfile);
		fclose ($fp);
		@rename($datei,$datei_neu);
		clearstatcache();
		chmod ($datei_neu, 0777);
		clearstatcache();
	}

	function doBackupSend($file = 'Backup', $compression = '')
	{
		$newfile = $this->generateBackup($compression);

		$datei = $path.date('Ymd'). '.' . $this->db->database . ".$file.{$this->filetype2}{$this->compressions[$compression][extension]}";

		$tmpOutput = ob_get_contents();
		ob_end_clean();

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Content-Disposition: attachment; filename=$datei");
		header("Content-Type: " . $this->compressions[$compression]['Content-Type']);
		print $newfile;

		if ( $tmpOutput !== false )
		{
			ob_start();
			echo $tmpOutput;
		}
	}

	function doBackupShow($file = 'Backup')
	{
		$newfile = $this->generateBackup('');

		//print "<PRE>\n" . $newfile . "\n<PRE>";
		return $newfile;
	}
}