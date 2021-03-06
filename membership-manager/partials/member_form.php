<?php
$show_form = true;

if (!empty($_POST))
{
	function cleanQuotes($value) {
		if (empty($value)) {
			return $value;
		}
		return str_replace("\\\"", "\"", $value);
	}
    $colArray = array(
            'ID' => (isset($_POST['ID']) && (!empty($_POST['ID'])))? $_POST['ID'] : 0,
            'FirstName' => cleanQuotes($_POST['FirstName']),
            'LastName' => cleanQuotes($_POST['LastName']),
            'MemberType' => $_POST['MemberType'],
            'Address' => $_POST['Address'],
            'City' => $_POST['City'],
            'State' => $_POST['State'],
            'Zip' => $_POST['Zip'],
            'Country' => $_POST['Country'],
            'HomePhone' => $_POST['HomePhone'],
            'MobilePhone' => $_POST['MobilePhone'],
            'Email' => $_POST['Email']
        );

    $formats = array('%d','%s','%s',
        '%s','%s','%s','%s','%s',
        '%s','%s','%s','%s');

    if(!empty($_POST['RenewalDate'])) {
        array_push($formats,'%s');
        $colArray['RenewalDate'] = self::db_date($_POST['RenewalDate']); 
    }

    if(!empty($_POST['MemberSince'])) {
        array_push($formats,'%s');
        $colArray['MemberSince'] = self::db_date($_POST['MemberSince']); 
    }

    if(!empty($_POST['FlightDate'])) {
        array_push($formats,'%s');
        $colArray['FlightDate'] = self::db_date($_POST['FlightDate']); 
    }

    $result = $wpdb->replace(
        self::$member_table, $colArray, $formats );

    $newID = $wpdb->insert_id;
    
    if ($result !== false)
    {
        // Record updated successfully
        $show_form = false;
        ?>
        <div class="updated settings-error">
            <p>
                <strong>Member <a href="?page=membership-manager-new_member&id=<?php echo $newID;?>">'<?php echo cleanQuotes($_POST['FirstName']) . " " . cleanQuotes($_POST['LastName']);?>'</a> Saved</strong>
            </p>
        </div>
        <?php
    }
    else
    {
        $show_form = true;
        ?>
        <div class="error settings-error">
            <p>
                <strong>Database Error - please check input</strong>
            </p>
        </div>
        <?php
    }
}


if ($show_form == true)
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    ?>
    <style>
        form label 
        { 
          display: inline-block;
          width: 100px;
          text-align: right;
          margin-right: 10px;
        }
        form label .req {
          color: red;
        }
        form input {
          width: 200px;
        }
        div.section
        {
            margin-left:50px;
            margin-top:20px;
            padding:7px 15px 7px 15px;
            border-radius:4px;
        }
        div.section div
        {
            margin-top:10px;
        }
        button { height:30px;}
        #error-div {
            margin-left: 50px;
            border: thin solid red;
            width: 30%;
            border-radius: 6px;
            background-color: #ffff66;
            display: none;
        }
        #errors {
           margin-left: 25px;
        }
        #errors div.error-item {
        }
        #deleteLink {
            margin-left: 25px;
        }
    </style>
    <div id="error-div">
        <ul id="errors">
        </ul>
    </div>
    <form id="member_form" method="POST">
        <div class="section">
            <?php
            if (!empty($data) && !empty($data->ID))
            {
                echo '<input type="hidden" name="ID" value="' . $data->ID . '"/>';
            }
            ?>
            <?php echo self::text_editor_for("FirstName", "First Name",  array("required" => true)) ?>
            <?php echo self::text_editor_for("LastName", "Last Name",  array("required" => true)) ?>
            <?php echo self::text_editor_for("Email", "Email") ?>
            <div>
                <label for="MemberType">Member Type
                    <span class="req">*</span>
                </label>
                <select id="MemberType" name="MemberType">
                    <option value="">Please choose a level</option>
                    <?php foreach ( self::get_member_types($wpdb) as $member_type ) { ?>
                    <option value="<?php echo $member_type->MemberType ?>"><?php echo $member_type->MemberType ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php echo self::text_editor_for("RenewalDate", "Renewal Date") ?>
            <?php echo self::text_editor_for("FlightDate", "Flight Date") ?>
            <?php echo self::text_editor_for("Address", "Address") ?>
            <?php echo self::text_editor_for("City", "City") ?>
            <div>
                <label for="State">State</label>
                <select id="State" name="State">
                    <option value="">--</option>
                    <option value="AL">AL</option>
                    <option value="AK">AK</option>
                    <option value="AZ">AZ</option>
                    <option value="AR">AR</option>
                    <option value="CA">CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DE">DE</option>
                    <option value="DC">DC</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="HI">HI</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="IA">IA</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="ME">ME</option>
                    <option value="MD">MD</option>
                    <option value="MA">MA</option>
                    <option value="MI">MI</option>
                    <option value="MN">MN</option>
                    <option value="MS">MS</option>
                    <option value="MO">MO</option>
                    <option value="MT">MT</option>
                    <option value="NE">NE</option>
                    <option value="NV">NV</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NY">NY</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VT">VT</option>
                    <option value="VA">VA</option>
                    <option value="WA">WA</option>
                    <option value="WV">WV</option>
                    <option value="WI">WI</option>
                    <option value="WY">WY</option>
                </select>
            </div>
            <?php echo self::text_editor_for("Zip", "Zip", array("max" => 10)) ?>
            <?php echo self::text_editor_for("Country", "Country") ?>
            <?php echo self::text_editor_for("HomePhone", "Home Phone", array("max" => 32)) ?>
            <?php echo self::text_editor_for("MobilePhone", "Mobile Phone", array("max" => 32)) ?>
            <?php echo self::text_editor_for("MemberSince", "Member Since") ?>
            <div>
                <a href="admin.php?page=membership-manager-membership_list">cancel</a>
<?php if (!empty($data) && !empty($data->ID)) { ?>
                <a id="deleteLink" href="admin.php?page=membership-manager-membership_list&delete&ID=<?php echo $data->ID ?>">delete</a>
<?php } ?>
                <button style="float: right" type="submit" id="member_form_submit"></button>
            </div>
        </div>
    </form>
     <script>
        jQuery(function($) {
            $( "#MemberSince" ).datepicker();
            $( "#RenewalDate" ).datepicker();
            $( "#FlightDate" ).datepicker();
            <?php
            if (!empty($data))
            {
                foreach($data as $k => $v)
                {
                    if ($k == 'ID' || empty($v)) continue;
                    ?> 
                    $(<?php echo json_encode("#$k");?>).val(<?php echo json_encode($v);?>);
                    <?php
                }
            }
            ?>

            $("#deleteLink").click(function() {
               return window.confirm("Do you really want to delete this user?"); 
            });
            
            var errDiv= $("#error-div");
            var errors = $("#errors");

            if(errors) {
                function error(error) {
                    errors.append("<li class='error-item'>"+error+"</li>");
                }

                $("#member_form").submit(function() {
                    // Add form validation here.
                    var submit = true;
                    errDiv.hide();
                    errors.empty();

                    var field = $("#FirstName");
                    if(field && !field.val()) {
                        error("Please enter 'First Name'");
                        submit = false;
                    }

                    field = $("#LastName");
                    if(field && !field.val()) {
                        error("Please enter 'Last Name'");
                        submit = false;
                    }

                    field = $("#MemberType");
                    if(field && !field.val()) {
                        error("Please enter 'Member Type'");
                        submit = false;
                    }

                    submit || 
                        errDiv.fadeIn() && $("html").scrollTop(50);

                    return submit;
                });
            }
        });
    </script>
    <?php
}
