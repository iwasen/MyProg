<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:全配信メール設定
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

set_global('member', '会員情報管理', '全配信メール設定', BACK_TOP);

$sql = "SELECT am_seq_no,am_target,am_subject,am_body,am_send_date,am_status" .
			" FROM t_all_mail WHERE am_seq_no=$am_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

if ($fetch->am_send_date) {
	$send_date_check = '1';
	$send_date = sql_time($fetch->am_send_date);
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
		if (f.send_date_check.checked)
			return input_check(f, "全配信メールを更新します。よろしいですか？");
		else
			return input_check(f, "全配信メールを即時送信します。よろしいですか？");
	case "delete":
		return confirm("全配信メールを削除します。よろしいですか？");
	case "test":
		return input_check(f, "全配信メールをテスト送信します。よろしいですか？");
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
		<td class="m0" colspan=2>■完了督促メールを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">配信対象<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<font size=-1>
			<input type="radio" name="target" <?=value_checked(3, $fetch->am_target)?>>本登録会員のみ
			<input type="radio" name="target" <?=value_checked(2, $fetch->am_target)?>>仮登録２会員のみ
			<input type="radio" name="target" <?=value_checked(1, $fetch->am_target)?>>仮登録１会員のみ
<!--			<input type="radio" name="target" <?=value_checked(4, $fetch->am_target)?>>全会員-->
			</font>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($fetch->am_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<span class="note">※差込文字が使えます。（会員氏名： %MEMBER_NAME% <font color="red">※但し、仮登録1会員には使用できません。</font>）<br></span>
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->am_body)?></textarea><br>
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
if ($fetch->am_status == 0) {
?>
	<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
	<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
}
?>
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="am_id" value="<?=$am_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
