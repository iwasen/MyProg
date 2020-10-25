<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便り配信ジョブ更新ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('ひとびと便り配信ジョブ更新', BACK_TOP);

// データ処理クラス生成
$data = new letter_data_class();

// DBからデータ読み込み
if ($_POST['letter_id'])
	$data->read_db($_POST['letter_id']);

// フォームからデータ読み込み
$data->read_form();

// DB更新
$data->action_db($_POST['next_action']);

if ($_POST['next_action'] == 'new')
	$href = "edit.php?letter_id=$data->letter_id";
else
	$href = 'list.php';
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">ひとびと便りを<?=$data->action?>しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='<?=$href?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
