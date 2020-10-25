#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

my($owner_kind, $owner_id, $point, $result_f, $logfile);
$logfile = './point_update.log';

# 日付取得
($sec, $min, $hour, $mday, $mon, $year) = localtime(time);
$mon++;
$year += 1900;
$process_date = sprintf("%04d.%02d.%02d",$year,$mon,$mday);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 読者ポイント確認
$sql = "select P.RP_OWNER_KIND, P.RP_OWNER_ID, COALESCE(P.SUM, 0) as POINT "
	 . "  from (select RP_OWNER_KIND, RP_OWNER_ID, sum(RP_AVAILABLE_POINT) as SUM from T_RESERVE_PT_DETAIL "
	 . "        where to_char(RP_TRANS_DATE, 'YYYY.MM.DD') = '$process_date' and RP_RESERVE_FLG = 0"
	 . "        group by RP_OWNER_KIND, RP_OWNER_ID ) P "
	 . " order by P.RP_OWNER_KIND, P.RP_OWNER_ID";
$sth = $dbh->prepare($sql);
$sth->execute;
if ($sth->state != 0) {
	exit;
}
$sth->bind_columns(undef, \($owner_kind, $owner_id, $point));
while ($sth->fetch) {

	$result_f = '1';
	#現在値更新
	if (!upd_master($owner_kind, $owner_id, $point)) {
		$result_f = '2';
	}else{
		#詳細書込
		if (!ins_detail($owner_kind, $owner_id)) {
			$result_f = '2';
		}
	}

	#結果登録
	$sql3 = "update T_RESERVE_PT_DETAIL set RP_RESERVE_FLG = '$result_f' "
		 . "  where RP_OWNER_KIND = '$owner_kind' and RP_OWNER_ID = $owner_id "
		 . "    and to_char(RP_TRANS_DATE, 'YYYY.MM.DD') = '$process_date' and RP_RESERVE_FLG = 0";

	$sth3 = $dbh->prepare($sql3);
	$sth3->execute;
	$ErrFlg = $sth3->state;
	if ($ErrFlg != 0) {
		&write_log("DB ERROR owner_kind:$owner_kind owner_id:$owner_id at update T_RESERVE_PT_DETAIL");
		exit 1;
	}
	$sth3->finish;
}
$sth->finish;

$dbh->disconxxxt;

exit 0;

#=== 詳細登録 ===
sub ins_detail {
	local($o_kind, $o_id) = @_;
	local($sqlx, $sthx, $rows, $eFlg);

	$sqlx = "insert into T_POINT_DETAIL "
		  . "(select R.RP_TRANS_ID, R.RP_OWNER_KIND, R.RP_OWNER_ID, R.RP_ATTRIBUTION_CD, R.RP_TRANS_DATE, "
		  . "        R.RP_TRANS_POINT, R.RP_AVAILABLE_POINT, R.RP_COUNTER_ID, R.RP_MAG_ID, R.RP_REMARK "
		  . "   from T_RESERVE_PT_DETAIL R "
		  . "  where R.RP_OWNER_KIND = '$o_kind' and R.RP_OWNER_ID = $o_id "
		  . "    and to_char(R.RP_TRANS_DATE, 'YYYY.MM.DD') = '$process_date' "
		  . "    and R.RP_RESERVE_FLG = 0)";
	$sthx = $dbh->prepare($sqlx);
	$rows = $sthx->execute;
	$eFlg = $sthx->state;
	$sthx->finish;
	if ($eFlg != 0) {
		&write_log("DB ERROR owner_kind:$o_kind owner_id:$o_id at insert T_POINT_DETAIL result=$rows");
		return 0;
	}
	return true;

}
#=== 現在値更新 ===
sub upd_master {
	local($o_kind, $o_id, $pt) = @_;
	local($sqlx, $sthx, $rows, $eFlg);

	if ($o_kind == '1'){
		$sqlx = "update M_MELONPA "
			  . "   set PT_MELONPAN = ( select PT_MELONPAN + $pt from M_MELONPA where reader_id = $o_id) "
			  . " where reader_id = $o_id and (PT_MELONPAN + $pt) > 0";
	}elsif($o_kind == '2'){
		$sqlx = "update M_PUBLISHER "
			  . "   set PT_MELONPAN = ( select PT_MELONPAN + $pt from M_PUBLISHER where PUBLISHER_ID = $o_id) "
			  . " where PUBLISHER_ID = $o_id and (PT_MELONPAN + $pt) > 0";
	}elsif($o_kind == '3'){
		$sqlx = "update M_MELONPAI "
			  . "   set MELONPAI_POINT = ( select MELONPAI_POINT + $pt from M_MELONPAI where MELONPAI_ID = $o_id) "
			  . " where MELONPAI_ID = $o_id and (MELONPAI_POINT + $pt) > 0";
	}else{
		return 0;
	}
	$sthx = $dbh->prepare($sqlx);
	$rows = $sthx->execute;
	$eFlg = $sthx->state;
	$sthx->finish;
	if ($eFlg != 0) {
		&write_log("DB ERROR owner_kind:$o_kind owner_id:$o_id at update MASTER result=$eFlg");
		return 0;
	}
	if ($rows != 1) {
		&write_log("ERROR owner_kind:$o_kind owner_id:$o_id not found or minus point at update MASTER result=$rows");
		return 0;
	}
	return true;
}

#=== rollback ===
sub Rollback {
	$sql = "rollback";
	$sth9 = $dbh->prepare($sql);
	$sth9->execute;
	$sth9->finish;
}

#=== ログ書き出し ===
sub write_log {
	local($msg) = @_;
	local($logdata);

	$logdata = sprintf("%04d/%02d/%02d %02d:%02d ", $year, $mon, $mday, $hour, $min) . "\n";
	$logdata.= $msg;
	
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
