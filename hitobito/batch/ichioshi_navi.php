<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * ���������ʥӽ��ץХå�����
 * �ʼ¹Լ����������ʬ��
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
include("$inc/ichioshi_navi.php");

// ����������
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// �ƥ�����
else
	define('CURRENT_TIME', time());

// �Хå��������饹����
$batch = new batch_class('ichioshi_navi');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$sql = "SELECT inu_update_mode, inu_update_time FROM t_ichioshi_navi_update";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->inu_update_mode == 2 && $fetch->inu_update_time == date('H', CURRENT_TIME)) {
			db_begin_trans();

			select_ichioshi_navi();

			// ���顼�����å�
			$err_msg = db_errormessage();
			if ($err_msg == '') {
				db_commit_trans();

				$batch->set_log(0, "�����Υ��������ʥӤ򹹿����ޤ���");
			} else {
				db_rollback();

				$batch->set_log(9, "�����Υ��������ʥӹ���������DB���顼��ȯ�����ޤ�����($err_msg)");
			}
		}
	}
}
?>