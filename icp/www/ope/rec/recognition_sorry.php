<?
/******************************************************
' System :ICPオペレータページ
' Content:ジョブ差し戻し
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

$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_reorder_flag"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	db_begin_trans();

	if ($fetch->jb_reorder_flag == DBFALSE) {
		// 新規申請
		$rec['jb_status'] = sql_number($g_job_status['申請前']);
		db_update('t_job', $rec, "jb_job_id=$job_id");
	} else {
		// 新規申請
		$rec['jb_reorder_flag'] = sql_bool(false);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// 再申請情報削除
		db_delete('t_job_reorder', "jr_job_id=$job_id");
	}

	// ジョブ差し戻し通知メール送信
	send_job_notify_mail($job_id, 'job_remand');

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
					<td>プロジェクト：<?=htmlspecialchars($fetch->pj_name)?>（<?=htmlspecialchars($fetch->pj_pjt_cd)?>）<br>
						ジョブ：<?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）<br>
						を差し戻しました。</td>
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
