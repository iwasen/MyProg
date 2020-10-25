<?php
/**
 * ひとびと･net パトロール隊ページ
 *
 * メインメニューページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '.';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/menu.php");

// ヘッダ情報設定
$header = new header_class('ひとびと・net パトロール隊メニュー', BACK_LOGOFF);

// メニュー設定
$menu = new menu_class();
$menu->set_category('<br>');
$menu->set_menu('スケジュール管理', 'mente/patrol/schedule.php');
$menu->set_menu('Blog記事管理', 'blog/article/list.php');
$menu->set_menu('ML投稿管理', 'ml/contribution/list.php');
$menu->set_menu('メルマガ品質管理', 'blog/magazine/list.php');
$menu->set_menu('アンケート品質管理', 'blog/enquete/list.php');
$menu->set_menu('リンク品質管理', 'blog/link/list.php');
?>

<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>

<?php $menu->disp_menu(); ?>

<?php $header->page_footer(); ?>
