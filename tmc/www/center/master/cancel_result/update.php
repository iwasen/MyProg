<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�᡼����ɲ����ͳ�ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_mail_cancel_result WHERE mcr_cancel_cd=%s",
			sql_char($_POST['cancel_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	$rec['mcr_cancel_cd'] = sql_number($_POST['cancel_cd']);
	$rec['mcr_cancel_text'] = sql_char($_POST['cancel_text']);
	db_insert('t_mail_cancel_result', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['mcr_cancel_text'] = sql_char($_POST['cancel_text']);
	db_update('t_mail_cancel_result', $rec, "mcr_cancel_cd={$_POST['cancel_cd']}");
}

// �쥳���ɺ������
function rec_delete() {
	db_delete('t_mail_cancel_result', "mcr_cancel_cd={$_POST['cancel_cd']}");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ����᡼����ɲ����ͳ�����ɤϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '�᡼����ɲ����ͳ����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = '�᡼����ɲ����ͳ����򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = '�᡼����ɲ����ͳ�������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡å᡼����ɲ����ͳ�ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
