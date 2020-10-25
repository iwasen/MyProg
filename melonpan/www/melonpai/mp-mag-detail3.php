<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
//db_conxxxt();
mex_passchk($gConn, $melonpai_id, $melonpai_pswd);

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
		M.mag_bn_pub,
		M.mag_pub_stop_flg,
		M.osusume_flg
	from M_MAGAZINE M,M_MAG_CATG A,M_MAG_CATG B,M_MAG_BG C,M_MAG_BG D ";
	$sql .= "where M.mag_id = '$mag_id'";
	$sql .= "and M.mag_catg_1 = A.catg_id
	and M.mag_catg_2 = B.catg_id
	and M.mag_bg_1 = C.bg_id
	and M.mag_bg_2 = D.bg_id";
	$ret = db_exec($sql);
if (!pg_numrows($ret)) {
	$error_msg = $cibi_error_msg_6 . "—\Šú‚¹‚ÊƒGƒ‰[‚ª”­¶‚µ‚Ü‚µ‚½B";
	print $error_msg;
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
//$sql = "select rowidtochar(rowid) from L_BACKNUMBER
$sql = "select index_id from L_BACKNUMBER
		where mag_id = '$resarray[0]'
		order by modefy_dt desc";
$ret = db_exec($sql);
$nrow = pg_numrows($ret);
if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}

$xx = 0;
for ($i = 0; $i < $nrow; $i++) {
	$resarray_back= pg_fetch_array  ($ret, $i);
	$back_num[$xx] = $resarray_back[0];
	$xx++;
	if ( $xx >= 3 ) {
		break;
	}
}
include("tmpl_html/mp-mag-detail3.html");
?>
