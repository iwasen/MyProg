<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:������ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '������ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT gk_gas_kind_cd, gk_order FROM m_gas_kind WHERE gk_gas_kind_name='$name' or gk_order=$gk_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(gk_gas_kind_cd), 0) + 1) FROM m_gas_kind WHERE gk_gas_kind_cd<>999";
		$sql = "INSERT INTO m_gas_kind (gk_gas_kind_cd, gk_gas_kind_name, gk_order) VALUES (($sub), '$name', $gk_order)";
		db_exec($sql);
		$msg = '���������Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��ϥ�����̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT gk_gas_kind_name FROM m_gas_kind WHERE gk_gas_kind_name='$name' AND gk_gas_kind_cd<>$gk_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_gas_kind SET" .
					" gk_gas_kind_name=" . sql_char($name) .
					", gk_order=" . sql_number($gk_order) .
					" WHERE gk_gas_kind_cd=$gk_cd";
		db_exec($sql);
		$msg = '������ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_gas_kind WHERE gk_gas_kind_cd=$gk_cd";
	db_exec($sql);
	$msg = '������������ޤ�����';
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
