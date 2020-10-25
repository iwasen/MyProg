<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:本登録承認処理
'******************************************************/

$title_text = $TT_hon_touroku;

$sql = "SELECT LB.mag_titl, LB.mag_body, to_char(LB.modefy_dt, 'YYYY/MM/DD hh24:mi:ss') as modefy_dt, MM.mail_add_publish"
	. " FROM L_BACKNUMBER LB, M_MAGAZINE MM"
	. " WHERE LB.mag_id='$mag_id' AND LB.mag_id=MM.mag_id"
	. " ORDER BY LB.modefy_dt DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function PagePrint() {
  if (confirm("最新３号分を印刷します。よろしいですか？")) {
    print();
  }
}
//-->
</SCRIPT>
</head>
<body onLoad="PagePrint();">

<form method="post" name="form1">
<?php
$first = true; $count = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_titl = $fetch->mag_titl;
	$mag_body = $fetch->mag_body;
	$modefy_dt = $fetch->modefy_dt;
	$mail_add_publish = $fetch->mail_add_publish;
	if ($first == true) {
		print "<div>";
		$first = false;
	} else {
		print "<div class='page'>";
	}
?>
<table>
	<tr><th align="left">Subject: <?= htmlspecialchars($mag_titl) ?></th></tr>
	<tr><th align="left">From: <?= htmlspecialchars($mail_add_publish) ?></th></tr>
	<tr><th align="left">Date: <?= $modefy_dt ?></th></tr>
</table>
<br><xmp><?= htmlspecialchars($fetch->mag_body) ?></xmp>
<hr color=#9fc741 size=3>
</div>
<?
	$count++;
	if ($count >= 3) {
		break;
	}
}
?>
</form>

</body>
</html>
