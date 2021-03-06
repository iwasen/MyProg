<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:家族カテゴリマスタ変更画面
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
set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '家族カテゴリマスタ', BACK_TOP);

$sql = "SELECT * FROM m_family_ctg WHERE fc_family_ctg_cd = $fc_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("家族カテゴリIDが不正", __FILE__);
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
		alert("家族カテゴリ名を入力してください。");
		f.name.focus();
		return false;
	}
	if (f.adult_num.value == "") {
		alert("大人の人数を入力してください。");
		f.adult_num.focus();
		return false;
	}
	if (!num_chk(f.adult_num.value)){
		alert("大人の人数は半角の数値で入力してください。");
		f.adult_num.focus();
		return(false);
	}
	if (f.child_num.value == "") {
		alert("小人の人数を入力してください。");
		f.child_num.focus();
		return false;
	}
	if (!num_chk(f.child_num.value)){
		alert("小人の人数は半角の数値で入力してください。");
		f.child_num.focus();
		return(false);
	}
	return confirm("家族カテゴリマスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("家族カテゴリ名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■家族カテゴリ情報を入力してください。</td>
	</tr>

	<tr>
		<td class="m1">家族カテゴリ名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=70 maxlength=25 <?=value($fetch->fc_text)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">大人数<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="adult_num" size=20 maxlength=4 <?=value($fetch->fc_adult_num)?>>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">小人数<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="child_num" size=20 maxlength=4 <?=value($fetch->fc_child_num)?>>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="fc_cd" <?=value($fc_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
