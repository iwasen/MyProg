<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
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

if ($_SESSION['login_type'] == 3)
	redirect('agent/menu.php');

// �إå���������
$header = new header_class('�ҤȤӤȡ�net ��̳�ɥ�˥塼', BACK_LOGOFF);

// ��˥塼����
$menu = new menu_class();
$menu->set_category('A. ���С�����');
$menu->set_menu('���С�����', 'member/member/list.php');
$menu->set_menu('���С����', 'member/transition/month.php');
$menu->set_menu('���С��ץ�ե�����', 'member/profile/profile.php');
$menu->set_menu('�ҤȤӤ��ؤ����', 'member/letter/list.php');
$menu->set_menu('���С����󥱡��ȴ���', 'member/enquete/list.php');

$menu->set_category('B. �ʥӴ���');
$menu->set_menu('�ʥӸ���', 'navi/navi/list.php');
$menu->set_menu('�ʥӿ��', 'navi/transition/transition.php');
$menu->set_menu('�ʥӥץ�ե�����', 'navi/profile/profile.php');
$menu->set_menu('�������', 'navi/apply/list.php');
$menu->set_menu('�����ʹԴ���', 'navi/progress/list.php');
$menu->set_menu('��ǧ����', 'navi/approval/list.php');
$menu->set_menu('���ޥ�ȯ�ԼԴ���', 'navi/publisher/list.php');

$menu->set_category('C. Blog����');
$menu->set_menu('Blog��������', 'blog/article/list.php');
$menu->set_menu('Blog���������', 'blog/transition/month.php');
$menu->set_menu('Blog����������', 'blog/active/list.php');
$menu->set_menu('���ޥ��ʼ�����', 'blog/magazine/list.php');
$menu->set_menu('���󥱡����ʼ�����', 'blog/enquete/list.php');
$menu->set_menu('����ʼ�����', 'blog/link/list.php');
$menu->set_menu('�䤤��碌����', 'blog/inquiry/list.php');

$menu->set_category('D. ML����');
$menu->set_menu('ML��ƴ���', 'ml/contribution/list.php');
$menu->set_menu('ML��ƿ����', 'ml/transition/month.php');
$menu->set_menu('ML����������', 'ml/active/list.php');
$menu->set_menu('��������Ͽ����', 'ml/guest/list.php');
$menu->set_menu('��Ͽ���ɥ쥹�ʳ�����ƴ���', 'ml/permit/list.php');

$menu->set_category('E. �����ȴ���');
$menu->set_menu('�ʥӥ�󥭥�', 'site/ranking-navi/edit.php');
$menu->set_menu('�͵�������󥭥�', 'site/ranking-article/edit.php');
$menu->set_menu('�����Υ��������ʥ�', 'site/recommend/edit.php');
$menu->set_menu('�ʥӵ����ǿ����', 'site/new-article/edit.php');
$menu->set_menu('�磻�磻��ļ��ǿ����', 'site/new-contribution/edit.php');
$menu->set_menu('�ʥӥ��쥯�Ⱥǿ����', 'site/new-selection/edit.php');
$menu->set_menu('�ý�����', 'site/feature-articles/list.php');
$menu->set_menu('�ǿ��������߾���', 'site/new-kuchikomi/edit.php');
$menu->set_menu('����ͥ����', 'site/channel/list.php');
$menu->set_menu('�����ȥץ�ե��������', 'site/siteprofile/siteprofile.php');
$menu->set_menu('����å������󥿡�', 'site/clickcounter/list.php');
$menu->set_menu('�������', '');

$menu->set_category('F. ��̳�ɥ��ƥʥ�');
$menu->set_menu('�ѥȥ��������', 'mente/patrol/menu.php');
$menu->set_menu('�ʥӥإ��ML����', '');
$menu->set_menu('�ʥӥѡ��ȥʡ�ML����', '');
$menu->set_menu('��������Ͽ', 'mente/admin/list.php');
$menu->set_menu('�ޥ��������ƥʥ�', 'mente/master/menu.php');
$menu->set_menu('�ƥ�ץ졼�ȴ���', 'mente/template/menu.php');
$menu->set_menu('�Хå���', 'mente/batch_log/list.php');
$menu->set_menu('����������Ȳ���', 'agent/menu.php');
$menu->set_menu('�������������Ͽ', 'mente/agent/list.php');
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
