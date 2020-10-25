<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:連絡事項新規登録画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 現日時取得
$today = getdate();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.title.value == "") {
		alert("タイトルを入力してください。");
		f.title.focus();
		return false;
	}
	if (f.message.value == "") {
		alert("連絡事項を入力してください。");
		f.message.focus();
		return false;
	}
	return confirm("連絡事項を登録します。よろしいですか？");
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</script>
</head>
<body onload="document.form1.title.focus()">

<? center_header('連絡事項書き込み｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■連絡事項を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">タイトル<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size=70 maxlength=100>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">連絡事項<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="message" cols=80 rows=5></textarea><br>
			<span class="note">（文字数制限はありません。HTMLタグの使用も可能）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">宛先スタッフ</td>
		<td class="n1"><select name="to_staff_id"><?select_staff('- 全員 -')?></select></td>
	</tr>
	<tr>
		<td class="m1">表示開始日時</td>
		<td class="n1"><input type="checkbox" name="start_date_check" value="1" onclick="show_hide('start_date',checked)">指定する
			<span id="start_date" style="display:none">&nbsp;
				<select name="start_date_y"><?select_year($today['year'], '', $today['year'])?></select>年
				<select name="start_date_m"><?select_month('', $today['mon'])?></select>月
				<select name="start_date_d"><?select_day('', $today['mday'])?></select>日
				<select name="start_date_h"><?select_hour()?></select>時
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">表示終了日時</td>
		<td class="n1"><input type="checkbox" name="end_date_check" value="1" onclick="show_hide('end_date',checked)">指定する
			<span id="end_date" style="display:none">&nbsp;
				<select name="end_date_y"><?select_year($today['year'], '', $today['year'])?></select>年
				<select name="end_date_m"><?select_month('', $today['mon'])?></select>月
				<select name="end_date_d"><?select_day('', $today['mday'])?></select>日
				<select name="end_date_h"><?select_hour()?></select>時
			</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
