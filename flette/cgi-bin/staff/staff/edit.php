#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����å���Ͽ�ѹ�����
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// Ź�ޥ����åդϼ��Ȱʳ����ѹ��Բ�
if ($s_staff_auth == 2 && $s_staff_id != $staff_id)
	redirect('../index.php');

$sql = "SELECT * FROM m_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
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
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("�ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("̾�������ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("̾����̾�ˤ����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	return confirm("�����åվ���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�����åդ������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? page_header('�����åվ����ѹ�', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�������åվ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->st_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">������ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=50 <?=value(str_repeat('*', strlen($fetch->st_password)))?>>
			<span class="note">��Ⱦ�ѱѿ�������ʸ���ޤǡ�</span>
		</td>
	</tr>
<?
if ($s_staff_auth == 1) {
?>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="auth"><? select_staff_auth($fetch->st_auth) ?></select></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=10 <?=value($fetch->st_name1)?>>��
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=10 <?=value($fetch->st_name2)?>>̾
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=30 <?=value($fetch->st_name1_kana)?>>��
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=30 <?=value($fetch->st_name2_kana)?>>̾
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��°</td>
		<td class="n1">
			<input class="kanji" type="text" name="section" size=50 maxlength=100 <?=value($fetch->st_section)?>>
			<span class="note">�ʣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1">
			<select name="status"><? select_staff_status($fetch->st_status) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->st_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǽ���������</td>
		<td class="n1"><?=format_datetime($fetch->st_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="staff_id" <?=value($staff_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<?
if ($s_staff_auth == 1) {
?>
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<?
}
?>
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
