<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �桼���ڡ���������
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/const.php");

// �ѥ�᡼������
$member_id = $_REQUEST['member_id'];
$jump_url = $_REQUEST['url'];

// �桼��������ڡ���URL����
$login_url = get_const('user_page_url') . "index.php?module=Member&action=LoginFromAdmin";

// ���С��ѥ���ɼ���
$sql = "SELECT mbr_password FROM t_member WHERE mbr_member_id=$member_id";
$password = db_fetch1($sql);

// Mojavi�Υ��å�������¸
session_name('Mojavi');
session_start();
$_SESSION['org/mojavi/user/User/attributes']['org/mojavi']['admin_login_password'] = $password;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$login_url?>">
<input type="hidden" name="member_login_id" <?=value($member_id)?>>
<input type="hidden" name="member_login_pass" <?=value($password)?>>
<input type="hidden" name="redirect_url" <?=value($jump_url)?>>
</form>
</body>
</html>
