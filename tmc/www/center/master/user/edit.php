<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹡��桼�������ѹ�
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// �桼���������
$user_id = $_GET['user_id'];
$sql = "SELECT *"
		. " FROM t_user_personal"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " JOIN t_user_manage ON umg_user_id=ups_user_id"
		. " JOIN t_user_car ON ucr_user_id=ups_user_id"
		. " JOIN t_user_campaign ON ucm_user_id=ups_user_id"
		. " WHERE ups_user_id=$user_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect("list.php");
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
	if (f.name_kanji.value == "") {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name_kanji.focus();
		return false;
	}
	if (f.name_kana.value == "") {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name_kana.focus();
		return false;
	}
	if (f.zip_cd.value == "") {
		alert("͹���ֹ�����Ϥ��Ƥ���������");
		f.zip_cd.focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.age.value == "") {
		alert("ǯ������Ϥ��Ƥ���������");
		f.age.focus();
		return false;
	}
	return confirm("�桼������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�桼���������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡å桼�������ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>���桼���Ŀ;���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�桼���ɣ�</td>
		<td class="n1">
			<?=$fetch->ups_user_id?>
			<input type="hidden" name="user_id" <?=value($user_id)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">���֥�����</td>
		<td class="n1"><select name="status"><? select_user_status('', $fetch->ust_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����</td>
		<td class="n1">
			<input type="text" name="name_kanji" size=40 maxlength=41 <?=value($fetch->ups_name_kanji)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1">
			<input type="text" name="name_kana" size=40 maxlength=41 <?=value($fetch->ups_name_kana)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1">
			<input type="text" name="zip_cd" size=10 maxlength=7 <?=value($fetch->ups_zip_cd)?>>
			<span class="note">��Ⱦ�ѿ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����0����ƻ�ܸ���</td>
		<td class="n1">
			<input type="text" name="address0" size=40 maxlength=20 <?=value($fetch->ups_address0)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����1�ʻԶ跴Į¼��</td>
		<td class="n1">
			<input type="text" name="address1" size=40 maxlength=15 <?=value($fetch->ups_address1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����2</td>
		<td class="n1">
			<input type="text" name="address2" size=40 maxlength=20 <?=value($fetch->ups_address2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ֹ�</td>
		<td class="n1">
			<input type="text" name="tel_no" size=20 maxlength=20 <?=value($fetch->ups_tel_no)?>>
			<span class="note">��Ⱦ�ѿ�������ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���������ֹ�</td>
		<td class="n1">
			<input type="text" name="keitai_no" size=20 maxlength=20 <?=value($fetch->ups_keitai_no)?>>
			<span class="note">��Ⱦ�ѿ�������ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1">
			<input type="text" name="mail_addr" size=40 maxlength=40 <?=value($fetch->ups_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="sex_cd"><? select_seibetsu($fetch->ups_sex_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1">
			<input type="text" name="age" <?=value($fetch->ups_age)?>>
			<span class="note">��Ⱦ�ѿ�����ʸ���ޤǡ�</span>
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
