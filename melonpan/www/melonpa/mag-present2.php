<?
include("../inc/com_rm_func.inc");
include("../sub/head.inc");
include("inc/database.php");
include("inc/present.inc");

/*-------------------------------------------
	キーワードの取得
---------------------------------------------*/
function get_keyword($conn, $present_id){

$sql = "SELECT "
	. "  PD_keyword1"
	. ", PD_keyword2"
	. ", PD_keyword3"
	. " FROM M_PRESENT_DATA "
	. " WHERE PD_present_id = '$present_id' ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$keyword1 = $fetch->pd_keyword1;
	$keyword2 = $fetch->pd_keyword2;
	$keyword3 = $fetch->pd_keyword3;
}
return array( $keyword1, $keyword2, $keyword3 );
}
/*-------------------------------------------
	キーワードの割当て
---------------------------------------------*/
function assign_keyword($code, $a_KeyWord ){

	reset ($a_KeyWord);
	for ( $i = 0 ; $i < 3 ; $i++ ) {
		$column = each ($a_KeyWord);
		$keyword = $column['value'];
		if ( keyword_check($code, $keyword) ) {
			return $keyword;
		}
	}
	return "";
}
/*-------------------------------------------
	マガジン購読確認(新規購読)
---------------------------------------------*/
function check_reader($conn, $mail_adr, 
                     $mag_id1, $mag_id2, $mag_id3){
$sql = "SELECT count(*) as cnt FROM T_MAILADDR "
	. " WHERE ( mag_id = '$mag_id1' and mail_addr = '$mail_adr' ) "
	. "   or  ( mag_id = '$mag_id2' and mail_addr = '$mail_adr' ) "
	. "   or  ( mag_id = '$mag_id3' and mail_addr = '$mail_adr' ) ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$cnt = $fetch->cnt;
}else{
	$cnt=0;
}
return $cnt;
}

/*-------------------------------------------
	マガジン購読確認(既存読者)
---------------------------------------------*/
function check_reader_kison($conn, $mail_adr, 
                     $mag_id1, $mag_id2, $mag_id3){

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id1' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id2' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id3' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

/*
	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id1' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id2' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id3' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}
*/
	return true;

}
/*=============================================================================

	プレゼント応募受付の登録

=============================================================================*/
//$conn = db_conxxxt();
$check_ok=false;
$print_msg = "";

/* echo "mail_adr=$mail_adr, oubo1=$oubo1, oubo2=$oubo2, oubo3=$oubo3"; debug */

$oubo1=trim($oubo1);
$oubo2=trim($oubo2);
$oubo3=trim($oubo3);
$mail_adr=trim(strtolower($mail_adr));

/*-----------------------------
	入力チェック	
-------------------------------*/
if (strlen($mail_adr) == 0) {
	$print_msg .= "■メールアドレスが入力されていないようです。<br>";
} else {
	if (strlen($mail_adr) <  6) $print_msg .= "■メールアドレスが短すぎるようです。<br>";
	if (strlen($mail_adr) > 50) $print_msg .= "■メールアドレスが長すぎるようです。<br>";
	if (chkmail_addr($mail_adr) == -1) $print_msg .= "■メールアドレスが正しく入力されていないようです。<br>";
}
if ($hitorigoto=="") $print_msg .= "■今日のひとりごとが入力されていないようです。<br>";
if ($oubo1=="") $print_msg .= "■応募券【１】が貼り付けられていないようです。<br>";
if ($oubo2=="") $print_msg .= "■応募券【２】が貼り付けられていないようです。<br>";
if ($oubo3=="") $print_msg .= "■応募券【３】が貼り付けられていないようです。<br>";
if ( $print_msg != "" ){
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	応募券の重複チェック
-------------------------------*/
if ($oubo1==$oubo2 || $oubo1==$oubo3 || $oubo2==$oubo3){
	$print_msg .= "■プレゼント応募券が重複しているようです。<br>";
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	応募券の解読
-------------------------------*/
if ( !decode_present($oubo1, $dec_1_present_id, $dec_1_mag_id) )
	$print_msg .= "■応募券【１】が無効のようです。<br>";
if ( !decode_present($oubo2, $dec_2_present_id, $dec_2_mag_id) )
	$print_msg .= "■応募券【２】が無効のようです。<br>";
if ( !decode_present($oubo3, $dec_3_present_id, $dec_3_mag_id) )
	$print_msg .= "■応募券【３】が無効のようです。<br>";

if ( $print_msg != "" ){
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	プレゼントIDの確認
-------------------------------*/
if ( $present_id != $dec_1_present_id ||
     $present_id != $dec_2_present_id ||
     $present_id != $dec_3_present_id  ) {

	$print_msg .= "■今回のプレゼント用ではない、応募券があるようです。<br>";
    include("../melonpa/mag-present2.html");
	exit;
}

/*-----------------------------
	キーワードチェック
-------------------------------*/
if ( $keyword_flag == "1" ){
	$array_keyword = get_keyword($conn, $present_id);

	$Keyword1=assign_keyword($oubo1, $array_keyword);
	$Keyword2=assign_keyword($oubo2, $array_keyword);
	$Keyword3=assign_keyword($oubo3, $array_keyword);
	
	if ($Keyword1==$Keyword2 || $Keyword1==$Keyword3 || $Keyword2==$Keyword3){
		$print_msg .= "■キーワードが３つ、揃っていないようです。<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}

/*-----------------------------
	メルマガ購読確認
-------------------------------*/
if ($present_type == "A"){
	//新規購読
	if ( check_reader($conn, $mail_adr, $dec_1_mag_id, $dec_2_mag_id, $dec_3_mag_id) != 3 ) {
		$print_msg .= "■このメールアドレスでは購読されていないメルマガの応募券があるようです。<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}else{
	//既存購読
	if ( !check_reader_kison($conn, $mail_adr, $dec_1_mag_id, $dec_2_mag_id, $dec_3_mag_id) ) {
		$print_msg .= "■このメールアドレスでは購読されていないメルマガの応募券があるようです。<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}

/*-----------------------------
	重複申し込みチェック
	受付時の機能?「１人１プレゼントにつき１回のみ」
-------------------------------*/
$sql = "SELECT "
	. "  to_char(PL_date, 'MM') as DATE_m "
	. ", to_char(PL_date, 'DD') as DATE_d "
	. " FROM T_PRESENT_LIST "
	. "WHERE PL_present_id = '$present_id' "
	. "  AND PL_mail_adr   = '$mail_adr'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$date_m = $fetch->date_m;
	$date_d = $fetch->date_d;
	$print_msg  = "■すでにこのメールアドレスでご応募いただいているようです。<br>";
//	$print_msg  = $mail_adr."さんの".$present_name."へのご応募は、";
//	$print_msg .= round($date_m)."月".round($date_d)."日に登録済となっております。";
	include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	申し込みデータ登録
-------------------------------*/
$sql  = "insert into T_PRESENT_LIST "
	."       ( PL_present_id, PL_mail_adr, PL_mag_id1, PL_mag_id2, PL_mag_id3, PL_date) "
	."values ('$present_id','$mail_adr','$dec_1_mag_id','$dec_2_mag_id','$dec_3_mag_id', 'now')";

if (!db_exec($sql)) {
	$print_msg = "■予期せぬエラーが発生しました。";
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	登録完了メッセージ表示
-------------------------------*/
$check_ok=true;
$print_msg  = $present_name."のご応募を受け付けました。当選者の発表は、";
$print_msg .= round(substr($lottery,5,2))."月".round(substr($lottery,8,2))."日頃、";
$print_msg .= "ご登録されたメールアドレスに連絡させていただきます。<BR>";
$print_msg .= "なお、残念ながら落選された方へのメールは、お送りしません。<BR>";
include("../melonpa/mag-present2.html");
?>
