<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥڡ������ơ����������ڡ���
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
$status = $_GET['status'];

// �ڡ����إå���������
$header = new header_class('��ǧ����', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_status=$status"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($status) {
case 0:
	$msg = '�ʥӱ����̤��ǧ���ᤷ�ޤ�����';
	break;
case 1:
	send_navi_mail($navi_page_id, 'navi_apply_ok');
	$msg = '�ʥӱ����ǧ���ޤ�������ǧ�᡼����ۿ����ޤ�����';
	break;
case 2:
	send_navi_mail($navi_page_id, 'navi_apply_ng');
	$msg = '�ʥӱ�����Ǥꤷ�ޤ��������Ǥ�᡼����ۿ����ޤ�����';
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
