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
$str = "select * from ko_user";
$result = mysql_query($str);
$my_array = array();
while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
    $my_array[] = $row;
}	
$data = $my_array;
echo json_encode($data);
?>

