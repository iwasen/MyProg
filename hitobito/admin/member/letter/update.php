<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ��ۿ�����ֹ����ڡ���
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
$header = new header_class('�ҤȤӤ��ؤ��ۿ�����ֹ���', BACK_TOP);

// �ǡ����������饹����
$data = new letter_data_class();

// DB����ǡ����ɤ߹���
if ($_POST['letter_id'])
	$data->read_db($_POST['letter_id']);

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// DB����
$data->action_db($_POST['next_action']);

if ($_POST['next_action'] == 'new')
	$href = "edit.php?letter_id=$data->letter_id";
else
	$href = 'list.php';
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">�ҤȤӤ��ؤ��<?=$data->action?>���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='<?=$href?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
