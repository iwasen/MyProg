<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������ͥ�ޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_sales_channel', 'sort_col', 'sort_dir');

// �������ͥ��������
$order_by = order_by(1, 0, 'sch_sales_channel_cd');
$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name FROM t_sales_channel $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡��������ͥ�ޥ���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�Ѥ��������ͥ����</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'�������ͥ�̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?sales_channel_cd=<?=$fetch->sch_sales_channel_cd?>" title="�������ͥ�����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->sch_sales_channel_name)?></a></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
