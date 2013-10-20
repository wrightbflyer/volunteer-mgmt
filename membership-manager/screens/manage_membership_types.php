<h2>Manage Membership Types</h2>
<?php 
if (!empty($_POST))
{
	function update_members($newType)
	{
		global $wpdb;
		$wpdb->update(
			WBF_Membership::$member_table
			,array(
				'MemberType' => $newType
			)
			,array(
				'MemberType' => $_POST['OriginalMemberType']
			)
			,array(
				'%s'
			)
			,array(
				'%s'
			)
		);
	}

	$result = -1;
	if (!empty($_POST['member_types_delete_mode']) && $_POST['member_types_delete_mode'] == 'delete')
	{
		// delete
		$formAction = "Deleted";
	
		update_members($_POST['replacementMemberType']);
		
		// delete the membership type
		$result = $wpdb->delete(
			self::$member_type_table
			,array(
				'MemberType' => $_POST['OriginalMemberType']
			)
			,array(
				'%s'
			)
		);
	}
	elseif (!empty($_POST['OriginalMemberType']))
	{
		// update
		$formAction = "Updated";
	
		// process database update
		$result = $wpdb->update(
			self::$member_type_table
			,array(
				'MemberType' => $_POST['MemberType']//,
				//'idx' => self::db_number($_POST['idx'])
			)
			,array(
				'MemberType' => $_POST['OriginalMemberType']
			)
			,array(
				'%s',
				'%d'
			)
			,array(
				'%s'
			)
		);
		
		if ($_POST['OriginalMemberType'] != $_POST['MemberType'])
		{
			// the user is changing the MemberType of an existing type... update any members assigned to the type
			update_members($_POST['MemberType']);
		}
	}
	else
	{
		// insert
		$formAction = "Added";
		
		// process insert
		$result = $wpdb->insert(
			self::$member_type_table
			,array(
				'MemberType' => $_POST['MemberType']//,
				//'idx' => self::db_number($_POST['idx'])
			)
			,array(
				'%s',
				'%d'
			)
		);
	}
	
	if ($result === false)
    { ?>
        <div class="error settings-error">
            <p>
                <strong>Database Error - please check input <?php echo $formAction; ?></strong>
            </p>
        </div>
        <?php
    }
    else
    {
        // Record added/deleted/updated successfully
        ?>
        <div class="updated settings-error">
            <p><b>Member Type <?php echo $_POST['MemberType'] . ' ' . $formAction ?></b></p>
        </div>
        <?php
    }
}
?>

<style>
	div.memberTypes { width:330px; }
	
    div.memberTypes div.stripeMe:nth-of-type(odd) {
        background-color:#f8f8f8;
		overflow:auto;
		max-height:400px;
    }
	div.memberTypeEdit { margin-top:20px; }
	
	#divDelete {
		display:none; margin-top:10px;
		padding:10px;
	    background-color: #FFEBE8;
		border-color: #CC0000;
		border-radius: 3px 3px 3px 3px;
		border-style: solid;
		border-width: 1px;
	}
	
	form label 
	{ 
	  display: inline-block;
	  width: 100px;
	  text-align: right;
	  margin-right: 10px;
	}
	form input {
	  width: 200px;
	}
</style>

<!-- Listing of member types -->
<?php
$member_types = self::get_member_types($wpdb); ?>

<b>Existing Member Types:</b>
<div class="memberTypes">
	<?php
	$member_types = self::get_member_types($wpdb);
	foreach($member_types as $member_type)
	{
		?>
		<div class="stripeMe">
			<a href="<?php echo add_query_arg( 'id', $member_type->MemberType );?>">
				<?php echo $member_type->MemberType;?>
			</a>
		</div>
		<?php
	}	
	?>
</div>
	
<!-- add/edit/delete form -->
<hr />

<div class="metabox-holder postbox-container">
<div class="postbox">
	<?php
	$id = (empty($_REQUEST['id'])) ? null : $_REQUEST['id'];
	if (!empty($id) && empty($_POST))
	{ ?>
	<h3 class="hndle"><span>Edit Membership Type</span></h3>
	<?php
	}
	else
	{?>
	<h3 class="hndle"><span>Add New Membership Type</span></h3>
	<?php
	}
	?>
	<div class="inside">
	<?php
		$sql = "SELECT * FROM " . self::$member_type_table . " WHERE MemberType=" . self::db_string($id);
		$edit_type = (!empty($id)) ? $wpdb->get_row($sql) : null;?>		
	<form method="POST" id="member_type_form">
		<?php echo self::text_editor_for("MemberType", "Member Type") ?>
		<!--<?php echo self::text_editor_for("idx", "Order") ?>-->
		<input type="hidden" id="OriginalMemberType" name="OriginalMemberType" value="<?php echo (!empty($edit_type)) ? $edit_type->MemberType : '';?>" />

		<div style="float:right;">
			<?php if (!empty($id) && empty($_POST))
			{ ?>
			<input type="hidden" id="member_types_delete_mode" name="member_types_delete_mode" />
			<a href="?page=membership-manager-manage_membership_types" id="member_types_cancel" class="button">Cancel</a>
			<button type="button" id="member_types_delete" class="button">Delete</button>
			<?php } ?>
			<button type="submit" id="member_types_save" class="button">Save</button>
		</div>
		<div style="clear:both;"></div>
		
		<!-- Delete confirmation dynamically-displayed div -->
		<div id="divDelete">
			<div><b>Confirm Deletion</b></div>
			<div>
				Replace members assigned to this Membership Type with:
				<select id="replacementMemberType" name="replacementMemberType">
                    <option value="">Please choose a level</option>
                    <?php foreach ( $member_types as $mt ) { 
						if ($mt->MemberType == $edit_type->MemberType) continue; ?>
                    <option value="<?php echo $mt->MemberType ?>"><?php echo $mt->MemberType ?></option>
                    <?php } ?>
                </select>
			</div>
			<br/>
			<div style="float:right;">
				<button type="button" id="member_types_delete_cancel" class="button">Cancel Delete</button>
				<button id="member_types_delete_confirm" class="button">Delete</button>
			</div>
			<div style="clear:both;"></div>
		</div>
	</form>
	</div>
	<script>
        jQuery(document).ready(function($) {
            <?php
            if (!empty($edit_type) && empty($_POST))
            {
				// use jQuery to populate form inputs when the user is editing
                foreach($edit_type as $k => $v)
                {
                    ?> 
                    jQuery(<?php echo json_encode("#$k");?>).val(<?php echo json_encode($v);?>);
                    <?php
                }?>
				<?php
            }
            ?>

			jQuery('#member_types_delete').click(deleteClick);
			jQuery('#member_types_delete_cancel').click(cancelDelete);
			jQuery('#member_types_delete_confirm').click(deleteConfirm);
			jQuery('#member_types_form').submit(formSubmit);
			jQuery('#member_types_save').click(save);
			jQuery('#MemberType').focus();
		});
		
		function deleteClick() {
			jQuery(this).hide();
			jQuery('#member_types_save').hide();
			jQuery('#member_types_cancel').hide();
			jQuery('#divDelete').show();
		}
		
		function deleteConfirm() {
			jQuery('#member_types_delete_mode').val('delete');
			jQuery('#member_types_save').trigger('click');
		}
		
		function cancelDelete() {
			jQuery('#member_types_delete, #member_types_save, #member_types_cancel').show();
			jQuery('#divDelete').hide();
		}
		
		function formSubmit () {
			return false;
		}
		
		function save() {
			var typeBox = jQuery('#MemberType');
			typeBox.val(jQuery.trim(typeBox.val()));
			if (!typeBox.val()) {
				alert('Membership Type is required');
				jQuery('#MemberType').focus();
				return false;
			}
			jQuery(this).attr('disabled', 'disabled');
			document.forms[0].submit();
		}
    </script>
</div>
</div>
