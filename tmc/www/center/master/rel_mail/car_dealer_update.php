<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼��ּ��������Ź���󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$vol_no = $_POST['vol_no'];
$dealer_cd = $_POST['dealer_cd'];
$body = trim_crlf($_POST['body']);

$table = 't_relation_car_dealer';

// �ּ��������Ź����򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE rlc_vol_no=$vol_no AND rlc_dealer_cd='$dealer_cd'";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['rlc_contents'] = sql_char($body);
	db_update($table, $rec, "rlc_vol_no=$vol_no AND rlc_dealer_cd='$dealer_cd'");
} else {
	$rec['rlc_vol_no'] = sql_number($vol_no);
	$rec['rlc_dealer_cd'] = sql_char($dealer_cd);
	$rec['rlc_contents'] = sql_char($body);
	db_insert($table, $rec);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��üּ��������Ź����') ?>

<div align="center">
<p class="msg">��³��졼�����᡼��μּ��������Ź�������Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='car_dealer_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
