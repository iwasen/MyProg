<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:�ץ쥼��ȴ����ꥹ��ɽ��
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
function decode_status($code) {
	switch ($code) {
	case 0:
		return '������';
	case 1:
		return '�»���';
	case 2:
		return '��λ';
	case 3:
		return '���';
	case 9:
		return '̵��';
	}
	return '����';
}
// ���decode
function decode_type($code) {
	switch ($code) {
	case 0:
		return '����';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾����', '�ץ쥼��ȴ���', BACK_TOP);

// �����Ⱦ��
$order_by = order_by(1, 1, 'ps_present_no', 'ps_type', 'ps_name', 'ps_start_date', 'ps_lottery_date', 'ps_status');

// ɽ���Կ����
$limit = disp_limit();
//$sub1 = "select count(mb_seq_no) from v_member where mb_step=3 and mb_seq_no not in (select li_mb_seq_no from t_present_list)";
$sub1 = "select count(mb_seq_no) from t_member left join t_present_list on mb_seq_no=li_mb_seq_no and li_ps_no=ps_present_no"
	. " where mb_step=3 and li_mb_seq_no is null and mb_kind is null";
$sub2 = "select count(li_ps_sub_no) from t_present_list where li_status=3 and li_ps_no=ps_present_no";
$sub3 = "select count(li_ps_sub_no) from t_present_list where (li_status=1 or li_status=2) and li_ps_no=ps_present_no";
$sub4 = "select count(li_ps_sub_no) from t_present_list where li_status=9 and li_ps_no=ps_present_no";
$sub5 = "select count(li_ps_sub_no) from t_present_list where li_status=5 and li_ps_no=ps_present_no";
$sql = "select ps_present_no,ps_type,ps_name,ps_start_date,ps_end_date,ps_lottery_date,ps_status,ps_num,($sub1) as entry_count,($sub2) as send_count"
	. ",($sub3) as ref_count, ($sub4) as del_count, ($sub5) as finish_count"
	. " from t_present $order_by $limit";
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
		<td class="lt">������ץ쥼��Ȱ�����<font size=-1 color="blue">�����:&nbsp;<?=$nrow?>���</td>
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
		sort_header(2, '���');
		sort_header(3, '�����ȥ�');
		sort_header(4, '����');
		sort_header(5, '������');
		sort_header(6, '����');
		sort_header(7, '�̎ߎڎ��ގݎĿ�');
		sort_header(8, '����');
		sort_header(0, '����');
		sort_header(0, '��礻');
		sort_header(0, '����');
		sort_header(8, '��λ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->ps_present_no?></td>
		<td align="center"><?=decode_type($fetch->ps_type)?></td>
		<td align="left"><a href="edit.php?ps_no=<?=$fetch->ps_present_no?>" title="��Ͽ���Ƥ������ޤ�"><?=$fetch->ps_name?></a></td>
		<td align="center"><?=format_datetime($fetch->ps_start_date)?>&nbsp;��&nbsp;<?=format_datetime($fetch->ps_end_date)?></td>
		<td align="center"><?=format_date($fetch->ps_lottery_date)?></td>
		<td align="center"><?=decode_status($fetch->ps_status)?></td>
		<td align="right"><?=number_format($fetch->ps_num)?></td>
		<td align="right"><a href="entry.php?ps_no=<?=$fetch->ps_present_no?>" title="�����Ԥΰ�����ɽ�����ޤ�"><?=number_format($fetch->entry_count)?></a></td>
		<td align="right"><a href="result1.php?ps_no=<?=$fetch->ps_present_no?>" title="ȯ���������ɽ�����ޤ�"><?=$fetch->send_count?></a></td>
		<td align="right"><a href="result2.php?ps_no=<?=$fetch->ps_present_no?>" title="��礻������ɽ�����ޤ�"><?=$fetch->ref_count?></a></td>
		<td align="right"><a href="result3.php?ps_no=<?=$fetch->ps_present_no?>" title="����������ɽ�����ޤ�"><?=$fetch->del_count?></a></td>
		<td align="right"><a href="result4.php?ps_no=<?=$fetch->ps_present_no?>" title="���ۺѤ߰�����ɽ�����ޤ�"><?=$fetch->finish_count?></a></td>
		
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
