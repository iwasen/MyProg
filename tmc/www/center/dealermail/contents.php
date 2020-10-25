<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メール情報表示画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");

// 送信状態表示
function decode_status($status) {
	switch ($status) {
	case 1:
		return '未作成';
	case 2:
		return '送信待';
	case 3:
		return '送信中';
	case 4:
		return '送信済';
	}
	return "不明";
}

// 販売店メールID取得
$dealer_mail_id = $_GET['dealer_mail_id'];

// 販売店メール情報取得
$sql = "SELECT dml_status,dml_subject,dml_send_date,EXISTS(SELECT * FROM t_dealer_mail_click WHERE dmn_dealer_mail_id=dml_dealer_mail_id) AS click_counter"
		. " FROM t_dealer_mail"
		. " WHERE dml_dealer_mail_id=$dealer_mail_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$status = $fetch->dml_status;
	$subject = $fetch->dml_subject;
	$send_date = $fetch->dml_send_date;
	$search_id = $fetch->dml_search_id;
	$click_counter = $fetch->click_counter;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "delete":
		return confirm("販売店メールを削除します。よろしいですか？");
	case "test":
		return confirm("テストメールを送信します。よろしいですか？");
	case "ready":
		return confirm("この販売店メールの作成を完了して送信可能に設定します。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>

<? center_header('販売店メール配信｜登録情報') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■販売店メール登録情報</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m1" width="20%">件名(Subject)</td>
		<td class="n1"><?=htmlspecialchars($subject)?></td>
	</tr>
	<tr>
		<td class="m1">送信開始日時</td>
		<td class="n1"><?=format_datetime($send_date)?></td>
	</tr>
	<tr>
		<td class="m1">状態フラグ</td>
		<td class="n1"><?=decode_status($status)?></td>
	</tr>
</table>
<br>
<input type="button" value="メールコンテンツ" onclick="location.href='edit.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<input type="button" value="検索条件" onclick="location.href='search.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<?
if ($status == 1) {
?>
<input type="submit" value="作成完了" onclick="document.form1.next_action.value='ready'">
<?
}
if ($click_counter == DBTRUE && ($status == 3 || $status == 4)) {
?>
<input type="button" value="ｸﾘｯｸｶｳﾝﾀCSV出力" onclick="location.href='cc_csv.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<?
}
?>
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="一覧に戻る" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="dealer_mail_id" <?=value($dealer_mail_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
