#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

my($reader_id, $publisher_id, $p_diff, $pt_melonpan, $logfile);

# 日付取得
($sec, $min, $hour, $mday, $mon, $year) = localtime(time);
$mon++;
$year += 1900;
$logfile = './point_diff.log';

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 読者ポイント確認
$sql = "SELECT M.READER_ID, ( M.PT_MELONPAN - COALESCE(P.SUM, 0) ) as DIFF "
	 . "  FROM M_MELONPA M"
	 . "  LEFT JOIN (select OWNER_ID, sum(AVAILABLE_POINT) as SUM "
	 . "               from T_POINT_DETAIL where OWNER_KIND = '1' group by OWNER_ID ) P "
	 . " ON M.READER_ID = P.OWNER_ID and M.PT_MELONPAN - COALESCE(P.SUM, 0) <> 0 ";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($reader_id, $p_diff));
while ($sth->fetch) {
	&write_log("1", $reader_id, $p_diff);
}
$sth->finish;

# 発行者ポイント確認
$sql = "select M.PUBLISHER_ID, ( M.PT_MELONPAN - COALESCE(P.SUM, 0) ) as DIFF "
	 . "  from M_PUBLISHER M"
	 . "  left join (select OWNER_ID, sum(AVAILABLE_POINT) as SUM "
	 . "               from T_POINT_DETAIL where OWNER_KIND = '2' group by OWNER_ID ) P "
	 . "    on M.PUBLISHER_ID = P.OWNER_ID and M.PT_MELONPAN - COALESCE(P.SUM, 0) <> 0 ";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($publisher_id, $p_diff));
while ($sth->fetch) {
	&write_log("2", $publisher_id, $p_diff);
}
$sth->finish;

# めろんぱいポイント確認
$sql = "select M.MELONPAI_ID, ( M.MELONPAI_POINT - COALESCE(P.SUM, 0) ) as DIFF "
	 . "  from M_MELONPAI M"
	 . "  left join (select OWNER_ID, sum(AVAILABLE_POINT) as SUM "
	 . "               from T_POINT_DETAIL where OWNER_KIND = '3' group by OWNER_ID ) P "
	 . "    on M.MELONPAI_ID = P.OWNER_ID and M.MELONPAI_POINT - COALESCE(P.SUM, 0) <> 0 ";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($melonpai_id, $p_diff));
while ($sth->fetch) {
	&write_log("3", $melonpai_id, $p_diff);
}
$sth->finish;

$dbh->disconxxxt;

exit 0;

#=== ログ書き出し ===
sub write_log {
	local($o_kind, $o_id, $diff_point) = @_;
	local($logdata);

	$logdata = sprintf("%04d/%02d/%02d %02d:%02d %s %6d %10d",
			$year, $mon, $mday, $hour, $min, $o_kind, $o_id, $diff_point);

	&append_log($logfile, $logdata);
}

#=== ログファイルに追加 ===
sub append_log {
	local($filename, $logdata) = @_;

	if (open(LOG, ">>$filename")) {
		flock(LOG, 2);
		print LOG $logdata . "\n";
		flock(LOG, 8);
		close(LOG);
	}
}

