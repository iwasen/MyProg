<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���ȿ�����Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ������Ͽ�ѹ�', BACK_TOP);

$affiliate_mgr_id = $_REQUEST['affiliate_mgr_id'];

$sql = "SELECT afm_affiliate_name,afm_affiliate_type,afm_condition,afm_appl_start_date,afm_appl_end_date,afm_affiliate_start_date,afm_affiliate_end_date"
		. " FROM t_affiliate_mgr"
		. " WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

$appl_start_y = get_datepart('Y', $fetch->afm_appl_start_date);
$appl_start_m = get_datepart('M', $fetch->afm_appl_start_date);
$appl_start_d = get_datepart('D', $fetch->afm_appl_start_date);

$appl_end_y = get_datepart('Y', $fetch->afm_appl_end_date);
$appl_end_m = get_datepart('M', $fetch->afm_appl_end_date);
$appl_end_d = get_datepart('D', $fetch->afm_appl_end_date);

$affiliate_start_y = get_datepart('Y', $fetch->afm_affiliate_start_date);
$affiliate_start_m = get_datepart('M', $fetch->afm_affiliate_start_date);
$affiliate_start_d = get_datepart('D', $fetch->afm_affiliate_start_date);

$affiliate_end_y = get_datepart('Y', $fetch->afm_affiliate_end_date);
$affiliate_end_m = get_datepart('M', $fetch->afm_affiliate_end_date);
$affiliate_end_d = get_datepart('D', $fetch->afm_affiliate_end_date);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (f.affiliate_name.value == "") {
			alert("���ե��ꥨ����̾�����Ϥ��Ƥ���������");
			f.affiliate_name.focus();
			return false;
		}
		if (f.condition.value == "") {
			alert("�������Ϥ��Ƥ���������");
			f.condition.focus();
			return false;
		}
		return confirm("���ե��ꥨ���Ȥ򹹿����ޤ���������Ǥ�����");
	case "delete":
		return confirm("���ե��ꥨ���Ȥ������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body onload="document.form1.affiliate_name.focus()">

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center">���ե��ꥨ������Ͽ</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">�����ե��ꥨ���Ⱦ���</td>
	</tr>
	<tr>
		<td class="m1" width="22%">���ե��ꥨ����̾</td>
		<td class="n1"><input class="kanji" type="text" name="affiliate_name" size=50 maxlength=50 <?=value($fetch->afm_affiliate_name)?>>
		<span class="note">�����ѣ���ʸ���ޤǡ�</span></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<input type="radio" name="affiliate_type" <?=value_checked(AFFILIATE_WEB, $fetch->afm_affiliate_type)?>>�Хʡ���WEB
			<input type="radio" name="affiliate_type" <?=value_checked(AFFILIATE_MAIL, $fetch->afm_affiliate_type)?>>�᡼��
		</td>
	</tr>
	<tr>
		<td class="m1">���</td>
		<td class="n1"><input type="text" size="50" name="condition" <?=value($fetch->afm_condition)?>></td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class=n1>
			<select name="appl_start_y"><? select_year(date('Y'), '', $appl_start_y) ?></select>ǯ
			<select name="appl_start_m"><? select_month('', $appl_start_m) ?></select>��
			<select name="appl_start_d"><? select_day('', $appl_start_d) ?></select>��
			��
			<select name="appl_end_y"><? select_year(date('Y'), '', $appl_end_y) ?></select>ǯ
			<select name="appl_end_m"><? select_month('', $appl_end_m) ?></select>��
			<select name="appl_end_d"><? select_day('', $appl_end_d) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">���ե��ꥨ���ȴ���</td>
		<td class=n1>
			<select name="affiliate_start_y"><? select_year(date('Y'), '', $affiliate_start_y) ?></select>ǯ
			<select name="affiliate_start_m"><? select_month('', $affiliate_start_m) ?></select>��
			<select name="affiliate_start_d"><? select_day('', $affiliate_start_d) ?></select>��
			��
			<select name="affiliate_end_y"><? select_year(date('Y'), '', $affiliate_end_y) ?></select>ǯ
			<select name="affiliate_end_m"><? select_month('', $affiliate_end_m) ?></select>��
			<select name="affiliate_end_d"><? select_day('', $affiliate_end_d) ?></select>��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick='history.back()'>
<input type="hidden" name="next_action">
<input type="hidden" name="affiliate_mgr_id" <?=value($affiliate_mgr_id)?>>
</form>

</div>

<? page_footer() ?>

</body>
</html>
