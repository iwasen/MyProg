<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��²���ƥ���ޥ����ꥹ��ɽ��
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

//�ᥤ�����
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '��²���ƥ���ޥ���', BACK_TOP);

$order_by = order_by(1, 0, 'fc_family_ctg_cd', 'fc_text', 'fc_adult_num', 'fc_adult_num', 'fc_child_num');
$sql = "SELECT fc_family_ctg_cd, fc_adult_num, fc_child_num, fc_text FROM m_family_ctg $order_by";
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
		<td class="lt">����²���ƥ���ޥ�������</td>
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
		sort_header(1, '�ֹ�');
		sort_header(2, '��²���ƥ���̾');
		sort_header(3, '��Ϳ�');
		sort_header(4, '�Ҷ���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?fc_cd=<?=$fetch->fc_family_ctg_cd?>" title="��²���ƥ���ޥ��������ɽ�����ѹ����ޤ�"><?=$fetch->fc_family_ctg_cd?></a></td>
		<td><?=htmlspecialchars($fetch->fc_text)?></td>
		<td align="right"><?=$fetch->fc_adult_num?></td>
		<td align="right"><?=$fetch->fc_child_num?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
