<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��²³���ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// �ᥤ�����
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '��²³���ޥ���', BACK_TOP);

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
	if (f.fr_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.fr_order.focus();
		return false;
	}
	if (!num_chk(f.fr_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.fr_order.focus();
		return(false);
	}
	if (f.fr_name.value == "") {
		alert("��²³��̾�Τ����Ϥ��Ƥ���������");
		f.fr_name.focus();
		return false;
	}
	if (f.fr_sex.value == "") {
		alert("��²���̤����򤷤Ƥ���������");
		f.fr_sex.focus();
		return false;
	}
	return confirm("��²³��̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.fr_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����²³����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="fr_order" size=20 maxlength=4>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��²³��̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="fr_name" size=50 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���/�Ҷ�<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_attb">
				<option value="" selected>���򤷤Ʋ�����</option>
				<option value=1>���</option>
				<option value=2>�Ҷ�</option>
			</select>
		</td>
	</tr>
		<td class="m1">��²����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_sex">
				<option value="" selected>���򤷤Ʋ�����</option>
				<option value=0>�۶���</option>
				<option value=1>����</option>
				<option value=2>����</option>
			</select>
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
