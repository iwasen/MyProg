<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:推薦文詳細
'******************************************************/

$sql = "SELECT com_title, com_text FROM T_CHOICE WHERE cho_id=$cho_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$com_title = $fetch->com_title;
	$com_text = $fetch->com_text;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>みつくろい依頼内容表示</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="300" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="midashi14">■みつくろい依頼内容閲覧<br>
      <table width="350" border="1" cellspacing="0" cellpadding="0" bgcolor="#Fccffff" align="center">
				<tr>
					<td>
	    			<table width="100%" border="0" cellspacing="1" cellpadding="3">
		  			  <tr>
		   			    <td bgcolor="#99bc66">タイトル：<?= htmlspecialchars($com_title); ?></td>
		  			  </tr>
		  			  <tr align="center">
  			    		<td class="pfont" align="left"><textarea  cols="45" rows="10"><?= htmlspecialchars($com_text); ?></textarea></td>
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

