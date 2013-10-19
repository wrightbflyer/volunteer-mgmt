<h1>Membership Manager</h1>
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
				'MemberType' => $_POST['MemberType'],
				'idx' => self::db_number($_POST['idx'])
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
				'MemberType' => $_POST['MemberType'],
				'idx' => self::db_number($_POST['idx'])
			)
			,array(
				'%s',
				'%d'
			)
		);
	}
	
	if ($result == 1)
    {
        // Record added/deleted/updated successfully
        ?>
        <div class="updated settings-error">
            <p><b>Member Type <?php echo $_POST['MemberType'] . ' ' . $formAction ?></b></p>
        </div>
        <?php
    }
    else
    {
        ?>
        <div class="error settings-error">
            <p>
                <strong>Database Error - please check input <?php echo $formAction; ?></strong>
            </p>
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
	div.memberTypeEdit { background-color:rgba(255, 255, 0, .3); margin-top:20px; border:1px solid #000; }
	
	#divDelete { display:none; background-color:rgba(255, 0, 0, .5); margin-top:10px; }
	
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

<div class="memberTypeEdit memberTypes">
	<?php
	$id = (empty($_REQUEST['id'])) ? null : $_REQUEST['id'];
	if (!empty($id) && empty($_POST))
	{ ?>
	<b>Edit Membership Type:</b>
	<?php
	}
	else
	{?>
	<b>Add New Membership Type:</b>
	<?php
	}
	?>
	
	<?php
		$sql = "SELECT * FROM " . self::$member_type_table . " WHERE MemberType=" . self::db_string($id);
		$edit_type = (!empty($id)) ? $wpdb->get_row($sql) : null;?>		
	<form method="POST">
		<?php echo self::text_editor_for("MemberType", "Member Type") ?>
		<?php echo self::text_editor_for("idx", "Order") ?>
		<input type="hidden" id="OriginalMemberType" name="OriginalMemberType" value="<?php echo (!empty($edit_type)) ? $edit_type->MemberType : '';?>" />

		<div style="float:right;">
			<?php if (!empty($id) && empty($_POST))
			{ ?>
			<input type="hidden" id="member_types_delete_mode" name="member_types_delete_mode" />
			<button type="button" id="member_types_delete">Delete</button>
			<?php } ?>
			<button type="submit" id="member_types_save">Save</button>
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
			<div style="float:right;">
				<button type="button" id="member_types_delete_cancel">Cancel Delete</button>
				<button id="member_types_delete_confirm">Delete</button>
			</div>
			<div style="clear:both;"></div>
		</div>
	</form>
	
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
			jQuery('#MemberType').focus();
		});
		
		function deleteClick() {
			jQuery(this).hide();
			jQuery('#member_types_save').hide();
			jQuery('#divDelete').show();
		}
		
		function deleteConfirm() {
			jQuery('#member_types_delete_mode').val('delete');
			jQuery('#member_types_save').trigger('click');
		}
		
		function cancelDelete() {
			jQuery('#member_types_delete, #member_types_save').show();
			jQuery('#divDelete').hide();
		}
    </script>
</div>
