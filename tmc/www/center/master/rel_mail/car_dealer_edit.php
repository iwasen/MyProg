<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼��ּ��������Ź�����ѹ�����
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
$dealer_cd = $_GET['dealer_cd'];

// ��Ͽ�Ѥߤμּ��������Ź�������
$sql = "SELECT rlc_contents FROM t_relation_car_dealer WHERE rlc_vol_no=$vol_no AND rlc_dealer_cd='$dealer_cd'";
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
	if (f.body.value == "") {
		alert("�ּ��������Ź��������Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	return confirm("�ּ��������Ź�������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��üּ��������Ź����') ?>

<div align="center">
<form method="post" name="form1" action="car_dealer_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����³��졼�����᡼��μּ��������Ź��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">����Ź</td>
		<td class="n1"><input type="hidden" name="dealer_cd" <?=value($dealer_cd)?>><?=decode_dealer($dealer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">�ּ��������Ź����</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->rlc_contents)?></textarea></td>
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
