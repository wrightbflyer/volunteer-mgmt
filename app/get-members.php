<?php 

$db = new mysqli("127.0.0.1", "root", "", "members") or die(mysql_error()); 

$data = $db->query("SELECT * FROM members") or die(mysql_error()); 

$results = array();

while($info = $data->fetch_assoc()) 
{ 
  $results[] = $info;
} 

header("content-type: application/json");
print json_encode($results);

?>
