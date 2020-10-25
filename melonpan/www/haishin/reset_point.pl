#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

require 'get_seq.pl';	# for tras_id

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{LongTruncOk} = 1;

my($reader_id, $publisher_id, $pt_melonpan);

# ポイント履歴削除
$sql = " truncate table T_POINT_DETAIL";
$sth4 = $dbh->prepare($sql);
$sth4->execute;
$sth4->finish;

# 読者ポイント作成
$sth = $dbh->prepare("select READER_ID, PT_MELONPAN from M_MELONPA where PT_MELONPAN > 0");
$sth->execute;
$sth->bind_columns(undef, \($reader_id, $pt_melonpan));
while ($sth->fetch) {
	&reg_point_detail("1", $reader_id, $pt_melonpan);
}
$sth->finish;

# 発行者ポイント作成
$sth = $dbh->prepare("select PUBLISHER_ID, PT_MELONPAN from M_PUBLISHER where PT_MELONPAN > 0");
$sth->execute;
$sth->bind_columns(undef, \($publisher_id, $pt_melonpan));
while ($sth->fetch) {
	&reg_point_detail("2", $publisher_id, $pt_melonpan);
}
$sth->finish;

# めろんぱいポイント作成
$sth = $dbh->prepare("select MELONPAI_ID, MELONPAI_POINT from M_MELONPAI where MELONPAI_POINT > 0");
$sth->execute;
$sth->bind_columns(undef, \($melonpai_id, $melonpai_point));
while ($sth->fetch) {
	&reg_point_detail("3", $melonpai_id, $melonpai_point);
}
$sth->finish;
$dbh->disconxxxt;

exit 0;


#ポイント詳細登録
sub reg_point_detail {
	local($owner_kind, $owner_id, $point) = @_;
	local($sqlp, $trans_id);

	#trans_id 取得
	$trans_id = get_seq($dbh, '0601');
	if ($trans_id == '') {
		return false;
	}

	#登録
	$sqlp = "insert into T_POINT_DETAIL "
		  . "(TRANS_ID, OWNER_KIND, OWNER_ID, ATTRIBUTION_CD, TRANS_DATE, TRANS_POINT, AVAILABLE_POINT) "
		  . "values ( $trans_id, $owner_kind, $owner_id, '00', CURRENT_TIMESTAMP, $point, $point)";
	$dbh->do($sqlp);
	return true;
}
