<style>
    thead th {padding:5px;text-align:center;}
    tbody tr:nth-of-type(odd) {
        background-color:#ddd;
    }
    tbody td {padding:5px;}
    #download { margin-bottom: 20px; }
</style>

<div id="download">
<a href="<?php echo add_query_arg( array("download"=>"true") ) ?>">Download</a>
</div>

<table>
    <thead>
        <tr>
          <?php echo self::th('Member Name', 'lastname') ?>
          <?php echo self::th('Member Type', 'membertype') ?>
          <?php echo self::th('Home Phone', 'homephone') ?>
          <?php echo self::th('Mobile Phone', 'mobilephone') ?>
          <?php echo self::th('City', 'city') ?>
          <?php echo self::th('State', 'state') ?>
          <?php echo self::th('Zip', 'zip') ?>
          <?php echo self::th('Email', 'email') ?>
          <?php echo self::th('Renewal Date', 'renewaldate') ?>
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
                    <a href="<?php echo add_query_arg( array("id"=>$member->ID), menu_page_url( 'membership-manager-member', false ) ); ?>">
                        <?php echo $member->LastName;?>, <?php echo $member->FirstName;?>
                    </a>
                </td>
                <td><?php echo $member->MemberType;?></td>
                <td><?php echo $member->HomePhone;?></td>
                <td><?php echo $member->MobilePhone;?></td>
                <td><?php echo $member->City;?></td>
                <td><?php echo $member->State;?></td>
                <td><?php echo $member->Zip;?></td>
                <td><?php echo $member->Email;?></td>
                <td><?php echo date("F jS, Y",strtotime($member->RenewalDate));?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
