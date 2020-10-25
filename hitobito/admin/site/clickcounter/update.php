<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンター更新ページ
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
$header = new header_class('クリックカウンター更新', BACK_TOP);

// データ処理クラス生成
$data = new recruit_data_class();

// DBからデータ読み込み
if ($_POST['recruit_id'])
	$data->read_db($_POST['recruit_id']);

// フォームからデータ読み込み
$data->read_form();

// DB更新
$data->action_db($_POST['next_action']);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">クリックカウンターを<?=$data->action?>しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
