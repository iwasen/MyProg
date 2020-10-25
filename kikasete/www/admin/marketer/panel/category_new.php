<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'スペシャルパネル管理', BACK_TOP);

// 新規カテゴリID取得
$sql = "SELECT MAX(pnc_category_id) FROM t_panel_category";
$category_id = db_fetch1($sql, 0) + 1;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	f.category_name.focus();
}
function onsubmit_form1(f) {
	if (f.category_name.value == "") {
		alert("カテゴリ名を入力してください。");
		f.category_name.focus();
		return false;
	}
	return confirm("スペシャルパネルカテゴリを新規登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="category_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■スペシャルパネルカテゴリ新規登録</td>
	</tr>
	<tr>
		<td class="m1" width="20%">カテゴリID</td>
		<td class="n1"><?=$category_id?><input type="hidden" name="category_id" <?=value($category_id)?>></td>
	</tr>
	<tr>
		<td class="m1" width="20%">カテゴリ名<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="text" name="category_name" size=80 maxlength=100>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
