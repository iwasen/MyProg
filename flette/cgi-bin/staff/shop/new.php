#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:店舗新規登録画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
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
	if (f.shop_name.value == "") {
		alert("店舗名称を入力してください。");
		f.shop_name.focus();
		return false;
	}
	return confirm("店舗を新規登録します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? page_header('店舗新規登録', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■店舗情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">店舗名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="shop_name" size=30 maxlength=30>
			<span class="note">（全角・半角３０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">店舗住所</td>
		<td class="n1">
			<input class="kanji" type="text" name="shop_address" size=80 maxlength=200>
			<span class="note">（全角・半角２００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ステータス<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="status" value="1" checked>有効
			<input type="radio" name="status" value="9">無効
		</td>
	</tr>
	<tr>
		<td class="m1">表示順序</td>
		<td class="n1">
			<input class="number" type="text" name="display_order" size=5 maxlength=5>
			<span class="note">（半角数字５文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
