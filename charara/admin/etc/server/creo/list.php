<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:����¾�����å������̱�������å��쥪
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
include("$inc/format.php");

// ������ID
$server_id = 'CRE';

//�ᥤ�����
set_global('etc', '����¾�����å������̱������', '���쥪', BACK_TOP);

$order_by = order_by(1, 0, 'cp_campaign_cd', 'cp_name', 'cp_from_date', 'cp_to_date');
$sql = "SELECT * FROM m_campaign WHERE cp_server_id='$server_id' $order_by";
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
		<td class="lt">�������ڡ������</td>
		<td class="lb" align="right">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '������');
		sort_header(2, '�����ڡ���̾');
		sort_header(3, '����');
		sort_header(4, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="detail.php?cp_cd=<?=$fetch->cp_campaign_cd?>" title="���ʾ����ɽ�����ѹ����ޤ�"><?=$fetch->cp_campaign_cd?></a></td>
		<td><?=$fetch->cp_name?></td>
		<td align="center"><?=format_date($fetch->cp_from_date)?></td>
		<td align="center"><?=format_date($fetch->cp_to_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
