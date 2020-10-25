<?
include('../inc/common.php');
include('../inc/database.php');

//=== メイン処理 ===
$ary = explode('-', trim($QUERY_STRING));
if (count($ary) != 2)
	param_error();

$ct_id = $ary[0];
$member_id = $ary[1];

if (!is_numeric($ct_id))
	param_error();

if ($member_id == '%CLICK%')
	$member_id = '';
elseif (is_numeric($member_id))
	$member_id = MakeId($member_id, 10);
else
	param_error();

$sql = "SELECT CC_jump_url,CC_start_date,CC_end_date FROM T_CLICK_COUNT WHERE CC_ct_id=$ct_id";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if ($ds->EOF())
	param_error();

$time = time();
if ($ds->GetValue('CC_start_date') != null) {
	if (strtotime($ds->GetValue('CC_start_date')) > $time)
		$member_id = '';
}

if ($ds->GetValue('CC_end_date') != null) {
	if (strtotime($ds->GetValue('CC_end_date')) < $time - 24*60*60)
		$member_id = '';
}

$url = $ds->GetValue('CC_jump_url');

//クリックカウント
if ($member_id != '') {
	$sql = "SELECT SR_member_id"
			. " FROM T_SENDMAIL_RR,T_JOB,T_CLICK_COUNT"
			. " WHERE CC_ct_id=$ct_id AND JB_job_id=CC_job_id AND SR_mail_id=JB_mail_id AND SR_member_id='$member_id' AND SR_status='1'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF()) {
		BeginTrans();

		$sql = "INSERT INTO T_MAIL_CLICK"
				. " VALUES(MC_seq_no.nextval,$ct_id,'$member_id','$REMOTE_ADDR',SYSDATE)";
		ExecuteSQL($sql);

		$sql = "UPDATE T_CLICK_COUNT SET CC_click_num=NVL(CC_click_num,0)+1 WHERE CC_ct_id=$ct_id";
		ExecuteSQL($sql);

		CommitTrans();
	}
}

redirect($url);

//=== パラメータエラー ===
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>