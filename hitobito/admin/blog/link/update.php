<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����ʼ��������ڡ���
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
include("$inc/database.php");
include("$inc/ngword.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('��󥯴���', BACK_TOP);

// �ǡ����������饹����
$data = new link_data_class();

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// DB����
$data->action_db($_POST['next_action']);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$data->edit_name?>����򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='check.php?link_id=<?=$_POST['link_id']?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
