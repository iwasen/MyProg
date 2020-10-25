<?
/******************************************************
' System :メール配信サービス
' Content:配信メール新規作成
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/select.php");

set_global('配信メール新規登録', BACK_TOP);

$today = getdate(time() + (60 * 60 * 24));
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	show_hide('send_date', f.send_date_check.checked);
	show_hide('limit_date', f.limit_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (f.list_file.value == "") {
			alert("送信先リストファイルを入力してください。");
			f.list_file.focus();
			return false;
		}
		if (f.send_date_check.checked) {
			d = new Date(f.send_date_y.value, f.send_date_m.value-1, f.send_date_d.value, f.send_date_h.value, f.send_date_i.value);
			if (d < new Date()) {
				alert("送信日時は未来の時刻を設定してください。");
				f.send_date_y.focus();
				return false;
			}
		}
		if (f.limit_date_check.checked) {
			d = new Date(f.limit_date_y.value, f.limit_date_m.value-1, (f.limit_date_d.value-0)+1);
			if (d < new Date()) {
				alert("解除締切り日時は未来の時刻を設定してください。");
				f.limit_date_y.focus();
				return false;
			}
		}
		return input_check(f, "配信メールを登録します。よろしいですか？");
	case "test":
		return input_check(f, "配信メールをテスト送信します。よろしいですか？");
	}
	return false;
}
function input_check(f, msg) {
	if (f.from_addr.value == "") {
		alert("送信元アドレスを入力してください。");
		f.from_addr.focus();
		return false;
	}
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("本文を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm(msg);
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
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" ENCtype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■配信メールを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">送信先リストファイル<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="file" name="list_file" size=80><br>
			<span class="note">（１列目がメールアドレス、２列目以降が差込データのCSV形式ファイル）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">差込データ</td>
		<td class="n1">
			<input class="alpha"  type="text" name="plug_vars" size="80"><br>
			<span class="note">（変数名をカンマ区切りで入力してください）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=80 maxlength=100>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=80 maxlength=100>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">返信先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=80 maxlength=100>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea name="body" cols=78 rows=20></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信日時</td>
		<td class="n1"><input type="checkbox" name="send_date_check" value="1" onclick="show_hide('send_date',checked)">指定する
			<span class="note">（指定しない場合は即時）</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><? select_year($today['year'], '', $today['year'])?></select>年
				<select name="send_date_m"><? select_month('', $today['mon'])?></select>月
				<select name="send_date_d"><? select_day('', $today['mday'])?></select>日
				<select name="send_date_h"><? select_hour()?></select>時
				<select name="send_date_i"><? select_minute()?></select>分
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">解除締切り日時</td>
		<td class="n1"><input type="checkbox" name="limit_date_check" value="1" onclick="show_hide('limit_date',checked)">指定する
			<span class="note">（指定しない場合は無期限）</span>
			<span id="limit_date">&nbsp;
				<select name="limit_date_y"><? select_year($today['year'], '', $today['year'])?></select>年
				<select name="limit_date_m"><? select_month('', $today['mon'])?></select>月
				<select name="limit_date_d"><? select_day('', $today['mday'])?></select>日
			</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? page_footer() ?>
</body>
</html>
