<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ޥ������ƥʥ󥹡û��鳨��Ϣ�ޥ����þ��ʥޥ���
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
set_global('master_st', '�ޥ��������û��鳨��Ϣ�ޥ���', '���ʥޥ���', BACK_TOP);

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
	if (f.pd_product_cd.value == "") {
		alert("���ʥ����ɤ����Ϥ��Ƥ���������");
		f.pd_product_cd.focus();
		return false;
	}
	if (f.pd_name.value == "") {
		alert("����̾�����Ϥ��Ƥ���������");
		f.pd_name.focus();
		return false;
	}
	return confirm("���ʾ������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.pd_product_cd.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���뾦�ʾ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">���ʥ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="pd_product_cd" size=6 maxlength=6>
			<span class="note">��Ⱦ�ѱѿ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="pd_name" size=60>
		</td>
	</tr>
	<tr>
		<td class="m1">���ʾܺ�</td>
		<td class="n1">
			<textarea name="pd_explain" cols=50 rows=5></textarea>
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
