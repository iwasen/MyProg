<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ޥ������ƥʥ󥹥�˥塼�ڡ���
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

$header = new header_class('�ޥ������ƥʥ󥹥�˥塼', BACK_TOP);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('���ܥޥ���');
$menu->set_menu('�����ƥ�ޥ���', 'system/list.php');
$menu->set_menu('͹���ֹ�ޥ���', 'zip/upload.php');
$menu->set_menu('���ȥޥ���', 'shokugyo/list.php');
$menu->set_menu('�����ͳ�ޥ���', 'resign_reason/list.php');
$menu->set_menu('NG��ɥޥ���', 'ngword/list.php');
$menu->set_menu('Ping�ޥ���', 'ping/list.php');
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
