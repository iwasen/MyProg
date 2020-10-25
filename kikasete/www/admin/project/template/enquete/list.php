<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ץ��������Ѽ���ʸ�ޥ�������ɽ��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/list.php");

//�ᥤ�����

set_global2('project', '�ץ������ȴ����åƥ�ץ졼���Խ�', '����ʸ�ƥ�ץ졼�Ȱ���', BACK_TOP);

$order_by = order_by(1, 1, 'pq_seq_no', 'pq_title','pq_regist_date');
$sql = "SELECT pq_seq_no,pq_title,pq_regist_date FROM m_pjt_question $order_by";
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
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../submenu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=95%>
	<tr class="tch">
<?
		sort_header(1, 'seq');
		sort_header(2, '�����ȥ�');
		sort_header(3, '��Ͽ��');
?>
	</tr>
<?

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=htmlspecialchars($fetch->pq_seq_no)?></td>
		<td align="left">
			<a href="edit.php?pq_no=<?=$fetch->pq_seq_no?>" title="����ʸ�ƥ�ץ졼�Ȥ������ޤ�"><?=htmlspecialchars($fetch->pq_title)?></a>
		</td>
		<td align="center"><?=format_datetime($fetch->pq_regist_date)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
