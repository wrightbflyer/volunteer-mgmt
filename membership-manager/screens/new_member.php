<?php
if (!empty($_REQUEST['id']))
{
    ?>
    <h2>View / Edit Member</h2>
    <?php
    $sql = "SELECT * FROM " . self::$member_table . " WHERE ID=" . self::db_number($_REQUEST['id']);
    $member = $wpdb->get_row($sql);
    if(!empty($member->RenewalDate)) {
        $member->RenewalDate = date("m/d/Y",strtotime($member->RenewalDate));
    }
    if(!empty($member->MemberSince)) {
        $member->MemberSince = date("m/d/Y",strtotime($member->MemberSince));
    }
    if(!empty($member->FlightDate)) {
        $member->FlightDate = date("m/d/Y",strtotime($member->FlightDate));
    }
    self::partial('member_form',$member);
    ?>
    <script>jQuery(document).ready(function($) { jQuery('#member_form_submit').html('Update Member'); });</script>
    <?php
} else { ?>
<h2>Add a New Member</h2>
<?php self::partial('member_form',$_POST); ?>
<script>jQuery(document).ready(function($) { jQuery('#member_form_submit').html('Add New Member'); });</script>
<?php } ?>
