<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��̳��admin��Ͽ�ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �����Ծ��������
function select_admin_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�����ԥޥ���', BACK_TOP);

$sql = "SELECT * FROM m_admin WHERE ad_admin_id=$admin_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('�����ԣɣĤ�����', __FILE__);
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
function update_check(f) {
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("�ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name1_kana.focus();
		return false;
	}
	return confirm("�����Ծ���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�����Ԥ������ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>�������Ծ�������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->ad_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=20 <?=value($fetch->ad_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->ad_name1)?>>��
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->ad_name2)?>>̾
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->ad_name1_kana)?>>��
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->ad_name2_kana)?>>̾
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->ad_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->ad_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->ad_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->ad_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->ad_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->ad_kinmu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->ad_kinmu_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->ad_kinmu_post)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_admin_status($fetch->ad_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="admin_id" <?=value($admin_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
