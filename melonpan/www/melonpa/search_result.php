<?php
include("../search/kensaku-list_dat.php");

function mk_input($pname,$param) {
	global $input_data;
	$input_data .= "<input type=\"hidden\" name=\"$pname\" value=\"$param\">\n";
}

/****************************************************************
 Main
****************************************************************/
//メールアドレス小文字変換
$email = strtolower($email);

if ( $page == "" || $page < 0 ) {
	$page = 1;
}

if ($action_btn == "決定（購読する／解除）") {
	if ($reader_pswd != "") {
		$mail_addr = $email;
	}
	include("mel_reg.php");
	exit();
}

include("../inc/com_db_func.inc");
include("inc/pass_check.inc");

//db_conxxxt();

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
$mail_addr = strtolower($mail_addr);

$input_data = "";
$input_read_chk = "";
$input_read_chk2 = "";

if ($action_btn != "" && count($read_chk) != 0) {
	while (list($key, $val) = each($read_chk)) {
		$input_read_chk .= "<input type=\"hidden\" name=\"read_chk[]\" value=\"$val\">\n";
		$mag_id = substr($val,2,6);
		$on_of = substr($val,0,2);
			if ($on_of == "on") { $on_of_j = "購読";} else {$on_of_j = "解除";}
		$input_read_chk2 .= "[ID:$mag_id $on_of_j] ";
	}
}

if ($order == "kari") {
	$orderby = "to_char(M.mag_pub_status_dt,'yyyy.mm.dd')";
} else {
	//$orderby = "to_char(M.last_send_dt,'YYYY/MM/DD') desc";
	//20010315modify
	$orderby = " M.last_send_dt desc";
}

if ( $mode == "catg" ) {
/* --------------------------------------------------------------------------------------  */
/*	カテゴリ検索
/* --------------------------------------------------------------------------------------  */
	if ( ereg("00$",$catg_id) ) {
		$where = "M.mag_catg_1 between '".substr($catg_id,0,2)."00' and '".substr($catg_id,0,2)."99' or\n"
			   . "M.mag_catg_2 between '".substr($catg_id,0,2)."00' and '".substr($catg_id,0,2)."99'";
	} else {
		$where = "M.mag_catg_1 = '$catg_id' or M.mag_catg_2 = '$catg_id'";
	}

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
			to_char(M.last_send_dt,'YYYY/MM/DD HH24:MI'), /* 案件NO24 表示形式変更 */
/*			rowidtochar(M.rowid),*/
			M.publisher_id,
			M.osusume_flg,
			to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
			M.mag_pub_status_flg,
			M.mag_riba_status_flg,
			M.MAG_ORIGINAL_FLG,
			M.MAG_TYPE,
			to_char(M.MAG_AGR_DT,'yyyy.mm.dd'),
			O.KAKO_CNT,
			C.catg as catg_3,
			D.catg as catg_4,
			M.last_send_dt
		from M_MAGAZINE M LEFT JOIN (select MAG_ID, count(*) as KAKO_CNT from T_OSUSUME_LIST where date_to is not NULL group by mag_id) as O ON M.MAG_ID = O.MAG_ID,M_MAG_CATG A,M_MAG_CATG B,M_MAG_CATG C,M_MAG_CATG D
		where $where
		and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
		and ( M.mag_pub_stop_flg <> '02' )
		and ( M.publisher_id <> 0 )
		and C.catg_id = '".substr($catg_id,0,2)."00'
		and D.catg_id = '$catg_id'
		and M.mag_catg_1 = A.catg_id
		and M.mag_catg_2 = B.catg_id
		order by $orderby";
		$ret = db_exec($sql);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
		mk_input("catg_id",$catg_id);
		mk_input("mode","catg");
/* --------------------------------------------------------------------------------------  */
/*	私向け
/* --------------------------------------------------------------------------------------  */
} elseif ( $mode == "reader" ) {
	mk_input("mode","reader");
	$kind = "";
	$where0 = "";
	$ta_flg = 0;
	if ( isset($sex) ) {
		while ( list($key,$val) = each($sex) ) {
			if ( $val != "" ) {
				$sex_def[1] = "男性向け";
				$sex_def[2] = "女性向け";
				$where0 .= "(M.mag_reader_sex = '$val') or ";
					mk_input("sex[]",$val);
				if ( $kind == "" ) {
					$kind = $sex_def[$val];
					$kind_mark = "s";
					$kind_flg = $val;
				} else {
					$ta_flg = 1;
				}
			}
		}
	}
  		$where0 = ereg_replace(" or $","",$where0);
	$where2 = "";
	if ( isset($age) ) {
		while ( list($key,$val) = each($age) ) {
			if ( $val != "" ) {
				$where2 .= "(M.mag_reader_age = '$val') or ";
					mk_input("age[]",$val);
				if ( $kind == "" && $val != 60 ) {
					$kind = $val."代";
					$kind_mark = "a";
					$kind_flg = $val;
				} elseif ( $kind == "" && $val == 60 ) {
					$kind = $val."代以上";
					$kind_mark = "a";
					$kind_flg = $val;
				} else {
					$ta_flg = 1;
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
				if ( $kind == "" ) {
					$kind = $val;
					$kind_mark = "r";
				} else {
					$ta_flg = 1;
				}
			}
		}
	}
  		$where3 = ereg_replace(" or $","",$where3);
	$where1 = "";
	if ( isset($occup) ) {
		while ( list($key,$val) = each($occup) ) {
			if ( $val != "" ) {
				$where1 .= "(M.mag_reader_occup = '$val') or ";
					mk_input("occup[]",$val);
				if ( $kind == "" ) {
					$kind = $val;
					$kind_mark = "o";
				} else {
					$ta_flg = 1;
				}
			}
		}
	}
  		$where1 = ereg_replace(" or $","",$where1);
	$where = "(".$where0.") and (".$where2.") and (".$where3.") and (".$where1.")";
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
			to_char(M.last_send_dt,'YYYY/MM/DD HH24:MI'), /* 案件NO24 表示形式変更 */
			M.publisher_id,
/*			rowidtochar(M.rowid),*/
			M.osusume_flg,
			to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
			M.mag_pub_status_flg,
			M.mag_riba_status_flg,
			M.MAG_ORIGINAL_FLG,
			M.MAG_TYPE,
			to_char(M.MAG_AGR_DT,'yyyy.mm.dd'),
			O.KAKO_CNT,
			M.last_send_dt
		from M_MAGAZINE M LEFT JOIN (select MAG_ID, count(*) as KAKO_CNT from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O ON M.MAG_ID = O.MAG_ID, M_MAG_CATG A, M_MAG_CATG B
		where $where
		and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
		and ( M.mag_pub_stop_flg <> '02' )
		and ( M.publisher_id <> 0 )
		and M.mag_catg_1 = A.catg_id
		and M.mag_catg_2 = B.catg_id
		order by $orderby";

	$ret = db_exec($sql);

	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
/* --------------------------------------------------------------------------------------  */
/*	気分でみつける
/* --------------------------------------------------------------------------------------  */
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
			to_char(M.last_send_dt,'YYYY/MM/DD HH24:MI'), /* 案件NO24 表示形式変更 */
			M.publisher_id,
/*			rowidtochar(M.rowid),*/
			M.osusume_flg,
			to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
			M.mag_pub_status_flg,
			M.mag_riba_status_flg,
			M.MAG_ORIGINAL_FLG,
			M.MAG_TYPE,
			to_char(M.MAG_AGR_DT,'yyyy.mm.dd'),
			O.KAKO_CNT,
			M.last_send_dt
			 from M_MAGAZINE M LEFT JOIN (select MAG_ID, count(*) as KAKO_CNT from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O ON M.MAG_ID = O.MAG_ID,M_MAG_CATG A, M_MAG_CATG B
/* **		from M_MAGAZINE M,M_MAG_CATG A, M_MAG_CATG B,
			(select MAG_ID, count(*) as KAKO_CNT from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O */
		where $where
		and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
		and ( M.mag_pub_stop_flg <> '02' )
		and ( M.publisher_id <> 0 )
		and M.mag_catg_1 = A.catg_id
		and M.mag_catg_2 = B.catg_id
/* **		and M.MAG_ID = O.MAG_ID(+) */
		order by $orderby";

	$ret = db_exec($sql);

	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
/* --------------------------------------------------------------------------------------  */
/*	キーワード検索
/* --------------------------------------------------------------------------------------  */
} elseif ( $mode == "word" || $mode == '') {
	if ( $keyword != "" ) {
		mk_input("keyword",$keyword);
		$keyword = stripslashes($keyword);

		$kind = "キーワード &gt; ".htmlspecialchars($keyword);
		$keyword = str_replace("'","''",$keyword);
		$keyword2 = $keyword;
		$keyword = i18n_ja_jp_hantozen($keyword, "a"); 	// 英数を半角へ
		$keyword = strtoupper($keyword);
		$kw11 = i18n_ja_jp_hantozen($keyword, "A"); 	// 英数を全角へ
		$kw12 = i18n_ja_jp_hantozen($keyword2, "A");	// 英数を全角へ
		$kw21 = i18n_ja_jp_hantozen($keyword, "KV");	// 全角カナへ(マガジン名、紹介文は半角カナなし)
		$kw_low = i18n_ja_jp_hantozen(strtolower($keyword), "R");		//全角小文字対応
/* for Debug
print "keyword = $keyword\n";
print "kw11 = $kw11\n";
print "kw12 = $kw12\n";
print "kw21 = $kw21\n";
print "kw_low = $kw_low\n";
*/
		$where = "("
				."   UPPER(M.mag_nm) Like '%$keyword%' "
				."or UPPER(M.mag_nm) Like '%$kw11%' "
				."or UPPER(M.mag_nm) Like '%$kw12%' "
				."or UPPER(M.mag_nm) Like '%$kw21%' "
				."or UPPER(M.mag_nm) Like '%$kw_low%' "
				."or UPPER(M.mag_intro_short) Like '%$keyword%' "
				."or UPPER(M.mag_intro_short) Like '%$kw11%' "
				."or UPPER(M.mag_intro_short) Like '%$kw12%' "
				."or UPPER(M.mag_intro_short) Like '%$kw21%' "
				."or UPPER(M.mag_intro_short) Like '%$kw_low%' "
				."or UPPER(M.mag_intro_long) Like '%$keyword%' "
				."or UPPER(M.mag_intro_long) Like '%$kw11%' "
				."or UPPER(M.mag_intro_long) Like '%$kw12%' "
				."or UPPER(M.mag_intro_long) Like '%$kw21%' "
				."or UPPER(M.mag_intro_long) Like '%$kw_low%' "
				."or UPPER(M.mag_id) Like '%$keyword%'"
				.")";
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
			to_char(M.last_send_dt,'YYYY/MM/DD HH24:MI'), /* 案件NO24 表示形式変更 */
/* **			rowidtochar(M.rowid),*/
			M.mag_id,
			M.osusume_flg,
			to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
			M.mag_pub_status_flg,
			M.mag_riba_status_flg,
			M.MAG_ORIGINAL_FLG,
			M.MAG_TYPE,
			to_char(M.MAG_AGR_DT,'yyyy.mm.dd'),
			O.KAKO_CNT,
			M.last_send_dt
		 from M_MAGAZINE M LEFT JOIN (select MAG_ID, count(*) as KAKO_CNT from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O ON M.MAG_ID = O.MAG_ID"
		 	.",M_MAG_CATG A,M_MAG_CATG B
		 where $where
		 and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' )
		 and ( M.mag_pub_stop_flg <> '02' )
		 and ( M.publisher_id <> 0 )
		 and M.mag_catg_1 = A.catg_id
		 and M.mag_catg_2 = B.catg_id
		 order by $orderby";
		$ret = db_exec($sql);
	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	mk_input("mode","word");
/* 旧（バッチ非対応版）-start------------------------------------------------------------
} elseif ( $mode == "rank" ) {
   旧（バッチ非対応版）-end--------------------------------------------------------------  */
}

$all_num = 0;
$data = "";
$nrow = pg_numrows($ret);
for ($i = 0; $i < $nrow; $i++) {
	$all_num++;
	if ( $all_num > $page*10 || $all_num <= ($page-1)*10 ) {
		continue;
	}
	$resarray= pg_fetch_array ($ret, $i);
	mk_data();
}

/* -------------------------------------------------- */
include("../sub/head.inc");
include("../search/kensaku-list_header.php");
/* -------------------------------------------------- */

if ( $mode == "catg" ) {
	if ( $all_num == 0 ) {
		$sql = "select A.catg,B.catg
			from M_MAG_CATG A,M_MAG_CATG B
			where A.catg_id = '".substr($catg_id,0,2)."00' and B.catg_id = '$catg_id'";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
		if (!$ret) {
			exit();
		}
			for ($i = 0; $i < $nrow; $i++) {
			$resarray= pg_fetch_array ($ret, $i);
			$kind = "<A HREF=\"search_result.php?mode=catg&catg_id=".substr($catg_id,0,2)."00\">\n";
			$kind .= trim($resarray[0])."</A>";
			if ( $resarray[0] != $resarray[1] ) {
				$kind .= " &gt; <A HREF=\"search_result.php?mode=catg&catg_id=".$catg_id."\">\n";
				$kind .= trim($resarray[1])."</A>\n";
			}
		}
	} else {
		$kind = "<A HREF=\"search_result.php?mode=catg&catg_id=".substr($catg_id,0,2)."00\">\n";
		$kind .= trim($resarray[19])."</A>\n";		// 07.20 mod
		if ( $resarray[19] != $resarray[20] ) {		// 07.20 mod
			$kind .= " &gt; <A HREF=\"search_result.php?mode=catg&catg_id=".$catg_id."\">\n";

			$kind .= trim($resarray[20])."</A>\n";	// 07.20 mod

		}
	}

} elseif ( $mode == "reader" ) {
	if ( $kind != "" ) {
		if ($kind_mark == "s") {
			$kind_moto = $kind;
			$kind2 = "<A HREF=\"search_result.php?mode=reader&sex[]=$kind_flg\">\n";
			$kind2 .= $kind_moto."</A> &gt; ";
		} elseif ($kind_mark == "a") {
			$kind_moto = $kind;
			$kind2 = "<A HREF=\"search_result.php?mode=reader&age[]=$kind_flg\">\n";
			$kind2 .= $kind_moto."</A> &gt; ";
		} elseif ($kind_mark == "r") {
			$sql = "select AREA from M_MAG_AREA where AREA_ID = '$kind'";
//			$stmt = ociparse($gConn,$sql);
//			$ret = ociexecute($stmt);
			$ret = db_exec($sql);
			if (!$ret) { exit(); }
			$nrow = pg_numrows($ret);
			for ($i = 0; $i < $nrow; $i++) {
				$resarray= pg_fetch_array ($ret, $i);
				$kind2 = "<A HREF=\"search_result.php?mode=reader&area[]=$kind\">\n";
				$kind2 .= $resarray[0]."</A> &gt; ";
			}
		} elseif ($kind_mark == "o") {
			$sql = "select OCCUP from M_MAG_OCCUP where OCCUP_ID = '$kind'";
//			$stmt = ociparse($gConn,$sql);
//			$ret = ociexecute($stmt);
			$ret = db_exec($sql);
			if (!$ret) { exit(); }
			$nrow = pg_numrows($ret);
			for ($i = 0; $i < $nrow; $i++) {
				$resarray= pg_fetch_array ($ret, $i);
				$kind2 = "<A HREF=\"search_result.php?mode=reader&occup[]=$kind\">\n";
				$kind2 .= $resarray[0]."</A> &gt; ";
			}
		}
		if ($ta_flg == 1) {
			$kind = $kind2."　他";
		} else {
			$kind = $kind2;
		}
	}

} elseif ( $mode == "feeling" ) {
	if ( $kind != "" ) {
		$sql = "select A.bg,B.bg from M_MAG_BG A,M_MAG_BG B
			where A.bg_id = '".substr($kind,0,2)."000' and B.bg_id = '$kind'";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
		if (!$ret) {
			exit();
		}
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
/* add 01/05/22 ---------------------------------
} elseif ( $mode == "rank" ) {
	$kind = $rank_msg;
	$fr_dt = ($page - 1) * 10 + 1;
	$to_dt = $page * 10 ;
	$kind .= "  [ TOP ".$fr_dt."〜".$to_dt." ］";
} else {
   ---------------------------------------------- */
}

if ( $page > 1 ) {
	//$pre_page = $page-1;
	//$chg_page = "<A HREF=\"search_result.php?".$input_data."page=".$pre_page."\">\n";
	//$chg_page .= "&lt;&lt;&lt;前の10件";
	//$chg_page .= "</A>\n";

	//$chg_page .= $input_data;
	//$chg_page .= "<input type=\"hidden\" name=\"page\" value=\"$pre_page\">\n";
	//$chg_page .= "<input type=\"submit\" name=\"action\" value=\"前の10件\">\n";

	$chg_page .= "<input type=\"button\" name=\"action\" value=\"前の10件\" onClick=\"history.back()\">\n";
	$chg_page2 .= "<input type=\"button\" name=\"action\" value=\"前の10件\" onClick=\"history.back()\">\n";

}
if ( $all_num > $page*10 ) {
	$aft_page = $page+1;
	//$chg_page .= "　　<A HREF=\"search_result.php?".$input_data."page=".$aft_page."\">";
	//$chg_page .= "次の10件&gt;&gt;&gt;";
	//$chg_page .= "</A>\n";

	$chg_page .= "<input type=\"hidden\" name=\"page\" value=\"$aft_page\">\n";
	$chg_page .= "<input type=\"submit\" name=\"action\" value=\"次の10件\">\n";
	$chg_page .= $input_data;

	//$chg_page2 .= $input_data;
	$chg_page2 .= "<input type=\"hidden\" name=\"page\" value=\"$aft_page\">\n";
	$chg_page2 .= "<input type=\"submit\" name=\"action\" value=\"次の10件\">\n";
}
/* ----------------------------------------------------------- */
include("../search/kensaku-list-link-header.php");
/* ----------------------------------------------------------- */

echo $data;


if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}

/* ----------------------------------------------------------- */
include("../search/kensaku-list-link-footer.php");
draw_footer("..");
print "</table>";
print "</td>";
print "</tr>";
print "</table>";
print "</form></body></html>";
/* ----------------------------------------------------------- */
?>
