<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:メール返信入力
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

// テンプレート選択
function select_template($nigaoe_id) {
	$sql = "SELECT mt_template_id,mt_guide FROM m_mail_template WHERE mt_template_id LIKE 'msg_%' ORDER BY mt_template_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo "<a href='mail.php?nigaoe_id=$nigaoe_id&template_id=$fetch->mt_template_id'>$fetch->mt_guide</a><br>\n";
	}
}

set_global('charara', '', 'きゃららFactory メール返信', BACK_TOP);

if ($template_id) {
	$sql = "SELECT mt_subject,mt_from,mt_body FROM m_mail_template WHERE mt_template_id=" . sql_char($template_id);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$subject = $fetch->mt_subject;
		$from = $fetch->mt_from;
		$body = $fetch->mt_body;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	var f = document.form1;
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("メール本文を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm("メールを送信します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="send.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■送信メール入力</td>
	</tr>
<?
if (!$template_id) {
?>
	<tr>
		<td class="m1" width="20%">テンプレート</td>
		<td class="n1"><? select_template($nigaoe_id) ?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1" width="20%">件名</td>
		<td class="n1"><input type="text" name="subject" size=70 <?=value($subject)?>></td>
	</tr>
	<tr>
		<td class="m1">メール本文</td>
		<td class="n1"><textarea name="body" cols=78 rows=20><?=htmlspecialchars($body)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　送信　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="nigaoe_id" <?=value($nigaoe_id)?>>
<input type="hidden" name="from" <?=value($from)?>>

</div>
</form>

<? page_footer() ?>

</body>
</html>
