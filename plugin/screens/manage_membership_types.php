<h1>Membership Manager</h1>
<h2>Manage Membership Types</h2>

<?php
$members = self::get_member_types($wpdb);
self::partial('member_listing',$members);
?>