<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��̳��ȼ�ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '��̳��ȼ�ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT kg_gyoushu_cd,kg_order FROM m_kinmu_gyoushu WHERE kg_gyoushu_cd=$gyoushu_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(kg_gyoushu_cd), 0) + 1) FROM m_kinmu_gyoushu";
		$sql = "INSERT INTO m_kinmu_gyoushu (kg_gyoushu_cd, kg_gyoushu_name, kg_order) VALUES (($sub) , '$gyoushu_name', $order)";
		db_exec($sql);
		$msg = '��̳��ȼ�̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��̳��ȼ�ID�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "UPDATE m_kinmu_gyoushu SET" .
				" kg_gyoushu_name=" . sql_char($gyoushu_name) .
				", kg_order=" . sql_number($order) .
				" WHERE kg_gyoushu_cd=$gyoushu_cd";
	db_exec($sql);
	$msg = '��̳��ȼ�ޥ�������򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	$sql = "DELETE FROM m_kinmu_gyoushu WHERE kg_gyoushu_cd=$gyoushu_cd";
	db_exec($sql);
	$msg = '��̳��ȼ�̾�������ޤ�����';
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
