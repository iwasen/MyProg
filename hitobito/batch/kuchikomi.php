<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * �ǿ��������߾�������Хå�����
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

//===== ��������WEB��DB���� =====
define('KCOM_DB', 'kcom');						// SQLServer�ǡ����١���̾
define('KCOM_USER', 'kcom');					// SQLServer���������̾
define('KCOM_PASS', '2002takasusanganbare');	// SQLServer�ѥ����
//===============================

// �Хå��������饹����
$batch = new batch_class('kuchikomi');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$odbc_id = odbc_conxxxt(KCOM_DB, KCOM_USER, KCOM_PASS);
	if (!$odbc_id) {
		$batch->set_log(9, "��������WEB�Σģ¥����Ф���³�Ǥ��ޤ���");
		return;
	}

	db_begin_trans();

	// �������ߥ��ƥ���ޥ�������
	get_category_master($odbc_id);

	//  �ǿ��������߾������
	get_new_kuchikomi($odbc_id);

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();

		$batch->set_log(0, "�ǿ��������߾����������ޤ�����");
	} else {
		db_rollback();

		$batch->set_log(9, "�ǿ��������߾���μ���������DB���顼��ȯ�����ޤ�����($err_msg)");
		break;
	}
}

// ���������ߥ��ƥ���ޥ�������
function get_category_master($odbc_id) {
	$sql = "SELECT * FROM m_category";
	$result = odbc_exec($odbc_id, $sql);
	while ($fetch = odbc_fetch_object($result)) {
		$sql = "SELECT COUNT(*) FROM m_kcom_category WHERE kcg_l1=$fetch->cg_l1 AND kcg_l2=$fetch->cg_l2 AND kcg_l3=$fetch->cg_l3 AND kcg_l4=$fetch->cg_l4 AND kcg_l5=$fetch->cg_l5";
		if (db_fetch1($sql) == 0) {
			$rec['kcg_l1'] = sql_number($fetch->cg_l1);
			$rec['kcg_l2'] = sql_number($fetch->cg_l2);
			$rec['kcg_l3'] = sql_number($fetch->cg_l3);
			$rec['kcg_l4'] = sql_number($fetch->cg_l4);
			$rec['kcg_l5'] = sql_number($fetch->cg_l5);
			$rec['kcg_status'] = sql_number($fetch->cg_status);
			$rec['kcg_name'] = sql_char($fetch->cg_name);
			db_insert('m_kcom_category', $rec);
		} else {
			$rec['kcg_status'] = sql_number($fetch->cg_status);
			$rec['kcg_name'] = sql_char($fetch->cg_name);
			db_update('m_kcom_category', $rec, "kcg_l1=$fetch->cg_l1 AND kcg_l2=$fetch->cg_l2 AND kcg_l3=$fetch->cg_l3 AND kcg_l4=$fetch->cg_l4 AND kcg_l5=$fetch->cg_l5 AND (kcg_status<>$fetch->cg_status OR kcg_name<>" . sql_char($fetch->cg_name) . ")");
		}
	}
}

// ���ǿ��������߾������
function get_new_kuchikomi($odbc_id) {
	$sql = "SELECT chn_channel_id FROM m_channel";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$channel_id = $fetch->chn_channel_id;

		db_delete('t_kuchikomi_channel', "kch_channel_id=$channel_id");

		$kc_ary = array();

		$sql = "SELECT chk_channel_id,chk_l1,chk_l2,chk_l3,chk_l4,chk_l5 FROM m_channel_kcom WHERE chk_channel_id=$channel_id";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch = pg_fetch_object($result2, $j);

			$tmp_ary = array();
			$tmp_ary[] = "cr_l1=$fetch->chk_l1";
			$tmp_ary[] = "cr_l2=$fetch->chk_l2";
			$tmp_ary[] = "cr_l3=$fetch->chk_l3";
			$tmp_ary[] = "cr_l4=$fetch->chk_l4";
			$tmp_ary[] = "cr_l5=$fetch->chk_l5";

			$kc_ary[] = join(' AND ', $tmp_ary);
		}

		if (count($kc_ary) > 0) {
			$kc_cond = join(' OR ', $kc_ary);

			$sql = "SELECT TOP 20 rp_report_id,rp_gazou_fname,rp_gazou_status,rp_body,rp_shouhin,mm_nickname,rp_regist_date,rp_title"
				. " FROM t_report"
				. " JOIN m_member ON mm_member_id=rp_member_id"
				. " WHERE rp_status=0 AND rp_open=1 AND rp_report_id IN (SELECT cr_report_id FROM t_cat_rep_comp WHERE ($kc_cond) AND cr_status=0)"
				. " ORDER BY rp_regist_date DESC";
			$result3 = odbc_exec($odbc_id, $sql);
			while ($fetch = odbc_fetch_object($result3)) {
				$rec['kch_channel_id'] = sql_number($channel_id);
				$rec['kch_kuchikomi_id'] = sql_number($fetch->rp_report_id);
				db_insert('t_kuchikomi_channel', $rec);

				$sql = "SELECT COUNT(*) FROM t_kuchikomi WHERE kck_kuchikomi_id=$fetch->rp_report_id";
				if (db_fetch1($sql) == 0) {
					$rec['kck_kuchikomi_id'] = sql_number($fetch->rp_report_id);
					$rec['kck_image_url'] = sql_char($fetch->rp_gazou_fname != '' ? "http://www.kuchi-komi.net/monitor/rp_img/$fetch->rp_gazou_fname" : "");
					$rec['kck_content'] = sql_char(mb_strimwidth(char_conv($fetch->rp_body), 0, 66, '��'));
					$rec['kck_page_url'] = sql_char("http://www.kuchi-komi.net/monitor/report/rp_dt.asp?id=$fetch->rp_report_id");
					$rec['kck_shouhin'] = sql_char(char_conv($fetch->rp_shouhin));
					$rec['kck_nickname'] = sql_char(char_conv($fetch->mm_nickname));
					$rec['kck_regist_date'] = sql_date($fetch->rp_regist_date);
					$rec['kck_title'] = sql_char(char_conv($fetch->rp_title));
					db_insert('t_kuchikomi', $rec);
				}
			}
		}

	}

	db_delete('t_kuchikomi', "kck_kuchikomi_id NOT IN (SELECT kch_kuchikomi_id FROM t_kuchikomi_channel)");
}

// ��ʸ�������к�
function char_conv($str) {
	return str_replace(sprintf('%c%c%c', 0x8f, 0xa2, 0xb7), '��', $str);
}
?>