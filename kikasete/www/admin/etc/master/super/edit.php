<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ѡ��ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �����ѡ����������
function select_super_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ѡ��ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_super WHERE sp_super_cd=$super_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('�����ѡ��ɣĤ�����', __FILE__);
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
	if (f.sp_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.sp_order.focus();
		return false;
	}
	if (!num_chk(f.sp_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.sp_order.focus();
		return(false);
	}
	if (f.sp_name.value == "") {
		alert("�����ѡ�̾�����Ϥ��Ƥ���������");
		f.sp_name.focus();
		return false;
	}
	return confirm("�����ѡ��ޥ�������򹹿����ޤ�����������Ǥ�����");
}
function delete_check(f) {
	return confirm("�����ѡ�̾�������ޤ�����������Ǥ�����");
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
		<td class="m0" colspan=2>�������ѡ���������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="sp_order" size=20 maxlength=4 <?=value($fetch->sp_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ѡ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sp_name" size=70 maxlength=25 <?=value($fetch->sp_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_super_status($fetch->sp_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="super_cd" <?=value($super_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>