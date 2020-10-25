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
set_global('monitor', 'モニター管理', 'アフィリエイト登録変更', BACK_TOP);

$affiliate_mgr_id = $_REQUEST['affiliate_mgr_id'];

$sql = "SELECT afm_affiliate_name,afm_affiliate_type,afm_condition,afm_appl_start_date,afm_appl_end_date,afm_affiliate_start_date,afm_affiliate_end_date"
		. " FROM t_affiliate_mgr"
		. " WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

$appl_start_y = get_datepart('Y', $fetch->afm_appl_start_date);
$appl_start_m = get_datepart('M', $fetch->afm_appl_start_date);
$appl_start_d = get_datepart('D', $fetch->afm_appl_start_date);

$appl_end_y = get_datepart('Y', $fetch->afm_appl_end_date);
$appl_end_m = get_datepart('M', $fetch->afm_appl_end_date);
$appl_end_d = get_datepart('D', $fetch->afm_appl_end_date);

$affiliate_start_y = get_datepart('Y', $fetch->afm_affiliate_start_date);
$affiliate_start_m = get_datepart('M', $fetch->afm_affiliate_start_date);
$affiliate_start_d = get_datepart('D', $fetch->afm_affiliate_start_date);

$affiliate_end_y = get_datepart('Y', $fetch->afm_affiliate_end_date);
$affiliate_end_m = get_datepart('M', $fetch->afm_affiliate_end_date);
$affiliate_end_d = get_datepart('D', $fetch->afm_affiliate_end_date);
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
	switch (f.next_action.value) {
	case "update":
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
		return confirm("アフィリエイトを更新します。よろしいですか？");
	case "delete":
		return confirm("アフィリエイトを削除します。よろしいですか？");
	}
	return false;
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
		<td class="n1"><input class="kanji" type="text" name="affiliate_name" size=50 maxlength=50 <?=value($fetch->afm_affiliate_name)?>>
		<span class="note">（全角５０文字まで）</span></td>
	</tr>
	<tr>
		<td class="m1">種類</td>
		<td class="n1">
			<input type="radio" name="affiliate_type" <?=value_checked(AFFILIATE_WEB, $fetch->afm_affiliate_type)?>>バナー・WEB
			<input type="radio" name="affiliate_type" <?=value_checked(AFFILIATE_MAIL, $fetch->afm_affiliate_type)?>>メール
		</td>
	</tr>
	<tr>
		<td class="m1">条件</td>
		<td class="n1"><input type="text" size="50" name="condition" <?=value($fetch->afm_condition)?>></td>
	</tr>
	<tr>
		<td class="m1">応募期間</td>
		<td class=n1>
			<select name="appl_start_y"><? select_year(date('Y'), '', $appl_start_y) ?></select>年
			<select name="appl_start_m"><? select_month('', $appl_start_m) ?></select>月
			<select name="appl_start_d"><? select_day('', $appl_start_d) ?></select>日
			〜
			<select name="appl_end_y"><? select_year(date('Y'), '', $appl_end_y) ?></select>年
			<select name="appl_end_m"><? select_month('', $appl_end_m) ?></select>月
			<select name="appl_end_d"><? select_day('', $appl_end_d) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">アフィリエイト期間</td>
		<td class=n1>
			<select name="affiliate_start_y"><? select_year(date('Y'), '', $affiliate_start_y) ?></select>年
			<select name="affiliate_start_m"><? select_month('', $affiliate_start_m) ?></select>月
			<select name="affiliate_start_d"><? select_day('', $affiliate_start_d) ?></select>日
			〜
			<select name="affiliate_end_y"><? select_year(date('Y'), '', $affiliate_end_y) ?></select>年
			<select name="affiliate_end_m"><? select_month('', $affiliate_end_m) ?></select>月
			<select name="affiliate_end_d"><? select_day('', $affiliate_end_d) ?></select>日
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick='history.back()'>
<input type="hidden" name="next_action">
<input type="hidden" name="affiliate_mgr_id" <?=value($affiliate_mgr_id)?>>
</form>

</div>

<? page_footer() ?>

</body>
</html>
