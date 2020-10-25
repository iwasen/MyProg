<?
/******************************************************
' System :ICPオペレータページ
' Content:未承認ジョブ一覧
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ICP管理者権限が無ければプロジェクト一覧ページへ
$sql = "SELECT op_admin_auth FROM t_operator WHERE op_operator_id=$g_operator_id";
if (db_fetch1($sql) == 0)
	redirect('pjt/list.php');

// 表示種別
$disp = isset($_GET['disp']) ? $_GET['disp'] : '1';

// 表示するステータス
switch ($disp) {
case '1':
	$kind = '未承認';
	$cond_status = "AND (jb_status={$g_job_status['申請中']} OR jb_reorder_flag=true)";
	break;
case '2':
	$kind = '承認済み';
	$cond_status = "AND jb_status IN ({$g_job_status['承認済']},{$g_job_status['待機中']})";
	break;
case '3':
	$kind = '実施中';
	$cond_status = "AND jb_status IN ({$g_job_status['実施中']},{$g_job_status['休止中']},{$g_job_status['猶予中']})";
	break;
case '4':
	$kind = '終了';
	$cond_status = "AND jb_status={$g_job_status['終了']}";
	break;
case '5':
	$kind = '全';
	$cond_status = '';
	break;
}
if ($g_operator_id != 1)
	$cond_org_id .= "AND op_org_id=(SELECT op_org_id FROM t_operator WHERE op_operator_id=$g_operator_id)";
else
	$cond_org_id = '';
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
function on_recognition(job_id) {
	location.href = "<?=$top?>/rec/recognition.php?job_id=" + job_id;
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

				<form method="get" name="form1" action="top.php">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td width="50%">■<?=$kind?>ジョブ一覧</td>
					<td width="50%" align="right">
						<select name="disp" class="form" onchange="document.form1.submit()">
							<option <?=value_selected('1', $disp)?>>未承認</option>
							<option <?=value_selected('2', $disp)?>>承認済み</option>
							<option <?=value_selected('3', $disp)?>>実施中</option>
							<option <?=value_selected('4', $disp)?>>終了</option>
							<option <?=value_selected('5', $disp)?>>全表示</option>
						</select>

					</td>
				</tr>
				</table>
				</form>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="5%">pjt ID</th>
					<th width="20%">pjt名（pjtコード）</th>
					<th width="5%">job ID</th>
					<th width="20%">job名（jobコード）</th>
					<th width="20%">期間</th>
					<th width="10%">申請人数</th>
					<th width="10%">申請状況</th>
					<th width="5%">承認</th>
				</tr>
<?
$sql = "SELECT pj_pjt_id,pj_pjt_cd,pj_name,jb_job_id,jb_job_cd,jb_name,jb_start_date,jb_end_date,jb_member_max,jb_status,jb_reorder_flag,jr_name,jr_start_date,jr_end_date,jr_member_max"
		. " FROM t_pjt"
		. " JOIN t_job ON jb_pjt_id=pj_pjt_id"
		. " JOIN t_operator ON op_operator_id=pj_operator_id"
		. " LEFT JOIN t_job_reorder ON jr_job_id=jb_job_id"
		. " WHERE pj_status={$g_pjt_status['稼動中']} $cond_org_id $cond_status"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$reorder_flag = ($fetch->jb_reorder_flag == DBTRUE);
	$member_max = $reorder_flag ? $fetch->jr_member_max : $fetch->jb_member_max;
?>
				<tr class="line<?=$i % 2?>">
					<td align="center"><?=$fetch->pj_pjt_id?></td>
					<td><?=htmlspecialchars($fetch->pj_name)?>（<?=htmlspecialchars($fetch->pj_pjt_cd)?>）</td>
					<td align="center"><?=$fetch->jb_job_id?></td>
					<td><?=htmlspecialchars($reorder_flag ? $fetch->jr_name : $fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）</td>
					<td align="center"><?=format_date($reorder_flag ? $fetch->jr_start_date : $fetch->jb_start_date)?>〜<?=format_date($reorder_flag ? $fetch->jr_end_date : $fetch->jb_end_date)?></td>
					<td align="right"><?=$member_max ? number_format($member_max) . '人' : '制限なし'?></td>
					<td align="center"><?=$reorder_flag ? '再申請' : ($fetch->jb_status == $g_job_status['申請中'] ? '新規' : '-')?></td>
					<td>
<?
	if ($fetch->jb_status == $g_job_status['申請中'] || $reorder_flag) {
?>
						<input type="button" value="承認" class="form" onclick="on_recognition(<?=$fetch->jb_job_id?>)">
<?
	}
?>
					</td>
				</tr>
<?
}
?>
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
