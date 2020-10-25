<?php
	include("../inc/com_db_func.inc");
/*
	include("include/error.inc");
	include("include/nv_mod002.phpm");
	include("include/nv_mod001.phpm");
*/


	$sql = "select * from M_MAG_BG order by bg_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}

	$title_color[0] = "#336600";
	$title_color[1] = "#669900";
	$bg_color[0] = "#FFFFCC";
	$bg_color[1] = "#FFFFFF";
	$tcnt=0;
	$bgcnt=0;

	include("tmpl_html/feeling-list-header.html");

	$catg_list0 = "<TABLE WIDTH=\"660\"><TR>\n";
	$catg_list1 = "</TR></TABLE>\n";
	$catg = array();

	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		if ( ereg("000$",$resarray[0]) ) {
			$skey = sprintf("%05s",substr($resarray[0],0,2)*1000+1);
			$ekey = sprintf("%05s",$skey+998);
			$sql = "select count(*) from M_MAGAZINE where (mag_bg_1 between '$skey' and '$ekey' or mag_bg_2 between '$skey' and '$ekey')";
			$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
			$sql .= " and ( mag_pub_stop_flg <> '02' )";
			$sql .= " and ( publisher_id <> 0 )";
			$ret2 = db_exec($sql);
			$resnum= pg_fetch_array ($ret2, 0);
			
			if ( $tcnt == 4 ) {
				echo "</table>\n";
				echo "</td>\n";
				echo "<td width=\"330\" valign=\"top\">\n";
				echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"330\">\n";
				echo "<tr>\n";
			}

			echo "<td colspan=\"2\" bgcolor=\"".$title_color[$tcnt % 2]."\" valign=\"middle\" height=\"25\"> \n";
			echo "<div align=\"center\"><b>\n";
			echo "<A HREF=\"search_result.php?mode=feeling&mag_bg=".substr($resarray[0],0,2)."000\">\n";
			echo "<font size=\"2\" color=\"#FFCC00\">\n";
			echo trim($resarray[1])."</font></A>\n";
			echo "</b><font size=\"2\" color=\"#FFCC00\">\n";
			echo "(".$resnum[0].")";
			echo "</font></div>\n";
			echo "</td>\n";
			$tcnt++;
			$bgcnt = 0;

		} else {
			$sql = "select count(mag_id) from M_MAGAZINE where (mag_bg_1 = '$resarray[0]' or mag_bg_2 = '$resarray[0]')";
			$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
			$sql .= " and ( mag_pub_stop_flg <> '02' )";
			$sql .= " and ( publisher_id <> 0 )";
			$ret2 = db_exec($sql);
			$resnum= pg_fetch_array ($ret2, 0);
			echo "<tr bgcolor=\"".$bg_color[$bgcnt % 2]."\">\n";
			echo "<td width=\"24\" valign=\"top\">\n";
			echo "<input type=\"checkbox\" name=\"mag_bg_chk[]\" value=\"".$resarray[0]."\">\n";
			echo "</td>\n";
			echo "<td class=\"text9ptnoColor\" width=\"330\">\n";
			echo trim($resarray[1])." (".$resnum[0].")\n";
			echo "</td>\n</tr>\n";
			$bgcnt++;
		}
	}

	if (!$ret) {
		$error_msg = $cibi_error_msg_8;
		exit();
	}

	echo "<INPUT TYPE=\"hidden\" NAME=\"mode\" VALUE=\"feeling\">\n";

	include("tmpl_html/feeling-list-footer.html");

?>
