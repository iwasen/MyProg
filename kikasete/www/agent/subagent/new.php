<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���֥���������ȿ�����Ͽ
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// ����������ȼ���
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>�������������Ұ�</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>�Х�����</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>���ϲ�ҼҰ�</option>', "\n";
}

// �ᥤ�����
set_global('���֥���������ȿ�����Ͽ', BACK_TOP);
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
	if (f.mail_addr_copy.value == "") {
		alert("�᡼�륢�ɥ쥹��ǧ�����Ϥ��Ƥ���������");
		f.mail_addr_copy.focus();
		return false;
	}
	if (f.mail_addr.value != f.mail_addr_copy.value) {
		alert("�᡼�륢�ɥ쥹�����פ��Ƥ��ޤ���");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("�ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.password_copy.value == "") {
		alert("�ѥ���ɳ�ǧ�����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.password.value != f.password_copy.value) {
		alert("�ѥ���ɤ����פ��Ƥ��ޤ���");
		f.password_copy.focus();
		return false;
	}
	if (f.agent_disc.value == "") {
		alert("����������ȼ��̤����򤷤Ƥ���������");
		f.agent_disc.focus();
		return false;
	}
	return confirm("���֥���������Ȥ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td align="center" class="m4" colspan=2><b>���֥������������Ͽ</b></td>
	</tr>
	<tr>
		<td class="m0" colspan=2 align="left">�����ܾ���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4>ǯ
			<select name="birthday_m"><?select_month(' ', '')?></select>��
			<select name="birthday_d"><?select_day(' ', '')?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<input type="radio" name="sex" value=1>����
			<input type="radio" name="sex" value=2>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹��ǧ<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr_copy" size=50 maxlength=100>
			<span class="note"><nobr>�ʳ�ǧ�Τ��ᡢ�⤦���٥᡼�륢�ɥ쥹�����Ϥ��Ƥ�����������</nobr></span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="password" name="password">
			<span class="note">��Ⱦ�ѱѿ�����������ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ���ɳ�ǧ<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="password" name="password_copy">
			<span class="note">�ʳ�ǧ�Τ��ᡢ�⤦���٥ѥ���ɤ����Ϥ��Ƥ�����������</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������ȼ���<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_disc"><? select_agent_disc('���򤷤Ʋ�����', '')?></select></td>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������Ƚ�°����</td>
		<td class="n1">
			<input class="kanji" type="text" name="agent_unit" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<div align="center">
	<input type="hidden" name="next_action">
	<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
	<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
