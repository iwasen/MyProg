<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:チェーンマスタ新規登録画面
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// エリア選択肢
function select_chain_area($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ca_chain_area_cd,ca_area_name FROM m_chain_area ORDER BY ca_chain_area_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ca_chain_area_cd, $selected), '>', htmlspecialchars($fetch->ca_area_name), '</option>', "\n";
	}
}
// 業態選択肢
function select_gyoutai($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gt_gyoutai_cd,gt_gyoutai_name FROM m_gyoutai ORDER BY gt_gyoutai_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gt_gyoutai_cd, $selected), '>', htmlspecialchars($fetch->gt_gyoutai_name), '</option>', "\n";
	}
}

set_global('etc', 'その他管理｜マスタメンテナンス', 'チェーンマスタ', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onSubmit_form1(f) {
	if (f.ch_order.value == "") {
		alert("表示順序を入力してください。");
		f.ch_order.focus();
		return false;
	}
	if (!num_chk(f.ch_order.value)){
		alert("表示順序は半角の数値で入力してください。");
		f.ch_order.focus();
		return(false);
	}
	if (f.ch_name.value == "") {
		alert("チェーン名を入力してください。");
		f.ch_name.focus();
		return false;
	}
	if (f.chain_area_cd.value == "") {
		alert("エリアを選択してください。");
		f.chain_area_cd.focus();
		return false;
	}
        if (f.gyoutai_cd.value == "") {
		alert("業態を選択してください。");
		f.gyoutai_cd.focus();
		return false;
	}
	return confirm("チェーン名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.ch_order.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録するチェーン情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">表示順序<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="ch_order" size=20 maxlength=4>
			<span class="note">（半角数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">チェーン名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="ch_name" size=70 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
        <tr>
		<td class="m1">業態<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoutai_cd"><? select_gyoutai('- 選択してください -') ?></select></td>
	</tr>
	<tr>
		<td class="m1">エリア<?=MUST_ITEM?></td>
		<td class="n1"><select name="chain_area_cd"><? select_chain_area('- 選択してください -') ?></select></td>
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
