<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート追加発信画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// Myアンケート情報読み込み
$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

// 抽出条件を取得
$where = $search->make_sql() . " AND mn_monitor_id NOT IN (SELECT el_monitor_id FROM t_enquete_list WHERE el_enquete_id={$enquete->enquete_id})";

// 追加配信可能数を取得
if ($cell->cell_option == 2) {
	$monitor_ary = $cell->get_search_list2($where, 0);
	$send_num = count($monitor_ary);
} else {
	$sql = "SELECT COUNT(*) FROM t_monitor WHERE $where";
	$send_num = db_fetch1($sql, 0);
}

// 追加設定済み（未配信）件数を減算
$sql = "SELECT SUM(mas_send_num) FROM t_myenq_add_send WHERE mas_marketer_id=$marketer_id AND mas_my_enq_no=$my_enq_no";
$send_num -= db_fetch1($sql, 0);
if ($send_num < 0)
	$send_num = 0;

// 追加配信日時のデフォルト
$default_hour = 18;
$time = time();
if (date('H', $time) >= $default_hour)
	$time += 24 * 26 * 60;
$year = date('Y', $time);
$month = date('m', $time);
$day = date('d', $time);
$hour = $default_hour;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>追加配信</title>
<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	returnValue = false;
	f.add_send_num.focus();
}
function onclick_add_send() {
	var f = document.form1;
	if (f.add_send_num.value <= 0) {
		alert("追加配信数を入力してください。");
		f.add_send_num.focus();
		return;
	}
	if (!check_num(f.add_send_num.value)) {
		alert("追加配信数が正しくありません。");
		f.add_send_num.focus();
		return;
	}
	if (f.add_send_num.value > <?=$send_num?>) {
		alert("追加配信数は追加配信可能数以下で設定してください。");
		f.add_send_num.focus();
		return;
	}
	if (!check_date(f.year.value, f.month.value, f.day.value)) {
		alert("配信日時が正しくありません。");
		f.year.focus();
		return;
	}
	if (f.hour.value > 23) {
		alert("時間が正しくありません。");
		f.hour.focus();
		return;
	}
	var send_date = new Date(f.year.value, f.month.value - 1, f.day.value, f.hour.value);
	if (send_date < new Date()) {
		alert("配信日時に過去の日付が入力されています。");
		f.year.focus();
		return;
	}
	if (send_date > new Date(<?=$enquete->end_date_y?>, <?=$enquete->end_date_m?> - 1, <?=$enquete->end_date_d?>, <?=$enquete->end_date_h?>)) {
		alert("配信日時がアンケート終了日時を過ぎています。");
		f.year.focus();
		return;
	}
	dialogArguments.add_send_num.value = f.add_send_num.value;
	dialogArguments.start_date_y.value = f.year.value;
	dialogArguments.start_date_m.value = f.month.value;
	dialogArguments.start_date_d.value = f.day.value;
	dialogArguments.start_date_h.value = f.hour.value;
	returnValue = true;
	window.close();
}
function check_num(n) {
	for (i = 0; i < n.length; i++){
		if (n.charAt(i) < "0" || n.charAt(i) > "9") {
			return false;
		}
	}
	return true;
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
//-->
</script>
</head>
<body bgcolor="threedface" onload="onload_body()">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
	<tr>
		<td align="center" valign="center">
			<table border=0>
				<tr>
					<td>追加配信可能数</td>
					<td>：</td>
					<td ><?=number_format($send_num)?> 人</td>
				</tr>
				<tr>
					<td>追加配信数</td>
					<td>：</td>
					<td><input type="text" name="add_send_num" size=8><font size="-1">通</font></td>
				</tr>
				<tr>
					<td>配信日時</td>
					<td>：</td>
					<td>
						<font size="-1">
						<input type="text" name="year" size=5 maxlength=4 <?=value($year)?>>年
						<input type="text" name="month" size=3 maxlength=2 <?=value($month)?>>月
						<input type="text" name="day" size=3 maxlength=2 <?=value($day)?>>日
						<input type="text" name="hour" size=3 maxlength=2 <?=value($hour)?>>時
					</font>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="　　OK　　" onclick="onclick_add_send()">
			<input type="button" value="キャンセル" onclick="window.close()">
		</td>
	</tr>
</table>
</form>
</body>
</html>
