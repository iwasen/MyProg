<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥڡ��������ե饰�����ڡ���
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

// �ѥ�᡼������
$navi_page_id = $_GET['navi_page_id'];
$flag = $_GET['flag'];

// �ڡ����إå���������
$header = new header_class('�����ʹԴ���', BACK_TOP);

$sql = "UPDATE t_navi_page"
		. " SET nvp_open_flag=$flag"
		. " WHERE nvp_navi_page_id=$navi_page_id";
db_exec($sql);

switch ($flag) {
case 0:
	$msg = '�������ǽ�CHK��̤�������ᤷ�ޤ�����';
	break;
case 2:
	$msg = '�������ǽ�CHK�������ޤ�����';
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
