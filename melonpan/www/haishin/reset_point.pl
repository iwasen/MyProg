#!/usr/bin/perl

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

require 'get_seq.pl';	# for tras_id

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{LongTruncOk} = 1;

my($reader_id, $publisher_id, $pt_melonpan);

# �ݥ����������
$sql = " truncate table T_POINT_DETAIL";
$sth4 = $dbh->prepare($sql);
$sth4->execute;
$sth4->finish;

# �ɼԥݥ���Ⱥ���
$sth = $dbh->prepare("select READER_ID, PT_MELONPAN from M_MELONPA where PT_MELONPAN > 0");
$sth->execute;
$sth->bind_columns(undef, \($reader_id, $pt_melonpan));
while ($sth->fetch) {
	&reg_point_detail("1", $reader_id, $pt_melonpan);
}
$sth->finish;

# ȯ�Լԥݥ���Ⱥ���
$sth = $dbh->prepare("select PUBLISHER_ID, PT_MELONPAN from M_PUBLISHER where PT_MELONPAN > 0");
$sth->execute;
$sth->bind_columns(undef, \($publisher_id, $pt_melonpan));
while ($sth->fetch) {
	&reg_point_detail("2", $publisher_id, $pt_melonpan);
}
$sth->finish;

# ����Ѥ��ݥ���Ⱥ���
$sth = $dbh->prepare("select MELONPAI_ID, MELONPAI_POINT from M_MELONPAI where MELONPAI_POINT > 0");
$sth->execute;
$sth->bind_columns(undef, \($melonpai_id, $melonpai_point));
while ($sth->fetch) {
	&reg_point_detail("3", $melonpai_id, $melonpai_point);
}
$sth->finish;
$dbh->disconxxxt;

exit 0;


#�ݥ���Ⱦܺ���Ͽ
sub reg_point_detail {
	local($owner_kind, $owner_id, $point) = @_;
	local($sqlp, $trans_id);

	#trans_id ����
	$trans_id = get_seq($dbh, '0601');
	if ($trans_id == '') {
		return false;
	}

	#��Ͽ
	$sqlp = "insert into T_POINT_DETAIL "
		  . "(TRANS_ID, OWNER_KIND, OWNER_ID, ATTRIBUTION_CD, TRANS_DATE, TRANS_POINT, AVAILABLE_POINT) "
		  . "values ( $trans_id, $owner_kind, $owner_id, '00', CURRENT_TIMESTAMP, $point, $point)";
	$dbh->do($sqlp);
	return true;
}
