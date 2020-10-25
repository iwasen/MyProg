<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・実施ジョブ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/icp_db.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー実施ジョブ', BACK_TOP);

$sql = "SELECT ppj_room_name,ppj_purpose,ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$icp_job_id = $fetch->ppj_icp_job_id;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function theme_detail(remark_id) {
	window.open("theme_detail.php?remark_id=" + remark_id, "theme_detail", "resizable=yes,scrollbars=yes");
}
function to_room() {
	window.open("icp_room.php?pjt_id=<?=$pjt_id?>", "room");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($fetch->ppj_room_name)?></td>
	</tr>
</table>
</form>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" colspan=3>■目的</td>
	</tr>
	<tr>
		<td class="n1" colspan=3><?=nl2br(htmlspecialchars($fetch->ppj_purpose))?></td>
	</tr>
	<tr>
		<td class="m0" colspan=5>■話題の履歴</td>
	</tr>
	<tr>
		<td class="m1" width="15%" align="center">SEQ</td>
		<td class="m1" width="25%" align="center">テーマ</td>
		<td class="m1" width="20%" align="center">投げかけ日</td>
	</tr>
<?
if ($icp_job_id) {
	$sql = "SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject"
			. " FROM l_remark"
			. " WHERE rm_job_id=$icp_job_id AND rm_disp_type=1 AND rm_status=1 AND rm_marking_id IS NOT NULL"
			. " ORDER BY rm_seq_no";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
	<tr>
		<td class="m1" width="20%" align="center"><?=$i + 1?></td>
		<td class="n1" width="60%"><a href="javascript:theme_detail(<?=$fetch->rm_remark_id?>)"><?=htmlspecialchars($fetch->rm_subject)?></a></td>
		<td class="n1" width="20%" align="center"><?=format_date($fetch->rm_date)?></td>
	</tr>
<?
	}
}
?>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" colspan=3>■発言集計</td>
		<td class="m0" align="right">
<?
if ($icp_job_id) {
?>
			<input type="button" value="ダウンロード">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1" width="25%" align="center">ID</td>
		<td class="m1" width="25%" align="center">ニックネーム</td>
		<td class="m1" width="25%" align="center">総発言</td>
		<td class="m1" width="25%" align="center">日別カウント数</td>
	</tr>
<?
if ($icp_job_id) {
	$sb1 = "SELECT rm_member_id,COUNT(*) AS s_count1,COUNT(DISTINCT(date_trunc('day', rm_date))) AS s_count2"
			. " FROM l_remark"
			. " WHERE rm_job_id=$icp_job_id AND rm_remark_type=3 AND rm_status=1"
			. " GROUP BY rm_member_id";
	$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,s_count1,s_count2"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " LEFT JOIN ($sb1) sb1 ON rm_member_id=jm_member_id"
			. " WHERE jm_job_id=$icp_job_id AND jm_status=1 AND pm_operator_flag=0"
			. " ORDER BY pm_member_id";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
	<tr>
		<td class="n1" align="center"><?=$fetch->pm_member_id?></td>
		<td class="n1" align="center"><?=htmlspecialchars($fetch->pm_nickname)?></td>
		<td class="n1" align="center"><?=number_format($fetch->s_count1)?></td>
		<td class="n1" align="center"><?=number_format($fetch->s_count2)?></td>
	</tr>
<?
	}
}
?>
</table>
</form>
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr align="right">
		<td>
<?
if ($icp_job_id) {
?>
			<input type="button" value="閲覧者の登録" onclick="location.href='regist_rom.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="会議室へ" onclick="to_room()">
<?
}
?>
		</td>
	</tr>
</table>
<br>
<div align="center">
	<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
