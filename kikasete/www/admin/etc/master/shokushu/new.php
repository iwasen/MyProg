<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '����ޥ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onSubmit_form1(f) {
	if (f.ss_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.ss_order.focus();
		return false;
	}
	if (!num_chk(f.ss_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.ss_order.focus();
		return(false);
	}
	if (f.shokushu_name.value == "") {
		alert("����̾�����Ϥ��Ƥ���������");
		f.shokushu_name.focus();
		return false;
	}
	return confirm("����̾����Ͽ���ޤ�����������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.ss_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���뿦���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="ss_order" size=20 maxlength=4>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="shokushu_name" size=70 maxlength=25>
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