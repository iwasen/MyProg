<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:Eco�������󤫤�Τ��Τ餻�����ꥹ��ɽ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

// ���Τ餻���ơ�����
function decode_tm_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾����', 'Eco�������󤫤�Τ��Τ餻', BACK_TOP);

if ($del != '1')
	$where = "WHERE tm_status=0";

// �����Ⱦ��
$order_by = order_by(1, 1, 'tm_seq_no', 'tm_title', 'tm_start_date', 'tm_end_date', 'tm_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT * FROM t_top_msg $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��Eco�������󤫤�Τ��Τ餻����</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">̵����ɽ��</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '�ֹ�');
		sort_header(2, '�����ȥ�');
		sort_header(3, '������');
		sort_header(4, '��λ��');
		sort_header(5, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->tm_seq_no?></td>
		<td><a href="edit.php?tm_no=<?=$fetch->tm_seq_no?>" title="Eco�������󤫤�Τ��Τ餻�����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->tm_title)?></a></td>
		<td align="center"><?=format_date($fetch->tm_start_date)?></td>
		<td align="center"><?=format_date($fetch->tm_end_date)?></td>
		<td align="center"><?=decode_tm_status($fetch->tm_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
