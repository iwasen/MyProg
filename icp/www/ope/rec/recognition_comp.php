<?
/******************************************************
' System :ICPオペレータページ
' Content:ジョブ承認
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/status.php");
include("$inc/mail.php");
include("$inc/job_mail.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$job_id = $_GET['job_id'];

$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_reorder_flag,jb_status"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$pjt_cd = $fetch->pj_pjt_cd;
	$pjt_name = $fetch->pj_name;
	$job_cd = $fetch->jb_job_cd;
	$job_name = $fetch->jb_name;
	$status = $fetch->jb_status;

	db_begin_trans();

	if ($fetch->jb_reorder_flag == DBFALSE) {
		// 新規申請
		$rec['jb_status'] = sql_number($g_job_status['承認済']);
		db_update('t_job', $rec, "jb_job_id=$job_id");
	} else {
		// 再申請
		$sql = "SELECT * FROM t_job_reorder WHERE jr_job_id=$job_id";
		$result = db_exec($sql);
		if (pg_num_rows($result) > 0) {
			$fetch = pg_fetch_object($result, 0);

			$job_name = $fetch->jr_name;

			// 再申請の内容にジョブ情報を置き換え
			$rec['jb_name'] = sql_char($fetch->jr_name);
			$rec['jb_room_type'] = sql_char($fetch->jr_room_type);
			$rec['jb_start_date'] = sql_char($fetch->jr_start_date);
			$rec['jb_end_date'] = sql_char($fetch->jr_end_date);
			$rec['jb_member_max'] = sql_char($fetch->jr_member_max);
			$rec['jb_open_mail_addr'] = sql_char($fetch->jr_open_mail_addr);
			$rec['jb_attachment_file'] = sql_char($fetch->jr_attachment_file);
			$rec['jb_reorder_flag'] = sql_bool(false);
			if ($status == $g_job_status['猶予中'])
				$rec['jb_status'] = sql_number($g_job_status['待機中']);	// 猶予期間中だったら一旦待機中に戻す
			db_update('t_job', $rec, "jb_job_id=$job_id");
		}

		// 再申請情報削除
		db_delete('t_job_reorder', "jr_job_id=$job_id");
	}

	// ジョブ承認通知メール送信
	send_job_notify_mail($job_id, 'job_accept');

	db_commit_trans();
} else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■未承認ジョブ詳細</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>プロジェクト：<?=htmlspecialchars($pjt_name)?>（<?=htmlspecialchars($pjt_cd)?>）<br>
						ジョブ：<?=htmlspecialchars($job_name)?>（<?=htmlspecialchars($job_cd)?>）<br>
						を承認しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="<?=$top?>/top.php">トップページに戻る</a>
					</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
