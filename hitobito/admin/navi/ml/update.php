<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥ�ML��Ͽ���󹹿��ڡ���
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
$header = new header_class('�磻�磻��ļ���ML�˴���', BACK_TOP);

// �ǡ����������饹����
$data = new ml_data_class();

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

<p class="msg">�磻�磻��ļ������<?=$data->action?>���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$_POST['ret'] ? "location.href='{$_POST['ret']}'" : 'history.go(-2)'?>"></p>

<?php $header->page_footer(); ?>

</body>
</html>
