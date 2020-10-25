<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:地域区分マスタ変更画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// メイン処理
set_global('master', 'マスタ管理｜地域マスタ', '地域区分マスタ', BACK_TOP);

$sql = "SELECT * FROM m_area2 WHERE aa_area2_cd = $aa_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("地域IDが不正", __FILE__);
$fetch = pg_fetch_object($result, 0);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.aa_name.value == "") {
		alert("地域区分名を入力してください。");
		f.aa_name.focus();
		return false;
	}
	return confirm("地域区分マスタ情報を更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■地域区分情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">地域ID</td>
		<td class="n1"><?=$aa_cd?></td>
		</td>
	</tr>
	<tr>
		<td class="m1">地域区分名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="aa_name" size=70 maxlength=10 <?=value($fetch->aa_area2_name)?>>
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="aa_cd" <?=value($aa_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
