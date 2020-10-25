<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ��Ͽ���ɥ쥹�ʳ�����ƴ����ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/remark.php");
include("$inc/const.php");

// ������᡼������
function send_regist_mail($room_id, $member_id) {
	$sql = "SELECT rom_regist_mail_flag FROM t_room WHERE rom_room_id=$room_id";
	if (!db_fetch1($sql))
		return;

	// �᡼��ƥ�ץ졼�ȼ���
	get_mail_template('room_regist', $subject, $from, $cc, $bcc, $repty_to, $body);

	// ����ԥ᡼�����
	$sql = "SELECT rom_regist_mail FROM t_room WHERE rom_room_id=$room_id";
	$regist_mail = db_fetch1($sql);

	// ���������ѿ�����
	$var_ary = get_room_var($room_id);
	$body = replace_room_var($var_ary, $regist_mail != '' ? $regist_mail : $body);

	// �᡼�륢�ɥ쥹����
	$sql = "SELECT mbr_mail_addr FROM t_member WHERE mbr_member_id=$member_id";
	$to = db_fetch1($sql);

	// ����᡼������
	send_mail_one($subject, $from, $to, $reply_to, $body, $cc, $bcc);
}

$flag = $_POST['flag'];
$reg_kari = $_POST['reg_kari'];
$reg_guest = $_POST['reg_guest'];

db_begin_trans();

switch($flag) {
case 'kari':
	if (!$reg_kari)
		break;

	foreach($reg_kari as $data => $check) {
		list($room_id, $member_id) = explode(':', $data);

		$sql = sprintf("UPDATE t_room_member SET rmm_status=CASE rmm_status WHEN 1 THEN 2 END, rmm_guest_date='now' WHERE rmm_room_id=%s AND rmm_member_id=%s"
			, sql_number($room_id)
			, sql_number($member_id));
		db_exec($sql);
	}
	break;
case 'guest':
	if (!$reg_guest)
		break;

	foreach($reg_guest as $data => $check) {
		list($room_id, $member_id) = explode(':', $data);

		$sql = sprintf("UPDATE t_room_member SET rmm_status=CASE rmm_status WHEN 2 THEN 3 END, rmm_regist_date='now' WHERE rmm_room_id=%s AND rmm_member_id=%s"
			, sql_number($room_id)
			, sql_number($member_id));
		db_exec($sql);

		send_regist_mail($room_id, $member_id);
	}
	break;
}

db_commit_trans();

redirect('list.php');

?>