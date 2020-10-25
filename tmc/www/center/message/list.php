<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:Ϣ�����ꥹ��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/select.php");

// StaffIDɽ��
function print_staff_id($staff_id) {
	if ($staff_name == null)
		return '����';
	else
		return $staff_id;
}

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'message', 'all', 'displine', 'sort_col', 'sort_dir', 'page');

// ����
if (!$all)
	$where = "and (msg_end_date is null OR msg_end_date>CURRENT_TIMESTAMP)";

// ɽ����
$order_by = order_by(1, 1, 'msg_regist_date', 'msg_title', "coalesce(msg_to_staff_id,'')", "coalesce(msg_start_date,'2000/01/01')", "coalesce(msg_end_date,'2999/01/01')");

// ����ɽ���Կ�
$limit = disp_limit();

// Ϣ������������
$sql = "SELECT msg_message_id,msg_start_date,msg_end_date,msg_regist_date,msg_to_staff_id,msg_title" .
			" FROM t_message" .
			" WHERE msg_privilege='$g_privilege' $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onClick_staff_id(message_id) {
  var win = window.open("check.php?message_id=" + message_id, "message_check", "resizable=yes,scrollbars=yes,width=400");
  win.focus();
}
//-->
</script>
<? list_script() ?>
</head>
<body>

<? center_header('Ϣ�����񤭹���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��Ϣ��������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr><input type="checkbox" name="all" <?=value_checked('1', $all)?> onclick="submit()">���Υǡ�����ɽ��&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table width="100%" <?=LIST_TABLE?>>
	<tr class="tch">
<?
sort_header(1, '��Ͽ����');
sort_header(2, '�����ȥ�');
sort_header(3, '�о�Staff');
sort_header(4, '��������');
sort_header(5, '��λ����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->msg_regist_date)?></td>
		<td><a href="edit.php?message_id=<?=$fetch->msg_message_id?>" title="Ϣ������ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->msg_title)?></a></td>
		<td><a href="javascript:onClick_staff_id('<?=$fetch->msg_message_id?>')" title="Ϣ������̤���ɳ�ǧ��Ԥ��ޤ�"><?=print_staff_id($fetch->msg_to_staff_id)?></a></td>
		<td align="center"><?=format_datetime($fetch->msg_start_date)?></td>
		<td align="center"><?=format_datetime($fetch->msg_end_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
