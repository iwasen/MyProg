#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:送信メールテンプレート変更画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.from_addr.value == "") {
		alert("Fromアドレスを入力してください。");
		f.from_addr.focus();
		return false;
	}
	if (f.to_addr.value == "") {
		alert("Toアドレスを入力してください。");
		f.to_addr.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("本文を入力してください。");
		f.body.focus();
		return false;
	}
	if (f.action == "update.php")
		return confirm("送信メールテンプレートを更新します。よろしいですか？");
	else if (f.action == "testsend.php")
		return confirm("テスト送信を行います。よろしいですか？");
	else
		return false;
}
function macro_list() {
	window.open("macro.php", "macro_list", "width=430,height=820,scrollbars=yes,resizable=yes");
}
//-->
</script>
</head>
<body>

<? page_header('送信メールテンプレート修正', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■送信メールテンプレート情報を入力してください。</td>
		<td align="right"><font size="-1"><a href="javascript:void(0)" onclick="macro_list()">差込マクロ一覧</a></font></td>
	</tr>
	<tr>
		<td class="m1" width="20%">テンプレート名</td>
		<td class="n1"><?=htmlspecialchars($fetch->mt_guide)?></td>
	</tr>
	<tr>
		<td class="m1">件名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=200 <?=value($fetch->mt_subject)?>>
			<span class="note">（全角・半角２００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">From名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=50 maxlength=50 <?=value($fetch->mt_from_name)?>>
			<span class="note">（全角・半角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Fromアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=70 maxlength=100 <?=value($fetch->mt_from_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Toアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="to_addr" size=70 maxlength=100 <?=value($fetch->mt_to_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ccアドレス</td>
		<td class="n1">
			<textarea name="cc_addr" cols=70 rows=3 class="alpha"><?=htmlspecialchars($fetch->mt_cc_addr)?></textarea>
			<br><span class="note">（カンマまたは改行で区切って複数入力可能）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Bccアドレス</td>
		<td class="n1">
			<textarea name="bcc_addr" cols=70 rows=3 class="alpha"><?=htmlspecialchars($fetch->mt_bcc_addr)?></textarea>
			<br><span class="note">（カンマまたは改行で区切って複数入力可能）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mt_body)?></textarea></td>
	</tr>
</table>
<br>
<input type="hidden" name="template_id" <?=value($fetch->mt_template_id)?>>
<input type="submit" value="テスト送信" onclick="document.form1.action='testsend.php'">
<input type="submit" value="　更新　" onclick="document.form1.action='update.php'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
