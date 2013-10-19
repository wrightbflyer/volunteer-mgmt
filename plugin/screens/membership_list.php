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
    <h2>Membership Listing</h2>
    <?php
    $orderBy = isset($_GET["sort"]) ? $_GET["sort"] : "LastName";
    $sql = "SELECT * FROM " . self::$table . " ORDER BY $orderBy";
    $members = $wpdb->get_results($sql);
    self::partial('member_listing',$members);
}
