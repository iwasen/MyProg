<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��ȥޥ�����Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

//�ᥤ�����

set_global2('project', '�ץ������ȴ����åޥ�����Ͽ', '��Ⱦ�����Ͽ', BACK_TOP);

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
	document.form1.cl_name.focus();
}
function onSubmit_form1(f) {
	if (f.cl_name.value == "") {
		alert("���̾�����Ϥ��Ƥ���������");
		f.cl_name.focus();
		return false;
	}
	if (f.cl_name_kana.value == "") {
		alert("���̾�ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.cl_name_kana.focus();
		return false;
	}
	return confirm("��Ⱦ������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="cl_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width=700>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td align="center" class="m4"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td class="m0" colspan=2>����Ͽ�����Ⱦ�������Ϥ��Ƥ�������</td>
				</tr>
				<tr>
					<td class="m1">���̾<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="cl_name" size=70 maxlength=50>
						<span class="note">�����ѣ���ʸ���ޤǡ�</span>
					</td>
				</tr>
				<tr>
					<td class="m1">���̾(����)<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="cl_name_kana" size=70 maxlength=50>
						<span class="note">�����ѣ���ʸ���ޤǡ�</span>
					</td>
				</tr>
				<tr>
					<td class="m1">͹���ֹ�</td>
					<td class="n1">
						<input class="number" type="text" name="cl_zip1" size=4 maxlength=3>-
						<input class="number" type="text" name="cl_zip2" size=5 maxlength=4>
						<span class="note">��Ⱦ�ѿ�����</span>
					</td>
				</tr>
				<tr>
					<td class="m1">����</td>
					<td class="n1">
						<select name="cl_address1"><?select_area('���򤷤Ʋ�����', '')?>
						</select>
						<br>
						<input class="kanji" type="text" name="cl_address2" size=70 maxlength=50>
						<span class="note">�ʻԶ��</span>
						<br>
						<input class="kanji" type="text" name="cl_address3" size=70 maxlength=50>
						<span class="note">��Į¼���ϡ�</span>
						<br>
						<input class="kanji" type="text" name="cl_address4" size=70 maxlength=50>
						<span class="note">�ʥӥ�̾��</span>
					</td>
				</tr>
				<tr>
					<td class="m1">�ȼ�</td>
					<td class="n1">
						<select name="cl_gyoushu_cd"><?select_gyoushu('���򤷤Ʋ�����', '', 2)?>
						</select>
					</td>
				</tr>
			</table><br>
			<div align="center">
			<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
			<input type="reset" value="�ꥻ�å�">
			<input type="button" value="����롡" onclick="history.back()">
			<input type="hidden" name="next_action" value="new">
			<input type="hidden" name="back_url" <?=value($back_url)?>>
			</div>
		</td>
	<tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
