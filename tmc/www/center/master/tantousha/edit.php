<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:ô�����ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$tantousha_id = $_GET['tantousha_id'];

// ��Ͽ�Ѥ�ô���Ծ������
$sql = "SELECT * FROM t_tantousha WHERE tts_tantousha_id=" . sql_number($tantousha_id);
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
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
function update_check(f) {
	if (f.tantousha_name.value == "") {
		alert("ô����̾�����Ϥ��Ƥ���������");
		f.tantousha_name.focus();
		return false;
	}
	if (f.from_name.value == "") {
		alert("�᡼��From̾�����Ϥ��Ƥ���������");
		f.from_name.focus();
		return false;
	}
	if (f.from_addr.value == "") {
		alert("�᡼��From���ɥ쥹�����Ϥ��Ƥ���������");
		f.from_addr.focus();
		return false;
	}
	if (f.mail_account.value == "") {
		alert("��������ȡ��ѥ���ɤ����Ϥ��Ƥ���������");
		f.mail_account.focus();
		return false;
	}
	return confirm("ô���Ԥ򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("ô���Ԥ������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡�ô���ԥޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��ô���Ծ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">ô����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="tantousha_name" size=50 maxlength=50 <?=value($fetch->tts_tantousha_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼��From̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=50 maxlength=50 <?=value($fetch->tts_from_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼��From���ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=50 maxlength=50 <?=value($fetch->tts_from_addr)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��������ȡ��ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_account" size=50 maxlength=50 <?=value($fetch->tts_mail_account)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="tantousha_id" <?=value($tantousha_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
