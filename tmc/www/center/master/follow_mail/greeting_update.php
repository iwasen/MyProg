<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼�밧��ʸ��������
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
$sales_channel_cd = $_POST['sales_channel_cd'];
$body = trim_crlf($_POST['body']);

$table = 't_follow_greeting';

// ����å���������Ͽ
regist_click_counter($vol_no, $body);

// ����ʸ�򿷵���Ͽ�ޤ��Ϲ���
$sql = "SELECT COUNT(*) FROM $table WHERE flg_vol_no=$vol_no AND flg_sales_channel_cd=$sales_channel_cd";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0)) {
	$rec['flg_contents'] = sql_char($body);
	db_update($table, $rec, "flg_vol_no=$vol_no AND flg_sales_channel_cd=$sales_channel_cd");
} else {
	$rec['flg_vol_no'] = sql_number($vol_no);
	$rec['flg_sales_channel_cd'] = sql_number($sales_channel_cd);
	$rec['flg_contents'] = sql_char($body);
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

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��ð���ʸ') ?>

<div align="center">
<p class="msg">����ե����᡼��ΰ���ʸ����Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='greeting_list.php?vol_no=<?=$vol_no?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
