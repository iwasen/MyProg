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
include("inc.php");

set_global('agent', 'エージェント管理', 'アラートメール', BACK_TOP);

$sql = "SELECT am_seq_no,am_agent_id,am_subject,am_body,am_send_date,am_status" .
			" FROM t_alert_mail WHERE am_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

$target = $fetch->am_target;
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
		return input_check(f, "アラートメールを更新します。よろしいですか？");
	case "delete":
		return confirm("アラートメールを削除します。よろしいですか？");
	case "test":
		return confirm("アラートメールをテスト送信します。よろしいですか？");
	}
	return false;
}
function input_check(f, msg) {
	var ok;
	for (i = 0; i < f("agent_id[]").length; i++) {
		if (f("agent_id[]")[i].checked) {
			ok = true;
			break;
		}
	}
	if (!ok) {
		alert("送信先を選択してください。");
		f("agent_id[]")[0].focus();
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
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■アラートメールを表示しています</td>
	</tr>
	<tr>
		<td class="m1" width="20%">送信先<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><? checkbox_agant($fetch->am_agent_id) ?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($fetch->am_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->am_body)?></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信日時</td>
		<td class="n1"><input type="checkbox" name="send_date_check" <?=value_checked('1', $send_date_check)?> onclick="show_hide('send_date',checked)">指定する
			<span class="note">（指定しない場合は即時）</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><?select_year(date('Y'), '', $send_date_y)?></select>年
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
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
