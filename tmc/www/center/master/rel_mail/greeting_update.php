<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼�밧��ʸ��������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$vol_no = $_POST['vol_no'];
$body = trim_crlf($_POST['body']);

$table = 't_relation_greeting';

// ����ʸ�򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE rlg_vol_no=$vol_no";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['rlg_contents'] = sql_char($body);
	db_update($table, $rec, "rlg_vol_no=$vol_no");
} else {
	$rec['rlg_vol_no'] = sql_number($vol_no);
	$rec['rlg_contents'] = sql_char($body);
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

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��ð���ʸ') ?>

<div align="center">
<p class="msg">��³��졼�����᡼��ΰ���ʸ����Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
