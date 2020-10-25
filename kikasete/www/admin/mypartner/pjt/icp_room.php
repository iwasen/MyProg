<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・ICP会議室表示
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ICPプロジェクトID取得
$icp_pjt_id = get_icp_pjt_id();

// ICPジョブID取得
$sql = "SELECT ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$icp_job_id = db_fetch1($sql);

if ($icp_job_id) {
	// ICPプロジェクトコード取得
	$sql = "SELECT pj_pjt_cd FROM t_pjt WHERE pj_pjt_id=$icp_pjt_id";
	$icp_pjt_cd = icp_db_fetch1($sql);

	if ($icp_pjt_cd) {
		// ICPジョブコード取得
		$sql = "SELECT jb_job_cd FROM t_job WHERE jb_job_id=$icp_job_id";
		$icp_job_cd = icp_db_fetch1($sql);

		if ($icp_job_cd) {
			// 登録オペレータのメールアドレスとパスワードを取得
			if (SERVICE == 'agent') {
				$sql = "SELECT ag_mail_addr_low FROM t_agent WHERE ag_agent_id=$agent_id";
				$mail_addr = db_fetch1($sql);
				$sql = "SELECT pm_mail_addr,pm_password FROM t_pjt_member WHERE pm_pjt_id=$icp_pjt_id AND pm_mail_addr=" . sql_char($mail_addr);
			} else
				$sql = "SELECT pm_mail_addr,pm_password FROM t_pjt_member WHERE pm_pjt_id=$icp_pjt_id AND pm_operator_flag=1";

			$result = icp_db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mail_addr = $fetch->pm_mail_addr;
				$password = $fetch->pm_password;

				// 会議室URL取得
				$url = get_room_url($icp_pjt_cd) . 'login/login_check.php';
			}
		}
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<?
if ($url != '') {
?>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$url?>">
<input type="hidden" name="job_cd" <?=value($icp_job_cd)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="password" <?=value($password)?>>
</form>
<?
} else {
?>
<body onload="window.close()">
<?
}
?>
</body>
</html>