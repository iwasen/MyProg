<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:����ǯ���ޥ����ѹ�����
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
set_global('master', '�ޥ��������ý����Ϣ�ޥ������ƥʥ�', '����ǯ���ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_chikunen WHERE cn_chikunen_cd = $cn_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("����ǯ��ID������", __FILE__);
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
	if (f.name.value == "") {
		alert("����ǯ��̾�����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.from.value == "") {
		alert("����ǯ���ξ�¤����Ϥ��Ƥ���������");
		f.from.focus();
		return false;
	}
	if (!num_chk(f.from.value)){
		alert("����ǯ���ξ�¤�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.from.focus();
		return(false);
	}
	if (f.to.value == "") {
		alert("����ǯ���β��¤����Ϥ��Ƥ���������");
		f.to.focus();
		return false;
	}
	if (!num_chk(f.to.value)){
		alert("����ǯ���β��¤�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.to.focus();
		return(false);
	}
	return confirm("����ǯ���ޥ�������򹹿����ޤ�����������Ǥ�����");
}
function delete_check(f) {
	return confirm("����ǯ��̾�������ޤ�����������Ǥ�����");
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
		<td class="m0" colspan=2>������ǯ����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">����ǯ��̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=70 maxlength=25 <?=value($fetch->cn_chikunen_text)?>>
		</td>
	<tr>
		<td class="m1">����ǯ���ξ��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="from" size=20 maxlength=4 <?=value($fetch->cn_chikunen_from)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����ǯ���β���<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="to" size=20 maxlength=4 <?=value($fetch->cn_chikunen_to)?>>
			<span class="note">��Ⱦ�ѿ�����</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="cn_cd" <?=value($cn_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>