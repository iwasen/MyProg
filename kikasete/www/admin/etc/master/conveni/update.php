<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����ӥ˥ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '����ӥ˥ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cv_conveni_cd FROM m_conveni WHERE cv_name='$cv_name' or cv_order=$cv_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(cv_conveni_cd), 0) + 1) FROM m_conveni";
		$sql = "INSERT INTO m_conveni (cv_conveni_cd, cv_status, cv_name, cv_order) VALUES (($sub) , 0, '$cv_name', $cv_order)";
		db_exec($sql);
		$msg = '����ӥ�̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��ϥ���ӥ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cv_conveni_cd FROM m_conveni WHERE cv_name='$cv_name' AND cv_conveni_cd<>$conveni_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_conveni SET" .
					" cv_name=" . sql_char($cv_name) .
					", cv_status=" . sql_number($status) .
					", cv_order=" . sql_number($cv_order) .
					" WHERE cv_conveni_cd=$conveni_cd";
		db_exec($sql);
		$msg = '����ӥ˥ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '����ӥ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_conveni WHERE cv_conveni_cd=$conveni_cd";
	db_exec($sql);
	$msg = '����ӥ�̾�������ޤ�����';
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
