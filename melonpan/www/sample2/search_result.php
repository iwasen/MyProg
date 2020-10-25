<?php
function mk_data() {
	global $data,$resarray,$reader_id,$reader_pswd,$mail_addr;
		$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
		$mark = "";
		if ($resarray[11] == 1) { $mark = "<img src=\"../img/mark-rec.gif\">";}
		if ($resarray[12] > $mae) { $mark .= "<img src=\"../img/mark-new.gif\">";}
		if ($resarray[13] == "01") { $mark .= "<img src=\"../img/mark-kari.gif\">";}
		if ($resarray[14] == 1) { $mark .= "<img src=\"../img/mark-rib.gif\">";}
		if ($mark == "") { $mark = "<br>"; }

	$data .= "<table width=\"690\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\">\n";
	$data .= "<tr>\n";
	$data .= "<td bgcolor=\"#336600\" width=\"70\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><font color=\"#FFCC00\"><b>メルマガID</b></font></div>\n";
	$data .= "</td>\n";
	$data .= "<td width=\"60\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"left\"><font size=\"2\">\n";
	$data .= $resarray[0]."\n";
	$data .= "</font></div>\n";
	$data .= "</td>\n";
	$data .= "<td bgcolor=\"#336600\" width=\"69\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><font color=\"#FFCC00\"><b>タイトル</b></font></div>\n";
	$data .= "</td>\n";
	$data .= "<td class=\"text9ptnoColor\"><b>\n";
//	$data .= "<b><a href=\"mag-detail.php?mag_rid=".$resarray[10]."&reader_id=".$reader_id."&reader_pswd=$reader_pswd&mail_addr=$mail_addr\">\n";
	$data .= "<b><a href=\"mag-detail.php?mag_id=".$resarray[10]."&reader_id=".$reader_id."&reader_pswd=$reader_pswd&mail_addr=$mail_addr\">\n";
	$data .= trim($resarray[1])."\n";
	$data .= "</a></b></td>\n";
	$data .= "</b></td>\n";
	$data .= "<td bgcolor=\"#336600\" width=\"68\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><b><font color=\"#FFCC00\"><b>マーク</b></font></b></div>\n";
	$data .= "</td>\n";
	$data .= "<td  width=\"148\" class=\"text9ptnoColor\">\n";
	$data .= $mark."\n";
	$data .= "</td>\n";
	$data .= "</tr>\n";
	$data .= "<tr> \n";
	$data .= "<td width=\"69\" bgcolor=\"#336600\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><b><font color=\"#FFCC00\">最終発行日 </font></b></div>\n";
	$data .= "</td>\n";
	$data .= "<td width=\"63\" class=\"text9ptnoColor\">\n";
	$data .= $resarray[9]."\n";
	$data .= "</td>\n";
	$data .= "<td width=\"69\" bgcolor=\"#336600\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><b><font color=\"#FFCC00\"><b>カテゴリ</b></font></b></div>\n";
	$data .= "</td>\n";
	$data .= "<td class=\"text9ptnoColor\" width=\"195\">\n";
	$data .= trim($resarray[7])."、".trim($resarray[8])."\n";
	$data .= "</td>\n";
	$data .= "<td width=\"68\"  bgcolor=\"#336600\" class=\"text9ptnoColor\">\n";
	$data .= "<div align=\"center\"><b><font color=\"#FFCC00\">発行周期</font></b></div>\n";
	$data .= "</td>\n";
	$data .= "<td width=\"148\" class=\"text9ptnoColor\">\n";
	$data .= trim($resarray[3])."\n";
	$data .= "</td>\n";
	$data .= "</tr>\n";
	$data .= "<tr>\n";
	$data .= "<td colspan=\"4\" class=\"text9ptnoColor\">\n";
	$data .= trim($resarray[2])."\n";
	$data .= "</td>\n";
	$data .= "<td  bgcolor=\"#336600\" class=\"text9ptnoColor\" width=\"68\"> \n";
	$data .= "<div align=\"center\"><b><font color=\"#FFCC00\">登録</font></b></div>\n";
	$data .= "</td>\n";
	$data .= "<td class=\"text9ptnoColor\" width=\"148\"> \n";
	$data .= "<div align=\"left\">　</div>\n";
	$data .= "</td>\n";
	$data .= "</tr>\n";
	$data .= "<tr> </tr>\n";
	$data .= "</table>\n";
	$data .= "<br>\n";
}
function mk_input($pname,$param) {
	global $input_data;
	$input_data .= $pname."=".$param."&";
}
	include("../inc/com_db_func.inc");

	$input_data = "";

//	db_conxxxt();

	if ($reader_id != "") {
		$sql = "select mail_addr from M_MELONPA where reader_id = $reader_id and reader_pswd = '$reader_pswd'";
			$stmt = OCIParse($gConn, $sql);
			OCIDefinebyname($stmt, "MAIL_ADDR", &$mail_addr);
			OCIExecute($stmt, OCI_DEFAULT);
			$ct = OCIFetchstatement($stmt,&$results);
			if ($ct < 1) {
				ocifreestatement($stmt);
				$error_msg = $cibi_error_msg_8;
				echo "Password Error";
				db_logoff();
				exit();
			}
		}


	if ( $mode == "catg" ) {
//		$catg_id = substr($catg_id,0,2);

/*  マガジン数が増えるまでは使用しない *************************************************************

		if ( ereg("00$",$catg_id) ) {
      			$where = "M.mag_catg_1 between ".substr($catg_id,0,2)."00 and ".substr($catg_id,0,2)."99 or\n";
      			$where .= "M.mag_catg_2 between ".substr($catg_id,0,2)."00 and ".substr($catg_id,0,2)."99";
		} else {
      			$where = "M.mag_catg_1 = $catg_id or M.mag_catg_2 = $catg_id";
		}

      		$where = "(".$where.")\n";

		$sql = "select distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_short,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				to_char(M.modefy_dt,'YYYY/MM/DD'),
				A.catg,
				B.catg,
				to_char(L.send_dt,'YYYY/MM/DD'),
				rowidtochar(M.rowid),
				C.catg,
				D.catg
			from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B,M_MAG_CATG C,M_MAG_CATG D,L_SEND L
			where $where
			and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
			and ( M.mag_pub_stop_flg <> '02' )
			and ( M.publisher_id <> 0 )
			and M.mag_catg_1 = A.catg_id
			and M.mag_catg_2 = B.catg_id
			and C.catg_id = ".substr($catg_id,0,2)."00
			and D.catg_id = $catg_id
			order by to_char(L.send_dt,'YYYY/MM/DD') desc";

*****************************************************************************************************/

	$where = "M.mag_catg_1 between '".substr($catg_id,0,2)."00' and '".substr($catg_id,0,2)."99' or\n";
	$where .= "M.mag_catg_2 between '".substr($catg_id,0,2)."00' and '".substr($catg_id,0,2)."99'";
	$where = "(".$where.")\n";

		$sql = "select distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_short,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				to_char(M.modefy_dt,'YYYY/MM/DD'),
				A.catg as catg_1,
				B.catg as catg_2,
				to_char(M.last_send_dt,'YYYY/MM/DD'),
/* **				rowidtochar(M.rowid),*/
				M.mag_id,
				M.osusume_flg,
				to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
				M.mag_pub_status_flg,
				M.mag_riba_status_flg,
				M.osusume_flg,
				C.catg as catg_3,
				D.catg as catg_4
			from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B,M_MAG_CATG C,M_MAG_CATG D
			where $where
			and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
			and ( M.mag_pub_stop_flg <> '02' )
			and ( M.publisher_id <> 0 )
			and M.mag_catg_1 = A.catg_id
			and M.mag_catg_2 = B.catg_id
			and C.catg_id = '".substr($catg_id,0,2)."00'
			and D.catg_id = '$catg_id'
			order by to_char(M.last_send_dt,'YYYY/MM/DD') desc";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
/* **
		$stmt = ociparse($gConn,$sql);
		$ret = ociexecute($stmt);
*/
		if (!$ret) {
//			ocifreestatement($stmt);
			$error_msg = $cibi_error_msg_6;
//			include("tmpl_html/cibi_error.html");
//			include("tmpl_html/cibi_error.html");
//			db_logoff();
			exit();
		}
		mk_input("catg_id",$catg_id);
		mk_input("mode","catg");

	} elseif ( $mode == "reader" ) {
		mk_input("mode","reader");
		$kind = "";
		$where0 = "";
		if ( isset($sex) ) {
			while ( list($key,$val) = each($sex) ) {
				if ( $val != "" ) {
					$sex_def[1] = "男性向け";
					$sex_def[2] = "女性向け";
					$where0 .= "(M.mag_reader_sex = '$val') or ";
					mk_input("sex[]",$val);
					if ( $kind == "" ) {
						$kind = $sex_def[$val];
					}
				}
			}
		}
      		$where0 = ereg_replace(" or $","",$where0);
		$where1 = "";
		if ( isset($occup) ) {
			while ( list($key,$val) = each($occup) ) {
				if ( $val != "" ) {
					$where1 .= "(M.mag_reader_occup = '$val') or ";
					mk_input("occup[]",$val);
				}
			}
		}
      		$where1 = ereg_replace(" or $","",$where1);
		$where2 = "";
		if ( isset($age) ) {
			while ( list($key,$val) = each($age) ) {
				if ( $val != "" ) {
					$where2 .= "(M.mag_reader_age = '$val') or ";
					mk_input("age[]",$val);
					if ( $kind == "" && $val != 60 ) {
						$kind = $val."代";
					} elseif ( $kind == "" && $val == 60 ) {
						$kind = $val."代以上";
					}
				}
			}
		}
      		$where2 = ereg_replace(" or $","",$where2);
		$where3 = "";
		if ( isset($area) ) {
			while ( list($key,$val) = each($area) ) {
				if ( $val != "" ) {
					$where3 .= "(M.mag_reader_area = '$val') or ";
					mk_input("area[]",$val);
				}
			}
		}
      		$where3 = ereg_replace(" or $","",$where3);
		$where = "(".$where0.") and (".$where1.") and (".$where2.") and (".$where3.")";
      		$where = ereg_replace("\(\) and ","",$where);
      		$where = ereg_replace("\(\)$","",$where);
      		$where = ereg_replace(" and $","",$where);
		if ( $where == "" ) {
			$where = "0 = 0";
		}
		$where = "(".$where.")";
		$sql = "select distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_short,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				to_char(M.modefy_dt,'YYYY/MM/DD'),
				A.catg as catg_1,
				B.catg as catg_2,
				to_char(M.last_send_dt,'YYYY/MM/DD'),
/* **				rowidtochar(M.rowid),*/
				M.mag_id,
				M.osusume_flg,
				to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
				M.mag_pub_status_flg,
				M.mag_riba_status_flg,
				M.osusume_flg
			from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B
			where $where
			and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
			and ( M.mag_pub_stop_flg <> '02' )
			and ( M.publisher_id <> 0 )
			and M.mag_catg_1 = A.catg_id
			and M.mag_catg_2 = B.catg_id 
			order by to_char(M.last_send_dt,'YYYY/MM/DD') desc";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);

/* **
		$stmt = ociparse($gConn,$sql);
		$ret = ociexecute($stmt);
*/
		if (!$ret) {
//			ocifreestatement($stmt);
			$error_msg = $cibi_error_msg_6;
//			include("tmpl_html/cibi_error.html");
//			include("tmpl_html/cibi_error.html");
//			db_logoff();
			exit();
		}
	} elseif ( $mode == "feeling" ) {
		$kind = "";
		mk_input("mode","feeling");
		if ( isset($mag_bg_chk) ) {
			while ( list(,$value )=each($mag_bg_chk) ) {
				$where .= "M.mag_bg_1 Like '$value' or M.mag_bg_2 Like '$value' or \n";
				mk_input("mag_bg_chk[]",$value);
				if ( $kind == "" ) {
					$kind = $value;
				} else {
					$ekind = " 他";
				}
			}
		} elseif ( $mag_bg != "" ) {
      			$where = "M.mag_bg_1 Like '".substr($mag_bg,0,2)."%' or M.mag_bg_2 Like '".substr($mag_bg,0,2)."%'";
			mk_input("mag_bg",$mag_bg);
			$kind = $mag_bg;
		} else {
      			$where = "0 = 0";
			$ekind = "ALL";
		}
      		$where = ereg_replace(" or \n$","",$where);
      		$where = "(".$where.")";

		$sql = "select distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_short,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				to_char(M.modefy_dt,'YYYY/MM/DD'),
				A.catg,
				B.catg,
				to_char(M.last_send_dt,'YYYY/MM/DD'),
/* **				rowidtochar(M.rowid),*/
				M.mag_id,
				M.osusume_flg,
				to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
				M.mag_pub_status_flg,
				M.mag_riba_status_flg,
				M.osusume_flg
			from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B
			where $where
			and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
			and ( M.mag_pub_stop_flg <> '02' )
			and ( M.publisher_id <> 0 )
			and M.mag_catg_1 = A.catg_id
			and M.mag_catg_2 = B.catg_id 
			order by to_char(M.last_send_dt,'YYYY/MM/DD') desc";
			$ret = db_exec($sql);
			$nrow = pg_numrows($ret);
/* **
		$stmt = ociparse($gConn,$sql);
		$ret = ociexecute($stmt);
*/
		if (!$ret) {
//			ocifreestatement($stmt);
			$error_msg = $cibi_error_msg_6;
//			db_logoff();
			exit();
		}
	} elseif ( $mode == "word" ) {
		if ( $keyword != "" ) {
			mk_input("keyword",urlencode($keyword));
			$keyword = stripslashes($keyword);
			$kind = "キーワード &gt; ".htmlspecialchars($keyword);
			$keyword = ereg_replace("'","''",$keyword);
			$where = "(M.mag_nm Like '%$keyword%' or M.mag_intro_short Like '%$keyword%' or \n";
			$where .= "M.mag_intro_long Like '%$keyword%' or M.mag_id Like '%$keyword%')\n";
		} else {
			$where = "(0 = 0)\n";
		}
		$sql = "select distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_short,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				to_char(M.modefy_dt,'YYYY/MM/DD'),
				A.catg,
				B.catg,
				to_char(M.last_send_dt,'YYYY/MM/DD'),
/* **				rowidtochar(M.rowid),*/
				M.mag_id,
				M.osusume_flg,
				to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
				M.mag_pub_status_flg,
				M.mag_riba_status_flg,
				M.osusume_flg
			 from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B
			 where $where
			and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
			and ( M.mag_pub_stop_flg <> '02' )
			and ( M.publisher_id <> 0 )
			 and M.mag_catg_1 = A.catg_id
			 and M.mag_catg_2 = B.catg_id
			 order by to_char(M.last_send_dt,'YYYY/MM/DD') desc";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
/* **
		$stmt = ociparse($gConn,$sql);
		$ret = ociexecute($stmt);
*/
		if (!$ret) {
//			ocifreestatement($stmt);
			$error_msg = $cibi_error_msg_6;
//			include("tmpl_html/cibi_error.html");
//			include("tmpl_html/cibi_error.html");
//			db_logoff();
			exit();
		}
		mk_input("mode","word");
	}
	if ( $page == "" || $page < 0 ) {
		$page = 1;
	}

	$all_num = 0;
	$data = "";
//	while ( ocifetchinto($stmt,&$resarray) ) {
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		$all_num++;
		if ( $all_num > $page*10 || $all_num <= ($page-1)*10 ) {
			continue;
		}
		mk_data();
	}

	include("tmpl_html/list1-header.html");
	if ( $mode == "catg" ) {
		if ( $all_num == 0 ) {
			$sql = "select A.catg,B.catg
				from M_MAG_CATG A,M_MAG_CATG B
				where A.catg_id = ".substr($catg_id,0,2)."00 and B.catg_id = ".$catg_id;
			$stmt = ociparse($gConn,$sql);
			$ret = ociexecute($stmt);

			if (!$ret) {
				ocifreestatement($stmt);
				db_logoff();
				exit();
			}
			while ( ocifetchinto($stmt,&$resarray) ) {
				$kind = "<A HREF=\"search_result.php?mode=catg&catg_id=".substr($catg_id,0,2)."00\">\n";
				$kind .= trim($resarray[0])."</A>";
				if ( $resarray[0] != $resarray[1] ) {
					$kind .= " &gt; <A HREF=\"search_result.php?mode=catg&catg_id=".$catg_id."\">\n";
					$kind .= trim($resarray[1])."</A>\n";
				}
			}
		} else {
			$kind = "<A HREF=\"search_result.php?mode=catg&catg_id=".substr($catg_id,0,2)."00\">\n";
			$kind .= trim($resarray[16])."</A>\n";
			if ( $resarray[16] != $resarray[17] ) {
				$kind .= " &gt; <A HREF=\"search_result.php?mode=catg&catg_id=".$catg_id."\">\n";
				$kind .= trim($resarray[17])."</A>\n";
			}
		}
	} elseif ( $mode == "reader" ) {
/* **
		if ( $kind == "" ) {
			$sql = "select A.catg,B.catg
				from M_MAG_CATG A,M_MAG_CATG B
				where A.catg_id = ".substr($catg_id,0,2)."00 and B.catg_id = ".$catg_id;
			$stmt = ociparse($gConn,$sql);
			$ret = ociexecute($stmt);

			if (!$ret) {
				ocifreestatement($stmt);
				db_logoff();
				exit();
			}
			while ( ocifetchinto($stmt,&$resarray) ) {
				$kind = "<A HREF=\"search_result.php?mode=catg&catg_id=".substr($catg_id,0,2)."00\">\n";
				$kind .= trim($resarray[0])."</A> &gt; ";
				$kind .= "<A HREF=\"search_result.php?mode=catg&catg_id=".$catg_id."\">\n";
				$kind .= trim($resarray[1])."</A>\n";
			}
		}
*/
	} elseif ( $mode == "feeling" ) {
		if ( $kind != "" ) {
/* **
			$sql = "select A.bg,B.bg from M_MAG_BG A,M_MAG_BG B
				where A.bg_id = '".substr($kind,0,2)."000' and B.bg_id = '$kind'";
			$ret = db_exec($sql);
			$nrow = pg_numrows($ret);
*/
			$sql = "select A.bg,B.bg from M_MAG_BG A,M_MAG_BG B
				where A.bg_id = '".substr($kind,0,2)."000' and B.bg_id = '".$kind."'";
			$ret = db_exec($sql);
			$nrow = pg_numrows($ret);
/* **
			$stmt = ociparse($gConn,$sql);
			$ret = ociexecute($stmt);
*/
			if (!$ret) {
//				ocifreestatement($stmt);
//				db_logoff();
				exit();
			}
//			while ( ocifetchinto($stmt,&$resarray) ) {
			for ($i = 0; $i < $nrow; $i++) {
				$resarray= pg_fetch_array ($ret, $i);
				if ( $resarray[0] == $resarray[1] ) {
					$kind = trim($resarray[0]);
				} else {
					$kind = trim($resarray[0])." &gt; ";
					$kind .= trim($resarray[1])."\n";
				}
			}
		}
		$kind .= $ekind;
	} elseif ( $mode == "word" ) {
	} else {
	}
	if ( $page > 1 ) {
		$pre_page = $page-1;
		$chg_page = "<A HREF=\"search_result.php?".$input_data."page=".$pre_page."\">\n";
		$chg_page .= "&lt;&lt;&lt;前の10件";
		$chg_page .= "</A>\n";
	}
	if ( $all_num > $page*10 ) {
		$aft_page = $page+1;
		$chg_page .= "　　<A HREF=\"search_result.php?".$input_data."page=".$aft_page."\">";
		$chg_page .= "次の10件&gt;&gt;&gt;";
		$chg_page .= "</A>\n";
	}
	include("tmpl_html/list1-link-header.html");
	echo $data;

//	include("tmpl_html/cibi_read.html");
//	ocifreestatement($stmt);

	if (!$ret) {
//		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_8;
//		include("tmpl_html/cibi_error.html");
//		db_logoff();
		exit();
	}

//	db_logoff();
	include("tmpl_html/list1-link-footer.html");
	include("tmpl_html/list1-footer.html");
?>
