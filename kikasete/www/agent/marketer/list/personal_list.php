<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��å���������������α��������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

//ͭ��/̵��ɽ��
function decode_status($code) {
	if ($code == 0) {
		return 'ͭ��';
	} else {
		return '<font color="red">̵��</font>';
	}
}
//������ɽ��
function decode_direction($code) {
	if ($code == 2) {
		return '<font color="orange">����</font>';
	} else {
		return '<font color="green">����</font>';
	}
}

//�ᥤ�����

set_global('�ޡ��������ؤΥ�å���������������', BACK_TOP, 1);

$sql = "SELECT mr_name1,mr_name2 FROM t_marketer WHERE mr_marketer_id=$id AND mr_agent_id=$agent_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mr_name1 = $fetch->mr_name1;
	$mr_name2 = $fetch->mr_name2;
} else {
	redirect("$top/mypage.php");
}

// ���å������Ͽ
get_session_vars($pset, 'ag_list', 'direction', 'displine', 'sort_col', 'sort_dir', 'page');

if (!$direction) {
	$direction = 2;
}
if ($direction != '9') {
	$where = " AND co_direction=int2($direction)";
} else {
	$where = "";
}

// ɽ���Կ�����ղ�
$limit = disp_limit(20);

if ($del != '1')
	$where .= " AND co_status=0";

$order_by = order_by(1, 1, 'co_regist_date', 'co_title');
$sql = "SELECT co_seq_no,co_status,co_regist_date,co_title,co_direction"
	." FROM t_communication LEFT JOIN t_marketer ON co_marketer_id=mr_marketer_id"
	. " WHERE co_agent_id=$agent_id AND co_marketer_id=$id $where $order_by $limit";
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

function direction_check(num) {
	var f = document.form1;
	f.direction.value = num;
	f.submit();
}
function mr_msg(co_no, top) {
  var win;
  win = window.open(top + "/sub/mr_msg.php?co_no=" + co_no, "mr_msg", "scrollbars=yes,resizable=yes,width=500,height=510");
  win.focus();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td colspan=2>��<?=htmlspecialchars("$mr_name1 $mr_name2")?> �ͤΥ�å���������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select>
		<input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>>
		<input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>
		<input type="radio" name="direction_c" onclick='direction_check(2)' <?=value_checked(2, $direction)?>>��������
		<input type="radio" name="direction_c" onclick="direction_check(1)" <?=value_checked(1, $direction)?>>��������
		<input type="radio" name="direction_c" onclick="direction_check(9)" <?=value_checked(9, $direction)?>>����</td>
		<td align="right" class="lc"><nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">̵����ɽ��</nobr></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="id" <?=value($id)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=95%>
	<tr class="tch">
<?
		sort_header(1, '��������');
		sort_header(2, '�����ȥ�');
		sort_header(0, '������');
		sort_header(0, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->co_regist_date)?></td>
		<td align="left" title="�����᡼������Ƥ�ɽ���������ѹ����ޤ�"><a href='JavaScript:mr_msg("<?= $fetch->co_seq_no ?>","<?=$top?>")'><?=htmlspecialchars($fetch->co_title)?></a></td>
		<td align="center"><?=decode_direction($fetch->co_direction)?></td>
		<td align="center"><?=decode_status($fetch->co_status)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
