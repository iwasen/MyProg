<?php
/****************************************************************
 Main
****************************************************************/
db_conxxxt();
if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
include("tmpl_html/atri-header.html");

$sql = "select * from M_RIBAOPTIN where mag_id = '$mag_id'";
$stmt = ociparse($gConn,$sql);
OCIDefinebyname($stmt, "OPT_ID", &$opt_id);
OCIDefinebyname($stmt, "AGE", &$age);
OCIDefinebyname($stmt, "SEX", &$sex);
OCIDefinebyname($stmt, "MRG", &$mrg);
OCIDefinebyname($stmt, "OCP", &$ocp);
OCIDefinebyname($stmt, "ARA", &$ara);
OCIDefinebyname($stmt, "EX", &$ex);
$ret = ociexecute($stmt);

if (!$ret) {
	ocifreestatement($stmt);
	$p_titl = "りばっチャ属性設定エラー";
	$error_msg = "指定されたメルマガが見つかりません";
	include("tmpl_html/atri-error.html");
	exit();
}
OCIFetch($stmt);

if ($age != "") {
	$age_all = split("::",$age);
}
if ($sex != "") {
	$sex_all = split("::",$sex);
}
if ($mrg != "") {
	$mrg_all = split("::",$mrg);
}
if ($ocp != "") {
	$ocp_all = split("::",$ocp);
}
if ($ara != "") {
	$ara_all = split("::",$ara);
}

$male = 0;
$female = 0;
if (count($sex_all) != 0) {
	while (list($key,$val) = each($sex_all)) {
		if ($val == 1) { $male = 1;}
		if ($val == 2) { $female = 1;}
	}
	if ($male == 1) {
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"1\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"1\">\n";
	}
		print "男性向け\n<BR>";
	if ($female == 1) {
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"2\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"2\">\n";
	}
		print "  女性向け \n";
} else {
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"1\">\n";
		print "男性向け\n<BR>";
		print "<input type=\"checkbox\" name=\"sex[]\" value=\"2\">\n";
		print "  女性向け \n";
}
?>

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
if (count($ara_all) != 0) {
	while ( ocifetchinto($stmt, &$resarray) ) {
		$flg = 0;
		$ara_all_tmp = $ara_all;
		while (list($key,$val) = each($ara_all_tmp)) {
			if ($val == $resarray[0]) { $flg = 1;}
		}
			if ($flg == 1) {
				print "<input type=\"checkbox\" name=\"ara[]\" value=\"$resarray[0]\" checked>$resarray[1] \n";
			} else {
				print "<input type=\"checkbox\" name=\"ara[]\" value=\"$resarray[0]\">$resarray[1]\n";
			}
			echo "<BR>\n";
	}
} else {
	while ( ocifetchinto($stmt, &$resarray) ) {
		print "<input type=\"checkbox\" name=\"ara[]\" value=\"$resarray[0]\">$resarray[1] \n";
		print "<BR>\n";
	}
}

?>

</font></td>
<td rowspan="5"> <font size="2">

<?php
$sql = "select occup_id,occup from M_MAG_OCCUP order by occup_id";
$stmt = ociparse($gConn,$sql);
//OCIDefinebyname($stmt, "OCCUP_ID", &$occup_id);
//OCIDefinebyname($stmt, "OCCUP", &$occup);
$ret = ociexecute($stmt);
//OCIFetch($stmt);
if (count($ocp_all) != 0) {
	while ( ocifetchinto($stmt, &$resarray) ) {
		$flg = 0;
		$ocp_all_tmp = $ocp_all;
		while (list($key,$val) = each($ocp_all_tmp)) {
			if ($val == $resarray[0]) { $flg = 1;}
		}
			if ($flg == 1) {
				print "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\" checked>$resarray[1] \n";
			} else {
				print "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\">$resarray[1]\n";
			}
			echo "<BR>\n";
	}
} else {
	while ( ocifetchinto($stmt, &$resarray) ) {
		print "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\">$resarray[1] \n";
		print "<BR>\n";
	}
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
<?php

$nomrg = 0;
$mrged = 0;
if (count($mrg_all) != 0) {
	while (list($key,$val) = each($mrg_all)) {
		if ($val == 1) { $nomrg = 1;}
		if ($val == 2) { $mrged = 1;}
	}
	if ($nomrg == 1) {
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"1\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"1\">\n";
	}
		print "未婚</font> <br>\n";
	if ($mrged == 1) {
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"2\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"2\">\n";
	}
		print "既婚</font> \n";
} else {
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"1\">\n";
		print "未婚</font> <br>\n";
		print "<font size=\"2\"> \n";
		print "<input type=\"checkbox\" name=\"mrg[]\" value=\"2\">\n";
		print "既婚</font></p> \n";
}
?>

</TD>
</TR>
<TR align=left valign=top>
<td bgcolor="#336600"><font size="2" color="#006600"><b><font color="#FFFFFF">■年代</font></b></font></td>
</TR>
<TR align=left valign=top>
<TD width=120 bgcolor="#FFFFFF"><font size="2">

<?php
$age10 = 0;
$age20 = 0;
$age30 = 0;
$age40 = 0;
$age50 = 0;
$age60 = 0;
if (count($age_all) != 0) {
	while (list($key,$val) = each($age_all)) {
		if ($val == 10) { $age10 = 1;}
		if ($val == 20) { $age20 = 1;}
		if ($val == 30) { $age30 = 1;}
		if ($val == 40) { $age40 = 1;}
		if ($val == 50) { $age50 = 1;}
		if ($val == 60) { $age60 = 1;}
	}
	if ($age10 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"10\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"10\">\n";
	}
		print "10代 <BR>\n";
	if ($age20 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"20\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"20\">\n";
	}
		print "20代 <BR>\n";
	if ($age30 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"30\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"30\">\n";
	}
		print "30代 <BR>\n";
	if ($age40 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"40\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"40\">\n";
	}
		print "40代 <BR>\n";
	if ($age50 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"50\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"50\">\n";
	}
		print "50代 <BR>\n";
	if ($age60 == 1) {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"60\" checked>\n";
	} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"60\">\n";
	}
		print "60代以上 <BR>\n";
} else {
		print "<input type=\"checkbox\" name=\"age[]\" value=\"10\">\n";
		print "10代 <BR>\n";
		print "<input type=\"checkbox\" name=\"age[]\" value=\"20\">\n";
		print "20代 <BR>\n";
		print "<input type=\"checkbox\" name=\"age[]\" value=\"30\">\n";
		print "30代 <BR>\n";
		print "<input type=\"checkbox\" name=\"age[]\" value=\"40\">\n";
		print "40代 <BR>\n";
		print "<input type=\"checkbox\" name=\"age[]\" value=\"50\">\n";
		print "50代 <BR>\n";
		print "<input type=\"checkbox\" name=\"age[]\" value=\"60\">\n";
		print "60代以上 \n";
}
?>
</font>
<input type="hidden" name="mode" value="reader">

<?php
	include("tmpl_html/atri-footer.html");
?>
