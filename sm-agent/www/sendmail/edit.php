<?
/******************************************************
' System :メール配信サービス
' Content:配信メール変更
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/select.php");

set_global('配信メール参照・変更', BACK_TOP);

$sql = "SELECT md_send_date,md_limit_date,md_plug_vars,sd_from,sd_personal,sd_reply_to,sd_subject,sd_body,sd_status"
		. " FROM t_mail_data"
		. " JOIN t_send_mail_data ON sd_send_mail_id=md_send_mail_id"
		. " WHERE md_send_mail_id=$send_mail_id AND md_user_id=$user_login_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

if ($fetch->md_send_date) {
	$send_date_check = '1';
	$send_date = sql_time($fetch->md_send_date);
} else
	$send_date = time() + (60 * 60 * 24);
$send_date_y = date(Y, $send_date);
$send_date_m = date(m, $send_date);
$send_date_d = date(d, $send_date);
$send_date_h = date(H, $send_date);
$send_date_i = date(i, $send_date);

if ($fetch->md_limit_date) {
	$limit_date_check = '1';
	$limit_date = sql_time($fetch->md_limit_date);
} else
	$limit_date = time() + (60 * 60 * 24);
$limit_date_y = date(Y, $limit_date);
$limit_date_m = date(m, $limit_date);
$limit_date_d = date(d, $limit_date);
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
	show_hide("limit_date", f.limit_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (f.plug_vars.value != f.plug_vars_org.value && f.list_file.value == '') {
			alert("差込データを変更した場合は送信先リストファイルを入力してください。");
			f.list_file.focus();
			return false;
		}
		return input_check(f, "配信メールを更新します。よろしいですか？");
	case "delete":
		return confirm("配信メールを削除します。よろしいですか？");
	case "test":
		return confirm("配信メールをテスト送信します。よろしいですか？");
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
			<span class="note">（送信先を変更しない場合は何も入力しなくて結構です）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">差込データ</td>
		<td class="n1">
			<input type="hidden" name="plug_vars_org" <?=value($fetch->md_plug_vars)?>>
			<input class="alpha" type="text" name="plug_vars" size="80" <?=value($fetch->md_plug_vars)?>><br>
			<span class="note">（変数名をカンマ区切りで入力してください）</span>
			</td>
	</tr>
	<tr>
		<td class="m1">送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=80 maxlength=100 <?=value($fetch->sd_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=80 maxlength=100 <?=value($fetch->sd_personal)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">返信先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=80 maxlength=100 <?=value($fetch->sd_reply_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($fetch->sd_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->sd_body)?></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">送信日時</td>
		<td class="n1"><input type="checkbox" name="send_date_check" <?=value_checked('1', $send_date_check)?> onclick="show_hide('send_date',checked)">指定する
			<span class="note">（指定しない場合は即時）</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><? select_year(2001, '', $send_date_y)?></select>年
				<select name="send_date_m"><? select_month('', $send_date_m)?></select>月
				<select name="send_date_d"><? select_day('', $send_date_d)?></select>日
				<select name="send_date_h"><? select_hour('', $send_date_h)?></select>時
				<select name="send_date_i"><? select_minute('', $send_date_i)?></select>分
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">解除締切り日時</td>
		<td class="n1"><input type="checkbox" name="limit_date_check" <?=value_checked('1', $limit_date_check)?> onclick="show_hide('limit_date',checked)">指定する
			<span class="note">（指定しない場合は無期限）</span>
			<span id="limit_date">&nbsp;
				<select name="limit_date_y"><? select_year(2001, '', $limit_date_y)?></select>年
				<select name="limit_date_m"><? select_month('', $limit_date_m)?></select>月
				<select name="limit_date_d"><? select_day('', $limit_date_d)?></select>日
			</span>
		</td>
	</tr>
</table>
<br>
<?
if ($fetch->sd_status == 0) {
?>
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
}
?>
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="send_mail_id" value="<?=$send_mail_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
