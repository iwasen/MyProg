<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ�ե������ѹ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('monitor', '��˥�������', '�ץ�ե������ư�ɲ�', BACK_TOP);

$sql = "SELECT * FROM t_profile_item WHERE pfi_profile_id=$profile_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
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
function add_sel() {
	var tb = document.getElementById("sel").getElementsByTagName("TBODY")[0];
	var tr = tb.laxxxhild;
	var new_row = tr.cloneNode(true);
	var input = new_row.getElementsByTagName("INPUT");
	input[0].value = parseInt(input[0].value) + 10;
	input[1].value = "";
	input[2].value = "";
	tb.insertBefore(new_row, null);
}
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (f.order.value == "") {
			alert("ɽ��������Ϥ��Ƥ���������");
			f.order.focus();
			return false;
		}
		if (!check_num(f.order.value)) {
			alert("ɽ�����Ⱦ�ѿ��������Ϥ��Ƥ���������");
			f.order.focus();
			return false;
		}
		if (f.profile_name.value == "") {
			alert("����̾���Ϥ��Ƥ���������");
			f.profile_name.focus();
			return false;
		}
		if (f["sel_order[]"].length) {
			for (var i = 0; i < f["sel_order[]"].length; i++) {
				if (!check_sel(f["sel_order[]"][i], f["sel_text[]"][i]))
					return false;
			}
		} else {
			if (!check_sel(f["sel_order[]"], f["sel_text[]"]))
				return false;
		}
		return confirm("�ɲåץ�ե�����򹹿����ޤ���������Ǥ�����");
	case "delete":
		return confirm("�ɲåץ�ե�����������ޤ���������Ǥ�����");
	}
}
function check_sel(sel_order, sel_text) {
	if (sel_order.value == "" && sel_text.value != "") {
		alert("�����ɽ��������Ϥ��Ƥ���������");
		sel_order.focus();
		return false;
	}
	if (!check_num(sel_order.value)) {
		alert("�����ɽ�����Ⱦ�ѿ��������Ϥ��Ƥ���������");
		sel_order.focus();
		return false;
	}
	if (sel_order.value != "" && sel_text.value == "") {
		alert("���������Ϥ��Ƥ���������");
		sel_text.focus();
		return false;
	}
	return true;
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ɲåץ�ե���������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ɽ����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="order" size=10 maxlength=10 <?=value($fetch->pfi_order)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="profile_name" size=80 <?=value($fetch->pfi_profile_name)?>>
			<span class="note">��ʸ�������¤Ϥ���ޤ����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ��<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="hidden_flag" <?=value_checked(DBFALSE, $fetch->pfi_hidden_flag)?>>ɽ��
			<input type="radio" name="hidden_flag" <?=value_checked(DBTRUE, $fetch->pfi_hidden_flag)?>>��ɽ��
		</td>
	</tr>
	<tr>
		<td class="m1">�����<?=MUST_ITEM?></td>
		<td class="n1">
			<table class="small" id="sel">
				<tr>
					<td>ɽ����</td>
					<td>�����</td>
				</tr>
<?
$sql = "SELECT pfs_profile_cd,pfs_select_text,pfs_order"
		. " FROM t_profile_sel"
		. " WHERE pfs_profile_id=$profile_id"
		. " ORDER BY pfs_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow > 0) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
				<tr>
					<td><input class="number" type="text" name="sel_order[]" size=5 maxlength=5 <?=value($fetch->pfs_order)?>></td>
					<td><input class="kanji" type="text" name="sel_text[]" size=60 <?=value($fetch->pfs_select_text)?>><input type="hidden" name="profile_cd[]" <?=value($fetch->pfs_profile_cd)?>></td>
				</tr>
<?
	}
} else {
?>
					<td><input class="number" type="text" name="sel_order[]" size=5 maxlength=5 value="10"></td>
					<td><input class="kanji" type="text" name="sel_text[]" size=60><input type="hidden" name="profile_cd[]" value=""></td>
<?
}
?>
			</table>
			<input type="button" value="������ɲ�" onclick="add_sel()">
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="profile_id" <?=value($profile_id)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
