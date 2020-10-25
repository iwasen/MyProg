<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ品質管理理ページ
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
include("$inc/ngword.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('メールマガジン管理', BACK_TOP);

// データ処理クラス生成
$data = new melmaga_contents_data_class();

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

<p class="msg"><?=$data->edit_name?>情報を<?=$data->action?>しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='check.php?melmaga_contents_id=<?=$_POST['melmaga_contents_id']?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
