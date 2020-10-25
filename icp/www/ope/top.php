<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:̤��ǧ����ְ���
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

// ICP�����Ը��¤�̵����Хץ������Ȱ����ڡ�����
$sql = "SELECT op_admin_auth FROM t_operator WHERE op_operator_id=$g_operator_id";
if (db_fetch1($sql) == 0)
	redirect('pjt/list.php');

// ɽ������
$disp = isset($_GET['disp']) ? $_GET['disp'] : '1';

// ɽ�����륹�ơ�����
switch ($disp) {
case '1':
	$kind = '̤��ǧ';
	$cond_status = "AND (jb_status={$g_job_status['������']} OR jb_reorder_flag=true)";
	break;
case '2':
	$kind = '��ǧ�Ѥ�';
	$cond_status = "AND jb_status IN ({$g_job_status['��ǧ��']},{$g_job_status['�Ե���']})";
	break;
case '3':
	$kind = '�»���';
	$cond_status = "AND jb_status IN ({$g_job_status['�»���']},{$g_job_status['�ٻ���']},{$g_job_status['ͱͽ��']})";
	break;
case '4':
	$kind = '��λ';
	$cond_status = "AND jb_status={$g_job_status['��λ']}";
	break;
case '5':
	$kind = '��';
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
	<title>icp.net ���ڥ졼�����ڡ���</title>
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

	<!-- ������ƥ�� -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<form method="get" name="form1" action="top.php">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td width="50%">��<?=$kind?>����ְ���</td>
					<td width="50%" align="right">
						<select name="disp" class="form" onchange="document.form1.submit()">
							<option <?=value_selected('1', $disp)?>>̤��ǧ</option>
							<option <?=value_selected('2', $disp)?>>��ǧ�Ѥ�</option>
							<option <?=value_selected('3', $disp)?>>�»���</option>
							<option <?=value_selected('4', $disp)?>>��λ</option>
							<option <?=value_selected('5', $disp)?>>��ɽ��</option>
						</select>

					</td>
				</tr>
				</table>
				</form>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="5%">pjt ID</th>
					<th width="20%">pjt̾��pjt�����ɡ�</th>
					<th width="5%">job ID</th>
					<th width="20%">job̾��job�����ɡ�</th>
					<th width="20%">����</th>
					<th width="10%">�����Ϳ�</th>
					<th width="10%">��������</th>
					<th width="5%">��ǧ</th>
				</tr>
<?
$sql = "SELECT pj_pjt_id,pj_pjt_cd,pj_name,jb_job_id,jb_job_cd,jb_name,jb_start_date,jb_end_date,jb_member_max,jb_status,jb_reorder_flag,jr_name,jr_start_date,jr_end_date,jr_member_max"
		. " FROM t_pjt"
		. " JOIN t_job ON jb_pjt_id=pj_pjt_id"
		. " JOIN t_operator ON op_operator_id=pj_operator_id"
		. " LEFT JOIN t_job_reorder ON jr_job_id=jb_job_id"
		. " WHERE pj_status={$g_pjt_status['��ư��']} $cond_org_id $cond_status"
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
					<td><?=htmlspecialchars($fetch->pj_name)?>��<?=htmlspecialchars($fetch->pj_pjt_cd)?>��</td>
					<td align="center"><?=$fetch->jb_job_id?></td>
					<td><?=htmlspecialchars($reorder_flag ? $fetch->jr_name : $fetch->jb_name)?>��<?=htmlspecialchars($fetch->jb_job_cd)?>��</td>
					<td align="center"><?=format_date($reorder_flag ? $fetch->jr_start_date : $fetch->jb_start_date)?>��<?=format_date($reorder_flag ? $fetch->jr_end_date : $fetch->jb_end_date)?></td>
					<td align="right"><?=$member_max ? number_format($member_max) . '��' : '���¤ʤ�'?></td>
					<td align="center"><?=$reorder_flag ? '�ƿ���' : ($fetch->jb_status == $g_job_status['������'] ? '����' : '-')?></td>
					<td>
<?
	if ($fetch->jb_status == $g_job_status['������'] || $reorder_flag) {
?>
						<input type="button" value="��ǧ" class="form" onclick="on_recognition(<?=$fetch->jb_job_id?>)">
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
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
