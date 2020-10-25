<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:業態マスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '業態マスタ', BACK_TOP);

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
	if (f.gt_order.value == "") {
		alert("表示順序を入力してください。");
		f.gt_order.focus();
		return false;
	}
	if (!num_chk(f.gt_order.value)){
		alert("表示順序は半角の数値で入力してください。");
		f.gt_order.focus();
		return(false);
	}
	if (f.gyoutai_name.value == "") {
		alert("業態名を入力してください。");
		f.gyoutai_name.focus();
		return false;
	}
	return confirm("業態名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.gt_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録する業態情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">表示順序<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="gt_order" size=20 maxlength=4>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">業態名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="gyoutai_name" size=70 maxlength=25>
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
