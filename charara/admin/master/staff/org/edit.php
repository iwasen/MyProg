<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜スタッフ関連マスタ｜所属マスタ
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
include("$inc/decode.php");

// メイン処理
set_global('master_st', 'マスタ管理｜スタッフ関連マスタ', '所属マスタ', BACK_TOP);

$sql = "SELECT * FROM m_staff_org WHERE so_org_cd = $so_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("所属名IDが不正", __FILE__);
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
	if (f.so_kind_cd.value == "") {
		alert("種別名を選択してください。");
		f.so_kind_cd.focus();
		return false;
	}
	if (f.so_name.value == "") {
		alert("所属名を入力してください。");
		f.so_name.focus();
		return false;
	}
	return confirm("所属名マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("所属名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■更新する所属情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">種別名<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="so_kind_cd"><?=select_staff_kind('', decode_staff_kind($fetch->so_kind_cd))?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">所属名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="so_name" size=50 <?=value($fetch->so_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">所属名（カナ）</td>
		<td class="n1">
			<input class="kanji" type="text" name="so_name_kana" size=50 <?=value($fetch->so_name_kana)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">連絡先</td>
		<td class="n1">
			<textarea class="kanji" name="so_contact" cols=60 rows=10><?=htmlspecialchars($fetch->so_contact)?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="so_cd" <?=value($so_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
