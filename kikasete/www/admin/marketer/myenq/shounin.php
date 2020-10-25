<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート承認確認画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>承認</title>
<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	f.year.value = dialogArguments.start_date_y.value;
	f.month.value = dialogArguments.start_date_m.value;
	f.day.value = dialogArguments.start_date_d.value;
	f.hour.value = dialogArguments.start_date_h.value;
	returnValue = false;
	f.hour.focus();
}
function onclick_shounin() {
	var f = document.form1;
	if (!check_date(f.year.value, f.month.value, f.day.value)) {
		alert("開始日時が正しくありません。");
		f.year.focus();
		return;
	}
	if (f.hour.value == "" || !check_num(f.hour.value) || f.hour.value < 0 || f.hour.value > 23) {
		alert("時間が正しくありません。");
		f.hour.focus();
		return;
	}
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
			開始日時：
			<font size="-1">
			<input type="text" name="year" size=5 maxlength=4>年
			<input type="text" name="month" size=3 maxlength=2>月
			<input type="text" name="day" size=3 maxlength=2>日
			<input type="text" name="hour" size=3 maxlength=2>時
			</font><br><br>
			<input type="button" value="　　OK　　" onclick="onclick_shounin()">
			<input type="button" value="キャンセル" onclick="window.close()">
		</td>
	</tr>
</table>
</form>
</body>
</html>
