<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ѡ��ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ѡ��ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT sp_super_cd FROM m_super WHERE sp_name='$sp_name' or sp_order=$sp_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(sp_super_cd), 0) + 1) FROM m_super";
		$sql = "INSERT INTO m_super (sp_super_cd, sp_status, sp_name, sp_order) VALUES (($sub) , 0, '$sp_name', $sp_order)";
		db_exec($sql);
		$msg = '�����ѡ�̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��ϥ����ѡ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT sp_super_cd FROM m_super WHERE sp_name='$sp_name' AND sp_super_cd<>$super_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_super SET" .
					" sp_name=" . sql_char($sp_name) .
					", sp_status=" . sql_number($status) .
					", sp_order=" . sql_number($sp_order) .
					" WHERE sp_super_cd=$super_cd";
		db_exec($sql);
		$msg = '�����ѡ��ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '�����ѡ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_super WHERE sp_super_cd=$super_cd";
	db_exec($sql);
	$msg = '�����ѡ�̾�������ޤ�����';
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
