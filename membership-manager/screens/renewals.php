<h2>Membership Renewals</h2>
<?php
$clause = self::generateMemberTypeClause();
$members = self::get_member_renewal_list($wpdb,$clause);
self::partial('member_listing',$members);
