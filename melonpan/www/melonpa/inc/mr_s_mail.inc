<?php
include ("../inc/com_mail.inc");
include ("../melonpa/inc/present.inc");

// メロンパ登録
function mag_new_mail($mail_add_main, $login_key, $reader_id) {
	get_mail_template("melonpa ", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@login_key@", $login_key, $body);
	$body = str_replace("@reader_id@", $reader_id, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

// 購読確認
function mag_reg_mail($mail_addr, $mag_id, $mag_nm, $mail_id, $mod_time) {

	/* テンプレート取得 */
	if ( check_imode($mail_addr) ){
		get_mail_template("mel_reg2", $subject, $from_addr, $bcc_addr, $body);
		if (trim($body) =="")
			get_mail_template("mel_reg", $subject, $from_addr, $bcc_addr, $body);
	}else{
		get_mail_template("mel_reg", $subject, $from_addr, $bcc_addr, $body);
	}
	/* カテゴリの違うおすすめメルマガリスト取得 */
	$osusume_list = select_osusume($mag_id);
	
	/* メール作成と配信 */
	$to_addr = trim($mail_addr);

	$body .= present_footer($mag_id);

	$body = str_replace("@mag_id@", $mag_id, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);
	$body = str_replace("@date@", date("Y/m/d H:i:s"), $body);
	$body = str_replace("@mail_id@", $mail_id, $body);
	$body = str_replace("@mod_time@", $mod_time, $body);
	$body = str_replace("@osusume_intro@", $osusume_list, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

// プレゼント応募券フッタ
function present_footer($mag_id) {
	$footer = "";

	$sql = "select MAG_VOLUME from M_MAGAZINE where MAG_ID='$mag_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_volume = $fetch->mag_volume;
/* **
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$mag_volume = $select->field("MAG_VOLUME");
*/
		$sql = "select PD_present_id,PD_present_name,PD_keyword_flag,PD_keyword1,PD_keyword2,PD_keyword3"
			. " from M_PRESENT_DATA"
			. " where PD_status='0' and PD_present_type='A' and to_char(now(), 'YYYYMMDD') between to_char(PD_insert_start, 'YYYYMMDD') and to_char(PD_insert_end, 'YYYYMMDD')";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$PD_present_id = $fetch->pd_present_id;
			$PD_present_name = $fetch->pd_present_name;
			$PD_keyword_flag = $fetch->pd_keyword_flag;
			$PD_keyword1 = $fetch->pd_keyword1;
			$PD_keyword2 = $fetch->pd_keyword2;
			$PD_keyword3 = $fetch->pd_keyword3;
/* **
		$select = new select_sql($sql);
		if ($select->fetch()) {
			$fetch = pg_fetch_object($result, 0);
*/
//			if ($select->field("PD_keyword_flag") == "1") {
			if (PD_keyword_flag == "1") {

				srand((double)microtime() * 1000000);
//				$keyword = $select->field("PD_keyword" . rand(1, 3));
				$keyword = PD_keyword . rand(1, 3);
print "keyword=".$keyword;
	  			$mt_id = "pre_wk";
			} else {
				$keyword = "";
				$mt_id = "pre_nk";
			}

//			$code = encode_present($select->field("PD_present_id"), $mag_id, $keyword, $mag_volume);
			$code = encode_present($PD_present_id, $mag_id, $keyword, $mag_volume);

			get_mail_template($mt_id, $subject, $from_addr, $bcc_addr, $footer);
			$footer = "\n\n" . $footer;
//			$footer = str_replace("@present_nm@", $select->field("PD_present_name"), $footer);
			$footer = str_replace("@present_nm@", $PD_present_name, $footer);
			$footer = str_replace("@keyword@", $keyword, $footer);
			$footer = str_replace("@appl_cd@", $code, $footer);
		}
	}
	return $footer;
}

// パスワード通知
function pass_send_mail($mail_addr, $reader_pswd) {
	get_mail_template("pass", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_addr);

	$body = str_replace("@mail_addr@", $mail_addr, $body);
	$body = str_replace("@reader_pswd@", $reader_pswd, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

// めろんぱん・レター購読
function mag_let_mail($mail_addr, $mag_id, $mag_nm) {
	get_mail_template("let", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_addr);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

// バキューン購読
function mag_baq_mail($mail_addr, $mag_id, $mag_nm) {
	get_mail_template("baq", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_addr);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

// 携帯アドレスの判定
function check_imode($mail_addr) {

	$sql = "select count(*) as CNT from T_MAILADDR "
		 . "where MAIL_ADDR = '$mail_addr' and "
		 . "    ( MAIL_ADDR Like '%@docomo.ne.jp%' "
		 . "   or MAIL_ADDR Like '%ezweb.ne.jp%' "
		 . "   or MAIL_ADDR Like '%@jp-%.ne.jp%' "
		 . "   or MAIL_ADDR Like '%@email.sky.%' "
		 . "   or MAIL_ADDR Like '%@sky.tu-ka.ne.jp%' "
		 . "   or MAIL_ADDR Like '%@mozio.ne.jp%' "
		 . "   or MAIL_ADDR Like '%@phone.ne.jp%' )";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$cnt = $fetch->cnt;
/* **
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$cnt = $select->field("CNT");
*/
		if ( $cnt != 0 ) return true;
	}
	return false;
}

// 携帯アドレスの判定(共通)
function IsPortableAddr($email) {

	if ( eregi("@docomo.ne.jp", $email) 
	  || eregi("ezweb.ne.jp", $email)
	  || eregi("@jp-([0-9a-zA-Z]{1}).ne.jp", $email)
	  || eregi("@email.sky.", $email)
	  || eregi("@sky.tu-ka.ne.jp", $email) ){
		return true;
	}
	return false;
}

// 現在のおすすめマガジン取得
function select_osusume($mag_id) {

	// 購読マガジンのカテゴリ取得
	$ret = "";
	$sql = "select MAG_CATG_1, MAG_CATG_2 from M_MAGAZINE "
		 . " where MAG_ID = '$mag_id'";
	$result = db_exec($sql);
	if (!pg_numrows($result))
/* **
	$select = new select_sql($sql);
	if (!$select->fetch()) 
*/
		return $ret;

	// カテゴリの違うおすすめ取得
	$fetch = pg_fetch_object($result, 0);
	$cat1 = $fetch->mag_catg_1;
	$cat2 = $fetch->mag_catg_2;
/* **
	$cat1 = $select->field("MAG_CATG_1");
	$cat2 = $select->field("MAG_CATG_2");
*/
	$sql = "select MAG_NM, MAG_HP, MAG_INTRO_SHORT from M_MAGAZINE "
		 . " where OSUSUME_REASON is not null "
		 . "   and MAG_CATG_1 not in ( '$cat1', '$cat2' ) "
		 . "   and MAG_CATG_2 not in ( '$cat1', '$cat2' ) ";

/* **
	$conn = db_conxxxt();
	$stmt = OCIParse($conn,$sql);
	if (!OCIExecute($stmt)) 
*/
	if (!$result = db_exec($sql))
		return $ret;
	$nrows = pg_numrows($result);

/* **
	$nrows = OCIFetchStatement($stmt, &$results);
	OCIFreestatement($stmt);
	db_logoff();
*/
	// 任意の３誌選択
/* **
	if ( $nrows > 3 ){
		mt_srand((double)microtime()*1000000);
		$no1 = mt_rand( 1, $nrows );
		$no2 = mt_rand( 1, $nrows );
		$no3 = mt_rand( 1, $nrows );
		while($no1 == $no2 or $no2 == $no3 or $no1 == $no3){
			if ($no1 == $no2)	$no1 = mt_rand( 1, $nrows );
			if ($no2 == $no3)	$no2 = mt_rand( 1, $nrows );
			if ($no1 == $no3)	$no1 = mt_rand( 1, $nrows );
		}
	}
*/	
/* **
	if ( $nrows > 3 ){ $nrows = 3; }
*/
	// 紹介リスト作成
/* **
	for ( $i = 0 ; $i < $nrows ; $i++ ) {
		reset ($results);
		$column = each ($results);	$mag_nm = $column['value'];
		$column = each ($results);	$mag_hp = $column['value'];
		$column = each ($results);	$intros = $column['value'];

		$ret .= "★".trim($mag_nm[$i]) ."\n" ;
		if (trim($mag_hp[$i]) <> "http://" && trim($mag_hp[$i]) <> ""){
			$ret .= $mag_hp[$i] ."\n" ;
		}
		$wks = str_replace("\r\n", "",$intros[$i]);
		$wks = str_replace("\n", "",$wks);
		$cmnt = ""; $row = 1;
		for ( $j = 0 ; $j < mbstrlen($wks) ; $j++ ){
			$cmnt .= mbsubstr($wks, $j, 1);
			if ( strlen($cmnt) > ($row * 70) ){
				$cmnt .= "\n";
				$row++;
			}
		}
*/
		/* (最後の文字が取得できない？) --------------- */
/* **
		$wks2 = str_replace("\n", "",$cmnt);
		if (strlen($wks2) < strlen($wks)){
			$cmnt .= substr($wks, strlen($wks2) - strlen($wks));
		}
*/
		/* -------------------------------------------- */
/* **
		$ret .= $cmnt ."\n\n" ;
	}
	return $ret;
*/
}


?>
