<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ�᡼��ʸ�����ڡ���
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
$header = new header_class('�ҤȤӤ��ؤ�᡼��ʸ����', BACK_TOP);

// �ǡ����������饹����
$data = new letter_data_class();

// DB����ǡ����ɤ߹���
if ($_POST['letter_id'])
	$data->read_db($_POST['letter_id']);

// �ե����फ��ǡ����ɤ߹���
$data->read_form_mail();

// DB����
$data->update_db();
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">�ҤȤӤ��ؤΥ᡼��ʸ�򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='edit.php?letter_id=<?=$_POST['letter_id']?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
