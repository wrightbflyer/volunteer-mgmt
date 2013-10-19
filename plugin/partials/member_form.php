<?php
$show_form = true;

if (!empty($_POST))
{
    /*
    mysql> show columns from wp_wbf_members;
    +-------------+---------------------+------+-----+---------+----------------+
    | Field       | Type                | Null | Key | Default | Extra          |
    +-------------+---------------------+------+-----+---------+----------------+
    | ID          | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
    | FirstName   | varchar(256)        | NO   |     | NULL    |                |
    | LastName    | varchar(256)        | YES  |     | NULL    |                |
    | MemberType  | varchar(64)         | NO   |     | NULL    |                |
    | MemberSince | datetime            | YES  |     | NULL    |                |
    | RenewalDate | datetime            | YES  |     | NULL    |                |
    | Address     | varchar(256)        | YES  |     | NULL    |                |
    | City        | varchar(64)         | YES  |     | NULL    |                |
    | State       | varchar(64)         | YES  |     | NULL    |                |
    | Zip         | varchar(32)         | YES  |     | NULL    |                |
    | Country     | varchar(64)         | YES  |     | NULL    |                |
    | HomePhone   | varchar(32)         | YES  |     | NULL    |                |
    | MobilePhone | varchar(32)         | YES  |     | NULL    |                |
    | Email       | varchar(128)        | YES  |     | NULL    |                |
    +-------------+---------------------+------+-----+---------+----------------+
    */
    
    $result = $wpdb->replace(
        self::$member_table
        ,array(
            'ID' => (isset($_POST['ID']) && (!empty($_POST['ID'])))? $_POST['ID'] : 0,
            'FirstName' => $_POST['FirstName'],
            'LastName' => $_POST['LastName'],
            'MemberType' => $_POST['MemberType'],
            'MemberSince' => self::db_date($_POST['MemberSince']),
            'RenewalDate' => self::db_date($_POST['RenewalDate']),
            'Address' => $_POST['Address'],
            'City' => $_POST['City'],
            'State' => $_POST['State'],
            'Zip' => $_POST['Zip'],
            'Country' => $_POST['Country'],
            'HomePhone' => $_POST['HomePhone'],
            'MobilePhone' => $_POST['MobilePhone'],
            'Email' => $_POST['Email']
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
    $newID = $wpdb->insert_id;
    
    if (($result == 1) && !empty($newID))
    {
        // Record added successfully
        $show_form = false;
        ?>
        <div class="updated settings-error">
            <p>
                <strong>Member <a href="?page=membership-manager-membership_list&id=<?php echo $newID;?>">'<?php echo $_POST['FirstName'] . " " . $_POST['LastName'];?>'</a> Added</strong>
            </p>
        </div>
        <?php
    }
    elseif ($result > 1)
    {
        // Record updated successfully
        $show_form = false;
        ?>
        <div class="updated settings-error">
            <p>
                <strong>Member <a href="?page=membership-manager-membership_list&id=<?php echo $newID;?>">'<?php echo $_POST['FirstName'] . " " . $_POST['LastName'];?>'</a> Updated</strong>
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
        form input {
          width: 200px;
        }
        div.section
        {
            width:30%;
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
    </style>
    <form method="POST">
        <div class="section">
            <?php
            if (!empty($data) && isset($data->ID) && !empty($data->ID))
            {
                echo '<input type="hidden" name="ID" value="' . $data->ID . '"/>';
            }
            ?>
            <?php echo self::text_editor_for("FirstName", "First Name",  array("required" => true)) ?>
            <?php echo self::text_editor_for("LastName", "Last Name",  array("required" => true)) ?>
            <?php echo self::text_editor_for("Email", "Email") ?>
            <div>
                <label>Member Type </label>
                <input type="text" name="MemberType" id="MemberType"/>
            </div>
            <?php echo self::text_editor_for("RenewalDate", "Renewal Date") ?>
            <?php echo self::text_editor_for("Address", "Address") ?>
            <?php echo self::text_editor_for("City", "City") ?>
            <?php echo self::text_editor_for("State", "State") ?>
            <?php echo self::text_editor_for("Zip", "Zip") ?>
            <?php echo self::text_editor_for("Country", "Country") ?>
            <?php echo self::text_editor_for("HomePhone", "Home Phone") ?>
            <?php echo self::text_editor_for("MobilePhone", "Mobile Phone") ?>
            <?php echo self::text_editor_for("MemberSince", "Member Since") ?>
            <div>
                <button style="float: right" type="submit" id="member_form_submit"></button>
            </div>
        </div>
    </form>
     <script>
        jQuery(document).ready(function($) {
            jQuery( "#MemberSince" ).datepicker();
            jQuery( "#RenewalDate" ).datepicker();
            jQuery( "#MemberSince" ).val(<?php echo json_encode(date("m/d/Y"));?>);
            <?php
            if (!empty($data))
            {
                foreach($data as $k => $v)
                {
                    if ($k == 'ID') continue;
                    ?> 
                    jQuery(<?php echo json_encode("#$k");?>).val(<?php echo json_encode($v);?>);
                    <?php
                }
            }
            ?>
        });
    </script>
    <?php
}
