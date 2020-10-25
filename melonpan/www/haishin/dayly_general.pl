#!/usr/bin/perl

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

# ��������
$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year) = localtime(time);
$mon++;
$year += 1900;
$today = sprintf("%04d/%02d/%02d",$year,$mon,$mday);

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';
#print "\nDB SERVER NAME = $db_host\n\n";
# �ǡ����١�����³
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 1 �ٴ��ޥ������ȯ�Լ�������
$sql = "update M_MAGAZINE set MAG_CYCLE_DAY='�ٴ���' where MAG_STATUS_FLG = '01' and MAG_CYCLE_DAY <> '�ٴ���'";
$dbh->do($sql);


$dbh->disconxxxt;

