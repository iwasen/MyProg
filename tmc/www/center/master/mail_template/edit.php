<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メールテンプレート変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// メイン処理
$sql = "SELECT * FROM t_mail_template WHERE mtm_template_id='{$_GET['template_id']}'";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error("template_idが不正", __FILE__);
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
	return confirm("メールテンプレートを更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜メールテンプレート｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■メールテンプレート情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">件名</td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($fetch->mtm_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Fromアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="from" size=70 maxlength=100 <?=value($fetch->mtm_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ccアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="cc" size=70 maxlength=100 <?=value($fetch->mtm_cc)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Bccアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="bcc" size=70 maxlength=100 <?=value($fetch->mtm_bcc)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Reply-Toアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=70 maxlength=100 <?=value($fetch->mtm_reply_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mtm_body)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1"><textarea class="kanji" name="footer" cols=78 rows=10><?=htmlspecialchars($fetch->mtm_footer)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="template_id" <?=value($fetch->mtm_template_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
