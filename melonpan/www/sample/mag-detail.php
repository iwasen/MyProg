<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");

/*********************************************************************************
			Main
*********************************************************************************/


	if ($mail_addr == "") {
		$mail_addr = $cookie_mail_addr;
		$reader_pswd = $cookie_reader_pswd;
		$reader_id = $cookie_reader_id;
	}
	if ($riba == 1) {
			if ($mail_addr == "") {
				include ("mypage-login.php");
				exit();
			}
	}

	$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
	$input_data = "";


//
// Get Detail
//
		$sql = "select  distinct M.mag_id,
				M.mag_nm,
				M.mag_intro_long,
				M.mag_cycle_day,
				M.mag_catg_1,
				M.mag_catg_2,
				M.mag_volume,
				to_char(M.last_send_dt,'YYYY/MM/DD'),
				A.catg,
				B.catg,
				M.mag_hp,
				M.mag_bg_1,
				M.mag_bg_2,
				C.bg,
				D.bg,
				M.osusume_flg,
				to_char(M.mag_pub_status_dt,'yyyy.mm.dd'),
				M.mag_pub_status_flg,
				M.mag_riba_status_flg,
				M.mag_bn_pub
			from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B,M_MAG_BG C,M_MAG_BG D ";
			if ($mag_rid != "") {
//				$sql .= "where M.rowid = '$mag_rid' ";
				$sql .= "where M.mag_id = '$mag_rid' ";

			} else {
				$sql .= "where M.mag_id = '$mag_id'";
			}
			$sql .= "and M.mag_catg_1 = A.catg_id
			and M.mag_catg_2 = B.catg_id
			and M.mag_bg_1 = C.bg_id
			and M.mag_bg_2 = D.bg_id";
		$ret = db_exec($sql);
		if (!$ret) {
			$error_msg = $cibi_error_msg_6;
			exit();
		}
		$resarray= pg_fetch_array ($ret, 0);

//
// Get Amount of Reader
//
		$sql = "select count(*) from T_MAILADDR where mag_id = '".$resarray[0]."'";
		$ret = db_exec($sql);
		if (!$ret) {
			$error_msg = $cibi_error_msg_8;
			exit();
		}

		$resarray_mail= pg_fetch_array ($ret, 0);

//
// Get BackNumber
//
//		$sql = "select rowidtochar(rowid) from L_BACKNUMBER
		$sql = "select index_id from L_BACKNUMBER
				where mag_id = '".$resarray[0]."'	order by modefy_dt desc";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
		if (!$ret) {
			$error_msg = $cibi_error_msg_8;
			exit();
		}

		$xx = 0;
		for ($i = 0; $i < $nrow; $i++) {
			$resarray_back= pg_fetch_array ($ret, $i);
			$back_num[$xx] = $resarray_back[0];
			$xx++;

			if ( $xx >= 2 ) {
				break;
			}
		}
//
// Get Recommend
//
	if ($last_recomend_id == "") { $last_recomend_id = 99999999;} //2002.1.5 9999999999999999 →　99999999
		$sql = "select count(*) as COUNT from T_RECOMEND
				where mag_id = '".$resarray[0]."' and open_flg = '1' ";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
		if (!$ret) {
			$error_msg = $cibi_error_msg_8;
			exit();
		} else {
			$fetch = pg_fetch_object($ret, 0);
			$nrows = $fetch->count;
		}
		

		$sql = "select recomend_id,recom_titl,recom_text,recom_nic from T_RECOMEND
				where mag_id = '".$resarray[0]."' and open_flg = 1 and recomend_id < $last_recomend_id ";
		$sql .= " order by recomend_id desc";
		$ret = db_exec($sql);
		$nrow = pg_numrows($ret);
		if (!$ret) {
			$error_msg = $cibi_error_msg_8;
			exit();
		}
		$count_num = 0;
		$reco_data = "";
		for ($i = 0; $i < $nrow; $i++) {
			$resarray_reco= pg_fetch_array ($ret, $i);
			$count_num++;
			$reco_data .= "<p><font size=\"2\">\n";
			$reco_data .= "■".$resarray_reco[1]."<br>\n";
			$reco_data .= $resarray_reco[2];
			$reco_data .= "（".$resarray_reco[3]."）\n";;
			$reco_data .= "</font></p>\n";
			$last_rec_id = $resarray_reco[0];
			if ( $count_num > 4 ) {
				break;
			}
		}
		if ( $nrows > 5 ) {
			$reco_res = "<div align=\"right\">\n";
			$reco_res .= "<a href=\"recommend_all.php?mag_id=".$resarray[0]."&last_recomend_id=$last_rec_id&nrows=$nrows\">\n";
			$reco_res .= "その他の推薦文を読む&gt;&gt;&gt;</a><br>\n";
			$reco_res .= "</div>\n";
		}

		include("tmpl_html/mag-detail1.html");
//		include("tmpl_html/list1-footer.html");
//	} else {
//	}
?>
