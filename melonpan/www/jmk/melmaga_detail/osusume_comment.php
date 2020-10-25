<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:オススメコメント表示
'******************************************************/

if (!$melonpai_id) {
	$where = "";
} else {
	$where = " AND OL.melonpai_id='$melonpai_id'";
}

$sql = "SELECT OL.mag_id, MP.melonpai_name"
	. ", TO_CHAR(OL.sel_date, 'YYYY/MM/DD') as sel_date"
	. ", TO_CHAR(OL.date_from, 'YYYY/MM/DD') as date_from"
	. ", TO_CHAR(OL.date_to, 'YYYY/MM/DD') as date_to"
	. ", OL.reason"
	. " FROM T_OSUSUME_LIST OL, M_MELONPAI MP"
	. " WHERE OL.mag_id='$mag_id'" . $where . " AND to_char(OL.sel_date, 'YYYY/MM/DD')='$sel_date' AND OL.melonpai_id=MP.melonpai_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>オススメ選定詳細</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="midashi14">■おすすめ詳細<br>
      <table width="400" border="1" cellspacing="0" cellpadding="2" bgcolor="#Fccffff" align="center">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$melonpai_id = $fetch->melonpai_id;
	$sel_date = $fetch->sel_date;
	$date_from = $fetch->date_from;
	$date_to = $fetch->date_to;
	$reason = $fetch->reason;
	$melonpai_name = $fetch->melonpai_name;
?>
				<tr>
					<td width="120" bgcolor="#c0e0e0">メルマガID</td>
					<td><?= $mag_id ?></td>
				</tr>
				<tr>
					<td bgcolor="#c0e0e0">選定日</td>
					<td><?= $sel_date ?></td>
				</tr>
				<tr>
					<td bgcolor="#c0e0e0">有効期間</td>
					<td><?= $date_from ?>〜<?= $date_to ?></td>
				</tr>
				<tr>
					<td bgcolor="#c0e0e0">メルマガナビ</td>
					<td><?= htmlspecialchars($melonpai_name) ?></td>
			  </tr>
			  <tr>
					<td bgcolor="#c0e0e0">おすすめ理由</td>
					<td><?= htmlspecialchars($reason) ?></td>
	 		 </tr>
	    </table>
<?
}
?>
  	</td>
	</tr>
</table>
<input type="button" value=" 閉じる " onclick="window.close()">
</form>
</center>
</body>
</html>

