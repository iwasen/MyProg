<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡��Ȱ�������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function select_enq_status($sel) {
	echo "<option ", value_selected('', $sel), ">- ��ǧ�Ԥ� -</option>";
	echo "<option ", value_selected('*', $sel), ">- ����ʤ� -</option>";
	echo "<option ", value_selected('0', $sel), ">������</option>";
	echo "<option ", value_selected('1', $sel), ">������λ</option>";
	echo "<option ", value_selected('2', $sel), ">������</option>";
	echo "<option ", value_selected('3', $sel), ">��̳�ɥ����å���</option>";
	echo "<option ", value_selected('4', $sel), ">��ǧ�Ѥ�</option>";
	echo "<option ", value_selected('5', $sel), ">�»���</option>";
	echo "<option ", value_selected('6', $sel), ">������</option>";
	echo "<option ", value_selected('7', $sel), ">��λ</option>";
}

//�ᥤ�����
set_global('myenquete', '�ޡ�����������', '�ͣ����󥱡��ȴ���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'my_enquete', 'mail_addr', 'name', 'start_date_y', 'start_date_m', 'start_date_d', 'end_date_y', 'end_date_m', 'end_date_d', 'title', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

// ɽ�����
$where = '';

if ($mail_addr != '')
	and_join($where, "mr_mail_addr LIKE '%$mail_addr%'");

if ($name != '')
	and_join($where, "(mr_name1 LIKE '%$name%' OR mr_name2 LIKE '%$name%')");

if ($start_date_y != '')
	and_join($where, "en_start_date=" . sql_date("$start_date_y/$start_date_m/$start_date_d"));

if ($end_date_y != '')
	and_join($where, "en_end_date=" . sql_date("$end_date_y/$end_date_m/$end_date_d"));

if ($title != '')
	and_join($where, "en_title LIKE '%$title%'");

if ($status == '*')
	and_join($where, "en_status<>9");
else {
	if ($status == '')
		and_join($where, "(en_status=2 OR en_status=3)");
	else
		and_join($where, "en_status=$status");
}

if ($where != '')
	$where =  "WHERE $where";

// �����Ⱦ��
// 2003.12.17����� me_enquete_id ���ɲ�
$order_by = order_by(1, 1, 'me_regist_date', 'mr_name1_kana||mr_name2_kana', 'me_enquete_id','en_title', 'question_num', 'me_send_num', 'anc_ans_count', 'en_start_date', 'en_status');

// ɽ���Կ����
$limit = disp_limit();

// 2003.12.17����� me_enquete_id ���ɲ�
$sql = "SELECT me_marketer_id,me_my_enq_no,me_regist_date,en_start_date,en_end_date,en_title,en_enq_type,mr_mail_addr,mr_name1,mr_name2,en_status,me_enquete_id,(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id) as question_num,me_send_num,anc_ans_count"
		. " FROM t_my_enquete"
		. " JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " JOIN t_marketer ON me_marketer_id=mr_marketer_id"
		. " LEFT JOIN t_ans_count ON anc_enquete_id=en_enquete_id"
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
function disp_enquete(marketer_id, my_enq_no, status) {
	var check = "";
	if (status == 2) {
		if (confirm("���Σͣ����󥱡��Ȥ�ֻ�̳�ɥ����å���פȤ��ޤ�����"))
			check = "&check=1";
	}
	location.href = "show.php?marketer_id=" + marketer_id + "&my_enq_no=" + my_enq_no + check;
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
		<td class="lt">���ͣ����󥱡��Ȱ���</td>
		<td class="lb">
			<input type="button" value="���С��ꥹ�Ⱥ���" onclick="location.href='../member_list/list.php?mypro=my'">
			<input type="button" value="CSV���������" onclick="location.href='csv4.php?status=' + document.form1.status.value">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�᡼�륢�ɥ쥹<input type="text" name="mail_addr" <?=value($mail_addr)?>>��</nobr>
			<nobr>̾��<input type="text" name="name" size=10 <?=value($name)?>>��</nobr>
			<nobr>������<input type="text" name="start_date_y" size=4 <?=value($start_date_y)?>>ǯ
			<select name="start_date_m"><? select_month('', $start_date_m) ?></select>��
			<select name="start_date_d"><? select_day('', $start_date_d) ?></select>����</nobr>
			<nobr>��λ��<input type="text" name="end_date_y" size=4 <?=value($end_date_y)?>>ǯ
			<select name="end_date_m"><?select_month('', $end_date_m)?></select>��
			<select name="end_date_d"><?select_day('', $end_date_d)?></select>����</nobr>
			<nobr>����<input type="text" name="title" <?=value($title)?>>��</nobr>
			<nobr>����<select name="status"><? select_enq_status($status) ?></select>��</nobr>
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
<!-- 2003.12.17����� ID�򣳤ˤ����ɲ� -->
<?
		sort_header(1, '������');
		sort_header(2, '�ޡ�������');
		sort_header(3, 'ID');
		sort_header(4, '����');
		sort_header(5, '�����');
		sort_header(6, 'ȯ����');
		sort_header(7, '�ֿ���(%)');
		sort_header(8, '��������');
		sort_header(9, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_date($fetch->me_regist_date)?></td>
		<td><a href="<?=$top?>/common/marketer_info.php?marketer_id=<?=$fetch->me_marketer_id?>" target="_blank" title="<?=htmlspecialchars("$fetch->mr_mail_addr\n")?>�ޡ������������ɽ�����ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
<!-- 2003.12.17����� ID���ɲ� -->
		<td align="center"><a href="javascript:disp_enquete(<?=$fetch->me_marketer_id?>,<?=$fetch->me_my_enq_no?>,<?=$fetch->en_status?>)" title="�ͣ����󥱡������Ƥ�ɽ�����ޤ�"><?=$fetch->me_enquete_id?></a></td>
		<td><?=htmlspecialchars($fetch->en_title)?></td>
		<td align="right"><?=number_format($fetch->question_num)?></td>
		<td align="right"><?=number_format($fetch->me_send_num)?></td>
		<td align="right"><?=number_format($fetch->anc_ans_count)?> (<?=$fetch->me_send_num ? number_format($fetch->anc_ans_count / $fetch->me_send_num * 100, 1) : 0?>)</td>
		<td align="center"><?=format_date($fetch->en_start_date)?>��<?=format_date($fetch->en_end_date)?></td>
		<td align="center"><?=decode_enq_status($fetch->en_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
