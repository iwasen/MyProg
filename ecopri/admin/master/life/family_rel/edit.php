<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:��²³���ޥ����ѹ�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// ��²³����
function select_attb($default) {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, $default), ">$i</option>\n";
	}
}

//��²����
function select_sex($default) {
	echo '<option ', value_selected(0, $default), ">�۶���</option>\n";
	echo '<option ', value_selected(1, $default), ">����</option>\n";
	echo '<option ', value_selected(2, $default), ">����</option>\n";
}

// �ᥤ�����
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '��²³���ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_family_rel WHERE fr_family_rel_cd = $fr_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("��²³��CD������", __FILE__);
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
	if (f.fr_order.value == "") {
		alert("ɽ����������Ϥ��Ƥ���������");
		f.fr_order.focus();
		return false;
	}
	if (!num_chk(f.fr_order.value)){
		alert("ɽ�������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.fr_order.focus();
		return(false);
	}
	if (f.fr_name.value == "") {
		alert("��²³��̾�Τ����Ϥ��Ƥ���������");
		f.fr_name.focus();
		return false;
	}
	return confirm("��²³���ޥ�������򹹿����ޤ�����������Ǥ�����");
}
function delete_check(f) {
	return confirm("��²³��̾�������ޤ�����������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����²³����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="fr_order" size=20 maxlength=4 <?=value($fetch->fr_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��²³��̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="fr_name" size=50 maxlength=25 <?=value($fetch->fr_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���/�Ҷ�<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_attb">
				<option value=1 <?=value_selected(1, $fetch->fr_attb)?>>���</option>
				<option value=2 <?=value_selected(2, $fetch->fr_attb)?>>�Ҷ�</option>
			</select>
		</td>
	</tr>
	</tr>
		<td class="m1">��²����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_sex"><?=select_sex($fetch->fr_sex)?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="fr_cd" <?=value($fr_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>