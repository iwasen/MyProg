<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 郵便番号マスタアップロードページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/edit.php");

$header = new header_class('郵便番号マスタアップロード', BACK_TOP);

// 編集ページ処理クラス生成
$edit = new edit_class('郵便番号CSVファイルを入力してください。');

$edit->set_item('郵便番号CSVファイル', true);
$edit->set_file('zip_csv', 60);
$edit->set_note('（KEN_ALL.CSV）');

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (!input_check(f))
		return false;
	if (!confirm("郵便番号マスタを更新します。よろしいですか？"))
		return false;
	f.upload.disabled = true;
	document.getElementById("msg").innerHTML = "<br><font color='red'>しばらくお待ちください。</font><br>";
	return true;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)" enctype="multipart/form-data">

<?php $edit->disp_edit() ?>

<br>
<?php
if ((int)ini_get('post_max_size') < 15 || (int)ini_get('upload_max_filesize') < 15) {
?>
<font color="red">ini.php の post_max_size と upload_max_filesize は15M以上に設定してください。</font><br><br>
<?php
} else {
?>
<input type="submit" name="upload" value="アップロード">
<?php
}
?>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
<span id="msg"></span>

<?php $header->page_footer(); ?>

</body>
</html>
