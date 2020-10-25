<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:Welcomeメッセージ登録
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

set_global('member', '会員トップページ設定', 'Welcomeメッセージ', BACK_TOP);

$sql = "SELECT MAX(wm_end_date) AS max_date from t_welcome_msg";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('メッセージＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
$max_date = $fetch->max_date;
if ($max_date) {
	$max_date = mktime (0,0,0,get_datepart('M',$max_date),(get_datepart('D',$max_date) + 1),get_datepart('Y',$max_date));
	$max_date = strftime ("%Y-%m-%d", $max_date);
} else
	$max_date = date("Y-m-d");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}

function onSubmit_form1(f) {
	if (!check_date(f.start_y.value, f.start_m.value, f.start_d.value)) {
		alert("開始日の指定が正しくありません。");
		f.start_d.focus();
		return(false);
	}
	if (!check_date(f.end_y.value, f.end_m.value, f.end_d.value)) {
		alert("終了日の指定が正しくありません。");
		f.end_d.focus();
		return(false);
	}
	if (f.msg.value == "") {
		alert("メッセージを入力してください。");
		f.msg.focus();
		return false;
	}
	if (f.msg.value.indexOf('"') != -1) {
		alert("ダブルクォーテーションは使えません。");
		f.msg.focus();
		return false;
	}
	return confirm("Welcomeメッセージを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■Welcomeメッセージ情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">表示期間<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_y"><? select_year(2002, '', get_datepart('Y', $max_date)) ?></select>月
			<select name="start_m"><? select_month('', get_datepart('M', $max_date)) ?></select>月
			<select name="start_d"><? select_day('', get_datepart('D', $max_date)) ?></select>日〜
			<select name="end_y"><? select_year(2002, '', get_datepart('Y', $max_date)) ?></select>月
			<select name="end_m"><? select_month('', get_datepart('M', $max_date)) ?></select>月
			<select name="end_d"><? select_day(' ') ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">メッセージ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" class="kanji" name="msg" size=40 maxlength=50>
			<span class="note">（全角２０文字程度）</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<!--
<input type="reset" value="キャンセル">
-->
<input type="button" value=" 戻る " onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
