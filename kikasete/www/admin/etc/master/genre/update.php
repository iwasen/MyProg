<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��̣������ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '��̣������ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT kg_genre_cd FROM m_genre WHERE kg_name='$kg_name' or kg_order=$kg_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(kg_genre_cd), 0) + 1) FROM m_genre";
		$sql = "INSERT INTO m_genre (kg_genre_cd, kg_status, kg_name, kg_order) VALUES (($sub) , 0, '$kg_name', $kg_order)";
		db_exec($sql);
		$msg = '��̣������̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��϶�̣������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT kg_genre_cd FROM m_genre WHERE kg_name='$kg_name' AND kg_genre_cd<>$genre_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_genre SET" .
					" kg_name=" . sql_char($kg_name) .
					", kg_status=" . sql_number($status) .
					", kg_order=" . sql_number($kg_order) .
					" WHERE kg_genre_cd=$genre_cd";
		db_exec($sql);
		$msg = '��̣������ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��̣������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_genre WHERE kg_genre_cd=$genre_cd";
	db_exec($sql);
	$msg = '��̣������̾�������ޤ�����';
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
