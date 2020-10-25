<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:興味ジャンルマスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '興味ジャンルマスタ', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onSubmit_form1(f) {
	if (f.kg_order.value == "") {
		alert("表示順序を入力してください。");
		f.kg_order.focus();
		return false;
	}
	if (!num_chk(f.kg_order.value)){
		alert("表示順序は半角の数値で入力してください。");
		f.kg_order.focus();
		return(false);
	}
	if (f.kg_name.value == "") {
		alert("興味ジャンル名を入力してください。");
		f.kg_name.focus();
		return false;
	}
	return confirm("興味ジャンル名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.kg_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する興味ジャンル情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">表示順序<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="kg_order" size=20 maxlength=4>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">興味ジャンル名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kg_name" size=70 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
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
