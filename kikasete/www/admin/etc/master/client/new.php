<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:企業マスタ新規登録画面
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

set_global('etc', 'その他管理｜マスタメンテナンス', '企業マスタ', BACK_TOP);
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
	if (f.cl_name.value == "") {
		alert("企業名を入力してください。");
		f.cl_name.focus();
		return false;
	}
	if (f.cl_name_kana.value == "") {
		alert("企業名(カナ)入力してください。");
		f.cl_name_kana.focus();
		return false;
	}
	if (!num_chk(f.cl_zip1.value)){
		alert("郵便番号は半角の数値で入力してください。");
		f.cl_zip1.focus();
		return(false);
	}
	if (!num_chk(f.cl_zip2.value)){
		alert("郵便番号は半角の数値で入力してください。");
		f.cl_zip2.focus();
		return(false);
	}
	return confirm("企業を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.cl_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する企業情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">企業名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cl_name" size=70 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">企業名(カナ)<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cl_name_kana" size=70 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1">
			<input class="number" type="text" name="cl_zip1" size=4 maxlength=3>-
			<input class="number" type="text" name="cl_zip2" size=5 maxlength=4>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">住所</td>
		<td class="n1">
			<select name="cl_address1"><?select_area('選択して下さい', '')?>
			</select>
			<br>
			<input class="kanji" type="text" name="cl_address2" size=70 maxlength=50>
			<span class="note">（市区）</span>
			<br>
			<input class="kanji" type="text" name="cl_address3" size=70 maxlength=50>
			<span class="note">（町村番地）</span>
			<br>
			<input class="kanji" type="text" name="cl_address4" size=70 maxlength=50>
			<span class="note">（ビル名）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1">
			<select name="cl_gyoushu_cd"><?select_gyoushu('選択して下さい', '', 2)?>
			</select>
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
