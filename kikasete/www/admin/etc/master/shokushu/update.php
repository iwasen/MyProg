<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '����ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT ss_shokushu_cd, ss_order FROM m_shokushu WHERE ss_shokushu_name='$shokushu_name' or ss_order=$ss_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(ss_shokushu_cd), 0) + 1) FROM m_shokushu";
		$sql = "INSERT INTO m_shokushu (ss_shokushu_cd, ss_shokushu_name, ss_order) VALUES (($sub), '$shokushu_name', $ss_order)";
		db_exec($sql);
		$msg = '����̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��Ͽ���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_name='$shokushu_name' AND ss_shokushu_cd<>$shokushu_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_shokushu SET" .
					" ss_shokushu_name=" . sql_char($shokushu_name) .
					", ss_order=" . sql_number($ss_order) .
					" WHERE ss_shokushu_cd=$shokushu_cd";
		db_exec($sql);
		$msg = '����ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '����̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_shokushu WHERE ss_shokushu_cd=$shokushu_cd";
	db_exec($sql);
	$msg = '����̾�������ޤ�����';
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
