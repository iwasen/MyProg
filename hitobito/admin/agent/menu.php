<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����������ȥ�˥塼�ڡ���
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

// �إå���������
$header = new header_class('�ҤȤӤȡ�net ����������ȥ�˥塼', BACK_LOGOFF);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('A. ���С�����');
$menu->set_menu('���С�����', '../member/member/list.php');
$menu->set_menu('���С����', '../member/transition/month.php');
$menu->set_menu('���С����󥱡��ȴ���', '../member/enquete/list.php');

$menu->set_category('B. �ʥӴ���');
$menu->set_menu('�ʥӸ���', '../navi/navi/list.php');
$menu->set_menu('�ʥӥץ�ե�����', '../navi/profile/profile.php');
$menu->set_menu('', '');
$menu->set_menu('�ʥ���Ͽ', '../navi/apply/list.php');
$menu->set_menu('�����ʹԴ���', '../navi/progress/list.php');
$menu->set_menu('���ޥ�ȯ�ԼԴ���', '../navi/publisher/list.php');

$menu->set_category('C. Blog����');
$menu->set_menu('Blog��������', '../blog/article/list.php');
$menu->set_menu('Blog���������', '../blog/transition/month.php');
$menu->set_menu('Blog����������', '../blog/active/list.php');
$menu->set_menu('���ޥ��ʼ�����', '../blog/magazine/list.php');
$menu->set_menu('���󥱡����ʼ�����', '../blog/enquete/list.php');
$menu->set_menu('����ʼ�����', '../blog/link/list.php');

$menu->set_category('D. ML����');
$menu->set_menu('ML��ƴ���', '../ml/contribution/list.php');
$menu->set_menu('ML��ƿ����', '../ml/transition/month.php');
$menu->set_menu('ML����������', '../ml/active/list.php');
$menu->set_menu('������������Ͽ����', '../ml/guest/list.php');

$menu->set_category('E. �����ȴ���');
$menu->set_menu('�������', '');
$menu->set_menu('����å������󥿡�', '../site/clickcounter/list.php');
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
