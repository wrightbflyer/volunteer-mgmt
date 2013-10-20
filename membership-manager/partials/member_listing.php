<style>
    thead th {padding:5px;text-align:center;}
    tbody tr:nth-of-type(odd) {
        background-color:#F4F4F4;
    }
    tbody td {padding:5px;}
    #download { margin-bottom: 20px; }
</style>

<div id="download">
	<div style="float:left">
		<a class="button button-hero" href="javascript:downloadCSV();">Download CSV</a>
	</div>
	<div class="metabox-holder postbox-container" style="margin-left:25px;padding-top:0px;">
		<div class="postbox" style="margin-bottom:0px;">
			<div class="inside">
				<form method="POST" id="member_list_form">
					<input type="hidden" name="sort" id="sort" value="<?php if(!empty($_POST) && !empty($_POST["sort"])) { echo $_POST["sort"]; } ?>" />
					<input type="hidden" name="downloadcsv" id="downloadcsv" />
					
					Showing <?php echo $wpdb->num_rows; ?> results.
					Filter by Membership Type: <select id="membership_type_filter" name="membership_type_filter">
						<option value="">- Select -</option>
					<?php 
					$member_types = self::get_member_types($wpdb);			
					foreach($member_types as $mt) { ?>
						<option value="<?php echo $mt->MemberType ?>" <?php if (!empty($_POST) && !empty($_POST["membership_type_filter"]) && $_POST["membership_type_filter"] == $mt->MemberType) { echo "selected=selected"; } ?>><?php echo $mt->MemberType ?></option>
						<?php
					} ?>
				</select>
				</form>
			</div>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>

<table class="wp-list-table widefat">
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
                        [FlightDate] => 2013-10-09 00:00:00
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
                    <a href="<?php echo add_query_arg( array("id"=>$member->ID), menu_page_url( 'membership-manager-new_member', false ) ); ?>">
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
                <td><?php if(!empty($member->RenewalDate)) { 
                    echo date("F jS, Y",strtotime($member->RenewalDate));} ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
	jQuery('document').ready(function(){
		jQuery('#membership_type_filter').change(function(){
			jQuery('#downloadcsv').val('');
			jQuery(this).closest('form').submit();
		});
	});
	
	function setSort(sortField) {
		jQuery('#sort').val(sortField);
		jQuery('#downloadcsv').val('');
		jQuery('#member_list_form').submit();
	}
	
	function downloadCSV() {
		jQuery('#downloadcsv').val('1');
		jQuery('#member_list_form').submit();
	}
</script>
