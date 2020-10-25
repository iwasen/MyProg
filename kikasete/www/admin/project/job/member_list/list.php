<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����å��С��ꥹ��', '���С��ꥹ�Ȱ���', BACK_TOP);

$order_by = order_by(1, 0, 'mg_group_id', 'mg_group_name', 'mg_regist_date', 'ml_count');

$sub1 = "SELECT ml_group_id,COUNT(*) AS ml_count FROM t_member_list GROUP BY ml_group_id";
$sql = "SELECT mg_group_id,mg_group_name,mg_regist_date,mg_update_date,ml_count"
		. " FROM t_member_grp LEFT JOIN ($sub1) AS sub1 ON mg_group_id=ml_group_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
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
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">�����С��ꥹ�Ȱ���</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../list.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<form method="post" name="form2">
<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
		sort_header(1, '���С��ꥹ��ID');
		sort_header(2, '���С��ꥹ��̾');
		sort_header(3, '������');
		sort_header(4, '�ǽ�������');
		sort_header(5, '���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?group_id=<?=$fetch->mg_group_id?>" title="���С��ꥹ�Ȥ��ѹ����ޤ���"><?=$fetch->mg_group_id?></a></td>
		<td><a href="m_list.php?group_id=<?=$fetch->mg_group_id?>" title="���С��ꥹ�Ȥ�ɽ�����ޤ���"><?=htmlspecialchars($fetch->mg_group_name)?></a></td>
		<td align="center"><?=format_date($fetch->mg_regist_date)?></td>
		<td align="center"><?=format_date($fetch->mg_update_date)?></td>
		<td align="right"><?=number_format($fetch->ml_count)?></td>
	</tr>
<?
}
?>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
