<?
	header("Content-type: application/octet-stream");

//$conn = OCILogon("anpn", "melonpan", "anpn");
$conn = OCILogon("anpn", "melonpan", "linux");

//	header("Content-type: application/octet-stream");
$sql = "select blob from t_test where seq=1";
$stmt = OCIParse($conn, $sql);
OCIExecute($stmt, OCI_DEFAULT);

if (OCIFetchInto($stmt, $row, OCI_ASSOC)) {
	$data = $row["BLOB"]->load();
//	header("Content-type: application/octet-stream");
//	header("Content-type: image/jpeg");
	print $data;
}
?>
