<?php

function makepoint ($mail_addr) {
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$result = db_exec($sql);

	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$reader_id = $fetch->reader_id;
		$reader_pswd = $fetch->reader_pswd;
		$pt_melonpan = $fetch->pt_melonpan;
		echo $pt_melonpan;
	}
}

function make_maglist_t ($mail_addr) {
	$mae = strftime("%y-%m-%d ",time() - (14*24*60*60));

	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$reader_id = $fetch->reader_id;
		$reader_pswd = $fetch->reader_pswd;
		$pt_melonpan = $fetch->pt_melonpan;

#		$sql1 =  "select * from t_mailaddr where reader_id = ";
#		$sql = $sql1 .  $reader_id;
		$sql1 =  "select * from t_mailaddr where mail_addr = ";
		$sql = $sql1 .  "'".$mail_addr."' order by modify_dt";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$lcount = 0;
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$mag_id = $fetch->mag_id;
			$sql = "select M.MAG_NM, M.OSUSUME_FLG, to_char(M.MAG_AGR_DT, 'YY-MM-DD') as mag_agr_dt,"
				 . "       to_char(M.MAG_PUB_STATUS_DT, 'YY-MM-DD') as mag_pub_status_dt, M.MAG_PUB_STATUS_FLG,"
				 . "       M.MAG_PUB_STOP_FLG, M.MAG_RIBA_STATUS_FLG, M.PUBLISHER_ID, M.MAG_ORIGINAL_FLG,"
				 . "       M.MAG_TYPE, O.KAKO_CNT "
				 . "  from M_MAGAZINE M "
				 . "  left join (select MAG_ID, count(*) as KAKO_CNT "
				 ."                from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O "
				 . "    on M.MAG_ID = O.MAG_ID"
				 . " where M.MAG_ID = '$mag_id'";

/* **
			$sql = "select M.MAG_NM, M.OSUSUME_FLG, M.MAG_AGR_DT, M.MAG_PUB_STATUS_DT, M.MAG_PUB_STATUS_FLG,"
				 . "       M.MAG_PUB_STOP_FLG, M.MAG_RIBA_STATUS_FLG, M.PUBLISHER_ID, M.MAG_ORIGINAL_FLG,"
				 . "       M.MAG_TYPE, O.KAKO_CNT "
				 . "  from M_MAGAZINE M,"
				 . "	  (select MAG_ID, count(*) as KAKO_CNT "
				 . "         from T_OSUSUME_LIST where date_to is not NULL group by mag_id) O "
				 . " where M.MAG_ID = '$mag_id' and M.MAG_ID = O.MAG_ID(+)";
*/
			$result2 = db_exec($sql);
			if (pg_numrows($result2)) {
				$fetch = pg_fetch_object($result2, 0);
				$mag_nm = $fetch->mag_nm;
				$osusume_flg = $fetch->osusume_flg;
				$mag_agr_dt = $fetch->mag_agr_dt;
				$mag_pub_status_dt = $fetch->mag_pub_status_dt;
				$mag_pub_status_flg = $fetch->mag_pub_status_flg;
				$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
				$mag_riba_status_flg = $fetch->mag_riba_status_flg;
				$publisher_id = $fetch->publisher_id;
				$mag_original_flg = $fetch->mag_original_flg;
				$mag_type = $fetch->mag_type;
				$kako_cnt = $fetch->kako_cnt;

				if ($mag_pub_status_flg != '00' && $mag_pub_status_flg != '99' && 
				    $mag_pub_stop_flg != '02' && $publisher_id != 0) {
					$mark = "";
					if ($mag_pub_status_flg == '01') { 
						/* 仮登録はmag_pub_status_dtで新着確認 */
						if ( $mag_pub_status_dt > $mae ) { $mark .= "<img src=\"../img/shincyaku.gif\">"; }
						$mark .= "<img src=\"../img/kari.gif\">";
					}else{
						/* 仮登録以外はmag_agr_dtで新着確認 */
						if ($mag_agr_dt > $mae)				 { $mark .= "<img src=\"../img/shincyaku.gif\">";}
					}
					if ($osusume_flg == 1) { $mark = "<img src=\"../img/osusume.gif\">";}
					if (trim($kako_cnt) != "")			 { $mark .= "<img src=\"../img/osusumekako.gif\">";}
					if (check_Birthday($mag_agr_dt) == 1){ $mark .= "<img src=\"../img/birth.gif\">";}
					if ($mag_original_flg == "1") 	{ $mark .= "<img src=\"../img/meron.gif\">";}
					if ($mag_type == "02") 			{ $mark .= "<img src=\"../img/imode.gif\">";}
					if ($mag_riba_status_flg == 1) 	{ $mark .= "<img src=\"../img/point.gif\">";}
					if ($mark == "")
						$mark = "<br>";
					$lcount++;
					print "<tr>";
					print "<td width=\"57\" bgcolor=\"#e2f0c9\">";
					print "<div align=\"center\">$mag_id</div>";
					print "</td>";
					print "<td width=\"64\" bgcolor=\"white\">";
					print "<div align=\"center\">$mark</div>";
					print "</td>";
					print "<td width=\"355\" bgcolor=\"white\">";
					$fom_name =  "rmform" . sprintf("%03d", $lcount);
					print "<form name=\"$fom_name\" method=\"post\" action=\"mag-detail.php\">\n";
					print "<div align=\"left\">";
					print "<a href=\"javascript:document.$fom_name.submit();\">\n";
					print "<input type=\"hidden\" name=\"mag_id\" value=\"$mag_id\">\n";
					print "<input type=\"hidden\" name=\"reader_id\" value=\"$reader_id\">\n";
					print "<input type=\"hidden\" name=\"reader_pswd\" value=\"".trim($reader_pswd)."\">\n";
					print "<input type=\"hidden\" name=\"mail_addr\" value=\"".trim($mail_addr)."\">\n";
					print htmlspecialchars($mag_nm)."</a>";
					print "</div>";
					print "</td>";
					print "</form>\n";
					print "<td width=\"74\" bgcolor=\"white\">";
					$fom_name =  "rmform201" . sprintf("%03d", $lcount);
					print "<form name=$fom_name action=\"MelonpanRef.php\"  method=\"post\">\n";
					print "<div align=\"center\">";
					print "<input type=\"submit\" name=\"rmbott20001\" value=\"投稿\">\n";
					//print "--準備中--"; //仮
					print "</div>";
					print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
					print "<input type=\"hidden\" value=\"$reader_id\" name=\"reader_id\">\n";
					print "<input type=\"hidden\" value=\"".trim($mail_addr)."\" name=\"mail_addr\">\n";
					print "<input type=\"hidden\" value=\"".trim($reader_pswd)."\" name=\"reader_pswd\">\n";
					print "</td>";
					print "</form>\n";
					print "<td width=\"74\" bgcolor=\"white\">";
					$fom_name =  "rmform202" . sprintf("%03d", $lcount);
					print "<form name=$fom_name action=\"MelonpanRef.php\" method=\"post\">\n";
					print "<div align=\"center\">";
					print "<input type=\"submit\" name=\"rmbott20002\" value=\"解除\">\n";
					print "</div>";
					print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
					print "<input type=\"hidden\" value=\"$reader_id\" name=\"reader_id\">\n";
					print "<input type=\"hidden\" value=\"".trim($mail_addr)."\" name=\"mail_addr\">\n";
					print "<input type=\"hidden\" value=\"".trim($reader_pswd)."\" name=\"reader_pswd\">\n";
					print "</td>";
					print "</form>\n";
					print "</tr>";
				}
			}
		}
	}
}

/* ------------------------------------------------------ */
/* 	現在の購読数（マイページ表示）
/* ------------------------------------------------------ */
function get_my_mag_count ($mail_addr) {
	$sql = "SELECT count(*) as cnt FROM T_MAILADDR A, M_MAGAZINE M " 
		 . " WHERE A.mag_id = M.mag_id "
		 . "   AND M.publisher_id <> 0 "
		 . "   AND M.mag_pub_status_flg not in ( '00', '99' ) "
		 . "   AND M.mag_pub_stop_flg <> '02' "
		 . "   AND mail_addr = '".$mail_addr."'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$my_mag_count = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$my_mag_count = $fetch->cnt;
	}
	return $my_mag_count;
}

/* ------------------------------------------------------ */
/* 	誕生日判定
/* ------------------------------------------------------ */
#--- 誕生日判定 ---
function check_Birthday($base_date) {
	// $base_date = YY-MM-DD

	$flg = 0;
	for ( $i=-7 ; $i<7 ; $i++){
		$tmp_date = strftime("%y-%m-%d",time() + ($i*24*60*60));
		if ( substr($base_date,3,5) == substr($tmp_date,3,5) && substr($base_date,0,2) != substr($tmp_date,0,2) ){
			$flg = 1;
			break;
		}
	}
	return $flg;
}

?>
