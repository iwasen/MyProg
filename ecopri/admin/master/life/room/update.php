<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:����ּ��ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '����ּ��ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT rm_room_cd, rm_order FROM m_room WHERE rm_room_name='$name' or rm_order=$rm_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(rm_room_cd), 0) + 1) FROM m_room WHERE rm_room_cd<>999";
		$sql = "INSERT INTO m_room (rm_room_cd, rm_room_name, rm_order) VALUES (($sub), '$name', $rm_order)";
		db_exec($sql);
		$msg = '����ּ�����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��Ͻ���ּ��̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_name='$name' AND rm_room_cd<>$rm_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_room SET" .
					" rm_room_name=" . sql_char($name) .
					", rm_order=" . sql_number($rm_order) .
					" WHERE rm_room_cd=$rm_cd";
		db_exec($sql);
		$msg = '����ּ��ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '����ּ��̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_room WHERE rm_room_cd=$rm_cd";
	db_exec($sql);
	$msg = '����ּ��������ޤ�����';
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
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
