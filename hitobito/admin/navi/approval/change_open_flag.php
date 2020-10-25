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
include("$inc/mail.php");
include("$inc/navi_mail.php");

// パラメータ取得
$navi_page_id = $_GET['navi_page_id'];
$flag = $_GET['flag'];

// ページヘッダ情報設定
$header = new header_class('承認処理', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_open_flag=$flag"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($flag) {
case 0:
	$msg = '公開前最終CHKを解除しました。';
	break;
case 1:
	send_navi_mail($navi_page_id, 'navi_open_ok');
	$msg = '公開前最終CHKを承認しました。公開通知メールが配信されました。';
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
