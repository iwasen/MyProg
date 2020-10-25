<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:ô���ԥޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_tantousha WHERE tts_tantousha_id<>%s AND tts_tantousha_name=%s",
			sql_number($_POST['tantousha_id'], 0),
			sql_char($_POST['tantousha_name']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	$rec['tts_tantousha_name'] = sql_char($_POST['tantousha_name']);
	$rec['tts_from_name'] = sql_char($_POST['from_name']);
	$rec['tts_from_addr'] = sql_char($_POST['from_addr']);
	$rec['tts_mail_account'] = sql_char($_POST['mail_account']);
	db_insert('t_tantousha', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['tts_tantousha_name'] = sql_char($_POST['tantousha_name']);
	$rec['tts_from_name'] = sql_char($_POST['from_name']);
	$rec['tts_from_addr'] = sql_char($_POST['from_addr']);
	$rec['tts_mail_account'] = sql_char($_POST['mail_account']);
	db_update('t_tantousha', $rec, "tts_tantousha_id={$_POST['tantousha_id']}");
}

// �쥳���ɺ������
function rec_delete() {
	db_delete('t_tantousha', "tts_tantousha_id={$_POST['tantousha_id']}");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ���ô����̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = 'ô���Ԥ���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '���Ϥ���ô����̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = 'ô���Ծ���򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = 'ô���Ԥ������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡�ô���ԥޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
