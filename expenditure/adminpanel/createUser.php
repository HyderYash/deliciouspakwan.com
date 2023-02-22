<?Php
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
$str = "INSERT INTO ko_user (`ID`, `FIRST_NAME`, `LAST_NAME`) VALUES (0, '" . $_POST['firstName'] . "','" .  $_POST['lastName'] . "')";
$ptr = mysql_query($str);
?>