<h1>Membership Manager</h1>
<?php
if (!empty($_REQUEST['id']))
{
    ?>
    <h2>View / Edit Member</h2>
    <?php
    $sql = "SELECT * FROM " . self::$table . " WHERE ID=" . self::db_number($_REQUEST['id']);
    $member = $wpdb->get_row($sql);
    $member->RenewalDate = date("m/d/Y",strtotime($member->RenewalDate));
    $member->MemberSince = date("m/d/Y",strtotime($member->MemberSince));
    self::partial('member_form',$member);
    ?>
    <script>jQuery(document).ready(function($) { jQuery('#member_form_submit').html('Update Member'); });</script>
    <?php
}
else
{
    ?>
    <h2>Membership Listing - Renewals</h2>
    <?php
    // Calculate dates for start and end of this month
    $startDate = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),1,date("Y")));
    $endDate = date("Y-m-d H:i:s",mktime(0,0,-1,date("m")+1,1,date("Y")));
    
    $sql = "SELECT * FROM " . self::$table . " WHERE RenewalDate BETWEEN '$startDate' AND '$endDate' ORDER BY LastName";
    $members = $wpdb->get_results($sql);
    self::partial('member_listing',$members);
}

