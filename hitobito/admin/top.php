<?php
/**
 * ひとびと･net 事務局ページ
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

if ($_SESSION['login_type'] == 3)
	redirect('agent/menu.php');

// ヘッダ情報設定
$header = new header_class('ひとびと・net 事務局メニュー', BACK_LOGOFF);

// メニュー設定
$menu = new menu_class();
$menu->set_category('A. メンバー管理');
$menu->set_menu('メンバー検索', 'member/member/list.php');
$menu->set_menu('メンバー推移', 'member/transition/month.php');
$menu->set_menu('メンバープロファイル', 'member/profile/profile.php');
$menu->set_menu('ひとびと便り管理', 'member/letter/list.php');
$menu->set_menu('メンバーアンケート管理', 'member/enquete/list.php');

$menu->set_category('B. ナビ管理');
$menu->set_menu('ナビ検索', 'navi/navi/list.php');
$menu->set_menu('ナビ推移', 'navi/transition/transition.php');
$menu->set_menu('ナビプロファイル', 'navi/profile/profile.php');
$menu->set_menu('応募管理', 'navi/apply/list.php');
$menu->set_menu('公開進行管理', 'navi/progress/list.php');
$menu->set_menu('承認処理', 'navi/approval/list.php');
$menu->set_menu('メルマガ発行者管理', 'navi/publisher/list.php');

$menu->set_category('C. Blog管理');
$menu->set_menu('Blog記事管理', 'blog/article/list.php');
$menu->set_menu('Blog活性化推移', 'blog/transition/month.php');
$menu->set_menu('Blog活性化状況', 'blog/active/list.php');
$menu->set_menu('メルマガ品質管理', 'blog/magazine/list.php');
$menu->set_menu('アンケート品質管理', 'blog/enquete/list.php');
$menu->set_menu('リンク品質管理', 'blog/link/list.php');
$menu->set_menu('問い合わせ管理', 'blog/inquiry/list.php');

$menu->set_category('D. ML管理');
$menu->set_menu('ML投稿管理', 'ml/contribution/list.php');
$menu->set_menu('ML投稿数推移', 'ml/transition/month.php');
$menu->set_menu('ML活性化状況', 'ml/active/list.php');
$menu->set_menu('ゲスト登録管理', 'ml/guest/list.php');
$menu->set_menu('登録アドレス以外の投稿管理', 'ml/permit/list.php');

$menu->set_category('E. サイト管理');
$menu->set_menu('ナビランキング', 'site/ranking-navi/edit.php');
$menu->set_menu('人気記事ランキング', 'site/ranking-article/edit.php');
$menu->set_menu('今日のイチオシナビ', 'site/recommend/edit.php');
$menu->set_menu('ナビ記事最新投稿', 'site/new-article/edit.php');
$menu->set_menu('ワイワイ会議室最新投稿', 'site/new-contribution/edit.php');
$menu->set_menu('ナビセレクト最新投稿', 'site/new-selection/edit.php');
$menu->set_menu('特集記事', 'site/feature-articles/list.php');
$menu->set_menu('最新クチコミ情報', 'site/new-kuchikomi/edit.php');
$menu->set_menu('チャネル管理', 'site/channel/list.php');
$menu->set_menu('サイトプロファイル情報', 'site/siteprofile/siteprofile.php');
$menu->set_menu('クリックカウンター', 'site/clickcounter/list.php');
$menu->set_menu('月報処理', '');

$menu->set_category('F. 事務局メンテナンス');
$menu->set_menu('パトロール隊管理', 'mente/patrol/menu.php');
$menu->set_menu('ナビヘルプML管理', '');
$menu->set_menu('ナビパートナーML管理', '');
$menu->set_menu('管理者登録', 'mente/admin/list.php');
$menu->set_menu('マスターメンテナンス', 'mente/master/menu.php');
$menu->set_menu('テンプレート管理', 'mente/template/menu.php');
$menu->set_menu('バッチログ', 'mente/batch_log/list.php');
$menu->set_menu('エージェント画面', 'agent/menu.php');
$menu->set_menu('エージェント登録', 'mente/agent/list.php');
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
