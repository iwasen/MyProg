<?
$inc = '../inc';
include("$inc/common.php");
include("$inc/database.php");

// グループ別目標数取得
$sql = "SELECT gr_group_id,gr_area_id,gr_nendai,gr_mokuhyo_num FROM m_group";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mokuhyo_ary[$fetch->gr_group_id] = $fetch->gr_mokuhyo_num;
	$group_ary[$fetch->gr_area_id][$fetch->gr_nendai] = $fetch->gr_group_id;
}

// グループ別回答者数取得
$sql = "SELECT au_ans_group_id,COUNT(*) AS answer_count FROM t_ans_user GROUP BY au_ans_group_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$answer_ary[$fetch->au_ans_group_id] = $fetch->answer_count;
}

// エリア一覧取得
$sql = "SELECT ar_area_id,ar_area_name FROM m_area ORDER BY ar_area_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$area_ary[$fetch->ar_area_id] = $fetch->ar_area_name;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>ジョブ管理</title>
<link rel="stylesheet" type="text/css" href="admin.css">
<style type="text/css">
<!--
table.list th.head{
	background-color: #9bdeff;
}
table.list tr.l0{
	background-color: #ffffff;
}
table.list tr.l1{
	background-color: #e3e3e3;
}
-->
</style>
</head>

<body>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666">タウンガイドＷｅｂアンケート</td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="monitor">
	<tr onclick="location.reload()">
		<td align="center"><nobr>ジョブ管理</nobr></td>
	</tr>
</table>
<hr class="header">
<center>
<form>
	<table border=0 cellspacing=0 cellpadding=0 width='85%'>
		<tr>
			<td class="m0">■エリア別アンケート回答者数一覧</td>
			<td class="m0" align="right"><input type="button" value="CSVダウンロード" onclick="location.href='csv_download.php'"></td>
		</tr>
		<tr>
			<td colspan="2">
<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list">
<tr>
	<th rowspan="2" class="head">ID</th>
	<th rowspan="2" class="head">エリア名</th>
	<th colspan="3" class="head">２０代</th>
	<th colspan="3" class="head">３０代</th>
	<th colspan="3" class="head">４０代</th>
	<th colspan="3" class="head">５０代</th>
	<th colspan="3" class="head">６０代</td>
</tr>
<tr>
	<th class="head">回答数</th>
	<th class="head">目標数</th>
	<th class="head">不足数</th>
	<th class="head">回答数</th>
	<th class="head">目標数</th>
	<th class="head">不足数</th>
	<th class="head">回答数</th>
	<th class="head">目標数</th>
	<th class="head">不足数</th>
	<th class="head">回答数</th>
	<th class="head">目標数</th>
	<th class="head">不足数</th>
	<th class="head">回答数</th>
	<th class="head">目標数</th>
	<th class="head">不足数</th>
</tr>
<?
foreach ($area_ary as $area_id => $area_name) {
?>
<tr align="center" class="<?=$area_id % 2 ? 'l0' : 'l1'?>">
 <th><?=$area_id?></th>
 <th><?=$area_name?></th>
<?
	for ($nendai = 20; $nendai <= 60; $nendai += 10) {
		$group_id = $group_ary[$area_id][$nendai];
?>
 <td><?=number_format($answer_ary[$group_id])?></td>
 <td><?=number_format($mokuhyo_ary[$group_id])?></td>
 <td><?=number_format($mokuhyo_ary[$group_id] - $answer_ary[$group_id])?></td>
<?
	}
?>
</tr>
<?
}
?>
</table>
			</td>
		</tr>
	</table>

	<br>
</form>
</center>

<hr class="footer">
<div align="center">Copyright(c) 2005 xx xxxxx Inc. All rights reserved.</div>
</body>
</html>