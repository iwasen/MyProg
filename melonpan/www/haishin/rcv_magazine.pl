#!/usr/bin/perl

use Net::POP3;                       # Net::POP3 モジュールを使う
use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

my($err_mail_adr, $logfile, $hostname, $username, $password, $use_apop);
my($mag_passwd, $mag_id, $mag_nm, $mag_type, $rsv_hour, $subject, $body, $from_addr, $reply_to);
my($reply_addr, $err_msg);
my($rsv_year, $rsv_month,$rsv_day, $rsv_hh);

require 'get_seq.pl';
require 'jcode.pl';
require 'mimew.pl';

$logfile = './receive_mag_mail.log';
$sendmail = '/usr/sbin/sendmail';
$err_mail_adr = 'sys@melonpan.net';

getopts('au:h:p:d:',\%opts);

$hostname = $opts{'h'} || 'localhost';
#$hostname = $opts{'h'} || 'oishii.melonpan.net';
$username = $opts{'u'} || 'send';
$password = $opts{'p'} || 'Ve1Jmf14';
$use_apop = $opts{'a'} || 0;
# dbサーバ名
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000; 	#CLOB型対応
$dbh->{LongTruncOk} = 1;			#CLOB型対応

$dbh->{RaiseError} = 0;

# sendmail接続確認
open(MAIL, "| $sendmail -i -t > /dev/null") or die "接続失敗(smtp): $!";
close MAIL;

$pop = Net::POP3->new($hostname);

if (!$pop) {
	die "接続失敗: $!";
}

if ($use_apop) {
	$auth_check = $pop->apop($username,$password);
} else {
	$pop->user($username);
	$auth_check = $pop->pass($password);
}

if ($auth_check == undef) {
	exit;
#	die "認証失敗: ";
}

$ref_mailsize = $pop->list();

*mailindex = $ref_mailsize;			# $ref_mailsize の 別名を作る

foreach (sort {$a<=>$b } keys %mailindex) {
	$ref_lines = $pop->get($_);		# メール本文を取得
	$pop->delete($_);

	$full_text = '';
	$mag_id = '';
	$mag_nm = '';
	$subject = '';
	$rsv_hour = '';
	$reply_to = '';
	$from_addr = '';
	$body='';
	$body_flg='off';
	$body_end='no';

	$header_flg='on';

	foreach (@$ref_lines) {         # 1行ずつ表示

		$_ = jcode::euc($_);

		if ($_ =~ /@投稿パスワード@：(.*)/) {
			$mag_passwd = $1;
			$mag_passwd =~ s/ //g;
		} elsif ($_ =~ /@メルマガＩＤ@：(.*)/) {
			$mag_id = $1;
			$mag_id =~ s/ //g;
		} elsif ($_ =~ /@メルマガ名称@：(.*)/) {
			$mag_nm = $1;
			$subject =~ s/^\s+//;
			$subject =~ s/\s+$//;
		} elsif ($_ =~ /@配信予約日時@：(.*)/) {
			$rsv_hour = $1;
			$rsv_hour =~ s/ //g;
		} elsif ($_ =~ /@サブジェクト@：(.*)/) {
			$subject = $1;
			$subject =~ s/^\s+//;
			$subject =~ s/\s+$//;
		} elsif ($_ =~ /@本文開始\(次の行から\)@：/) {
			$body_flg='on';
		} elsif ($_ =~ /：@本文終了\(前の行まで\)@/) {
			$body_flg='off';
			$body_end='yes';
		} elsif ($header_flg eq 'on' && $_ =~ /^Reply-To:(.*)/i) {
			$reply_to = $1;
			$reply_to =~ s/ //g;
		} elsif ($header_flg eq 'on' && $_ =~ /^From:(.*)/i) {
			$from_addr = $1;
			$from_addr =~ s/ //g;
		}

		# ヘッダ終了確認
		$wk = $_;
		$wk =~ s/\n//g;
		$wk =~ s/\r//g;
		$wk =~ s/ //g;
		if ($wk eq '') {
			$header_flg = 'off';
		}

		# 本文取得
		if ( $body_flg eq 'on' &&  $_ !~ /@本文開始\(次の行から\)/ ) {
			$body .= $_;
		}
		#
		$full_text .= $_;			# for log

	}

#	print "passwd = $mag_passwd\n"."mag_id = $mag_id\n"."mag_nm = $mag_nm\n"."rsv_hour = $rsv_hour\n";
#	print "reply_to = $reply_to\n"."from_addr = $from_addr\n"."subject = $subject\n";
#	print "body = \n$body\n"."full_text = $full_text\n";
#	last;

	# 返信先アドレス確認
	$reply_addr = &IsValid_from_addr($from_addr, $reply_to);
#-----------------------
#	print "reply_addr = $reply_addr\n";
#-----------------------
	if ($reply_addr eq ''){
		&send_suspend_mail("返信アドレス取得不能", $full_text);
		next;
	}
	# マガジンＩＤ確認
	($mag_type, $err_msg) = &IsValid_rcv_mag_id($mag_id, $mag_passwd);
	if ($err_msg ne ''){
		&send_err_mail($reply_addr, $err_msg, $full_text);
		next;
	}
	# 投稿内容確認
	$err_msg = &IsValid_rcv_magazine($mag_type, $subject, $body, $body_end, $rsv_hour);
	if ($err_msg ne ''){
		&send_err_mail($reply_addr, $err_msg, $full_text);
		next;
	}
	# 配信予約時間確認
	($rsv_year, $rsv_month, $rsv_day, $rsv_hh, $err_msg) = &decode_hour($mag_type, $rsv_hour);
	if ($err_msg ne ''){
		&send_err_mail($reply_addr, $err_msg, $full_text);
		next;
	}else{
		$rsv_date_and_time = sprintf("%04d-%02d-%02d %02d:%02d", $rsv_year, $rsv_month,$rsv_day, $rsv_hh, 0);
		$rsv_date_and_time_msg = $rsv_year.'年'.$rsv_month.'月'.$rsv_day.'日 '.$rsv_hh.'時';
	}
	# 配信予約
	if (reserve_magazine($mag_id, $rsv_date_and_time, $rsv_month, $rsv_day, $subject, $body)){
		&send_anser_letter($reply_addr, $mag_nm, $rsv_date_and_time_msg, $subject, $body);
	}else{
		$err_msg = "予期せぬエラーが発生したため配信予約されませんでした。";
		&send_err_mail($reply_addr, $err_msg, $full_text);
		next;
	}
}

$pop->quit();                        # ログアウト

$dbh->disconxxxt;

#=== 返信アドレス取得 ===
sub IsValid_from_addr {
	local($r_from_addr, $r_reply_to) = @_;
	local($tmp, $adr);

	if ($r_reply_to =~ /@/) {
		return $r_reply_to;
	}

	if ($r_from_addr =~ /</) {
		($tmp, $adr)=split(/</, $r_from_addr);
		($r_from_addr, $tmp)=split(/>/, $adr);
	}

	if ($r_from_addr !~ /@/) {
		return '';
	}

	return $r_from_addr;
}

#=== メルマガID取得 ===
sub IsValid_rcv_mag_id {
	local($r_mag_id, $r_mag_passwd) = @_;
	local($type, $passwd, $erm);

	$erm = '';
	if ( $r_mag_id eq '' || length($r_mag_id) != 6 ){
		$erm = "マガジンＩＤを取得できませんでした。";
	}else{
		$sql = "select MAG_TYPE, MAIL_ISSUE_PSWD from M_MAGAZINE where MAG_ID='$r_mag_id'";
		if ( !(($type, $passwd) = $dbh->selectrow_array($sql)) ) {
			$erm = "システムエラーにより配信予約されませんでした。";
		}else{
			$passwd =~ s/ //g;
#			print "mail pass=$r_mag_passwd, db_pass=$passwd,\n";	#debug
			if ($r_mag_passwd ne $passwd || $passwd eq ""){
				$erm = "投稿パスワード不正により配信予約されませんでした。";
			}
		}
	}
	return ($type, $erm);

}
#=== 投稿内容確認 ===
sub IsValid_rcv_magazine {
	local($ml_type, $r_subject, $r_body, $r_body_end, $r_rsv_hour) = @_;

	if ($r_rsv_hour !~ /年/ || $r_rsv_hour !~ /月/ || $r_rsv_hour !~ /日/ || $r_rsv_hour !~ /時/) {
		return "配信予約時間を取得できなかったため配信予約されませんでした。";
	}

	if ($r_subject eq '')		{ return "サブジェクトを取得できたかったため配信予約されませんでした。";}
	if (length($r_subject) > 80){ return "サブジェクトが長すぎるため配信予約されませんでした。";}

	if ($r_body eq '')			{ return "本文を取得できたかったため配信予約されませんでした。";}
	if ($r_body_end ne 'yes')	{ return "本文の終端を検出できなかったため配信予約されませんでした。";}
	if (length($r_body) > 50000){ return "本文が長すぎるため配信予約されませんでした。";}
	if ($ml_type == '02' && length($r_body) > 400){ return "本文が長すぎるため配信予約されませんでした。";}

	return "";
}

#=== 予約日時翻訳確認 ===
sub decode_hour {
	local($ml_type, $r_rsv_hour) = @_;
	local($sec, $min, $hour, $mday, $mon, $year, $wday);
	local($yy, $mm, $dd, $hh, $erm);

#	$r_rsv_hour = "2001年8月18日23時";		# for debug
	$erm = '';

	# 現在時
	($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time);
	$mon++;
	$year += 1900;

	# 日時分解
	($yy, $tmp)=split(/年/, $r_rsv_hour);
	($mm, $tmp)=split(/月/, $tmp);
	($dd, $tmp)=split(/日/, $tmp);
	($hh, $tmp)=split(/時/, $tmp);

	# 日付書式確認
	if (!isValidDate($yy, $mm, $dd)){
		$erm = "予約日付が不正なため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}
	if ( $hh < 0 || $hh > 23){
		$erm = "予約時間が不正なため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}
	if ( $ml_type == '02' && ( $hh < 7 || $hh > 22 ) ){
		$erm = "この時間帯では予約ができないため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}

	$ymdh = "$yy/$mm/$dd $hh:00:00";
	$sql = "select SP_SEQ_NO from T_STOP_PERIOD where TO_TIMESTAMP('$ymdh','yyyy/mm/dd hh24:MI:SS') between SP_START_DATE and SP_END_DATE";
	if ($dbh->selectrow_array($sql)) {
		$erm = "この時間はサーバメンテナンス中のため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}


	# 予約日可能時間確認(予約日は翌月末まで)
	$rsv = sprintf("%04d%02d%02d%02d%02d", $yy, $mm,$dd, $hh, 0);
	$now = sprintf("%04d%02d%02d%02d%02d", $year, $mon,$mday, $hour, $min);

	if ( $rsv < $now ){
		$erm = "予約時間を過ぎていたため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}

	if ( $yy == $year ){
		if ( $mm > ($mon+1) ){
			$erm = "配信予約日時が翌月末以降であるため配信予約されませんでした。";
			return (0, 0, 0, 0, $erm);
		}
	}elsif ($yy == ($year+1)){
		if ( $mm != 1 || $mon != 12 ){
			$erm = "配信予約日時が翌月末以降であるため配信予約されませんでした。";
			return (0, 0, 0, 0, $erm);
		}
	}else{
		$erm = "配信予約日時が翌月末以降であるため配信予約されませんでした。";
		return (0, 0, 0, 0, $erm);
	}

	return ($yy, $mm, $dd, $hh, $erm);
}

#=== 日付確認 ===
sub isValidDate{
    local($year, $mon, $day) =@_;

    return 0 if $year < 2001
             || $year > 9999
             || $mon  <    1
             || $mon  >   12
             || $day  <    1
             || $day  >   31;

    # 末日を計算
    local($lastday) = (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[$mon - 1]
         + ($mon == 2 && ($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0));
    ($day <= $lastday) ? 1 : 0;
}

#=== 配信予約(DB書込) ===
sub reserve_magazine {
	local($r_mag_id, $r_rsv_date_and_time, $r_rsv_month, $r_rsv_day, $r_subject, $r_body) = @_;
	local($index_id, $body_id, $sql, $sth);

	# index_id 取得
	$index_id = get_seq($dbh, '0103');
	if ($index_id == '') {
		return 0;
	}

	# body_id 取得
	$sql = "select BODY_ID from T_BODY where MAG_ID = '$r_mag_id' order by BODY_ID desc";
	if (($body_id) = $dbh->selectrow_array($sql)) {
		$body_id += 1;
		if ($body_id > 9999) {
			$body_id = 1;
		}
	}else{ # 注)errorの場合も同様
		$body_id = 1;
	}
#	print "index_id=".$index_id."\n";		# for debug
#	print "body_id=".$body_id."\n";			# for debug

	# 本文挿入
	$sql = "insert into T_BODY values (?, ?, ?, ?, ?, current_timestamp)";
	$sth = $dbh->prepare($sql);
	if (!$sth->execute($index_id, $r_mag_id, $body_id, $r_subject, $r_body)){
		return 0;
	}

	# 予約
	$sql = "insert into T_PUB_RESERVE "
		 . "(index_id, mag_id, body_id, resrv_month, resrv_day, resrv_hour,resrv_flg,modefy_dt) "
		 . " values ($index_id, \'$r_mag_id\', $body_id, $r_rsv_month, $r_rsv_day, "
		 .             "to_timestamp(\'$r_rsv_date_and_time\', 'YYYY/MM/DD HH24:MI'), 0, current_timestamp)";
	$sth = $dbh->prepare($sql);
	if (!$sth->execute()){
		return 0;
	}

	return true;
}

#=== 受領確認返信 ===
sub send_anser_letter {
	local($r_reply_addr, $r_mag_nm, $r_date_and_time_msg, $r_subject, $r_body) = @_;
	local($t_subject, $t_from, $t_cc, $t_bcc, $t_body);

	($t_subject, $t_from, $t_cc, $t_bcc, $t_body) = &get_mail_template("melpubok");

	$header = "To: ".mimeencode($r_reply_addr)."\n"
			. "From: ".mimeencode($t_from)."\n"
			. "Subject: ".mimeencode($t_subject)."\n"
			. "Error-To: ".mimeencode($err_mail_adr)."\n";

	$t_body =~ s/\@mag_nm\@/$r_mag_nm/g;
	$t_body =~ s/\@delivery_date\@/$r_date_and_time_msg/g;
	$t_body =~ s/\@contents\@/$r_body/g;

	$t_body = jcode::jis($t_body);

	# send宛てメールは無視
	if ($r_reply_addr eq 'send@melonpan.net'){
		&write_log("send\@melonpan.net宛てメールのため送信中止\n");
		print "(ver 2001.10.11)error ret\n";
		return;
	}
	# ------------------

	# メール送信
	&send_mail($header, $t_body);

#	print "受領確認メール送信完了\n";		# for debug
	return;
}

#=== エラー通知送信(発行者) ===
sub send_err_mail {
	local($r_reply_addr, $e_msg, $f_text) = @_;
	local($t_subject, $t_from, $t_cc, $t_bcc, $t_body);

	($t_subject, $t_from, $t_cc, $t_bcc, $t_body) = &get_mail_template("melpubng");

	$header = "To: ".mimeencode($r_reply_addr)."\n"
			. "From: ".mimeencode($t_from)."\n"
			. "Subject: ".mimeencode($t_subject)."\n"
			. "Error-To: ".mimeencode($err_mail_adr)."\n";

	$t_body =~ s/\@err_msg\@/$e_msg/g;
	$t_body =~ s/\@rcv_mail\@/$f_text/g;
	$t_body = jcode::jis($t_body);

	# ログ保存
	&write_log($e_msg."\n".$f_text);

	# send宛てメールは無視
	if ($r_reply_addr eq 'send@melonpan.net'){
		&write_log("send\@melonpan.net宛てメールのため送信中止\n");
		print "(ver 2001.10.11)error ret\n";
		return;
	}
	# ------------------

	# メール送信
	&send_mail($header, $t_body);

#	print "エラー通知送信完了\n";			# for debug
	return;
}

#=== メールテンプレート取得 ===
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

#=== メール送信 ===
sub send_mail {
	local($ml_head, $ml_body) = @_;

	open(MAIL, "| $sendmail -i -t > /dev/null") or die "接続失敗(smtp): $!";
#	$ml_head .= "Mime-Version: 1.0\n"
	$ml_head .= "Content-Type: text/plain; charset=ISO-2022-JP\n"
			 .  "Content-Transfer-Encoding: 7bit\n";
	print MAIL $ml_head, $ml_body;

	close MAIL;
}

#=== エラー通知 ===
sub send_suspend_mail {
	local($e_msg, $f_text) = @_;

	# ログ保存
	&write_log("$e_msg\n".$f_text);
	print "エラーログ登録完了\n";
	return;
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

	$logdata = "ERROR TIME=".$logdata."\n".$f_text;
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

