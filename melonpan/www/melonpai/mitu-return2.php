<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>みつくろい依頼</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
</head>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600">
<p class="text01">▼以下の内容を送信します。よろしければ送信ボタンを押してください。</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<p><FONT COLOR="#99CCFF">■</FONT><FONT COLOR="#003399"><B>みつくろい返信【確認】</B></FONT></p>
<table width="440" border="0" cellspacing="0" cellpadding="5" bgcolor="#EEF1FF">
<tr>
<td>
<?php
		print "<p>依頼内容：".htmlspecialchars(stripcslashes($com_title))."　(<a href=\"mailto:".trim($send_mail_addr)."\">";
		print trim($send_mail_addr)."</a>)<br>\n";
		print htmlspecialchars(stripcslashes($com_text))."</p>\n";
?>

</td>
</tr>
</table>
<div align="left"><hr width="440"></div>
<form name="form1" action="mitu-send.php" method="post">
<?php
		print "<input type=\"hidden\" name=\"melonpai_id\" value=\"$melonpai_id\">\n";
		print "<input type=\"hidden\" name=\"melonpai_pswd\" value=\"$melonpai_pswd\">\n";
		print "<input type=\"hidden\" name=\"melonpai_name\" value=\"$melonpai_name\">\n";
		print "<input type=\"hidden\" name=\"cho_id\" value=\"$cho_id\">\n";
		print "<input type=\"hidden\" name=\"send_mail_addr\" value=\"".trim($send_mail_addr)."\">\n";
		print "<input type=\"hidden\" name=\"subject\" value=\"".stripcslashes($subject)."\">\n";
		print "<input type=\"hidden\" name=\"header\" value=\"".stripcslashes($header)."\">\n";
		print "<input type=\"hidden\" name=\"body\" value=\"".stripcslashes($body)."\">\n";
		print "<input type=\"hidden\" name=\"footer\" value=\"".stripcslashes($footer)."\">\n";
		print "<input type=\"hidden\" name=\"page_no\" value=\"$page_no\">\n";

?>
<p>SUBJECT： <?php echo htmlspecialchars(stripcslashes($subject)); ?>
<table width="440" border="0" cellspacing="0" cellpadding="5">
<tr bgcolor="#F0F0F0"> <td>
<?php echo ereg_replace("\n","<br>",htmlspecialchars(stripcslashes($header))); ?><br><br>
<?php echo ereg_replace("\n","<br>",htmlspecialchars(stripcslashes($body))); ?><br>
<?php echo ereg_replace("\n","<br>",htmlspecialchars(stripcslashes($footer))); ?><br><br></td>
</tr>
</table>
<p>　 </p>
<table><tr><td>
<input type="submit" name="Submit" value="　送　信　">
</td><td>
<input type="button" value="　戻　る　" onClick="history.back()">
</td></tr>
</table>
</p>
</form>
</td>
</tr>
</table>
<p>　</p>
</body>
</html>
