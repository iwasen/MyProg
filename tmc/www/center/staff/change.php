<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����åեѥ���ɡ��᡼�륢�ɥ쥹�ѹ�
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// ���᡼�륢�ɥ쥹����
$sql = sprintf("SELECT stf_mail_addr FROM t_staff WHERE stf_staff_id=%s",
		sql_number($g_staff_id));
$result = db_exec($sql);
if (pg_num_rows($result) != 0) {
	$fetch = pg_fetch_object($result, 0);
	$mail_addr = $fetch->stf_mail_addr;
}

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.password_old.value == "") {
		alert("��ѥ���ɤ����Ϥ��Ƥ���������");
		f.password_old.focus();
		return false;
	}
	if (f.password_new.value == "") {
		alert("���ѥ���ɤ����Ϥ��Ƥ���������");
		f.password_new.focus();
		return false;
	}
	if (f.password_verify.value == "") {
		alert("���ѥ���ɡʳ�ǧ�ˤ����Ϥ��Ƥ���������");
		f.password_verify.focus();
		return false;
	}
	if (f.password_new.value != f.password_verify.value) {
		alert("���ѥ���ɤ����פ��Ƥ��ޤ���");
		f.password_new.focus();
		return false;
	}
	return confirm("�ѥ���ɤ��ѹ����ޤ���������Ǥ�����");
}
function onSubmit_form2(f) {
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	return confirm("�᡼�륢�ɥ쥹���ѹ����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header(decode_privilege($g_privilege) . '�����ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="change_pwd.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="90%">
	<tr>
		<td class="m0">���ѥ�����ѹ�</td>
		<td class="m0" align="right"><input type="submit" value="���ѹ���"></td>
	</tr>
	<tr>
		<td class="m1" width="30%">��ѥ����</td>
		<td class="n1">
			<input type="password" name="password_old" maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ѥ����</td>
		<td class="n1">
			<input type="password" name="password_new" maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ѥ���ɡʳ�ǧ��</td>
		<td class="n1">
			<input type="password" name="password_verify" maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
</form>

<form method="post" name="form2" action="change_mail_addr.php" onsubmit="return onSubmit_form2(this)">
<table border=0 cellspacing=2 cellpadding=3 width="90%">
	<tr>
		<td class="m0">���᡼�륢�ɥ쥹�ѹ�</td>
		<td class="m0" align="right"><input type="submit" value="���ѹ���"></td>
	</tr>
	<tr>
		<td class="m1" width="30%">�᡼�륢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=50 <?=value($mail_addr)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
</form>
</div>

<? center_footer() ?>

</body>
</html>
