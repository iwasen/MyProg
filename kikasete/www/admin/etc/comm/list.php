<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��å�������������������ꥹ��ɽ��
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

// ��å��������������
function select_direction($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>�ޡ����������饨���������</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>����������Ȥ���ޡ�������</option>', "\n";
}

// ��̳�ɤ���Τ��Τ餻���ơ�����
function decode_co_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '���';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾����', '��å���������������', BACK_TOP);

// �������
if ($mr_mail_addr != '')
	and_join($where, "mr_mail_addr LIKE '%$mr_mail_addr%'");

if ($direction != '')
	and_join($where, "co_direction=$direction");

if ($ag_mail_addr != '')
	and_join($where, "ag_mail_addr LIKE '%$ag_mail_addr%'");

if ($title != '')
	and_join($where, "co_title LIKE '%$title%'");

if ($del != '1')
	and_join($where, "co_status=0");

if ($where != '')
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 1, 'co_seq_no', 'mr_name1_kana||mr_name2_kana', 'co_direction', 'ag_name1_kana||ag_name2_kana', 'co_title', 'co_regist_date', 'co_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT co_seq_no,co_status,co_regist_date,co_direction,co_marketer_id,co_agent_id,co_title,mr_mail_addr,mr_name1,mr_name2,ag_name1,ag_name2,ag_mail_addr"
		. " FROM t_communication JOIN t_marketer ON co_marketer_id=mr_marketer_id JOIN t_agent ON co_agent_id=ag_agent_id"
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
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����å���������������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�ޡ��������᡼�륢�ɥ쥹<input type="text" name="mr_mail_addr" <?=value($mr_mail_addr)?>>��</nobr>
			<nobr>����<select name="direction"><? select_direction('- ����ʤ� -', $direction) ?></select>��</nobr>
			<nobr>����������ȥ᡼�륢�ɥ쥹<input type="text" name="ag_mail_addr" <?=value($ag_mail_addr)?>>��</nobr>
			<nobr>�����ȥ�<input type="title" name="title" <?=value($title)?>>��</nobr>
			<nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?>>����Ѥ�ɽ����</nobr>
			<input type="button" value="����" onclick="submit()">
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
		sort_header(2, '�ޡ�������');
		sort_header(3, '����');
		sort_header(4, '�����������');
		sort_header(5, '�����ȥ�');
		sort_header(6, '����');
		sort_header(7, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->co_seq_no?></td>
		<td><a href="<?=$top?>/common/marketer_info.php?marketer_id=<?=$fetch->co_marketer_id?>" target="_blank" title="<?=htmlspecialchars("$fetch->mr_mail_addr\n")?>�ޡ������������ɽ�����ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=($fetch->co_direction == 1) ? '��' : '��'?></td>
		<td><a href="<?=$top?>/common/agent_info.php?agent_id=<?=$fetch->co_agent_id?>" target="_blank" title="<?=htmlspecialchars("$fetch->ag_mail_addr\n")?>����������Ⱦ����ɽ�����ޤ�"><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></a></td>
		<td><a href="show.php?seq_no=<?=$fetch->co_seq_no?>" title="��å��������Ƥ�ɽ�����ޤ�"><?=htmlspecialchars($fetch->co_title)?></a></td>
		<td align="center"><?=format_date($fetch->co_regist_date)?></td>
		<td align="center"><?=decode_co_status($fetch->co_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
