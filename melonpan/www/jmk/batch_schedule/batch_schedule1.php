<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ץ쥼��ȱ������
'******************************************************/

$title_text = $TT_batch_schedule;
$title_color = $TC_MASTER_MAINTE;

$sql = "select * from M_SH_LIST where SH_NAME='$sh'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$guide = $fetch->summary;
}

$pm = "$p1 $p2 $p3 $p4 $p5";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	fm = document.form1;
	if (fm.param.value == "") {
		alert("�ѥ�᡼�������Ϥ��Ƥ���������");
		fm.param.focus();
		return false;
	}
	return confirm("�������ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="batch_schedule2.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">��<?= $guide ?></td>
		</tr>
	</table>
	<table border=2 cellspacing=2 cellpadding=3 width="700">
		<tr>
			<td class="m4"><nobr>�������塼��ѥ�᡼��</nobr></td>
			<td class="n6" colspan="2">
				<input type="text" name="param" size="50" value="<?= $pm ?>">
			</td>
		</tr>
	</table><br>
<!--
	<input type="hidden" name="sch" value="<?= $param ?>" >
-->
	<input type="hidden" name="sh_name" value="<?= $sh ?>" >
	<input type="submit" value=" ���� ">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
