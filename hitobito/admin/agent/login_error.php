<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����󥨥顼�ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
include("$inc/header.php");
include("$inc/common.php");

// �ڡ����إå���������
$header = new header_class('�ҤȤӤȡ�net����������ȥ�����', BACK_NONE);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<div align="center">
<p class="msg">������˼��Ԥ��ޤ�����<br><br>���ܥ���򥯥�å����Ƥ⤦���٣ɣĤȥѥ���ɤ����Ϥ��Ƥ���������</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
</div>

<?php $header->page_footer(); ?>

</body>
</html>
