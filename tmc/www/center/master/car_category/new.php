<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ּ參�ƥ��꿷����Ͽ����
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
	if (f.car_category_name.value == "") {
		alert("�ּ參�ƥ���̾�����Ϥ��Ƥ���������");
		f.car_category_name.focus();
		return false;
	}
	return confirm("�ּ參�ƥ������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.car_category_name.focus()">

<? center_header('�ޥ������ƥʥ󥹡üּ參�ƥ���ޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ����ּ參�ƥ����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ּ參�ƥ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="car_category_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
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
