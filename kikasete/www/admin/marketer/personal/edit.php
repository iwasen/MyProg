<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ޡ��������Ŀ͸���
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
include("$inc/mr_permission.php");

// ��Ͽ��ˡ�����
function select_regist_type($sel) {
	echo "<option ", value_selected('0', $sel), ">�̾���Ͽ</option>";
	echo "<option ", value_selected('1', $sel), ">������Ͽ</option>";
}

// �ᥤ�����
set_global('marketer', '�ޡ�����������', '�ޡ��������Ŀ͸���', BACK_TOP);

$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('�ޡ��������ɣĤ�����', __FILE__);
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
	return confirm("�ޡ���������Ͽ����򹹿����ޤ���������Ǥ�����");
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
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mr_name1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mr_name2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mr_name1_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mr_name2_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mr_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mr_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mr_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mr_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mr_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mr_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mr_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- ̤���� -', $fetch->mr_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">�ȼ�</td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- ̤���� -', $fetch->mr_gyoushu_cd, 2)?></select></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- ̤���� -', $fetch->mr_shokushu_cd)?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">��̳��͹���ֹ�</td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3 <?=value(substr($fetch->mr_kinmu_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4 <?=value(substr($fetch->mr_kinmu_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳���ϰ�</td>
		<td class="n1"><select name="kinmu_area"><? select_area('- ̤���� -', $fetch->mr_kinmu_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�꣱</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣲ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣳ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr3)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�������ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20 <?=value($fetch->mr_kinmu_tel)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳��FAX�ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20 <?=value($fetch->mr_kinmu_fax)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->mr_kinmu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->mr_kinmu_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->mr_kinmu_post)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="type"><? select_marketer_type('', $fetch->mr_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">�����������</td>
		<td class="n1"><select name="agent_id"><? select_agent('- ̤���� -', $fetch->mr_agent_id, AG_MAIN)?></select></td>
	</tr>
	<tr>
		<td class="m1">�����������</td>
		<td class="n1">
<?
$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_mail_addr FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
			<a href="<?=$top?>/common/marketer_info?marketer_id=<?=$fetch2->mr_marketer_id?>" target="_blank"><?=$fetch2->mr_marketer_id?></a> <?=htmlspecialchars("$fetch2->mr_name1 $fetch2->mr_name2 <$fetch2->mr_mail_addr>")?>
<?
	}
} else
	echo '̵��';
?>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="status"><? select_marketer_status($fetch->mr_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ��ˡ</td>
		<td class="n1"><select name="regist_type"><? select_regist_type($fetch->mr_regist_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->mr_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->mr_update_date)?></td>
	</tr>
<?
if ($fetch->mr_type == MK_ASSISTANT) {
?>
	<tr>
		<td class="m0" colspan=2>������</td>
	</tr>
	<tr>
		<td class="m1">����������Ȥθ���</td>
		<td class="n1"><? input_permission($fetch->mr_permission) ?></td>
	</tr>
<?
} else {
?>
	<input type="hidden" name="permission" <?=value($fetch->mr_permission)?>>
<?
}
?>
</table>
<br>
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
