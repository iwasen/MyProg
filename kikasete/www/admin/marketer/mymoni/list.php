<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ���˥�������
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

// �롼���������
function select_room_status($sel) {
	echo "<option ", value_selected('', $sel), ">- ��ǧ�Ԥ� -</option>";
	echo "<option ", value_selected('*', $sel), ">- ����ʤ� -</option>";
	echo "<option ", value_selected('0', $sel), ">������</option>";
	echo "<option ", value_selected('1', $sel), ">��̳�ɥ����å���</option>";
	echo "<option ", value_selected('2', $sel), ">��ǧ�Ѥ�</option>";
	echo "<option ", value_selected('3', $sel), ">�»���</option>";
	echo "<option ", value_selected('4', $sel), ">������</option>";
	echo "<option ", value_selected('5', $sel), ">��λ</option>";
}

// �롼�����
function decode_moni_status($code, $def='����') {
	switch ($code) {
	case 0:
		return '������';
	case 1:
		return '��̳�ɥ����å���';
	case 2:
		return '��ǧ�Ѥ�';
	case 3:
		return '�»���';
	case 4:
		return '������';
	case 5:
		return '��λ';
	case 9:
		return '����Ѥ�';
	}
	return $def;
}

//�ᥤ�����
set_global('marketer', '�ޡ�����������', '�ͣ���˥�������', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'my_monitor', 'mail_addr', 'name', 'start_date_y', 'start_date_m', 'start_date_d', 'end_date_y', 'end_date_m', 'end_date_d', 'room_name', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

// ɽ�����
$where = "WHERE mr_status<>9";

if ($mail_addr != '')
	and_join($where, "mr_mail_addr LIKE '%$mail_addr%'");

if ($name != '')
	and_join($where, "(mr_name1 LIKE '%$name%' OR mr_name2 LIKE '%$name%')");

if ($start_date_y != '')
	and_join($where, "mm_start_date=" . sql_date("$start_date_y/$start_date_m/$start_date_d"));

if ($end_date_y != '')
	and_join($where, "mm_end_date=" . sql_date("$end_date_y/$end_date_m/$end_date_d"));

if ($room_name != '')
	and_join($where, "mm_room_name LIKE '%$room_name%'");

if ($status == '*')
	and_join($where, "mm_status<>9");
else {
	if ($status == '')
		and_join($where, "(mm_status=0 OR mm_status=1)");
	else
		and_join($where, "mm_status=$status");
}

// �����Ⱦ��
$order_by = order_by(1, 1, 'mm_regist_date', 'mr_name1_kana||mr_name2_kana', 'mm_room_name', 'mm_start_date', 'mm_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT mm_marketer_id,mm_room_id,mm_regist_date,mm_start_date,mm_end_date,mm_room_name,mr_mail_addr,mr_name1,mr_name2,mm_status"
		. " FROM t_my_monitor JOIN t_marketer ON mr_marketer_id=mm_marketer_id"
		. " $where $order_by $limit";
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
function onclick_room_name(room_id, status) {
	var check = "";
	if (status == 0) {
		if (confirm("���Σͣ���˥�����ֻ�̳�ɥ����å���פȤ��ޤ�����"))
			check = "&check=1";
	}
	location.href = "show.php?room_id=" + room_id + check;
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
		<td class="lt">���ͣ���˥�������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�᡼�륢�ɥ쥹<input type="text" name="mail_addr" <?=value($mail_addr)?>>��</nobr>
			<nobr>̾��<input type="text" name="name" size=10 <?=value($name)?>>��</nobr>
			<nobr>�롼��̾<input type="text" name="room_name" <?=value($room_name)?>>��</nobr>
			<nobr>������<input type="text" name="start_date_y" size=4 <?=value($start_date_y)?>>ǯ
			<select name="start_date_m"><? select_month('', $start_date_m) ?></select>��
			<select name="start_date_d"><? select_day('', $start_date_d) ?></select>����</nobr>
			<nobr>��λ��<input type="text" name="end_date_y" size=4 <?=value($end_date_y)?>>ǯ
			<select name="end_date_m"><?select_month('', $end_date_m)?></select>��
			<select name="end_date_d"><?select_day('', $end_date_d)?></select>����</nobr>
			<nobr>����<select name="status"><? select_room_status($status) ?></select>��</nobr>
			<input type="button" value="����" onclick="submit()">
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
		sort_header(1, '������');
		sort_header(2, '�ޡ�������');
		sort_header(3, '�롼��̾');
		sort_header(4, '�»ܴ���');
		sort_header(5, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_date($fetch->mm_regist_date)?></td>
		<td><a href="<?=$top?>/common/marketer_info.php?marketer_id=<?=$fetch->mm_marketer_id?>" target="_blank" title="<?=htmlspecialchars("$fetch->mr_mail_addr\n")?>�ޡ������������ɽ�����ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td><a href="javascript:onclick_room_name(<?=$fetch->mm_room_id?>,<?=$fetch->mm_status?>)" title="�ͣ���˥������Ƥ�ɽ�����ޤ�"><?=htmlspecialchars($fetch->mm_room_name)?></a></td>
		<td align="center"><?=format_date($fetch->mm_start_date)?>��<?=format_date($fetch->mm_end_date)?></td>
		<td align="center"><?=decode_moni_status($fetch->mm_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
