<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:�Ի�̾�ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('master', '�ޥ����������ϰ�ޥ���', '�Ի�̾�ޥ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.ci_todoufuken.value == "") {
		alert("��ƻ�ܸ�̾�����򤷤Ƥ���������");
		f.ci_todoufuken.focus();
		return false;
	}
	if (f.ci_shiku.value == "") {
		alert("�Ի�̾�����Ϥ��Ƥ���������");
		f.ci_shiku.focus();
		return false;
	}
	return confirm("�Ի�̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.ci_todoufuken.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����Ի�̾��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">��ƻ�ܸ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="ci_todoufuken"><?select_area('���򤷤Ƥ�������', '')?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">�Ի�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ci_shiku" size=40 maxlength=10>
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

<? page_footer() ?>
</body>
</html>
