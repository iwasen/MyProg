<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:学年マスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

// 学年値
function select_attb() {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, 0), ">$i</option>\n";
	}
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '学年マスタ', BACK_TOP);

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
	if (f.name.value == "") {
		alert("学年名称を入力してください。");
		f.name.focus();
		return false;
	}
	if (f.age_from.value == "") {
		alert("年齢下限を入力してください。");
		f.age_from.focus();
		return false;
	}
	if (f.age_to.value == "") {
		alert("年齢上限を入力してください。");
		f.age_to.focus();
		return false;
	}
	return confirm("学年を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.name.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録する学年情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">学年名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="name" size=50 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">年齢下限<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="age_from">
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	</tr>
		<td class="m1">年齢上限<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="age_to">
			<span class="note">（半角数字）</span>
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
