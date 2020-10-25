<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��å���������������α��������
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");
include("$inc/list.php");

//ɽ�����顼
function value_color($id) {
	global $agent_id;
	if ($id == $agent_id)
		echo 2;
	else
		echo 1;
}

//����ɽ��
function format_date2($from,$to,$def = '̤����') {
	if ($from && $to)
		return date('Y/m/d', sql_time($from)).'��'.date('m/d', sql_time($to));
	else
		return $def;
}

//=== ������� ===
function select_client($selected) {
	echo "<option value=''>- ����ʤ� -</option>\n";

	$sql = "SELECT cl_client_id,cl_name FROM m_client ORDER BY cl_client_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cl_client_id, $selected), '>', htmlspecialchars($fetch->cl_name), '</option>', "\n";
	}
}

//=== ���ô�������� ===
function select_tantousha($selected, $client_id) {
	echo "<option value=''>- ����ʤ� -</option>\n";

	if ($client_id != '')
		$where = "WHERE ts_client_id=$client_id";

	$sql = "SELECT ts_tantousha_id,ts_name1,ts_name2 FROM m_tantousha $where ORDER BY ts_tantousha_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ts_tantousha_id, $selected), '>', htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2"), '</option>', "\n";
	}
}

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '�ץ������Ȱ���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'project', 'client_id', 'tantousha_id', 'displine', 'sort_col', 'sort_dir', 'page');
$_SESSION['ss_pjt_list'] = 1;

if ($tantousha_id != '')
	and_join($where, "ts_tantousha_id='$tantousha_id'");

if ($client_id != '')
	and_join($where, "cl_client_id=$client_id");

if ($where)
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(5, 1, 'cl_client_id,ts_tantousha_id,pj_project_id', 'cl_name,ts_tantousha_id,pj_project_id', 'ts_tantousha_id,pj_project_id', 'ts_name1_kana||ts_name2_kana,pj_project_id', 'pj_project_id', 'pj_project_name');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT cl_client_id,cl_name,ts_tantousha_id,ts_name1,ts_name2,pj_project_id,pj_project_name"
		. " FROM t_project"
		. " JOIN m_tantousha ON ts_tantousha_id=pj_tantousha_id"
		. " JOIN m_client ON cl_client_id=ts_client_id"
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
function onchange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_client_id(cl_id) {
	var url;
	url = "client/cl_info.php?cl_id=" + cl_id;
	window.open(url, "cl_info", "resizable=yes,scrollbars=yes,width=600,height=320");
}
function onclick_tantousha_id(ts_id) {
	var url;
	url = "client/ts_info.php?ts_id=" + ts_id;
	window.open(url, "ts_info", "resizable=yes,scrollbars=yes,width=600,height=400");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td>���ץ������Ȱ���</td>
		<td class="lb">
			<input type="button" value="���������Ͽ" onclick="location.href='client/cl_new.php?back_url=<?=$REQUEST_URI?>'">
			<input type="button" value="�����̎ߎێ��ގ�������Ͽ" onclick="location.href='sel_client.php'">
<?
if (SERVICE == 'admin') {
?>
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>���<select name="client_id" onchange="onchange_client_id()"><? select_client($client_id) ?></select>&nbsp</nobr>
			<nobr>ô����<select name="tantousha_id" onchange="submit()"><? select_tantousha($tantousha_id, $client_id) ?></select>&nbsp</nobr>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '���ID');
sort_header(2, '���̾');
sort_header(3, 'ô����ID');
sort_header(4, 'ô����̾');
sort_header(5, '�̎ߎێ��ގ�����ID');
sort_header(6, "�̎ߎێ��ގ�����̾");
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="javascript:onclick_client_id(<?=$fetch->cl_client_id?>)" title="��Ⱦ����ɽ�����ޤ�"><?=$fetch->cl_client_id?></a></td>
		<td align="left"><a href="client/ts_new.php?cl_id=<?=$fetch->cl_client_id?>" title="ô���Ԥ���Ͽ���ޤ�"><?=htmlspecialchars($fetch->cl_name)?></td>
		<td align="center"><a href="javascript:onclick_tantousha_id('<?=$fetch->ts_tantousha_id?>')" title="���ô���Ծ����ɽ�����ޤ�"><?=$fetch->ts_tantousha_id?></a></td>
		<td align="left"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
		<td align="center"><a href="pj_edit.php?pjt_id=<?=$fetch->pj_project_id?>" title="�ץ������Ȥ���Ͽ���Ƥ򹹿����ޤ�"><?=$fetch->pj_project_id?></a></td>
		<td align="left"><a href="job/list.php?pjt_id=<?=$fetch->pj_project_id?>" title="����֤ΰ�����ɽ�����ޤ�"><?=htmlspecialchars($fetch->pj_project_name)?></a></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
