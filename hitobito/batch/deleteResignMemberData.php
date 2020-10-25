<?php
/**
 * 退会ユーザ情報の削除処理
 * 
 * @author Ryuji
 * @version $Id: deleteResignMemberData.php,v 1.2 2006/05/06 04:57:12 ryu Exp $
 * 
 */
require_once './batch.inc.php';


$logger = new batchLogger(basename(__FILE__), '退会ユーザ情報削除');
$db = getDB();

// ユーザ退会後のデータ保存期間日数
define('STORAGE_TIME_LIMIT', 14);
$limit_day = time() - (STORAGE_TIME_LIMIT * 24*60*60);
$limit_day_sql = $db->DBTimeStamp($limit_day);
$db->StartTrans();

/*
 * 処理対象は、mbr_mail_addr_low がNULL以外（NULLだったら、削除済みとみなす）
 *  mbr_status = 9 （退会）
 *  mbr_resign_date　がSTRAGE_TIME_LIMIT 以上前
 */

// ナビデータマスク
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
	$logger->addError('ナビデータ削除に失敗');
}

//メンバーデータマスク
$sql = "UPDATE t_member SET " .
		"mbr_mail_addr=NULL, " .
		"mbr_mail_addr_low=NULL, " .
		"mbr_password=NULL " .
		"WHERE mbr_status=9 AND mbr_resign_date < ".$limit_day_sql . 
		" AND mbr_mail_addr_low IS NOT NULL" ;
if(!$db->Execute($sql)){
	$logger->addError('メンバーデータ削除に失敗');
}

$db->CompleteTrans();

exit();

?>