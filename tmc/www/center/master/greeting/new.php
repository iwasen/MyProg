<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ΰ���ʸ������Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/select.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.from_month.selectedIndex == 0) {
		alert("ͭ�����ֳ��Ϸ�����򤷤Ƥ���������");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.selectedIndex == 0) {
		alert("ͭ�����ֳ����������򤷤Ƥ���������");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.selectedIndex == 0) {
		alert("ͭ�����ֽ�λ������򤷤Ƥ���������");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.selectedIndex == 0) {
		alert("ͭ�����ֽ�λ�������򤷤Ƥ���������");
		f.to_day.focus();
		return false;
	}
	if (f.greeting.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.to_day.focus();
		return false;
	}
	return confirm("����ΰ���ʸ����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.from_month.focus()">

<? center_header('�ޥ������ƥʥ󥹡û���ΰ���ʸ�ޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�������ΰ���ʸ�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ͭ������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_month"><? select_month(' ', '') ?></select>��
			<select name="from_day"><? select_day(' ', '') ?></select>��
			��
			<select name="to_month"><? select_month(' ', '') ?></select>��
			<select name="to_day"><? select_day(' ', '') ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����ʸ<?=MUST_ITEM?></td>
		<td class="n1"><textarea name="greeting" cols=80 rows=10></textarea></td>
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
