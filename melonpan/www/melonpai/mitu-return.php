<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>みつくろい依頼</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
<Script Language="JavaScript">
	function text_input() {
		if (window.parent.bookmark.document.form2) {
			if (window.parent.bookmark.document.form2.subject.value != "") {
				window.parent.input.document.form1.subject.value = window.parent.bookmark.document.form2.subject.value;
			}
			if (window.parent.bookmark.document.form2.body.value != "") {
				window.parent.input.document.form1.body.value = window.parent.bookmark.document.form2.body.value;
			}
			if (window.parent.bookmark.document.form2.header.value != "") {
				window.parent.input.document.form1.header.value = window.parent.bookmark.document.form2.header.value;
			}
			if (window.parent.bookmark.document.form2.footer.value != "") {
				window.parent.input.document.form1.footer.value = window.parent.bookmark.document.form2.footer.value;
			}
		}
	}
</Script>

</head>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600" onLoad="text_input()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<p><FONT COLOR="#99CCFF">■</FONT><FONT COLOR="#003399"><B>みつくろい返信</B></FONT></p>
<table width="440" border="0" cellspacing="0" cellpadding="5" bgcolor="#EEF1FF">
<tr>
<td>
<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("inc/mex_passchk.inc");

	mex_passchk($conn,$melonpai_id,$melonpai_pswd);
/* **
if ($job_flg == "add") {
	$sql = "SELECT mag_nm FROM M_MAGAZINE WHERE mag_id = '$input_mag'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);
	OCIFreestatement($stmt);
}
*/
$sql = "select header_txt,footer_txt from m_recom_txt where melonpai_id = $melonpai_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$header = $fetch->header_txt;
	$footer = $fetch->footer_txt;
}

$sql = "SELECT melonpai_name FROM M_MELONPAI WHERE melonpai_id = $melonpai_id ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_name = $fetch->melonpai_name;
}
$sql = "SELECT * FROM T_CHOICE WHERE cho_id = $cho_id ";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cho_id = $fetch->cho_id;
	$reader_id = $fetch->reader_id;
	$com_title = $fetch->com_title;
	$com_text = $fetch->com_text;
	$send_mail_addr = $fetch->send_mail_addr;
	$melonpai_id = $fetch->melonpai_id;
	$category_id = $fetch->category_id;
	$category_name = $fetch->categiry_name;

	print "<p>依頼内容：".htmlspecialchars($com_title)."　(<a href=\"mailto:".trim($send_mail_addr)."\">";
	print trim($send_mail_addr)."</a>)</p>\n";
	print "<p>".htmlspecialchars($com_text)."</p>\n";
	print "</td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "<p><FONT COLOR=\"#003399\">▼返信メール入力欄</FONT></p>\n";
	print "<form name=\"form1\" method=post action=\"mitu-return2.php\">\n";
	print "<input type=\"hidden\" name=\"melonpai_id\" value=\"$melonpai_id\">\n";
	print "<input type=\"hidden\" name=\"melonpai_pswd\" value=\"$melonpai_pswd\">\n";
	print "<input type=\"hidden\" name=\"melonpai_name\" value=\"$melonpai_name\">\n";
	print "<input type=\"hidden\" name=\"page_no\" value=\"$page_no\">\n";
	print "<input type=\"hidden\" name=\"cho_id\" value=\"$cho_id\">\n";
	print "<input type=\"hidden\" name=\"job_flg\" value=\"kakunin\">\n";
	print "<input type=\"hidden\" name=\"send_mail_addr\" value=\"".trim($send_mail_addr)."\">\n";
	print "<input type=\"hidden\" name=\"com_text\" value=\"$com_text\">\n";
	print "<input type=\"hidden\" name=\"com_title\" value=\"$com_title\">\n";
	print "SUBJECT： \n";
	print "<input type=\"text\" name=\"subject\" size=\"40\" value=\"";
	print "Re: ".$com_title."\"><br>\n";
	print "<p>ヘッダー：<br>\n";
	print "<textarea name=\"header\" rows=\"3\" cols=\"60\">\n";
	print $header;
	print "</textarea></p>\n";
	print "<p> 本文：<br>\n";
	print "<textarea name=\"body\" rows=\"25\" cols=\"60\">\n";
	print "</textarea></p>\n";
	print "<p>フッター：<br>\n";
	print "<textarea name=\"footer\" rows=\"3\" cols=\"60\">\n";
	print $footer;
	print "</textarea>\n";
	print "</p>\n";
	print "<p> \n";
	print "<input type=\"submit\" name=\"Submit\" value=\"　確　認　\">　\n";
	print "<input type=\"reset\" value=\"リセット\">\n";
	print "</p>\n";
	print "</form>\n";
}
?>
</td>
</tr>
</table>
</body>
</html>
