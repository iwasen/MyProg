<?
/******************************************************
' System :ICPオペレータページ
' Content:プロジェクト一覧
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
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
					<td width="50%">■プロジェクト一覧</td>
					<td width="50%" align="right"><input type="button" value="プロジェクト新規登録" class="form" onclick="location.href='regist.php'"></td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="5%">pjt ID</th>
					<th width="20%">pjt名</th>
					<th width="20%">作成オペレータ名</th>
					<th width="20%">組織CD</th>
					<th width="20%">組織名</th>
					<th width="5%">job数</th>
					<th width="10%">状態</th>
				</tr>
<?
// プロジェクト一覧表示
$sql = "SELECT pj_pjt_id,pj_pjt_cd,pj_name,pj_status,pj_job_num,pj_operator_id,op_name1||op_name2 AS op_name,op_org_id,or_name"
		. " FROM t_pjt"
		. " JOIN t_operator ON op_operator_id=pj_operator_id"
		. " JOIN m_org ON or_org_id=op_org_id"
		. ($_SESSION[SCD]['admin_auth'] ? '' : " WHERE pj_operator_id=$g_operator_id")
		. " ORDER BY pj_pjt_id DESC";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr class="line<?=$i % 2?>">
					<td align="center"><?=$fetch->pj_pjt_id?></td>
					<td><a href="pjt_login.php?pjt_id=<?=$fetch->pj_pjt_id?>" title="このプロジェクトを別ウィンドウで表示します" target="<?=$fetch->pj_pjt_cd?>"><?=htmlspecialchars($fetch->pj_name)?></a>（<?=htmlspecialchars($fetch->pj_pjt_cd)?>）</td>
					<td><?=htmlspecialchars($fetch->op_name)?></td>
					<td align="center"><?=$fetch->op_org_id?></td>
					<td><?=htmlspecialchars($fetch->or_name)?></td>
					<td align="right"><?=number_format($fetch->pj_job_num)?></td>
					<td align="center">
<?
	// 稼動中のものだけ終了可能
	if ($fetch->pj_status == $g_pjt_status['稼動中']) {
?>
						<a href="pjt_end.php?pjt_id=<?=$fetch->pj_pjt_id?>" title="このプロジェクトを終了します"><?=decode_pjt_status($fetch->pj_status)?></a>
<?
	} else {
?>
						<?=decode_pjt_status($fetch->pj_status)?>
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
