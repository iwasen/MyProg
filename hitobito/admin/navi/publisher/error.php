<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ������ڡ���
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

// �ڡ����إå���������
$header = new header_class('�ɼԸ���/�������', BACK_TOP);

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>
<br>
<div align="center">
	����������᡼�륢�ɥ쥹������ޤ���
	<br><br>
	<input type="button" value="����롡" onclick="history.back()">
</div>
<br>
<?php $header->page_footer(); ?>

</body>
</html>
