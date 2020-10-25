<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = "SELECT im_size,im_type,im_data FROM t_image WHERE im_image_id={$_GET['id']}";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	header("Content-type: $fetch->im_type");
	header("Content-length: $fetch->im_size");
	echo pg_unescape_bytea($fetch->im_data);
}
exit;
?>