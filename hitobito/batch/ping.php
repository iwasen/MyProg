<?php
/**
 * �ҤȤӤȎ�net
 *
 * ping��������
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/const.php");
include("$inc/blog_send_ping.php");

// �Хå��������饹����
$batch = new batch_class('ping_send');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$user_url = get_const('user_page_url') . 'index.php?module=Blog&action=Index&navipage_id=';

	// ping������URL����
	$sql = 'SELECT png_url FROM m_ping WHERE png_status=1';
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$ping_url[] = $fetch->png_url;
	}

	if (count($ping_url) > 0) {
		$err_num = 0;
		$normal_num = 0;

		// ̤������������
		$sql = 'SELECT bla_article_id, blg_title, nvp_navi_page_id FROM t_blog_article LEFT JOIN t_blog ON bla_blog_id=blg_blog_id LEFT JOIN t_navi_page ON blg_blog_id=nvp_blog_id'
		. ' WHERE bla_ping_flag IS NULL AND bla_date<=now() AND bla_status=1 AND bla_open_flag=1';
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$article_id = $fetch->bla_article_id;
			$blog_url = $user_url . $fetch->nvp_navi_page_id;
			$blog_name = mb_convert_encoding($fetch->blg_title, 'UTF-8');

			// ping����
			foreach ($ping_url as $url) {
				$ping = new blog_send_ping($blog_name, $blog_url);
				$ping->send_ping($url);
				if ($ping->get_response_error())
					$err_num++;
				else
					$normal_num++;
			}

			// ping�����ե饰����
			$sql = "UPDATE t_blog_article SET bla_ping_flag=1 WHERE bla_article_id=$article_id";
			db_exec($sql);

		}

		$batch->set_log(0, "ping������¹Ԥ��ޤ����������{$normal_num}����顼��{$err_num}���");

	}

}

?>