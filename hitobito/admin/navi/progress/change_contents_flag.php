<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥڡ�������ƥ�ĥ����å��ե饰�����ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/header.php");
include("$inc/mail.php");
include("$inc/navi_mail.php");

// �ѥ�᡼������
$navi_page_id = $_GET['navi_page_id'];
$flag = $_GET['flag'];

// �ڡ����إå���������
$header = new header_class('�����ʹԴ���', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_contents_check=$flag"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($flag) {
case 0:
	$msg = '����ƥ��CHK��̤�������ᤷ�ޤ�����';
	break;
case 2:
	send_navi_mail($navi_page_id, 'navi_content_ng');
	$msg = '����ƥ��CHK�򺹤��ᤷ�ޤ����������ᤷ�᡼�뤬�ۿ�����ޤ�����';
	break;
case 3:
	$msg = '����ƥ��CHK��ǧ���ޤ�����';
	break;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onClick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>