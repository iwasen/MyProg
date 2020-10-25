#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

open(BAD,">double.txt");
	

$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time);
$mon++;
$year += 1900;
$now_time = sprintf("%04d%02d%02d",$year,$mon,$mday);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$cnt=0;
$sql = sprintf("select mag_id from m_magazine");
$sth0 = $dbh->prepare($sql);
$sth0->execute;
while (($mag_id) = $sth0->fetchrow_array) {
	$sql = sprintf("select count(*) from t_mailaddr where mag_id = '$mag_id'");
	$sth1 = $dbh->prepare($sql);
 	$sth1->execute;
	$count = $sth1->fetchrow;
	$sth1->finish;

	$sql = sprintf("select distinct count(*) from t_mailaddr where mag_id = '$mag_id'");
	$sth2 = $dbh->prepare($sql);
 	$sth2->execute;
	$count2 = $sth2->fetchrow;
	$sth2->finish;
	
	if($count != $count2) {
		print BAD $mag_id.",".$count.",".$count2."\n";
		$cnt++;
	}
}
$sth0->finish;

$dbh->disconxxxt;
close(BAD);
print $cnt;
exit();
