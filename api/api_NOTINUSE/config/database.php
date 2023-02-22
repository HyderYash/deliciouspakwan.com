<?php
require 'http_response_code.php';
include_once '../../includes/env_constants.php';
/*------------------------------------------------------------------------
 |	Created By 		::	Ram Jee Krishna
 |	Created Date 	::	20-01-2010
 |	Files Purpose	::	This is the class file for database functionality
 |						2.Mysql 5
 |						3.AJAX
 |						4.CSS
 |						5.Bowser Compatibility -> IE,Opera,FireFox
 |  Updated By      ::	Ram Jee Krishna
 |  Updated Date    ::	23-01-2010
 |  Updation Note   ::	Added functions
-------------------------------------------------------------------------+/
*/
/**
 * db.class.php
 *
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 */


class DB {

	public $connection;
	private $databaseName;
	private $host_name;
	private $db_user;
	private $db_pass;

	function __construct()
	{
		//MYSQL Database Settings
 		$this->databaseName = DB_NAME;
 		$this->host_name = DB_HOST;
 		$this->db_user = DB_USER;
 		$this->db_pass = DB_PASS;
		
		//DATABASE connection
		$this->db_connect('mysql');

	}

	function db_connect($what,$mssql_db="")
	{
		if($what == "mysql")
		{
			$this->connection = mysqli_connect($this->host_name,$this->db_user,$this->db_pass) or die("Sorry!! Mysql connection couldn't established.");
			$this->useDB(DB_NAME);
			
		}
		else
		{
			die('NO any database selected');
		}
	}


	// Closes current connection
	function CloseConnection() {
		$Success = TRUE;
		if($this->connection) {
			if(!mysqli_close($this->connection)) {
			   $this->SetErrorMessage("Mysql Connection could not closed.");
			   $Success = FALSE;
			}
		}
		return $Success;
	}
	function SetErrorMessage($msg)
	{
		print $msg;exit;
	}
	function useDB ($dbname="")
	{
		if($dbname != "")
		{
			$this->databaseName = $dbname;
		}
		$this->createDB($dbname);
		mysqli_select_db($this->connection,$this->databaseName) or die("Sorry!! Database couldn't connected.");
	}
	function createDB($dbname="") 
	{

		if($dbname != "")
		{
			$this->databaseName = $dbname;
		}
		$sql = "CREATE DATABASE IF NOT EXISTS `" . $this->databaseName . "`";
		$this->get_sql_exec($sql);
		mysqli_select_db($this->connection,$this->databaseName) or die("Sorry!! Database couldn't connected.");

	}
	function createDefaultTables($sqlfilepath="",$db_prefix="",$table_suffix="")
	{
		if($sqlfilepath !="")
		{
			$file_path = $sqlfilepath;
		}
		else
		{
			$file_path = DB_SQL_PATH;
		}
		$tables_sql = file_get_contents($file_path);
		$tables_sql = str_replace('[DB_PREFIX]',$this->databaseName,$tables_sql);
		if($table_suffix != "")
		{
			$tables_sql = str_replace('[TABLE_SUFFIX]',$table_suffix,$tables_sql);
		}
		$sqlArray = explode(';',$tables_sql);
		$len = count($sqlArray);

		for($i = 0; $i < ($len - 1); $i++)
		{
			$this->get_sql_exec($sqlArray[$i].';');
		}
	}

/***************MYSQL DATABASE FUNCTIONS START HERE *********************/
	function get_real_escape_string($str)
	{
		return mysqli_real_escape_string($this->connection,$str);
	}
	function get_sql_exec($sql_query) {
		$result = mysqli_query($this->connection,$sql_query) or die("Sorry!! Following Query couldn't executed.\n" . $sql_query . "<br>" . mysqli_error($this->connection) . "<br><br>Calling From :: " . $this->traceFunction());
		return $result;
	}
	function traceFunction()
	{
		//$callOrigin = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$funcTrace = '';
		//foreach ($callOrigin as $key => $val)
		//{
			//$funcTrace .= " <== " . $callOrigin[$key]['function'] . "()";
		//}
		//print '<pre>';print_r($callOrigin);die;
		return $funcTrace;
	}
	function get_db_num_rows($pointer)
	{
		return mysqli_num_rows($pointer);
	}

	function get_db_fetch_array($pointer)
	{
	    return mysqli_fetch_array($pointer, MYSQLI_ASSOC);
  	}
	function get_db_fetch_row($pointer)
	{
	    return mysqli_fetch_row($pointer);
  	}
	function insert_records_with_array($table,$fieldvaluearray)
	{
		$newarray = array();
		foreach ($fieldvaluearray as $key => $value)
		{
		   $newarray = array("$key"=>"$value");
		}

		$query = "INSERT INTO ".$table." SET ";
		foreach ($fieldvaluearray as $item => $value)
		{
			$query .= $item . ' = "' . $value . '", ';
		}
		$query = rtrim($query, ', ');
		//print $query . "\n\n\n";//die;
		return $insert_rec= $this->get_sql_exec($query);
		
		//$this->CloseConnection();

	}
	function get_last_insert_id()
	{
		return mysqli_insert_id($this->connection);
	}
	function get_one_record_bak($table, $fields='*', $where='')
	{

		if ($where)
		{
			$where = 'WHERE '. $where;
		}

		$sql_query = 'SELECT '. $fields . ' FROM ' . $table . ' ' . $where;
		//return $sql_query;
		$pointer = $this->get_sql_exec($sql_query);
		$field_value = $this->get_db_fetch_array($pointer);
		//print $sql_query;
		return $field_value;
	}
	function get_one_record($sql_query)
	{
		$pointer = $this->get_sql_exec($sql_query);
		$field_value = $this->get_db_fetch_array($pointer);
		//print $sql_query;
		return $field_value;
	}
	function get_one_record_row($sql_query)
	{
		$pointer = $this->get_sql_exec($sql_query);
		$field_value = $this->get_db_fetch_row($pointer);
		//print $sql_query;
		return $field_value;
	}	
	function delete_records($table, $where='')
	{
		if ($where)
		{
			$where = 'WHERE '. $where;
		}
		$sql_query = 'DELETE FROM ' . $table . ' ' . $where;
		$pointer = $this->get_sql_exec($sql_query);
		return pointer;
	}
	function get_multiple_tables_records($sql)
	{
		if (!$pointer = $this->get_sql_exec($sql))
		{
			return false;
	    }
		if($this->get_db_num_rows($pointer) > 0 )
		{
			$recordset_array = array();
			$serial_no = 1;
			while($row = $this->get_db_fetch_array($pointer))
			{
				$sl_arr = array('sl_no' => $serial_no);
				$new_arr = array_merge($row, $sl_arr);
				$recordset_array[] = $new_arr;
				$serial_no = $serial_no + 1;
			}
			return $recordset_array;
		}
		else
		{
			return false;
		}
	}
	function get_multiple_tables_records_with_fields_list($query){//print $query;die;
		$fieldArr = array();
		$recordset_array = array();
		$result = mysqli_query($this->connection,$query);

		if (!$result) 
		{
			$message = 'ERROR:' . mysqli_error($this->connection);
			return $message;
		}
		else
		{
			$i = 0;
			while ($i < mysqli_num_fields($result))
			{
				$meta = mysqli_fetch_field($result);
				$fieldArr[] = $meta->name;
				$i = $i + 1;
			}
			while ($row = mysqli_fetch_row($result)){
				$recordset_array[] = $row;
			}
			mysqli_free_result($result);
		}
		return array('field' => $fieldArr, 'data' => $recordset_array);
	}

	
	function set_multiple_fields($table, $newfieldvaluepair, $where='')
	{

		if ($where)
		{
			$condition = 'WHERE '. $where . ' ';
		}
		else
		{
			$condition = '';
		}

		$sql = ('UPDATE '.  $table .' SET '. $newfieldvaluepair  .' ' . $condition);
		//print $sql;die;
		return $pointer = $this->get_sql_exec($sql);
	}
/***************MYSQL DATABASE FUNCTIONS END HERE *********************/


}

?>
