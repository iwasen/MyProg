<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥڡ������̿Ϳ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");

// ����������
$search_id = $_GET['search_id'];
$search = new search_class;
$search->get_form();
$search->make_sql($from, $where);
$condition = $search->get_condition();

// ��ͭ���桼��������
$sql = "SELECT COUNT(*) FROM t_user_status WHERE ust_status=1 OR ust_status=2";
$result = db_exec($sql);
$all = pg_fetch_result($result, 0, 0);

// �����Ϳ�����
$sql = "SELECT COUNT(*) FROM $from WHERE $where";
$result = db_exec($sql);
$find = pg_fetch_result($result, 0, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�Ϳ�����</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('�Ϳ�����') ?>

<div align="center">
<table border=0 cellspacing=2 cellpadding=2 width="100%">
	<tr>
		<td class="lt" colspan=2 align="left">���������</td>
	</tr>
	<tr>
		<td class="m1" width="25%">�����桼����</td>
		<td class="n1"><?=number_format($find)?> / <?=number_format($all)?> (<?=number_format($find / $all * 100, 1)?>%)</td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><font size="-1"><?=nl2br($condition)?></font></td>
	</tr>
</table><br>
<div align="center">
	<input type="submit" value="���Ĥ��롡" onclick="window.close()">
</div>

<? center_footer() ?>

</body>
</html>
