<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥ����åտ�����Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

// ���������
function select_privilege() {
	echo "<option ", value(''), ">- ���򤷤Ƥ������� -</option>";
	echo "<option ", value(PRIV_DHSTAFF), ">DH�����å�</option>";
	echo "<option ", value(PRIV_OPERATOR), ">���ڥ졼��</option>";
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
	return confirm("�����åդ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">

<? center_header('�ޥ������ƥʥ󥹡û�̳�ɥ����åաʥ��ڥ졼���˥ޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���륹���åվ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=50>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="privilege"><?=select_privilege()?></select></td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1"><textarea name="signature" cols=76 rows=5></textarea></td>
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
