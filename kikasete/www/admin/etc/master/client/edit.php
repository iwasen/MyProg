<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��ȥޥ�����Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '��ȥޥ���', BACK_TOP);

$sql = "SELECT * FROM m_client WHERE cl_client_id=$cl_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��ȣɣĤ�����', __FILE__);
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
	if (f.cl_name.value == "") {
		alert("���̾�����Ϥ��Ƥ���������");
		f.cl_name.focus();
		return false;
	}
	if (f.cl_name_kana.value == "") {
		alert("���̾(����)���Ϥ��Ƥ���������");
		f.cl_name_kana.focus();
		return false;
	}
	if (!num_chk(f.cl_zip1.value)){
		alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.cl_zip1.focus();
		return(false);
	}
	if (!num_chk(f.cl_zip2.value)){
		alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.cl_zip2.focus();
		return(false);
	}
	return confirm("��Ⱦ���򹹿����ޤ�����������Ǥ�����");
}
function delete_check(f) {
	return confirm("��Ȥ������ޤ�����������Ǥ�����");
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
		<td class="m0" colspan=2>����Ⱦ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cl_name" size=70 maxlength=50 <?=value($fetch->cl_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���̾(����)<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cl_name_kana" size=70 maxlength=50 <?=value($fetch->cl_name_kana)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1">
			<input class="number" type="text" name="cl_zip1" size=4 maxlength=3 <?=value(substr($fetch->cl_zip_code, 0, 3))?>>-
			<input class="number" type="text" name="cl_zip2" size=5 maxlength=4 <?=value(substr($fetch->cl_zip_code, 3, 4))?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<select name="cl_address1"><?select_area('', $fetch->cl_address1)?>
			</select>
			<br>
			<input class="kanji" type="text" name="cl_address2" size=70 maxlength=50 <?=value($fetch->cl_address2)?>>
			<span class="note">�ʻԶ��</span>
			<br>
			<input class="kanji" type="text" name="cl_address3" size=70 maxlength=50 <?=value($fetch->cl_address3)?>>
			<span class="note">��Į¼���ϡ�</span>
			<br>
			<input class="kanji" type="text" name="cl_address4" size=70 maxlength=50 <?=value($fetch->cl_address4)?>>
			<span class="note">�ʥӥ�̾��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ȼ�</td>
		<td class="n1">
			<select name="cl_gyoushu_cd"><?select_gyoushu('', $fetch->cl_gyoushu_cd, 2)?>
			</select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="cl_id" <?=value($cl_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>