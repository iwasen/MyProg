<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:My��˥��������»�
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

//�ᥤ�����
set_global('My��˥��������»�', BACK_TOP);

$fetch = get_system_info('sy_url_marketer');
$url_marketer = $fetch->sy_url_marketer;

$order_by = order_by(1, 0, 'mr_marketer_id', 'mr_name1_kana||mr_name2_kana', 'mr_kinmu_name');
$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_kinmu_name, mr_agent_id"
	." FROM t_marketer"
	. " WHERE mr_agent_id=$agent_id AND mr_status=0 $order_by";
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
function onclick_marketer(marketer_name, marketer_id) {
	var f = document.form2;
	if (confirm(marketer_name + "�����ID�ǥ����󤷡�My��˥����������»ܤ��ޤ���������Ǥ�����")) {
		f.marketer_id.value = marketer_id;
		f.submit();
	}
}

//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table align="left" border=0 cellspacing=0 cellpadding=1 width=700>
	<tr>
		<td>
			<form method="post" name="form2" action="marketer_login.php" target="_blank">
			<input type="hidden" name="url" value="mkk_mymonitor.php">
			<input type="hidden" name="marketer_id">
			</form>

			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>��My��˥��������»�</td>
				</tr>
			</table>
			<form method="post" name="form1">
			<input type="hidden" name="sort_col" <?=value($sort_col)?>>
			<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
			</form>
			<table <?=LIST_TABLE?> width="100%" class="small">
				<tr class="tch">
<?
sort_header(1, 'ID');
sort_header(2, '��̾');
sort_header(3, '���̾');
?>
				</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr class="tc<?=$i % 2?>">
					<td align="center"><?=$fetch->mr_marketer_id?></td>
					<td align="left"><a href='JavaScript:onclick_marketer("<?="$fetch->mr_name1 $fetch->mr_name2"?>","<?=$fetch->mr_marketer_id?>")' title="My���󥱡��Ȥ������»ܤ�¹Ԥ��ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
					<td align="left"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
