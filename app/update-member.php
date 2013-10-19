<?php 
date_default_timezone_set('America/New_York');

$member = json_decode($HTTP_RAW_POST_DATA);

// Connects to your Database 
$db = new mysqli("127.0.0.1", "root", "", "members") or die(mysql_error()); 

$insql = 'INSERT INTO `members`.`members`
          (`Firstname`, `Lastname`, `MembershipType`, `RenewalDate`, `City`, `State`, `Zip`, `Country`, `HomePhone`, `MobilePhone`, `Email`, `id`)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

$upsql = 'UPDATE `members`.`members`
          SET `Firstname` = ?, `Lastname` = ?, `MembershipType` = ?, `RenewalDate` = ?, `City` = ?, `State` = ?, `Zip` = ?, `Country` = ?, `HomePhone` = ?, `MobilePhone` = ?, `Email` = ? 
          WHERE `id` = ?';

$sql = "";
if( isset($member->{"id"}) ) {
  $sql = $upsql;
  $id =  $member->{"id"};
} else {
  $sql = $insql;
  $id = getGUID();
}

$stmt = $db->prepare($sql);

$stmt->bind_param('ssssssssssss', 
  $member->{"firstname"}, 
  $member->{"lastname"}, 
  $member->{"membershiptype"}, 
  date("Y-m-d H:i:s", strtotime($member->{"renewaldate"})), 
  $member->{"city"}, 
  $member->{"state"}, 
  $member->{"zip"}, 
  $member->{"country"}, 
  $member->{"homephone"}, 
  $member->{"mobilephone"}, 
  $member->{"email"},
  $id);

if( !$stmt->execute() ){ 
  header('HTTP/1.1 500 Internal Server Error');
  print $db->error;
} else {
  header("content-type: application/json");
  $result = array( 'id' => $id );
  print json_encode($result);
}

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
