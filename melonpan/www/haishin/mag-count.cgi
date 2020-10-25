#!/usr/bin/perl 

require 'jcode.pl';
require 'mimew.pl';

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$sql = "select catg_id from m_mag_catg";
$sth1 = $dbh->prepare($sql);
$sth1->execute;
$cnt = 0;
while (($catg_id) = $sth1->fetchrow_array) {
	%catg_list = ();
	$catg_list[$cnt] = $catg_id;
	$cnt++;
}
$sth1->finish;

$cnt = 0;
foreach $catg_id (@catg_list){
	if($catg_id eq ""){ next;}
	unless($catg_id =~ /00$/){
		$sql2 = "select count(*) As COUNT from m_magazine where (mag_catg_1 = '".$catg_id."' or mag_catg_2 = '".$catg_id."')";
		$sql2 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql2 .= " and ( mag_pub_stop_flg <> '02' )";
		$sql2 .= " and ( publisher_id <> 0 )";
		$sth2 = $dbh->prepare($sql2);
		$sth2->execute;
		while (($count) = $sth2->fetchrow_array) {
			$sql3 = "update m_mag_catg set catg_volume = $count where catg_id = '$catg_id'";
			$sth3 = $dbh->prepare($sql3);
			$sth3->execute;
			$count = "";
			$sth3->finish;
		}
		$sth2->finish;
	}else{
		@skey = split(//,$catg_id);
		$skey = $skey[0].$skey[1]."00";
		$ekey = $skey[0].$skey[1]."99";
		$sql4 = "select count(*) As COUNT from M_MAGAZINE where (( mag_catg_1 >= '$skey' and mag_catg_1 <= '$ekey' ) or ( mag_catg_2 >= '$skey' and mag_catg_2 <= '$ekey' )) ";
		$sql4 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql4 .= " and ( mag_pub_stop_flg <> '02' )";
		$sql4 .= " and ( publisher_id <> 0 )";
		$sth4 = $dbh->prepare($sql4);
		$sth4->execute;
		while (($count) = $sth4->fetchrow_array) {
			$sql5 = "update m_mag_catg set catg_volume = $count where catg_id = '$catg_id'";
			$sth5 = $dbh->prepare($sql5);
			$sth5->execute;
			$count = "";
			$sth5->finish;	
		}
		$sth4->finish;
	}
	$cnt++;
}
#print $cnt;
exit();
