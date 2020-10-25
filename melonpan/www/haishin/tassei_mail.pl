#!/usr/bin/perl

require 'jcode.pl';
require 'mimew.pl';

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

$logs_dir = './';
$logfile = './tassei_mail.log';
$sendmail = '/usr/sbin/sendmail';
$err_mail_adr = 'sys@melonpan.net';

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

# 読者達成メール設定
$sql = "select tm_seq_no,tm_subject,tm_from,tm_header,tm_body,tm_footer,tm_num,tm_first_flag from m_tassei_mail where tm_status='1' order by tm_num desc";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($tm_seq_no,$tm_subject,$tm_from,$tm_header,$tm_body,$tm_footer,$tm_num,$tm_first_flag));


$i = 0;
while ($sth->fetch) {
	$tm_seq_no[$i] = $tm_seq_no;
	$tm_subject[$i] = $tm_subject;
	$tm_from[$i] = $tm_from;
	$tm_header[$i] = $tm_header;
	$tm_body[$i] = $tm_body;
	$tm_footer[$i] = $tm_footer;
	$tm_num[$i] = $tm_num;
	$tm_first_flag[$i] = $tm_first_flag;

	# m_tassei_mailに初回フラグ書き出し
	if ($tm_first_flag[$i] eq '0') {
		$sql = "update m_tassei_mail set tm_first_flag='1' where tm_seq_no=$tm_seq_no[$i]";
		$sth2 = $dbh->prepare($sql);
		$sth2->execute;
		$sth2->finish;
	}
	$i++;
}
$sth->finish;

# m_tassei_mailに有効データがなければ終了
if ($i == 0) {
	exit;
}

# 該当メルマガ抽出
$sub = "select count(mag_id) as mag_count,mag_id from t_mailaddr group by mag_id";
$sql = "select C.mag_count,P.publisher_id,P.owner_nm_kanj,P.mail_add_main,M.mag_nm,M.tassei_mail_flg,M.mag_id "
	."from ($sub) C,m_magazine M,m_publisher P "
	."where C.mag_id=M.mag_id and M.publisher_id=P.publisher_id and mag_count >= $tm_num[-1] "
	."and M.publisher_id != 0 and M.mag_status_flg != '02' and M.mag_status_flg != '99' and M.mag_pub_status_flg != '99' and M.mag_pub_status_flg != '02' "
	."and M.mag_pub_stop_flg != '02' order by mag_count desc";
$sth = $dbh->prepare($sql);
$sth->execute;
$sth->bind_columns(undef, \($mag_count,$publisher_id,$owner_nm_kanj,$mail_add_main,$mag_nm,$tassei_mail_flg,$mag_id));

$i = 0;
while ($sth->fetch) {
	if ($mag_count < $tm_num[$i]) { $i++; }

	if ($tassei_mail_flg < $tm_num[$i]) {
		$tm_body2 = $tm_body[$i];
		$mag_nm =~ s/\s+$//;
		$tm_body2 =~ s/\@pub_name\@/$owner_nm_kanj/g;
		$tm_body2 =~ s/\@pub_mail\@/$mail_add_main/g;
		$tm_body2 =~ s/\@mag_name\@/$mag_nm/g;

		$header = "To: "		.mimeencode($mail_add_main)."\n"
				. "From: "		.mimeencode($tm_from[$i])."\n"
				. "Subject: "	.mimeencode($tm_subject[$i])."\n"
				. "Error-To: "	.mimeencode($err_mail_adr)."\n";

		$body = "$tm_header[$i]\n$tm_body2\n$tm_footer[$i]\n";
		$body = jcode::jis($body);

		# 達成メール送信
		if($tm_first_flag[$i] ne '0') {
			&send_mail($header, $body);
		}
		
		# m_magazineに達成フラグ書き出し
		$sql = "update m_magazine set tassei_mail_flg=$tm_num[$i] where mag_id='$mag_id'";
		$sth2 = $dbh->prepare($sql);
		$sth2->execute;
		$sth2->finish;
	}
}
$sth->finish;

#=== メール送信 ===
sub send_mail {
	local($ml_head, $ml_body) = @_;

	open(MAIL, "| $sendmail -i -t > /dev/null") or die "接続失敗(smtp): $!";
	$ml_head .= "Content-Type: text/plain; charset=ISO-2022-JP\n"
			 .  "Content-Transfer-Encoding: 7bit\n";
	print MAIL $ml_head;
	print MAIL "\n";
	print MAIL $ml_body;

	close MAIL;
}
