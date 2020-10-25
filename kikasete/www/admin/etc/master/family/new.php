<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:家族続柄マスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// 家族続柄値
function select_attb() {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, 0), ">$i</option>\n";
	}
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '家族続柄マスタ', BACK_TOP);

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
	if (f.fr_name.value == "") {
		alert("家族続柄名称を入力してください。");
		f.fr_name.focus();
		return false;
	}
	if (f.fr_sex.value == "") {
		alert("家族性別を選択してください。");
		f.fr_sex.focus();
		return false;
	}
	return confirm("家族続柄名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.fr_name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する家族続柄情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">家族続柄名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="fr_name" size=50 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">家族続柄値<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_attb"><?select_attb()?></select>
			<span class="note">（-1以下:親 0:配偶者もしくは兄弟 1以上:子供）</span>
		</td>
	</tr>
		<td class="m1">家族性別<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_sex">
				<option value="" selected>選択して下さい</option>
				<option value=0>配偶者</option>
				<option value=1>男性</option>
				<option value=2>女性</option>
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
