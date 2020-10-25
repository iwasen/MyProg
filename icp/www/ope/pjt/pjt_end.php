<?
/******************************************************
' System :ICPオペレータページ
' Content:プロジェクト終了
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$pjt_id = $_GET['pjt_id'];

// 終了していないジョブの検索
$sql = "SELECT COUNT(*) FROM t_job WHERE jb_pjt_id=$pjt_id AND jb_status<>{$g_job_status['終了']}";
$job_num = db_fetch1($sql);

$sql = "SELECT *"
		. " FROM t_pjt"
		. " WHERE pj_pjt_id=$pjt_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function on_submit_form1(f) {
<?
if ($job_num == 0) {
?>
	return confirm("このプロジェクトを終了します。よろしいですか？");
<?
} else {
?>
	return confirm("このプロジェクトには終了していないジョブがあります。プロジェクトを終了するとこれらのジョブも直ちに終了します。\nプロジェクトを終了してもよろしいですか？");
<?
}
?>
}
//-->
	</script>
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

				<form method="post" name="form1" action="pjt_end_comp.php" onsubmit="return on_submit_form1(this)">
				<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
				<input type="hidden" name="pjt_name" <?=value($fetch->pj_name)?>>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■このプロジェクトを終了します。</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">プロジェクト名</th>
					<td width="70%"><?=htmlspecialchars($fetch->pj_name)?></td>
				</tr>
				<tr>
					<th>プロジェクト詳細</th>
					<td><?=nl2br(htmlspecialchars($fetch->pj_description))?></td>
				</tr>
				<tr>
					<th>プロジェクトコード</th>
					<td><?=htmlspecialchars($fetch->pj_pjt_cd)?></td>
				</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■このプロジェクトに登録されているジョブ一覧</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="30%">ジョブ名</th>
					<th width="25%">会議室タイプ</th>
					<th width="20%">実施期間</th>
					<th width="10%">メンバー数</th>
					<th width="15%">状態</th>
				</tr>
<?
$sql = "SELECT jb_name,jb_job_cd,jb_room_type,jb_start_date,jb_end_date,jb_member_num,jb_status"
		. " FROM t_job"
		. " WHERE jb_pjt_id=$pjt_id"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr class="line<?=$i % 2?>">
					<td><?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）</td>
					<td><?=decode_room_type($fetch->jb_room_type)?></td>
					<td align="center"><?=format_date($fetch->jb_start_date)?> 〜 <?=format_date($fetch->jb_end_date)?></td>
					<td align="right"><?=number_format($fetch->jb_member_num)?>人</td>
					<td align="center"><?=decode_job_status($fetch->jb_status)?></td>
				</tr>
<?
}
?>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center"><input type="submit" value="　終了　" class="btn">
					<input type="button" value="キャンセル" class="btn" onclick="history.back()"></td>
				</tr>
				</table>
				</form>

			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>

<? ope_footer() ?>

</div>
</body>

</html>
