<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�֥�å��ꥹ�ȼ��̥ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�֥�å��ꥹ�ȼ��̥ޥ���', BACK_TOP);

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
	if (f.bl_name.value == "") {
		alert("�֥�å��ꥹ�ȼ���̾�����Ϥ��Ƥ���������");
		f.bl_name.focus();
		return false;
	}
	return confirm("�֥�å��ꥹ�ȼ���̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.bl_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ����֥�å��ꥹ�ȼ��̾�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">�֥�å��ꥹ�ȼ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="bl_name" size=70 maxlength=50>
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
