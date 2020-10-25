<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("../melonpa/inc/pass_check.inc");


function riba_zokusei_chk($mag_id) {
	$conn = db_conxxxt();
	$sql = "select m.mag_riba_status_flg,r.age,r.sex,r.mrg,r.ocp,r.ara,r.ex ";
	$sql .= " from m_magazine m ,m_ribaoptin r ";
	$sql .= " where m.mag_id ='$mag_id' and m.mag_id = r.mag_id ";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "MAG_RIBA_STATUS_FLG", &$mag_riba_status_flg);
	OCIDefinebyname($stmt, "OPT_ID", &$opt_id);
	OCIDefinebyname($stmt, "AGE", &$age);
	OCIDefinebyname($stmt, "SEX", &$sex);
	OCIDefinebyname($stmt, "MRG", &$mrg);
	OCIDefinebyname($stmt, "OCP", &$ocp);
	OCIDefinebyname($stmt, "ARA", &$ara);
	OCIDefinebyname($stmt, "EX", &$ex);
	OCIExecute($stmt, OCI_DEFAULT);
	$ct = OCIFetch($stmt);
	if ($mag_riba_status_flg == 1) {
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

		$riba_zokusei = "";
		if (count($sex_all) != 0) {
			while (list($key,$val) = each($sex_all)) {
				if ($val == 1) { $riba_zokusei .= "ÃËÀ­¡¿"; }
				if ($val == 2) { $riba_zokusei .= "½÷À­¡¿"; }
			}
		}

		if (count($mrg_all) != 0) {
			while (list($key,$val) = each($mrg_all)) {
				if ($val == 1) { $riba_zokusei .= "Ì¤º§¡¿"; }
				if ($val == 2) { $riba_zokusei .= "´ûº§¡¿"; }
			}
		}
		$ara_flg = 0;
		if (count($ara_all) != 0) {
			while (list($key,$val) = each($ara_all)) {
				$sql = "select area from m_zipcode where area_id = '$val'";
				$stmt = OCIParse($conn, $sql);
				OCIDefinebyname($stmt, "AREA", &$area);
				OCIExecute($stmt, OCI_DEFAULT);
				$ct = OCIFetch($stmt);
				$riba_zokusei .= $area."¡¿";
				OCIFreeStatement($stmt);
			}
		}
		$ocp_flg = 0;
		if (count($ocp_all) != 0) {
			while (list($key,$val) = each($ocp_all)) {
				$sql = "select occup from m_mag_occup where occup_id = '$val'";
				$stmt = OCIParse($conn, $sql);
				OCIDefinebyname($stmt, "OCCUP", &$occup);
				OCIExecute($stmt, OCI_DEFAULT);
				$ct = OCIFetch($stmt);
				$riba_zokusei .= $occup."¡¿";
				OCIFreeStatement($stmt);
			}
		}
		$age_flg = 0;
		if (count($age_all) != 0) {
			while (list($key,$val) = each($age_all)) {
				$riba_zokusei .= $val."Âå¡¿";
			}
		}
	}
	db_logoff();
	return $riba_zokusei;
}

function user_zokusei_chk($mail_addr) {
	if ($mail_addr == "") {
		$error_msg = "Â°À­¤ÏÅÐÏ¿¤µ¤ì¤Æ¤¤¤Þ¤»¤ó";
		return $error_msg;
	} else {
		$conn = db_conxxxt();
		$sql = "select reader_sex,reader_mrg,reader_zip1,reader_occup,to_char(reader_birth,'yyyy') as reader_birth from m_melonpa where mail_addr = '$mail_addr'";
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "READER_SEX", &$reader_sex);
		OCIDefinebyname($stmt, "READER_MRG", &$reader_mrg);
		OCIDefinebyname($stmt, "READER_ZIP1", &$reader_zip1);
		OCIDefinebyname($stmt, "READER_OCCUP", &$reader_occup);
		OCIDefinebyname($stmt, "READER_BIRTH", &$reader_birth);
		OCIExecute($stmt, OCI_DEFAULT);
		$ct = OCIFetchStatement($stmt,&$resarray);
		if ($ct != 0) { $riba_flg = 1; }
		OCIFreeStatement($stmt);
		$unix_now_time = strftime("%Y",time());
		$unix_age = ($unix_now_time - $reader_birth);

		if ($reader_sex == 1) {
			$reader_sex_j = "ÃËÀ­¡¿";
		} elseif ($reader_sex == 2) {
			$reader_sex_j = "½÷À­¡¿";
		}

		if ($reader_mrg == 1) {
			$reader_mrg_j = "Ì¤º§¡¿";
		} elseif ($reader_mrg == 2) {
			$reader_mrg_j = "´ûº§¡¿";
		}

		$sql = "select area from m_zipcode where zipcode = '$reader_zip1'";
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "AREA", &$area);
		OCIExecute($stmt, OCI_DEFAULT);
		$ct = OCIFetch($stmt);
		$area_j .= $area."¡¿";
		OCIFreeStatement($stmt);

		$sql = "select occup from m_mag_occup where occup_id = '$reader_occup'";
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "OCCUP", &$occup);
		OCIExecute($stmt, OCI_DEFAULT);
		$ct = OCIFetch($stmt);
		$occup_j = $occup."¡¿";
		OCIFreeStatement($stmt);

		if ($unix_age == "") { $user_birth = "";}
		elseif ($unix_age >= 60 && $unix_age < 70) { $user_birth = "60Âå¡¿"; }
		elseif ($unix_age >= 50 && $unix_age < 60) { $user_birth = "50Âå¡¿";}
		elseif ($unix_age >= 40 && $unix_age < 50) { $user_birth = "40Âå¡¿";}
		elseif ($unix_age >= 30 && $unix_age < 40) { $user_birth = "30Âå¡¿";}
		elseif ($unix_age >= 20 && $unix_age < 30) { $user_birth = "20Âå¡¿";}
		elseif ($unix_age >= 10 && $unix_age < 20) { $user_birth = "10Âå¡¿";}

		$user_zokusei = $reader_sex_j.$reader_mrg_j.$area_j.$occup_j.$user_birth;
		db_logoff();
		return $user_zokusei;
	}
}

/*********************************************************************************
			Main
*********************************************************************************/
if ($mail_addr == "") {
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
	$mail_addr = $cookie_mail_addr;
}

if ($riba == 1) {
	if ($mail_addr == "") {
		$no_riba_flg = 1;
		include ("../melonpan/melonpa-login.html");
		exit();
	}
}

/* test
echo "mail_addr = \"" .$mail_addr ."\"";
if ($mail_addr == "") {
	$mail_addr = "aa@aa.aa";
	$reader_pswd = "password";
	$reader_id = "0000";
}
*/
/*
if ($mail_addr != "") {
	if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
}
*/
$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
$input_data = "";

//
// Get Detail
//
$sql = "select distinct M.mag_id,
		M.mag_nm,
		M.mag_intro_long,
		M.mag_cycle_day,
		M.mag_catg_1,
		M.mag_catg_2,
		M.mag_volume,
		to_char(M.last_send_dt,'YYYY/MM/DD  HH24:MI'),
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
		M.mag_bn_pub,
		M.mag_status_flg
	from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B,M_MAG_BG C,M_MAG_BG D ";
if ($mag_rid != "") {
	$sql .= "where M.rowid = '$mag_rid' ";
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

// ÇÑ´©¥Á¥§¥Ã¥¯ 2003/04/30
if ($resarray['mag_status_flg'] == '99') {
	$p_titl = '¥á¥ë¥Þ¥¬¾ÜºÙ';
	$print_msg = '¤³¤Á¤é¤Î¥á¥ë¥Þ¥¬¤Ï¤¹¤Ç¤ËÇÑ´©¤µ¤ì¤Æ¤ª¤ê¤Þ¤¹¡£';
	include("tmpl_html/err-msg.html");
	exit;
}

//
// Get Amount of Reader
//
//$sql = "select count(*) from T_MAILADDR where mag_id>='$resarray[0]' and mag_id<='$resarray[0]'";
$sql = "select S_COUNT from S_MAILADDR where MAG_ID='$resarray[0]' AND S_KEY='ALL'";
$ret = db_exec($sql);

if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}

if (pg_numrows($ret))
	$resarray_mail= pg_fetch_array ($ret, 0);
$resarray_mail[0] = number_format($resarray_mail[0]);

//
// Get BackNumber
//
$sql = "select index_id from L_BACKNUMBER where mag_id = '$resarray[0]' and modefy_dt > current_timestamp - 365"
	 . " order by modefy_dt desc";
$stmt_back = db_exec($sql);
$nrow = pg_numrows($stmt_back);

$xx = 0;

for ($xx = 0; $xx < $nrow; $xx++) {
	$array_back_num = pg_fetch_array($stmt_back,$xx);
	$back_num[$xx] = $array_back_num[0];
	if ( $xx >= 3 ) {
		break;
	}
}

# 1Ç¯°ÊÆâ¤Ë³ºÅö¤¬¤Ê¤¯¤Æ¤âºÇ¿·¤À¤±¼èÆÀ
if ( $xx == 0){
	$sql = "select index_id from L_BACKNUMBER where mag_id = '$resarray[0]' order by modefy_dt desc";

	$stmt_back = db_exec($sql);
	$nrow = pg_numrows($stmt_back);

	if ( $nrow != 0 ) {
		$array_back_num = pg_fetch_array($stmt_back,$xx);
		$back_num[$xx] = urlencode($array_back_num[0]);
		$xx++;
	}
}

//
// Get Recommend
//
if ($last_recomend_id == "") { $last_recomend_id = 99999999;}//9999999999999999¢ª99999999
$sql = "select count(*) as COUNT from T_RECOMEND
		where mag_id = '$resarray[0]' and open_flg = 1 ";
$ret = db_exec($sql);

if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}
if (pg_numrows($ret)) {
	$fetch = pg_fetch_object($ret, 0);
	$nrows = $fetch->count;
}

$sql = "select recomend_id,recom_titl,recom_text,recom_nic from T_RECOMEND
		where mag_id = '$resarray[0]' and open_flg = 1 and recomend_id < $last_recomend_id ";
$sql .= " order by recomend_id desc";

$ret = db_exec($sql);
if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}
$count_num = 0;
$reco_data = "";

$nrow = pg_numrows($ret);
for ($i = 0; $i < $nrow; $i++) {
	$resarray_reco= pg_fetch_array ($ret, $i);
	$count_num++;
	$reco_data .= "<p><font size=\"3\">\n";
//	$reco_data .= "$resarray_reco[0]\n";
	$reco_data .= "¢£".htmlspecialchars($resarray_reco[1])."<br>\n";
	$reco_data .= htmlspecialchars($resarray_reco[2]);
	$reco_data .= "¡Ê".htmlspecialchars($resarray_reco[3])."¡Ë\n";;
	$reco_data .= "</font></p>\n";
	$last_rec_id = $resarray_reco[0];
	if ( $count_num > 4 ) {
		break;
	}
}

if ( $nrows > 5 ) {
	$reco_res = "<div align=\"right\">\n";
	$reco_res .= "<a href=\"../melonpa/recommend_all.php?mag_id=".$resarray[0]."&mag_nm=".urlencode($resarray[1])."&last_recomend_id=$last_rec_id&nrows=$nrows\">\n";
	$reco_res .= "¤½¤ÎÂ¾¤Î¿äÁ¦Ê¸¤òÆÉ¤à&gt;&gt;&gt;</a><br>\n";
	$reco_res .= "</div>\n";
}

include("mag-detail.html");

?>

