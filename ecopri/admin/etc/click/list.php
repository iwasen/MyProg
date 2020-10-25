<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:���CD-ROM����å�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// �������������
function select_login_result($sel) {
	echo "<option ", value_selected('', $sel), ">- ����ʤ� -</option>";
	echo "<option ", value_selected('t', $sel), ">����</option>";
	echo "<option ", value_selected('f', $sel), ">����</option>";
}

//�ᥤ�����
set_global('etc', '����¾����', 'CD-ROM����å�����', BACK_TOP);

// where���
if ($date_y != '')
	and_join($where, 'date_trunc(\'day\',cd_date)=' . sql_date("$date_y/$date_m/$date_d"));

if ($ip_addr != '')
	and_join($where, 'cd_ip_addr LIKE ' . sql_char("%$ip_addr%"));

if ($where != '') {
	$where = "($where)";
	$where = "WHERE $where";
}

// �����Ⱦ��
$order_by = order_by(1, 1, 'cd_date', 'cd_ip_addr');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT count(cd_ip_addr) as count FROM l_cd_click $where";
$num = db_fetch1($sql);

$sql = "SELECT cd_date,cd_ip_addr FROM l_cd_click $where $order_by $limit";
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
		<td class="lt">��CD-ROM����å�������<font size=-1 color="blue">�����:&nbsp;<?=$num?>���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>����å�����<input type="text" name="date_y" size=4 <?=value($date_y)?>>ǯ
			<select name="date_m"><? select_month('', $date_m) ?></select>��
			<select name="date_d"><? select_day('', $date_d) ?></select>����</nobr>
			<nobr>�ɣХ��ɥ쥹<input type="text" name="ip_addr" size=17 <?=value($ip_addr)?>>��</nobr>
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
		sort_header(1, '����å�����');
		sort_header(2, '�ɣХ��ɥ쥹');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->cd_date)?></td>
		<td align="center"><?=$fetch->cd_ip_addr?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
