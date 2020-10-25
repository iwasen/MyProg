<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:都市名マスタ変更画面
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

// 地域コード出力
function encode_area($name) {
	if ($name != '') {
		$sql = "SELECT ar_area_cd FROM m_area WHERE ar_area_name='$name'";
		$code = db_fetch1($sql);
	}
	return $code;
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '都市名マスタ', BACK_TOP);

$sql = "SELECT * FROM m_city WHERE ci_seq_no = $ci_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("都市名IDが不正", __FILE__);
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
	if (f.ci_shiku.value == "") {
		alert("都市名を入力してください。");
		f.ci_shiku.focus();
		return false;
	}
	return confirm("都市名マスタ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("都市名を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■都市名情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">都道府県名ID<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="ci_todoufuken"><? select_area('', encode_area($fetch->ci_todoufuken)) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">都市名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ci_shiku" size=40 maxlength=10 <?=value($fetch->ci_shiku)?>>
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="ci_no" <?=value($ci_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
