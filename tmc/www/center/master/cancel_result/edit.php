<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メール購読解除理由変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$cancel_cd = $_GET['cancel_cd'];

// 登録済みメール購読解除理由情報取得
$sql = "SELECT mcr_cancel_text FROM t_mail_cancel_result WHERE mcr_cancel_cd=" . sql_number($cancel_cd);
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
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
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
	if (f.cancel_text.value == "") {
		alert("メール購読解除理由を入力してください。");
		f.cancel_text.focus();
		return false;
	}
	return confirm("メール購読解除理由を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("メール購読解除理由を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜メール購読解除理由マスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■メール購読解除理由情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">メール購読解除理由コード</td>
		<td class="n1">
			<?=$cancel_cd?>
			<input type="hidden" name="cancel_cd" <?=value($cancel_cd)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">メール購読解除理由<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cancel_text" size=80 <?=value($fetch->mcr_cancel_text)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? center_footer() ?>

</body>
</html>
