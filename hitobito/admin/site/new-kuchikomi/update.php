<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 最新クチコミ投稿更新ページ
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
$header = new header_class('最新クチコミ投稿更新', BACK_TOP);

// データ処理クラス生成
$data = new new_kuchikomi_data_class();

// フォームからデータ読み込み
$data->read_form();

// DB更新
$data->update_db();
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">最新クチコミ投稿を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='edit.php?channel_id=<?=$_POST['channel_id']?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
