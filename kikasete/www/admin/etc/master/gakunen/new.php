<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��ǯ�ޥ���������Ͽ����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// ��ǯ��
function select_attb() {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, 0), ">$i</option>\n";
	}
}

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '��ǯ�ޥ���', BACK_TOP);

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
	if (f.name.value == "") {
		alert("��ǯ̾�Τ����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.age_from.value == "") {
		alert("ǯ�𲼸¤����Ϥ��Ƥ���������");
		f.age_from.focus();
		return false;
	}
	if (f.age_to.value == "") {
		alert("ǯ���¤����Ϥ��Ƥ���������");
		f.age_to.focus();
		return false;
	}
	return confirm("��ǯ����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����ǯ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��ǯ̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="name" size=50 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ǯ�𲼸�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="age_from">
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	</tr>
		<td class="m1">ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="age_to">
			<span class="note">��Ⱦ�ѿ�����</span>
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
