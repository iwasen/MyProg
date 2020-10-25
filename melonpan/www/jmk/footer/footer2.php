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

$sql = "SELECT * FROM T_MAG_FOOTER WHERE footer_id='$footer_id'";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect("index.php");

$fetch = pg_fetch_object($result, 0);
$admin_msg = htmlspecialchars($fetch->admin_msg);
$access_day = $fetch->access_day;
$access_day_end = $fetch->access_day_end;
$user_footer = $fetch->user_footer;
$riba_footer = $fetch->riba_footer;
$letter_footer = $fetch->letter_footer;
$bakyun_footer = $fetch->bakyun_footer;
$modify_dt = $fetch->modify_dt;
if ($user_footer == 1) { $user = " checked"; }
if ($riba_footer == 1) { $riba = " checked"; }
if ($letter_footer == 1) { $letter = " checked"; }
if ($bakyun_footer == 1) { $bakyun = " checked"; }
$insert_year = sprintf("%4d",substr($access_day, 0, 4));
$insert_month = sprintf("%2d",substr($access_day, 4, 2));
$insert_day = sprintf("%2d",substr($access_day, 6, 2));
$insert_year_end = sprintf("%4d",substr($access_day_end, 0, 4));
$insert_month_end = sprintf("%2d",substr($access_day_end, 4, 2));
$insert_day_end = sprintf("%2d",substr($access_day_end, 6, 2));
$position_header = ($fetch->insert_position[0] =='1') ? ' checked' : '';
$position_footer = ($fetch->insert_position[1] =='1') ? ' checked' : '';
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
function OnSubmit_form1() {
	with (document.form1) {
	    if (admin_msg.value == "") {
	      alert("�إå������եå����ǡ��������Ϥ��Ƥ���������");
	      admin_msg.focus();
	      return false;
	    }
		var dt = new Date(insert_year.value, insert_month.value - 1, insert_day.value);
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
	return confirm("�������ޤ���������Ǥ�����");
}
function OnClick_sakujo() {
	if (confirm("������ޤ���������Ǥ�����")) {
    location.href = "footer5.php?footer_id=<?= $footer_id ?>";
	}
}

//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="footer4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">���إå������եå����ǡ�������</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="80%">
		</tr>
		<tr>
			<td class="m4"><nobr>�إå������եå����ǡ���</nobr></td>
			<td class="n6"><textarea name="admin_msg" cols=80 rows=15><?= $admin_msg ?></textarea></td>
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
				<select name="insert_year"><? select_year(date("Y"), '', $insert_year); ?></select>ǯ
				<select name="insert_month"><? select_month('', $insert_month); ?></select>��
				<select name="insert_day"><? select_day('', $insert_day); ?></select>��
				��
				<select name="insert_year_end"><? select_year(date("Y"), '', $insert_year_end); ?></select>ǯ
				<select name="insert_month_end"><? select_month('', $insert_month_end); ?></select>��
				<select name="insert_day_end"><? select_day('', $insert_day_end); ?></select>��
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="footer_id" value="<?= $footer_id ?>" >
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="OnClick_sakujo()">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
