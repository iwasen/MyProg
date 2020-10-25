<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:調査結果新規アップロード（複数）画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('etc', 'その他管理', '調査結果アップロード（複数選択）', BACK_TOP);

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
	if (f.title.value == "") {
		alert("タイトルを入力してください。");
		f.title.focus();
		return false;
	}
	if (f.from_month.value == "") {
		alert("調査期間を入力してください。");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.value == "") {
		alert("調査期間を入力してください。");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.value == "") {
		alert("調査期間を入力してください。");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.value == "") {
		alert("調査期間を入力してください。");
		f.to_day.focus();
		return false;
	}
	if (f.compressed_file.value == "") {
		alert("圧縮ファイルを入力してください。");
		f.compressed_file.focus();
		return false;
	}
	return confirm("調査結果をアップロードします。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="up_multi.php" enctype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■調査結果を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">タイトル<?=MUST_ITEM?></td>
		<td class="n1"><input type="text" name="title" size=80></td>
	</tr>
	<tr>
		<td class="m1">調査期間<?=MUST_ITEM?></td>
		<td class="n1">
			<?/*↓一年前も表示する為追加20061222 */?>
                        <select name="from_year"><? select_year(date('Y')-1,"",date('Y')) ?></select>年
                        <?/*↓コメントアウト20061222 */?>
                        <?/*<select name="from_year"><? select_year(date('Y')) ?></select>年*/?>
			<select name="from_month"><? select_month('　') ?></select>月
			<select name="from_day"><? select_day('　') ?></select>日
			〜
                        <?/*↓一年前も表示する為追加20061222 */?>
                        <select name="to_year"><? select_year(date('Y')-1,"",date('Y')) ?></select>年
                        <?/*↓コメントアウト20061222 */?>
                        <?/*<select name="to_year"><? select_year(date('Y')) ?></select>年*/?>
			<select name="to_month"><? select_month('　') ?></select>月
			<select name="to_day"><? select_day('　') ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">圧縮ファイル<?=MUST_ITEM?></td>
		<td class="n1"><input type="file" name="compressed_file" size=60></td>
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
