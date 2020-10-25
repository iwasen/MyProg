<?
include('inc/database.php');

header("Cache-control: no-cache");
header("Content-type: application/octet-stream");

if ($cid != '') {
	$sql = "SELECT AT_file_body FROM T_ATTACHMENT WHERE AT_content_type='$cid'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF()) {
		echo $ds->GetValue('AT_file_body');
		exit;
	}
}
?>