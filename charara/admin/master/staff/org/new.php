<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ޥ������ƥʥ󥹡å����åմ�Ϣ�ޥ����ý�°�ޥ���
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('master_st', '�ޥ��������å����åմ�Ϣ�ޥ���', '��°�ޥ���', BACK_TOP);

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
	if (f.so_kind_cd.value == "") {
		alert("����̾�����򤷤Ƥ���������");
		f.so_kind_cd.focus();
		return false;
	}
	if (f.so_name.value == "") {
		alert("��°̾�����Ϥ��Ƥ���������");
		f.so_name.focus();
		return false;
	}
	return confirm("��°̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.so_kind_cd.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����°��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="so_kind_cd"><?=select_staff_kind('���򤷤Ƥ�������', '')?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">��°̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="so_name" size=50>
		</td>
	</tr>
	<tr>
		<td class="m1">��°̾�ʥ��ʡ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="so_name_kana" size=50>
		</td>
	</tr>
	<tr>
		<td class="m1">Ϣ����</td>
		<td class="n1">
			<textarea class="kanji" name="so_contact" cols=60 rows=10></textarea>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
