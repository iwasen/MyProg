<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��ƻ�ܸ��ޥ���������Ͽ����
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
set_global('master', '�ޥ�����������ƻ�ܸ��ޥ���', '��ƻ�ܸ��ޥ���', BACK_TOP);

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
	if (f.ar_name.value == "") {
		alert("��ƻ�ܸ�̾�����Ϥ��Ƥ���������");
		f.ar_name.focus();
		return false;
	}
	return confirm("��ƻ�ܸ�̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.ar_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ������ƻ�ܸ���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">��ƻ�ܸ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ar_name" size=70 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ϰ��ʬ̾<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="aa_cd"><? select_area2('���򤷤Ƥ�������', '') ?></select>
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
