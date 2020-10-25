<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:時節の挨拶文新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/select.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.from_month.selectedIndex == 0) {
		alert("有効期間開始月を選択してください。");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.selectedIndex == 0) {
		alert("有効期間開始日を選択してください。");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.selectedIndex == 0) {
		alert("有効期間終了月を選択してください。");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.selectedIndex == 0) {
		alert("有効期間終了日を選択してください。");
		f.to_day.focus();
		return false;
	}
	if (f.greeting.value == "") {
		alert("挨拶文を入力してください。");
		f.to_day.focus();
		return false;
	}
	return confirm("時節の挨拶文を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.from_month.focus()">

<? center_header('マスタメンテナンス｜時節の挨拶文マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録する時節の挨拶文を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">有効期間<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_month"><? select_month(' ', '') ?></select>月
			<select name="from_day"><? select_day(' ', '') ?></select>日
			〜
			<select name="to_month"><? select_month(' ', '') ?></select>月
			<select name="to_day"><? select_day(' ', '') ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">挨拶文<?=MUST_ITEM?></td>
		<td class="n1"><textarea name="greeting" cols=80 rows=10></textarea></td>
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
