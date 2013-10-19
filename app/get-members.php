<?php 
 // Connects to your Database 
 mysql_connect("127.0.0.1", "root", "") or die(mysql_error()); 
 mysql_select_db("members") or die(mysql_error()); 
 $data = mysql_query("SELECT * FROM members") 
 or die(mysql_error()); 

 $results = array();

 while($info = mysql_fetch_array( $data )) 
 { 
   $results[] = array(
           'id' => $info['id'],
           'firstname' => $info['Firstname'],
           'lastname' => $info['Lastname']
         );
 } 


 header("content-type: application/json");
 print json_encode($results);
?>
