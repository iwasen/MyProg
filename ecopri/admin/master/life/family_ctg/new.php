<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��²���ƥ���ޥ���������Ͽ����
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
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '��²���ƥ���ޥ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function onSubmit_form1(f) {
	if (f.name.value == "") {
		alert("��²���ƥ���̾�����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.adult_num.value == "") {
		alert("��ͤοͿ������Ϥ��Ƥ���������");
		f.adult_num.focus();
		return false;
	}
	if (!num_chk(f.adult_num.value)){
		alert("��ͤοͿ���Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.adult_num.focus();
		return(false);
	}
	if (f.child_num.value == "") {
		alert("���ͤοͿ������Ϥ��Ƥ���������");
		f.child_num.focus();
		return false;
	}
	if (!num_chk(f.child_num.value)){
		alert("���ͤοͿ���Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.child_num.focus();
		return(false);
	}
	return confirm("��²���ƥ���̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����²���ƥ����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">��²���ƥ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=70 maxlength=25>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��Ϳ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="adult_num" size=20 maxlength=4>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���Ϳ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="child_num" size=20 maxlength=4>
			<span class="note">��Ⱦ�ѿ�����</span>
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
