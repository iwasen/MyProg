<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// �����å��ܥå���ɽ������
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 cellspacing=0 cellpadding=0 width="100%">', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', ">", htmlspecialchars($fetch[1]), "</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0) {
		do {
			echo "<td width='10%'><br></td>\n";
		} while (++$col % $cols != 0);
		echo "</tr>\n";
	}

	echo "</table>\n";
}

// ���������ͼ���
function cell_name_value($age_option, $sex, $age) {
	global $cell;

	$name = "cell[$age_option][$sex][$age]";
	$value = $cell->age_option == $age_option ? $cell->send_num[$sex][$age] : '';

	return "name='$name' value='$value'";
}

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->marketer_id = $_SESSION['ss_owner_id'];
		$myenq->creator_id = $_SESSION['ss_marketer_id'];
	} else
		$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
}

$search = &$myenq->search;
$cell = &$myenq->cell;

// ����ȯ�����Υǥե����
$send_num = ($myenq->send_num) ? $myenq->send_num : 0;

// ���ڥ����ѥͥ륫�ƥ���
$special_panel_category_ary = array();
$sql = "SELECT pnc_category_id,pnc_category_name FROM t_panel_category WHERE pnc_display_order IS NOT NULL AND pnc_display_status=1 ORDER BY pnc_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$special_panel_category_ary[$fetch->pnc_category_id] = $fetch->pnc_category_name;
}
?>
<? marketer_header('My���󥱡���', PG_NULL, "", false) ?>

<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	change_age_type();
	change_age_option();
	change_sex_option();
	onclick_age_span();
	change_cell_option();
	change_mikikon_rate();
	change_area();
	onclick_rate_option();
<?
foreach ($special_panel_category_ary as $category_id => $category_name) {
?>
	special_panel_display(<?=$category_id?>);
<?
}
?>
}
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function all_check(chk, flag) {
	var f = document.form1;
	if (f[chk].length) {
		for (var i = 0; i < f[chk].length; i++)
			f[chk][i].checked = flag;
	} else
		f[chk].checked = flag;
}
function onMouseOver(c) {
	c.style.color = "#0000ff";
}
function onMouseOut(c) {
	c.style.color = "#000000";
}
function onclick_tab(n) {
	document.form1.age_option.value = n;
	change_age_option();
	change_sex_option();
}
function change_age_option() {
	var n = document.form1.age_option.value;
	for (var i = 1; i <= 3; i++) {
		if (i != n) {
			document.getElementById("tab" + i).style.background = "#eeeeee";
			document.getElementById("area" + i).style.display = "none";
		}
	}
	document.getElementById("tab" + n).style.background = "#a5f8fe";
	document.getElementById("area" + n).style.display = "";
	calc_cell_num();
}
function onclick_age_span() {
	var f = document.form1;
	var disable = !f.age_check.checked;
	document.getElementById("age_span").disabled = disable;
	f.age_span_from.disabled = disable;
	f.age_span_to.disabled = disable;
	if (disable) {
		f.age_span_from.value = "";
		f.age_span_to.value = "";
	}
	change_sex_option();
}
function onclick_cell() {
	var f = document.form1;
	var check = f.cell_option[1].checked;
	if (check) {
		f.age_span_from.value = f.age_from.value;
		f.age_span_to.value = f.age_to.value;
		f.age_check.checked = f.age_type.selectedIndex == 2 ? true : false;
		onclick_age_span();
	} else {
		f.age_from.value = f.age_span_from.value;
		f.age_to.value = f.age_span_to.value;
		if (f.age_check.checked)
			f.age_type.selectedIndex = 2;
		change_age_type();
	}
	change_cell_option();
}
function change_cell_option() {
	var f = document.form1;
	var check = f.cell_option[1].checked;
	document.getElementById("tr_sex").style.display = check ? "none" : "";
	document.getElementById("tr_age").style.display = check ? "none" : "";
	document.getElementById("tr_cell").style.display = check ? "" : "none";
	if (!check) {
		f.rate_option[0].checked = true;
		onclick_rate_option();
	}
//	document.getElementById("tr_send_rate").style.display = check ? "" : "none";
}
function calc_cell_num() {
	var f = document.form1;
	var age_option = f.age_option.value;
	var sex_s, sex_e, age_s, age_e;
	var count = 0;
	var sum = 0;
	switch (age_option) {
	case "1":
		sex_s = 1;
		sex_e = 2;
		age_s = 0;
		age_e = 0;
		break;
	case "2":
		if (f.sex_option2[0].checked) {
			sex_s = 0;
			sex_e = 0;
		} else {
			sex_s = 1;
			sex_e = 2;
		}
		age_s = 3;
		age_e = 14;
		break;
	case "3":
		if (f.sex_option3[0].checked) {
			sex_s = 0;
			sex_e = 0;
		} else {
			sex_s = 1;
			sex_e = 2;
		}
		age_s = 2;
		age_e = 8;
		break;
	}
	for (sex = sex_s; sex <= sex_e; sex++) {
		for (age = age_s; age <= age_e; age++) {
			var cell = f["cell[" + age_option + "][" + sex + "][" + age +"]"];
			if (cell.value != "") {
				if (check_num(cell.value)) {
					if (parseInt(cell.value) > 0) {
						count++;
						sum += parseInt(cell.value);
					}
				} else {
					alert("Ⱦ�ѿ��������Ϥ��Ƥ���������");
					cell.value = "";
					cell.focus();
					return;
				}
			}
		}
	}
	f.cell_sum.value = sum;
	f.cell_diff.value = parseInt(f.send_num.value) - sum;
	f.cell_count.value = count;
}
function onclick_sex_option() {
	change_sex_option();
	calc_cell_num();
}
function change_sex_option() {
	var age_5 = new Array(0, 0, 0, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70);
	var age_10 = new Array(0, 0, 20, 30, 40, 50, 60, 70);
	var f = document.form1;
	switch (f.age_option.value) {
	case "2":
		age_s = get_age_s(age_5);
		age_e = get_age_e(age_5);
		if (f.sex_option2[0].checked) {
			set_disable(2, 0, 0, 3, 14, age_s, age_e);
			set_disable(2, 1, 2, 3, 14, -1, -1);
		} else {
			set_disable(2, 0, 0, 3, 14, -1, -1);
			set_disable(2, 1, 2, 3, 14, age_s, age_e);
		}
		break;
	case "3":
		age_s = get_age_s(age_10);
		age_e = get_age_e(age_10);
		if (f.sex_option3[0].checked) {
			set_disable(3, 0, 0, 2, 8, age_s, age_e);
			set_disable(3, 1, 2, 2, 8, -1, -1);
		} else {
			set_disable(3, 0, 0, 2, 8, -1, -1);
			set_disable(3, 1, 2, 2, 8, age_s, age_e);
		}
		break;
	}
}
function get_age_s(range) {
	var f = document.form1;
	var age = (f.age_check.checked && f.age_span_from.value != "") ? f.age_span_from.value : 0;
	var age_s;
	for (age_s = 0; age_s < range.length; age_s++) {
		if (age < range[age_s])
			break;
	}
	return age_s;
}
function get_age_e(range) {
	var f = document.form1;
	var age = (f.age_check.checked && f.age_span_to.value != "") ? f.age_span_to.value : 100;
	var age_e;
	for (var age_e = range.length; age_e > 0; age_e--) {
		if (age >= range[age_e])
			break;
	}
	return age_e + 1;
}
function set_disable(age_option, sex_s, sex_e, age_s, age_e, age_1, age_2) {
	var f = document.form1;
	for (sex = sex_s; sex <= sex_e; sex++) {
		for (age = age_s; age <= age_e; age++) {
			var cell = f["cell[" + age_option + "][" + sex + "][" + age +"]"];
			var disable = (age < age_1 || age > age_2);
			if (disable)
				cell.value = "";
			cell.style.background = disable ? "#cccccc" : "#ffffff";
			cell.readonly = disable;
			cell.disabled = disable;
		}
	}
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}
function area_all_check(flag) {
	var f = document.form1;
	all_check("area_cd[]", flag);
	for (var i = 0; i < f["area_cd[]"].length; i++)
		onclick_area(f["area_cd[]"][i]);
}
function onclick_area(c) {
	var f = document.form1;
	if (c.checked) {
		var jitaku_area = f["jitaku_area[" + c.value + "][]"];
		if (jitaku_area.length) {
			for (var j = 0; j < jitaku_area.length; j++)
				jitaku_area[j].checked = true;
		} else {
			jitaku_area.checked = true;
		}
	}
	change_area();
}
function change_area() {
	var f = document.form1;
	var area_cd  = f["area_cd[]"];
	for (var i = 0; i < area_cd.length; i++) {
		var check = area_cd[i].checked;
		var jitaku_area = f["jitaku_area[" + area_cd[i].value + "][]"]
		if (jitaku_area.length) {
			for (var j = 0; j < jitaku_area.length; j++) {
				if (check)
					jitaku_area[j].disabled = false;
				else {
					jitaku_area[j].checked = false;
					jitaku_area[j].disabled = true;
				}
			}
		} else {
			if (check)
				jitaku_area.disabled = false;
			else {
				jitaku_area.checked = false;
				jitaku_area.disabled = true;
			}
		}
		var rate_area = f["rate_area[" + area_cd[i].value + "]"];
		if (check) {
			rate_area.style.background = "#ffffff";
			rate_area.readonly = false;
			rate_area.disabled = false;
		} else {
			rate_area.value = "";
			rate_area.style.background = "#cccccc";
			rate_area.readonly = true;
			rate_area.disabled = true;
		}
	}
	change_area_rate();
}
function onclick_rate_option() {
	var f = document.form1;
//	document.getElementById("tr_mikikon").style.display = f.rate_option[1].checked ? "" : "none";
	document.getElementById("tr_kyoju_area").style.display = f.rate_option[2].checked ? "" : "none";
	if (navigator.appName.charAt(0) == "N")
		document.getElementById("send_rate").rowSpan = f.rate_option[0].checked ? 2 : 3;
}
function check_cell() {
	var f = document.form1;
	if (f.cell_option[1].checked) {
		if (f.cell_diff.value != "0") {
			alert("ȯ�����˲���­������褦�Ǥ���");
			f.cell_diff.focus();
			return false;
		}
	}
	return true;
}
function check_rate() {
	var f = document.form1;
	var n, m, s = 0;
	if (f.rate_option[1].checked) {
		n = "rate_mikikon";
		m = 3;
	} else if (f.rate_option[2].checked) {
		n = "rate_area";
		m = 10;
	}
	if (m) {
		var fc = null;
		for (var i = 1; i <= m; i++) {
			var c = f[n + "[" + i + "]"];
			if (c.value != "") {
				if (!check_num(c.value)) {
					alert("�ۿ�����Ⱦ�ѿ��������Ϥ��Ƥ���������");
					c.focus();
					return false;
				}
				s += parseInt(c.value);
			}
			if (fc == null && !c.disabled)
				fc = c;
		}
		if (s != 100) {
			alert("�ۿ����ι�פ�100%�ˤʤäƤ��ʤ��褦�Ǥ���");
			if (fc != null)
				fc.focus();
			return false;
		}
	}
	return true;
}
function onsubmit_form1(f) {
	if (f.next_action.value != "search" && f.cell_option[1].checked) {
//		if (!check_cell_input())
//			return false;
		if (!check_cell())
			return false;
		if (!check_rate())
			return false;
	}
	return true;
}
function change_age_range() {
	var f = document.form1;
	if (!check_num(f.age_span_from.value)) {
		alert("ǯ����ϰϤ�Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.age_span_from.value = "";
		f.age_span_from.focus();
		return;
	}
	if (!check_num(f.age_span_to.value)) {
		alert("ǯ����ϰϤ�Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.age_span_to.value = "";
		f.age_span_to.focus();
		return;
	}
	change_sex_option();
	calc_cell_num();
}
function check_cell_input() {
	var f = document.form1;
	switch (f.age_option.value) {
	case "1":
		return cell_check(1, 1, 2, 0, 0);
	case "2":
		if (f.sex_option2[0].checked)
			return cell_check(2, 0, 0, 3, 14);
		else
			return cell_check(2, 1, 2, 3, 14);
	case "3":
		if (f.sex_option3[0].checked)
			return cell_check(3, 0, 0, 2, 8);
		else
			return cell_check(3, 1, 2, 2, 8);
	}
	return false;
}
function cell_check(age_option, sex_s, sex_e, age_s, age_e) {
	var f = document.form1;
	for (sex = sex_s; sex <= sex_e; sex++) {
		for (age = age_s; age <= age_e; age++) {
			var cell = f["cell[" + age_option + "][" + sex + "][" + age +"]"];
			if (!cell.disabled && cell.value == "") {
				alert("����Υ��뤬����ޤ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
				cell.focus();
				return false;
			}
		}
	}
	return true;
}
function change_mikikon_rate() {
	var f = document.form1;
	if (!f.mikikon[0].checked) {
		if (f.rate_option[1].checked) {
			f.rate_option[0].checked = true;
			onclick_rate_option();
		}
		document.getElementById("id_mikikon").disabled = true;
	} else
		document.getElementById("id_mikikon").disabled = false;
}
function change_area_rate() {
	var f = document.form1;
	var n = 0;
	for (var i = 0; i < f["area_cd[]"].length; i++) {
		if (f["area_cd[]"][i].checked)
			n++;
	}
	if (n == 1) {
		if (f.rate_option[2].checked) {
			f.rate_option[0].checked = true;
			onclick_rate_option();
		}
		document.getElementById("id_area").disabled = true;
	} else
		document.getElementById("id_area").disabled = false;
}
function special_panel_display(id) {
	var f = document.form1;
	if (f["special_panel" + id + "_flag"].checked) {
		document.all("special_panel" + id + "_c").style.display = "";
	} else {
		document.all("special_panel" + id + "_c").style.display = "none";
		all_check("special_panel[" + id + "][]", false);
	}
}

window.onload = function() {
  onload_body();
}
//-->
</script>
<style type="text/css">
.tab
{
	cursor: hand;
}
</style>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>

			<form method="post" name="form1" action="mkk_myenq_update3.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="90%">
				<tr>
					<td bgcolor="#eff7e8">����������</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff">�ƹ��ܤ��������򤷤Ƥ��ʤ����ϡ��������򤷤Ƥ������Ʊ���Ǥ���<BR>
<div class="footer_text">����<span style="color:#FF0000;">������ꡢ�Ҷ���̵ͭ��Ʊ���²�λ���ϡ�<a href="myenq_price.php" target="_blank">���ץ��������</a>��������ޤ���</span></div></td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%">����ȯ����</td>
								<td width="85%" bgcolor="#ffeecc">
									<select name="send_num" onchange="calc_cell_num()"><? select_max_send_num($send_num); ?> ��</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">ȯ��������<br>(�������)</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="radio" name="cell_option" <?=value_checked(1, $cell->cell_option)?> onclick="onclick_cell()">���ʤ�&nbsp;
									<input type="radio" name="cell_option" <?=value_checked(2, $cell->cell_option)?> onclick="onclick_cell()">����
								</td>
							</tr>
							<tr id="tr_sex">
								<td bgcolor="#eeeeee">����</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="radio" name="sex" <?=value_checked('', $search->sex)?>>���ꤷ�ʤ�&nbsp;
									<input type="radio" name="sex" <?=value_checked('1', $search->sex)?>>����&nbsp;
									<input type="radio" name="sex" <?=value_checked('2', $search->sex)?>>����
								</td>
							</tr>
							<tr id="tr_age">
								<td bgcolor="#eeeeee">ǯ��</td>
<?
// ǯ�������ˡ
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
?>
								<td bgcolor="#ffeecc">
									<select name="age_type" onchange="change_age_type()">
										<option <?=value_selected('', $age_type)?>>���������򤷤Ƥ�������</option>
										<option <?=value_selected('1', $age_type)?>>5�й�ߤ�ǯ��</option>
										<option <?=value_selected('2', $age_type)?>>Ǥ�դ�ǯ��</option>
									</select>
									<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age WHERE ae_age_cd>=3 ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 4);
?>
									</span>
									<span id="age_type2">
									<table border=0 width="100%">
										<tr>
											<td><input type="text" name="age_from" <?=value($search->age_from)?> size="4" maxlength="2">
											�͡�
											<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
											�� ��Ⱦ�ѿ�����</td>
										</tr>
									</table>
									</span>
								</td>
							</tr>
							<!-- ȯ��������(�������)������ -->
							<tr id="tr_cell" style="display:none">
								<td bgcolor="#eeeeee" width="15%">���̡�ǯ��</td>
								<td width="85%" bgcolor="#ffeecc">
<input type="hidden" name="age_option" <?=value($cell->cell_option == 2 ? $cell->age_option : 3)?>>
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
<tr>
<td class="footer_text">
��ȯ�����դ�֤���׾�硢1���դ����ꡢ5,000�ߤΥ��ץ�������⤬ɬ�פǤ���
</td>
</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" width=100%>
				<tr>
					<td width="150" valign="bottom">
						<table border="1" cellspacing="0" cellpadding="2" width=100% bgcolor="#eeeeee" style="border-bottom-style:none">
							<tr>
								<td id="tab1" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(1)">ǯ�����ꤷ�ʤ�</td>
								<td id="tab2" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(2)">5�й��</td>
								<td id="tab3" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(3)">10�й��</td>
							</tr>
						</table>
					</td>
					<td align="right">
						<input type="checkbox" name="age_check" value="1" <?=checked($search->age_from || $search->age_to)?> onclick="onclick_age_span()">Ǥ�դ�ǯ�����ꤹ��&nbsp;&nbsp;&nbsp;
						<span id="age_span"><input type="text" name="age_span_from" <?=value($search->age_from)?> size="6" onblur="change_age_range()">�С�����<input type="text" name="age_span_to" <?=value($search->age_to)?> size="6" onblur="change_age_range()">��</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
			<!-- ��ǯ�����ꤷ�ʤ����������ɽ�� -->
	<tr id="area1" style="display:none">
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width=40% frame="box">
				<tr>
					<td bgcolor="#eeeeee">����</td>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(1, 1, 0)?> size="5" onblur="calc_cell_num()"></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">����</td>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(1, 2, 0)?> value="" size="5" onblur="calc_cell_num()"></td>
				</tr>
			</table>
		</td>
	</tr>
			<!-- //��ǯ�����ꤷ�ʤ����������ɽ�� -->
			<!-- ��5�й�ߡ��������ɽ�� -->
	<tr id="area2" style="display:none">
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box">
				<tr>
					<td bgcolor="#eeeeee" colspan="2" rowspan=2>&nbsp;</td>
					<td bgcolor="#eeeeee" align="center">10��</td>
					<td bgcolor="#eeeeee" align="center" colspan=2>20��</td>
					<td bgcolor="#eeeeee" align="center" colspan=2>30��</td>
					<td bgcolor="#eeeeee" align="center" colspan=2>40��</td>
					<td bgcolor="#eeeeee" align="center" colspan=2>50��</td>
					<td bgcolor="#eeeeee" align="center" colspan=2>60��</td>
					<td bgcolor="#eeeeee" align="center" rowspan=2>70��<br>�ʾ�</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
					<td bgcolor="#eeeeee" align="center">��</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" colspan="2"><input type="radio" name="sex_option2" <?=value_checked(1, $cell->sex_option)?> onclick="onclick_sex_option()">����<br>����ʤ�</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(2, 0, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" rowspan="2"><input type="radio" name="sex_option2" <?=value_checked(2, $cell->sex_option)?> onclick="onclick_sex_option()"></td>
					<td bgcolor="#eeeeee" nowrap>����</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(2, 1, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" nowrap>����</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(2, 2, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
			</table>
		</td>
	</tr>
			<!-- //��5�й�ߡ��������ɽ�� -->
			<!-- ��10�й�ߡ��������ɽ�� -->
	<tr id="area3" style="display:none">
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box">
				<tr>
					<td bgcolor="#eeeeee" colspan="2">&nbsp;</td>
					<td bgcolor="#eeeeee" align="center">10��</td>
					<td bgcolor="#eeeeee" align="center">20��</td>
					<td bgcolor="#eeeeee" align="center">30��</td>
					<td bgcolor="#eeeeee" align="center">40��</td>
					<td bgcolor="#eeeeee" align="center">50��</td>
					<td bgcolor="#eeeeee" align="center">60��</td>
					<td bgcolor="#eeeeee" align="center">70��ʾ�</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" colspan="2"><input type="radio" name="sex_option3" <?=value_checked(1, $cell->sex_option)?> onclick="onclick_sex_option()">���̻���ʤ�</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(3, 0, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" rowspan="2"><input type="radio" name="sex_option3" <?=value_checked(2, $cell->sex_option)?> onclick="onclick_sex_option()"></td>
					<td bgcolor="#eeeeee">����</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(3, 1, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">����</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
					<td bgcolor="#ffeecc" align="center"><input type="text" <?=cell_name_value(3, 2, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
				</tr>
			</table>
		</td>
	</tr>
			<!-- //��10�й�ߡ��������ɽ�� -->
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="2" width="100%" frame="box">
			<tr>
				<td width="25%">�嵭ȯ�������</td><td><input type="text" name="cell_sum" size="15" readonly>��</td>
			</tr>
			<tr>
				<td>ȯ��������­��ư�׻�</td><td><input type="text" name="cell_diff" size="15" readonly>�� ��0�ˤʤ�褦�˾嵭�Ǥ����꤯��������</td>
			</tr>
			<tr>
				<td>��������</td><td><input type="text" name="cell_count" size="15" readonly>����</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><input type="submit" value="ȯ�����γ�ǧ" onclick="document.form1.next_action.value='send_check'"></td>
	</tr>
</table>
								</td>
							</tr>
							<!-- ȯ��������(�������)������ -->
							<tr>
								<td bgcolor="#eeeeee">̤����</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="mikikon" <?=value_checked('', $search->mikikon)?> onclick="change_mikikon_rate()">���ꤷ�ʤ�&nbsp;
									<input type="radio" name="mikikon" <?=value_checked('1', $search->mikikon)?> onclick="change_mikikon_rate()">̤��&nbsp;
									<input type="radio" name="mikikon" <?=value_checked('2', $search->mikikon)?> onclick="change_mikikon_rate()">����&nbsp;
									<input type="radio" name="mikikon" <?=value_checked('3', $search->mikikon)?> onclick="change_mikikon_rate()">����¾
<br />
 
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�ｻ�ϰ�</td>
								<td bgcolor="#ffeecc">
									<div>
										<input type="button" value="������" onclick="area_all_check(true)">
										<input type="button" value="�����؎�" onclick="area_all_check(false)">
									</div>
<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box">
	<tr>
		<td align="center" bgcolor="#ffffff"><b>���ꥢ</b></td>
		<td align="center" colspan="6">���ꥢ�����ƻ�ܸ������Ӥ�������</td>
	</tr>
<?
$cols = 6;
$jitaku_area_ary = explode(',', $search->jitaku_area);

$sql = "SELECT ar_area_cd,ar_area_name,ar_area3_cd FROM m_area ORDER BY ar_area_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$area_ary[$fetch->ar_area3_cd][$fetch->ar_area_cd] = $fetch->ar_area_name;
}

$sql = "SELECT ar3_area3_cd,ar3_area3_name FROM m_area3 ORDER BY ar3_area3_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$area3_ary[$fetch->ar3_area3_cd] = $fetch->ar3_area3_name;
}

foreach ($area3_ary as $area3_cd => $area3_name) {
	$area_checked = false;
	foreach (array_keys($area_ary[$area3_cd]) as $area_cd) {
		if (in_array($area_cd, $jitaku_area_ary)) {
			$area_checked = true;
			break;
		}
	}
?>
	<tr>
		<td width="17%" bgcolor="#ffffff"><input type="checkbox" name="area_cd[]" <?=value($area3_cd)?> <?=checked($area_checked)?> onclick="onclick_area(this)"><b><?=$area3_name?></b></td>
		<td width="83%" colspan=6>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
<?
	$col = 0;
	foreach ($area_ary[$area3_cd] as $area_cd => $area_name) {
		if ($col % $cols == 0) {
?>
				<tr>
<?
		}
?>
					<td width="10%"><input type="checkbox" name="jitaku_area[<?=$area3_cd?>][]" value="<?=$area_cd?>" <?=checked(in_array($area_cd, $jitaku_area_ary))?>><?=htmlspecialchars($area_name)?></td>
<?
		if (++$col % $cols == 0) {
?>
				</tr>
<?
		}
	}
	if ($col % $cols != 0) {
		do {
?>
					<td width='10%'><br></td>
<?
		} while (++$col % $cols != 0);
?>
				</tr>
<?
	}
?>
			</table>
		</td>
	</tr>
<?
}
?>
</table>
<br />
<span class="footer_text">��ȯ��������γ�������Ǥ��ޤ�������ץ�����դ�֤���פˤ��ơ����Υڡ��������ǳ������򤷤Ƥ���������</span>  
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�ȼ�</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
checkbox_common($sql, 'gyoushu', $search->gyoushu, 2);
?>
								</td>
							</tr>
							<? /*<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
checkbox_common($sql, 'shokushu', $search->shokushu, 2);
?>
								</td>
							</tr>*/ ?>
<? /*
							<tr>
								<td bgcolor="#eeeeee">�Ҷ���̵ͭ</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="have_child[]" <?=value_checked_multi('1', $search->have_child)?>>ͭ��&nbsp;
									<input type="checkbox" name="have_child[]" <?=value_checked_multi('2', $search->have_child)?>>̵��
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="housing_form[]" <?=value_checked_multi('1', $search->housing_form)?>>��ͷ���&nbsp;
									<input type="checkbox" name="housing_form[]" <?=value_checked_multi('2', $search->housing_form)?>>���罻��
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�֤ν�ͭ</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>�ȵ��ȼ֤��ͭ���Ƥ���&nbsp;
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>�ȵ��Τ߽�ͭ���Ƥ���&nbsp;
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>�ȵ��Ͻ�ͭ���Ƥ��ʤ�
								</td>
							</tr>
<?
// �ɲåץ��ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
							<tr>
								<td bgcolor="#eeeeee"><?=htmlspecialchars($profile_name)?></td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
checkbox_common($sql, 'ma_profile', $search->ma_profile, 2);
?>
								</td>
							</tr>
<?
}
?>							
							<tr>
								<td bgcolor="#eeeeee">�褯�Ԥ�����ӥ�</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�褯�Ԥ������ѡ�</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
								</td>
							</tr>

							<tr>
								<td bgcolor="#eeeeee">��̣������</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
								</td>
							</tr>
*/ ?>
<?
foreach ($special_panel_category_ary as $category_id => $category_name) {
	$special_panel_exists = false;
	if ($search->special_panel != '') {
		$sql = "SELECT EXISTS(SELECT * FROM t_panel WHERE pnl_category_id={$category_id} AND pnl_display_order IS NOT NULL AND pnl_display_status=1 AND pnl_panel_id IN ($search->special_panel))";
		$special_panel_exists = db_fetch1($sql) == DBTRUE;
	}
?>
							<tr>
								<td bgcolor="#eeeeee"><?=htmlspecialchars($category_name)?></td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="special_panel<?=$category_id?>_flag" <?=checked($special_panel_exists)?> onclick="special_panel_display(<?=$category_id?>)">���ꤹ��
									<span id="special_panel<?=$category_id?>_c">&nbsp;
										<input type="button" value="������" onclick="all_check(<?="'special_panel[$category_id][]'"?>,true)">
										<input type="button" value="�����" onclick="all_check(<?="'special_panel[$category_id][]'"?>,false)"><hr>
<?
	$sql = "SELECT pnl_panel_id,pnl_panel_name FROM t_panel WHERE pnl_category_id={$category_id} AND pnl_display_order IS NOT NULL AND pnl_display_status=1 ORDER BY pnl_display_order";
	checkbox_common($sql, "special_panel[$category_id]", $search->special_panel, 2);
?>
									</span>
								</td>
							</tr>
<?
}
?>
							<tr id="tr_send_rate" style="display:none">
								<td bgcolor="#eeeeee" rowspan=4 id="send_rate">�ۿ��������</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="rate_option" <?=value_checked(1, $cell->rate_option)?> onclick="onclick_rate_option()">���ꤷ�ʤ�&nbsp;&nbsp;&nbsp;
									<span id="id_mikikon"><input type="radio" name="rate_option" <?=value_checked(2, $cell->rate_option)?> onclick="onclick_rate_option()">̤����</span>&nbsp;&nbsp;&nbsp;
									<span id="id_area"><input type="radio" name="rate_option" <?=value_checked(3, $cell->rate_option)?> onclick="onclick_rate_option()">�ｻ���ꥢ</span>
								</td>
							</tr>
							<!-- ������� -->
							<tr id="tr_mikikon" style="display:none">
								<td bgcolor="#ffeecc">
									̤����<input type="text" name="rate_mikikon[1]" <?=value($cell->rate_option == 2 ? $cell->send_rate[1] : '')?> size="5">��&nbsp;&nbsp;&nbsp;
									������<input type="text" name="rate_mikikon[2]" <?=value($cell->rate_option == 2 ? $cell->send_rate[2] : '')?> size="5">��&nbsp;&nbsp;&nbsp;
									����¾��<input type="text" name="rate_mikikon[3]" <?=value($cell->rate_option == 2 ? $cell->send_rate[3] : '')?> size="5">��<br>
									<input type="submit" value="ȯ�����γ�ǧ" onclick="document.form1.next_action.value='send_check2'">
								</td>
							</tr>
							<tr id="tr_kyoju_area" style="display:none">
								<td bgcolor="#ffeecc">
<table border="0" cellspacing="0" cellpadding="0" width="100%" frame="box">
<?
$cols = 3;
$col = 0;
foreach ($area3_ary as $area3_cd => $area3_name) {
	if ($col % $cols == 0) {
?>
	<tr>
<?
	}
?>
		<td width="10%"><?=$area3_name?></td>
		<td width="20%"><input type="text" name="rate_area[<?=$area3_cd?>]" <?=value($cell->rate_option == 3 ? $cell->send_rate[$area3_cd] : '')?> size="5">��</td>
<?
	if (++$col % $cols == 0) {
?>
	</tr>
<?
	}
}
if ($col % $cols != 0) {
	do {
?>
		<td><br></td>
		<td><br></td>
<?
	} while (++$col % $cols != 0);
?>
	</tr>
<?
}
?>
</table>
<input type="submit" value="ȯ�����γ�ǧ" onclick="document.form1.next_action.value='send_check2'">
								</td>
							</tr>
							<tr></tr>
							<!-- ������� -->
						</table>

					</td>
				</tr>
			</table>

			<br>
			<input type="submit" value="���Ϳ�������" onclick="document.form1.next_action.value='search'">
			<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			<input type="hidden" name="type" value="5">
			</form>
<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>