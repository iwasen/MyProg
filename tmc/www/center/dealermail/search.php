<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼�븡��������ϲ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/search.php");

// ����Ź�᡼��ID����
$dealer_mail_id = $_GET['dealer_mail_id'];

// �������ID����
$sql = "SELECT dml_search_id FROM t_dealer_mail WHERE dml_dealer_mail_id=$dealer_mail_id";
$result = db_exec($sql);
$search_id = pg_fetch_result($result, 0, 0);

// DB���鸡�������ɤ߹���
$search = new search_class;
$search->read_db($search_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	show_reset();
}
function onSubmit_form1(f) {
	f.target = "_self";
	f.action = "update.php";
}
function show_reset() {
	var f = document.form1;
	show_hide('sex_cd_c', f.sex_cd_flag.checked);
	show_hide('age_c', f.age_flag.checked);
	show_hide('dealer_cd_c', f.dealer_cd_flag.checked);
	show_hide('sql_c', f.sql_flag.checked);
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function dealer_select(name) {
	var url;
	url = "<?=$top?>/common/dealer_select.php?name=" + name + "&dealer_cd=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "dealer_select", "width=500,resizable=yes,scrollbars=yes");
}
function onclick_search() {
	var f = document.form1;
	f.target = "_blank";
	f.action = "<?=$top?>/common/search_result.php";
	f.submit();
}
//-->
</script>
</head>
<body onload="onLoad_body()">

<? center_header('����Ź�᡼���ۿ��ø������') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������������ꤷ�Ƥ���������</td>
	</tr>
</table>

<table border=0 cellspacing=2 cellpadding=2 width="100%">
	<tr class="subhead">
		<td colspan=2>�Ŀ;���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">����</td>
		<td class="n1">
			<input type="checkbox" name="sex_cd_flag" <?=checked($search->sex_cd)?> onclick="show_hide('sex_cd_c', checked)">���ꤹ��
			<span id="sex_cd_c">&nbsp;
				<input type="radio" name="sex_cd" <?=value_checked('1', $search->sex_cd)?>>����
				<input type="radio" name="sex_cd" <?=value_checked('2', $search->sex_cd)?>>����
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1">
			<input type="checkbox" name="age_flag" <?=checked($search->age_from || $search->age_to)?> onclick="show_hide('age_c', checked)">���ꤹ��
			<span id="age_c">&nbsp;
				<input class="number" type="text" name="age_from" size=4 value="<?=$search->age_from?>">�ͤ���
				<input class="number" type="text" name="age_to" size=4 value="<?=$search->age_to?>">�ͤޤ�
			</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>��������</td>
	</tr>
	<tr>
		<td class="m1">����Ź������</td>
		<td class="n1">
			<input type="checkbox" name="dealer_cd_flag" <?=checked($search->dealer_cd)?> onclick="show_hide('dealer_cd_c', checked)">���ꤹ��
			<span id="dealer_cd_c">&nbsp;
				<font class="note">������Ź�����ɤ򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
				<input type="button" value="����" onclick="dealer_select('dealer_cd')"><br>
				<textarea name="dealer_cd" cols=80 rows=3><?=$search->dealer_cd?></textarea>
			</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>����¾�ξ��</td>
	</tr>
	<tr>
		<td class="m1">�ɲãӣѣ�</td>
		<td class="n1">
			<input type="checkbox" name="sql_flag" <?=checked($search->sql)?> onclick="show_hide('sql_c', checked)">���ꤹ��
			<span id="sql_c">&nbsp;
				<font class="note">��SQLʸ��WHERE�������Ϥ��Ƥ���������</font><br>
				<textarea name="sql" cols=80 rows=5><?=$search->sql?></textarea>
			</span>
		</td>
	</tr>
</table>

<br>
<input type="button" value="�Ϳ�����" onclick="onclick_search()">
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='search'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="dealer_mail_id" <?=value($dealer_mail_id)?>>
<input type="hidden" name="mail_recv_flag" value="1">
</form>
</div>

<? center_footer() ?>

</body>
</html>
