<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:住居面積マスタ変更画面
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
set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '住居面積マスタ', BACK_TOP);

$sql = "SELECT * FROM m_space WHERE sp_space_cd = $sp_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("住居面積IDが不正", __FILE__);
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function update_check(f) {
	if (f.name.value == "") {
		alert("住居面積名を入力してください。");
		f.name.focus();
		return false;
	}
	if (f.from.value == "") {
		alert("住居面積の上限を入力してください。");
		f.from.focus();
		return false;
	}
	if (!num_chk(f.from.value)){
		alert("住居面積の上限は半角の数値で入力してください。");
		f.from.focus();
		return(false);
	}
	if (f.to.value == "") {
		alert("住居面積の下限を入力してください。");
		f.to.focus();
		return false;
	}
	if (!num_chk(f.to.value)){
		alert("住居面積の下限は半角の数値で入力してください。");
		f.to.focus();
		return(false);
	}
	return confirm("住居面積マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("住居面積名を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■住居面積情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">住居面積名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=70 maxlength=25 <?=value($fetch->sp_space_text)?>>
		</td>
	<tr>
		<td class="m1">住居面積の上限<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="from" size=20 maxlength=4 <?=value($fetch->sp_space_from)?>>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">住居面積の下限<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="to" size=20 maxlength=4 <?=value($fetch->sp_space_to)?>>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sp_cd" <?=value($sp_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
