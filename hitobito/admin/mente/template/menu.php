<?php
/**
 * ひとびと･net 事務局ページ
 *
 * テンプレート管理メニューページ
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
include("$inc/menu.php");

$header = new header_class('テンプレート管理メニュー', BACK_TOP);

// メニュー設定
$menu = new menu_class();
$menu->set_category('テンプレート');
$menu->set_menu('送信メールテンプレート', 'mail/list.php');
$menu->set_menu('会議室ヘッダ・フッタ', 'room/edit.php');
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<?php $menu->disp_menu(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
