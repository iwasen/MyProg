<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼���̾��������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$vol_no = $_POST['vol_no'];
$car_model_cd = $_POST['car_model_cd'];

$table = 't_follow_subject';

// ��̾�򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE fls_vol_no=$vol_no AND fls_car_model_cd=$car_model_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['fls_subject'] = sql_char($_POST['subject']);
	db_update($table, $rec, "fls_vol_no=$vol_no AND fls_car_model_cd=$car_model_cd");
} else {
	$rec['fls_vol_no'] = sql_number($vol_no);
	$rec['fls_car_model_cd'] = sql_number($car_model_cd);
	$rec['fls_subject'] = sql_char($_POST['subject']);
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

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��÷�̾') ?>

<div align="center">
<p class="msg">����ե����᡼��η�̾����Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='subject_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
