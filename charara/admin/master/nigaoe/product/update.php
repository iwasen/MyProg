<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ޥ������ƥʥ󥹡å����åմ�Ϣ�ޥ����ý�°���̥ޥ���
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master_st', '�ޥ��������û��鳨��Ϣ�ޥ���', '���ʥޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT pd_product_cd FROM m_product WHERE pd_product_cd='$pd_product_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO m_product (pd_product_cd, pd_name, pd_explain)"
			. " VALUES ('$pd_product_cd', '$pd_name', '$pd_explain')";
		db_exec($sql);
		$msg = '���ʾ������Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ������ʥ����ɤϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT pd_product_cd FROM m_product WHERE pd_product_cd<>'$pd_cd' AND pd_name='$pd_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_product SET" .
					" pd_name=" . sql_char($pd_name) .
					", pd_explain=" . sql_char($pd_explain) .
					" WHERE pd_product_cd='$pd_cd'";
		db_exec($sql);
		$msg = '���ʥޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���ʥ����ɤϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "SELECT nd_nigaoe_id FROM t_nigaoe_data WHERE nd_product_cd='$pd_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "DELETE FROM m_product WHERE pd_product_cd='$pd_cd'";
		db_exec($sql);
		$msg = '���ʾ���������ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���ʥ����ɤϴ��˻��Ѥ���Ƥ���١�����Ǥ��ޤ���';
		$ret = 'history.back()';
	}
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
