<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ����������ڡ���
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

// �إå���������
$header = new header_class('�ѥȥ����������', BACK_TOP);

$sql = "SELECT ptl_patrol_id FROM m_patrol WHERE ptl_mail_addr='{$_SESSION['login_mail_addr']}' AND ptl_status=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$_SESSION['patrol_login_id'] = $fetch->ptl_patrol_id;
	$_SESSION['login_type'] = 9;

	redirect("$top/../patrol/top.php");
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>

<br>
�ѥȥ�����ޥ����� <?=$_SESSION['login_mail_addr']?> ����Ͽ����Ƥ��ޤ���
<br>
<br>
<input type="button" value="����롡" onclick="history.back()">

<?php $header->page_footer(); ?>
</body>
</html>
