<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�Ի�̾�ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '�Ի�̾�ޥ���', BACK_TOP);

$ci_todoufuken = decode_area($ci_todoufuken);

switch ($next_action) {
case 'new':
	$sql = "SELECT ci_seq_no FROM m_city WHERE ci_shiku='$ci_shiku'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(ci_seq_no), 0) + 1) FROM m_city";
		$sql = "INSERT INTO m_city (ci_seq_no, ci_todoufuken, ci_shiku) VALUES (($sub), '$ci_todoufuken', '$ci_shiku')";
		db_exec($sql);
		$msg = '�Ի�̾̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ����Ի�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ci_seq_no FROM m_city WHERE ci_shiku='$ci_shiku' AND ci_seq_no<>$ci_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_city SET" .
					" ci_todoufuken=" . sql_char($ci_todoufuken) .
					", ci_shiku=" . sql_char($ci_shiku) .
					" WHERE ci_seq_no=$ci_no";
		db_exec($sql);
		$msg = '�Ի�̾�ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '�Ի�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_city WHERE ci_seq_no=$ci_no";
	db_exec($sql);
	$msg = '�Ի�̾�������ޤ�����';
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
