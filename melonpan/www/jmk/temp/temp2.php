<?
$conn = OCILogon("anpn", "melonpan", "anpn");
$sql = "insert into t_test (seq,blob) values(1,EMPTY_BLOB()) returning blob into :blob";
$stmt = OCIParse($conn, $sql);
$lob = OCINewDescriptor($conn, OCI_D_LOB);
OCIBindByName($stmt, ":blob", &$lob, -1, OCI_B_BLOB);

OCIExecute($stmt, OCI_DEFAULT);
$lob->savefile("/home/aizawa/test.jpg");

OCICommit($conn);
OCIFreeDesc($lob);
OCIFreeStatement($stmt);

print "OK";
?>
