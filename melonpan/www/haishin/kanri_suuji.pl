#!/usr/bin/perl

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

# ���ռ���
$time = time;
$today = get_ymd($time);
$time -= 24 * 60 * 60;
$yesterday = get_ymd($time);
$time -= 24 * 60 * 60;
$yesterday2 = get_ymd($time);

#print "$yesterday\n";
#print "$yesterday2\n";

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

# �ǡ����١�����³
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

# 1 ������Ͽ���
$sql = "select count(*) from M_MAGAZINE where to_date(MAG_PUB_STATUS_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 1);

# 2 ������Ͽȯ�ԼԿ�
$sql = "select count(*) from M_PUBLISHER where to_date(REGIST_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 2);

# 3 ȯ�Ի��
$sql = "select count(*) from L_SEND where to_date(SEND_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 3);

# 4 ȯ������
$sql = "select sum(SEND_VOLUME) from L_SEND where to_date(SEND_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 4);

# 5 ��Ͽ�ɼԿ�
$lastdata = &get_yesterday_data(22);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID<>'000013' and MAG_ID<>'000049'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID<>'000013' and MAG_ID<>'000049'";
}
&write_data_from_sql($sql, 5);

# 6 ����Ѥ�쥿����Ͽ�ɼԿ�
$lastdata = &get_yesterday_data(23);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID='000049'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000049'";
}
&write_data_from_sql($sql, 6);

# 7 �Х��塼����Ͽ�ɼԿ�
$lastdata = &get_yesterday_data(24);
if ($lastdata == 0) {
	$sql = "select count(*) from T_MAILADDR where to_date(MODIFY_DT,'YYYY/MM/DD')='$yesterday' and MAG_ID='000013'";
} else {
	$sql = "select count(*)-$lastdata from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000013'";
}
&write_data_from_sql($sql, 7);

# 8 �������Ͽ�Կ�
$lastdata = &get_yesterday_data(32);
if ($lastdata == 0) {
	$sql = "select count(*) from M_MELONPA where to_date(REGIST_DT,'YYYY/MM/DD')='$yesterday'";
} else {
	$sql = "select count(*)-$lastdata from M_MELONPA where (REGIST_DT<'$today' or REGIST_DT is null) and LOG_KEY_FLG='1'";
}
&write_data_from_sql($sql, 8);

# 9 �Ѵ����
$sql = "select count(*) from T_MAG_DELREASON where to_date(MODEFY_DT,'YYYY/MM/DD')='$yesterday'";
&write_data_from_sql($sql, 9);

# 21 ��ˡ����ɼԿ�
$sql = "select count(distinct MAIL_ADDR) from T_MAILADDR where MODIFY_DT<'$today'";
$uniq_reader = &write_data_from_sql($sql, 21);

# 22 ���ɼԿ�
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID<>'000013' and MAG_ID<>'000049'";
$total_reader = &write_data_from_sql($sql, 22);

# 23 ����Ѥ�쥿���ɼԿ�
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000049'";
&write_data_from_sql($sql, 23);

# 24 �Х��塼���ɼԿ�
$sql = "select count(*) from T_MAILADDR where MODIFY_DT<'$today' and MAG_ID='000013'";
&write_data_from_sql($sql, 24);

# 25 ����Ͽ�Ԥ����
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='00'";
&write_data_from_sql($sql, 25);

# 26 ����Ͽ���
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='01'";
&write_data_from_sql($sql, 26);

# 27 ����Ͽ�Ԥ����
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='01' and MAG_VOLUME>=3";
&write_data_from_sql($sql, 27);

# 28 ����Ͽ���
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_PUB_STATUS_FLG='03'";
&write_data_from_sql($sql, 28);

# 29 ��Ͽ���
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and (MAG_PUB_STATUS_FLG='01' or MAG_PUB_STATUS_FLG='03')";
$regist_magazine = &write_data_from_sql($sql, 29);

# 30 ȯ�ԼԿ�
$sql = "select count(distinct PUBLISHER_ID) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and (MAG_PUB_STATUS_FLG='01' or MAG_PUB_STATUS_FLG='03')";
&write_data_from_sql($sql, 30);

# 31 ��ͤ�������ɻ��
&write_data(int($total_reader / $uniq_reader * 100), 31);

# 32 �������Ͽ�Կ�
$sql = "select count(*) from M_MELONPA where (REGIST_DT<'$today' or REGIST_DT is null) and LOG_KEY_FLG='1'";
&write_data_from_sql($sql, 32);

# 33 �Ѵ����
$sql = "select count(*) from T_MAG_DELREASON";
&write_data_from_sql($sql, 33);

# 34 �ٴ�����
$sql = "select count(*) from M_MAGAZINE where MAG_PUB_STATUS_DT<'$today' and MAG_STATUS_FLG='01'";
&write_data_from_sql($sql, 34);

# 35 ���濫�����ɼԿ�
&write_data(int($total_reader / $regist_magazine * 100), 35);

$dbh->disconxxxt;

#=== YYYY/MM/DD���������դ��Ѵ� ===
sub get_ymd {
	($sec, $min, $hour, $mday, $mon, $year) = localtime(@_[0]);
	return sprintf("%04d/%02d/%02d", $year + 1900, $mon + 1, $mday);
}

#=== SQL����ǡ�����������ơ��֥����¸ ===
sub write_data_from_sql {
	local($sql, $item_no) = @_;
	local($data);

	if (!($data = $dbh->selectrow_array($sql))) {
		$data = 0;
	}
	&write_data($data, $item_no);

	return $data;
}

#=== �����ǡ�����ơ��֥����¸ ===
sub write_data {
	local($data, $item_no) = @_;
	local($sql);

	$sql = "insert into T_KANRI_DATA (KD_date,KD_item_no,KD_number) values ('$yesterday',$item_no,$data)";
	$dbh->do($sql);
}

#=== �����ǡ������� ===
sub get_yesterday_data {
	local($item_no) = @_;

	$sql = "select KD_number from T_KANRI_DATA where KD_date='$yesterday2' and KD_item_no=$item_no";
	($data) = $dbh->selectrow_array($sql);

	return $data;
}
