<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:Pro���󥱡�������ɽ�ޥ���������Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('proenquete', '�У�異�󥱡��ȴ����åޥ������ƥʥ�', '����ɽ����', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.import_file.value == "") {
		alert("�ե�����̾�����Ϥ��Ƥ���������");
		f.import_file.focus();
		return false;
	}
	return confirm("�ܥ��󥱡�������ɽ�򥤥�ݡ��Ȥ��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.import_file.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update2.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ܥ��󥱡�������ɽ��CSV�ե�����̾�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ե�����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="file" name="import_file" size=70>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action" value="import">
</form>
</div>

<? page_footer() ?>
</body>
</html>
