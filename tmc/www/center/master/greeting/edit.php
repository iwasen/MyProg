<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ΰ���ʸ�ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ���ϥѥ�᡼��
$seq_no = $_GET['seq_no'];

// ��Ͽ�Ѥ߻���ΰ���ʸ�������
$sql = "SELECT * FROM t_season_greetings WHERE sgr_seq_no=" . sql_number($seq_no);
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
	if (f.greeting.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.to_day.focus();
		return false;
	}
	return confirm("����ΰ���ʸ�򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("����ΰ���ʸ�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡û���ΰ���ʸ�ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>������ΰ���ʸ�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ͭ������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_month"><? select_month('', $fetch->sgr_from_month) ?></select>��
			<select name="from_day"><? select_day('', $fetch->sgr_from_day) ?></select>��
			��
			<select name="to_month"><? select_month('', $fetch->sgr_to_month) ?></select>��
			<select name="to_day"><? select_day('', $fetch->sgr_to_day) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����ʸ<?=MUST_ITEM?></td>
		<td class="n1"><textarea name="greeting" cols=80 rows=10><?=htmlspecialchars($fetch->sgr_greeting)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<?
if ($seq_no != 0) {
?>
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<?
}
?>
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" <?=value($seq_no)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
