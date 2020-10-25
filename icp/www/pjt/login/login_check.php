<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクトページログインチェック
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/status.php");
include("$inc/auth.php");
include("$inc/define.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");

// パスからプロジェクトコード取得
$pjt_cd = get_pjt_cd();

$fail = 1;

// IDとパスワードをチェック
$sql = sprintf(
			"SELECT pm_member_id,pm_mail_addr,pm_name1,pm_name2,pm_operator_flag,pm_staff_auth,or_name,pj_pjt_id,pj_name,pj_pjt_cd"
		. " FROM t_pjt_member"
		. " JOIN m_org ON or_org_id=pm_org_id"
		. " JOIN t_pjt ON pj_pjt_id=pm_pjt_id"
		. " WHERE pj_pjt_cd=%s AND pm_mail_addr=%s AND pm_password=%s AND pm_status=1 AND pj_status=1",
		sql_char($pjt_cd),
		sql_char(strtolower($_POST['mail_addr'])),
		sql_char($_POST['password']));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	if ($_POST['job_cd'] != '') {
		$sql = sprintf(
					"SELECT jb_job_id,jb_status,jb_room_type,jm_participant_auth"
				. " FROM t_job"
				. " JOIN t_job_member ON jm_job_id=jb_job_id AND jm_member_id=$fetch->pm_member_id"
				. " WHERE jb_job_cd=%s AND jb_status>={$g_job_status['待機中']} AND jm_status=1",
				sql_char($_POST['job_cd']));
		$result = db_exec($sql);
		if (pg_num_rows($result) > 0) {
			$fetch2 = pg_fetch_object($result, 0);

			$staff_auth = strpos($fetch->pm_staff_auth, AUTH_ON) !== false;

			if ($fetch2->jb_status == $g_job_status['待機中'])
				$fail = 2;
			elseif ($fetch2->jb_status == $g_job_status['休止中'] && !$staff_auth)
				$fail = 3;
			elseif ($fetch2->jb_status != $g_job_status['実施中'] && !$staff_auth)
				$fail = 4;
			else {
				session_start();

				// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
				SetCookie('icp_login_member', $fetch->pm_mail_addr, COOKIE_EXPIRE, '/');

				$scd = 'r' . get_pjt_cd();
				$_SESSION[$scd]['pjt_id'] = $fetch->pj_pjt_id;
				$_SESSION[$scd]['job_id'] = $fetch2->jb_job_id;
				$_SESSION[$scd]['member_id'] = $fetch->pm_member_id;
				$_SESSION[$scd]['room_type'] = $fetch2->jb_room_type;
				$_SESSION[$scd]['participant_auth'] = $fetch2->jm_participant_auth;
				$_SESSION[$scd]['staff_auth'] = $fetch->pm_staff_auth;

				if ($fetch2->jb_room_type == ROOM_PIR) {
					if (!check_participant_auth('司会者', $fetch2->jm_participant_auth))
						$_SESSION[$scd]['disp_member_id'] = $fetch->pm_member_id;
					else
						$_SESSION[$scd]['disp_member_id'] = '';
				} else
					$_SESSION[$scd]['disp_member_id'] = 0;

				redirect("$top/room/index.php");
			}
		}
	} else {
		// スタッフ系権限が必要
		if (strpos($fetch->pm_staff_auth, AUTH_ON) !== false) {
			session_start();

			// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
			SetCookie('icp_login_member', $fetch->pm_mail_addr, COOKIE_EXPIRE, '/');

			// セッションにログイン情報を保存
			$_SESSION[SCD]['pjt_id'] = $fetch->pj_pjt_id;
			$_SESSION[SCD]['member_id'] = $fetch->pm_member_id;
			$_SESSION[SCD]['member_name'] = $fetch->pm_name1 . $fetch->pm_name2;
			$_SESSION[SCD]['org_name'] = $fetch->or_name;
			$_SESSION[SCD]['pjt_name'] = $fetch->pj_name;
			$_SESSION[SCD]['pjt_cd'] = $fetch->pj_pjt_cd;
			$_SESSION[SCD]['operator_flag'] = $fetch->pm_operator_flag;
			$_SESSION[SCD]['staff_auth'] = $fetch->pm_staff_auth;

			redirect("$top/index.php");
		}
	}
}

// クッキーのメールアドレスを削除
SetCookie('icp_login_member', '', 0, '/');

// ログインエラー
$url = "login.php?fail=$fail";
if ($fail != 1)
	$url .= '&job_cd=' . urlencode($_POST['job_cd']) . '&mail_addr=' . urlencode($_POST['mail_addr']);
redirect($url);
?>
