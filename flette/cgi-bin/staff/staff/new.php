#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����åտ�����Ͽ����
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
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
	if (f.auth.value == "") {
		alert("���¤����򤷤Ƥ���������");
		f.auth.focus();
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
	return confirm("�����åդ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">

<? page_header('�����åտ�����Ͽ', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���륹���åվ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">������ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=50>
			<span class="note">��Ⱦ�ѱѿ�������ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="auth"><? select_staff_auth('') ?></select></td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=10>��
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=10>̾
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=30>��
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=30>̾
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��°</td>
		<td class="n1">
			<input class="kanji" type="text" name="section" size=50 maxlength=100>
			<span class="note">�ʣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1">
			<select name="status"><? select_staff_status('1') ?></select>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>

</body>
</html>
