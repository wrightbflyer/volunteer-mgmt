<h1>Settings</h1>

<h2>BD Usage</h2>
<pre>
$sql = 'SELECT * FROM ' . self::$table. ' ' . $where . ' ORDER BY ' . $order;
$rows = $wpdb->get_results($sql, OBJECT_K);
</pre>
