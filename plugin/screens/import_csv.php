<?php

function removeQuotes($value){
	if( $value == null){
		return null;
	}

	if( strlen($value) < 3 ){
		return $value;
	}

	if(substr($value,0,1) == "\"" && substr($value,strlen($value) -1 ,1) == "\""){
		return substr($value,1,strlen($value) -2);
	}
	return $value;
}

if(isset($_FILES["file"])) {

	 if ($_FILES["file"]["error"] > 0){
	 	echo "Error: " . $_FILES["file"]["error"] . "<br>";
	 }else{
	 	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	 	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	 	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	 	echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>" ;


		$file = fopen($_FILES["file"]["tmp_name"], "r") or exit("Unable to open file!");

		//Output a line of the file until the end is reached
		while(!feof($file)){
			$line = fgets($file) ;
			//echo  $line . "<br>";
			$pieces = explode(",", $line);

			$arrlength=count($pieces);


			$recFirstName = removeQuotes($pieces[0]);
			$recLastName = removeQuotes($pieces[1]);
			$recEmail = removeQuotes($pieces[2]);
			$recAddress = removeQuotes($pieces[3]);
			$recCity= removeQuotes($pieces[4]);
			$recState = removeQuotes($pieces[5]);
			$recZip = removeQuotes($pieces[6]);
			$recCountry= removeQuotes($pieces[7]);
			$recPhone = removeQuotes($pieces[8]);
			$recCell = removeQuotes($pieces[9]);
			$recMemberSince = removeQuotes($pieces[10]);
			$recRenewalDate = removeQuotes($pieces[11]);
			$recMemberType = removeQuotes($pieces[12]);
			$recId = removeQuotes($pieces[13]);


			echo  "-------------------------------------------<br>";

			echo  $recFirstName . "<br>";
			echo  $recLastName . "<br>";
			echo  $recEmail . "<br>";
			echo  $recAddress . "<br>";
			echo  $recCity . "<br>";
			echo  $recState . "<br>";
			echo  $recZip . "<br>";
			echo  $recCountry . "<br>";
			echo  $recPhone . "<br>";
			echo  $recCell . "<br>";
			echo  $recMemberSince . "<br>";
			echo  $recRenewalDate . "<br>";
			echo  $recMemberType . "<br>";
			echo  $recId . "<br>";

			if($recFirstName != "first_name"){
				$result = $wpdb->replace(
						self::$member_table
						,array(
							'ID' => (isset($recId) && (!empty($recId)))? $recId : 0,
							'FirstName' => $recFirstName,
							'LastName' => $recLastName,
							'MemberType' => $recMemberType,
							'MemberSince' => self::db_date($recMemberSince),
							'RenewalDate' => self::db_date($recRenewalDate),
							'Address' => $recAddress,
							'City' => $recCity,
							'State' => $recState,
							'Zip' => $recZip,
							'Country' => $recCountry,
							'HomePhone' => $recPhone,
							'MobilePhone' => $recCell,
							'Email' => $recEmail
						)
						,array(
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s'
						)
					);
				}
			}
			fclose($file);
		}

	}

 ?>




<form enctype="multipart/form-data"  method="POST">
	<input type="hidden" name="csv_file_upload" id="file_upload" value="true" />
	Choose .csv file to import: <input name="file" type="file" /><br />
	<input type="submit" class="button-primary" value="Upload File" />
</form>
