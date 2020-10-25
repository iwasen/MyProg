<?php
include("../inc/com_db_func.inc");
include("inc/pass_check.inc");

function mk_sql($rank_kind){
	$sql = "select R.ranking ,M.mag_id, M.mag_nm, R.r_point from T_RANKING_LIST R, M_MAGAZINE M
			 where rank_id='$rank_kind' and R.mag_id = M.mag_id order by R.ranking";
	return $sql;
}
/****************************************************************
 Main
****************************************************************/

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}else{
	pass_check($gConn,trim($mail_addr),trim($reader_pswd));
}

if 	( $rank_id == "101" ): { 
	$rank_msg = "読者数Ranking" ;
	$sql = mk_sql("01");
}elseif	( $rank_id == "102" ):{ 

	$rank_msg = "読者が増えてるRanking";
	$sql = "SELECT mag_id, mag_id, mag_nm, rankpoint 
			FROM 
			( 
				select M.mag_id, M.mag_nm, X.rankpoint 
				from M_MAGAZINE M,
					(select mag_id, (mag_count - send_volume) as rankpoint from T_MAG_DONDON where send_volume <> 0) as X 
					where ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' ) 
		  			and ( M.mag_pub_stop_flg <> '02' ) 
		  			and ( M.publisher_id <> 0 ) 
		  			and M.mag_id = X.mag_id 
		  			and X.rankpoint > 0 
				order by X.rankpoint desc 
			) as P LIMIT 50";
			
}elseif	( $rank_id == "103" ):{ 
	$rank_msg = "推薦文が多いRanking" ;
	$sql = mk_sql("03");
}elseif	( $rank_id == "104" ):{ 
	$rank_msg = "解除率が低いRanking" ;
	$sql = mk_sql("11");
}elseif	( $rank_id == "105" ):{ 
	$rank_msg = "読者の平均年齢が低いRanking";
	$sql = mk_sql("07");
}elseif	( $rank_id == "106" ):{ 
	$rank_msg = "読者の平均年齢が高いRanking";
	$sql = mk_sql("08");
}elseif	( $rank_id == "201" ): {
	$rank_msg = "女性が読んでるRanking";
	$sql = mk_sql("04");
}elseif	( $rank_id == "202" ): { 
	$rank_msg = "学生が読んでるRanking";
	$sql = mk_sql("05");
}elseif	( $rank_id == "203" ):{
	$rank_msg = "ビジネスマンが読んでるRanking";
	$sql = mk_sql("06");
}elseif	( $rank_id == "204" ):{ 
	$rank_msg = "専門職が読んでるRanking";
	$sql = mk_sql("09");
}elseif	( $rank_id == "205" ):{ 
	$rank_msg = "主婦・主夫が読んでるRanking";
	$sql = mk_sql("10");
}else :
endif;

$ret = db_exec($sql);
$nrows = pg_numrows($ret);
if (!$ret) {
	$error_msg = $cibi_error_msg_6;
	exit();
}

if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}

/* 更新日取得 ---------------- */
if ( $rank_id == "102" ){ //T_MAG_DONDONのみ
	$sql = "SELECT to_char(modify_dt, 'MM/DD/HH24') as dt FROM T_MAG_DONDON "
		."WHERE send_volume <> 0 order by modify_dt desc";
	$ret2 = db_exec($sql);
	if (!$ret2) {
		$koushinbi  = "";
	}else{
		$fetch = pg_fetch_object($ret2, 0);
		$modify_dt = $fetch->dt;
		$koushinbi  = round(substr($modify_dt,0,2))."月";
		$koushinbi .= round(substr($modify_dt,3,2))."日 ";
		$koushinbi .= substr($modify_dt,6,2)."時現在";
	}
}
/* --------------------------- */

/* 対象データの説明文設定 ---------------- */
if ($rank_id == "101"):{
	$cmnt = "";
}elseif ($rank_id == "102"):{
	$cmnt = "";
}elseif ($rank_id == "103"):{
	$cmnt = "※過去6ヶ月間の推薦文を対象としています。";
}elseif ($rank_id == "104"):{
	$cmnt = "※読者50人以上のメルマガのみ表示されています。";
}else:
	$cmnt = "※Ｍｙめろんぱん登録20人以上のメルマガのみ表示されています。";
endif;

include("../search/kensaku-list_ranking.php");

?>
