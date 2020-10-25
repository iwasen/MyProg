<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:��̳�ɤ���Τ��Τ餻�����ꥹ��ɽ��
'******************************************************/

$top = '../../..';
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

//�ᥤ�����
set_global('member', '����ȥåץڡ�������', '��̳�ɤ���Τ��Τ餻', BACK_TOP);

// �����Ⱦ��
$order_by = order_by(3, 1, 'an_seq_no', 'an_title', 'an_start_date', 'an_end_date', 'an_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT * FROM t_admin_msg $order_by $limit";
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
		<td class="lt">����̳�ɤ���Τ��Τ餻����</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
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
		sort_header(0, 'ɽ��');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if (date("Y-m-d") >= $fetch->an_start_date && date("Y-m-d") <= $fetch->an_end_date)
		$disp = "<font color='red'>ɽ����</font>";
	elseif (date("Y-m-d") > $fetch->an_end_date)
		$disp = "��λ";
	else
		$disp = "";
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->an_seq_no?></td>
		<td><a href="edit.php?an_no=<?=$fetch->an_seq_no?>" title="��̳�ɤ���Τ��Τ餻�����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->an_title)?></a></td>
		<td align="center"><?=format_date($fetch->an_start_date)?></td>
		<td align="center"><?=format_date($fetch->an_end_date)?></td>
		<td align="center"><?=$disp?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>