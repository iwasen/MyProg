<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����ӥ˥ޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// ����ӥ˾��������
function select_conveni_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '����ӥ˥ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_conveni WHERE cv_conveni_cd=$conveni_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('����ӥˣɣĤ�����', __FILE__);
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
	if (f.cv_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.cv_order.focus();
		return false;
	}
	if (!num_chk(f.cv_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.cv_order.focus();
		return(false);
	}
	if (f.cv_name.value == "") {
		alert("����ӥ�̾�����Ϥ��Ƥ���������");
		f.cv_name.focus();
		return false;
	}
	return confirm("����ӥ˥ޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("����ӥ�̾�������ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>������ӥ˾�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="cv_order" size=20 maxlength=4 <?=value($fetch->cv_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����ӥ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cv_name" size=70 maxlength=25 <?=value($fetch->cv_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_conveni_status($fetch->cv_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="conveni_cd" <?=value($conveni_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
