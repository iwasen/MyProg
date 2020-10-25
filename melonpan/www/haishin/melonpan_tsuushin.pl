#!/usr/bin/perl

# �ǥե����from���ɥ쥹
$default_from_addr = 'mpan-news@melonpan.net';
$default_from_name = '����Ѥ��̳��';

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

# ���ե�����ģ��
$logs_dir = './';

# ��å��ե�����̾
$lockfname = 'lock_tsuushin';

######### ��å��ե������ǧ #######
&lockfile;
if ($okrun != 0) {
	exit 0;
}

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

# �ǡ����١�����³
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{RaiseError} = 1;

$sql = "select TS_id,TS_subject,TS_from,TS_body "
	 . "  from T_TSUUSHIN where TS_status='0' and TS_send_date <= current_timestamp";
$sth_ts = $dbh->prepare($sql);
$sth_ts->execute;
$sth_ts->bind_columns(undef, \($id, $subject, $from, $body));
while ($sth_ts->fetch) {

	# send_mail_id����
#	$sql = "select SL_SEND_MAIL_ID_SEQ.NEXTVAL from dual";
#	$send_mail_id = $dbh->selectrow_array($sql);

	# from���ɥ쥹ʬ��
	($from_addr, $personal_from_addr) = get_from_addr($from);
	
	# T_SEND_MAIL_DATA�˥��å�
#	$sql = "insert into T_SEND_MAIL_DATA" .
#		" (SD_SEND_MAIL_ID,SD_SUBJECT,SD_FROM,SD_BODY,SD_STATUS,SD_PERSONAL)" .
#		" values(?,?,?,?,'0',?)";
#	$sth_sd = $dbh->prepare($sql);
#	$sth_sd->execute($send_mail_id, $subject, $from_addr, $body, $personal_from_addr);
	#--mod for postgres ------------------------------------------------------------
	$sql = "insert into T_SEND_MAIL_DATA" .
		" (SD_SUBJECT,SD_FROM,SD_BODY,SD_STATUS,SD_PERSONAL)" .
		" values(?,?,?,'0',?)";
	$sth_sd = $dbh->prepare($sql);
	$sth_sd->execute($subject, $from_addr, $body, $personal_from_addr);

	# send_mail_id����
	$sql = "select currval('t_send_mail_d_sd_send_mail__seq')";
	$sth_sq = $dbh->prepare($sql);
	$sth_sq->execute;
	if ( $sth_sq->state != 0 ){
		die "SQL ERROR: $!";
	}
	$sth_sq->bind_columns(undef, \($send_mail_id));
	$sth_sq->fetch;
	# -------------------------------------------------------------------------------

	# T_SEND_MAIL_LIST������
	$sql = "select distinct $send_mail_id,mail_add_main" .
		" from M_PUBLISHER MP, M_MAGAZINE MM" .
		" where MM.mag_status_flg in ('00','01') and MM.mag_pub_status_flg in ('00','01','03') ".
		" and MM.mag_pub_stop_flg in ('00','01') and MP.publisher_id=MM.publisher_id";
	$sql = "insert into T_SEND_MAIL_LIST(SL_SEND_MAIL_ID,SL_TO) ($sql)";
	$send_volume = $dbh->do($sql);

	# T_SEND_MAIL_DATA�Υ��ơ������򥻥å�
	$sql = "update T_SEND_MAIL_DATA set SD_status='1' where SD_send_mail_id=$send_mail_id";
	$dbh->do($sql);

	# T_TSUUSHIN�������Ѥߤ�����
	$sql = "update T_TSUUSHIN "
		 . "   set TS_status='1',TS_send_start=current_timestamp,TS_send_volume=$send_volume where TS_id=$id";
	$dbh->do($sql);

	#--add for postgres
	$sth_sq->finish;
	$sth_sd->finish;
	#------------------

	$dbh->commit;
}

#--add for postgres
$sth_ts->finish;
$dbh->disconxxxt;
#------------------
&unlockfile;
exit 0;

#=== from���ɥ쥹ʬ�� ===
sub get_from_addr {
	local($from_addr_mixed) = @_;
	local($from_addr, $from_name, $tmp);

	if ($from_addr_mixed =~ /</ && $from_addr_mixed =~ />/) {
		($from_name, $tmp)=split(/</, $from_addr_mixed);
		($from_addr, $tmp)=split(/>/, $tmp);
	}else{
		$from_name = '';
		$from_addr = $from_addr_mixed;
	}

	if ($from_addr =~ /</ || $from_addr =~ />/) {
		return ($default_from_addr, $default_from_name);
	}

	if ($from_addr !~ /@/) {
		return ($default_from_addr, $default_from_name);
	}
	
	return ($from_addr, $from_name);
}

sub lockfile {
	$okrun = 0;
	# ��å��ե�����̾
	$lokfile = $logs_dir . $lockfname;

	$locktime = time;
	$lock_time = sprintf("%d",$locktime);

	# ��å��ե������¸�ߤ�����å�
	unless(-e $lokfile) {
		$okrun = 0;
		if (open(LOK,">$lokfile")) {
			flock(LOK,2);
			print LOK $lock_time;
			flock(LOK,8);
			close(LOK);

			# �ѡ��ߥå����� 666 ��
			chmod (0666,"$lokfile");
		}
	}else{
		$okrun = 1;
		if (open(LOK,"<$lokfile")) {
			flock(LOK,2);
			read LOK, $make_time, 256;
			flock(LOK,8);
			close(LOK);
			if ($locktime - 1800 > $make_time) {
				$okrun = 0;
			}
		}
	}
}
sub unlockfile {
	# ��å��ե�����̾
	$lokfile = $logs_dir . $lockfname;
	unlink "$lokfile";
}
