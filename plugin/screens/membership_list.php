    <h2>Membership List</h2>
    <?php
	$clause = null;
	if (!empty($_POST) && !empty($_POST["membership_type_filter"])) {
		$clause = ' MemberType = "' . $_POST["membership_type_filter"] . '"';
	}
	
    $members = self::get_members($wpdb, $clause);
    self::partial('member_listing',$members);
