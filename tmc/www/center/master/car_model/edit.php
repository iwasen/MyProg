<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ּ��ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ���ϥѥ�᡼��
$car_model_cd = $_GET['car_model_cd'];

// ��Ͽ�Ѥ߼ּ�������
$sql = "SELECT * FROM t_car_model WHERE crm_car_model_cd=" . sql_char($car_model_cd);
$result = db_exec($sql);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
	if (f.car_model_name.value == "") {
		alert("�ּ�̾�����Ϥ��Ƥ���������");
		f.car_model_name.focus();
		return false;
	}
	if (f.car_category_cd.selectedIndex == 0) {
		alert("�ּ參�ƥ�������򤷤Ƥ���������");
		f.car_category_cd.focus();
		return false;
	}
	return confirm("�ּ�򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�ּ�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡üּ�ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>���ּ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ּ�̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="car_model_name" size=40 maxlength=20 <?=value($fetch->crm_car_model_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ּ參�ƥ���<?=MUST_ITEM?></td>
		<td class="n1"><select name="car_category_cd"><?=select_car_category(DEFAULT_SELECT, $fetch->crm_car_category_cd)?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="car_model_cd" <?=value($car_model_cd)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
