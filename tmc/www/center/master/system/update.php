<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content�����ƥ���󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_system WHERE sys_key=%s",
			sql_char($_POST['key']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	$rec['sys_key'] = sql_char($_POST['key']);
	$rec['sys_value'] = sql_char($_POST['value']);
	$rec['sys_caption'] = sql_char($_POST['caption']);
	db_insert('t_system', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['sys_value'] = sql_char($_POST['value']);
	$rec['sys_caption'] = sql_char($_POST['caption']);
	db_update('t_system', $rec, 'sys_key=' . sql_char($_POST['key']));
}

// �ᥤ�����
$ok = true;
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ��줿���ܥ����ϴ�����Ͽ����Ƥ��ޤ���';
		$ok = false;
	} else {
		rec_insert();
		$msg = '�����ƥ�����������Ͽ���ޤ�����';
	}
	break;
case 'update':
	rec_update();
	$msg = '�����ƥ��������򹹿����ޤ�����';
	break;
}

// �����
if ($ok)
	$ret = "location.href='list.php'";
else
	$ret = "history.back()";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡å����ƥ��������ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
