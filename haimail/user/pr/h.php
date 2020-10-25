<?
include('../inc/common.php');
include('../inc/database.php');

//�ѥ�᡼��¸�ߥ����å�
$prid = $id;
if ($prid == '')
	param_error();

//�ѥ�᡼��Ĺ�����å�
if (strlen($prid) != 8)
	param_error();

//�����å�����
$sum = 0;
for ($i = 0; $i < 8; $i++) {
	$temp = $prid{$i};
	if ($temp < '0' || $temp > '9')
		 param_error();
	$sum = ($sum + $temp) % 10;
}
if ($sum != 0)
	param_error();

$prid =substr($prid, 0, 7);

//����å����˵�Ͽ
$sql = "INSERT INTO T_CLICK_LOG"
		. " VALUES(CL_seq_no.nextval,'$prid','$REMOTE_ADDR',SYSDATE,NULL)";
ExecuteSQL($sql);

//PR�ޥ����˥���å����û�
$sql = "UPDATE T_PR SET PR_click_count=PR_click_count+1 WHERE PR_pr_id='$prid'";
ExecuteSQL($sql);

//����ID�����å�
$top = '../index.php';
$sql = "SELECT PR_baitai_id FROM T_PR WHERE PR_pr_id='$prid'";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds->EOF()) {
	//����ץ쥹�б�
	if ($ds->GetValue('PR_baitai_id') == BT_IMPRESS)
		$top = '../impress/index.php';

	//�ͥåȥޥ����б�
	if ($ds->GetValue('PR_baitai_id') == BT_NETMILE)
		$top = '../present/index_nm.html';
}

SetCookie('PRID', $prid, 0, '/');
redirect($top);

//=== �ѥ�᡼�����顼���� ===
function param_error() {
	redirect('../index.php');
}
?>