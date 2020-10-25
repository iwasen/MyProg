<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:家族続柄マスタ変更画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// 家族続柄値
function select_attb($default) {
	for ($i = -10; $i <= 10; $i++) {
		echo '<option ', value_selected($i, $default), ">$i</option>\n";
	}
}

//家族性別
function select_sex($default) {
	echo '<option ', value_selected(0, $default), ">配偶者</option>\n";
	echo '<option ', value_selected(1, $default), ">男性</option>\n";
	echo '<option ', value_selected(2, $default), ">女性</option>\n";
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '家族続柄マスタ', BACK_TOP);

$sql = "SELECT * FROM m_family_rel WHERE fr_family_rel_cd = $fr_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("家族続柄CDが不正", __FILE__);
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
	if (f.fr_name.value == "") {
		alert("家族続柄名称を入力してください。");
		f.fr_name.focus();
		return false;
	}
	return confirm("家族続柄マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("家族続柄名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■家族続柄情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">家族続柄名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" name="fr_name" size=50 maxlength=25 <?=value($fetch->fr_name)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">家族続柄値<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_attb"><?select_attb($fetch->fr_attb)?></select>
			<span class="note">（-1以下:親 0:配偶者もしくは兄弟 1以上:子供）</span>
		</td>
	</tr>
	</tr>
		<td class="m1">家族性別<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="fr_sex"><?select_sex($fetch->fr_sex)?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="fr_cd" <?=value($fr_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
