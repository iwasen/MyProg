<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥ����åհ����ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/decode.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_staff', 'del', 'sort_col', 'sort_dir');

// ����Ѥߤ�ɽ��
if ($del != '1')
	$where = "WHERE stf_status=0";

// ��̳�ɥ����åհ�������
$order_by = order_by(1, 0, 'stf_mail_addr', 'stf_name', 'stf_privilege', 'stf_status');
$sql = "SELECT stf_staff_id,stf_name,stf_mail_addr,stf_privilege,stf_status FROM t_staff $where $order_by";
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

<? center_header('�ޥ������ƥʥ󥹡û�̳�ɥ����åաʥ��ڥ졼���˥ޥ���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�����åհ���</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
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
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'�᡼�륢�ɥ쥹');
sort_header(2,'̾��');
sort_header(3,'����');
sort_header(4,'����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($g_privilege == PRIV_DHSTAFF)
		$mail_addr = "<a href='edit.php?staff_id=$fetch->stf_staff_id' title='�����åվ����ɽ�����ѹ����ޤ�'>" . htmlspecialchars($fetch->stf_mail_addr) . "</a>";
	else
		$mail_addr = htmlspecialchars($fetch->stf_mail_addr);
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=$mail_addr?></td>
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
		<td align="center"><?=decode_privilege($fetch->stf_privilege)?></td>
		<td align="center"><?=decode_staff_status($fetch->stf_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
