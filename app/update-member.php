<?php 
date_default_timezone_set('America/New_York');

// Connects to your Database 
$db = new mysqli("127.0.0.1", "root", "", "members") or die(mysql_error()); 

$insql = 'INSERT INTO `members`.`members`
          (`Firstname`, `Lastname`, `MembershipType`, `RenewalDate`, `City`, `State`, `Zip`, `Country`, `HomePhone`, `MobilePhone`, `Email`, `id`,
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

$upsql = 'UPDATE `members`.`members`
          SET `Firstname` = ?, `Lastname` = ?, `MembershipType` = ?, `RenewalDate` = ?, `City` = ?, `State` = ?, `Zip` = ?, `Country` = ?, `HomePhone` = ?, `MobilePhone` = ?, `Email` = ? 
          WHERE `id` = ?';

if( isset($_POST["id"]) ) {
  $sql = $upsql;
  $id =  $_POST["id"];
} else {
  $sql = $stmt;
  $id = getGUID();
}

$stmt = $db->prepare($sql);

$stmt->bind_param('ssssssssssss', 
  $_POST["firstname"], 
  $_POST["lastname"], 
  $_POST["membershiptype"], 
  date("Y-m-d H:i:s", strtotime($_POST["renewaldate"])), 
  $_POST["city"], 
  $_POST["state"], 
  $_POST["zip"], 
  $_POST["country"], 
  $_POST["homephone"], 
  $_POST["mobilephone"], 
  $_POST["email"],
  $id);

if( !$stmt->execute() ){ 
  $result = array( 'error' => $db->error );
} else {
  $result = array( 'id' => $id );
}

header("content-type: application/json");
print json_encode($result);


function getGUID(){
  if (function_exists('com_create_guid')){
    return com_create_guid();
  }else{
    mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = substr($charid, 0, 8).$hyphen
      .substr($charid, 8, 4).$hyphen
      .substr($charid,12, 4).$hyphen
      .substr($charid,16, 4).$hyphen
      .substr($charid,20,12);

    return $uuid;
  }
}
?>
