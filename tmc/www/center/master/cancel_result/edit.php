<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�᡼����ɲ����ͳ�ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$cancel_cd = $_GET['cancel_cd'];

// ��Ͽ�Ѥߥ᡼����ɲ����ͳ�������
$sql = "SELECT mcr_cancel_text FROM t_mail_cancel_result WHERE mcr_cancel_cd=" . sql_number($cancel_cd);
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
	if (f.cancel_text.value == "") {
		alert("�᡼����ɲ����ͳ�����Ϥ��Ƥ���������");
		f.cancel_text.focus();
		return false;
	}
	return confirm("�᡼����ɲ����ͳ�򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�᡼����ɲ����ͳ�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡å᡼����ɲ����ͳ�ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>���᡼����ɲ����ͳ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�᡼����ɲ����ͳ������</td>
		<td class="n1">
			<?=$cancel_cd?>
			<input type="hidden" name="cancel_cd" <?=value($cancel_cd)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼����ɲ����ͳ<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cancel_text" size=80 <?=value($fetch->mcr_cancel_text)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? center_footer() ?>

</body>
</html>
