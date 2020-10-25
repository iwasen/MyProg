<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��������ޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �������󥹥ơ�����
function decode_chain_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '��������ޥ���', BACK_TOP);

if ($del != '1')
	$where = "WHERE ch_status=0";
$order_by = order_by(1, 0, 'ch_order', 'ch_name', 'ch_chain_area_cd', 'ch_status', 'ch_gyoutai_cd');
$sql = "SELECT ch_chain_cd,ch_status,ch_name,ca_area_name,ch_order,gt_gyoutai_name"
		. " FROM m_chain"
		. " JOIN m_chain_area ON ca_chain_area_cd=ch_chain_area_cd"
                . " JOIN m_gyoutai ON gt_gyoutai_cd=ch_gyoutai_cd"
		. " $where $order_by";
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
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����������ޥ�������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">̵����ɽ��</nobr>
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
		sort_header(2, '��������̾');
		sort_header(5, '����');
		sort_header(3, '���ꥢ');
		sort_header(4, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?chain_cd=<?=$fetch->ch_chain_cd?>" title="��������ޥ��������ɽ�����ѹ����ޤ�"><?= $fetch->ch_order ?></a></td>
		<td><?=htmlspecialchars($fetch->ch_name)?></td>
                <td><?=htmlspecialchars($fetch->gt_gyoutai_name)?></td>
		<td><?=htmlspecialchars($fetch->ca_area_name)?></td>
		<td align="center"><?=decode_chain_status($fetch->ch_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
