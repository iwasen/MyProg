<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:職業マスタ変更画面
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
set_global('etc', 'その他管理｜マスタメンテナンス', '職業マスタ', BACK_TOP);

$sql = "SELECT * FROM m_shokugyou WHERE sg_shokugyou_cd = $shokugyou_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("職業IDが不正", __FILE__);
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
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function update_check(f) {
	if (f.sg_order.value == "") {
		alert("表示順序を入力してください。");
		f.sg_order.focus();
		return false;
	}
	if (!num_chk(f.sg_order.value)){
		alert("表示順序は半角の数値で入力してください。");
		f.sg_order.focus();
		return(false);
	}
	if (f.shokugyou_name.value == "") {
		alert("職業名を入力してください。");
		f.shokugyou_name.focus();
		return false;
	}
	return confirm("職業マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("職業名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■職業情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">表示順序<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" name="sg_order" size=20 maxlength=4 <?=value($fetch->sg_order)?>>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">職業名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="shokugyou_name" size=70 maxlength=25 <?=value($fetch->sg_shokugyou_name)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="shokugyou_cd" <?=value($shokugyou_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
