<h2>Snail Mail Members</h2>
<?php 
$members = self::get_member_snailmail_list($wpdb);
self::partial('member_listing',$members);
?>
