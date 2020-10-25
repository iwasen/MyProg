#!/usr/bin/perl

require 'jcode.pl';

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$sql = "select riba_id,mag_id,add_point from t_riba_send where add_flg = 0 ";
$sth1 = $dbh->prepare($sql);
$sth1->execute;
$cnt = 0;
while (($riba_id,$mag_id,$add_point) = $sth1->fetchrow_array) {
	if($add_point eq ""){ $add_point = 0;}
	if($mag_id eq ""){ next;}
	$sql2 = "select mail_addr from t_mailaddr where mag_id = '$mag_id'";
	$sth2 = $dbh->prepare($sql2);
	$sth2->execute;
	while (($mail_addr) = $sth2->fetchrow_array) {
		if($mail_addr eq ""){ next;}
		$sql3 = "update m_melonpa set pt_melonpan = pt_melonpan + $add_point where mail_addr = '$mail_addr'";
		$sth3 = $dbh->prepare($sql3);
		$sth3->execute;
		$sth3->finish;
	}
	$sql4 = "update t_riba_send set add_flg = 1 where riba_id = $riba_id";
	$sth4 = $dbh->prepare($sql4);
	$sth4->execute;
	$sth4->finish;
	$sth2->finish;
	$cnt++;
}
$sth1->finish;
$dbh->disconxxxt;
#print $cnt;
exit();


