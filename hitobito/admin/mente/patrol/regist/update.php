<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ�����ޥ��������ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/disp_error.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�ѥȥ�����ޥ�������', BACK_TOP);

// �ǡ����������饹����
$data = new patrol_data_class();

// �ե����फ��ǡ����ɤ߹���
$data->read_form();

// �ǡ��������å�
if ($msg = $data->check_data($_POST['next_action']))
	disp_error($header, $msg);

// DB����
$data->action_db($_POST['next_action']);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">�ѥȥ������<?=$data->action?>���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
