<h2>Membership List</h2>
<?php
$clause = self::generateMemberTypeClause();

if(!empty($_GET['ID']) && isset($_GET['delete'])) {
    self::remove_member($wpdb, $_GET['ID']);
}

$members = self::get_members($wpdb, $clause);
self::partial('member_listing',$members);
