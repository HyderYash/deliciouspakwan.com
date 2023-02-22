<?php
	function db_connect()
	{
			$connection = mysql_connect('localhost','local_user','local_pass') or die("Sorry!! Mysql connection couldn't established.");
			mysql_select_db('kodb') or die("Sorry!! Database couldn't connected.");
	}
	function db_disconnect()
	{
		mysql_close($connection);
	}

db_connect();

$str = "DELETE FROM ko_user WHERE ID = '" .  $_POST['id'] . "'";
$ptr = mysql_query($str);
?>