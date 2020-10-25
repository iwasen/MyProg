<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ΰ���ʸ�ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳������������
function rec_insert() {
	$rec['sgr_from_month'] = sql_char($_POST['from_month']);
	$rec['sgr_from_day'] = sql_char($_POST['from_day']);
	$rec['sgr_to_month'] = sql_char($_POST['to_month']);
	$rec['sgr_to_day'] = sql_char($_POST['to_day']);
	$rec['sgr_greeting'] = sql_char($_POST['greeting']);
	db_insert('t_season_greetings', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['sgr_from_month'] = sql_char($_POST['from_month']);
	$rec['sgr_from_day'] = sql_char($_POST['from_day']);
	$rec['sgr_to_month'] = sql_char($_POST['to_month']);
	$rec['sgr_to_day'] = sql_char($_POST['to_day']);
	$rec['sgr_greeting'] = sql_char($_POST['greeting']);
	db_update('t_season_greetings', $rec, "sgr_seq_no={$_POST['seq_no']}");
}

// �쥳���ɺ������
function rec_delete() {
	db_delete('t_season_greetings', "sgr_seq_no={$_POST['seq_no']}");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	rec_insert();
	$msg = '����ΰ���ʸ����Ͽ���ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	rec_update();
	$msg = '����ΰ���ʸ����򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = '����ΰ���ʸ�������ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡û���ΰ���ʸ�ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
