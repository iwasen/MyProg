<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガ本文表示
'******************************************************/

$sql = "SELECt TS_subject, TS_body FROM T_TSUUSHIN WHERE TS_id='$id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$TS_subject = $fetch->ts_subject;
	$TS_body = $fetch->ts_body;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん通信本文表示</title>
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
    <td class="midashi14">■めろんぱん通信本文閲覧<br>
      <table width="550" border="1" cellspacing="0" cellpadding="0" bgcolor="#Fccffff" align="center">
				<tr>
					<td>
	    			<table width="100%" border="0" cellspacing="1" cellpadding="10">
		  			  <tr>
		   			    <td bgcolor="#FFCCCC">Subject：<?= htmlspecialchars($TS_subject); ?></td>
		  			  </tr>
		  			  <tr align="center">
  			    		<td class="pfont" align="left"><textarea  cols="72" rows="45"><?= htmlspecialchars($TS_body); ?></textarea></td>
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

