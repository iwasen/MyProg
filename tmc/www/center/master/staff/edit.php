<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����å���Ͽ�ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���������
function select_privilege($sel) {
	global $g_privilege;

	echo "<option ", value_selected('', $sel), ">- ���򤷤Ƥ������� -</option>";
	echo "<option ", value_selected(PRIV_DHSTAFF, $sel), ">DH�����å�</option>";
	echo "<option ", value_selected(PRIV_OPERATOR, $sel), ">���ڥ졼��</option>";
}

// �����åվ��������
function select_staff_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// ���ϥѥ�᡼��
$staff_id = $_GET['staff_id'];

// ��Ͽ�Ѥߥ����åվ������
$sql = "SELECT * FROM t_staff WHERE stf_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error("�����åգɣĤ�����", __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onload_body() {
	onclick_password();
}
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
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.name.value == "") {
		alert("̾�������Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.privilege.selectedIndex == 0) {
		alert("���¤����򤷤Ƥ���������");
		f.privilege.focus();
		return false;
	}
	return confirm("�����åվ���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�����åդ������ޤ���������Ǥ�����");
}
function onclick_password() {
	var f = document.form1;
	f.password.style.display = f.password_change.checked ? "" : "none";
}
//-->
</script>
</head>
<body onload="onload_body()">

<? center_header('�ޥ������ƥʥ󥹡û�̳�ɥ����åաʥ��ڥ졼���˥ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�������åվ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=50 <?=value($fetch->stf_mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����</td>
		<td class="n1">
			<input type="checkbox" name="password_change" value="1" onclick="onclick_password()">�ѹ���
			<input type="password" name="password" maxlength=20 style="display:none">
		</td>
	</tr>
	<tr>
		<td class="m1">̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=25 <?=value($fetch->stf_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="privilege"><? select_privilege($fetch->stf_privilege)?></select></td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1"><textarea name="signature" cols=76 rows=5><?=htmlspecialchars($fetch->stf_signature)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_staff_status($fetch->stf_status)?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" <?=value($fetch->stf_staff_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
