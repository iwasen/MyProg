#!/usr/bin/perl

# �ۿ�������� => ȯ�Լ�

use Getopt::Std;                     # ���ץ���������
use DBI;
use DBD::Pg;

require 'jcode.pl';
require 'mimew.pl';

my($sendmail, $err_mail_adr, $logfile);
my($today, $mag_id, $mag_nm, $mail_header, $start_date, $end_date, $send_volume, $to_addr, $msg);

$logs_dir = './';
$logfile = './issue_report.log';
$sendmail = '/usr/sbin/sendmail';
$err_mail_adr = 'sys@melonpan.net';

## ��������
$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year) = localtime(time - (0*24*60*60));
$mon++;
$year += 1900;
$today = sprintf("%04d/%02d/%02d %02d:%02D",$year,$mon,$mday,$hour,$min);

# db������̾
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;


$dbh->{LongReadLen} = 10000000; 		#CLOB���б�
$dbh->{LongTruncOk} = 1;				#CLOB���б�
$dbh->{RaiseError} = 1;

# sendmail��³��ǧ
open(MAIL, "| $sendmail -i -t > /dev/null") or die "��³����(smtp): $!";
close MAIL;

$sql = "select SD_SEND_MAIL_ID, SD_MAIL_HEADER, SD_STATUS, to_char(SD_SEND_DATE, 'YYYY/MM/DD HH24:MI'),"
	 . "       to_char(SD_START_DATE, 'YYYY/MM/DD HH24:MI:SS'), to_char(SD_END_DATE, 'YYYY/MM/DD HH24:MI:SS'),"
	 . "       SD_prog_data "
	 . "  from T_SEND_MAIL_DATA "
	 . " where (SD_status='3' OR SD_status='4') AND SD_prog_id='MSEND_DB' AND SD_prog_data IS NOT NULL "
	 . "  order by SD_SEND_MAIL_ID";
$sth = $dbh->prepare($sql);
$sth->execute;
if ( $sth->state != 0 ){
	die "SQL ERROR: $!";
}
$sth->bind_columns(undef, \($send_mail_id, $mail_header, $status, $reserve_date, $start_date, $end_date, $mag_id));
$scnt = 0;
#�����롼�׳���
for ( $acnt=0 ; $sth->fetch ; $acnt++ ) {

	$mag_id =~ s/ //g;

	# �ۿ���̼���
 	$sql2= "select P.MAIL_ADD_MAIN, M.MAG_NM, S.SEND_NUM, S.SEND_VOLUME "
		 . "  from (select MAG_ID, SEND_NUM, SEND_VOLUME from L_SEND "
         . "         where MAG_ID = '$mag_id' and to_char(SEND_DT, 'YYYY/MM/DD HH24:MI') = '$reserve_date') S,"
         . "       (select MAG_ID, MAG_NM, PUBLISHER_ID from M_MAGAZINE "
         . "         where MAG_ID = '$mag_id' and SEND_NOTICE_FLG = '1') M,"
       	 . "       M_PUBLISHER P "
		 . " where M.MAG_ID = S.MAG_ID "
		 . "   and M.PUBLISHER_ID = P.PUBLISHER_ID";

	$sth2 = $dbh->prepare($sql2);
	$sth2->execute;
	$sth2->bind_columns(undef, \($to_addr, $mag_nm, $vol_num, $send_volume));
	if ($sth2->fetch) {
		# ��ݡ�������
		$msg = trans_status($status);
		&send_report($to_addr, $mag_nm, $vol_num, $reserve_date, $start_date, $end_date, $send_volume, $msg);
		$scnt++;
	}else{
		&write_log("  ̤���� MAGID=$mag_id");
	}
	$sth2->finish;

	# T_SEND_MAIL_DATA��PROG_DATA�򥯥ꥢ
	$sql5 = "update T_SEND_MAIL_DATA set SD_PROG_DATA = NULL where SD_SEND_MAIL_ID = $send_mail_id";
	$sth5 = $dbh->prepare($sql5);
	$sth5->execute;
	$sth5->finish;

}

if ($acnt != 0){
	$logmsg = "������ = $today, �оݷ�� = $acnt ��, ������� = $scnt ��\n";
	&write_log($logmsg);
}

$dbh->disconxxxt;

#=== �ۿ���ݡ������� ===
sub send_report {
	local($to_pub, $r_mag_nm, $vol_no, $reserve, $start, $end, $volume, $warning) = @_;
	local($t_subject, $t_from, $t_cc, $t_bcc, $t_body);

	($t_subject, $t_from, $t_cc, $t_bcc, $t_body) = &get_mail_template("pub_rpt");

	$header = "To: "		.mimeencode($to_pub)."\n"
			. "From: "		.mimeencode($t_from)."\n"
			. "Subject: "	.mimeencode($t_subject)."\n"
			. "Error-To: "	.mimeencode($err_mail_adr)."\n";

	$r_mag_nm =~ s/^\s+//;
	$r_mag_nm =~ s/\s+$//;
	$t_body =~ s/\@mag_nm\@/$r_mag_nm/g;
	$t_body =~ s/\@vol_no\@/$vol_no/g;
	$t_body =~ s/\@reserve\@/$reserve/g;
	$t_body =~ s/\@start\@/$start/g;
	$t_body =~ s/\@end\@/$end/g;
	$t_body =~ s/\@volume\@/$volume/g;
	$t_body =~ s/\@warning\@/$warning/g;

	$t_body = jcode::jis($t_body);
	# �᡼������
	&send_mail($header, $t_body);

	return;
}

#=== �ۿ����ơ����� ===
sub trans_status {
	local($s_status) = @_;

	if ($s_status == 3){		# �����ۿ�
		return "������ۿ�����ޤ�����";
	}elsif ($s_status == 4) {	# �ۿ��襢�ɥ쥹���������ɥ쥹�����ä����������Ǥʤ���Τ�������ۿ�
		return "������ۿ�����ޤ�����";
	}
	return "������ۿ����줿�Ȼפ��ޤ���";
}
#=== �᡼��ƥ�ץ졼�ȼ��� ===
sub get_mail_template($template_id) {
	local($template_id) = @_;
	local($sql, $sth, $a, $b, $c, $d, $t_body);

	$sql = "select MT_SUBJECT, MT_FROM, MT_CC, MT_BCC, MT_BODY from M_MAIL_TEMPLATE where MT_ID='$template_id'";
	$sth = $dbh->prepare($sql);
	$sth->execute;

	($a, $b, $c, $d, $t_body)=$sth->fetchrow;
	$t_body =~ s/\r\n/\n/g;
	return ($a, $b, $c, $d, $t_body);
}

#=== �᡼������ ===
sub send_mail {
	local($ml_head, $ml_body) = @_;

	open(MAIL, "| $sendmail -i -t > /dev/null") or die "��³����(smtp): $!";
	$ml_head .= "Content-Type: text/plain; charset=ISO-2022-JP\n"
			 .  "Content-Transfer-Encoding: 7bit\n";
	print MAIL $ml_head, $ml_body;

	close MAIL;
}

#=== ���񤭽Ф� ===
sub write_log {
	local($f_text) = @_;
	local($logdata);

	($sec, $min, $hour, $mday, $mon, $year) = localtime(time);
	$mon++;
	$year += 1900;
	$logdata = sprintf("%04d/%02d/%02d %02d:%02d:%02d %s %s",
			$year, $mon, $mday, $hour, $min, $sec, $p_mag_id, $p_mail_addr);

	$logdata = "TIME=".$logdata."\n".$f_text;
	&append_log($logs_dir.$logfile, $logdata);
}

#=== ���ե�������ɲ� ===
sub append_log {
	local($filename, $logdata) = @_;

	if (open(LOG, ">>$filename")) {
		flock(LOG, 2);
		print LOG $logdata . "\n";
		flock(LOG, 8);
		close(LOG);
	}
}

