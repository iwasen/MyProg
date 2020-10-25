<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���ȿ����԰�����Web��
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

define('MAX_INTORO_LEN', 50);

// ���ե��ꥨ���Ⱦ��������
function select_affiliate_status($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $selected), ">������</option>";
	echo "<option ", value_selected('1', $selected), ">ͭ��</option>";
	echo "<option ", value_selected('2', $selected), ">̵��</option>";
}

// ���ե��ꥨ���Ⱦ���
function decode_affiliate_status($code) {
	switch ($code) {
	case 0:
		return '������';
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	case 3:
		return '���ᤷ';
	}
}

//�ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȿ����԰���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'affiliate_mem', 'search', 'status', 'month', 'displine', 'sort_col', 'sort_dir', 'page');

// ����Ͽ�������
$sql = "SELECT afm_appl_count,afm_regist_count"
		. " FROM t_affiliate_mgr"
		. " WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$shinsei_count = $fetch->afm_appl_count;
	$hon_count = $fetch->afm_regist_count;
}

// �����ƥ�����
if ($search != '') {
	if (is_numeric($search))
		and_join($where, "mn_monitor_id=$search");
	else
		and_join($where, "(mn_mail_addr LIKE '%$search%' OR mn_name1 LIKE '%$search%' OR mn_name2 LIKE '%$search%')");
}

// where���
and_join($where, "af_affiliate_mgr_id=$affiliate_mgr_id AND mn_status<>9");

if ($status != '')
	and_join($where, "af_status=$status");
else
	and_join($where, "af_status<>3");

if ($month != '')
	and_join($where, "date_part('month',af_appl_date)=$month");

$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 1, 'af_affiliate_id', 'mn_monitor_id', 'mn_name1_kana||mn_name2_kana', 'af_introduction', 'ans_count', 'mn_jitaku_area', 'mn_age', 'af_appl_date', 'af_accept_date', 'afc_click_count', 'afc_kari_count', 'afc_hon_count', 'af_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT af_affiliate_id,mn_monitor_id,mn_name1,mn_name2,af_web_url,af_introduction,af_appl_date,af_accept_date,afc_click_count,afc_kari_count,afc_hon_count,af_status,char_length(translate(mp_enq_history, '0', '')) AS ans_count,ar_area_name,age(mn_birthday) AS mn_age"
		. " FROM t_affiliate"
		. " JOIN t_affiliate_cnt ON afc_affiliate_id=af_affiliate_id"
		. " JOIN t_monitor ON mn_monitor_id=af_monitor_id"
		. " JOIN t_monitor_point ON mp_monitor_id=af_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
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
function open_intro(affiliate_id) {
	window.open("intro.php?affiliate_id=" + affiliate_id, "intro", "width=580,height=600,resizable=yes,scrollbars=yes");
}
function all_set() {
	set_all_check(document.form2["accept[]"], false);
	set_all_check(document.form2["cancel[]"], true);
}
function all_clear() {
	set_all_check(document.form2["accept[]"], false);
	set_all_check(document.form2["cancel[]"], false);
}
function set_all_check(e, c) {
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++)
				e[i].checked = c;
		} else
			e.checked = c;
	}
}
function submit_form2() {
	var e1 = document.form2["accept[]"];
	var e2 = document.form2["cancel[]"];
	var e3 = null;
	var c = false;
	if (e1) {
		if (e1.length) {
			for (var i = 0; i < e1.length; i++) {
				if (e1[i].checked && e2[i].checked) {
					e3 = e1[i];
					break;
				}
				if (e1[i].checked || e2[i].checked)
					c = true;
			}
		} else {
			if (e1.checked && e2.checked)
				e3 = e1;
			if (e1.checked || e2.checked)
				c = true;
		}
	}
	if (!c)
		alert("��ǧ�оݤ������å�����Ƥ��ޤ���");
	else if (e3) {
		alert("ͭ����̵����ξ���������å�����Ƥ��ޤ���");
		e3.focus();
	} else if (confirm("���򤷤����ե��ꥨ���Ȥ�ǧ���ޤ���������Ǥ�����"))
		document.form2.submit();
}
function change_status(affiliate_id, status) {
	if (status == 1) {
		if (confirm("���ե��ꥨ���Ȥ�̵���ˤ��ޤ���������Ǥ�����"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=2";
	} else if (status == 2) {
		if (confirm("���ե��ꥨ���Ȥ�ͭ���ˤ��ޤ���������Ǥ�����"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=1";
	}
}
function onclick_sashimodoshi() {
	if (confirm("̵���ο����Ԥ����ƺ��ᤷ���֤��ѹ����ޤ���������Ǥ�����"))
		location.href = "sashimodoshi.php";
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
		<td class="lt">�����ե��ꥨ���Ȱ���</td>
		<td class="lb">
			<nobr>�����Կ���<?=number_format($shinsei_count)?>������Ͽ�����<?=number_format($hon_count)?>��</nobr>
			<input type="button" value="������������" onclick="location.href='date_set.php?affiliate_mgr_id=<?=$affiliate_mgr_id?>'">
			<input type="button" value="������" onclick="all_set()">
			<input type="button" value="���ꥢ" onclick="all_clear()">
			<input type="button" value="��纹�ᤷ����" onclick="onclick_sashimodoshi()">
			<input type="button" value="��羵ǧ����" onclick="submit_form2()">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>����<input type="text" name="search" <?=value($search)?>><input type="button" value="����" onclick="submit()">��</nobr>
			<nobr>����<select name="status" onchange="submit()"><? select_affiliate_status('- ����ʤ� -', $status)?></select>��</nobr>
			<nobr>��<select name="month" onchange="submit()"><? select_month('- ����ʤ� -', $month)?></select>��</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2" action="accept.php">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '��˥���ID');
		sort_header(3, '̾��');
		sort_header(4, '����');
		sort_header(5, '���ݎ�����<br>���');
		sort_header(6, '��ƻ�ܸ�');
		sort_header(7, 'ǯ��');
		sort_header(8, '��������');
		sort_header(9, '��ǧ����');
		sort_header(10, '����å�');
		sort_header(11, '��');
		sort_header(12, '��');
		sort_header(13, '����');
		sort_header(0, '�����å�');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$affiliate_id = $fetch->af_affiliate_id;

	// �Ҳ�����������50ʸ���ޤǤ��ڤ�ͤ�
	$introduction = htmlspecialchars(mb_substr($fetch->af_introduction, 0, MAX_INTORO_LEN));
	if (mb_strlen($fetch->af_introduction) > MAX_INTORO_LEN)
		$introduction = "<a href='javascript:open_intro($affiliate_id)'>$introduction</a>";

	// ����Ͽ���Υ��
	$hon_count = number_format($fetch->afc_hon_count);
	if ($fetch->afc_hon_count)
		$hon_count = "<a href='reg_list.php?affiliate_id=$affiliate_id'>$hon_count</a>";

	// �����ѹ��ؤΥ��
	$status = decode_affiliate_status($fetch->af_status);
	if ($fetch->af_status == 1 || $fetch->af_status == 2)
		$status = "<a href='javascript:change_status($affiliate_id,$fetch->af_status)'>$status</a>";
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$affiliate_id?></td>
		<td align="center"><a href="<?=$top?>/common/monitor_info.php?monitor_id=<?=$fetch->mn_monitor_id?>" title="��˥�����Ͽ�����ɽ�����ޤ�" target="_blank"><?=$fetch->mn_monitor_id?></a></td>
		<td><a href="<?=$fetch->af_web_url?>" title="�Ҳ���URL��ɽ�����ޤ�" target="_blank"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></a></td>
		<td class="small2"><?=$introduction?></td>
		<td align="right"><?=number_format($fetch->ans_count)?></td>
		<td align="center"><?=$fetch->ar_area_name?></td>
		<td align="right"><?=(int)($fetch->mn_age / 10) * 10?></td>
		<td align="center"><?=format_datetime($fetch->af_appl_date)?></td>
		<td align="center"><?=format_datetime($fetch->af_accept_date, '-')?></td>
		<td align="right"><?=number_format($fetch->afc_click_count)?></td>
		<td align="right"><?=number_format($fetch->afc_kari_count)?></td>
		<td align="right"><?=$hon_count?></td>
		<td align="center"><?=$status?></td>
		<td align="center">
<?
	if ($fetch->af_status == 0) {
?>
			<nobr><input type="checkbox" name="accept[]" <?=value($affiliate_id)?>>ͭ��</nobr>
			<nobr><input type="checkbox" name="cancel[]" <?=value($affiliate_id)?>>̵��</nobr>
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>
</form>
</div>

<? page_footer() ?>
</body>
</html>
