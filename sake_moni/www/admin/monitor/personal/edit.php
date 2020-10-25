<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��˥����Ŀ͸���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/lifeinfo.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �����ʧ����ˡ
function select_payment_type($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>iMi�ݥ����</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>͹�ذ���</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>�ͥåȥХ�</option>', "\n";
}

// �ᥤ�����
set_global('monitor', '��˥�������', '��˥����Ŀ͸���', BACK_TOP);

$sql = "SELECT * FROM t_monitor LEFT JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('��˥����ɣĤ�����', __FILE__);
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
	if (f.name1.value == "") {
		alert("̾���ʴ����ˤ��������Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	if (f.birthday_y.value == "") {
		alert("��ǯ���������Ϥ��Ƥ���������");
		f.birthday_y.focus();
		return false;
	}
	if (f.mikikon[0].checked == false && f.mikikon[1].checked == false) {
		alert("̤���������򤷤Ƥ���������");
		f.mikikon[0].focus();
		return false;
	}
	if (f.sex[0].checked == false && f.sex[1].checked == false) {
		alert("���̤����򤷤Ƥ���������");
		f.sex[0].focus();
		return false;
	}
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
	if (f.shokugyou_cd.value == "") {
		alert("���Ȥ����򤷤Ƥ���������");
		f.shokugyou_cd.focus();
		return false;
	}
	return confirm("��˥�����Ͽ����򹹿����ޤ���������Ǥ�����");
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
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mn_name1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mn_name2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mn_name1_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mn_name2_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mn_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mn_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mn_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">̤����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="mikikon" <?=value_checked(1, $fetch->mn_mikikon)?>>̤��
			<input type="radio" name="mikikon" <?=value_checked(2, $fetch->mn_mikikon)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mn_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mn_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mn_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mn_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�ʼ����</td>
		<td class="n1">
			<input class="number" type="text" name="jitaku_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_jitaku_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="jitaku_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_jitaku_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�ʼ����<?=MUST_ITEM?></td>
		<td class="n1"><select name="jitaku_area"><? select_area('- ̤���� -', $fetch->mn_jitaku_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">���𽻽ꣲ</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr2" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- ̤���� -', $fetch->mn_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><?checkbox_chain($monitor_id, 4)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="status"><? select_monitor_status($fetch->mn_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">�ݥ����</td>
		<td class="n1">
			���ݥ����<input class="number" type="text" name="curr_point" size=10 maxlength=10 <?=value($fetch->mp_curr_point)?>>
			���ѥݥ����<input class="number" type="text" name="total_point" size=10 maxlength=10 <?=value($fetch->mp_total_point)?>>
			��<input type="checkbox" name="point_change" value="1">�ѹ�
		</td>
	</tr>
	<tr>
		<td class="m1">�����ʧ����ˡ</td>
		<td class="n1"><select name="payment_type"><? select_payment_type('- ̤���� -', $fetch->mn_payment_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">�����ֹ�</td>
		<td class="n1"><input class="number" type="text" name="account_num" size=20 maxlength=20 <?=value($fetch->mn_account_num)?>></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->mn_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->mn_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
