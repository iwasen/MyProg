#!/usr/bin/perl

use Net::POP3;                       # Net::POP3 モジュールを使う
use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

require 'get_seq.pl';

$err_mail_adr = 'sys@melonpan.net';
$logfile = './delete_mail.log';
$MAX_ERR_COUNT = 5;

getopts('au:h:p:d:',\%opts);

$hostname = $opts{'h'} || 'localhost';
$username = $opts{'u'} || 'sys';
$password = $opts{'p'} || 'xxxxxxxn7d';
$use_apop = $opts{'a'} || 0;
# dbサーバ名
$db_host = $opts{'d'} || 'localhost';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{RaiseError} = 0;

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

*mailindex = $ref_mailsize;          # $ref_mailsize の 別名を作る

foreach (sort {$a<=>$b } keys %mailindex) {
	$ref_lines = $pop->get($_);      # メール本文を取得
	$pop->delete($_);

	$to_addr = '';
	$mag_id = '';
	$mag_volume = '';
	$clean_seq = '';
	$clean_sub = '';
	$warning = 0;
	foreach (@$ref_lines) {         # 1行ずつ表示
		if ($_ =~ /^To: (.*)/) {
			$tmp = $1;
			$tmp =~ s/^\s+//;
			$tmp =~ s/\s+$//;
			if ($tmp ne $err_mail_adr) {
				$to_addr = $tmp;
			}
		} elsif ($_ =~ /^X-Melonpan-Id: (.*)/) {
			$mag_id = $1;
			$mag_id =~ s/ //g;
		} elsif ($_ =~ /^X-Melonpan-Pn: (.*)/) {
			$mag_volume = $1;
			$mag_volume =~ s/ //g;
		} elsif ($_ =~ /^X-Melonpan-Mc: (.*)/) {
			$clean_seq = $1;
			$clean_seq =~ s/ //g;
		} elsif ($_ =~ /^X-Melonpan-Ms: (.*)/) {
			$clean_sub = $1;
			$clean_sub =~ s/ //g;
		} elsif ($_ =~ /\(warning-timeout\)/) {
			$warning = 1;
		}
	}

#$to_addr = 'err@xxxxx-xxxx.co.jp ';
#$mag_id = '000013';
#$mag_volume = '45';
#$warning = 0;

	if ($warning == 0) {
		if ($to_addr ne '' && length($mag_id) == 6 && $mag_volume ne '') {
			&error_mail($to_addr, $mag_id, $mag_volume);
		} elsif ($clean_seq ne '' && $clean_sub ne '') {
			&clean_mail($clean_seq, $clean_sub);
		}
	}
#last;
}

$pop->quit();                        # ログアウト

$dbh->disconxxxt;

#=== エラーメール処理 ===
sub error_mail {
	local($p_mail_addr, $p_mag_id, $p_mag_seq) = @_;
	local($s_mail_addr, $s_mag_id);

	$s_mail_addr = $dbh->quote($p_mail_addr);
	$s_mag_id = $dbh->quote($p_mag_id);

	$sql = "SELECT READER_ID,MAG_SEQ,coalesce(ERR_COUNT,0) FROM T_MAILADDR WHERE MAG_ID=$s_mag_id AND MAIL_ADDR=$s_mail_addr";
	if (($f_reader_id, $f_mag_seq, $f_err_count) = $dbh->selectrow_array($sql)) {
		if ($f_mag_seq + 1 == $p_mag_seq) {
			if (++$f_err_count >= $MAX_ERR_COUNT) {
				&delete_mail_addr($p_mail_addr, $p_mag_id, $f_reader_id);
			} else {
				$sql = "UPDATE T_MAILADDR SET MAG_SEQ=$p_mag_seq,ERR_COUNT=$f_err_count WHERE MAG_ID=$s_mag_id AND MAIL_ADDR=$s_mail_addr";
				$sth = $dbh->prepare($sql);
				$sth->execute;
			}
		} elsif ($f_mag_seq != $p_mag_seq) {
			$sql = "UPDATE T_MAILADDR SET MAG_SEQ=$p_mag_seq,ERR_COUNT=1 WHERE MAG_ID=$s_mag_id AND MAIL_ADDR=$s_mail_addr";
			$sth = $dbh->prepare($sql);
			$sth->execute;
		}
	} else {
		# テスト用ログ
		&append_log('./unmatch.log', "$p_mag_id $p_mail_addr");
	}
}

#=== メールアドレス削除処理 ===
sub delete_mail_addr {
	local($p_mail_addr, $p_mag_id, $p_reader_id) = @_;
	local($s_mail_addr, $s_mag_id);

	$s_mail_addr = $dbh->quote($p_mail_addr);
	$s_mag_id = $dbh->quote($p_mag_id);

	$delete_id = get_seq($dbh, '0203');
	if ($delete_id == '') {
		return;
	}

	$reader_id = ($p_reader_id == 0) ? 'NULL' : $p_reader_id;
	$reason = 'メール不達による自動解除';

	$sql = "DELETE from T_MAILADDR WHERE MAG_ID=$s_mag_id AND MAIL_ADDR=$s_mail_addr";
	$dbh->do($sql);

	$sql = "INSERT INTO T_DELREASON (delete_id,mag_id,reader_id,reason,modify_dt)" .
			" VALUES ($delete_id,'$p_mag_id',$reader_id,'$reason',CURRENT_TIMESTAMP)";
	$dbh->do($sql);

	&write_log($p_mail_addr, $p_mag_id);
}

#=== 削除ログ書き出し ===
sub write_log {
	local($p_mail_addr, $p_mag_id) = @_;
	local($logdata);

	($sec, $min, $hour, $mday, $mon, $year) = localtime(time);
	$mon++;
	$year += 1900;
	$logdata = sprintf("%04d/%02d/%02d %02d:%02d:%02d %s %s",
			$year, $mon, $mday, $hour, $min, $sec, $p_mag_id, $p_mail_addr);

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

#=== メールクリーン処理 ===
sub clean_mail {
	local($seq_no, $sub_seq) =@_;

	$dbh->do("update T_CLEANING_LIST set CL_status='3' where CL_seq_no=$seq_no and CL_sub_seq=$sub_seq");
}
