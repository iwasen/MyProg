<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:HTMLメールプレビュー
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/str_replace.php");

if (SERVICE == 'admin') {
	$sql = "SELECT ad_mail_addr,ad_name1 FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$to_addr = $fetch->ad_mail_addr;
		$to_name = $fetch->ad_name1;
	}
} elseif (SERVICE == 'agent') {
	$sql = "SELECT ag_mail_addr,ag_name1 FROM t_agent WHERE ag_agent_id={$_SESSION['agent_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$to_addr = $fetch->ag_mail_addr;
		$to_name = $fetch->ag_name1;
	}
}

$html_body = str_replace('%MAIL_ADDR%', $to_addr, $html_body);
$html_body = str_replace('%MONITOR_NAME%', $to_name, $html_body);

$sql = "SELECT jb_search_id FROM t_job WHERE jb_job_id=$job_id";
replace_kyoumi_genre($mail_format, db_fetch1($sql), $html_body);

echo $html_body;
?>