<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メール購読解除理由新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 次のメール購読解除理由コード取得
$sql = "SELECT MAX(COALESCE(mcr_cancel_cd,0)) + 1 AS cancel_cd FROM t_mail_cancel_result";
$result = db_exec($sql);
$cancel_cd = pg_fetch_result($result, 0, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.cancel_cd.value == "") {
		alert("メール購読解除理由コードを入力してください。");
		f.cancel_text.focus();
		return false;
	}
	if (f.cancel_cd.value == "") {
		alert("メール購読解除理由を入力してください。");
		f.cancel_text.focus();
		return false;
	}
	return confirm("メール購読解除理由を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.cancel_text.focus()">

<? center_header('マスタメンテナンス｜メール購読解除理由マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録するメール購読解除理由を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">メール購読解除理由コード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="cancel_cd" size=4 maxlength=3 <?=value($cancel_cd)?>>
			<span class="note">（数字３桁まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">メール購読解除理由<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cancel_text" size=80>
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
