<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼��ּ��������Ź�����ѹ�����
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
$car_model_cd = $_GET['car_model_cd'];

// ��Ͽ�Ѥ߼ּ��������Ź�������
$sql = "SELECT flc_contents FROM t_follow_car_dealer WHERE flc_vol_no=$vol_no AND flc_dealer_cd='$dealer_cd' AND flc_car_model_cd=$car_model_cd";
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
	if (f.next_action.value == "ccreg") {
		f.target = "_blank";
		f.action = "<?=$top?>/common/cc_reg.php";
		return true;
	} else {
		if (f.body.value == "") {
			alert("�ּ��������Ź��������Ϥ��Ƥ���������");
			f.body.focus();
			return false;
		}
		f.target = "";
		f.action = "car_dealer_update.php";
		return confirm("�ּ��������Ź�������Ͽ���ޤ���������Ǥ�����");
	}
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��üּ��������Ź����') ?>

<div align="center">
<form method="post" name="form1" action="car_dealer_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>������ե����᡼��μּ��������Ź��������Ϥ��Ƥ���������</td>
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
		<td class="m1" width="20%">�ּ�</td>
		<td class="n1"><input type="hidden" name="car_model_cd" <?=value($car_model_cd)?>><?=decode_car_model($car_model_cd)?></td>
	</tr>
	<tr>
		<td class="m1">�ּ��������Ź����</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->flc_contents)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="���؎��������ݎ���Ͽ"onclick="document.form1.next_action.value='ccreg'">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='update'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="cc_start_date">
<input type="hidden" name="cc_end_date">
<input type="hidden" name="cc_no">
<input type="hidden" name="cc_url_name">
<input type="hidden" name="cc_jump_url">
<input type="hidden" name="cc_count" value=0>
</form>
</div>

<? center_footer() ?>

</body>
</html>
