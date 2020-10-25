#!/usr/bin/perl

require 'jcode.pl';
require 'mimew.pl';

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

$logs_dir = './';
$logfile = './memo_card.log';
$sendmail = '/usr/sbin/sendmail';
$err_mail_adr = 'sys@melonpan.net';

# 日時取得
$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year) = localtime(time);
$mon++;
$year += 1900;
$today = sprintf("%04d/%02d/%02d",$year,$mon,$mday);

# sendmail接続確認
open(MAIL, "| $sendmail -i -t > /dev/null") or die "接続失敗(smtp): $!";
close MAIL;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000; 		#LOB型対応
$dbh->{LongTruncOk} = 1;				#LOB型対応

# 読者誕生日
$sql = "select trim(MAIL_ADDR), int2(to_char(date('now'), 'YYYY')) - int2(to_char(READER_BIRTH, 'YYYY'))"
	 . "  from M_MELONPA where to_char(READER_BIRTH, 'MM/DD') = to_char(CURRENT_TIMESTAMP, 'MM/DD')";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($to_addr, $years));

$cnt = 0;
#送信ループ開始
while ($sth->fetch) {

	($t_subject, $t_from, $t_cc, $t_bcc, $t_body) = &get_mail_template("mc_rder");
	if ($t_body eq "") {
		last;
	}

	$t_body =~ s/\@reader_nm\@/$to_addr/g;
	$t_body =~ s/\@years\@/$years/g;

	$header = "To: "		.mimeencode($to_addr)."\n"
			. "From: "		.mimeencode($t_from)."\n"
			. "Subject: "	.mimeencode($t_subject)."\n"
			. "Error-To: "	.mimeencode($err_mail_adr)."\n";
	if ($t_cc ne "") {
		$header .= "Cc: ".mimeencode($t_cc)."\n";
	}
	if ($t_bcc ne "") {
		$header .= "Bcc: ".mimeencode($t_bcc)."\n";
	}

	$body = jcode::jis($t_body);
	# メール送信
	&send_mail($header, $body);

	$cnt++;
}
$sth->finish;
$logmsg = "読者誕生日 処理件数 = $cnt 件\n";
&write_log($logmsg);
#print "読者誕生日=$cnt\n";

# メルマガ誕生日
$sql = "select P.MAIL_ADD_MAIN, int2(to_char(CURRENT_TIMESTAMP,'YYYY')) - int2(to_char(M.MAG_AGR_DT,'YYYY')),"
	 . "       M.MAG_NM, P.OWNER_NM_KANJ"
	 . "  from M_MAGAZINE M, M_PUBLISHER P "
	 . " where P.PUBLISHER_ID = M.PUBLISHER_ID"
	 . "   and to_char(M.MAG_AGR_DT, 'MM/DD') = to_char(CURRENT_TIMESTAMP, 'MM/DD')"
	 . "   and to_char(M.MAG_AGR_DT, 'YYYY') <> to_char(CURRENT_TIMESTAMP, 'YYYY')"
	 . "   and M.mag_pub_status_flg = '03' and M.mag_pub_stop_flg <> '02' and M.publisher_id <> 0";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($to_addr, $years, $mag_nm, $owner_nm));

$cnt = 0;
#送信ループ開始
while ($sth->fetch) {

	($t_subject, $t_from, $t_cc, $t_bcc, $t_body) = &get_mail_template("mc_mag");
	if ($t_body eq "") {
		last;
	}

	$mag_nm =~ s/^\s+//;
	$mag_nm =~ s/\s+$//;
	$t_subject =~ s/\@years\@/$years/g;
	$t_body =~ s/\@owner_nm\@/$owner_nm/g;
	$t_body =~ s/\@mag_nm\@/$mag_nm/g;
	$t_body =~ s/\@years\@/$years/g;

	$header = "To: "		.mimeencode($to_addr)."\n"
			. "From: "		.mimeencode($t_from)."\n"
			. "Subject: "	.mimeencode($t_subject)."\n"
			. "Error-To: "	.mimeencode($err_mail_adr)."\n";
	if ($t_cc ne "") {
		$header .= "Cc: ".mimeencode($t_cc)."\n";
	}
	if ($t_bcc ne "") {
		$header .= "Bcc: ".mimeencode($t_bcc)."\n";
	}

	$body = jcode::jis($t_body);
	# メール送信
	&send_mail($header, $body);

	$cnt++;
}
$sth->finish;
$logmsg = "メルマガ誕生日 処理件数 = $cnt 件\n";
&write_log($logmsg);
#print "メルマガ誕生日=$cnt\n";

# データベース切断
$dbh->disconxxxt;


#=== メールテンプレート取得 ===
sub get_mail_template($template_id) {
	local($template_id) = @_;
	local($sql, $sth, $a, $b, $c, $d, $t_body);

	$t_body = "";
	$sql = "select MT_SUBJECT, MT_FROM, MT_CC, MT_BCC, MT_BODY from M_MAIL_TEMPLATE where MT_ID='$template_id'";
	$sth = $dbh->prepare($sql);
	$sth->execute;

	($a, $b, $c, $d, $t_body)=$sth->fetchrow;
	$t_body =~ s/\r\n/\n/g;
	return ($a, $b, $c, $d, $t_body);
}

#=== メール送信 ===
sub send_mail {
	local($ml_head, $ml_body) = @_;

	open(MAIL, "| $sendmail -i -t > /dev/null") or die "接続失敗(smtp): $!";
	$ml_head .= "Content-Type: text/plain; charset=ISO-2022-JP\n"
			 .  "Content-Transfer-Encoding: 7bit\n";
#	print MAIL $ml_head, $ml_body;
	print MAIL $ml_head;
	print MAIL "\n";
	print MAIL $ml_body;

	close MAIL;
}

#=== ログ書き出し ===
sub write_log {
	local($f_text) = @_;
	local($logdata);

	($sec, $min, $hour, $mday, $mon, $year) = localtime(time);
	$mon++;
	$year += 1900;
	$logdata = sprintf("%04d/%02d/%02d %02d:%02d:%02d %s %s",
			$year, $mon, $mday, $hour, $min, $sec, $p_mag_id, $p_mail_addr);

	$logdata = "TIME=".$logdata."\n".$f_text;
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

