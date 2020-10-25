<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:���/ô���ԥޥ�������ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");

//ɽ�����顼
function value_color($id) {
	global $agent_id;
	if ($id == $agent_id) {
		echo 2;
	} else {
		echo 1;
	}
}

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '���/ô��������', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'cl_list', 'direction', 'sort_col', 'sort_dir');

//ɽ�����
if (!$direction) {
	$direction = 1;
}
if (SERVICE == 'agent' && $direction == '1') {
	$where = " WHERE ag_agent_id=$agent_id";
} else {
	$where = "";
}

$order_by = order_by(1, 0, 'cl_client_id,ts_tantousha_id', 'cl_name_kana,ts_tantousha_id', 'ts_tantousha_id', 'ts_name_kana');
$sql = "SELECT cl_client_id,cl_name,cl_name_kana,ts_name1,ts_tantousha_id,ts_name2,ts_name1_kana||ts_name2_kana AS ts_name_kana"
	. " FROM m_client JOIN m_tantousha ON cl_client_id=ts_client_id LEFT JOIN t_agent ON ts_agent_id=ag_agent_id"
	. " $where $order_by";
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
		<td colspan=2>��<?=$g_title?></td>
		<td class="lb">
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
<?
if (SERVICE == 'agent') {
?>
	<tr>
		<td colspan=3 class="lc">
		<input type="radio" name="direction_c" onclick="direction_check(1)" <?=value_checked(1, $direction)?>>ô����ȤΤ�
		<input type="radio" name="direction_c" onclick="direction_check(9)" <?=value_checked(9, $direction)?>>����</td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=95%>
	<tr class="tch">
<?
sort_header(1, '���ID');
sort_header(2, '���̾');
sort_header(3, 'ô����ID');
sort_header(4, 'ô����̾');
?>
	</tr>
<?
$same_client = new join_same_item;
$same_client->prepare($result, 'cl_client_id');

$j = 1;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$row_count = $same_client->get_row_count();
	if ($row_count > 0)
		$j++;
?>
	<tr class="tc<?=$j % 2?>">
<?
	if ($row_count > 0) {
?>
		<td align="center" rowspan=<?=$row_count?>><?=$fetch->cl_client_id?></td>
		<td rowspan=<?=$row_count?>><?=htmlspecialchars($fetch->cl_name)?></td>
<?
	}
?>
		<td align="center"><?=$fetch->ts_tantousha_id?></td>
		<td><a href="pj_new.php?tantousha_id=<?=$fetch->ts_tantousha_id?>" title="�ץ������Ȥ�������ޤ�"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></a></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
