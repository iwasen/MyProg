<?php
/**
 * �ЂƂтƥnet �o�b�`����
 *
 * �i�r�]�����[������
 * �iqmail����Ăяo�����j
 *
 * @package
 * @author
 * @version
 */

$inc = dirname(__FILE__) . '/../inc';
include("$inc/common.php");
include("$inc/database.php");

// �]��ID�擾
$forward_id = $_ENV['DEFAULT'];
if ($forward_id == '')
	forward_error();

// �i�r���[���A�h���X�擾
$sql = sprintf("SELECT mbr_mail_addr FROM t_navi JOIN t_member ON mbr_member_id=nav_member_id WHERE nav_forward_id='%s' AND nav_status=1 AND mbr_status=1 AND nav_forward_flag=1", $forward_id);
$mail_addr = db_fetch1($sql);
if ($mail_addr == '')
	forward_error();

// ���[���]��
forward_mail($mail_addr);

exit;

function forward_mail($to) {
	// ���[���]��
	exec("/home/vpopmail/bin/vdelivermail '' $to");
}

function forward_error() {
	$host = $_ENV['HOST'];
	if ($host != '') {
		// ���[���]��
		forward_mail("error@$host");
	}

	exit;
}
?>