<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_myp_check.php");

// ICP�ץ�������ID����
$icp_pjt_id = get_icp_pjt_id();

// ICP�����ID����
$sql = "SELECT ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$icp_job_id = db_fetch1($sql);

if ($icp_job_id) {
	// ICP�ץ������ȥ����ɼ���
	$sql = "SELECT pj_pjt_cd FROM t_pjt WHERE pj_pjt_id=$icp_pjt_id";
	$icp_pjt_cd = icp_db_fetch1($sql);

	if ($icp_pjt_cd) {
		// ICP����֥����ɼ���
		$sql = "SELECT jb_job_cd FROM t_job WHERE jb_job_id=$icp_job_id";
		$icp_job_cd = icp_db_fetch1($sql);

		if ($icp_job_cd) {
			// �᡼�륢�ɥ쥹����
			$sql = "SELECT mr_mail_addr_low FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_owner_id']}";
			$mail_addr = db_fetch1($sql);

			// �ޡ���������ICP�ѥ���ɤ����
			$sql = "SELECT pm_password FROM t_pjt_member WHERE pm_pjt_id=$icp_pjt_id AND pm_mail_addr=" . sql_char($mail_addr);
			$result = icp_db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$password = $fetch->pm_password;
			}
		}
	}
}

// ��ļ�URL����
$url = get_room_url($icp_pjt_cd) . 'login/login_check.php';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="css/style.css">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$url?>">
<input type="hidden" name="job_cd" <?=value($icp_job_cd)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="password" <?=value($password)?>>
</form>
</body>
</html>