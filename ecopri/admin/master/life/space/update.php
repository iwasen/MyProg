<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:�������ѥޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '�������ѥޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT sp_space_cd, sp_space_text, sp_space_from, sp_space_to FROM m_space WHERE sp_space_text='$name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(sp_space_cd), 0) + 1) FROM m_space WHERE sp_space_cd<>999";
		$sql = "INSERT INTO m_space (sp_space_cd, sp_space_text, sp_space_from, sp_space_to) VALUES (($sub), '$name', $from, $to)";
		db_exec($sql);
		$msg = '�������Ѥ���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT sp_space_text FROM m_space WHERE sp_space_text='$name' AND sp_space_cd<>$sp_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_space SET" .
					" sp_space_text=" . sql_char($name) .
					", sp_space_from=" . sql_number($from) .
					", sp_space_to=" . sql_number($to) .
					" WHERE sp_space_cd=$sp_cd";
		db_exec($sql);
		$msg = '�������ѥޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_space WHERE sp_space_cd=$sp_cd";
	db_exec($sql);
	$msg = '�������Ѥ������ޤ�����';
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
