<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「プレゼント応募」管理
'******************************************************/

$title_text = $TT_present;
$title_color = $TC_OTHER;

$sub = "SELECT COUNT(PL_mail_adr) AS PL_count, PL_present_id FROM T_PRESENT_LIST GROUP BY PL_present_id";
$sql = "SELECT PD_present_id"
	. ", " . sql_number(PL_count)
	. ", PD_present_type"
	. ", PD_present_name"
	. ", TO_CHAR(PD_campaign_start, 'YYYY/MM/DD') AS PD_campaign_start"
	. ", TO_CHAR(PD_campaign_end, 'YYYY/MM/DD') AS PD_campaign_end"
	. ", TO_CHAR(PD_insert_start, 'YYYY/MM/DD') AS PD_insert_start"
	. ", TO_CHAR(PD_insert_end, 'YYYY/MM/DD') AS PD_insert_end"
	. ", TO_CHAR(PD_lottery_date, 'YYYY/MM/DD') AS PD_lottery_date"
	. ", PD_status"
	. " FROM M_PRESENT_DATA LEFT JOIN (" . $sub . ") as CT ON PD_present_id=PL_present_id"
	. " ORDER BY PD_present_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
		<td>■プレゼント登録一覧</td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='present1.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=2 width='100%'>
	<tr bgcolor="#ffcc99">
		<th align="center"><nobr>ＩＤ</nobr></th>
		<th align="center"><nobr>タイプ</nobr></th>
		<th align="center"><nobr>プレゼント名</nobr></th>
		<th align="center"><nobr>応募券挿入期間</nobr></th>
		<th align="center"><nobr>応募受付期間</nobr></th>
		<th align="center"><nobr>抽選日</nobr></th>
		<th align="center"><nobr>応募件数</nobr></th>
		<th align="center"><nobr>抽選</nobr></th>
	</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$present_id = $fetch->pd_present_id;
	$present_type = $fetch->pd_present_type;
	$present_name = $fetch->pd_present_name;
	$campaign_start = $fetch->pd_campaign_start;
	$campaign_end = $fetch->pd_campaign_end;
	$insert_start = $fetch->pd_insert_start;
	$insert_end = $fetch->pd_insert_end;
	$lottery_date = $fetch->pd_lottery_date;
	$count = $fetch->pl_count;
	$status = $fetch->pd_status;
	if ($present_type == 'A') {
		$present_type = "新規";
	} elseif ($present_type == 'B'){
		$present_type = "既存";
	}
	if ($status == '0') {
		$status = "未抽選";
	} elseif ($status == '1') {
		$status = "<a href='tosen_info.php?present_id=" . $present_id . "'>抽選済</a>";
	}
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><a href='present2.php?present_id=<?= $present_id ?>'><nobr><?= $present_id ?></nobr></a></td>
		<td align="center"><nobr><?= $present_type ?></nobr></td>
		<td align="left"><a href='../../melonpa/mag-present.php?opt_present_id=<?= $present_id ?>' target="_blank" ><?= htmlspecialchars($present_name) ?></a></td>
		<td align="center"><?= $insert_start ?>〜<?= $insert_end ?></td>
		<td align="center"><?= $campaign_start ?>〜<?= $campaign_end ?></td>
		<td align="center"><?= $lottery_date ?></td>
		<td align="right"><nobr><?= number_format($count) ?></nobr></td>
		<td align="center"><nobr><?= $status ?></nobr></td>
	</tr>
<?php
$line++;
}

?>
	</tr>
</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
