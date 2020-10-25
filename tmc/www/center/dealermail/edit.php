<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メール変更画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 販売店メールID取得
$dealer_mail_id = $_GET['dealer_mail_id'];

// 登録済み販売店メール取得
$sql = "SELECT dml_status,dml_subject,dml_send_date,dml_from_name,dmc_header,dmc_body,dmc_footer" .
			" FROM t_dealer_mail JOIN t_dealer_mail_contents ON dml_dealer_mail_id=dmc_dealer_mail_id" .
			" WHERE dml_dealer_mail_id=$dealer_mail_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$status = $fetch->dml_status;
	$subject = $fetch->dml_subject;
	$from_name = $fetch->dml_from_name;
	$header = $fetch->dmc_header;
	$body = $fetch->dmc_body;
	$footer = $fetch->dmc_footer;

	if ($fetch->dml_send_date) {
		$send_date_check = '1';
		$send_date = sql_time($fetch->dml_send_date);
	} else
		$send_date = time() + (60 * 60 * 24);
	$send_date_y = date(Y, $send_date);
	$send_date_m = date(m, $send_date);
	$send_date_d = date(d, $send_date);
	$send_date_h = date(H, $send_date);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	show_hide("send_date", f.send_date_check.checked);
}
function onSubmit_form1(f) {
	if (f.next_action.value == "ccreg") {
		f.target = "_blank";
		f.action = "<?=$top?>/common/cc_reg.php";
		return true;
	} else {
		f.target = "";
		f.action = "update.php";
		if (f.subject.value == "") {
			alert("件名(Subject)を入力してください。");
			f.subject.focus();
			return false;
		}
		return confirm("販売店メールを更新します。よろしいですか？");
	}
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

<? center_header('販売店メール配信｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■販売店メールを入力してください</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m1" width="20%">件名(Subject)</td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">From名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=20 maxlength=10 <?=value($from_name)?>>
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1">
			<textarea class="kanji" name="header" cols=78 rows=10><?=htmlspecialchars($header)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($body)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1">
			<textarea class="kanji" name="footer" cols=78 rows=10><?=htmlspecialchars($footer)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">送信開始日時</td>
		<td class="n1"><input type="checkbox" name="send_date_check" <?=value_checked('1', $send_date_check)?> onclick="show_hide('send_date',checked)">指定する
			<span class="note">（指定しない場合は即時）</span>
			<span id="send_date">&nbsp;
				<select name="send_date_y"><? select_year(2001, '', $send_date_y) ?></select>年
				<select name="send_date_m"><? select_month('', $send_date_m) ?></select>月
				<select name="send_date_d"><? select_day('', $send_date_d) ?></select>日
				<select name="send_date_h"><? select_hour('', $send_date_h) ?></select>時
			</span>
		</td>
	</tr>
</table>
<br>
<?
if ($fetch->dml_status <= 2) {
?>
<input type="submit" value="ｸﾘｯｸｶｳﾝﾀ登録"onclick="document.form1.next_action.value='ccreg'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
}
?>
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="dealer_mail_id" <?=value($dealer_mail_id)?>>
<input type="hidden" name="cc_start_date">
<input type="hidden" name="cc_end_date">
<input type="hidden" name="cc_no">
<input type="hidden" name="cc_url_name">
<input type="hidden" name="cc_jump_url">
<input type="hidden" name="cc_count" value=0>
</form>
</div>

<? center_footer() ?>

</body>
</html>
