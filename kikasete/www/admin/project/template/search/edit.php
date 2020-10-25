<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:対象条件マスタ登録登録
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/search.php");
include("$inc/condition.php");
include("$inc/select.php");

//メイン処理
set_global2('project', 'プロジェクト管理｜テンプレート編集', '対象条件テンプレート変更', BACK_TOP);

$search = new search_class;

$sql = "SELECT ps_title,ps_search_id FROM m_pjt_search WHERE ps_seq_no=$ps_no";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
if ($fetch->ps_search_id) {
	$search_id = $fetch->ps_search_id;
	$search->read_db($search_id);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function OnClick_search() {
	var f = document.form1;
	f.target = "_blank";
	f.action = "search.php";
	f.submit();
}

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
	if (f.ps_title.value == "") {
		alert("対象条件名を入力してください。");
		f.ps_title.focus();
		return false;
	}
	return confirm("対象条件を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("対象条件を削除します。よろしいですか？");
}
//-->
</script>
<? search_script($search) ?>
<? menu_script() ?>
</head>
<body onload="show_reset();document.form1.ps_title.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m0" align="left">■対象条件名を入力して下さい</td>
		<td align="right"><a href="javascript:history.back()"><nobr><font size=-1>>>戻る</font></nobr></a></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m1" width=20%>タイトル</td>
		<td class="n1" width=80%><input class="kanji" type="text" name="ps_title" size=70 maxlength=100 <?=value($fetch->ps_title)?>></td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■対象条件を設定して下さい</td>
	</tr>
</table>
<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
<? search_condition($search, 'small') ?>
</table><br>
<table class="small" border=0 cellspacing=3 cellpadding=0 width=700>
<div align="center">
<input type="hidden" name="search_id" <?=value($search_id)?>>
<input type="hidden" name="ps_no" <?=value($ps_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="リセット" onclick="document.form1.reset();show_reset()">
<input type="button" value="人数検索" onclick="OnClick_search()">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
