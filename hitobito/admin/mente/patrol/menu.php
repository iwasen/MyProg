<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ����������˥塼�ڡ���
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

// �إå���������
$header = new header_class('�ѥȥ����������˥塼', BACK_TOP);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('A. �ѥȥ��������');
$menu->set_menu('�ѥȥ�������Ͽ', 'regist/list.php');
$menu->set_menu('�������塼��', 'schedule.php');
$menu->set_menu('������', 'login.php');
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
