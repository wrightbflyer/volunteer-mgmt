<h1>Membership Manager</h1>
<h2>Membership Listing - Snail Mail</h2>
<?php 
$members = self::get_member_snailmail_list($wpdb);
self::partial('member_listing',$members);
?>
