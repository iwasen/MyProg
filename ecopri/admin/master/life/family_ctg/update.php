<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��²���ƥ���ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '��²���ƥ���ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT fc_text FROM m_family_ctg WHERE fc_text='$name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(fc_family_ctg_cd), 0) + 1) FROM m_family_ctg WHERE fc_family_ctg_cd<>999";
		$sql = "INSERT INTO m_family_ctg (fc_family_ctg_cd, fc_adult_num, fc_child_num, fc_text) VALUES (($sub), '$adult_num', '$child_num', '$name')";
		db_exec($sql);
		$msg = '��²���ƥ������Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ�����²���ƥ���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT fc_family_ctg_cd FROM m_family_ctg WHERE fc_text='$name' AND fc_family_ctg_cd<>$fc_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_family_ctg SET" .
					" fc_adult_num=" . sql_number($adult_num) .
					", fc_child_num=" . sql_number($child_num) .
					", fc_text=" . sql_char($name) .
					" WHERE fc_family_ctg_cd=$fc_cd";
		db_exec($sql);
		$msg = '��²���ƥ���ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��²���ƥ���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_family_ctg WHERE fc_family_ctg_cd=$fc_cd";
	db_exec($sql);
	$msg = '��²���ƥ���������ޤ�����';
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
