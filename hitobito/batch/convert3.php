<?php
/**
 * �ҤȤӤȎ�net
 *
 * �ǡ����١�������С��Ƚ���
 *
 * @package
 * @author
 * @version
 */

// ����С��Ƚ���ʬ��
//===== �¹Ԥ������ˤ���������ꤷ�Ƥ�������=====
$mssql_user = 'hitobito_user';                // SQLServer���������̾
$mssql_pass = 'hitobito_pass';                // SQLServer�ѥ����
$_ENV['HITOBITO_DB_HOST'] = "localhost";      // PostgreSQL������IP���ɥ쥹
$test_domain = 'hitobito.xxxxx-xxxx.co.jp';   // �ƥ����ѥ᡼��ɥᥤ������֤Ϥ��ιԤϥ����ȥ����ȡ�
//===============================================

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/member_cd.php");

set_time_limit(600);

// SQLServer����³
$odbc_id = odbc_conxxxt('hitobito', $mssql_user, $mssql_pass);
if (!$odbc_id) {
	echo "SQLServer����³�Ǥ��ޤ���";
	exit;
}

db_begin_trans();

// �ǡ����Ѵ�
convert_member();
convert_waiwai_room();
convert_room_member();

db_commit_trans();

//db_exec("VACUUM ANALYZE");

exit;

// �����С�����
function convert_member() {
	global $new_member_id, $test_domain;

	$sql = "SELECT ���С��ֹ�,��������,�ǽ���������,�᡼�륢�ɥ쥹,�ѥ����,SEND"
			. " FROM dbo.���С�"
			. " LEFT JOIN dbo.T_MAIL ON MAILID=�᡼�륢�ɥ쥹 AND SEND='1'"
			. " ORDER BY ���С��ֹ�";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr_low=" . sql_char(strtolower($fetch->�᡼�륢�ɥ쥹));
		$new_member_id[$fetch->���С��ֹ�] = db_fetch1($sql);
	}
}

// ���磻�磻��ļ�����
function convert_waiwai_room() {
	global $new_room_id;

	$sql = "SELECT ��������,�ǽ���������,ML_N,GAIYOU,�ͣ�̾,�����å�����°��,����å�����°��,�إå��������ե饰,�եå��������ե饰,"
				. "��������Ͽ����,��������Ͽ���°��,�إå���,�եå���,�����å�����,����å�����,ML_ID"
			. " FROM dbo.�ͣ̾���"
			. " INNER JOIN dbo.K_ML ON ML_ID=�ͣ��ֹ�"
			. " WHERE CLOSED<>'3'"
			. " ORDER BY �ͣ��ֹ�";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT rom_room_id FROM t_room WHERE rom_name=" . sql_char(trim($fetch->ML_N));
		$new_room_id[$fetch->ML_ID] = db_fetch1($sql);
	}
}

// ���磻�磻��ĥ��С�����
function convert_room_member() {
	global $new_member_id, $new_room_id;

	$sql = "SELECT �ͣ��ֹ�,���С��ֹ�,�ۿ���ߥե饰"
			. " FROM dbo.�̡ͣ����С�"
			. " ORDER BY ���С��ֹ�";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		if ($new_room_id[$fetch->�ͣ��ֹ�] && $new_member_id[$fetch->���С��ֹ�]) {
			$mail_send_flag = $fetch->�ۿ���ߥե饰 == 0 ? 1 : 0;

			$sql = "UPDATE t_room_member SET rmm_mail_send_flag=$mail_send_flag WHERE rmm_room_id={$new_room_id[$fetch->�ͣ��ֹ�]} AND rmm_member_id={$new_member_id[$fetch->���С��ֹ�]} AND rmm_mail_send_flag<>$mail_send_flag";
			db_exec($sql);

			if (db_errormessage() != '')
				exit;
		}
	}
}

// ��������������
function set_sequence($table, $column) {
	$sql = "select setval('{$table}_{$column}_seq', coalesce(max($column)+1,1),false) from $table";
	db_exec($sql);
}

// ����������
function date_format($date, $default = '') {
	$date = trim($date);
	if ($date != '') {
		sscanf($date, '%d/%d/%d %d:%d:%d', &$year, &$month, &$day, &$hour, &$minute, &$second);

		if ($year < 100) {
			if ($year < 50)
				$year += 2000;
			else
				$year += 1900;
		}

		if ($month == 0)
			$month = 1;

		if ($day == 0)
			$day = 1;

		$date = sprintf('%d-%d-%d %d:%d:%d', $year, $month, $day, $hour, $minute, $second);
	} else
		$date = $default;

	return $date;
}

// ��͹���ֹ�����
function zip_format($zip) {
	return str_replace('-', '', trim($zip));
}

// ��SQLServer�ǥ�����¹�
function mssql_query($sql) {
	global $odbc_id;

	return odbc_exec($odbc_id, mb_convert_encoding($sql, 'SJIS', 'EUC-JP'));
}

// ��SQLServer����ե��å�
function mssql_fetch($result) {
	$fetch = odbc_fetch_object($result);
	if (!$fetch)
		return $fetch;

	foreach ($fetch as $key => $val) {
		$key = mb_convert_encoding($key, 'EUC-JP', 'SJIS');
		$fetch2->$key = mb_convert_encoding($val, 'EUC-JP', 'SJIS');
	}

	return $fetch2;
}
?>