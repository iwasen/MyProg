<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ּ參�ƥ����ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$car_category_cd = $_GET['car_category_cd'];

// ��Ͽ�Ѥ߼ּ參�ƥ���������
$sql = "SELECT * FROM t_car_category WHERE crc_car_category_cd=" . sql_char($car_category_cd);
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
	if (f.car_category_name.value == "") {
		alert("�ּ參�ƥ���̾�����Ϥ��Ƥ���������");
		f.car_category_name.focus();
		return false;
	}
	return confirm("�ּ參�ƥ���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("�ּ參�ƥ���������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡üּ參�ƥ���ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>���ּ參�ƥ����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�ּ參�ƥ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="car_category_name" size=50 maxlength=50 <?=value($fetch->crc_car_category_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="car_category_cd" <?=value($car_category_cd)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
