<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��²³���ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// ��²³����
function select_attb() {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, 0), ">$i</option>\n";
	}
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '��²³���ޥ���', BACK_TOP);

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
<body onload="document.form1.fr_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����²³����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��²³��̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="fr_name" size=50 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��²³����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_attb"><?select_attb()?></select>
			<span class="note">��-1�ʲ�:�� 0:�۶��Ԥ⤷���Ϸ��� 1�ʾ�:�Ҷ���</span>
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
