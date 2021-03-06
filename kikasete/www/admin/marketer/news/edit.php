<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メールニュース配信
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

set_global('marketer', 'マーケター管理', 'メールニュース配信', BACK_TOP);

$sql = "SELECT ns_news_id,ns_target,ns_subject,ns_body,ns_send_date,ns_status" .
			" FROM t_mail_news WHERE ns_news_id=$news_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

if ($fetch->ns_send_date) {
	$send_date_check = '1';
	$send_date = sql_time($fetch->ns_send_date);
} else
	$send_date = time() + (60 * 60 * 24);
$send_date_y = date(Y, $send_date);
$send_date_m = date(m, $send_date);
$send_date_d = date(d, $send_date);
$send_date_h = date(H, $send_date);
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
	show_hide("send_date", f.send_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f, "メールニュースを更新します。よろしいですか？");
	case "delete":
		return confirm("メールニュースを削除します。よろしいですか？");
	case "test":
		return input_check(f, "メールニュースをテスト送信します。よろしいですか？");
	}
	return false;
}
function input_check(f, msg) {
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
		<td class="m0" colspan=2>■メールニュースを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">配信対象<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="send_target" <?=value_checked('3', $fetch->ns_target)?>>Myページ登録ユーザ<br>
			<input type="radio" name="send_target" <?=value_checked('4', $fetch->ns_target)?>>メルマガ登録ユーザ<br>
			<input type="radio" name="send_target" <?=value_checked('2', $fetch->ns_target)?>>両方（全登録ユーザ）
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($fetch->ns_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->ns_body)?></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信日時</td>
		<td class="n1"><input type="checkbox" name="send_date_check" <?=value_checked('1', $send_date_check)?> onclick="show_hide('send_date',checked)">指定する
			<span class="note">（指定しない場合は即時）</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><?select_year(2001, '', $send_date_y)?></select>年
				<select name="send_date_m"><?select_month('', $send_date_m)?></select>月
				<select name="send_date_d"><?select_day('', $send_date_d)?></select>日
				<select name="send_date_h"><?select_hour('', $send_date_h)?></select>時
			</span>
		</td>
	</tr>
</table>
<br>
<?
if ($fetch->ns_status == 0) {
?>
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
}
?>
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="news_id" value="<?=$news_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
