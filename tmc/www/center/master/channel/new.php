<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������ͥ뿷����Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �ּ�饤��ʥå�
function car_lineup() {
	$sql = "SELECT crc_car_category_cd,crc_car_category_name"
			. " FROM t_car_category"
			. " WHERE crc_car_category_cd<>0"
			. " ORDER BY crc_car_category_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo "<table border=0 cellspacing=0 cellpadding=1 width='100%' class='small'>\n";

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		echo "<tr class='subhead'>\n";
		echo "<td colspan=4>$fetch->crc_car_category_name</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>";
		echo "<textarea name='car_category_cd[$fetch->crc_car_category_cd]' cols=80 rows=5></textarea>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
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
	if (f.sales_channel_name.value == "") {
		alert("�������ͥ�̾�����Ϥ��Ƥ���������");
		f.sales_channel_name.focus();
		return false;
	}
	if (f.sales_channel_mark.value == "") {
		alert("��ά��������Ϥ��Ƥ���������");
		f.sales_channel_mark.focus();
		return false;
	}
	return confirm("�������ͥ����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.sales_channel_name.focus()">

<? center_header('�ޥ������ƥʥ󥹡��������ͥ�ޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ�����������ͥ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�������ͥ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sales_channel_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��ά����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sales_channel_mark" size=2 maxlength=1>
			<span class="note">��Ⱦ�ѣ�ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ּ�饤��ʥå�</td>
		<td class="n1"><? car_lineup() ?></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
