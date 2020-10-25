<?php
/**
 * ひとびと･net バッチ処理
 *
 * ナビ転送メール処理
 * （qmailから呼び出される）
 *
 * @package
 * @author
 * @version
 */

$inc = dirname(__FILE__) . '/../inc';
include("$inc/common.php");
include("$inc/database.php");

// 転送ID取得
$forward_id = $_ENV['DEFAULT'];
if ($forward_id == '')
	forward_error();

// ナビメールアドレス取得
$sql = sprintf("SELECT mbr_mail_addr FROM t_navi JOIN t_member ON mbr_member_id=nav_member_id WHERE nav_forward_id='%s' AND nav_status=1 AND mbr_status=1 AND nav_forward_flag=1", $forward_id);
$mail_addr = db_fetch1($sql);
if ($mail_addr == '')
	forward_error();

// メール転送
forward_mail($mail_addr);

exit;

function forward_mail($to) {
	// メール転送
	exec("/home/vpopmail/bin/vdelivermail '' $to");
}

function forward_error() {
	$host = $_ENV['HOST'];
	if ($host != '') {
		// メール転送
		forward_mail("error@$host");
	}

	exit;
}
?>