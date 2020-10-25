<?php

	db_conxxxt();
 	if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
	include("tmpl_html/atri-header.html");
?>
<input type="checkbox" name="sex[]" value="1">
男性向け<BR>
<input type="checkbox" name="sex[]" value="2">
女性向け
</TD>
<td rowspan="5" width="120"> <font size="2">

<?php
	$sql = "select area_id,area from m_mag_area order by area_id";
	$stmt = ociparse($gConn,$sql);
	$ret = ociexecute($stmt);

	if (!$ret) {
		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_6;
		db_logoff();
		exit();
	}
	while ( ocifetchinto($stmt, &$resarray) ) {
	echo "<input type=\"checkbox\" name=\"ara[]\" value=\"$resarray[0]\">$resarray[1] ";
	echo "<br>\n";
	}
?>

</font></td>
<td rowspan="5"> <font size="2">

<?php
	$sql = "select occup_id,occup from M_MAG_OCCUP order by occup_id";

	$stmt = ociparse($gConn,$sql);
	$ret = ociexecute($stmt);

	if (!$ret) {
		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_6;
		db_logoff();
		exit();
	}
	while ( ocifetchinto($stmt, &$resarray) ) {
		echo "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\">$resarray[1] \n";
		echo "<BR>\n";
	}

?>

</font>
</TR>
<TR align=left valign=top>
<TD bgcolor="#336600"><font size="2" color="#006600"><B><font color="#FFFFFF">■未既婚</font></B></font></TD>
</TR>
<TR align=left valign=top>
<TD width=120 bgcolor="#FFFFFF">
<p><font size="2">
<input type="checkbox" name="mrg[]" value="1">
未婚</font> <br>
<font size="2">
<input type="checkbox" name="mrg[]" value="2">
既婚</font> </p>
</TD>
</TR>
<TR align=left valign=top>
<td bgcolor="#336600"><font size="2" color="#006600"><b><font color="#FFFFFF">■年代</font></b></font></td>
</TR>
<TR align=left valign=top>
<TD width=120 bgcolor="#FFFFFF"><font size="2">

<input type="checkbox" name="age[]" value="10">
10代 <BR>
<input type="checkbox" name="age[]" value="20">
20代 <BR>
<input type="checkbox" name="age[]" value="30">
30代 <BR>
<input type="checkbox" name="age[]" value="40">
40代 <BR>
<input type="checkbox" name="age[]" value="50">
50代 <BR>
<input type="checkbox" name="age[]" value="60">
60代以上
</font>
<input type="hidden" name="mode" value="reader">

<?php
	include("tmpl_html/atri-footer.html");
?>
