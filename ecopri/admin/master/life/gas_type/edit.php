<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:���������ץޥ����ѹ�����
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
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '���������ץޥ���', BACK_TOP);

$sql = "SELECT * FROM m_gas_type WHERE gt_gas_type_cd = $gt_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("����������ID������", __FILE__);
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function update_check(f) {
	if (f.gt_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.gt_order.focus();
		return false;
	}
	if (!num_chk(f.gt_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.gt_order.focus();
		return(false);
	}
	if (f.name.value == "") {
		alert("����������̾�����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.gt_calorie.value == "") {
		alert("Ǯ�̤����Ϥ��Ƥ���������");
		f.gt_calorie.focus();
		return false;
	}
	return confirm("���������ץޥ�������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("����������̾�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����������׾�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="gt_order" size=20 maxlength=4 <?=value($fetch->gt_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����������̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=70 maxlength=25 <?=value($fetch->gt_gas_type_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ǯ��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="gt_calorie" size=20 maxlength=5 <?=value($fetch->gt_calorie)?>>
			<span class="note">�ʾ������ʲ�����ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="gt_cd" <?=value($gt_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
