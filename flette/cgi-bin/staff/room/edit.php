#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:���������ѹ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$sql = "SELECT * FROM m_room WHERE rm_room_id='$room_id'";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
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
	return confirm("��������򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? page_header('����������', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��������������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">����ID</td>
		<td class="n1"><?=$fetch->rm_room_id?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">��°Ź��</td>
		<td class="n1"><?=decode_shop($fetch->rm_shop_id)?></td>
	</tr>
	<tr>
		<td class="m1">����������<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_cd" size=2 maxlength=1 <?=value($fetch->rm_room_cd)?>>
			<span class="note">�����ѣ�ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_name" size=30 maxlength=30 <?=value($fetch->rm_room_name)?>>
			<span class="note">�����ѡ�Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���URL</td>
		<td class="n1">
			<input class="alpha" type="text" name="link_url" size=80 maxlength=80 <?=value($fetch->rm_link_url)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="status" <?=value_checked('1', $fetch->rm_status)?>>ͭ��
			<input type="radio" name="status" <?=value_checked('9', $fetch->rm_status)?>>̵��
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����</td>
		<td class="n1">
			<input class="number" type="text" name="display_order" size=5 maxlength=5 <?=value($fetch->rm_display_order)?>>
			<span class="note">��Ⱦ�ѿ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->rm_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǽ���������</td>
		<td class="n1"><?=format_datetime($fetch->rm_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="shop_id" <?=value($shop_id)?>>
<input type="hidden" name="room_id" <?=value($room_id)?>>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
