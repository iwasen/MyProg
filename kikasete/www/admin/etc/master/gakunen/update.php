<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��ǯ�ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '��ǯ�ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT gk_name FROM m_gakunen WHERE gk_name='$gk_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(gk_gakunen_cd), 0) + 1) FROM m_gakunen";
		$sql = "INSERT INTO m_gakunen (gk_gakunen_cd,gk_age_from,gk_age_to,gk_name) VALUES (($sub), $age_from, $age_to, '$name')";
		db_exec($sql);
		$msg = '��ǯ̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ�����ǯ̾�Τϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT gk_name FROM m_gakunen WHERE gk_name='$name' AND gk_gakunen_cd<>$gakunen_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_gakunen SET" .
					" gk_name=" . sql_char($name) .
					", gk_age_from=" . sql_number($age_from) .
					", gk_age_to=" . sql_number($age_to) .
					" WHERE gk_gakunen_cd=$gakunen_cd";
		db_exec($sql);
		$msg = '��ǯ�ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��ǯ̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_gakunen WHERE gk_gakunen_cd=$gakunen_cd";
	db_exec($sql);
	$msg = '��ǯ̾�������ޤ�����';
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
