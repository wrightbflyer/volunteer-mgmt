    <h2>Membership Renewals</h2>
    <?php
	$clause = null;
	if (!empty($_POST) && !empty($_POST["membership_type_filter"])) {
		$clause = ' MemberType = "' . $_POST["membership_type_filter"] . '"';
	}
    $members = self::get_member_renewal_list($wpdb,$clause);
    self::partial('member_listing',$members);
