<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビML登録情報更新ページ
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
$header = new header_class('ワイワイ会議室（ML）管理', BACK_TOP);

// データ処理クラス生成
$data = new ml_data_class();

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

<p class="msg">ワイワイ会議室情報を<?=$data->action?>しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$_POST['ret'] ? "location.href='{$_POST['ret']}'" : 'history.go(-2)'?>"></p>

<?php $header->page_footer(); ?>

</body>
</html>
