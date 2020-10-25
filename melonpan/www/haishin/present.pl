#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

# データベース接続
$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{RaiseError} = 1;

# プレゼントマスタ取得
$sql = "select PD_present_id,PD_present_name,PD_prize_1,PD_prize_2,PD_prize_3,PD_prize_name1,PD_prize_name2,PD_prize_name3" .
		" from M_PRESENT_DATA" .
		" where PD_status='0' and PD_lottery_date<=date('now')";
$sth_pd = $dbh->prepare($sql);
$sth_pd->execute;
$sth_pd->bind_columns(undef, \($present_id, $present_name, $prize_1, $prize_2, $prize_3, $prize_name1, $prize_name2, $prize_name3));

while ($sth_pd->fetch) {
	# 抽選処理
	&lottery();

	#抽選済みにセット
	$sql = "update M_PRESENT_DATA set PD_status='1' where PD_present_id='$present_id'";
	$dbh->do($sql);

	# 当選通知メール送信
	&send_mail();

	$dbh->commit;
}

#-- add for postgres
	$sth_pd->finish;
#-------------------

$dbh->disconxxxt;

# === 抽選処理 ===
sub lottery {
	local($sql, $sth);
	local($i, $j, $n, $t, $v);
	local($mail_list, $mail_adr);

	# 応募者取得
	$sql = "select PL_mail_adr from T_PRESENT_LIST,T_MAILADDR M1,T_MAILADDR M2,T_MAILADDR M3"
		. " where PL_present_id='$present_id'"
		. " and PL_mail_adr=M1.mail_addr and PL_mag_id1=M1.mag_id"
		. " and PL_mail_adr=M2.mail_addr and PL_mag_id2=M2.mag_id"
		. " and PL_mail_adr=M3.mail_addr and PL_mag_id3=M3.mag_id";
	$mail_list = $dbh->selectall_arrayref($sql);

	# 応募者数取得
	*mail_adr = $mail_list;
	$n = $#mail_adr + 1;

	# 抽選用配列初期化
	for ($i = 0; $i < $n; $i++) {
		$v[$i] = $i;
	}

	# 抽選用配列を乱数によりかき混ぜる
	srand();
	for ($i = $n - 1; $i > 0; $i--) {
		$j = int(($i + 1) * rand());
		$t = $v[$i];  $v[$i] = $v[$j];  $v[$j] = $t;
	}

	# 当選者設定用SQL
	my $sql = "update T_PRESENT_LIST set PL_prize=? where PL_present_id='$present_id' and PL_mail_adr=?";
	my $sth = $dbh->prepare($sql);

	# 当選者数初期化
	my $count = 0;

	# １等賞
	for ($i = 0; $i < $prize_1 && $count < $n; $i++) {
		$sth->execute(1, $mail_adr[$v[$count++]][0]);
	}

	# ２等賞
	for ($i = 0; $i < $prize_2 && $count < $n; $i++) {
		$sth->execute(2, $mail_adr[$v[$count++]][0]);
	}

	# ３等賞
	for ($i = 0; $i < $prize_3 && $count < $n; $i++) {
		$sth->execute(3, $mail_adr[$v[$count++]][0]);
	}
}

#=== 当選通知メール送信 ===
sub send_mail {
	local($sql, $sth_sd, $sth_sl, $sth_pl);
	local($mail_adr, $prize, $sub_seq);

	# メールテンプレート取得
	$sql = "select MT_subject,MT_from,MT_cc,MT_bcc,MT_body from M_MAIL_TEMPLATE where MT_id='present'";
	($subject, $from, $cc, $bcc, $body) = $dbh->selectrow_array($sql);

#-- mod for postgres --------------------------------------------------------------
	# send_mail_id取得
#	$sql = "select SL_SEND_MAIL_ID_SEQ.NEXTVAL from dual";
#	$send_mail_id = $dbh->selectrow_array($sql);

	# T_SEND_MAIL_DATAにセット
#	$sql = "insert into T_SEND_MAIL_DATA" .
#		" (SD_SEND_MAIL_ID,SD_SUBJECT,SD_FROM,SD_BODY,SD_STATUS)" .
#		" values(?,?,?,?,'0')";
#	$sth_sd = $dbh->prepare($sql);
# 	$sth_sd->execute($send_mail_id, $subject, $from, $body);
	$sql = "insert into T_SEND_MAIL_DATA" .
		" (SD_SUBJECT,SD_FROM,SD_BODY,SD_STATUS)" .
		" values(?,?,?,'0')";
	$sth_sd = $dbh->prepare($sql);
  	$sth_sd->execute($subject, $from, $body);

	# send_mail_id取得
	$sql = "select currval('t_send_mail_d_sd_send_mail__seq')";
	$sth_sq = $dbh->prepare($sql);
	$sth_sq->execute;
	if ( $sth_sq->state != 0 ){
		die "SQL ERROR: $!";
	}
	$sth_sq->bind_columns(undef, \($send_mail_id));
	$sth_sq->fetch;
	# -------------------------------------------------------------------------------

	# T_SEND_MAIL_LISTへ挿入の準備
	$sql = "insert into T_SEND_MAIL_LIST" .
		" (SL_send_mail_id,SL_to,SL_sub_seq)" .
		" values($send_mail_id,?,?)";
	$sth_sl = $dbh->prepare($sql);

	# T_SEND_MAIL_EMBEDへ挿入の準備
	$sql = "insert into T_SEND_MAIL_EMBED" .
		" (SE_send_mail_id,SE_sub_seq,SE_variable_name,SE_embed_text)" .
		" values($send_mail_id,?,?,?)";
	$sth_se = $dbh->prepare($sql);

	# 当選者取得
	$sub_seq = 1;
	$sql = "select PL_mail_adr,PL_prize from T_PRESENT_LIST" .
		" where PL_present_id='$present_id' and PL_prize is not null";
	$sth_pl = $dbh->prepare($sql);
	$sth_pl->execute;
	$sth_pl->bind_columns(undef, \($mail_adr, $prize));
	while ($sth_pl->fetch) {
		# T_SEND_MAIL_LISTへ挿入
	  	$sth_sl->execute($mail_adr, $sub_seq);

		# T_SEND_MAIL_EMBEDへ挿入
	  	$sth_se->execute($sub_seq, '@mail_addr@', $mail_adr);
	  	$sth_se->execute($sub_seq, '@present_nm@', $present_name);
	  	$sth_se->execute($sub_seq, '@prize_no@', $prize);
		if ($prize == 1) {
		  	$sth_se->execute($sub_seq, '@prize_nm@', $prize_name1);
		} elsif ($prize == 2) {
		  	$sth_se->execute($sub_seq, '@prize_nm@', $prize_name2);
		} elsif ($prize == 3) {
		  	$sth_se->execute($sub_seq, '@prize_nm@', $prize_name3);
		}

		$sub_seq++;
	}

	# T_SEND_MAIL_DATAのステータスをセット
	$sql = "update T_SEND_MAIL_DATA set SD_status='1' where SD_send_mail_id=$send_mail_id";
	$dbh->do($sql);

#-- add for postgres
	$sth_pl->finish;
	$sth_se->finish;
	$sth_sl->finish;
	$sth_sq->finish;
	$sth_sd->finish;
#-------------------

}
