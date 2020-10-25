<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト新規登録
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト新規登録', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onsubmit_form1(f) {
	if (f.affiliate_name.value == "") {
		alert("アフィリエイト名を入力してください。");
		f.affiliate_name.focus();
		return false;
	}
	if (f.condition.value == "") {
		alert("条件を入力してください。");
		f.condition.focus();
		return false;
	}
	return confirm("アフィリエイトを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.affiliate_name.focus()">

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center">アフィリエイト登録</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■アフィリエイト情報</td>
	</tr>
	<tr>
		<td class="m1" width="22%">アフィリエイト名</td>
		<td class="n1"><input class="kanji" type="text" name="affiliate_name" size=50 maxlength=50 value="">
		<span class="note">（全角５０文字まで）</span></td>
	</tr>
	<tr>
		<td class="m1">種類</td>
		<td class="n1">
			<input type="radio" name="affiliate_type" value="<?=AFFILIATE_WEB?>" checked>バナー・WEB
			<input type="radio" name="affiliate_type" value="<?=AFFILIATE_MAIL?>">メール
		</td>
	</tr>
	<tr>
		<td class="m1">条件</td>
		<td class="n1"><input type="text" size="50" name="condition" value=""></td>
	</tr>
	<tr>
		<td class="m1">応募期間</td>
		<td class=n1>
			<select name="appl_start_y"><? select_year(date('Y'), '', '') ?></select>年
			<select name="appl_start_m"><? select_month('', '') ?></select>月
			<select name="appl_start_d"><? select_day('', '') ?></select>日
			〜
			<select name="appl_end_y"><? select_year(date('Y'), '', '') ?></select>年
			<select name="appl_end_m"><? select_month('', '') ?></select>月
			<select name="appl_end_d"><? select_day('', '') ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">アフィリエイト期間</td>
		<td class=n1>
			<select name="affiliate_start_y"><? select_year(date('Y'), '', '') ?></select>年
			<select name="affiliate_start_m"><? select_month('', '') ?></select>月
			<select name="affiliate_start_d"><? select_day('', '') ?></select>日
			〜
			<select name="affiliate_end_y"><? select_year(date('Y'), '', '') ?></select>年
			<select name="affiliate_end_m"><? select_month('', '') ?></select>月
			<select name="affiliate_end_d"><? select_day('', '') ?></select>日
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="　戻る　" onclick='history.back()'>
<input type="hidden" name="next_action" value="new">
</form>

</div>

<? page_footer() ?>

</body>
</html>
