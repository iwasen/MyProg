#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

($sec2,$min2,$hour2,$mday2,$mon2,$year2) = localtime(time - (14*24*60*60));
$mon2++;
$year2 += 1900;
$mae = sprintf("%04d%02d%02d",$year2,$mon2,$mday2);

#$sql = sprintf("delete t_mag_top");
$sql = sprintf("truncate table T_MAG_TOP");
$sth0 = $dbh->prepare($sql);
$sth0->execute;
$sth0->finish;

###　新着：仮登録
$sql = "select mag_nm,to_char(mag_pub_status_dt,'yyyy.mm.dd'),mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg from M_MAGAZINE ";
$sql .= " where (mag_pub_status_flg = '01') ";
$sql .= " and ( mag_status_flg = '00' ) ";
$sql .= " and ( mag_riba_status_flg is null or mag_riba_status_flg = 0) ";
$sql .= " and ( mag_pub_stop_flg <> '02' ) ";
$sql .= " and ( publisher_id <> 0 ) ";
#$sql .= " and rownum <= 20 ";
$sql .= " order by mag_pub_status_dt desc";
$sth = $dbh->prepare($sql);
$sth->execute;

$cnt = 1;
while (($mag_nm,$mag_pub_status_dt,$mag_id ,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg) = $sth->fetchrow_array) {
	if($cnt == 21){last;}
#	$mag_pub_status_dt2 = $mag_pub_status_dt;
#	$mag_pub_status_dt2 =~ s/\.//g;
#	if($mag_pub_status_dt2 > $mae) { $new_flg = 1;}
#	else{ $new_flg = 0;}
	$new_flg = 0;	#仮登録は新着マークなしに変更
	if($osusume_flg != 1) { $osusume_flg = 0;}
	if($mag_riba_status_flg != 1){ $mag_riba_status_flg = 0;}
	$mag_nm =~ s/\'/\'\'/g;
	$bd_flg = 0;
	$kako_osusume_flg = 0;
	$sql = "insert into t_mag_top "
	     . "(TOP_ID, CNT, MAG_NM, NEW_FLG, MAG_ID, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	     . "MAG_PUB_STATUS_DT, MODIFY_DT, MAG_BIRTHDAY_FLG, OSUSUME_KAKO_FLG) "
	     . "values(1,$cnt,\'$mag_nm\',$new_flg,\'$mag_id\',$osusume_flg,\'$mag_pub_status_flg\', "
	     . "$mag_riba_status_flg,\'$mag_pub_status_dt\',current_timestamp, $bd_flg, $kako_osusume_flg) ";
	$sth2 = $dbh->prepare($sql);
	$sth2->execute;
	$sth2->finish;
	$cnt++;
}
$sth2->finish;
$sth->finish;

###　お勧め
#$sql = "select M.mag_nm, to_char(M.MAG_AGR_DT,'yyyy.mm.dd'), M.mag_id ,
#               M.osusume_flg, M.mag_pub_status_flg, M.mag_riba_status_flg, K.kako_cnt 
#          from M_MAGAZINE M, 
#		     (select mag_id, date_from from T_OSUSUME_LIST where date_to is NULL ) O,
#		     (select mag_id, count(*) as kako_cnt from T_OSUSUME_LIST where date_to is not NULL group by mag_id) K 
#         where (M.osusume_flg = 1 )  and ( M.mag_status_flg = '00' ) 
#           and (M.mag_pub_status_flg = '03' ) and ( M.mag_pub_stop_flg <> '02' ) and ( M.publisher_id <> 0 ) 
#           and M.mag_id = O.mag_id(+) 
#           and M.mag_id = K.mag_id(+) 
#         order by O.date_from desc ";
$sql = 
  "SELECT M.mag_nm, to_char(M.MAG_AGR_DT,'yyyy.mm.dd'), M.mag_id, M.osusume_flg, M.mag_pub_status_flg,"
 ."       M.mag_riba_status_flg, K.kako_cnt "
 ."  FROM M_MAGAZINE M "
 ."  LEFT JOIN (select mag_id, date_from from T_OSUSUME_LIST where date_to is NULL ) O ON M.mag_id = O.mag_id"
 ."  LEFT JOIN (select mag_id, count(*) as kako_cnt from T_OSUSUME_LIST where date_to is not NULL "
 ."             group by mag_id) K ON M.mag_id = K.mag_id"
 ." where (M.osusume_flg = 1 )  and ( M.mag_status_flg = '00' ) "
 ."   and (M.mag_pub_status_flg = '03' ) and ( M.mag_pub_stop_flg <> '02' ) and ( M.publisher_id <> 0 ) "
 ."  order by O.date_from desc ";
$sth = $dbh->prepare($sql);
$sth->execute;
$cnt = 1;
while (($mag_nm, $mag_agr_dt, $mag_id , $osusume_flg, $mag_pub_status_flg, $mag_riba_status_flg,
	    $o_cnt) = $sth->fetchrow_array) {
	if($cnt == 21){last;}
	$mag_agr_dt2 = $mag_agr_dt;
	$mag_agr_dt2 =~ s/\.//g;
	if($mag_agr_dt2 > $mae) { $new_flg = 1;}
	else{ $new_flg = 0;}
	if($osusume_flg != 1) { $osusume_flg = 0;}
	if($mag_riba_status_flg != 1){ $mag_riba_status_flg = 0;}
	$mag_nm =~ s/\'/\'\'/g;
	$bd_flg = check_Birthday($mag_agr_dt2);
	if ($o_cnt == " "){
		$kako_osusume_flg = 0;
	}else{
		$kako_osusume_flg = 1;
	}
	$sql = "insert into t_mag_top "
	     . "(TOP_ID, CNT, MAG_NM, NEW_FLG, MAG_ID, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	     . "MAG_PUB_STATUS_DT, MODIFY_DT, MAG_BIRTHDAY_FLG, OSUSUME_KAKO_FLG) "
	     . "values(2,$cnt,\'$mag_nm\',$new_flg,\'$mag_id\',$osusume_flg,\'$mag_pub_status_flg\', "
	     . "$mag_riba_status_flg,\'$mag_agr_dt\',current_timestamp, $bd_flg, $kako_osusume_flg) ";
	$sth2 = $dbh->prepare($sql);
	$sth2->execute;
	$sth2->finish;
	$cnt++;
}
$sth2->finish;
$sth->finish;

###　新着：本登録
$sql = "select mag_nm,to_char(MAG_AGR_DT,'yyyy.mm.dd'),mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg from M_MAGAZINE ";
$sql .= " where (mag_pub_status_flg = '03')";
$sql .= " and ( mag_status_flg = '00' ) ";
$sql .= " and ( mag_riba_status_flg is null or mag_riba_status_flg = 0) ";
$sql .= " and ( mag_pub_stop_flg <> '02' ) ";
$sql .= " and ( publisher_id <> 0 ) ";
#$sql .= " and rownum <= 20 ";
$sql .= " order by MAG_AGR_DT desc";
$sth = $dbh->prepare($sql);
$sth->execute;

$cnt = 1;
while (($mag_nm,$mag_pub_status_dt,$mag_id ,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg) = $sth->fetchrow_array) {
	if($cnt == 21){last;}
	$mag_pub_status_dt2 = $mag_pub_status_dt;
	$mag_pub_status_dt2 =~ s/\.//g;
	if($mag_pub_status_dt2 > $mae) { $new_flg = 1;}
	else{ $new_flg = 0;}
	if($osusume_flg != 1) { $osusume_flg = 0;}
	if($mag_riba_status_flg != 1){ $mag_riba_status_flg = 0;}
	$mag_nm =~ s/\'/\'\'/g;
	$bd_flg = 0;
	$kako_osusume_flg = 0;
	$sql = "insert into t_mag_top "
	     . "(TOP_ID, CNT, MAG_NM, NEW_FLG, MAG_ID, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	     . "MAG_PUB_STATUS_DT, MODIFY_DT, MAG_BIRTHDAY_FLG, OSUSUME_KAKO_FLG) "
	     . "values(3,$cnt,\'$mag_nm\',$new_flg,\'$mag_id\',$osusume_flg,\'$mag_pub_status_flg\', "
	     . "$mag_riba_status_flg,\'$mag_pub_status_dt\',current_timestamp, $bd_flg, $kako_osusume_flg) ";
	$sth2 = $dbh->prepare($sql);
	$sth2->execute;
	$sth2->finish;
	$cnt++;
}
$sth2->finish;
$sth->finish;

###　りばっちゃ
$sql = "select m.mag_nm,to_char(m.mag_pub_status_dt,'yyyy.mm.dd'),m.mag_id ,m.osusume_flg,m.mag_pub_status_flg,m.mag_riba_status_flg, m.company_nm from M_MAGAZINE m, M_PUBLISHER p ";
$sql .= "where (m.mag_riba_status_flg = 1 and m.publisher_id = p.publisher_id ) ";
$sql .= " and ( M.mag_pub_status_flg = '03' or M.mag_pub_status_flg = '01' ) ";
$sql .= " and ( M.mag_pub_stop_flg <> '02' ) ";
$sql .= " and ( M.publisher_id <> 0 ) ";
#$sql .= " and rownum <= 20 ";
$sql .= " order by m.mag_pub_status_dt desc";
$sth = $dbh->prepare($sql);
$sth->execute;

$cnt = 1;
while (($mag_nm,$mag_pub_status_dt,$mag_id ,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg) = $sth->fetchrow_array) {
	if($cnt == 21){last;}
	$mag_pub_status_dt2 = $mag_pub_status_dt;
	$mag_pub_status_dt2 =~ s/\.//g;
	if($mag_pub_status_dt2 > $mae) { $new_flg = 1;}
	else{ $new_flg = 0;}
	if($osusume_flg != 1) { $osusume_flg = 0;}
	if($mag_riba_status_flg != 1){ $mag_riba_status_flg = 0;}
    $mag_nm =~ s/\'/\'\'/g;
	$bd_flg = 0;
	$kako_osusume_flg = 0;
	$sql = "insert into t_mag_top "
	     . "(TOP_ID, CNT, MAG_NM, NEW_FLG, MAG_ID, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	     . "MAG_PUB_STATUS_DT, MODIFY_DT, MAG_BIRTHDAY_FLG, OSUSUME_KAKO_FLG) "
	     . "values(4,$cnt,\'$mag_nm\',$new_flg,\'$mag_id\',$osusume_flg,\'$mag_pub_status_flg\', "
	     . "$mag_riba_status_flg,\'$mag_pub_status_dt\',current_timestamp, $bd_flg, $kako_osusume_flg) ";
	$sth2 = $dbh->prepare($sql);
	$sth2->execute;
	$sth2->finish;
	$cnt++;
}
$sth2->finish;
$sth->finish;


###　どんどん増える(TOP_ID=5)
#$sql = "select D.mag_nm, D.mag_pub_status_dt, D.mag_id, "
#	 . "       D.osusume_flg, D.mag_pub_status_flg, D.mag_riba_status_flg, D.new_flg, K.kako_cnt, "
#	 . "      (D.mag_count - D.send_volume) as dondon_num, to_char(M.MAG_AGR_DT,'yyyy.mm.dd') "
#	 . "  from T_MAG_DONDON D, M_MAGAZINE M,"
#	 . "	  (select mag_id, count(*) as kako_cnt from T_OSUSUME_LIST where date_to is not NULL group by mag_id) K "
#	 . " where send_volume <> 0 "
#    . "   and D.mag_id = K.mag_id(+) "
#     . "   and D.mag_id = M.mag_id "
#	 . " order by dondon_num desc";
$sql = 
  "SELECT D.mag_nm, D.mag_pub_status_dt, D.mag_id, D.osusume_flg, D.mag_pub_status_flg, D.mag_riba_status_flg, "
 ."       D.new_flg, K.kako_cnt, (D.mag_count - D.send_volume) as dondon_num, to_char(M.MAG_AGR_DT,'yyyy.mm.dd') "
 ."  FROM T_MAG_DONDON D "
 ."  JOIN M_MAGAZINE M ON D.mag_id = M.mag_id"
 ."	 LEFT JOIN (select mag_id, count(*) as kako_cnt from T_OSUSUME_LIST where date_to is not NULL "
 ."             group by mag_id) K ON D.mag_id = K.mag_id"
 ." WHERE send_volume <> 0 "
 ." ORDER BY dondon_num desc";

$sth = $dbh->prepare($sql);
$sth->execute;
$cnt = 1;
while (($mag_nm, $mag_pub_status_dt, $mag_id , $osusume_flg, $mag_pub_status_flg, $mag_riba_status_flg,
	    $new_flg, $o_cnt, $snd_vol, $mag_agr_dt) = $sth->fetchrow_array) {
	if($cnt == 21){last;}

	$mag_nm =~ s/\'/\'\'/g;

	# 新着フラグ再設定
	if ($mag_agr_dt eq ''){
		$wk_date = $mag_pub_status_dt;
	}else{
		$wk_date = $mag_agr_dt;
	}
	$wk_date =~ s/\.//g;
	if($wk_date > $mae){ 
		$new_flg = 1;
	}else{ 
		$new_flg = 0;
	}

	# オススメフラグ
	if($osusume_flg != 1) { 
		$osusume_flg = 0;
	}
	if($mag_riba_status_flg != 1){ 
		$mag_riba_status_flg = 0;
	}

	# 過去オススメフラグ
	if ($o_cnt == " "){
		$kako_osusume_flg = 0;
	}else{
		$kako_osusume_flg = 1;
	}

	# 誕生日フラグ
	$mag_agr_dt =~ s/\.//g;
	$bd_flg = check_Birthday($mag_agr_dt);

	$sql = "insert into t_mag_top "
	     . "(TOP_ID, CNT, MAG_NM, NEW_FLG, MAG_ID, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	     . "MAG_PUB_STATUS_DT, MODIFY_DT, MAG_BIRTHDAY_FLG, OSUSUME_KAKO_FLG) "
	     . "values(5,$cnt,\'$mag_nm\',$new_flg,\'$mag_id\',$osusume_flg,\'$mag_pub_status_flg\', "
	     . "$mag_riba_status_flg,\'$mag_pub_status_dt\',current_timestamp, $bd_flg, $kako_osusume_flg) ";
	$sth2 = $dbh->prepare($sql);
	$sth2->execute;
	$sth2->finish;
	$cnt++;
}
$sth2->finish;
$sth->finish;

### コミット
#$sql = sprintf("commit");
#$sth7 = $dbh->prepare($sql);
#$sth7->execute;
#$sth7->finish;

$dbh->disconxxxt;

exit 0;

#--- 誕生日判定 ---
sub check_Birthday {
	local($base_date) = @_;
	local($tmp_date,$sec,$min,$hour,$mday,$mon,$year,$wday);

	$flg = 0;
	#1週間前
	for ( $i=-7 ; $i<7 ; $i++){
		($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time + ($i*24*60*60));
		$mon++;
		$year += 1900;
		$tmp_date = sprintf("%04d%02d%02d",$year,$mon,$mday);
		if ( substr($base_date,4,4) eq substr($tmp_date,4,4) && substr($base_date,0,4) ne substr($tmp_date,0,4) ){
			$flg = 1;
			last;
		}
	}
	return $flg;
}