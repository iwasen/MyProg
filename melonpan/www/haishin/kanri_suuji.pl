#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# 日付取得
$time = time;
$today = get_ymd($time);
$time -= 24 * 60 * 60;
$yesterday = get_ymd($time);
$time -= 24 * 60 * 60;
$yesterday2 = get_ymd($time);

#print "$yesterday\n";
#print "$yesterday2\n";

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

# データベース接続
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 1 新規登録誌数
$sql = "select count(*) from M_MAGAZINE where to_date(MAG_PUB_STATUS_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 1);

# 2 新規登録発行者数
$sql = "select count(*) from M_PUBLISHER where to_date(REGIST_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 2);

# 3 発行誌数
$sql = "select count(*) from L_SEND where to_date(SEND_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 3);

# 4 発行部数
$sql = "select sum(SEND_VOLUME) from L_SEND where to_date(SEND_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 4);

# 5 登録読者数
$lastdata = &get_yesterday_data(22);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID<>'000013' and MAG_ID<>'000049'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID<>'000013' and MAG_ID<>'000049'";
}
&write_data_from_sql($sql, 5);

# 6 めろんぱんレター登録読者数
$lastdata = &get_yesterday_data(23);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID='000049'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000049'";
}
&write_data_from_sql($sql, 6);

# 7 バキューン登録読者数
$lastdata = &get_yesterday_data(24);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID='000013'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000013'";
}
&write_data_from_sql($sql, 7);

# 8 メロンパ登録者数
$lastdata = &get_yesterday_data(32);
if ($lastdata == 0) {
	$sql = "select count(*) from M_MELONPA where to_date(REGIST_DT,'YYYY/MM/DD')='$yesterday'";
} else {
	$sql = "select count(*)-$lastdata from M_MELONPA where (REGIST_DT<'$today' or REGIST_DT is null) and LOG_KEY_FLG='1'";
}
&write_data_from_sql($sql, 8);

# 9 廃刊誌数
$sql = "select count(*) from T_MAG_DELREASON where to_date(MODEFY_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 9);

# 21 ユニーク読者数
$sql = "select count(distinct MAIL_ADDR) from T_MAILADDR where MODIFY_DT<'$today'";
$uniq_reader = &write_data_from_sql($sql, 21);

# 22 総読者数
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID<>'000013' and MAG_ID<>'000049'";
$total_reader = &write_data_from_sql($sql, 22);

# 23 めろんぱんレター読者数
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000049'";
&write_data_from_sql($sql, 23);

# 24 バキューン読者数
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000013'";
&write_data_from_sql($sql, 24);

# 25 仮登録待ち誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='00'";
&write_data_from_sql($sql, 25);

# 26 仮登録誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='01'";
&write_data_from_sql($sql, 26);

# 27 本登録待ち誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='01' and MAG_VOLUME>=3";
&write_data_from_sql($sql, 27);

# 28 本登録誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='03'";
&write_data_from_sql($sql, 28);

# 29 登録誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and (MAG_PUB_STATUS_FLG='01' or MAG_PUB_STATUS_FLG='03')";
$regist_magazine = &write_data_from_sql($sql, 29);

# 30 発行者数
$sql = "select count(distinct PUBLISHER_ID) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and (MAG_PUB_STATUS_FLG='01' or MAG_PUB_STATUS_FLG='03')";
&write_data_from_sql($sql, 30);

# 31 一人あたり購読誌数
&write_data(int($total_reader / $uniq_reader * 100), 31);

# 32 メロンパ登録者数
$sql = "select count(*) from M_MELONPA where (REGIST_DT<'$today' or REGIST_DT is null) and LOG_KEY_FLG='1'";
&write_data_from_sql($sql, 32);

# 33 廃刊誌数
$sql = "select count(*) from T_MAG_DELREASON";
&write_data_from_sql($sql, 33);

# 34 休刊中誌数
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_STATUS_FLG='01'";
&write_data_from_sql($sql, 34);

# 35 １誌あたり読者数
&write_data(int($total_reader / $regist_magazine * 100), 35);

$dbh->disconxxxt;

#=== YYYY/MM/DD形式に日付を変換 ===
sub get_ymd {
	($sec, $min, $hour, $mday, $mon, $year) = localtime(@_[0]);
	return sprintf("%04d/%02d/%02d", $year + 1900, $mon + 1, $mday);
}

#=== SQLからデータを取得しテーブルに保存 ===
sub write_data_from_sql {
	local($sql, $item_no) = @_;
	local($data);

	if (!($data = $dbh->selectrow_array($sql))) {
		$data = 0;
	}
	&write_data($data, $item_no);

	return $data;
}

#=== 管理データをテーブルに保存 ===
sub write_data {
	local($data, $item_no) = @_;
	local($sql);

	$sql = "insert into T_KANRI_DATA (KD_date,KD_item_no,KD_number) values ('$yesterday',$item_no,$data)";
	$dbh->do($sql);
}

#=== 前日データ取得 ===
sub get_yesterday_data {
	local($item_no) = @_;

	$sql = "select KD_number from T_KANRI_DATA where KD_date='$yesterday2' and KD_item_no=$item_no";
	($data) = $dbh->selectrow_array($sql);

	return $data;
}
