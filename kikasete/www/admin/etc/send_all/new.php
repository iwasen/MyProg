<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:全配信
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

set_global('etc', 'その他管理', '全配信', BACK_TOP);

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
	f("target[]")[0].focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		return input_check(f, "全配信メールを登録します。よろしいですか？");
	case "test":
		return confirm("全配信メールをテスト送信します。よろしいですか？");
	}
	return false;
}
function input_check(f, msg) {
	var ok;
	for (i = 0; i < f("target[]").length; i++) {
		if (f("target[]")[i].checked) {
			ok = true;
			break;
		}
	}
	if (!ok) {
		alert("送信先を選択してください。");
		f("target[]")[0].focus();
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
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■全配信メールを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">送信先<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="checkbox" name="target[]" value="<?=PS_MONITOR?>">モニター
			<input type="checkbox" name="target[]" value="<?=PS_MARKETING_PARTNER?>">Ｍパートナー
			<br>
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_FREE?>">マーケター（無料）
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_PAY?>">マーケター（有料）
			<input type="checkbox" name="target[]" value="<?=PS_MARKETER_ASSISTANT?>">マーケター（アシスタント）
			<br>
			<input type="checkbox" name="target[]" value="<?=PS_AGENT?>">エージェント
			<input type="checkbox" name="target[]" value="<?=PS_AGENT_SUB?>">エージェント（サブ）
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
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
				<select name="send_date_y"><?select_year($today['year'], '', $today['year'])?></select>年
				<select name="send_date_m"><?select_month('', $today['mon'])?></select>月
				<select name="send_date_d"><?select_day('', $today['mday'])?></select>日
				<select name="send_date_h"><?select_hour()?></select>時
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
