<?php
	include("../inc/com_db_func.inc");
/*
	include("include/error.inc");
	include("include/nv_mod002.phpm");
	include("include/nv_mod001.phpm");
*/

Function get_num($where_target,$param) {
	global $gConn;
	$sql = "select count(*) from M_MAGAZINE where $where_target = '$param'";
	$ret = db_exec($sql);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	$resnum= pg_fetch_array ($ret, 0);
	return $resnum[0];
}

	include("tmpl_html/muke-list-header.html");
?>
<input type="checkbox" name="sex[]" value="1">
男性向け
<?php
	echo "(".get_num("mag_reader_sex",1).")";
?>		
<BR>
<input type="checkbox" name="sex[]" value="2">
			  女性向け
<?php
	echo "(".get_num("mag_reader_sex",2).")";
?>		
</TD>
<td rowspan="3" bgcolor="#FFFFCC">
<p><font size="2">
<?php
	$sql = "select area_id,area from M_MAG_AREA order by area_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		$sql = "select count(*) from M_MAGAZINE where mag_reader_area = '$resarray[0]'";
		$result = db_exec($sql);
		$res_area= pg_fetch_array ($result, 0);
		echo "<input type=\"checkbox\" name=\"area[]\" value=\"$resarray[0]\">$resarray[1] ($res_area[0])\n";
		echo "<BR>\n";
	}

?>
</font></p>
</td>
<td rowspan="3" bgcolor="#FFFFCC"> <font size="2">
<?php
	$sql = "select occup_id,occup from M_MAG_OCCUP order by occup_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		$sql = "select count(*) from M_MAGAZINE where mag_reader_occup = '$resarray[0]'";
		$result = db_exec($sql);
		$res_occup= pg_fetch_array ($result, 0);
		echo "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\">$resarray[1] ($res_occup[0])\n";
		echo "<BR>\n";
	}

?>
</TR>
<?php
	include("tmpl_html/muke-list-nendai.html");
?>
<TR align=left valign=top>
<TD width=180 bgcolor="#FFFFCC"><FONT size="2">
<input type="checkbox" name="age[]" value="10">
10代
<?php
	echo "(".get_num("mag_reader_age",10).")";
?>
<BR>

<input type="checkbox" name="age[]" value="20">
20代
<?php
	echo "(".get_num("mag_reader_age",20).")";
?>
<BR>

<input type="checkbox" name="age[]" value="30">
30代
<?php
	echo "(".get_num("mag_reader_age",30).")";
?>
<BR>

<input type="checkbox" name="age[]" value="40">
40代
<?php
	echo "(".get_num("mag_reader_age",40).")";
?>
<BR>

<input type="checkbox" name="age[]" value="50">
50代
<?php
	echo "(".get_num("mag_reader_age",50).")";
?>
<BR>

<input type="checkbox" name="age[]" value="60">
60代以上
<?php
	echo "(".get_num("mag_reader_age",60).")";
?>

<input type="hidden" name="mode" value="reader">

<?php
	include("tmpl_html/muke-list-footer.html");
?>
