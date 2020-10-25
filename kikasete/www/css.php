<?
$inc = "inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("SELECT htc_size,htc_content_type,htc_css_data FROM t_html_css WHERE htc_css_id=%s", sql_number($id));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	header("Content-type: $fetch->htc_content_type");
	header("Content-length: $fetch->htc_size");
	echo $fetch->htc_css_data;
}
exit;
?>
