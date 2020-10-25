#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$sql = "update t_mag_dondon set mag_count=(select count(*) from t_mailaddr where t_mailaddr.mag_id=t_mag_dondon.mag_id)";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->finish;

$dbh->commit;
$dbh->disconxxxt;
exit();
