#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 週発行部数
$sth = $dbh->prepare("SELECT COALESCE(SUM(SEND_VOLUME),0) AS SUM_VOLUME FROM L_SEND WHERE SEND_DT>date('now')-7");
$sth->execute;
$sum_volume = $sth->fetchrow;
$sth2 = $dbh->prepare("UPDATE L_MAG_SUM SET SUM = $sum_volume WHERE SUM_ID = 1");
$sth2->execute;

# メルマガ数(休刊除く)
$sth3 = $dbh->prepare("SELECT count(*) AS M_MAGCOUNT FROM M_MAGAZINE WHERE MAG_PUB_STATUS_FLG = \'03\' and mag_pub_stop_flg <> \'02\' and mag_pub_stop_flg <> \'01\' and mag_status_flg = \'00\'" );
$sth3->execute;

$m_magcount = $sth3->fetchrow;
$sth4 = $dbh->prepare("UPDATE L_MAG_SUM SET SUM = $m_magcount WHERE SUM_ID = 2");
$sth4->execute;

# 総読者数
$sth5 = $dbh->prepare("SELECT count(*) AS M_MAILCOUNT FROM T_MAILADDR where mag_id <> \'000013\' and mag_id <> \'000049\'");
$sth5->execute;

$m_mailcount = $sth5->fetchrow;
$sth6 = $dbh->prepare("UPDATE L_MAG_SUM SET SUM = $m_mailcount WHERE SUM_ID = 3");
$sth6->execute;

$sth6->finish;
$sth5->finish;
$sth4->finish;
$sth3->finish;
$sth2->finish;
$sth->finish;

#$sql = sprintf("commit");
#$sth7 = $dbh->prepare($sql);
#$sth7->execute;
#$sth7->finish;
$dbh->commit;

$dbh->disconxxxt;

exit 0;
