<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼��桼�����٥ͥե��åȾ��󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("click_counter.php");

// ���ϥѥ�᡼��
$vol_no = $_POST['vol_no'];
$car_model_cd = $_POST['car_model_cd'];
$body = trim_crlf($_POST['body']);

$table = 't_follow_benefit';

// ����å���������Ͽ
regist_click_counter($vol_no, $body);

// �桼�����٥ͥե��åȾ���򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE flb_vol_no=$vol_no AND flb_car_model_cd=$car_model_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['flb_contents'] = sql_char($body);
	db_update($table, $rec, "flb_vol_no=$vol_no AND flb_car_model_cd=$car_model_cd");
} else {
	$rec['flb_vol_no'] = sql_number($vol_no);
	$rec['flb_car_model_cd'] = sql_number($car_model_cd);
	$rec['flb_contents'] = sql_char($body);
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

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��å桼�����٥ͥե��åȾ���') ?>

<div align="center">
<p class="msg">����ե����᡼��Υ桼�����٥ͥե��åȾ������Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='benefit_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
