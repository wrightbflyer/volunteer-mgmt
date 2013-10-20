<h2>Snail Mail Members</h2>
<?php 
$clause = self::generateMemberTypeClause();
$members = self::get_member_snailmail_list($wpdb, $clause);
self::partial('member_listing',$members);
?>
