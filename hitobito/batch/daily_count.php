<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * ���̥ǡ������ץХå�����
 * �ʼ¹Լ���������0��0ʬ��
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
$batch = new batch_class('daily_count');

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

	// ���̽���
	daily_count($date);

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();

		$batch->set_log(0, "���̽��׽�����¹Ԥ��ޤ���");
	} else {
		db_rollback();

		$batch->set_log(9, "���̽��׽�����DB���顼��ȯ�����ޤ�����($err_msg)");
	}
}

// �����̥ǡ�������
function daily_count($date) {
	$count_ary = array();

	// �ʥӥڡ�����ML���С������ҤȤӤ��ؤ���Ͽ��
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS member_num, COUNT(CASE WHEN mbr_hitobito_letter=1 THEN 1 END) AS letter_num"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN t_room_member ON rmm_room_id=nvp_room_id"
			. " JOIN t_member ON mbr_member_id=rmm_member_id"
			. " WHERE nvp_status=1 AND nvp_open_flag=1 AND rom_status IN (1,2) AND rmm_status=3 AND mbr_status<>9"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count_ary[$fetch->nvp_navi_page_id]->member_num = $fetch->member_num;
		$count_ary[$fetch->nvp_navi_page_id]->letter_num = $fetch->letter_num;
	}

	// ���С������ҤȤӤ��ؤ���Ͽ��
	$sql = "SELECT COUNT(*) AS member_num, COUNT(CASE WHEN mbr_hitobito_letter=1 THEN 1 END) AS letter_num"
			. " FROM t_member"
			. " WHERE mbr_status<>9";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$count_ary[0]->member_num = $fetch->member_num;
	$count_ary[0]->letter_num = $fetch->letter_num;

	// ML��
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS ml_num"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " WHERE nvp_status=1 AND nvp_open_flag=1 AND rom_status IN (1,2)"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$total = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count_ary[$fetch->nvp_navi_page_id]->ml_num = $fetch->ml_num;
		$total += $fetch->ml_num;
	}
	$count_ary[0]->ml_num = $total;

	// Blog��
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS blog_num"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " WHERE nvp_status=1 AND nvp_open_flag=1 AND blg_status=1"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$total = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count_ary[$fetch->nvp_navi_page_id]->blog_num = $fetch->blog_num;
		$total += $fetch->blog_num;
	}
	$count_ary[0]->blog_num = $total;

	// Blog������
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS article_num"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " JOIN t_blog_article ON bla_blog_id=nvp_blog_id"
			. " WHERE nvp_status=1 AND nvp_open_flag=1 AND blg_status=1 AND bla_status=1"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$total = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count_ary[$fetch->nvp_navi_page_id]->article_num = $fetch->article_num;
		$total += $fetch->article_num;
	}
	$count_ary[0]->article_num = $total;

	// ���ޥ��ɼԿ�
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS reader_num"
			. " FROM t_navi_page"
			. " JOIN t_melmaga ON mmg_melmaga_id=nvp_melmaga_id"
			. " JOIN t_melmaga_member ON mmm_melmaga_id=nvp_melmaga_id"
			. " WHERE nvp_status=1 AND nvp_open_flag=1 AND mmg_status=1"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$total = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count_ary[$fetch->nvp_navi_page_id]->reader_num = $fetch->reader_num;
		$total += $fetch->reader_num;
	}
	$count_ary[0]->reader_num = $total;

	db_delete('t_daily_count', "day_date=" . sql_date2($date));

	foreach ($count_ary as $navi_page_id => $count) {
		$rec['day_date'] = sql_date2($date);
		$rec['day_navi_page_id'] = sql_number($navi_page_id);
		$rec['day_member_num'] = sql_number((int)$count->member_num);
		$rec['day_letter_num'] = sql_number((int)$count->letter_num);
		$rec['day_ml_num'] = sql_number((int)$count->ml_num);
		$rec['day_blog_num'] = sql_number((int)$count->blog_num);
		$rec['day_article_num'] = sql_number((int)$count->article_num);
		$rec['day_reader_num'] = sql_number((int)$count->reader_num);
		db_insert('t_daily_count', $rec);
	}
}
?>