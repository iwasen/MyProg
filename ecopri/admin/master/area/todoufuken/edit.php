<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��ƻ�ܸ��ޥ����ѹ�����
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
set_global('master', '�ޥ����������ϰ�ޥ���', '��ƻ�ܸ��ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_area WHERE ar_area_cd = $ar_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("�ϰ�ID������", __FILE__);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.ar_name.value == "") {
		alert("��ƻ�ܸ�̾�����Ϥ��Ƥ���������");
		f.ar_name.focus();
		return false;
	}
	return confirm("��ƻ�ܸ��ޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("��ƻ�ܸ�̾�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����ƻ�ܸ���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">��ƻ�ܸ�ID<?=MUST_ITEM?></td>
		<td class="n1"><?=$fetch->ar_area_cd?></td>
	</tr>
	<tr>
		<td class="m1">��ƻ�ܸ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ar_name" size=70 maxlength=25 <?=value(htmlspecialchars($fetch->ar_area_name))?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ϰ��ʬ̾<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="aa_cd"><? select_area2('', $fetch->ar_area2_cd) ?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="ar_cd" <?=value($ar_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
