<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ȴ���������ͥ빹���ڡ���
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
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('����ͥ빹��', BACK_TOP);

// �ǡ����������饹����
$data = new channel_data_class();

// DB����ǡ����ɤ߹���
if ($_POST['channel_id'])
	$data->read_db($_POST['channel_id']);

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

<p class="msg">����ͥ��<?=$data->action?>���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
