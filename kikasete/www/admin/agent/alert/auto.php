<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アラートメール
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
include("$inc/mail.php");

set_global('agent', 'エージェント管理', 'アラートメール', BACK_TOP);

$sql = "SELECT am_seq_no,am_agent_id,am_subject,am_body,am_send_date" .
			" FROM t_alert_mail WHERE am_kind=1 AND am_status=0";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$seq_no = $fetch->am_seq_no;
	$subject = $fetch->am_subject;
	$body = $fetch->am_body;
	$send_date = sql_time($fetch->am_send_date);

	$next_action = 'update';
} else {
	get_mail_template('alt_mail', $subject, $from, $cc, $bcc, $repty_to, $body);
	$send_date = time() + (60 * 60 * 24 * 7);

	$next_action = 'new';
}
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
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
	case "update":
		return input_check(f, "アラートメールを更新します。よろしいですか？");
	case "test":
		return confirm("アラートメールをテスト送信します。よろしいですか？");
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
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■自動送信アラートメールを設定してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($body)?></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">次回送信日時</td>
		<td class="n1">
			<select name="send_date_y"><? select_year(date('Y'), '', $send_date_y) ?></select>年
			<select name="send_date_m"><? select_month('', $send_date_m) ?></select>月
			<select name="send_date_d"><? select_day('', $send_date_d) ?></select>日
			<select name="send_date_h"><? select_hour('', $send_date_h) ?></select>時
		</td>
	</tr>
</table>
<br>
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='<?=$next_action?>'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
<input type="hidden" name="send_date_check" value="1">
<input type="hidden" name="kind" value="1">
</form>
</div>

<? page_footer() ?>
</body>
</html>
