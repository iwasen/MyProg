<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��������ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ����������������
function select_chain_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// ���ꥢ�����
function select_chain_area($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ca_chain_area_cd,ca_area_name FROM m_chain_area ORDER BY ca_chain_area_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ca_chain_area_cd, $selected), '>', htmlspecialchars($fetch->ca_area_name), '</option>', "\n";
	}
}
//�����������
function select_gyoutai($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gt_gyoutai_cd,gt_gyoutai_name FROM m_gyoutai ORDER BY gt_gyoutai_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gt_gyoutai_cd, $selected), '>', htmlspecialchars($fetch->gt_gyoutai_name), '</option>', "\n";
	}
}
// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '��������ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_chain WHERE ch_chain_cd=$chain_cd";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('��������ɣĤ�����', __FILE__);
$fetch = pg_fetch_object($result, 0);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function update_check(f) {
	if (f.ch_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.ch_order.focus();
		return false;
	}
	if (!num_chk(f.ch_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.ch_order.focus();
		return(false);
	}
	if (f.ch_name.value == "") {
		alert("��������̾�����Ϥ��Ƥ���������");
		f.ch_name.focus();
		return false;
	}
	if (f.chain_area_cd.value == "") {
		alert("���ꥢ�����򤷤Ƥ���������");
		f.chain_area_cd.focus();
		return false;
	}
��������if (f.gyoutai_cd.value == "") {
		alert("���֤����򤷤Ƥ���������");
		f.gyoutai_cd.focus();
		return false;
	}
	return confirm("��������ޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("��������̾�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����������������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="ch_order" size=20 maxlength=4 <?=value($fetch->ch_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ch_name" size=70 maxlength=25 <?=value($fetch->ch_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
��������</tr>
        <tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoutai_cd"><? select_gyoutai('- ���򤷤Ƥ������� -', $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd, $fetch->ch_gyoutai_cd) ?></select></td>
	</tr>
	<tr>
		<td class="m1">���ꥢ<?=MUST_ITEM?></td>
		<td class="n1"><select name="chain_area_cd"><? select_chain_area('- ���򤷤Ƥ������� -', $fetch->ch_chain_area_cd) ?></select></td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_chain_status($fetch->ch_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="chain_cd" <?=value($chain_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
