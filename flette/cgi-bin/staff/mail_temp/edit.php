#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����᡼��ƥ�ץ졼���ѹ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.subject.value == "") {
		alert("��̾�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.from_addr.value == "") {
		alert("From���ɥ쥹�����Ϥ��Ƥ���������");
		f.from_addr.focus();
		return false;
	}
	if (f.to_addr.value == "") {
		alert("To���ɥ쥹�����Ϥ��Ƥ���������");
		f.to_addr.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	if (f.action == "update.php")
		return confirm("�����᡼��ƥ�ץ졼�Ȥ򹹿����ޤ���������Ǥ�����");
	else if (f.action == "testsend.php")
		return confirm("�ƥ���������Ԥ��ޤ���������Ǥ�����");
	else
		return false;
}
function macro_list() {
	window.open("macro.php", "macro_list", "width=430,height=820,scrollbars=yes,resizable=yes");
}
//-->
</script>
</head>
<body>

<? page_header('�����᡼��ƥ�ץ졼�Ƚ���', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">�������᡼��ƥ�ץ졼�Ⱦ�������Ϥ��Ƥ���������</td>
		<td align="right"><font size="-1"><a href="javascript:void(0)" onclick="macro_list()">�����ޥ������</a></font></td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ƥ�ץ졼��̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->mt_guide)?></td>
	</tr>
	<tr>
		<td class="m1">��̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=200 <?=value($fetch->mt_subject)?>>
			<span class="note">�����ѡ�Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">From̾��</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=50 maxlength=50 <?=value($fetch->mt_from_name)?>>
			<span class="note">�����ѡ�Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">From���ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=70 maxlength=100 <?=value($fetch->mt_from_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">To���ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="to_addr" size=70 maxlength=100 <?=value($fetch->mt_to_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Cc���ɥ쥹</td>
		<td class="n1">
			<textarea name="cc_addr" cols=70 rows=3 class="alpha"><?=htmlspecialchars($fetch->mt_cc_addr)?></textarea>
			<br><span class="note">�ʥ���ޤޤ��ϲ��ԤǶ��ڤä�ʣ�����ϲ�ǽ��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Bcc���ɥ쥹</td>
		<td class="n1">
			<textarea name="bcc_addr" cols=70 rows=3 class="alpha"><?=htmlspecialchars($fetch->mt_bcc_addr)?></textarea>
			<br><span class="note">�ʥ���ޤޤ��ϲ��ԤǶ��ڤä�ʣ�����ϲ�ǽ��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ<?=MUST_ITEM?></td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mt_body)?></textarea></td>
	</tr>
</table>
<br>
<input type="hidden" name="template_id" <?=value($fetch->mt_template_id)?>>
<input type="submit" value="�ƥ�������" onclick="document.form1.action='testsend.php'">
<input type="submit" value="��������" onclick="document.form1.action='update.php'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
