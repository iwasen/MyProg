<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガバックナンバー表示
'******************************************************/

$sql = "SELECT mag_id, mag_titl, mag_body, to_char(modefy_dt, 'YYYY/MM/DD') as modefy_dt  FROM L_BACKNUMBER WHERE index_id=" . sql_number($index_id);
$result = db_exec($sql);
if (!pg_numrows($result)) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
</head>
<body>
<center>
<?php
	print "<br>バックナンバーが見つかりません。<br><br>";
?>
<input type="button" value="　閉じる　" onclick="window.close()"></form>
</center>
</body>
</html>
<?
exit;
} else {
	$fetch = pg_fetch_object($result, 0);
	$mag_id = $fetch->mag_id;
	$rowid = $fetch->rowid;
	$mag_titl = $fetch->mag_titl;
	$mag_body = $fetch->mag_body;
	$modefy_dt = $fetch->modefy_dt;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>バックナンバー表示</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td colspan=2 align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
		</td>
	</tr>
  <tr>
    <td valign="top" class="midashi14">■メルマガ本文閲覧<br>
      <table width="550" border="1" cellspacing="0" cellpadding="0" bgcolor="#Fccffff" align="center">
				<tr>
					<td>
	    			<table width="100%" border="0" cellspacing="1" cellpadding="4">
		  			  <tr>
		   			    <td bgcolor="#FFCCCC">Subject：<?= htmlspecialchars($mag_titl); ?>
									<div align="right"><font size=-1>配信日：<?= $modefy_dt ?></font></div></td>
		  			  </tr>
		  			  <tr align="center">
  			    		<td class="pfont" align="left"><textarea  cols="72" rows="45"><?= htmlspecialchars($mag_body); ?></textarea></td>
  			 		 </tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top"><br>
			<table>
				<tr>
					<td>
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<th>backnumber</th>
							</tr>
<?php
$sql = "SELECT index_id, to_char(MODEFY_DT ,'YYYY/MM/DD') as modefy_dt FROM L_BACKNUMBER"
	. " WHERE mag_id = '$mag_id' ORDER BY modefy_dt DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
	return;
} else {
	for( $i = 0 ; $i < $nrow ; $i++ ){
		$fetch = pg_fetch_object($result, $i);
		$index_id = $fetch->index_id;
		$modefy_dt = $fetch->modefy_dt;
?>
							<tr>
								<td><a href='mag_backnumber.php?index_id=<?= urlencode($index_id) ?>'><?= $modefy_dt ?></td>
							</tr>
<?
	}
}
?>
						</table>
					</td>
				</tr>
	    </table>
  	</td>
	</tr>
</table>
<input type="button" value=" 閉じる " onclick="window.close()">
</form>
</center>
</body>
</html>

