<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼���ꤲ�����ơ�������������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$vol_no = $_POST['vol_no'];
$car_category_cd = $_POST['car_category_cd'];
$theme = $_POST['theme'];
$body = trim_crlf($_POST['body']);

$table = 't_relation_theme';

// �ꤲ�����ơ������򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['rlt_theme'] = sql_char($theme);
	$rec['rlt_contents'] = sql_char($body);
	db_update($table, $rec, "rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd");
} else {
	$rec['rlt_vol_no'] = sql_number($vol_no);
	$rec['rlt_car_category_cd'] = sql_number($car_category_cd);
	$rec['rlt_theme'] = sql_char($theme);
	$rec['rlt_contents'] = sql_char($body);
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

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼����ꤲ�����ơ�����') ?>

<div align="center">
<p class="msg">��³��졼�����᡼����ꤲ�����ơ���������Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='theme_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
