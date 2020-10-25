<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ情報更新
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('project', 'プロジェクト管理', 'ジョブ情報更新', BACK_TOP);

switch ($next_action) {
case 'apply':
	$today = time();
	$en_start = sql_time($en_start_date);
	$jb_start = sql_time($jb_start_date);
/*
	if (SERVICE == 'agent') {
		if ($en_start_date != "" && $en_start < $today) {
			$msg = "アンケートの開始時間を過ぎているようです。<br>";
		}
		if ($jb_start < $today) {
			$msg .= "配信開始時間を過ぎているようです。<br>";
		}
	}
*/
	if ($msg == "") {
		$sql = "UPDATE t_enquete SET en_status=2 WHERE en_enquete_id=$en_id";
		db_exec($sql);
		$title = "ジョブ申請";
		$msg = "ジョブの申請を完了しました。";
		$back = "location.href='jb_contents.php?job_id=$job_id'";
	} else {
		$back = "history.back()";
	}
	break;
case 'cancel_apply':
	$sql = "SELECT en_status FROM t_enquete WHERE en_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
	}
	if ($fetch->en_status == 2) {
		$sql = "UPDATE t_enquete SET en_status=1 WHERE en_job_id=$job_id";
		db_exec($sql);
		$title = "ジョブ申請取り消し";
		$msg = "ジョブの申請を取り消しました。";
	} elseif ($fetch->en_status == 3) {
		$msg = "事務局でチェック中のため、処理できませんでした。";
	} else {
		$msg = "エラーが発生したため、処理できませんでした。";
	}
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'delete':
	db_begin_trans();
	$sql = "SELECT jb_enquete_id,jb_search_id FROM t_job WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->jb_enquete_id) {
			$sql= "DELETE FROM t_enquete WHERE en_enquete_id=$fetch->jb_enquete_id";
			db_exec($sql);
		}

		if ($fetch->jb_search_id) {
			$sql= "DELETE FROM t_search WHERE sr_search_id=$fetch->jb_search_id";
			db_exec($sql);
		}
	}
	$sql = "DELETE FROM t_send_notice WHERE sn_job_id=$job_id";
	db_exec($sql);
	$sql = "DELETE FROM t_job WHERE jb_job_id=$job_id";
	db_exec($sql);
	db_commit_trans();
	$title = "ジョブ情報　更新";
	$msg = 'ジョブ情報を削除しました。';
	$back = "location.href='list.php'";
	break;
case 'pause':
	$sql = "UPDATE t_enquete SET en_status=6 WHERE en_job_id=$job_id AND en_status=5";
	db_exec($sql);
	$title = "ジョブ状態　更新";
	$msg = 'ジョブを一時停止しました。';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'restart':
	$sql = "UPDATE t_enquete SET en_status=5 WHERE en_job_id=$job_id AND en_status=6";
	db_exec($sql);
	$title = "ジョブ状態　更新";
	$msg = 'ジョブを再開しました。';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'remake':
	db_begin_trans();
	$sql = "UPDATE t_job SET jb_send_status=0,jb_notice_flag=FALSE WHERE jb_job_id=$job_id";
	db_exec($sql);
	$sql = "UPDATE t_enquete SET en_status=1 WHERE en_enquete_id=(SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id)";
	db_exec($sql);
	db_commit_trans();
	$title = "ジョブ状態　更新";
	$msg = 'ジョブを未承認状態に戻しました。';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'end_date':
	$sql = sprintf("UPDATE t_enquete SET en_end_date=%s WHERE en_job_id=$job_id", sql_date2($en_end_year,$en_end_month,$en_end_day,$en_end_hour));
	db_exec($sql);
	$title = "締切日時　更新";
	$msg = '締切日時を更新しました。';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
