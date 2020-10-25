<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��ƻ�ܸ��ޥ����ꥹ��ɽ��
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");

//�ᥤ�����
set_global('master', '�ޥ����������ϰ�ޥ���', '��ƻ�ܸ��ޥ���', BACK_TOP);

$order_by = order_by(1, 0, 'ar_area_cd', 'ar_area_name','ar_area2_cd');
$sql = "SELECT ar_area_cd, ar_area_name,ar_area2_cd FROM m_area $order_by";
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
		<td class="lt">����ƻ�ܸ��ޥ�������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '��ƻ�ܸ�ID');
		sort_header(2, '��ƻ�ܸ�̾');
		sort_header(3, '�ϰ��ʬ̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?ar_cd=<?=$fetch->ar_area_cd?>" title="��ƻ�ܸ������ɽ�����ѹ����ޤ�"><?=$fetch->ar_area_cd?></a></td>
		<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
		<td><?=decode_area2($fetch->ar_area2_cd)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
