<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ǿ�����������ƹ����ڡ���
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
$header = new header_class('�ǿ�����������ƹ���', BACK_TOP);

// �ǡ����������饹����
$data = new new_kuchikomi_data_class();

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// DB����
$data->update_db();
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">�ǿ�����������Ƥ򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='edit.php?channel_id=<?=$_POST['channel_id']?>'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
