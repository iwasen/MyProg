<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:ô���Կ�����Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.tantousha_name.value == "") {
		alert("ô����̾�����Ϥ��Ƥ���������");
		f.tantousha_name.focus();
		return false;
	}
	if (f.from_name.value == "") {
		alert("�᡼��From̾�����Ϥ��Ƥ���������");
		f.from_name.focus();
		return false;
	}
	if (f.from_addr.value == "") {
		alert("�᡼��From���ɥ쥹�����Ϥ��Ƥ���������");
		f.from_addr.focus();
		return false;
	}
	if (f.mail_account.value == "") {
		alert("��������ȡ��ѥ���ɤ����Ϥ��Ƥ���������");
		f.mail_account.focus();
		return false;
	}
	return confirm("ô���Ԥ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.tantousha_name.focus()">

<? center_header('�ޥ������ƥʥ󥹡�ô���ԥޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ����ô���Ծ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">ô����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="tantousha_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼��From̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼��From���ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=50 maxlength=50>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������ȡ��ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_account" size=50 maxlength=50>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
