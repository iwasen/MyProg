<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:���֥ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '���֥ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT gt_gyoutai_cd, gt_order FROM m_gyoutai WHERE gt_gyoutai_name='$gyoutai_name' or gt_order=$gt_order";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(gt_gyoutai_cd), 0) + 1) FROM m_gyoutai";
		$sql = "INSERT INTO m_gyoutai (gt_gyoutai_cd, gt_gyoutai_name, gt_order) VALUES (($sub), '$gyoutai_name', $gt_order)";
		db_exec($sql);
		$msg = '����̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��϶���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT gt_gyoutai_name FROM m_gyoutai WHERE gt_gyoutai_name='$gyoutai_name' AND gt_gyoutai_cd<>$gyoutai_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_gyoutai SET" .
					" gt_gyoutai_name=" . sql_char($gyoutai_name) .
					", gt_order=" . sql_number($gt_order) .
					" WHERE gt_gyoutai_cd=$gyoutai_cd";
		db_exec($sql);
		$msg = '���֥ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '����̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_gyoutai WHERE gt_gyoutai_cd=$gyoutai_cd";
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
