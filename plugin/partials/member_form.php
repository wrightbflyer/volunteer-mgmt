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
                <strong>Member '<?php echo $_POST['FirstName'] . " " . $_POST['LastName'];?>' Added</strong>
            </p>
        </div>
        <div>
            <p>
                <a href="?page=membership-manager-membership_list&id=<?php echo $newID;?>">View Record</a>
                &nbsp;
                <a href="?page=membership-manager-membership_list">View Member List</a>
                &nbsp;
                <a href="?page=membership-manager-new_member">Create Another Member</a>
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
                <strong>Member '<?php echo $_POST['FirstName'] . " " . $_POST['LastName'];?>' Updated</strong>
            </p>
        </div>
        <div>
            <p>
                <a href="?page=membership-manager-membership_list&id=<?php echo $newID;?>">View Record</a>
                &nbsp;
                <a href="?page=membership-manager-membership_list">View Member List</a>
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
        form label { display:inline-block;width:100px;font-weight:bold;}
        div.section
        {
            width:30%;
            margin-left:50px;
            margin-top:20px;
            padding:7px 15px 7px 15px;
            border:1px solid #AAA;
            border-radius:4px;
            background-color:#EEE;
        }
        div.section div
        {
            margin-top:3px;
        }
        button { width:100%;height:30px;}
    </style>
    <form method="POST">
        <div class="section">
            <?php
            if (!empty($data) && isset($data->ID) && !empty($data->ID))
            {
                echo '<input type="hidden" name="ID" value="' . $data->ID . '"/>';
            }
            ?>
            <div>
                <label>First Name <span class="req">*</span></label>
                <input type="text" name="FirstName" id="FirstName"/>
            </div>
            <div>
                <label>LastName <span class="req">*</span></label>
                <input type="text" name="LastName" id="LastName"/>
            </div>
            <div>
                <label>Email </label>
                <input type="text" name="Email" id="Email"/>
            </div>
            <div>
                <label>Member Type </label>
                <input type="text" name="MemberType" id="MemberType"/>
            </div>
            <div>
                <label>Renewal Date </label>
                <input type="text" name="RenewalDate" id="RenewalDate"/>
            </div>
            <div>
                <label>Address </label>
                <input type="text" name="Address" id="Address"/>
            </div>
            <div>
                <label>City </label>
                <input type="text" name="City" id="City"/>
            </div>
            <div>
                <label>State </label>
                <input type="text" name="State" id="State"/>
            </div>
            <div>
                <label>Zip </label>
                <input type="text" name="Zip" id="Zip"/>
            </div>
            <div>
                <label>Country </label>
                <input type="text" name="Country" id="Country"/>
            </div>
            <div>
                <label>Home Phone </label>
                <input type="text" name="HomePhone" id="HomePhone"/>
            </div>
            <div>
                <label>Mobile Phone </label>
                <input type="text" name="MobilePhone" id="MobilePhone"/>
            </div>
            <div>
                <label>Member Since </label>
                <input type="text" name="MemberSince" id="MemberSince"/>
            </div>
            <div>
                <button type="submit" id="member_form_submit"></button>
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
