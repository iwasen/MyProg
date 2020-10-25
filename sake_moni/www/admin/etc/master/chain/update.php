<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��������ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '��������ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT ch_chain_cd FROM m_chain WHERE ch_name='$ch_name' or ch_order=$ch_order";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(ch_chain_cd), 0) + 1) FROM m_chain";
		$sql = "INSERT INTO m_chain (ch_chain_cd, ch_status, ch_name, ch_order, ch_chain_area_cd, ch_gyoutai_cd) VALUES (($sub) , 0, '$ch_name', $ch_order, $chain_area_cd, $gyoutai_cd)";
		db_exec($sql);
		$msg = '��������̾����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��ϥ�������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ch_chain_cd FROM m_chain WHERE ch_name='$ch_name' AND ch_chain_cd<>$chain_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_chain SET" .
					" ch_name=" . sql_char($ch_name) .
					", ch_status=" . sql_number($status) .
					", ch_order=" . sql_number($ch_order) .
					", ch_chain_area_cd=" . sql_number($chain_area_cd) .
                                        ", ch_gyoutai_cd=" . sql_number($gyoutai_cd) .
					" WHERE ch_chain_cd=$chain_cd";
		db_exec($sql);
		$msg = '��������ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '��������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_chain WHERE ch_chain_cd=$chain_cd";
	db_exec($sql);
	$msg = '��������̾�������ޤ�����';
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
