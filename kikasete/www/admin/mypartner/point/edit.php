<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ݥ������Ϳ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ����ݥ������Ϳ', BACK_TOP);

$sql = "SELECT ppj_room_name,ppj_point_timing,ppj_point_rule,ppj_point_max,ppj_point_unit,ppj_point_bonus"
		. " FROM t_mp_pjt"
		. " WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
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
function sel_chk(c) {
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			return true;
	}
	return false;
}
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onsubmit_form1(f) {
	if (!sel_chk(f.point_timing)) {
		alert("��Ϳ���������򤷤Ƥ���������");
		f.point_timing[0].focus();
		return false;
	}
	if (!sel_chk(f.point_rule)) {
		alert("���ܥ롼������򤷤Ƥ���������");
		f.point_rule[0].focus();
		return false;
	}
	if (f.point_max.value == "") {
		alert("������������ݥ���ȿ������Ϥ��Ƥ���������");
		f.point_max.focus();
		return false;
	}
	if (!num_chk(f.point_max.value)) {
		alert("������������ݥ���ȿ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.point_max.focus();
		return false;
	}
	if (f.point_unit.value == "") {
		alert("�ݥ���ȿ������Ϥ��Ƥ���������");
		f.point_unit.focus();
		return false;
	}
	if (!num_chk(f.point_unit.value)) {
		alert("�ݥ���ȿ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.point_unit.focus();
		return false;
	}
	if (!sel_chk(f.point_bonus)) {
		alert("�ܡ��ʥ���������򤷤Ƥ���������");
		f.point_bonus[0].focus();
		return false;
	}
	return true;
}
function disp_point_unit(rule) {
	var f = document.form1;
	var c = document.getElementById("point_unit");
	if (f.point_rule[0].checked)
		c.innerText = "��ȯ������";
	else if (f.point_rule[1].checked)
		c.innerText = "��ȯ����";
	else if (f.point_rule[2].checked)
		c.innerText = "��";
}
//-->
</script>
</head>
<body onload="disp_point_unit()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��<?=htmlspecialchars($fetch->ppj_room_name)?></td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=get_return_page()?>'">
		</td>
	</tr>
</table>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr class="lc">
		<td class="lt">�ݥ������Ϳ�롼��</td>
		<td class="lb">&nbsp;</td>
	</tr>
	<tr class="lc">
		<td valign="top" width="65%">
			����������
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="90%">
			<tr>
				<th class="m1" width="50%">��Ϳ����</th>
				<td class="n1" width="50%">
					<input type="radio" name="point_timing" <?=value_checked('1', $fetch->ppj_point_timing)?>>���<br>
					<input type="radio" name="point_timing" <?=value_checked('2', $fetch->ppj_point_timing)?>>����
				</td>
			</tr>
			<tr>
				<th class="m1">���ܥ롼��</th>
				<td class="n1">
					<input type="radio" name="point_rule" <?=value_checked('1', $fetch->ppj_point_rule)?> onclick="disp_point_unit()">ȯ������<br>
					<input type="radio" name="point_rule" <?=value_checked('2', $fetch->ppj_point_rule)?> onclick="disp_point_unit()">��ȯ����<br>
					<input type="radio" name="point_rule" <?=value_checked('3', $fetch->ppj_point_rule)?> onclick="disp_point_unit()">���
				</td>
			</tr>
			<tr>
				<th class="m1">������������ݥ���ȿ�</th>
				<td class="n1"><input type="text" name="point_max" <?=value($fetch->ppj_point_max)?> size="10">�Ύߎ��ݎ�</td>
			</tr>
			<tr>
				<th class="m1">�ݥ���ȿ�</th>
				<td class="n1"><span id="point_unit"></span><input type="text" name="point_unit" <?=value($fetch->ppj_point_unit)?> size="10">�Ύߎ��ݎ�</td>
			</tr>
			</table>
		</td>
		<td valign="top" width="35%">
			���ܡ��ʥ�����
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="90%">
			<tr>
				<th class="m1">�ܡ��ʥ�����</th>
				<td class="n1">
					<input type="radio" name="point_bonus" <?=value_checked('t', $fetch->ppj_point_bonus)?>>ͭ��<br>
					<input type="radio" name="point_bonus" <?=value_checked('f', $fetch->ppj_point_bonus)?>>̵��<br>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<div align="center">
<input type="submit" value="��������">
<input type="reset" value="�����᤹">��
</div>

<hr class="footer">

<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr class="lc">
		<td valign="top">
			���ݥ������Ϳ�����ޥ���
			<input type="button" value="����" onclick="location.href='p_master_edit.php?pjt_id=<?=$pjt_id?>'">
		</td>
	</tr>
</table>
<?
$sql = "SELECT * FROM m_mp_point_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	sscanf($fetch->ppd_calc_date, '%2d%2d', $m, $d);
	$calc_date[$fetch->ppd_month] = "$m/$d";

	sscanf($fetch->ppd_give_date, '%2d%2d', $m, $d);
	$give_date[$fetch->ppd_month] = "$m/$d";
}
?>
<table border=1 cellspacing=0 cellpadding=0 width="100%">
	<tr align="center">
		<td class="m1"></td>
		<td class="m1">1��</td>
		<td class="m1">2��</td>
		<td class="m1">3��</td>
		<td class="m1">4��</td>
		<td class="m1">5��</td>
		<td class="m1">6��</td>
		<td class="m1">7��</td>
		<td class="m1">8��</td>
		<td class="m1">9��</td>
		<td class="m1">10��</td>
		<td class="m1">11��</td>
		<td class="m1">12��</td>
	</tr>
	<tr align="center">
		<td class="m1">�׻���</td>
<?
for ($i = 1; $i <= 12; $i++) {
?>
		<td class="n1"><?=$calc_date[$i]?></td>
<?
}
?>
	</tr>
	<tr align="center">
		<td class="m1">��Ϳ��</td>
<?
for ($i = 1; $i <= 12; $i++) {
?>
		<td class="n1"><?=$give_date[$i]?></td>
<?
}
?>
	</tr>
</table>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
