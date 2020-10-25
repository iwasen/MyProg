<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����åոĿ͸���
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

//�ᥤ�����
set_global('staff', '�����åմ���', '�����åոĿ͸���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'staff_personal', 'and_or', 'mail_addr', 'name1', 'name2', 'name1_kana', 'name2_kana', 'birthday_y', 'birthday_m', 'birthday_d', 'kinmu_name', 'type', 'displine', 'sort_col', 'sort_dir', 'page');

// where���
if ($mail_addr != '')
	and_or_join($where, "st_mail_addr LIKE '%$mail_addr%'", $and_or);

if ($name1 != '')
	and_or_join($where, "st_name1 LIKE '%$name1%'", $and_or);

if ($name2 != '')
	and_or_join($where, "st_name2 LIKE '%$name2%'", $and_or);

if ($name1_kana != '')
	and_or_join($where, "st_name1_kana LIKE '%$name1_kana%'", $and_or);

if ($name2_kana != '')
	and_or_join($where, "st_name2_kana LIKE '%$name2_kana%'", $and_or);

if ($birthday_y != '')
	and_or_join($where, "st_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d"), $and_or);

if ($kinmu_name != '')
	and_or_join($where, "st_kinmu_name LIKE '%$kinmu_name%'", $and_or);

if ($where != '')
	$where = "($where)";
and_join($where, 'st_status<>9');
$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 0, 'st_staff_id', 'st_mail_addr', 'st_name1||st_name2', 'st_name1_kana||st_name2_kana', 'st_birthday', 'st_kinmu_name');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT st_staff_id,st_mail_addr,st_name1,st_name2,st_name1_kana,st_name2_kana,st_birthday,st_kinmu_name"
		. " FROM t_staff $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
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
		<td class="lt">�������åհ���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='find.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�᡼�륢�ɥ쥹<input type="text" name="mail_addr" <?=value($mail_addr)?>>��</nobr>
			<nobr>̾���ʴ���)��<input type="text" name="name1" size=10 <?=value($name1)?>>
			̾<input type="text" name="name2" size=10 <?=value($name2)?>>��</nobr>
			<nobr>̾���ʥ���)��<input type="text" name="name1_kana" size=10 <?=value($name1_kana)?>>
			̾<input type="text" name="name2_kana" size=10 <?=value($name2_kana)?>>��</nobr>
			<nobr>��ǯ����<input type="text" name="birthday_y" size=4 <?=value($birthday_y)?>>ǯ
			<select name="birthday_m"><?select_month('', $birthday_m)?></select>��
			<select name="birthday_d"><?select_day('', $birthday_d)?></select>����</nobr>
			<nobr>���̾<input type="text" name="kinmu_name" size=10 <?=value($kinmu_name)?>>��</nobr>
			<nobr>�����<input type="radio" name="and_or" <?=value_checked('A', $and_or)?>>AND
			<input type="radio" name="and_or" <?=value_checked('O', $and_or)?>>OR��</nobr>
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
		sort_header(1, 'ID');
		sort_header(2, '�᡼�륢�ɥ쥹');
		sort_header(3, '̾��');
		sort_header(4, '̾���ʥ��ʡ�');
		sort_header(5, '��ǯ����');
		sort_header(6, '���̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->st_staff_id?></td>
		<td><a href="edit.php?staff_id=<?=$fetch->st_staff_id?>" title="�����å���Ͽ�����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->st_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->st_name1 $fetch->st_name2")?></td>
		<td><?=htmlspecialchars("$fetch->st_name1_kana $fetch->st_name2_kana")?></td>
		<td align="center"><?=format_date($fetch->st_birthday)?></td>
		<td><?=htmlspecialchars($fetch->st_kinmu_name)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
