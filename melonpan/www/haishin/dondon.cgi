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

#２週間前の日曜日
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time - (($wday+8)*24*60*60));
$mon++;
$year += 1900;
$nitiyoubi = sprintf("%04d%02d%02d",$year,$mon,$mday);

#二週間前
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time - (14*24*60*60));
$mon++;
$year += 1900;
$mae = sprintf("%04d%02d%02d",$year,$mon,$mday);

#３ヶ月前
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time - (90*24*60*60));
$mon++;
$year += 1900;
$before3m = sprintf("%04d%02d%02d",$year,$mon,$mday);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

#$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$sql = sprintf("truncate table t_mag_dondon");
$sth0 = $dbh->prepare($sql);
$sth0->execute;

#$sql = "COMMITT";
#$sth9 = $dbh->prepare($sql);
#$sth9->execute;
$sth0->finish;
#$sth9->finish;
##$dbh->commit or die $dbh->errstr;

#今週の配信記録を取得
$sql = "select mag_nm,to_char(mag_pub_status_dt,'yyyy.mm.dd'),mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg,to_char(MAG_AGR_DT,'yyyy.mm.dd') from M_MAGAZINE ";
$sql .= "where (mag_pub_status_flg = '03' or mag_pub_status_flg = '01' ) ";
$sql .= " and ( mag_pub_stop_flg <> '02' ) ";
$sql .= " and ( publisher_id <> 0 ) ";
$sql .= " and ( to_char(last_send_dt,'YYYYMMDD') >= '$before3m') ";
$sql .= " order by last_send_dt desc";
$sth = $dbh->prepare($sql);
$sth->execute;

$cnt = 1;
while (($mag_nm,$mag_pub_status_dt,$mag_id ,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg,$mag_agr_dt) = $sth->fetchrow_array) {
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

	#メールアドレス数を取得
#	$sql2 = "select count(*) from t_mailaddr where mag_id = '$mag_id'";
	$sql2 = "select count(*) from t_mailaddr where mag_id >= '$mag_id' and mag_id <= '$mag_id'";
	$sth3 = $dbh->prepare($sql2);
	$sth3->execute;
	$mag_count = $sth3->fetchrow;

	#先週以前の配信数を取得
#	$sql4 = "select send_volume from L_SEND,";
#	$sql4 .= "(select max(send_dt) max_send_dt from L_SEND where mag_id = '$mag_id'";
#	$sql4 .= " and to_char(send_dt,'YYYYMMDD') < '$nitiyoubi')";
#	$sql4 .= " where mag_id = '$mag_id' and send_dt=max_send_dt";
	$sql4 = "select send_volume from L_SEND JOIN "
		  . "(select max(send_dt) as max_send_dt from L_SEND where mag_id = '$mag_id'"
		  . " and to_char(send_dt,'YYYYMMDD') < '$nitiyoubi') V "
		  . " ON mag_id = '$mag_id' and send_dt=max_send_dt";
#print $sql4."\n";
	$sth4 = $dbh->prepare($sql4);
	$sth4->execute;
	($send_volume) = $sth4->fetchrow;
	if($send_volume eq ""){ $send_volume = 0;}
	$sql3 = "insert into t_mag_dondon values(?,?,?,?,?,?,?,?,?,?,current_timestamp)";
	$sth2 = $dbh->prepare($sql3);
	$sth2->execute($cnt,$mag_nm,$new_flg,$mag_id,$osusume_flg,$mag_pub_status_flg,$mag_riba_status_flg,$mag_pub_status_dt,$mag_count,$send_volume);
	$sth3->finish;
	$sth2->finish;
	$sth4->finish;
	$cnt++;
}
$sth->finish;
#$sql10 = "commit";
#$sth10 = $dbh->prepare($sql10);
#$sth10->execute;
#$sth10->finish;

$dbh->disconxxxt;
#print 0;
exit();
