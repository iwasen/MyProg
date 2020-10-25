<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガ本文表示
'******************************************************/

if ($body) {
	$sql = "SELECt mag_titl, mag_body FROM T_BODY WHERE index_id='$body'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_title = $fetch->mag_titl;
		$mag_body = $fetch->mag_body;
	}
} elseif ($backnum) {
	$sql = "SELECT mag_titl, mag_body FROM L_BACKNUMBER WHERE index_id='$backnum'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_title = $fetch->mag_titl;
		$mag_body = $fetch->mag_body;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>メルマガ本文表示</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
		</td>
	</tr>
  <tr>
    <td class="midashi14">■メルマガ本文閲覧<br>
      <table width="550" border="1" cellspacing="0" cellpadding="0" bgcolor="#Fccffff" align="center">
				<tr>
					<td>
	    			<table width="100%" border="0" cellspacing="1" cellpadding="10">
		  			  <tr>
		   			    <td bgcolor="#FFCCCC">Subject：<?= htmlspecialchars($mag_title); ?></td>
		  			  </tr>
		  			  <tr align="center">
  			    		<td class="pfont" align="left"><textarea  cols="72" rows="45"><?= htmlspecialchars($mag_body); ?></textarea></td>
  			 		 </tr>
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

