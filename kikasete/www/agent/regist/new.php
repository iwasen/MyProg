<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�����������My�ڡ���������Ͽɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

// ����������ȼ���
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>�������������Ұ�</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>�Х�����</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>���ϲ�ҼҰ�</option>', "\n";
}

//�ᥤ�����

set_global('My�ڡ���������Ͽ','');

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	document.form1.job_name.focus();
}
function OnChange_agent_type() {
	if (document.form1.type.value == 2) {
		main_agent.style.display = "block";
	} else {
		main_agent.style.display = "none";
	}
}
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
	if (!f.disp_flag[0].checked && !f.disp_flag[1].checked) {
		alert("ɽ�����֤����򤷤Ƥ���������");
		return false;
	}
	return confirm("�������������Ͽ�������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload='document.form1.name1.focus();'>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="regist.php" onsubmit="return onSubmit_form1(this)">
<table>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr><td align="center" class="m4"><b>��Ͽ�������ϲ���</b></td></tr>
			</table>
			<table width=100%>
				<tr>
					<td class="m0" colspan=2 align="left">�����ܾ���</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr width=>
					<td class="m1" width=200>̾���ʴ�����<?=MUST_ITEM?></td>
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
			</table>
			<table width=100%>
				<tr>
					<td class="m0" colspan=2>����̳�����</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="m1" width=200>���̾</td>
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
			<table width=100%>
				<tr>
					<td class="m0" colspan=2>���������</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="m1" width=200>���ʾҲ�ʸ</td>
					<td class="n1"><textarea class="kanji" name="self_introduction" cols=60 rows=5></textarea>
					<span class="note"><nobr>�����ѣ�����ʸ���ޤǡ�</nobr></span>
					</td>
				</tr>
				<tr>
					<td class="m1">ɽ������<?=MUST_ITEM?></td>
					<td class="n1">
					<input type="radio" name="disp_flag" value='t'>ɽ��
					<input type="radio" name="disp_flag" value='f'>��ɽ��
					</td>
				</tr>
				<tr>
					<td class="m1">�£ã�����</td>
					<td class="n1"><textarea class="alpha" name="bcc_addr" cols=60 rows=3></textarea>
					<span class="note"><nobr>��Ⱦ�ѱѿ���ʣ������ġ�</nobr></span>
					</td>
				</tr>
				<tr>
					<td class="m1">��̾</td>
					<td class="n1"><textarea class="kanji" name="signature" cols=60 rows=5></textarea>
					<span class="note"><nobr>�����ѣ�����ʸ���ޤǡ�</nobr></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="agent_id" <?=value($agent_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
