<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥ���Ͽ���󹹿��ڡ���
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
$header = new header_class('�ʥ���Ͽ�����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new navi_data_class();

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// DB����
$data->action_db($_POST['next_action']);

if ($data->err) {
	$msg = '�ʥӥ��ɥ쥹[' . $data->forward_id . ']�ϡ�������Ͽ����Ƥ��ޤ���';
	$link = "history.back();";
} else {
	$msg = '�ʥ���Ͽ�����' . $data->action . '���ޤ�����';
	$link = "location.href='list.php'";
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$link?>"></p>

<?php $header->page_footer(); ?>

</body>
</html>
