<?php
/**
 * ひとびと･net 事務局ページ
 *
 * パトロール隊マスタ更新ページ
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
include("$inc/database.php");
include("$inc/disp_error.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('パトロール隊マスタ更新', BACK_TOP);

// データ処理クラス生成
$data = new patrol_data_class();

// フォームからデータ読み込み
$data->read_form();

// データチェック
if ($msg = $data->check_data($_POST['next_action']))
	disp_error($header, $msg);

// DB更新
$data->action_db($_POST['next_action']);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">パトロール隊を<?=$data->action?>しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
