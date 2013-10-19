
<html>
  <head>
    <title>Huzzah!</title>
<script src="scripts/jquery-1.10.2.min.js"></script>
<script>
$(function() {

  $("#member-form").submit( function(event) {
    event.preventDefault();

    var form = $(this),
        url = form.attr("action");

    var member = {
      id: null,
      firstname: $("#firstname").val(),
      lastname: $("#lastname").val(),
      membershiptype: $("#membershiptype").val(),
      renewaldate: $("#renewaldate").val(),
      city: $("#city").val(),
      state: $("#state").val(),
      zip: $("#zip").val(),
      country: $("#country").val(),
      homephone: $("#homephone").val(),
      mobilephone: $("#mobilephone").val(),
      email: $("#email").val(),
    };

    console.log(member);

    $.ajax(url, {
      'data': JSON.stringify(member),
      'type': 'POST',
      'processData': false,
      'contentType': 'application/json'
    })
    .done( function() {
      console.log("yeah");
    })
      .fail( function(err) {
        alert("crap: " + err.responseText);
      });

  });
});
</script>
  </head>
  <body>

<h2>Add new member</h2>
<form action="update-member.php" id="member-form">
<table class="form-table">
  <tbody>
<?php
$fields = array(
  'firstname' => array("required" => true, "label" => "First name", "class" => ""),
  'lastname' => array("required" => true, "label" => "Last name", "class" => ""), 
  'membershiptype' => array("required" => true, "label" => "Membership type", "class" => ""),
  'renewaldate' => array("required" => true, "label" => "Renewal Date", "class" => "date"),
  'city' => array("required" => true, "label" => "City", "class" => ""),
  'state' => array("required" => true, "label" => "State (OH)", "class" => "state"),
  'zip' => array("required" => true, "label" => "Zip", "class" => ""),
  'country' => array("required" => true, "label" => "Country", "class" => ""),
  'homephone' => array("required" => true, "label" => "Home phone", "class" => "phone"),
  'mobilephone' => array("required" => true, "label" => "Mobile phone", "class" => "phone"),
  'email' => array("required" => true, "label" => "Email", "class" => "email") 
);

foreach( $fields as $key => $value ) {
?>

    <tr class="form-field">
      <th scope="row"><label for="<?php echo $key?>"><?php echo $value["label"]?></label></th>
      <td><input name="<?php echo $key?>" type="text" id="<?php echo $key?>" value="" class="<?php echo $value["class"]?>" aria-required="true"></td>
    </tr>
    <?php } ?>

  </tbody></table>

  <p class="submit"><input type="submit" name="savemember" id="savemember" class="button button-primary" value="Save member"></p>

</form>


<h2>Members</h2>
  </body>
</html>

