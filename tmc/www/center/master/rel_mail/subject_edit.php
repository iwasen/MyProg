<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼���̾�ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// ���ϥѥ�᡼��
$vol_no = $_GET['vol_no'];
$car_category_cd = $_GET['car_category_cd'];

// ��Ͽ�Ѥߤη�̾����
$sql = "SELECT rls_subject FROM t_relation_subject WHERE rls_vol_no=$vol_no AND rls_car_category_cd=$car_category_cd";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.subject.value == "") {
		alert("��̾�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	return confirm("��̾����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.subject.focus()">

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��÷�̾') ?>

<div align="center">
<form method="post" name="form1" action="subject_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����³��졼�����᡼��η�̾�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">�ּ參�ƥ���</td>
		<td class="n1"><input type="hidden" name="car_category_cd" <?=value($car_category_cd)?>><?=decode_car_category($car_category_cd)?></td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1"><input type="text" class="kanji" name="subject" size=80 <?=value($fetch->rls_subject)?>></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
