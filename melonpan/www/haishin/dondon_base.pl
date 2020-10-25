#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

$ENV{'TZ'} = "JST-9";

#当日の日付
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time);
$mon++;
$year += 1900;
$now_time = sprintf("%04d%02d%02d",$year,$mon,$mday);

#二週間前
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time - (14*24*60*60));
$mon++;
$year += 1900;
$mae = sprintf("%04d%02d%02d",$year,$mon,$mday);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

#t_mag_dondon削除
$sth = $dbh->prepare("delete from t_mag_dondon");
$sth->execute;
$sth->finish;

#対象メルマガ取得
$sql = "select MAG_ID, MAG_NM, OSUSUME_FLG, MAG_PUB_STATUS_FLG, MAG_RIBA_STATUS_FLG, "
	 . "       to_char(MAG_PUB_STATUS_DT,'yyyy.mm.dd'), to_char(MAG_AGR_DT,'yyyy.mm.dd'),"
	 . "       coalesce(ZENKAI_READER_CNT, 0), coalesce(ZENZENKAI_READER_CNT, 0) "
	 . "  from M_MAGAZINE "
	 . " where ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) "
	 . "   and ( mag_pub_stop_flg <> '02' ) "
	 . "   and ( publisher_id <> 0 ) ";
$sth = $dbh->prepare($sql);
$sth->execute;

$cnt = 1;
while (($mag_id ,$mag_nm,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg,$mag_pub_status_dt,$mag_agr_dt,$zen_cnt, $zenzen_cnt) = $sth->fetchrow_array) {

	$mag_nm =~ s/ //g;
	$mag_pub_status_dt2 = $mag_pub_status_dt;
	$mag_pub_status_dt2 =~ s/\.//g;
	if($mag_pub_status_dt2 > $mae) { $new_flg = 1;}
	else{ $new_flg = 0;}
	$mag_agr_dt2 = $mag_agr_dt;
	$mag_agr_dt2 =~ s/\.//g;
	if($mag_agr_dt2 > $mae) { $new_flg = 1;}
	if($osusume_flg != 1) { $osusume_flg = 0;}
	if($mag_riba_status_flg != 1){ $mag_riba_status_flg = 0;}

	#現在読者数取得(今週)
#	$sql1 = "select count(*) from t_mailaddr where mag_id = '$mag_id'";
	$sql1 = "select count(*) from t_mailaddr where mag_id >= '$mag_id' and mag_id <= '$mag_id'";
	$sth1 = $dbh->prepare($sql1);
	$sth1->execute;
	$mag_count = $sth1->fetchrow;
	$sth1->finish;

	#T_MAG_DONDON更新
	$sql2 = "insert into t_mag_dondon values(?,?,?,?,?,?,?,?,?,?,current_timestamp)";
	$sth2 = $dbh->prepare($sql2);
	$sth2->execute($cnt, $mag_nm, $new_flg, $mag_id, $osusume_flg, $mag_pub_status_flg,
				   $mag_riba_status_flg, $mag_pub_status_dt, $mag_count, $zenzen_cnt);
	$sth2->finish;

	#読者数繰越(先週->先々週、現在->先週)
	$sql3 = "update M_MAGAZINE "
		  . "   set ZENZENKAI_READER_CNT = $zen_cnt, ZENKAI_READER_CNT = $mag_count"
		  . " where MAG_ID = '$mag_id'";
	$sth3 = $dbh->prepare($sql3);
	$sth3->execute;
	$sth3->finish;

	$cnt++;

}
$sth->finish;
$dbh->commit;

$dbh->disconxxxt;

exit();
