<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:地域マスタ変更画面
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
set_global('etc', 'その他管理｜マスタメンテナンス', '地域マスタ', BACK_TOP);

$sql = "SELECT * FROM m_area WHERE ar_area_cd = $ar_cd";
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
	if (f.ar_name.value == "") {
		alert("地域(都道府県)名を入力してください。");
		f.ar_name.focus();
		return false;
	}
	return confirm("地域マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("地域名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■地域情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">地域ID</td>
		<td class="n1"><?=$fetch->ar_area_cd?></td>
		</td>
	</tr>
	<tr>
		<td class="m1">地域(都道府県)名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ar_name" size=70 maxlength=25 <?=value($fetch->ar_area_name)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="ar_cd" <?=value($ar_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
