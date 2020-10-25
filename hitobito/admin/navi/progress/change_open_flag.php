<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビページ公開フラグ更新ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/header.php");

// パラメータ取得
$navi_page_id = $_GET['navi_page_id'];
$flag = $_GET['flag'];

// ページヘッダ情報設定
$header = new header_class('公開進行管理', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_open_flag=$flag"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($flag) {
case 0:
	$msg = '公開前最終CHKを未申請に戻しました。';
	break;
case 2:
	$msg = '公開前最終CHKを申請しました。';
	break;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onClick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
