<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:������֥ޥ������󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '������֥ޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT kt_keitai_cd, kt_order FROM m_keitai WHERE kt_keitai_name='$name' or kt_order=$kt_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(kt_keitai_cd), 0) + 1) FROM m_keitai WHERE kt_keitai_cd<>999";
		$sql = "INSERT INTO m_keitai (kt_keitai_cd, kt_keitai_name, kt_order) VALUES (($sub), '$name', $kt_order)";
		db_exec($sql);
		$msg = '������֤���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ���ɽ������ޤ��Ͻ������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT kt_keitai_name FROM m_keitai WHERE kt_keitai_name='$name' AND kt_keitai_cd<>$kt_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_keitai SET" .
					" kt_keitai_name=" . sql_char($name) .
					", kt_order=" . sql_number($kt_order) .
					" WHERE kt_keitai_cd=$kt_cd";
		db_exec($sql);
		$msg = '������֥ޥ�������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '�������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_keitai WHERE kt_keitai_cd=$kt_cd";
	db_exec($sql);
	$msg = '������֤������ޤ�����';
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
