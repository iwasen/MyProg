<?php
	include("../inc/com_db_func.inc");

	$sql = "select * from M_MAG_CATG order by catg_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}

	include("tmpl_html/category-list-header.html");

	$catg_tr0 = "<TR align=left valign=top bgcolor=\"#FFCC00\">\n";
	$catg_td0 = "<TD width=330><B><FONT size=\"2\" color=\"#336600\">\n";
	$catg_td1 = "</FONT></B></TD>\n";
	$catg_tr1 = "</TR>\n";
	$catg_tr2 = "<TR align=left valign=top bgcolor=\"#FFFFCC\">\n";
	$catg_td2 = "<TD width=330><P class=sFontH><FONT size=\"2\">\n";
	$catg_td3 = "</FONT></P></TD>\n";
	$catg_tr3 = "</TR>\n";

	$catg = array();

	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		if ( ereg("00$",$resarray[0]) ) {
			$skey = substr($resarray[0],0,2)*100;
			$skey = sprintf("%04d", $skey);
			$ekey = $skey+99;
			$ekey = sprintf("%04d", $ekey);
			//******** 2000/12/23 okamoto add
			$sql2 = "select count(*) from M_MAGAZINE where ( mag_catg_1 between '$skey' and '$ekey' or mag_catg_2 between '$skey' and '$ekey' ) ";
			$sql2 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
			$sql2 .= " and ( mag_pub_stop_flg <> '02' )";
			$sql2 .= " and ( publisher_id <> 0 )";
			//*******************************
			$ret_num = db_exec($sql2);
			$resnum= pg_fetch_array ($ret_num, 0);
			$catg_title[substr($resarray[0],0,2)] = "<A HREF=\"search_result.php?mode=catg&catg_id=$resarray[0]&reader_id=$reader_id&reader_pswd=$reader_pswd\">".trim($resarray[1])."</A>($resnum[0])\n";
		} else {
			$catg[substr($resarray[0],0,2)] .= $resarray[1]." (".$resarray[2].")| \n";
		}
	}

 	echo $catg_tr0.$catg_td0.$catg_title["01"].$catg_td1.$catg_td0.$catg_title["11"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["01"].$catg_td3.$catg_td2.$catg["11"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["02"].$catg_td1.$catg_td0.$catg_title["12"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["02"].$catg_td3.$catg_td2.$catg["12"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["03"].$catg_td1.$catg_td0.$catg_title["13"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["03"].$catg_td3.$catg_td2.$catg["13"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["04"].$catg_td1.$catg_td0.$catg_title["14"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["04"].$catg_td3.$catg_td2.$catg["14"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["05"].$catg_td1.$catg_td0.$catg_title["15"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["05"].$catg_td3.$catg_td2.$catg["15"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["06"].$catg_td1.$catg_td0.$catg_title["16"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["06"].$catg_td3.$catg_td2.$catg["16"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["07"].$catg_td1.$catg_td0.$catg_title["17"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["07"].$catg_td3.$catg_td2.$catg["17"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["08"].$catg_td1.$catg_td0.$catg_title["18"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["08"].$catg_td3.$catg_td2.$catg["18"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["09"].$catg_td1.$catg_td0.$catg_title["19"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["09"].$catg_td3.$catg_td2.$catg["19"].$catg_td3.$catg_tr3;
 	echo $catg_tr0.$catg_td0.$catg_title["10"].$catg_td1.$catg_td0.$catg_title["20"].$catg_td1.$catg_tr1;
	echo $catg_tr2.$catg_td2.$catg["10"].$catg_td3.$catg_td2.$catg["20"].$catg_td3.$catg_tr3;

	if (!$ret) {
		$error_msg = $cibi_error_msg_8;
		exit();
	}

	include("tmpl_html/category-list-footer.html");
?>
