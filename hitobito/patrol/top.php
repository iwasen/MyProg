<?php
/**
 * �ҤȤӤȎ�net �ѥȥ�����ڡ���
 *
 * �ᥤ���˥塼�ڡ���
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

// �إå���������
$header = new header_class('�ҤȤӤȡ�net �ѥȥ������˥塼', BACK_LOGOFF);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('<br>');
$menu->set_menu('�������塼�����', 'mente/patrol/schedule.php');
$menu->set_menu('Blog��������', 'blog/article/list.php');
$menu->set_menu('ML��ƴ���', 'ml/contribution/list.php');
$menu->set_menu('���ޥ��ʼ�����', 'blog/magazine/list.php');
$menu->set_menu('���󥱡����ʼ�����', 'blog/enquete/list.php');
$menu->set_menu('����ʼ�����', 'blog/link/list.php');
?>

<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>

<?php $menu->disp_menu(); ?>

<?php $header->page_footer(); ?>
