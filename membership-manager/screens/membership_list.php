    <h2>Membership List</h2>
    <?php
	$clause = null;
	if (!empty($_POST) && !empty($_POST["membership_type_filter"])) {
		$clause = ' MemberType = "' . $_POST["membership_type_filter"] . '"';
	}

    if(!empty($_GET['ID']) && isset($_GET['delete'])) {
        self::remove_member($wpdb, $_GET['ID']);
    }
	
    $members = self::get_members($wpdb, $clause);
    self::partial('member_listing',$members);
