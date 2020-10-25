<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����åոĿ͸���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ��Ͽ��ˡ�����
function select_regist_type($sel) {
	echo "<option ", value_selected('0', $sel), ">�̾���Ͽ</option>";
	echo "<option ", value_selected('1', $sel), ">������Ͽ</option>";
}

// �ᥤ�����
set_global('staff', '�����åմ���', '�����åոĿ͸���', BACK_TOP);

$sql = "SELECT * FROM t_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('�����åգɣĤ�����', __FILE__);
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
	return confirm("�����å���Ͽ����򹹿����ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>�����ܾ���</td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->st_name1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->st_name2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->st_name1_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->st_name2_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->st_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->st_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->st_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->st_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->st_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->st_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->st_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">��̳��͹���ֹ�</td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3 <?=value(substr($fetch->st_kinmu_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4 <?=value(substr($fetch->st_kinmu_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳���ϰ�</td>
		<td class="n1"><select name="kinmu_area"><? select_area('- ̤���� -', $fetch->st_kinmu_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�꣱</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50 <?=value($fetch->st_kinmu_addr1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣲ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50 <?=value($fetch->st_kinmu_addr2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣳ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50 <?=value($fetch->st_kinmu_addr3)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�������ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20 <?=value($fetch->st_kinmu_tel)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳��FAX�ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20 <?=value($fetch->st_kinmu_fax)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->st_kinmu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->st_kinmu_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->st_kinmu_post)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="status"><? select_staff_status($fetch->st_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ��ˡ</td>
		<td class="n1"><select name="regist_type"><? select_regist_type($fetch->st_regist_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->st_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->st_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="staff_id" <?=value($staff_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
