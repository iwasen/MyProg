<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����Υ��������ʥӹ����ڡ���
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
include("$inc/ichioshi_navi.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�����Υ��������ʥӹ���', BACK_TOP);

// �ǡ����������饹����
$data = new recommend_data_class();

// DB����ǡ����ɤ߹���
$data->read_db($_POST['channel_id']);

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// DB����
$data->update_db();

// ���������ʥ�����
select_ichioshi_navi($data->channel_id);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">�����Υ��������ʥӤ򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='<?=$top?>/top.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
