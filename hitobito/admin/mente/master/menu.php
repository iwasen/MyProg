<?php
/**
 * ひとびと･net 事務局ページ
 *
 * マスタメンテナンスメニューページ
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

$header = new header_class('マスタメンテナンスメニュー', BACK_TOP);

// メニュー設定
$menu = new menu_class();
$menu->set_category('基本マスタ');
$menu->set_menu('システムマスタ', 'system/list.php');
$menu->set_menu('郵便番号マスタ', 'zip/upload.php');
$menu->set_menu('職業マスタ', 'shokugyo/list.php');
$menu->set_menu('退会理由マスタ', 'resign_reason/list.php');
$menu->set_menu('NGワードマスタ', 'ngword/list.php');
$menu->set_menu('Pingマスタ', 'ping/list.php');
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
