<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��̳��ȼ�ޥ����ѹ�����
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
set_global('etc', '����¾�����åޥ������ƥʥ�', '��̳��ȼ�ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_kinmu_gyoushu WHERE kg_gyoushu_cd=$gyoushu_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("��̳��ȼ�ID������", __FILE__);
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
	if (f.gyoushu_name.value == "") {
		alert("��̳��ȼ�̾�����Ϥ��Ƥ���������");
		f.gyoushu_name.focus();
		return false;
	}
	if (f.order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.order.focus();
		return false;
	}
	if (!num_chk(f.order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.order.focus();
		return(false);
	}
	return confirm("��̳��ȼ�ޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("��̳��ȼ�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����̳��ȼ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��̳��ȼ�ID</td>
		<td class="n1"><?=$gyoushu_cd?></td>
	</tr>
	<tr>
		<td class="m1">��̳��ȼ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="gyoushu_name" size=70 maxlength=25 <?=value($fetch->kg_gyoushu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="order" size=20 maxlength=4 <?=value($fetch->kg_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="gyoushu_cd" <?=value($gyoushu_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
