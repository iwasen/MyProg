<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�桼��������Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");

set_global('�桼��������Ͽ', BACK_ADMIN);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	f.mail_addr.focus();
}
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
	if (f.name1.value == "") {
		alert("̾�������Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("̾�������Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
}
//-->
</script>
</head>
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���桼����������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="alpha" type="text" name="mail_addr" size=80>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" size=20 maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size="40"> ��<br>
			<input class="kanji" type="text" name="name2" size="40"> ̾
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? page_footer() ?>
</body>
</html>
