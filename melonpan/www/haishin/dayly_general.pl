#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# 日時取得
$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year) = localtime(time);
$mon++;
$year += 1900;
$today = sprintf("%04d/%02d/%02d",$year,$mon,$mday);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';
#print "\nDB SERVER NAME = $db_host\n\n";
# データベース接続
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 1 休刊マガジンの発行周期更新
$sql = "update M_MAGAZINE set MAG_CYCLE_DAY='休刊中' where MAG_STATUS_FLG = '01' and MAG_CYCLE_DAY <> '休刊中'";
$dbh->do($sql);


$dbh->disconxxxt;

