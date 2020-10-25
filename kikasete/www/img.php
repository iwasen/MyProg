<?
$inc = "inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("SELECT hti_size,hti_content_type,hti_image_data FROM t_html_image WHERE hti_image_id=%s", sql_number($id));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	header("Content-type: $fetch->hti_content_type");
	header("Content-length: $fetch->hti_size");
	echo pg_unescape_bytea($fetch->hti_image_data);
}
exit;
?>
