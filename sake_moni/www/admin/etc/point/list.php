<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ポイント情報出力
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('etc', 'その他管理', 'ポイント情報出力', BACK_TOP);
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
	if (f.from_month.value == "") {
		alert("ポイント出力期間を入力してください。");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.value == "") {
		alert("ポイント出力期間を入力してください。");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.value == "") {
		alert("ポイント出力期間を入力してください。");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.value == "") {
		alert("ポイント出力期間を入力してください。");
		f.to_day.focus();
		return false;
	}
	return confirm("ポイント情報をダウンロードします。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="csv.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■ポイント出力条件を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ポイント出力期間<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<select name="from_year"><? select_year(2004, '', date('Y')) ?></select>年
			<select name="from_month"><? select_month('　') ?></select>月
			<select name="from_day"><? select_day('　') ?></select>日
			〜
			<select name="to_year"><? select_year(date('Y')) ?></select>年
			<select name="to_month"><? select_month('　') ?></select>月
			<select name="to_day"><? select_day('　') ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント出力媒体</td>
		<td class="n1">
			<select name="net_id">
				<option value="1">きかせて・net</option>
				<option value="2">iMiネット</option>
			</select>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="ダウンロード">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
