<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:Ϣ����๹������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳���ɿ����ɲý���
function rec_insert() {
	$rec['msg_start_date'] = sql_datetime($_POST['start_date_check'], $_POST['start_date_y'], $_POST['start_date_m'], $_POST['start_date_d'], $_POST['start_date_h']);
	$rec['msg_end_date'] = sql_datetime($_POST['end_date_check'], $_POST['end_date_y'], $_POST['end_date_m'], $_POST['end_date_d'], $_POST['end_date_h']);
	$rec['msg_privilege'] = sql_char($_SESSION['s_privilege']);
	$rec['msg_to_staff_id'] = sql_number($_POST['to_staff_id']);
	$rec['msg_title'] = sql_char($_POST['title']);
	$rec['msg_message'] = sql_char($_POST['message']);
	db_insert('t_message', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['msg_start_date'] = sql_datetime($_POST['start_date_check'], $_POST['start_date_y'], $_POST['start_date_m'], $_POST['start_date_d'], $_POST['start_date_h']);
	$rec['msg_end_date'] = sql_datetime($_POST['end_date_check'], $_POST['end_date_y'], $_POST['end_date_m'], $_POST['end_date_d'], $_POST['end_date_h']);
	$rec['msg_to_staff_id'] = sql_number($_POST['to_staff_id']);
	$rec['msg_title'] = sql_char($_POST['title']);
	$rec['msg_message'] = sql_char($_POST['message']);
	db_update('t_message', $rec, "msg_message_id={$_POST['message_id']}");
}

// �쥳���ɺ������
function rec_delete() {
	$rec['msg_end_date'] = 'CURRENT_TIMESTAMP';
	db_update('t_message', $rec, "msg_message_id={$_POST['message_id']}");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	rec_insert();
	$msg = 'Ϣ��������Ͽ���ޤ�����';
	break;
case 'update':
	rec_update();
	$msg = 'Ϣ�����򹹿����ޤ�����';
	break;
case 'delete':
	rec_delete();
	$msg = 'Ϣ�����������ޤ�����';
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

<? center_header('Ϣ�����񤭹��ߡ�Ϣ����๹��') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
