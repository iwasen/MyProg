<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ƥ�ץ졼�ȴ�����˥塼�ڡ���
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

$header = new header_class('�ƥ�ץ졼�ȴ�����˥塼', BACK_TOP);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('�ƥ�ץ졼��');
$menu->set_menu('�����᡼��ƥ�ץ졼��', 'mail/list.php');
$menu->set_menu('��ļ��إå����եå�', 'room/edit.php');
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
