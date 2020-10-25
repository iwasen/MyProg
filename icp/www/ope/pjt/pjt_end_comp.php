<?
/******************************************************
' System :ICPオペレータページ
' Content:プロジェクト終了完了
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$pjt_id = $_POST['pjt_id'];

db_begin_trans();

// ジョブを終了
$rec['jb_status'] = sql_number($g_job_status['終了']);
db_update('t_job', $rec, sprintf("jb_pjt_id=%s AND jb_status<>%s", sql_number($pjt_id), sql_number($g_job_status['終了'])));

// プロジェクトを終了
$rec['pj_status'] = sql_number($g_pjt_status['終了']);
db_update('t_pjt', $rec, sprintf("pj_pjt_id=%s", sql_number($pjt_id)));

db_commit_trans();
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
					<td>■プロジェクト終了完了</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>プロジェクト　<?=htmlspecialchars($_POST['pjt_name'])?>　を終了しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../top.php">トップページに戻る</a>　｜　<a href="list.php">プロジェクト一覧に戻る</a>
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
