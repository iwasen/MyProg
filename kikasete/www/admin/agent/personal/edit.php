<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����������ȸĿ͸���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");

// ����������ȼ���
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>�������������Ұ�</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>�Х�����</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>���ϲ�ҼҰ�</option>', "\n";
}

// ����������Ⱦ��������
function select_agent_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('1', $sel), ">����Ͽ</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}

// ����������ȼ��������
function select_agent_type($sel) {
	echo "<option ", value_selected('1', $sel), ">�����������</option>";
	echo "<option ", value_selected('2', $sel), ">���֥����������</option>";
}

// �ᥤ�����
set_global('agent', '����������ȴ���', '����������ȸĿ͸���', BACK_TOP);

$sql = "SELECT * FROM t_agent WHERE ag_agent_id=$agent_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('����������ȣɣĤ�����', __FILE__);
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
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == "") {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name2_kana.focus();
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
	return confirm("�������������Ͽ����򹹿����ޤ���������Ǥ�����");
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
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->ag_name1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->ag_name2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->ag_name1_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->ag_name2_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->ag_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->ag_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->ag_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->ag_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->ag_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->ag_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->ag_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������ȼ���<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_disc"><? select_agent_disc('', $fetch->ag_agent_disc) ?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->ag_kinmu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->ag_kinmu_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->ag_kinmu_post)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������Ƚ�°����</td>
		<td class="n1">
			<input class="kanji" type="text" name="agent_unit" size=50 maxlength=50 <?=value($fetch->ag_agent_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
	<tr>
		<td class="m1">���ʾҲ�ʸ</td>
		<td class="n1">
			<textarea class="kanji" name="self_introduction" cols=60 rows=5><?=htmlspecialchars($fetch->ag_self_introduction)?></textarea>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ������<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="disp_flag" <?=value_checked('t', $fetch->ag_disp_flag)?>>ɽ��
			<input type="radio" name="disp_flag" <?=value_checked('f', $fetch->ag_disp_flag)?>>��ɽ��
		</td>
	</tr>
	<tr>
		<td class="m1">�£ã�����</td>
		<td class="n1">
			<textarea class="alpha" name="bcc_addr" cols=60 rows=3><?=htmlspecialchars($fetch->ag_bcc_addr)?></textarea>
			<span class="note">��Ⱦ�ѱѿ�������޶��ڤ��ʣ���ġ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1">
			<textarea class="kanji" name="signature" cols=60 rows=5><?=htmlspecialchars($fetch->ag_signature)?></textarea>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="type"><? select_agent_type($fetch->ag_type) ?></select></td>
	</tr>
	<tr>
		<td class="m1">���֥����������</td>
		<td class="n1">
<?
$sql = "SELECT ag_agent_id,ag_name1,ag_name2,ag_mail_addr FROM t_agent WHERE ag_main_agent=$agent_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
			<a href="<?=$top?>/common/agent_info?agent_id=<?=$fetch2->ag_agent_id?>" target="_blank"><?=$fetch2->ag_agent_id?></a> <?=htmlspecialchars("$fetch2->ag_name1 $fetch2->ag_name2 <$fetch2->ag_mail_addr>")?>
<?
	}
} else
	echo '̵��';
?>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="status"><? select_agent_status($fetch->ag_status) ?></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->ag_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->ag_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="agent_id" <?=value($agent_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
