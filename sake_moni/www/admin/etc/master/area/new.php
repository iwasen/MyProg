<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:地域マスタ新規登録画面
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
set_global('etc', 'その他管理｜マスタメンテナンス', '地域マスタ', BACK_TOP);

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
	if (f.ar_name.value == "") {
		alert("地域(都道府県)名を入力してください。");
		f.ar_name.focus();
		return false;
	}
	return confirm("地域名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.ar_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する地域情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">地域(都道府県)名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ar_name" size=70 maxlength=25>
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
