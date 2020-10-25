<?php
/**
 * ひとびと･net 事務局ページ
 *
 * エージェントメニューページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/menu.php");

// ヘッダ情報設定
$header = new header_class('ひとびと・net エージェントメニュー', BACK_LOGOFF);

// メニュー設定
$menu = new menu_class();
$menu->set_category('A. メンバー管理');
$menu->set_menu('メンバー検索', '../member/member/list.php');
$menu->set_menu('メンバー推移', '../member/transition/month.php');
$menu->set_menu('メンバーアンケート管理', '../member/enquete/list.php');

$menu->set_category('B. ナビ管理');
$menu->set_menu('ナビ検索', '../navi/navi/list.php');
$menu->set_menu('ナビプロファイル', '../navi/profile/profile.php');
$menu->set_menu('', '');
$menu->set_menu('ナビ登録', '../navi/apply/list.php');
$menu->set_menu('公開進行管理', '../navi/progress/list.php');
$menu->set_menu('メルマガ発行者管理', '../navi/publisher/list.php');

$menu->set_category('C. Blog管理');
$menu->set_menu('Blog記事管理', '../blog/article/list.php');
$menu->set_menu('Blog活性化推移', '../blog/transition/month.php');
$menu->set_menu('Blog活性化状況', '../blog/active/list.php');
$menu->set_menu('メルマガ品質管理', '../blog/magazine/list.php');
$menu->set_menu('アンケート品質管理', '../blog/enquete/list.php');
$menu->set_menu('リンク品質管理', '../blog/link/list.php');

$menu->set_category('D. ML管理');
$menu->set_menu('ML投稿管理', '../ml/contribution/list.php');
$menu->set_menu('ML投稿数推移', '../ml/transition/month.php');
$menu->set_menu('ML活性化状況', '../ml/active/list.php');
$menu->set_menu('仮・ゲスト登録管理', '../ml/guest/list.php');

$menu->set_category('E. サイト管理');
$menu->set_menu('月報処理', '');
$menu->set_menu('クリックカウンター', '../site/clickcounter/list.php');
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
