#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:����������Ͽ����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.room_name.value == "") {
		alert("����̾�Τ����Ϥ��Ƥ���������");
		f.room_name.focus();
		return false;
	}
	return confirm("�����򿷵���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? page_header('����������Ͽ', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��������������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��°Ź��</td>
		<td class="n1"><?=decode_shop($shop_id)?></td>
	</tr>
	<tr>
		<td class="m1">����������<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_cd" size=2 maxlength=1>
			<span class="note">�����ѣ�ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_name" size=30 maxlength=30>
			<span class="note">�����ѡ�Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���URL</td>
		<td class="n1">
			<input class="alpha" type="text" name="link_url" size=80 maxlength=80>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="status" value="1" checked>ͭ��
			<input type="radio" name="status" value="9">̵��
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����</td>
		<td class="n1">
			<input class="number" type="text" name="display_order" size=5 maxlength=5>
			<span class="note">��Ⱦ�ѿ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="shop_id" <?=value($shop_id)?>>
<input type="submit" value="����Ͽ��">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
