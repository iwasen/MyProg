<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�䤤��碌�б���ô�����ڥ졼���ѹ�
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ô�����ڥ졼���ѹ�����
if ($_POST['new_staff_id']) {
	$sql = "UPDATE t_comm_thread SET cmt_staff_id={$_POST['new_staff_id']} WHERE cmt_comm_id="
			. "(SELECT cml_thread_comm_id FROM t_comm_log WHERE cml_comm_id={$_POST['comm_id']})";
	db_exec($sql);

	echo "<script type='text/javascript'>\n";
	echo "opener.document.form1.submit();\n";
	echo "window.close();\n";
	echo "</script>\n";
	exit;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<form method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td>�����ڥ졼���ѹ�</td>
		<td align="right">
			<input type="submit" value="���ѹ���">
			<input type="button" value="����󥻥�" onclick="window.close()">
		</td>
	</tr>
</table>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>̾��</th>
		<th>�᡼�륢�ɥ쥹</th>
		<th>ô��</th>
	</tr>
<?
$sql = "SELECT stf_staff_id,stf_name,stf_mail_addr"
		. " FROM t_staff"
		. " WHERE stf_status=0"
		. " ORDER BY stf_staff_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
		<td><?=$fetch->stf_mail_addr?></td>
		<td align="center"><input type="radio" name="new_staff_id" <?=value_checked($fetch->stf_staff_id, $_GET['staff_id'])?>></td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="comm_id" value="<?=$_GET['comm_id']?>">
</form>

</body>
</html>
