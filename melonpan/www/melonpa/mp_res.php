<?php
	include("../inc/com_db_func.inc");
/*
	include("include/error.inc");
	include("include/nv_mod002.phpm");
	include("include/nv_mod001.phpm");
*/

	db_conxxxt();

/*
	$sql = "select distinct M_MAGAZINE.mag_id,
			M_MAGAZINE.mag_nm,
			M_MAGAZINE.mag_intro_short,
			M_MAGAZINE.mag_cycle,
			M_MAGAZINE.mag_catg_1,
			M_MAGAZINE.mag_hp,
			M_MAG_CATG.catg
		 from M_MAGAZINE,M_MAG_CATG
		 where (M_MAGAZINE.mag_catg_1
			 between $catg_id"."00 and $catg_id"."99
		 and M_MAGAZINE.mag_catg_1 = M_MAG_CATG.catg_id)
		 or (M_MAGAZINE.mag_catg_2
			 between $catg_id"."00 and $catg_id"."99
		 and M_MAGAZINE.mag_catg_2 = M_MAG_CATG.catg_id)
		 order by M_MAGAZINE.mag_id";
*/
	$sql = "select* from M_MAG_BG";
//	$sql = "select count(M_MAGAZINE.mag_reader_occup) from M_MAGAZINE group by M_MAGAZINE.mag_reader_occup";
	$stmt = ociparse($gConn,$sql);
	$ret = ociexecute($stmt);

	if (!$ret) {
		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_6;
//		include("tmpl_html/cibi_error.html");
//		include("tmpl_html/cibi_error.html");
		db_logoff();
		exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>メルマガサーチ結果</TITLE>
</HEAD>
<BODY bgcolor="#FFFFFF">
<?php
/*
	echo "<TABLE WIDTH=\"700\" BORDER=\"1\">\n";
	echo "<TR>\n";
	echo "<TH>Category</TH>\n";
	echo "<TH>タイトル</THD>\n";
	echo "<TH>メルマガID</TH>\n";
	echo "<TH>説明</TH>\n";
	echo "<TH>最終発行日</TH>\n";
	echo "<TH>更新頻度</TH>\n";
	echo "<TH>購読</TH>\n";
	echo "</TR>\n";
*/
$nrows = OCIFetchStatement ($stmt,$results);
if ($nrows > 0) {
   print "<TABLE BORDER=\"1\">\n";
   print "<TR>\n";
   while (list($key, $val) = each($results)) {
      print "<TH>$key</TH>\n";
   }
   print "</TR>\n";

   for ( $i = 0; $i < $nrows; $i++ ) {
      reset ($results);
      print "<TR>\n";
      while ( $column = each ($results) ) {
         $data = $column['value'];
         print "<TD>$data[$i]</TD>\n";
      }
      print "</TR>\n";
   }
   print "</TABLE>\n";
} else {
   echo "No data found<BR>\n";
}  
print "$nrows Records Selected<BR>\n";

/*
	while ( ocifetchinto($stmt, &$resarray) ) {
		echo "<TR>\n";
		echo "<TD>$resarray[6]</TD>\n";
		echo "<TD>$resarray[1]</TD>\n";
		echo "<TD>$resarray[0]</TD>\n";
		echo "<TD>$resarray[2]</TD>\n";
		echo "<TD>$resarray[3]</TD>\n";
		echo "<TD>$resarray[4]</TD>\n";
		echo "<TD>購読・解除</TD>\n";
		echo "</TR>\n";
	}
	echo "</TABLE>\n";
*/

//	include("tmpl_html/cibi_read.html");
	ocifreestatement($stmt);

	if (!$ret) {
		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_8;
//		include("tmpl_html/cibi_error.html");
		db_logoff();
		exit();
	}

	db_logoff();
?>
</BODY>
</HTML>
