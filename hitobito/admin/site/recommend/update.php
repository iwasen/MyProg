<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 今日のイチオシナビ更新ページ
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
include("$inc/ichioshi_navi.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('今日のイチオシナビ更新', BACK_TOP);

// データ処理クラス生成
$data = new recommend_data_class();

// DBからデータ読み込み
$data->read_db($_POST['channel_id']);

// フォームからデータ読み込み
$data->read_form();

// DB更新
$data->update_db();

// イチオシナビ選択
select_ichioshi_navi($data->channel_id);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">今日のイチオシナビを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='<?=$top?>/top.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
