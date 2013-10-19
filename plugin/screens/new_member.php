<?php
// Process data
if (!empty($_POST))
{
    print "<pre>";
    print_r($_POST);
    print "</pre>";
    ?>
    <div id="setting-error-settings_updated" class="updated settings-error">
        <p>
            <strong>Member Added</strong>
        </p>
    </div>
    <?php
}
?>
<form method="POST">
    <div>
        <label>First Name</label>
        <input type="text" name="FirstName"/>
    </div>
    <button type="submit">Add New Member</button>    
</form>
