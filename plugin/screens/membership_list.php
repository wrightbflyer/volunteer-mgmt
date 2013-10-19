    <h2>Membership Listing</h2>
    <?php
    $members = self::get_members($wpdb);
    self::partial('member_listing',$members);
