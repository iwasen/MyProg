<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼�����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");

// �������������
function select_sendmail_status($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('1', $selected), ">̤����</option>\n";
	echo "<option ", value_selected('2', $selected), ">������</option>\n";
	echo "<option ", value_selected('3', $selected), ">������</option>\n";
	echo "<option ", value_selected('4', $selected), ">������</option>\n";
}

// ��������ɽ��
function decode_status($status) {
	switch ($status) {
	case 1:
		return '<font color="green">̤����</font>';
	case 2:
		return '<font color="brown">������</font>';
	case 3:
		return '<font color="red">������</font>';
	case 4:
		return '<font color="blue">������</font>';
	}
	return "����";
}

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'dealermail', 'subject', 'date', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

$where = '';

// ��̾���
if ($subject != '')
	and_join($where, "dml_subject LIKE '%$subject%'");

// ���վ��
if ($date != '')
	and_join($where, "date_trunc('month', dml_send_date)='$date/1'");

// ���־��
if ($status != '')
	and_join($where, "dml_status=$status");

if ($where)
	$where = "WHERE $where";

// ɽ���Կ�����ղ�
$limit = disp_limit();

// ����Ź�᡼���������
$order_by = order_by(1, 1, 'dml_dealer_mail_id', 'dml_subject', "coalesce(dml_send_date,'2000/01/01')", 'dml_status;dml_dealer_mail_id');
$sql = "SELECT dml_dealer_mail_id,dml_subject,dml_send_date,dml_status" .
			" FROM t_dealer_mail" .
			" $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('����Ź�᡼���ۿ�') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�Ѥ�����Ź�᡼�����</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>��̾<input type="text" name="subject" <?=value($subject)?>><input type="submit" value="����">&nbsp</nobr>
			<nobr>����<select name="date" onchange="submit()"><?select_ym('- ���� -', $date)?></select>&nbsp</nobr>
			<nobr>����<select name="status" onchange="submit()"><?select_sendmail_status('- ���� -', $status)?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($_GET['sort_col'])?>>
<input type="hidden" name="sort_dir" <?=value($_GET['sort_dir'])?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1, 'SEQ');
sort_header(2, '��̾');
sort_header(3, '��������');
sort_header(4, '����');
?>
	</tr>
<?
$same_id = new join_same_item;
$same_id->prepare($result, 'dml_dealer_mail_id');

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$row_count = $same_id->get_row_count();
?>
	<tr class="tc<?=$i % 2?>">
<?
	$project_id = $fetch->pj_project_id;
	if ($row_count > 0) {
?>
		<td align="center" rowspan=<?=$row_count?>><a href="contents.php?dealer_mail_id=<?=$fetch->dml_dealer_mail_id?>" title="����Ź�᡼���ɽ�����ѹ���Ԥ��ޤ�"><?=$fetch->dml_dealer_mail_id?></a></td>
<?
	}
?>
		<td><?=htmlspecialchars($fetch->dml_subject)?></td>
		<td align="center"><?=format_datetime($fetch->dml_send_date)?></td>
		<td align="center"><?=decode_status($fetch->dml_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
