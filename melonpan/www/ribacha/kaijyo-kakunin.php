<?php
/*======================================================================================*/
/*	kaijyo-kakunin.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="ȯ�ԼԲ��";
$conn = db_conxxxt();
$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB");
$stmt = OCIParse($conn, $sql);
$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
include("$inc_path/pub_mm_defmane.def");
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (!OCIFetch($stmt)) {
	$sql = mp_make_sql ("SELECT_M_PUBLISHER");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
		include ("kaijyo-kakunin.html");
	} else {
		$print_msg =  "���ꤵ�줿ȯ�ԼԣɣĤϤ���ޤ���";
		include ("err-msg.html");
	}
} else {
	$sql = mp_make_sql ("SELECT_M_PUBLISHER");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
		$print_msg =  "���ꤵ�줿�ǡ����ˤϡ����ޥ�����Ͽ����Ƥ���ޤ���";
		include ("err-msg.html");
	}
}
db_logoff();
?>
