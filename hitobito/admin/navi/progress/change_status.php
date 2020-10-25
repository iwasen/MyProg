<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビページステータス更新ページ
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
$status = $_GET['status'];

// ページヘッダ情報設定
$header = new header_class('公開進行管理', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_status=$status"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($status) {
case 0:
	$msg = 'ナビ応募を未承認に戻しました。';
	break;
case 1:
	send_navi_mail($navi_page_id, 'navi_apply_ok');
	$msg = 'ナビ応募を承認しました。承認メールを配信しました。';
	break;
case 2:
	send_navi_mail($navi_page_id, 'navi_apply_ng');
	$msg = 'ナビ応募をお断りしました。お断りメールを配信しました。';
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
