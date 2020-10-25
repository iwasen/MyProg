<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// 入力パラメータ
$kind = $_GET['kind'];
$report_id = $_GET['report_id'];

switch ($kind) {
case 'r':
	$select = 'rp_report_file AS file_content,rp_report_file_name AS file_name,rp_report_file_type AS content_type';
	break;
case 's':
	$select = 'rp_sum_file AS file_content,rp_sum_file_name AS file_name,rp_sum_file_type AS content_type';
	break;
case 'e':
	$select = 'rp_enquete_file AS file_content,rp_enquete_file_name AS file_name,rp_enquete_file_type AS content_type';
	break;
}

$sql = "SELECT $select FROM t_report WHERE rp_report_id=$report_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$content = pg_unescape_bytea($fetch->file_content);

	header('Pragma: no-cache');
	header("Content-type: $fetch->content_type");
	header('Content-Disposition: attachment; filename="' . mb_convert_encoding($fetch->file_name, 'SJIS') . '"');
	header("Content-length: " . strlen($content));
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');

	echo $content;
}
exit;
?>