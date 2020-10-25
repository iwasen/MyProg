<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�Хå���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ��������
function decode_batch_status($code) {
	switch ($code) {
	case 0:
		return '����';
	case 9:
		return '<font color="red">���顼</font>';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾����', '�Хå���', BACK_TOP);

// �����Ⱦ��
$order_by = order_by(1, 1, 'lb_date', 'lb_file_name', 'lb_description', 'lb_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT lb_date,lb_file_name,lb_description,lb_status"
		. " FROM l_batch $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onsubmit_form2() {
	return confirm("�Хå����������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���Хå���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '��������');
		sort_header(2, '�ե�����̾');
		sort_header(3, '��������');
		sort_header(4, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->lb_date)?></td>
		<td align="center"><?=$fetch->lb_file_name?></td>
		<td><?=htmlspecialchars($fetch->lb_description)?></td>
		<td align="center"><?=decode_batch_status($fetch->lb_status)?></td>
	</tr>
<?
}
?>
</table>
<br>
<form method="post" name="form2" action="update.php" onclick="return onsubmit_form2()">
<input type="submit" value="�������">
</form>
</div>

<? page_footer() ?>
</body>
</html>
