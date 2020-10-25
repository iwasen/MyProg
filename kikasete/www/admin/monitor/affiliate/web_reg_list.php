<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ������Ͽ�԰�����Web��
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

// ��˥������������
function select_monitor_status2($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $selected), ">ͭ��</option>";
	echo "<option ", value_selected('2', $selected), ">��ã</option>";
	echo "<option ", value_selected('9', $selected), ">���</option>";
}

//�ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ������Ͽ�԰���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'affiliate_reg', 'search', 'status', 'month', 'displine', 'sort_col', 'sort_dir', 'page');

// ����Ͽ�������
if ($affiliate_id)
	$sql = "SELECT afc_hon_count FROM t_affiliate_cnt WHERE afc_affiliate_id=$affiliate_id";
else
	$sql = $sql = "SELECT afm_regist_count FROM t_affiliate_mgr WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$reg_sum = db_fetch1($sql);

// �����
if ($affiliate_id) {
	$sql = "SELECT af_parent_affiliate_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id";
	$parent_affiliate_id = db_fetch1($sql);
	if ($parent_affiliate_id)
		$ret_url = "web_reg_list.php?affiliate_id=$parent_affiliate_id";
	else
		$ret_url = 'web_list.php';
} else
	$ret_url = 'list.php';

// where���
if ($affiliate_id)
	and_join($where, "afr_affiliate_id=$affiliate_id");
else
	and_join($where, "afr_affiliate_id IN (SELECT af_affiliate_id FROM t_affiliate WHERE af_affiliate_mgr_id=$affiliate_mgr_id)");

// �����ƥ�����
if ($search != '') {
	if (is_numeric($search))
		and_join($where, "mn_monitor_id=$search");
	else
		and_join($where, "(mn_mail_addr LIKE '%$search%' OR mn_name1 LIKE '%$search%' OR mn_name2 LIKE '%$search%')");
}

if ($status != '')
	and_join($where, "mn_status=$status");

if ($month != '')
	and_join($where, "date_part('month',mn_regist_date)=$month");

$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 1, 'mn_monitor_id', 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana', 'ans_count', 'mn_age', 'mn_jitaku_zip', 'mn_jitaku_area', 'mn_regist_date', 'mn_status', 'afr_point');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT afr_affiliate_id,mn_monitor_id,mn_mail_addr,mn_name1,mn_name2,mn_jitaku_zip,ar_area_name,mn_regist_date,mn_status,afr_point,char_length(translate(mp_enq_history, '0', '')) AS ans_count,age(mn_birthday) AS mn_age"
		. " FROM t_affiliate_reg"
		. " JOIN t_monitor ON mn_monitor_id=afr_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " JOIN t_monitor_point ON mp_monitor_id=afr_monitor_id"
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
function all_set() {
	set_all_check(true);
}
function all_clear() {
	set_all_check(false);
}
function set_all_check(c) {
	var e = document.form2["check[]"];
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++)
				e[i].checked = c;
		} else
			e.checked = c;
	}
}
function submit_form2() {
	var e = document.form2["check[]"];
	var c = false;
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++) {
				if (e[i].checked) {
					c = true;
					break;
				}
			}
		} else
			c = e.checked;
	}
	if (!c)
		alert("�ݥ���Ⱥ���оݤ������å�����Ƥ��ޤ���");
	else if (confirm("���򤷤���˥����Υ��ե��ꥨ���ȥݥ���Ȥ�̵���ˤ��ޤ���������Ǥ�����"))
		document.form2.submit();
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
		<td class="lt">�����ե��ꥨ������Ͽ��˥�������</td>
		<td class="lb">
			<nobr>����Ͽ����<?=number_format($reg_sum)?>��</nobr>
			<input type="button" value="CSV����" onclick="location.href='check_csv.php?affiliate_id=<?=$affiliate_id?>'">
			<input type="button" value="������" onclick="all_set()">
			<input type="button" value="���ꥢ" onclick="all_clear()">
			<input type="button" value="�ݥ���ȼ�������" onclick="submit_form2()">
			<input type="button" value="����롡" onclick="location.href='<?=$ret_url?>'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>����<input type="text" name="search" <?=value($search)?>><input type="button" value="����" onclick="submit()">��</nobr>
			<nobr>����<select name="status" onchange="submit()"><? select_monitor_status2('- ����ʤ� -', $status)?></select>��</nobr>
			<nobr>��<select name="month" onchange="submit()"><? select_month('- ����ʤ� -', $month)?></select>��</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2" action="point_del.php" onsubmit="return onsubmit_form2(this)">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '��˥���ID');
		sort_header(2, '�᡼�륢�ɥ쥹');
		sort_header(3, '̾��');
		sort_header(4, '���ݎ�����<br>���');
		sort_header(5, 'ǯ��');
		sort_header(6, '͹���ֹ�');
		sort_header(7, '��ƻ�ܸ�');
		sort_header(8, '����Ͽ����');
		sort_header(9, '��Ͽ��<br>����');
		sort_header(10, '�ݥ����<br>����');
		sort_header(0, '�����å�');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$monitor_id = $fetch->mn_monitor_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="<?=$top?>/common/monitor_info.php?monitor_id=<?=$monitor_id?>" title="��˥�����Ͽ�����ɽ�����ޤ�" target="_blank"><?=$monitor_id?></a></td>
		<td><?=htmlspecialchars($fetch->mn_mail_addr)?></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="right"><?=number_format($fetch->ans_count)?></td>
		<td align="right"><?=(int)($fetch->mn_age / 10) * 10?></td>
		<td align="center"><?=htmlspecialchars($fetch->mn_jitaku_zip)?></td>
		<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
		<td align="center"><?=format_datetime($fetch->mn_regist_date, '-')?></td>
		<td align="center"><?=decode_monitor_status($fetch->mn_status)?></td>
		<td align="center"><?=$fetch->afr_point ? 'ͭ' : '̵'?></td>
		<td align="center">
<?
	if ($fetch->afr_point != 0) {
?>
			<input type="checkbox" name="check[]" <?=value("$fetch->afr_affiliate_id:$monitor_id")?>>
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="affiliate_id" <?=value($affiliate_id)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
