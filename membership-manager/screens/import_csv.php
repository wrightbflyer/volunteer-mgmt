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
        echo "Processing...";


		$file = fopen($_FILES["file"]["tmp_name"], "r") or exit("Unable to open file!");

		//Output a line of the file until the end is reached
		while(!feof($file)){
			$line = fgets($file) ;
			//echo  $line . "<br>";

			//Split on commas in file.
			$pieces = explode(",", $line);

			//Move the pieces into fields
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

            echo ".";

			//Write Out the record to the screen.
			//echo  "-------------------------------------------<br>";

			//echo  $recFirstName . "<br>";
			//echo  $recLastName . "<br>";
			//echo  $recEmail . "<br>";
			//echo  $recAddress . "<br>";
			//echo  $recCity . "<br>";
			//echo  $recState . "<br>";
			//echo  $recZip . "<br>";
			//echo  $recCountry . "<br>";
			//echo  $recPhone . "<br>";
			//echo  $recCell . "<br>";
			//echo  $recMemberSince . "<br>";
			//echo  $recRenewalDate . "<br>";
			//echo  $recMemberType . "<br>";
			//echo  $recId . "<br>";

			//If it is the header record skip it.
			if($recFirstName != "first_name"){

				//Merge the record into the database based on the record Id.
                $colArray = array(
                    'ID' => (isset($recId) && (!empty($recId)))? $recId : 0,
                    'FirstName' => $recFirstName,
                    'LastName' => $recLastName,
                    'MemberType' => $recMemberType,
                    'Address' => $recAddress,
                    'City' => $recCity,
                    'State' => $recState,
                    'Zip' => $recZip,
                    'Country' => $recCountry,
                    'HomePhone' => $recPhone,
                    'MobilePhone' => $recCell,
                    'Email' => $recEmail
                );

                $formats = array('%d','%s','%s',
                    '%s','%s','%s','%s','%s',
                    '%s','%s','%s','%s');

                if(!empty($recRenewalDate)) {
                    array_push($formats,'%s');
                    $colArray['RenewalDate'] = self::db_date($recRenewalDate); 
                }

                if(!empty($recMemberSince)) {
                    array_push($formats,'%s');
                    $colArray['MemberSince'] = self::db_date($recMemberSince); 
                }

                $wpdb->replace(self::$member_table,
                    $colArray, $formats) || die();

			}
        }

			//Close the File.
			fclose($file);

			//Now make sure all of the Membership Types are in the Membership Drop Down.

			$result = $wpdb->query('
				    Insert into wp_wbf_member_type (MemberType)
					SELECT distinct trim(MemberType) 
					FROM wp_wbf_members
					Where MemberType is not null and trim(MemberType) != ""
					and MemberType not in (
					Select MemberType
						 From wp_wbf_member_type
					);
			');

            echo "<br><h2>Load Complete</h2>";

		}

	}

 ?>


<h2>Import CSV</h2>

<form enctype="multipart/form-data"  method="POST">
	<input type="hidden" name="csv_file_upload" id="file_upload" value="true" />

  <label for="file">Choose .csv file to import:</label>
  <input name="file" type="file" /><br />
	<input type="submit" class="button-primary" value="Upload File" />
</form>

<br/>

<p><strong>Importing data is not for the faint of heart.</strong>  Import was
only built only for an initial import, but it <em>might</em> work for bulk updates.
Please read the notes below to understand how this works.</p>

<h3>Column headers required</h3>
<p>The first row of yoru import should include the column headers as follows:</p>
<pre>
"first_name","last_name","email","address","city","state","zip","country","phone","cell","member_since","renewal_date","member_type","id"
</pre>

<h3>Updating or Overwritting data</h3> 
<p>You'll notice that the last column header is "id".  If this column is not populated,
all data will be appended.  That means if you already have 10 members named Joe Smith
and import 10 more named Joe Smith, you'll have 20 members named Joe Smith.</p>

<p>If you would like to try bulk updating, you can populate this last column.
Since we also have the ability to download lists, you can attempt to download that data and reimport.
<strong><em>This use case is not well tested and should be considered risky</em></strong>
</p>


<h3>Sample Data</h3>
<p>Since we've not had a lot of scenarios to validate the import, below is an example
import that we know works well.  If you have data that fails to import, you might
compare to find what causes the problems.</p>

<pre>
first_name","last_name","email","address","city","state","zip","country","phone","cell","member_since","renewal_date","member_type","id"
"STEVE  ","FAST",,"2253 S WOOLER RD","NEW CARLISLE","OH",45344,,"932-128-2320",,"12/1/2012",,"Volunteer",
"Jimmy ""Jim""","Jones","jimmy@emailr.com","7255 S PALMER RD","NEW CARLISLE","OH",45344,,"937-845-3320","937-543-6555","2/1/2012",,"Trustee",
"Rick","Rolled","rickrolled@emailr.com","1969 MUSTANG DR","ONTARIO","CA",91761,,"951-442-2122",,"9/10/2011","9/10/2013","Volunteer",
</pre>


