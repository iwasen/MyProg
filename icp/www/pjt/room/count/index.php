<?
/******************************************************
' System :ICP会議室ページ
' Content:発言集計画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/room.php");
include("$inc/variable.php");
include("$inc/crypt.php");
include("$inc/select.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>ログ選択</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#b8e8e9" align="center" width="100%">
		<tr>
			<td width="50%" class="text12b">発言集計</td>
			<td width="50%" align="right"><input type="button" value="ダウンロード" class="button" onclick="location.href='download.php'"></td>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td valign="middle">

			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
			<tr>
				<td>
						<table border="1" cellspacing="0" cellpadding="2" width="100%" class="text12">
						<tr bgcolor="#b8e8e9">
							<th width="10%">ID</th>
							<th width="30%">ニックネーム</th>
							<th width="30%">総発言</th>
							<th width="30%">発言日数</th>
						</tr>
<?
$sb1 = "SELECT rm_member_id,COUNT(*) AS s_count1,COUNT(DISTINCT(date_trunc('day', rm_date))) AS s_count2"
		. " FROM l_remark"
		. " WHERE rm_job_id=$job_id AND rm_remark_type=3 AND rm_status=1"
		. " GROUP BY rm_member_id";
$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,s_count1,s_count2"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " LEFT JOIN ($sb1) sb1 ON rm_member_id=jm_member_id"
		. " WHERE jm_job_id=$job_id AND jm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
						<tr align="center" bgcolor="<?=$i % 2 == 0 ? '#ffffff' : '#f7fdfd'?>">
							<td><?=$fetch->pm_member_id?></td>
							<td><?=htmlspecialchars($fetch->pm_nickname)?></td>
							<td><?=number_format($fetch->s_count1)?></td>
							<td><?=number_format($fetch->s_count2)?></td>
						</tr>
<?
}
?>
						</table>

						<div align="right"><input type="button" value="ダウンロード" class="button" onclick="location.href='download.php'"></div>
				</td>
			</tr>
			</table>

			</td>
		</tr>
		</table></td>
</tr>
</table>

</body>

</html>
