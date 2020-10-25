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

exit;	// �ְ�äƼ¹Ԥ��ʤ�����ʤ⤷�¹Ԥ���ɬ�פ�������ϥ����ȥ����Ȥ����
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
//error_reporting(E_ALL);

// SQLServer����³
$odbc_id = odbc_conxxxt('hitobito', $mssql_user, $mssql_pass);
if (!$odbc_id) {
	echo "SQLServer����³�Ǥ��ޤ���";
	exit;
}

// ��¸�Υ쥳���ɺ��
truncate_table('m_channel');
truncate_table('m_sub_channel');
truncate_table('t_kuchikomi_channel');
truncate_table('t_kuchikomi');
/*
truncate_table('t_member');
truncate_table('t_navi');
truncate_table('t_room');
truncate_table('t_navi_page');
truncate_table('t_blog');
truncate_table('t_melmaga');
truncate_table('t_room_member');
*/
db_begin_trans();

// �ǡ����Ѵ�
convert_channel_master();
convert_member();
convert_navi();
convert_waiwai_room();
convert_navi_page();
convert_room_member();

db_commit_trans();

db_exec("VACUUM ANALYZE");

exit;

// ������ͥ�ޥ���
function convert_channel_master() {
	global $new_channel_id, $new_sub_channel_id;

	// ����ͥ�ޥ���
	$channel_id = 0;
	$sql = "SELECT CHANNEL_ID,CHANNEL_N,KBUN,SEQ"
			. " FROM dbo.M_CHANNEL1"
			. " ORDER BY SEQ";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$rec['chn_channel_id'] = sql_number(++$channel_id);
		$rec['chn_status'] = sql_number($fetch->KBUN == '1' ? 1 : 9);
		$rec['chn_channel_name'] = sql_char(trim($fetch->CHANNEL_N));
		$rec['chn_order'] = sql_number($fetch->SEQ);
		db_insert('m_channel', $rec);

		$new_channel_id[$fetch->CHANNEL_ID] = $channel_id;
	}

	// ���֥���ͥ�ޥ���
	$sub_channel_id = 0;
	$sql = "SELECT CHANNEL_ID,CHANNEL_SID,CHANNEL_SN,KBUN,SEQ"
			. " FROM dbo.M_CHANNELSUB1"
			. " WHERE KBUN='1'"
			. " ORDER BY CHANNEL_ID,SEQ";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$rec['sch_subchannel_id'] = sql_number(++$sub_channel_id);
		$rec['sch_channel_id'] = sql_number($new_channel_id[$fetch->CHANNEL_ID]);
		$rec['sch_status'] = sql_number($fetch->KBUN == '1' ? 1 : 9);
		$rec['sch_subchannel_name'] = sql_char(trim($fetch->CHANNEL_SN));
		$rec['sch_order'] = sql_number($fetch->SEQ);
		db_insert('m_sub_channel', $rec);

		if (db_errormessage() != '')
			exit;

		$new_sub_channel_id[$fetch->CHANNEL_ID][$fetch->CHANNEL_SID] = $sub_channel_id;
	}
}

// �����С�����
function convert_member() {
	global $new_member_id, $test_domain;

	$sql = "SELECT ���С��ֹ�,��������,�ǽ���������,�᡼�륢�ɥ쥹,�ѥ����,SEND"
			. " FROM dbo.���С�"
			. " LEFT JOIN dbo.T_MAIL ON MAILID=�᡼�륢�ɥ쥹 AND SEND='1'"
			. " ORDER BY ���С��ֹ�";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$member_id = get_next_seq('t_member', 'mbr_member_id');
		$new_member_id[$fetch->���С��ֹ�] = $member_id;

		if (isset($test_domain))
			$mail_addr = sprintf("member-%05d@$test_domain", $member_id);
		else
			$mail_addr = trim($fetch->�᡼�륢�ɥ쥹);

		$rec['mbr_member_id'] = sql_number($member_id);
		$rec['mbr_status'] = sql_number(1);
		$rec['mbr_regist_date'] = sql_date(date_format($fetch->��������, 'now'));
		$rec['mbr_update_date'] = sql_date(date_format($fetch->�ǽ���������));
		$rec['mbr_mail_addr'] = sql_char($mail_addr);
		$rec['mbr_mail_addr_low'] = sql_char(strtolower($mail_addr));
		$rec['mbr_password'] = sql_char(md5($fetch->�ѥ����));
		$rec['mbr_hitobito_letter'] = sql_number($fetch->SEND == '1' ? 1 : 0);
		db_insert('t_member', $rec);

		if (db_errormessage() != '')
			exit;
	}

	set_sequence('t_member', 'mbr_member_id');
}

// ���ʥӾ���
function convert_navi() {
	global $new_member_id, $new_navi_id;

	// ��ƻ�ܸ��ޥ�������
	$sql = "SELECT tdf_area_id,tdf_name FROM m_todofuken";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$todofuken[$fetch->tdf_name] = $fetch->tdf_area_id;
		$todofuken[mb_substr($fetch->tdf_name, 0, mb_strlen($fetch->tdf_name) - 1)] = $fetch->tdf_area_id;
	}

	// ���ȥޥ�������
	$sql = "SELECT skg_shokugyo_id,skg_shokugyo_name FROM m_shokugyo";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$todofuken[$fetch->skg_shokugyo_name] = $fetch->skg_shokugyo_id;
	}

	$sql = "SELECT NAVI_ID,���С��ֹ�,��������,�ǽ���������,Name,��ǯ����,����,͹���ֹ�,���������ֹ�,�ƣ����ֹ�,��̳�������ֹ�,���꣱,���ꣲ,���ꣳ,���̾,����"
			. " FROM dbo.v_Navi"
			. " INNER JOIN dbo.���С� ON ���С��ֹ�=NAVI_ID"
			. " ORDER BY (CASE WHEN NAVI_ID=305 THEN 0 ELSE 1 END),NAVI_ID";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		preg_match('/ *([^ ]+) *([^ ]*) *$/', str_replace('��', ' ', $fetch->Name), $name);

		$navi_id = get_next_seq('t_navi', 'nav_navi_id');
		$new_navi_id[$fetch->NAVI_ID] = $navi_id;

		$rec['nav_navi_id'] = sql_number($navi_id);
		$rec['nav_member_id'] = sql_number($new_member_id[$fetch->���С��ֹ�]);
		$rec['nav_status'] = sql_number(1);
		$rec['nav_regist_date'] = sql_date(date_format($fetch->��������, 'now'));
		$rec['nav_update_date'] = sql_date(date_format($fetch->�ǽ���������));
		$rec['nav_name1'] = sql_char($name[1]);
		$rec['nav_name2'] = sql_char($name[2]);
		$rec['nav_birthday'] = sql_date(date_format($fetch->��ǯ����));
		$rec['nav_sex_cd'] = sql_number($fetch->���� == 0 ? 1 : 2);
		$rec['nav_zip_cd'] = sql_char(zip_format($fetch->͹���ֹ�));
		$rec['nav_jitaku_tel'] = sql_char($fetch->���������ֹ�);
		$rec['nav_jitaku_fax'] = sql_char($fetch->�ƣ����ֹ�);
		$rec['nav_kinmu_tel'] = sql_char($fetch->��̳�������ֹ�);
		$rec['nav_address1'] = sql_number($todofuken[trim($fetch->���꣱)]);
		$rec['nav_address2'] = sql_char($fetch->���ꣲ);
		$rec['nav_address3'] = sql_char($fetch->���ꣳ);
		$rec['nav_company'] = sql_char($fetch->���̾);
		$rec['nav_shokugyo_id'] = sql_number($todofuken[trim($fetch->����)]);
		db_insert('t_navi', $rec);

		if (db_errormessage() != '')
			exit;
	}

	set_sequence('t_navi', 'nav_navi_id');
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
		$room_id = get_next_seq('t_room', 'rom_room_id');
		$new_room_id[$fetch->ML_ID] = $room_id;

		$rec['rom_room_id'] = sql_number($room_id);
		$rec['rom_status'] = sql_number(1);
		$rec['rom_regist_date'] = sql_date(date_format($fetch->��������, 'now'));
		$rec['rom_update_date'] = sql_date(date_format($fetch->�ǽ���������));
		$rec['rom_open_date'] = sql_date(date_format($fetch->��������, 'now'));
		$rec['rom_name'] = sql_char(trim($fetch->ML_N));
		$rec['rom_outline'] = sql_char($fetch->GAIYOU);
		//$rec['rom_banner_id'] =
		$rec['rom_room_code'] = sql_char(trim($fetch->�ͣ�̾));
		$rec['rom_regist_mail_flag'] = sql_number($fetch->�����å�����°��);
		$rec['rom_resign_mail_flag'] = sql_number($fetch->����å�����°��);
		$rec['rom_header_flag'] = sql_number($fetch->�إå��������ե饰);
		$rec['rom_footer_flag'] = sql_number($fetch->�եå��������ե饰);
		$rec['rom_guest_login_flag'] = sql_number($fetch->��������Ͽ����);
		$rec['rom_guest_regist_notify_flag'] = sql_number($fetch->��������Ͽ���°��);
		$rec['rom_public_user_notify_flag'] = sql_number(1);
		$rec['rom_header'] = sql_char($fetch->�إå���);
		$rec['rom_footer'] = sql_char($fetch->�եå���);
		$rec['rom_regist_mail'] = sql_char($fetch->�����å�����);
		$rec['rom_resign_mail'] = sql_char($fetch->����å�����);
		//$rec['rom_guest_regist_mail'] =
		db_insert('t_room', $rec);

		if (db_errormessage() != '')
			exit;
	}

	set_sequence('t_room', 'rom_room_id');
}

// ���ʥӥڡ�������
function convert_navi_page() {
	global $new_channel_id, $new_sub_channel_id;
	global $new_navi_id;
	global $new_room_id;

	$sql = "SELECT T1.NAVIPAGE_ID,T1.NAVI_ID,T1.CLOSED,T1.SDATE,NAVIPAGE_N,CHANNEL_ID,CHANNEL_SID,NAVIPAGE_GAIYOU,T1.PR,ML_ID"
			. " FROM dbo.N_NAVIPAGE T1"
			. " INNER JOIN (SELECT * FROM dbo.N_INDEX1 UNION SELECT * FROM dbo.N_INDEX2) T2 ON T2.NAVIPAGE_ID=T1.NAVIPAGE_ID AND T2.GRP_ID=T1.GRP_ID"
			. " LEFT JOIN K_ML T3 ON T3.NAVIPAGE_ID=T1.NAVIPAGE_ID AND T3.CLOSED<>'3'"
			. " WHERE T1.CLOSED<>'3'"
			. " ORDER BY (CASE WHEN T1.NAVIPAGE_ID='hitobito' THEN 0 ELSE 1 END),T1.NAVI_ID";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		if (isset($new_navi_id[$fetch->NAVI_ID])) {
			$navi_page_id = get_next_seq('t_navi_page', 'nvp_navi_page_id');

			$rec['nvp_navi_page_id'] = sql_number($navi_page_id);
			$rec['nvp_navi_id'] = sql_number($new_navi_id[$fetch->NAVI_ID]);
			$rec['nvp_status'] = sql_number($fetch->CLOSED == '3' ? 9 : 1);
			$rec['nvp_regist_date'] = sql_date(date_format($fetch->SDATE), 'current_date');
			$rec['nvp_open_date'] = sql_date(date_format($fetch->SDATE));
			$rec['nvp_theme'] = sql_char($fetch->NAVIPAGE_N);
			$rec['nvp_channel_id'] = sql_number($new_channel_id[$fetch->CHANNEL_ID]);
			$rec['nvp_subchannel_id'] = sql_number($new_sub_channel_id[$fetch->CHANNEL_ID][$fetch->CHANNEL_SID]);
			$rec['nvp_type'] = sql_number(isset($new_room_id[$fetch->ML_ID]) ? 1 : 2);
			$rec['nvp_outline'] = sql_char($fetch->NAVIPAGE_GAIYOU);
			$rec['nvp_blog_id'] = sql_number($navi_page_id);
			$rec['nvp_room_id'] = sql_number($new_room_id[$fetch->ML_ID]);
			$rec['nvp_melmaga_id'] = sql_number($navi_page_id);
			$rec['nvp_title'] = sql_char($fetch->NAVIPAGE_N);
			//$rec['nvp_copy'] =
			$rec['nvp_self_introduction'] = sql_char($fetch->PR);
			$rec['nvp_image_id'] = sql_number($fetch->NAVI_ID);
			$rec['nvp_contents_check'] = sql_number(3);
			$rec['nvp_open_flag'] = sql_number($fetch->CLOSED == '3' ? 0 : 1);
			db_insert('t_navi_page', $rec);

			$rec['blg_blog_id'] = sql_number($navi_page_id);
			$rec['blg_status'] = sql_number($fetch->CLOSED == '3' ? 9 : 1);
			$rec['blg_regist_date'] = sql_date(date_format($fetch->SDATE), 'current_date');
			$rec['blg_title'] = sql_char($fetch->NAVIPAGE_N);
			db_insert('t_blog', $rec);

			$rec['mmg_melmaga_id'] = sql_number($navi_page_id);
			$rec['mmg_status'] = sql_number(9);
			$rec['mmg_melmaga_name'] = sql_char('���ޥ�̾��̤����');
			$rec['mmg_backnumber'] = sql_number(1);
			$rec['mmg_notify_mail'] = sql_number(1);
			db_insert('t_melmaga', $rec);

			if (db_errormessage() != '')
				exit;
		}
	}

	set_sequence('t_navi_page', 'nvp_navi_page_id');
	set_sequence('t_blog', 'blg_blog_id');
	set_sequence('t_melmaga', 'mmg_melmaga_id');
}

// ���磻�磻��ĥ��С�����
function convert_room_member() {
	global $new_member_id, $new_room_id;

	$sql = "SELECT �ͣ��ֹ�,���С��ֹ�,��������,�ǽ���������,�ϥ�ɥ�,̾��"
			. " FROM dbo.�̡ͣ����С�"
			. " ORDER BY ���С��ֹ�";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		if (isset($new_room_id[$fetch->�ͣ��ֹ�])) {
			$rec['rmm_room_id'] = sql_number($new_room_id[$fetch->�ͣ��ֹ�]);
			$rec['rmm_member_id'] = sql_number($new_member_id[$fetch->���С��ֹ�]);
			$rec['rmm_status'] = sql_number(3);
			$rec['rmm_kari_date'] = sql_date(date_format($fetch->��������, 'now'));
			$rec['rmm_guest_date'] = sql_date(date_format($fetch->�ǽ���������));
			$rec['rmm_regist_date'] = sql_date(date_format($fetch->��������, 'now'));
			$rec['rmm_nickname'] = sql_char(trim($fetch->�ϥ�ɥ�) != '' ? trim($fetch->�ϥ�ɥ�) : trim($fetch->̾��));
			$rec['rmm_member_code'] = sql_char(create_member_cd());
			$rec['rmm_admin_flag'] = sql_number(0);
			$rec['rmm_mail_send_flag'] = sql_number($fetch->�ۿ���ߥե饰 == 0 ? 1 : 0);
			db_insert('t_room_member', $rec);

			if (db_errormessage() != '')
				exit;
		}
	}
}

// ���ơ��֥�TRUNCATE
function truncate_table($table) {
	$sql = "TRUNCATE TABLE $table";
	db_exec($sql);
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