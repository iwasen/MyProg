<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�֥�å��ꥹ�ȼ��̥ޥ����ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '�֥�å��ꥹ�ȼ��̥ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_blacklist WHERE bl_blacklist_flg = $bl_flg";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("�֥�å��ꥹ�ȼ���flg������", __FILE__);
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
	if (f.bl_name.value == "") {
		alert("�֥�å��ꥹ�ȼ���̾�����Ϥ��Ƥ���������");
		f.bl_name.focus();
		return false;
	}
	return confirm("�֥�å��ꥹ�ȼ��̥ޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�֥�å��ꥹ�ȼ���̾�������ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>���֥�å��ꥹ�ȼ��̾�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ե饰</td>
		<td class="n1"><?=$fetch->bl_blacklist_flg?></td>
		</td>
	</tr>
	<tr>
		<td class="m1">�֥�å��ꥹ�ȼ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="bl_name" size=70 maxlength=50 <?=value($fetch->bl_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="bl_flg" <?=value($bl_flg)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
