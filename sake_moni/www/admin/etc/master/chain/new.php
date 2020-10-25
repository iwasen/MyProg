<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��������ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

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
// ���������
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

set_global('etc', '����¾�����åޥ������ƥʥ�', '��������ޥ���', BACK_TOP);
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
        if (f.gyoutai_cd.value == "") {
		alert("���֤����򤷤Ƥ���������");
		f.gyoutai_cd.focus();
		return false;
	}
	return confirm("��������̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.ch_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ������������������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="ch_order" size=20 maxlength=4>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ch_name" size=70 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
        <tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoutai_cd"><? select_gyoutai('- ���򤷤Ƥ������� -') ?></select></td>
	</tr>
	<tr>
		<td class="m1">���ꥢ<?=MUST_ITEM?></td>
		<td class="n1"><select name="chain_area_cd"><? select_chain_area('- ���򤷤Ƥ������� -') ?></select></td>
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
