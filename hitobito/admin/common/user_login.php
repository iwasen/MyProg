<?php
/**
 * ひとびと･net 共通ファイル
 *
 * ユーザページログイン
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

// パラメータ取得
$member_id = $_REQUEST['member_id'];
$jump_url = $_REQUEST['url'];

// ユーザログインページURL取得
$login_url = get_const('user_page_url') . "index.php?module=Member&action=LoginFromAdmin";

// メンバーパスワード取得
$sql = "SELECT mbr_password FROM t_member WHERE mbr_member_id=$member_id";
$password = db_fetch1($sql);

// Mojaviのセッションに保存
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
