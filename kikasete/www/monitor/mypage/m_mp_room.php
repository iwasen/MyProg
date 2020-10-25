<?
$top = '.';
$inc = "$top/../../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
$inc = "$top/../inc";
include("$inc/mn_mypage.php");

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
			// ICPメンバーID取得
			$sql = "SELECT pmn_icp_member_id FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id AND pmn_monitor_id={$_SESSION['ss_monitor_id']}";
			$icp_member_id = db_fetch1($sql);

			// モニターのメールアドレスとICPパスワードを取得
			$sql = "SELECT pm_mail_addr,pm_password FROM t_pjt_member WHERE pm_member_id=$icp_member_id";
			$result = icp_db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mail_addr = $fetch->pm_mail_addr;
				$password = $fetch->pm_password;
			}
		}
	}
}

// 会議室URL取得
$url = get_room_url($icp_pjt_cd) . 'login/login_check.php';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$url?>">
<input type="hidden" name="job_cd" <?=value($icp_job_cd)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="password" <?=value($password)?>>
</form>
</body>
</html>