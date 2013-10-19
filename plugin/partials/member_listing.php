<style>
    thead th {padding:5px;text-align:center;}
    tbody tr:nth-of-type(odd) {
        background-color:#ddd;
    }
    tbody td {padding:5px;}
</style>
<table>
    <thead>
        <tr>
            <th>Member Name</th>
            <th>Member Type</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Renewal Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($data as $member)
        {
            /*
             $member => stdClass Object
                    (
                        [ID] => 1
                        [FirstName] => asdf
                        [LastName] => asdf
                        [MemberType] => asdf
                        [MemberSince] => 2013-10-23 00:00:00
                        [RenewalDate] => 2013-10-09 00:00:00
                        [Address] => asdf
                        [City] => asdf
                        [State] => asdf
                        [Zip] => asdf
                        [Country] => asdf
                        [HomePhone] => asdf
                        [MobilePhone] => asdf
                        [Email] => asdf
                    )
            */
            ?>
            <tr>
                <td>
                    <a href="<?php echo add_query_arg( 'id', $member->ID );?>">
                        <?php echo $member->LastName;?>, <?php echo $member->FirstName;?>
                    </a>
                </td>
                <td><?php echo $member->MemberType;?></td>
                <td><?php echo $member->Email;?></td>
                <td><?php echo $member->HomePhone;?></td>
                <td><?php echo date("F jS, Y",strtotime($member->RenewalDate));?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>