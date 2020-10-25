<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ȼ�ޥ����ꥹ��ɽ��
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

function decode_type_cd($code) {
	switch ($code) {
	case 1:
		return '��˥�����';
	case 2:
		return '�ޡ���������';
	}
}

//�ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�ȼ�ޥ���', BACK_TOP);

$order_by = order_by(1, 0, 'gs_order', 'gs_gyoushu_name', 'gs_type_cd');
$sql = "SELECT gs_gyoushu_cd, gs_gyoushu_name, gs_order, gs_type_cd FROM m_gyoushu $order_by";
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
		<td class="lt">���ȼ�ޥ�������</td>
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
		sort_header(1, 'ɽ�����');
		sort_header(2, '�ȼ�̾');
		sort_header(3, 'ɽ������');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?gyoushu_cd=<?=$fetch->gs_gyoushu_cd?>" title="�ȼ�ޥ��������ɽ�����ѹ����ޤ�"><?=$fetch->gs_order?></a></td>
		<td><?=htmlspecialchars($fetch->gs_gyoushu_name)?></td>
		<td align="center"><?=decode_type_cd($fetch->gs_type_cd)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
