<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:システム情報新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	var f = document.form1;
	if (f.key.value == "") {
		alert("項目キーを入力してください。");
		f.key.focus();
		return false;
	}
	return confirm("システム設定情報を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜システム設定情報｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■システム情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">項目キー</td>
		<td class="n1">
			<input class="alpha" type="text" name="key">
		</td>
	</tr>
	<tr>
		<td class="m1">項目名</td>
		<td class="n1">
			<input class="kanji" type="text" name="caption" size=100 maxlength=100>
		</td>
	</tr>
	<tr>
		<td class="m1">設定内容</td>
		<td class="n1">
			<input type="text" name="value" size=100 maxlength=100>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
