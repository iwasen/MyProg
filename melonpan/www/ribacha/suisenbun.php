<?php
/****************************************************************
 Main
****************************************************************/
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
$sql = "SELECT * FROM T_RECOMEND WHERE recomend_id = :RECOMEND_ID";
$stmt = OCIParse($conn, $sql);
$ret01 = OCIbindbyname ($stmt, ":RECOMEND_ID", &$recomend_id, -1);
OCIDefinebyname($stmt, "RECOM_TITL", &$recom_titl);	// �����ȥ�
OCIDefinebyname($stmt, "RECOM_TEXT", &$recom_text);	// ��ʸ
OCIDefinebyname($stmt, "RECOM_NIC", &$recom_nic);	// �˥å��͡���
OCIDefinebyname($stmt, "MAG_ID", &$mag_id);	// �˥å��͡���
$ncols = OCIExecute($stmt);
if (OCIFetch($stmt))
{
}
db_logoff();
include ("suisenbun.html");
?>
