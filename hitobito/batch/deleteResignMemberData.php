<?php
/**
 * ���桼������κ������
 * 
 * @author Ryuji
 * @version $Id: deleteResignMemberData.php,v 1.2 2006/05/06 04:57:12 ryu Exp $
 * 
 */
require_once './batch.inc.php';


$logger = new batchLogger(basename(__FILE__), '���桼��������');
$db = getDB();

// �桼������Υǡ�����¸��������
define('STORAGE_TIME_LIMIT', 14);
$limit_day = time() - (STORAGE_TIME_LIMIT * 24*60*60);
$limit_day_sql = $db->DBTimeStamp($limit_day);
$db->StartTrans();

/*
 * �����оݤϡ�mbr_mail_addr_low ��NULL�ʳ���NULL���ä��顢����ѤߤȤߤʤ���
 *  mbr_status = 9 ������
 *  mbr_resign_date����STRAGE_TIME_LIMIT �ʾ���
 */

// �ʥӥǡ����ޥ���
$sql = "UPDATE t_navi SET " .
		"nav_name1='', " .
		"nav_name2=NULL, " .
		"nav_name1_kana=NULL, " .
		"nav_name2_kana=NULL, " .
		"nav_zip_cd=NULL, " .
		"nav_jitaku_tel=NULL, " .
		"nav_jitaku_fax=NULL, " .
		"nav_kinmu_tel=NULL, " .
		"nav_address2=NULL, " .
		"nav_address3=NULL," .
		"nav_company=NULL " .
		"WHERE nav_member_id IN " .
		" (SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr_low IS NOT NULL AND mbr_status=9 AND mbr_resign_date < ".$limit_day_sql.")";
if(!$db->Execute($sql)){
	$logger->addError('�ʥӥǡ�������˼���');
}

//���С��ǡ����ޥ���
$sql = "UPDATE t_member SET " .
		"mbr_mail_addr=NULL, " .
		"mbr_mail_addr_low=NULL, " .
		"mbr_password=NULL " .
		"WHERE mbr_status=9 AND mbr_resign_date < ".$limit_day_sql . 
		" AND mbr_mail_addr_low IS NOT NULL" ;
if(!$db->Execute($sql)){
	$logger->addError('���С��ǡ�������˼���');
}

$db->CompleteTrans();

exit();

?>