<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("inc.php");

set_global($class, $section, 'メンバーリスト登録', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.group_name.value == "") {
		alert("メンバーリスト名を入力してください。");
		f.group_name.focus();
		return false;
	}
	return confirm("メンバーリストを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.group_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■メンバーリスト情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="25%">メンバーリスト名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="group_name" size=50>
		</td>
	</tr>
	<tr>
		<td class="m1">概要</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=50 rows=5></textarea>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
