    <h1>Membership Manager</h1>
    <h2>Membership Listing - Renewals</h2>
    <?php
    // Calculate dates for start and end of this month
    $startDate = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),1,date("Y")));
    $endDate = date("Y-m-d H:i:s",mktime(0,0,-1,date("m")+1,1,date("Y")));
    
    $where = "RenewalDate < '$endDate'";
    $members = self::get_members($wpdb, $where);
    self::partial('member_listing',$members);
