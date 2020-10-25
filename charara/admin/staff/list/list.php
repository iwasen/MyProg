<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�����å���Ͽ�ꥹ�� 
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
include("$inc/decode.php");

// ̵����ɽ��
function check_flag($flag) {
	if ($flag)
		return "checked";
}

// ɽ����
function check_color($status) {
	if ($status == 9)
		echo "<font color='gray'>";
	else
		echo "<font color='black'>";
}

//�ᥤ�����
set_global('staff', '�����åվ������', '�����å���Ͽ�ꥹ��', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'staff_list', 'kind', 'org', 'name', 'mail_addr', 'priority', 'level', 'period', 'invalid', 'displine', 'sort_col', 'sort_dir', 'page');

// where���
if ($kind != '')
	and_join($where, "so_kind_cd=" . $kind);

if ($org != '')
	and_join($where, "st_organization=" . $org);

if ($name != '')
	and_join($where, "st_name1 LIKE '%$name%'");

if ($mail_addr != '')
	and_join($where, "st_mail_addr LIKE '%$mail_addr%'");

if ($priority != '')
	and_join($where, "st_priority_rank=" . $priority);

if ($level != '')
	and_join($where, "st_level_rank=" . $level);

if ($period == '') {
	and_join($where, "st_from_date <='" . date("Y-m-d") . "'");
	and_join($where, "st_to_date >='" . date("Y-m-d") . "'");
}
if ($invalid == '')
	and_join($where, "st_status=0");

if ($where != '') {
	$where = "($where)";
	$where = "WHERE $where";
} else
	$where = '';

// �����Ⱦ��
$order_by = ORDER_BY(6, 1, 'so_kind_cd', 'st_organization', 'st_name1||st_name2', 'st_name1_kana||st_name2_kana', 'st_mail_addr', 'st_priority_rank', 'st_level_rank','st_from_date', 'st_to_date');

// ɽ���Կ����
$limit = disp_limit();

$sql = "select count(st_staff_id) as count from m_staff LEFT JOIN m_staff_org ON st_organization=so_org_cd $where";
$num = db_fetch1($sql);

$sql = "SELECT st_staff_id,st_status,st_name1,st_name2,st_name1_kana,st_name2_kana,st_mail_addr,st_priority_rank,st_level_rank"
		. ",st_organization,st_from_date,st_to_date, so_kind_cd"
		. " FROM m_staff LEFT JOIN m_staff_org ON st_organization=so_org_cd $where $order_by $limit";
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
		<td class="lt">�������åհ�����<font size=-1 color="blue">�����:&nbsp;<?=$num?>̾��</font></td>
		<td class="lb" align="right">
			<input type="button" value="������Ͽ" onclick='location.href="new.php"'>
			<input type="button" value="����롡" onclick='location.href="<?=$top?>/menu.php"'>
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr><br>
			<nobr>����<select name="kind"><?=select_staff_kind('����', $kind)?></select>&nbsp;</nobr>
			<nobr>��°<select name="org"><?=select_staff_org('����',$kind)?></select>&nbsp;</nobr>
			<nobr>̾���ʴ���)��<input type="text" name="name" size=10 <?=value($name)?>>
			<nobr>�Ҏ��َ��Ďގڎ�<input type="text" name="mail_addr" <?=value($mail_addr)?>>&nbsp;</nobr>
			<nobr>ͥ����<input type="text" name="priority" <?=value($priority)?> size=7>&nbsp;</nobr>
			<nobr>��٥�<select name="level"><?=select_level_rank('����', $level)?></select>&nbsp;</nobr>
			<nobr>���ֳ���ɽ��<input type="checkbox" name="period" <?=check_flag($period)?>></nobr>
			<nobr>̵����ɽ��<input type="checkbox" name="invalid" <?=check_flag($invalid)?>></nobr>
			<input type="button" value="����" onclick="submit()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small2">
	<tr class="tch">
<?
		sort_header(1, '����');
		sort_header(2, '��°');
		sort_header(3, '��̾');
		sort_header(4, '��̾�ʥ��ʡ�');
		sort_header(5, '�᡼�륢�ɥ쥹');
		sort_header(6, 'ͥ����');
		sort_header(7, '��٥�');
		sort_header(8, '������');
		sort_header(9, '��λ��');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$status = $fetch->st_status;
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=check_color($status)?><?=$fetch->st_organization != '' ? decode_staff_kind($fetch->so_kind_cd) : '-̤����-'?></a></font></td>
		<td><?=check_color($status)?><?=$fetch->st_organization != '' ? decode_staff_org($fetch->st_organization) : '-̤����-'?></a></font></td>
		<td><?=check_color($status)?><a href="edit.php?st_id=<?=$fetch->st_staff_id?>" title="�����å���Ͽ�����ɽ�����ѹ����ޤ�"><?=htmlspecialchars("$fetch->st_name1 $fetch->st_name2")?></a></font></td>
		<td><?=check_color($status)?><?=htmlspecialchars("$fetch->st_name1_kana $fetch->st_name2_kana")?></font></td>
		<td align="center"><?=check_color($status)?><a href="mailto:<?=$fetch->st_mail_addr?>" title="�����åդ˥᡼�뤷�ޤ�"><?=htmlspecialchars($fetch->st_mail_addr)?></a></font></td>
		<td align="center"><?=check_color($status)?><?=$fetch->st_priority_rank?></font></td>
		<td align="center"><?=check_color($status)?><?=$fetch->st_level_rank?></font></td>
		<td align="center"><?=check_color($status)?><?=str_replace('-', '/', $fetch->st_from_date)?></font></td>
		<td align="center"><?=check_color($status)?><?=str_replace('-', '/', $fetch->st_to_date)?></font></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
