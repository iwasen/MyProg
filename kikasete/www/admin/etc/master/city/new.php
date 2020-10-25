<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:都市名マスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '都市名マスタ', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.ci_todoufuken.value == "") {
		alert("都道府県名を選択してください。");
		f.ci_todoufuken.focus();
		return false;
	}
	if (f.ci_shiku.value == "") {
		alert("都市名を入力してください。");
		f.ci_shiku.focus();
		return false;
	}
	return confirm("都市名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.ci_todoufuken.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する都市名情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">都道府県名<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="ci_todoufuken"><?select_area('選択してください', '')?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">都市名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ci_shiku" size=40 maxlength=10>
			<span class="note">（全角１０文字まで）</span>
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
