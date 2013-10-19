<h1>Membership Manager</h1>
<h2>Add a New Member</h2>
<?php self::partial('member_form',$_POST); ?>
<script>jQuery(document).ready(function($) { jQuery('#member_form_submit').html('Add New Member'); });</script>
