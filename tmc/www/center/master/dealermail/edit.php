<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼��ƥ�ץ졼���Խ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ����Ź�᡼��ƥ�ץ졼�ȼ���
$sql = "SELECT dmt_header,dmt_footer FROM t_dealer_mail_template";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$header = $fetch->dmt_header;
	$footer = $fetch->dmt_footer;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("����Ź�᡼��ƥ�ץ졼�Ȥ򹹿����ޤ���������Ǥ�����");
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡�����Ź�᡼��ƥ�ץ졼��') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">������Ź�᡼��Υإå��ȥեå��Υƥ�ץ졼�Ȥ����Ϥ��Ƥ���������</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m1" width="20%">�إå�</td>
		<td class="n1" width="80%">
			<textarea class="kanji" name="header" cols=78 rows=10><?=htmlspecialchars($header)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">�եå�</td>
		<td class="n1">
			<textarea class="kanji" name="footer" cols=78 rows=10><?=htmlspecialchars($footer)?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
