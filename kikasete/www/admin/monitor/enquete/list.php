<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ͥ��󥱡��ȴ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// ���󥱡��Ⱦ���
function decode_enq_status($code) {
	switch ($code) {
	case 0:
		return '������';
	case 4:
		return '�»��Ԥ�';
	case 5:
		return '�»���';
	case 6:
		return '������';
	case 7:
		return '��λ';
	case 9:
		return '����Ѥ�';
	}
	return '����';
}

//�ᥤ�����
set_global('monitor', '��˥�������', '�����ͥ��󥱡��ȴ���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'monitor_enquete', 'displine', 'sort_col', 'sort_dir', 'page');

// �����Ⱦ��
$order_by = order_by(1, 1, 'fe_seq_no', 'en_title', 'en_start_date', 'en_end_date', 'en_status', 'em_sum', 'eu_sum');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT fe_seq_no,en_title,en_start_date,en_end_date,en_status,COALESCE(em_sum,0) AS em_sum,COALESCE(eu_sum,0) AS eu_sum"
		. " FROM t_free_enquete"
		. " JOIN t_enquete ON fe_enquete_id=en_enquete_id"
		. " LEFT JOIN t_enquete_sum ON em_enquete_id=fe_enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0"
		. " LEFT JOIN t_episode_sum ON eu_seq_no=fe_seq_no"
		. " $order_by $limit";
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
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������ͥ��󥱡��Ȱ���</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
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

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '�ֹ�');
		sort_header(2, '����');
		sort_header(3, '��������');
		sort_header(4, '��λ����');
		sort_header(5, '����');
		sort_header(6, '�����Կ�');
		sort_header(7, '���ˎߎ����Ď�');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->fe_seq_no?></td>
		<td><a href="edit.php?seq_no=<?=$fetch->fe_seq_no?>" title="�����ͥ��󥱡������Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->en_title)?></a></td>
		<td align="center"><?=format_datetime($fetch->en_start_date)?></td>
		<td align="center"><?=format_datetime($fetch->en_end_date)?></td>
		<td align="center"><?=decode_enq_status($fetch->en_status)?></td>
<?
if ($fetch->em_sum != 0) {
?>
		<td align="right"><a href="result.php?seq_no=<?=$fetch->fe_seq_no?>" title="���󥱡��ȷ�̤�ɽ�����ޤ�"><?=number_format($fetch->em_sum)?></a></td>
<?
} else {
?>
		<td align="right">0</td>
<?
}
if ($fetch->eu_sum != 0) {
?>
		<td align="right"><a href="episode.php?seq_no=<?=$fetch->fe_seq_no?>" title="���ԥ����ɤ�ɽ�����ޤ�"><?=number_format($fetch->eu_sum)?></a></td>
<?
} else {
?>
		<td align="right">0</td>
<?
}
?>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
