    <h2>Membership List</h2>
    <?php
    $members = self::get_members($wpdb);
    self::partial('member_listing',$members);
