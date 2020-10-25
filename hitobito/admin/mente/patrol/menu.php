<?php
/**
 * ひとびと･net 事務局ページ
 *
 * パトロール隊管理メニューページ
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

// ヘッダ情報設定
$header = new header_class('パトロール隊管理メニュー', BACK_TOP);

// メニュー設定
$menu = new menu_class();
$menu->set_category('A. パトロール隊管理');
$menu->set_menu('パトロール隊登録', 'regist/list.php');
$menu->set_menu('スケジュール', 'schedule.php');
$menu->set_menu('ログイン', 'login.php');
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
