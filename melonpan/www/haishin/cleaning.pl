#!/usr/bin/perl

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

$ENV{NLS_DATE_FORMAT} = 'YYYY/MM/DD HH24:MI:SS';

# ���ե�����ģ��
$logs_dir = './';

# ��å��ե�����̾
$lockfname = 'lock_cleaning';

######### ��å��ե������ǧ #######
&lockfile;
if ($okrun != 0) {
	exit 0;
}

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

# �ǡ����١�����³
#$dbh = DBI->conxxxt('dbi:Oracle:', $oracle_db, '', 'Oracle');
#die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{LongTruncOk} = 1;
#$dbh->{RaiseError} = 1;

# �᡼�륯�꡼�˥󥰥ǡ�������
$sql = "select CD_seq_no,CD_subject,CD_from,CD_reply_to,CD_body,CD_start_date" .
		" from M_CLEANING_DATA" .
		" where CD_status='0' and CD_start_date<=CURRENT_TIMESTAMP";
$sth_cd = $dbh->prepare($sql);
$sth_cd->execute;
$sth_cd->bind_columns(undef, \($seq_no, $subject, $from, $reply_to, $body, $start_date));

while ($sth_cd->fetch) {
	#--mod for postgres ------------------------------------------------------------
	# send_mail_id����
#	$sql = "select SL_SEND_MAIL_ID_SEQ.NEXTVAL from dual";
#	$send_mail_id = $dbh->selectrow_array($sql);

	# T_SEND_MAIL_DATA�˥��å�
#	$sql = "insert into T_SEND_MAIL_DATA" .
#		" (SD_SEND_MAIL_ID,SD_SUBJECT,SD_FROM,SD_REPLY_TO,SD_BODY,SD_STATUS,SD_SEND_DATE)" .
#		" values(?,?,?,?,?,'0',?)";
#	$sth_sd = $dbh->prepare($sql);
#	$sth_sd->execute($send_mail_id, $subject, $from, $reply_to, $body, $start_date);
	$sql = "insert into T_SEND_MAIL_DATA" .
		" (SD_SUBJECT,SD_FROM,SD_REPLY_TO,SD_BODY,SD_STATUS,SD_SEND_DATE)" .
		" values(?,?,?,?,'0',?)";
	$sth_sd = $dbh->prepare($sql);
	$sth_sd->execute($subject, $from, $reply_to, $body, $start_date);

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

	# T_SEND_MAIL_LIST�������ν���
	$sql = "insert into T_SEND_MAIL_LIST" .
		" (SL_send_mail_id,SL_to,SL_sub_seq,SL_mail_header)" .
		" values($send_mail_id,?,?,?)";
	$sth_sl = $dbh->prepare($sql);

	# T_SEND_MAIL_EMBED�������ν���
	$sql = "insert into T_SEND_MAIL_EMBED" .
		" (SE_send_mail_id,SE_sub_seq,SE_variable_name,SE_embed_text)" .
		" values($send_mail_id,?,'%PARAMETER%',?)";
	$sth_se = $dbh->prepare($sql);

	# �᡼�륯�꡼�˥󥰥ꥹ�ȼ���
	$sql = "select CL_sub_seq,CL_mail_adr from T_CLEANING_LIST where CL_seq_no=$seq_no and CL_status='0'";
	$sth_cl = $dbh->prepare($sql);
	$sth_cl->execute;
	$sth_cl->bind_columns(undef, \($sub_seq, $mail_adr));
	while ($sth_cl->fetch) {
		# �᡼��إå��Խ�
		$mail_header = "X-Melonpan-Mc: $seq_no\n" . "X-Melonpan-Ms: $sub_seq";

		# T_SEND_MAIL_LIST������
	  	$sth_sl->execute($mail_adr, $sub_seq, $mail_header);

		# T_SEND_MAIL_EMBED������
		$key = ($seq_no + $sub_seq * 3 + 4240) % 10000;
	  	$sth_se->execute($sub_seq, "c=$seq_no&s=$sub_seq&k=$key");

		# ���ơ���������
		$sql = "update T_CLEANING_LIST set CL_status='2' where CL_seq_no=$seq_no and CL_sub_seq=$sub_seq";
		$dbh->do($sql);
	}

	# T_SEND_MAIL_DATA�Υ��ơ������򥻥å�
	$sql = "update T_SEND_MAIL_DATA set SD_status='1' where SD_send_mail_id=$send_mail_id";
	$dbh->do($sql);

	# M_CLEANING_DATA�Υ��ơ������򹹿�
	$sql = "update M_CLEANING_DATA set CD_status='1' where CD_seq_no=$seq_no";
	$dbh->do($sql);

#-- add for postgres
	$sth_cl->finish;
	$sth_se->finish;
	$sth_sl->finish;
	$sth_sq->finish;
	$sth_sd->finish;
#-------------------
	$dbh->commit;
}

#-- add for postgres
	$sth_cd->finish;
#-------------------
$dbh->disconxxxt;

&unlockfile;
exit 0;

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
