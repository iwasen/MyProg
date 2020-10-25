<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�إå������եå����ǡ�����Ͽ
'******************************************************/

$title_text = $TT_footer;
$title_color = $TC_MASTER;

if ($info == "on") {
	$sub = "SELECT MAX(footer_id) as max_footer_id FROM T_MAG_FOOTER";
	$sql = "SELECT admin_msg, user_footer, riba_footer, letter_footer, bakyun_footer, insert_position FROM T_MAG_FOOTER, (" . $sub . ") as MAX WHERE footer_id=max_footer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$admin_msg = htmlspecialchars($fetch->admin_msg);
		$user_footer = $fetch->user_footer;
		$riba_footer = $fetch->riba_footer;
		$letter_footer = $fetch->letter_footer;
		$bakyun_footer = $fetch->bakyun_footer;
		if ($user_footer == 1) { $user = " checked"; }
		if ($riba_footer == 1) { $riba = " checked"; }
		if ($letter_footer == 1) { $letter = " checked"; }
		if ($bakyun_footer == 1) { $bakyun = " checked"; }
		$position_header = ($fetch->insert_position[0] =='1') ? ' checked' : '';
		$position_footer = ($fetch->insert_position[1] =='1') ? ' checked' : '';
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.admin_msg.focus();
}
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
	if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
		else
		return false;
	}
}
function OnSubmit_form1() {
	with (document.form1) {
	    if (admin_msg.value == "") {
	      alert("�إå������եå����ǡ��������Ϥ��Ƥ���������");
	      admin_msg.focus();
	      return false;
	    }
		if (!check_date(insert_year.value, insert_month.value, insert_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			insert_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
		var dt = new Date(insert_year.value, insert_month.value - 1, insert_day.value);
		if (dd > dt) {
			alert("�������դϻ���Ǥ��ޤ���");
			insert_day.focus();
			return(false);
		}
		var dt_end = new Date(insert_year_end.value, insert_month_end.value - 1, insert_day_end.value);
		if (dt > dt_end) {
			alert("�����������դ��ϰϤ�����������ޤ���");
			insert_day_end.focus();
			return(false);
		}
		if (position_header.checked == false && position_footer.checked == false) {
			alert("�������֤���ꤷ�Ƥ���������");
			position_header.focus();
			return(false);
		}
	}
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
function OnClick_info() {
	location.href = "footer1.php?info=on";
}
function OnClick_clear() {
	location.href = "footer1.php?info=off";
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="footer3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">���إå������եå����ǡ�����Ͽ</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="80%">
		<tr>
			<td class="m4"><nobr>�إå������եå����ǡ���</nobr></td>
			<td class="n6">
				<input type="button" value="���ǡ������ԡ�" onclick='JavaScript:OnClick_info()'>
				<input type="button" value="���ꥢ" onclick='JavaScript:OnClick_clear()'><br>
				<textarea name="admin_msg" cols=80 rows=15><?= $admin_msg ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>�оݥ��ޥ�</nobr></td>
			<td class="n6">
				<input type="checkbox" name="user_footer" value="1" <?= $user ?>>���̥��ޥ���
				<input type="checkbox" name="letter_footer" value="1" <?= $letter ?>>����Ѥ�쥿����
				<input type="checkbox" name="riba_footer" value="1" <?= $riba ?>>��Фå��㡡
				<input type="checkbox" name="bakyun_footer" value="1" <?= $bakyun ?>>�С����塼��
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>��������</nobr></td>
			<td class="n6">
				<input type="checkbox" name="position_header" value="1" <?= $position_header ?>>�إå�����
				<input type="checkbox" name="position_footer" value="1" <?= $position_footer ?>>�եå�����
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>������</nobr></td>
			<td class="n6">
				<select name="insert_year"><? select_year(date("Y"), '', date("Y")); ?></select>ǯ
				<select name="insert_month"><? select_month('', date("m")); ?></select>��
				<select name="insert_day"><? select_day('', date("d")); ?></select>��
				��
				<select name="insert_year_end"><? select_year(date("Y"), '', date("Y")); ?></select>ǯ
				<select name="insert_month_end"><? select_month('', date("m")); ?></select>��
				<select name="insert_day_end"><? select_day('', date("d")); ?></select>��
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
