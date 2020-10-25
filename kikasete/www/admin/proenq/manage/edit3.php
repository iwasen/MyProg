<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">', "\n";
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

// セル設定値取得
function cell_namep_value($age_option, $sex, $age) {
	global $cell;

	$name = "cell[$age_option][$sex][$age]";
	$value = $cell->age_option == $age_option ? $cell->send_num[$sex][$age] : '';

	return "name='$name' value='$value'";
}

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
	$marketer_id = $pro_enq->marketer_id;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($marketer_id, $pro_enq_no);
}

$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
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
	show_hide('taisho_myenq_list_c', f.taisho_myenq_list_flag.checked);
	show_hide('jogai_myenq_list_c', f.jogai_myenq_list_flag.checked);
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
function all_check(radio, flag) {
	var f = document.form1;
	if (f[radio].length) {
		for (var i = 0; i < f[radio].length; i++)
			f[radio][i].checked = flag;
	} else
		f[radio].checked = flag;
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
	document.getElementById("tr_send_rate").style.display = check ? "" : "none";
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
					alert("半角数字で入力してください。");
					cell.value = "";
					cell.focus();
					return;
				}
			}
		}
	}
	f.cell_sum.value = sum;
	f.cell_diff.value = parseInt(f.sample_num.value) - sum;
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
	document.getElementById("tr_mikikon").style.display = f.rate_option[1].checked ? "" : "none";
	document.getElementById("tr_kyoju_area").style.display = f.rate_option[2].checked ? "" : "none";
	if (navigator.appName.charAt(0) == "N")
		document.getElementById("send_rate").rowSpan = f.rate_option[0].checked ? 2 : 3;
}
function check_cell() {
	var f = document.form1;
	if (f.cell_option[1].checked) {
		if (f.cell_diff.value != "0") {
			alert("サンプル数に過不足があるようです。");
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
					alert("配信割合は半角数字で入力してください。");
					c.focus();
					return false;
				}
				s += parseInt(c.value);
			}
			if (fc == null && !c.disabled)
				fc = c;
		}
		if (s != 100) {
			alert("配信割合の合計が100%になっていないようです。");
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
		alert("年齢の範囲は半角数字で入力してください。");
		f.age_span_from.value = "";
		f.age_span_from.focus();
		return;
	}
	if (!check_num(f.age_span_to.value)) {
		alert("年齢の範囲は半角数字で入力してください。");
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
				alert("空白のセルがあります。半角数字で入力してください。");
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
function show_hide(id, show) {
	document.all(id).style.display = show ? "" : "none";
}
function member_select(name) {
	var url;
	url = "<?=$top?>/../common/myenq_member_list.php?name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "member_list", "resizable=yes,scrollbars=yes,width=600");
}
//-->
</script>
<style type="text/css">
.tab
{
	cursor: hand;
}
</style>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update3.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■対象条件・サンプル割付の設定</td>
	</tr>
	<tr>
		<td class="m1" width="20%">本アンケート回収<br>目標サンプル数</td>
		<td class="n1" width="80%">
			<input type="text" name="sample_num" size=5 <?=value((int)$pro_enq->sample_num)?> onchange="calc_cell_num()"> サンプル</select>
		</td>
	</tr>
<?
// モニター全員をデフォルト
if ($search->type == '')
	$search->type = '0';
?>
	<tr>
		<td class="m1">対象モニター</td>
		<td class="n1">
			<input type="radio" name="type" <?=value_checked('0', $search->type)?>>全モニター&nbsp;
			<input type="radio" name="type" <?=value_checked('5', $search->type)?>>アクティブモニターのみ&nbsp;
			<input type="radio" name="type" <?=value_checked('2', $search->type)?>>リサーチパートナーのみ&nbsp;
			<input type="radio" name="type" <?=value_checked('6', $search->type)?>>ＧＭＯモニター
		</td>
	</tr>
	<tr>
		<td class="m1">セル指定</td>
		<td class="n1">
			<input type="radio" name="cell_option" <?=value_checked(1, $cell->cell_option)?> onclick="onclick_cell()">しない&nbsp;
			<input type="radio" name="cell_option" <?=value_checked(2, $cell->cell_option)?> onclick="onclick_cell()">する
		</td>
	</tr>
	<tr id="tr_sex">
		<td class="m1">性別</td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked('', $search->sex)?>>指定しない&nbsp;
			<input type="radio" name="sex" <?=value_checked('1', $search->sex)?>>男性&nbsp;
			<input type="radio" name="sex" <?=value_checked('2', $search->sex)?>>女性
		</td>
	</tr>
	<tr id="tr_age">
		<td class="m1">年代</td>
<?
// 年齢指定方法
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
?>
		<td class="n1">
			<select name="age_type" onchange="change_age_type()">
				<option <?=value_selected('', $age_type)?>>形式を選択してください</option>
				<option <?=value_selected('1', $age_type)?>>5歳刻みの年齢</option>
				<option <?=value_selected('2', $age_type)?>>任意の年齢</option>
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
					才〜
					<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
					才 <span class="note">（半角数字）</span></td>
				</tr>
			</table>
			</span>
		</td>
	</tr>
	<!-- セル指定　する -->
	<tr id="tr_cell" style="display:none">
		<td class="m1">性別／年代</td>
		<td class="n1">
			<input type="hidden" name="age_option" <?=value($cell->cell_option == 2 ? $cell->age_option : 3)?>>
			<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width=100%>
							<tr>
								<td width="150" valign="bottom">
									<table border="1" cellspacing="0" cellpadding="2" width=100% class="m1" style="border-bottom-style:none">
										<tr>
											<td id="tab1" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(1)">年代を指定しない</td>
											<td id="tab2" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(2)">5歳刻み</td>
											<td id="tab3" align="center" nowrap class="tab" onmouseover="onMouseOver(this)" onmouseout="onMouseOut(this)" onclick="onclick_tab(3)">10歳刻み</td>
										</tr>
									</table>
								</td>
								<td align="right">
									<input type="checkbox" name="age_check" value="1" <?=checked($search->age_from || $search->age_to)?> onclick="onclick_age_span()">任意の年齢を指定する&nbsp;&nbsp;&nbsp;
									<span id="age_span"><input type="text" name="age_span_from" <?=value($search->age_from)?> size="6" onblur="change_age_range()">歳　〜　<input type="text" name="age_span_to" <?=value($search->age_to)?> size="6" onblur="change_age_range()">歳</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
						<!-- 【年代を指定しない】選択時に表示 -->
				<tr id="area1" style="display:none">
					<td>
						<table border=1 cellspacing=0 cellpadding=2 width=40% frame="box">
							<tr>
								<td class="m1">男性</td>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(1, 1, 0)?> size="5" onblur="calc_cell_num()"></td>
							</tr>
							<tr>
								<td class="m1">女性</td>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(1, 2, 0)?> value="" size="5" onblur="calc_cell_num()"></td>
							</tr>
						</table>
					</td>
				</tr>
						<!-- //【年代を指定しない】選択時に表示 -->
						<!-- 【5歳刻み】選択時に表示 -->
				<tr id="area2" style="display:none">
					<td>
						<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box">
							<tr>
								<td class="m1" colspan="2" rowspan=2>&nbsp;</td>
								<td class="m1" align="center">10代</td>
								<td class="m1" align="center" colspan=2>20代</td>
								<td class="m1" align="center" colspan=2>30代</td>
								<td class="m1" align="center" colspan=2>40代</td>
								<td class="m1" align="center" colspan=2>50代</td>
								<td class="m1" align="center" colspan=2>60代</td>
								<td class="m1" align="center" rowspan=2>70代<br>以上</td>
							</tr>
							<tr>
								<td class="m1" align="center">後</td>
								<td class="m1" align="center">前</td>
								<td class="m1" align="center">後</td>
								<td class="m1" align="center">前</td>
								<td class="m1" align="center">後</td>
								<td class="m1" align="center">前</td>
								<td class="m1" align="center">後</td>
								<td class="m1" align="center">前</td>
								<td class="m1" align="center">後</td>
								<td class="m1" align="center">前</td>
								<td class="m1" align="center">後</td>
							</tr>
							<tr>
								<td class="m1" colspan="2"><input type="radio" name="sex_option2" <?=value_checked(1, $cell->sex_option)?> onclick="onclick_sex_option()">性別指定なし</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(2, 0, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
							<tr>
								<td class="m1" rowspan="2"><input type="radio" name="sex_option2" <?=value_checked(2, $cell->sex_option)?> onclick="onclick_sex_option()"></td>
								<td class="m1" nowrap>男性</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(2, 1, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
							<tr>
								<td class="m1" nowrap>女性</td>
<?
for ($i = 3; $i <= 14; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(2, 2, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
						</table>
					</td>
				</tr>
						<!-- //【5歳刻み】選択時に表示 -->
						<!-- 【10歳刻み】選択時に表示 -->
				<tr id="area3" style="display:none">
					<td>
						<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box">
							<tr>
								<td class="m1" colspan="2">&nbsp;</td>
								<td class="m1" align="center">10代</td>
								<td class="m1" align="center">20代</td>
								<td class="m1" align="center">30代</td>
								<td class="m1" align="center">40代</td>
								<td class="m1" align="center">50代</td>
								<td class="m1" align="center">60代</td>
								<td class="m1" align="center">70代以上</td>
							</tr>
							<tr>
								<td class="m1" colspan="2"><input type="radio" name="sex_option3" <?=value_checked(1, $cell->sex_option)?> onclick="onclick_sex_option()">性別指定なし</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(3, 0, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
							<tr>
								<td class="m1" rowspan="2"><input type="radio" name="sex_option3" <?=value_checked(2, $cell->sex_option)?> onclick="onclick_sex_option()"></td>
								<td class="m1">男性</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(3, 1, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
							<tr>
								<td class="m1">女性</td>
<?
for ($i = 2; $i <= 8; $i++) {
?>
								<td class="n1" align="center"><input type="text" <?=cell_namep_value(3, 2, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
}
?>
							</tr>
						</table>
					</td>
				</tr>
						<!-- //【10歳刻み】選択時に表示 -->
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="2" width="100%" frame="box">
						<tr>
							<td width="30%">上記サンプル数合計</td><td><input type="text" name="cell_sum" size="15" readonly>通</td>
						</tr>
						<tr>
							<td>サンプル数過不足自動計算</td><td><input type="text" name="cell_diff" size="15" readonly>通 （0になるように上記でご指定ください）</td>
						</tr>
						<tr>
							<td>セル指定数</td><td><input type="text" name="cell_count" size="15" readonly>セル</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- セル指定　する -->
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1">
			<input type="radio" name="mikikon" <?=value_checked('', $search->mikikon)?> onclick="change_mikikon_rate()">指定しない&nbsp;
			<input type="radio" name="mikikon" <?=value_checked('1', $search->mikikon)?> onclick="change_mikikon_rate()">未婚&nbsp;
			<input type="radio" name="mikikon" <?=value_checked('2', $search->mikikon)?> onclick="change_mikikon_rate()">既婚&nbsp;
			<input type="radio" name="mikikon" <?=value_checked('3', $search->mikikon)?> onclick="change_mikikon_rate()">その他
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1">
			<div>
				<input type="button" value="全選択" onclick="area_all_check(true)">
				<input type="button" value="全ｸﾘｱ" onclick="area_all_check(false)">
			</div>
			<table border=1 cellspacing=0 cellpadding=2 width=100% frame="box" class="small">
				<tr>
					<td align="center"><b>エリア</b></td>
					<td align="center" colspan="6">エリア内の都道府県をお選びください</td>
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
					<td width="17%"><input type="checkbox" name="area_cd[]" <?=value($area3_cd)?> <?=checked($area_checked)?> onclick="onclick_area(this)"><b><?=$area3_name?></b></td>
					<td width="83%" colspan=6>
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
<?
	$col = 0;
	foreach ($area_ary[$area3_cd] as $area_cd => $area_name) {
		if ($col % $cols == 0) {
?>
							<tr class="small">
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
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1">
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1">
<?
$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
checkbox_common($sql, 'gyoushu', $search->gyoushu, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">職種</td>
		<td class="n1">
<?
$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
checkbox_common($sql, 'shokushu', $search->shokushu, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">子供の有無</td>
		<td class="n1">
			<input type="checkbox" name="have_child[]" <?=value_checked_multi('1', $search->have_child)?>>有り&nbsp;
			<input type="checkbox" name="have_child[]" <?=value_checked_multi('2', $search->have_child)?>>無し
		</td>
	</tr>
	<tr>
		<td class="m1">住居形態</td>
		<td class="n1">
			<input type="checkbox" name="housing_form[]" <?=value_checked_multi('1', $search->housing_form)?>>一戸建て&nbsp;
			<input type="checkbox" name="housing_form[]" <?=value_checked_multi('2', $search->housing_form)?>>集合住宅
		</td>
	</tr>
	<tr>
		<td class="m1">車の所有</td>
		<td class="n1">
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>免許と車を所有している&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>免許のみ所有している&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>免許は所有していない
		</td>
	</tr>
	<tr>
		<td class="m1">よく行くコンビニ</td>
		<td class="n1">
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">よく行くスーパー</td>
		<td class="n1">
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
		</td>
	</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_name)?></td>
		<td class="n1">
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
		<td class="m1">興味ジャンル</td>
		<td class="n1">
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">対象メンバーリスト</td>
		<td class="n1">
			<input type="checkbox" name="taisho_myenq_list_flag" <?=checked($search->taisho_myenq_list)?> onclick="show_hide('taisho_myenq_list_c', checked)">指定する
			<span id="taisho_myenq_list_c">&nbsp;
				<font class="note">（メンバーグループIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
				<input type="button" value="選択" onclick="member_select('taisho_myenq_list')"><br>
				<textarea name="taisho_myenq_list" cols=80 rows=3><?=$search->taisho_myenq_list?></textarea>
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">除外メンバーリスト</td>
		<td class="n1">
			<input type="checkbox" name="jogai_myenq_list_flag" <?=checked($search->jogai_myenq_list)?> onclick="show_hide('jogai_myenq_list_c', checked)">指定する
			<span id="jogai_myenq_list_c">&nbsp;
				<font class="note">（メンバーグループIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
				<input type="button" value="選択" onclick="member_select('jogai_myenq_list')"><br>
				<textarea name="jogai_myenq_list" cols=80 rows=3><?=$search->jogai_myenq_list?></textarea>
			</span>
		</td>
	</tr>
	<tr id="tr_send_rate" style="display:none">
		<td class="m1" rowspan=4 id="send_rate">配信割合設定</td>
		<td class="n1">
			<input type="radio" name="rate_option" <?=value_checked(1, $cell->rate_option)?> onclick="onclick_rate_option()">指定しない&nbsp;&nbsp;&nbsp;
			<span id="id_mikikon"><input type="radio" name="rate_option" <?=value_checked(2, $cell->rate_option)?> onclick="onclick_rate_option()">未既婚</span>&nbsp;&nbsp;&nbsp;
			<span id="id_area"><input type="radio" name="rate_option" <?=value_checked(3, $cell->rate_option)?> onclick="onclick_rate_option()">居住エリア</span>
		</td>
	</tr>
	<!-- 割合設定 -->
	<tr id="tr_mikikon" style="display:none">
		<td class="n1">
			未婚　<input type="text" name="rate_mikikon[1]" <?=value($cell->rate_option == 2 ? $cell->send_rate[1] : '')?> size="5">％&nbsp;&nbsp;&nbsp;
			既婚　<input type="text" name="rate_mikikon[2]" <?=value($cell->rate_option == 2 ? $cell->send_rate[2] : '')?> size="5">％&nbsp;&nbsp;&nbsp;
			その他　<input type="text" name="rate_mikikon[3]" <?=value($cell->rate_option == 2 ? $cell->send_rate[3] : '')?> size="5">％<br>
		</td>
	</tr>
	<tr id="tr_kyoju_area" style="display:none">
		<td class="n1">
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
					<td width="20%"><input type="text" name="rate_area[<?=$area3_cd?>]" <?=value($cell->rate_option == 3 ? $cell->send_rate[$area3_cd] : '')?> size="5">％</td>
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
		</td>
	</tr>
	<tr></tr>
	<!-- 割合設定 -->
</table>

<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="next_action">
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
</form>

<? page_footer() ?>
</body>
</html>
