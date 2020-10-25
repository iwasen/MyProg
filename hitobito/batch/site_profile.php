<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * �����ȥץ�ե����뽸�ץХå�����
 * �ʼ¹Լ��������1��0��0ʬ��
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/date.php");
include("$inc/batch.php");

// ����������
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// �ƥ�����
else
	define('CURRENT_TIME', time());

// �Хå��������饹����
$batch = new batch_class('site_profile');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	db_begin_trans();

	// �о�ǯ�����
	$date = get_date_from_ut(CURRENT_TIME);
	$date['d'] = 1;
	$date_from = sql_date2($date);
	$date = add_month($date, 1);
	$date_to = sql_date2($date);

	// �ʥӿ�����
	$navi_num = get_navi_num();

	// ���С�������
	$member_num = get_member_num();

	// �ؤ���Ͽ�Կ�����
	$letter_member = get_letter_member();

	// Blog������
	$blog_num = get_blog_num();

	// �磻�磻��Ŀ�����
	$room_num = get_room_num();

	// �ʥӵ������������
	$article_num = get_article_num($date_from, $date_to);

	// ȯ���Ϳ��������
	$remark_num = get_remark_num($date_from, $date_to);

	// ȯ�����������
	$remark_count = get_remark_count($date_from, $date_to);

	// �᡼���ۿ�������
	$mail_num = get_mail_num($date_from, $date_to);

	// RAMΨ�������
	$room_member_num = get_room_member_num();
	$ram_rate = $room_member_num ? round($remark_num / $room_member_num * 100) : 0;

	// �ڡ����ӥ塼������
	$page_view = get_page_view($date_from, $date_to);

	// ˬ��Կ�����
	$visitor_num = get_visitor_num($date_from, $date_to);

	// ����å�������
	$click_num = get_click_num($date_from, $date_to);

	// �ȥ�å��Хå�������
	$trackback_num = get_trackback_num($date_from, $date_to);

	// �����ȿ�����
	$comment_num = get_comment_num($date_from, $date_to);

	// ����ͥ��̥ʥӥڡ���������
	$navi_page_num_ary = get_navi_page_num($date_from, $date_to);

	// �����ȥץ�ե������DB�˽񤭹���
	$rec['stp_navi_num'] = sql_number($navi_num);
	$rec['stp_member_num'] = sql_number($member_num);
	$rec['stp_letter_member'] = sql_number($letter_member);
	$rec['stp_blog_num'] = sql_number($blog_num);
	$rec['stp_room_num'] = sql_number($room_num);
	$rec['stp_article_num'] = sql_number($article_num);
	$rec['stp_remark_num'] = sql_number($remark_num);
	$rec['stp_remark_count'] = sql_number($remark_count);
	$rec['stp_mail_num'] = sql_number($mail_num);
	$rec['stp_ram_rate'] = sql_number($ram_rate);
	$rec['stp_page_view'] = sql_number($page_view);
	$rec['stp_visitor_num'] = sql_number($visitor_num);
	$rec['stp_click_num'] = sql_number($click_num);
	$rec['stp_trackback_num'] = sql_number($trackback_num);
	$rec['stp_comment_num'] = sql_number($comment_num);
	db_insert_update('t_site_profile', $rec, 'stp_date', $date_from);

	// ����ͥ��̥ʥӥڡ�������DB�˽񤭹���
	db_delete('t_site_navi_page', "snp_date=$date_from");
	foreach ($navi_page_num_ary as $channel_id => $navi_page_num) {
		$rec['snp_date'] = $date_from;
		$rec['snp_channel_id'] = sql_number($channel_id);
		$rec['snp_navi_page_num'] = sql_number($navi_page_num);
		db_insert('t_site_navi_page', $rec);
	}

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();

		$batch->set_log(0, "�����ȥץ�ե�����({$date['y']}/{$date['m']})�򽸷פ��ޤ���");
	} else {
		db_rollback();

		$batch->set_log(9, "�����ȥץ�ե����뽸�׽�����DB���顼��ȯ�����ޤ�����($err_msg)");
	}
}

// ���ʥӿ�����
function get_navi_num() {
	$sql = "SELECT COUNT(*) FROM t_navi WHERE nav_status=1";
	return db_fetch1($sql);
}

// �����С�������
function get_member_num() {
	$sql = "SELECT COUNT(*) FROM t_member WHERE mbr_status=1";
	return db_fetch1($sql);
}

// ���ؤ���Ͽ�Կ�����
function get_letter_member() {
	$sql = "SELECT COUNT(*) FROM t_member WHERE mbr_status=1 AND mbr_hitobito_letter=1";
	return db_fetch1($sql);
}

// ��Blog������
function get_blog_num() {
	$sql = "SELECT COUNT(*) FROM t_blog WHERE blg_status=1";
	return db_fetch1($sql);
}

// ���磻�磻��Ŀ�����
function get_room_num() {
	$sql = "SELECT COUNT(*) FROM t_room WHERE rom_status IN (1,2)";
	return db_fetch1($sql);
}

// ���ʥӵ������������
function get_article_num($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM t_blog_article WHERE bla_date>=$date_from AND bla_date<$date_to";
	return db_fetch1($sql);
}

// ��ȯ���Ϳ��������
function get_remark_num($date_from, $date_to) {
	$sql = "SELECT COUNT(DISTINCT rmk_member_id) FROM l_room_remark WHERE rmk_date>=$date_from AND rmk_date<$date_to";
	return db_fetch1($sql);
}

// ��ȯ�����������
function get_remark_count($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_room_remark WHERE rmk_date>=$date_from AND rmk_date<$date_to";
	return db_fetch1($sql);
}

// ���᡼���ۿ�������
function get_mail_num($date_from, $date_to) {
	// �ҤȤӤ��ؤ��ۿ���
	$sql = "SELECT SUM(hbl_send_num) FROM t_hitobito_letter WHERE hbl_status=4 AND hbl_send_date>=$date_from AND hbl_send_date<$date_to";
	$letter = db_fetch1($sql);

	// ���ޥ��ۿ���
	$sql = "SELECT SUM(mmc_send_num) FROM t_melmaga_contents WHERE mmc_status=2 AND mmc_send_date>=$date_from AND mmc_send_date<$date_to";
	$melmaga = db_fetch1($sql);

	// �磻�磻����ۿ���
	$sql = "SELECT SUM(rmk_send_num) FROM l_room_remark WHERE rmk_send_flag=true AND rmk_date>=$date_from AND rmk_date<$date_to";
	$room = db_fetch1($sql);

	return $letter + $melmaga + $room;
}

// ���磻�磻��Ŀ���Ͽ�Կ�
function get_room_member_num() {
	$sql = "SELECT COUNT(*) FROM t_room_member WHERE rmm_status=3";
	return db_fetch1($sql);
}

// ���ڡ����ӥ塼��
function get_page_view($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_page_view WHERE pvl_date>=$date_from AND pvl_date<$date_to";
	return db_fetch1($sql);
}

// ��ˬ��Կ���
function get_visitor_num($date_from, $date_to) {
	$sql = "SELECT COUNT(DISTINCT pvl_ip_addr) FROM l_page_view WHERE pvl_date>=$date_from AND pvl_date<$date_to";
	return db_fetch1($sql);
}

// ������å�������
function get_click_num($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_banner_click WHERE bnl_date>=$date_from AND bnl_date<$date_to";
	return db_fetch1($sql);
}

// ���ȥ�å��Хå�������
function get_trackback_num($date_from, $date_to) {
	// blog�ȥ�å��Хå���
	$sql = "SELECT COUNT(*) FROM t_blog_trackback WHERE blt_date>=$date_from AND blt_date<$date_to";
	$blog = db_fetch1($sql);

	// ���󥱡��ȥȥ�å��Хå���
	$sql = "SELECT COUNT(*) FROM l_navi_enq_trackback WHERE net_date>=$date_from AND net_date<$date_to";
	$enquete = db_fetch1($sql);

	return $blog + $enquete;
}

// �������ȿ�����
function get_comment_num($date_from, $date_to) {
	// blog�����ȿ�
	$sql = "SELECT COUNT(*) FROM t_blog_comment WHERE blo_date>=$date_from AND blo_date<$date_to";
	$blog = db_fetch1($sql);

	// ���󥱡��ȥ����ȿ�
	$sql = "SELECT COUNT(*) FROM l_navi_enq_comment WHERE neo_date>=$date_from AND neo_date<$date_to";
	$enquete = db_fetch1($sql);

	// ���ޥ������ȿ�
	$sql = "SELECT COUNT(*) FROM l_melmaga_comment WHERE mmo_date>=$date_from AND mmo_date<$date_to";
	$melmaga = db_fetch1($sql);

	return $blog + $enquete + $melmaga;
}

// ������ͥ��̥ʥӥڡ���������
function get_navi_page_num() {
	$ary = array();

	$sql = "SELECT nvp_channel_id, COUNT(*) AS navi_page_num FROM t_navi_page WHERE nvp_status=1 AND nvp_open_flag=1 GROUP BY nvp_channel_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_channel_id] = $fetch->navi_page_num;
	}

	return $ary;
}
?>