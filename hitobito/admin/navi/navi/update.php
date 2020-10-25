<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ登録情報更新ページ
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
$header = new header_class('ナビ登録情報編集', BACK_TOP);

// データ処理クラス生成
$data = new navi_data_class();

// フォームからデータ読み込み
$data->read_form();

// DB更新
$data->action_db($_POST['next_action']);

if ($data->err) {
	$msg = 'ナビアドレス[' . $data->forward_id . ']は、既に登録されています。';
	$link = "history.back();";
} else {
	$msg = 'ナビ登録情報を' . $data->action . 'しました。';
	$link = "location.href='list.php'";
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$link?>"></p>

<?php $header->page_footer(); ?>

</body>
</html>
