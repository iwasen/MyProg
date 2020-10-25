#!/usr/bin/perl

$bakyun_id = "000013";
$letter_id = "000049";
$letter_html_id = "S00001";

$ENV{NLS_DATE_FORMAT} = 'YYYY/MM/DD HH24:MI:SS';

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

require 'get_seq.pl';	# for tras_id

# ログファイルＤｉｒ
$logs_dir = 'maillogs/';

# ロックファイル名
$lockfname = 'lock';

# 送信元アドレス
$mailfrom   = 'magazine@melonpan.net';

# 運営元情報
$unei_msg = "\n\n「◆めろんぱんシステム運営管理\n　：株式会社○○○○○○　http://www.xxxxxxx.co.jp/」\n";

######### ロックファイル確認 #######
&lockfile;
if ($okrun != 0) {
	exit 0;
}

# 乱数初期化
srand();

## 日時を取得
$ENV{'TZ'} = "JST-9";
($sec,$min,$hour,$mday,$mon,$year) = localtime(time);
$mon++;
$year -= 100;
$date_now = sprintf("%02d/%02d/%02d %02d:%02d:%02d",$year,$mon,$mday,$hour,$min,$sec);
$access_day_moto = sprintf("%02d%02d%02d",$year,$mon,$mday);
if (length($access_day_moto) == 6) {
	$access_day = "20".$access_day_moto;
}

## 配信日を作成
$delivery_date_moto = sprintf("%02d.%02d.%02d",$year,$mon,$mday);
if (length($delivery_date_moto) == 8) {
	$delivery_date = "20".$delivery_date_moto;
}

# dbサーバ名
getopts('d:a:',\%opts);
$db_host = $opts{'d'} || 'localhost';
$all_send_addr = $opts{'a'} || '';

$dbh = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "",{ RaiseError => 1, AutoCommit => 0 });
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh;

$dbh->{LongReadLen} = 10000000;
$dbh->{LongTruncOk} = 1;

my($mag_id, $body_id);
my($mag_title, $mag_body,$sql,$send_volume);
my($fr_personal);

$sth = $dbh->prepare("select index_id, mag_id, body_id, resrv_hour from t_pub_reserve where resrv_flg = '0 ' and resrv_hour <= current_timestamp");
$sth->execute;
$ErrFlg = $sth->state;
&errorstep;

$sth->bind_columns(undef, \($index_id, $mag_id, $body_id, $resrv_hour));

#送信ループ開始
while ($sth->fetch) {
	# ログメッセージ
	$logs = "";

	if ($mag_id eq "" || $body_id eq "") { next; }

	# 配信予約テーブルテーブルのフラグ更新
	$sql = "update t_pub_reserve set resrv_flg = '2 ' where  mag_id = '$mag_id' and body_id = $body_id";
	$sth30 = $dbh->prepare($sql);
	$sth30->execute;
	$ErrFlg = $sth30->state;
	if ($ErrFlg != 0) {
		$logs .= "t_pub_reserve Update Error MAGID:$mag_id BODYID:$body_id ($log_body)\n";
	}
	$sth30->finish;

	# 本文取得
	$sql = "select mag_titl, mag_body from t_body where  mag_id = '$mag_id' and body_id = $body_id";
	$sth1 = $dbh->prepare($sql);
	$sth1->execute;
	$ErrFlg = $sth1->state;
	if ($ErrFlg != 0) {
		$logs .= "Body Read Error MAGID:$mag_id ($log_body)\n";
		next;
	}
	($mag_title,$mag_body) = $sth1->fetchrow;

	# 本文取得チェック (2002.03.25 追加)
	if ($mag_title eq '' || $mag_body eq '') {
		$logs .= "Body or Title Read Error MAGID:$mag_id\n";
		$filename = "$mag_id.log";
		&outputlog;
		$sth1->finish;
		next;
	}

	# メルマガマスタ取得
	my($mag_nm,$sql3);
	$sql3 = "select publisher_id, mag_nm ,COALESCE(mag_volume,0) ,mail_add_publish ,mag_riba_status_flg ,add_point,COALESCE(mag_present_ins_flg,'1'),osusume_point_flg, mag_from_addr, send_notice_flg,header_insert_flg from m_magazine where mag_id = '$mag_id'";
	$sth3 = $dbh->prepare($sql3);
  	$sth3->execute;
	$ErrFlg = $sth3->state;
	if ($ErrFlg != 0) {
		$logs .= "Magazine Read Error MAGID:$mag_id ($log_body)\n";
		next;
	}
	$filename = "$mag_id.log";
	($publisher_id, $mag_nm, $mag_volume, $mail_add_publish, $mag_riba_status_flg, $add_point, $mag_present_ins_flg, $osusume_point_flg, $mag_from_addr, $send_notice_flg, $header_insert_flg) = $sth3->fetchrow;
	$mag_nm =~ s/ //g;

	$mag_volume++;

##### FOOTER_ADD
	if ($header_insert_flg) {
		$sql11 = "select admin_msg,user_footer,riba_footer,letter_footer,bakyun_footer,insert_position from T_MAG_FOOTER where access_day <= '$access_day' AND access_day_end >= '$access_day'";
		$sth11 = $dbh->prepare($sql11);
		$sth11->execute;
		$ErrFlg = $sth11->state;
		if ($ErrFlg != 0) {
			$logs .= "Footer Read Error ($log_body)\n";
			next;
		}
		while (($admin_msg,$user_footer,$riba_footer,$letter_footer,$bakyun_footer,$insert_position) = $sth11->fetchrow_array) {
			if ($admin_msg ne "") {
				if (substr($insert_position, 0, 1) == "1") {
					if ($publisher_id == 0) {
						if ($mag_id eq $letter_id) {
							if ($letter_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						} elsif ($mag_id eq $bakyun_id) {
							if ($bakyun_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						} elsif ($mag_riba_status_flg == 1) {
							if ($riba_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						} else {
							if ($user_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						}
					} else {
						if ($mag_riba_status_flg == 1) {
							if ($riba_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						} else {
							if ($user_footer == 1) { $mag_body = $admin_msg."\n\n".$mag_body; }
						}
					}
				}
				if (substr($insert_position, 1, 1) == "1") {
					if ($publisher_id == 0) {
						if ($mag_id eq $letter_id) {
							if ($letter_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						} elsif ($mag_id eq $bakyun_id) {
							if ($bakyun_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						} elsif ($mag_riba_status_flg == 1) {
							if ($riba_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						} else {
							if ($user_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						}
					} else {
						if ($mag_riba_status_flg == 1) {
							if ($riba_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						} else {
							if ($user_footer == 1) { $mag_body .= "\n\n".$admin_msg; }
						}
					}
				}
			}
		}
		$sth11->finish;
	}

	# プレゼント応募券挿入
	if ($mag_present_ins_flg == "1") {
		$kno = int(rand() * 3) + 1;
		$sql = "select PD_present_id,PD_present_name,PD_keyword_flag,PD_keyword$kno"
			. "   from M_PRESENT_DATA"
			. "  where PD_status='0' and PD_present_type='B' "
			. "    and to_date(CURRENT_TIMESTAMP,'YYYY/MM/DD') between PD_insert_start and PD_insert_end";
		if (($present_id, $present_nm, $keyword_flag, $keyword) = $dbh->selectrow_array($sql)) {
			if ($keyword_flag == "1") {
	  			$mt_id = "pre_wk";
			} else {
				$keyword = "";
				$mt_id = "pre_nk";
			}

			$appl_cd = encode_present($present_id, $mag_id, $keyword, $mag_volume);

			$sql = "select MT_body from M_MAIL_TEMPLATE where MT_id='$mt_id'";
			($footer) = $dbh->selectrow_array($sql);
			$footer =~ s/\@present_nm\@/$present_nm/;
			$footer =~ s/\@keyword\@/$keyword/;
			$footer =~ s/\@mag_id\@/$mag_id/;
			$footer =~ s/\@mag_nm\@/$mag_nm/;
			$footer =~ s/\@appl_cd\@/$appl_cd/;

			$mag_body .= "\n\n" . $footer;
		}
	}

	# 全メルマガに運営元情報挿入
	$mag_body =~ s/\s+$//;
	$mag_body .= "\n" . $unei_msg;

	if ($mag_id eq $bakyun_id) {
		$mailfrom = "yell\@xxxxxxx.co.jp";
		$mailreplyto = "yell\@xxxxxxx.co.jp";
	} elsif ($mag_id eq $letter_id or $mag_id eq $letter_html_id) {
		$mailfrom = "magazine\@melonpan.net";
		$mailreplyto = "letter\@melonpan.net";
	} else {
		$mailfrom = "magazine\@melonpan.net";
		$mailreplyto = $mail_add_publish;
	}
	$fr_personal = $mag_from_addr;	# (2001.07.25 ADD, 10.02移動)

#-- del for postgres
#	$sql_seq = "select SL_SEND_MAIL_ID_SEQ.NEXTVAL from dual";
#	$sth21 = $dbh->prepare($sql_seq);
#	$sth21->execute;
#	$SD_SEND_MAIL_ID = $sth21->fetchrow;
#	$sth21->finish;
#------------------

	$mailfrom =~ s/ //g;
	$mailreplyto =~ s/ //g;
	$mail_header = "X-Melonpan-Id: $mag_id\n" . "X-Melonpan-Pn: $mag_volume";

	$mag_title =~ s/\@date\@/$delivery_date/g;
	$mag_title =~ s/\@volno\@/$mag_volume/g;

	if ($send_notice_flg eq '1'){	# 配信結果通知
		$wk_prog_id = 'MSEND_DB';
		$wk_mag_id = $mag_id;
	}else{
		$wk_prog_id = '';
		$wk_mag_id = '';
	}

	if ($mag_id eq $letter_html_id) {
		$mail_type = '1';	# HTML
	} else {
		$mail_type = '0';	# TEXTL
	}

	if ($mag_title eq '') {$mag_title = ' ';}
	$sth20 = $dbh->prepare("insert into T_SEND_MAIL_DATA (SD_SUBJECT,SD_FROM,SD_REPLY_TO,SD_BODY,SD_STATUS,SD_MAIL_HEADER,SD_SEND_DATE,SD_personal,SD_PROG_ID,SD_PROG_DATA,SD_MAIL_TYPE) values(?,?,?,?,?,?,?,?,?,?,?)");
  	$sth20->execute($mag_title,$mailfrom,$mailreplyto,$mag_body,0,$mail_header,$resrv_hour,$fr_personal,$wk_prog_id,$wk_mag_id,$mail_type);
	$ErrFlg = $sth20->state;
	if ($ErrFlg != 0) {
		&Rollback;
		$logs .= "MAIL_DATA INSERTError MAGID:$mag_id ($log_body)\n";
		next;
	}
	$sth20->finish;

	# send_mail_id取得
	$sql = "select currval('t_send_mail_d_sd_send_mail__seq')";
	$sth21 = $dbh->prepare($sql);
	$sth21->execute;
	if ( $sth21->state != 0 ){
		die "SQL ERROR: $!";
	}
	$sth21->bind_columns(undef, \($SD_SEND_MAIL_ID));
	$sth21->fetch;
	$sth21->finish;
#------------------

	# 送信リスト(T_SEND_MAIL_LIST)書き込み(2002.03.22追加)
	my($mail_addr,$sql2);
	if ($all_send_addr eq ''){
		$sql2 = "select distinct $SD_SEND_MAIL_ID,trim(mail_addr) from t_mailaddr where mag_id = '$mag_id'";
	}else{
		$sql2 = "select distinct $SD_SEND_MAIL_ID,trim(mail_addr)"
			  . "  from (select $SD_SEND_MAIL_ID, mail_addr from t_mailaddr where mag_id='$mag_id') as tm"
			  . " union (select $SD_SEND_MAIL_ID, '$all_send_addr' as mail_addr from m_magazine where mag_id='$mag_id')"
	}
	$sql_list = "insert into T_SEND_MAIL_LIST(SL_SEND_MAIL_ID,SL_TO) ($sql2)";
	$sth22 = $dbh->prepare($sql_list);
	$send_volume = $sth22->execute;
	if ($all_send_addr ne ''){
		$send_volume--;
	}
	$ErrFlg = $sth22->state;
	if ($ErrFlg != 0) {
		#&Rollback;
		$logs .= "MAIL_LIST INSERTError MAILADDR:$mail_addr ($log_body)\n";
	}
	$sth22->finish;

	# HTMLレター送信数セット
	if ($mag_id eq $letter_html_id) {
		$sql = "UPDATE t_html_letter SET hl_send_num=$send_volume WHERE hl_index_id=$index_id";
		my $sth_hl = $dbh->prepare($sql);
		$sth_hl->execute;
	}

	# 解除用URL埋込(01.09.27追加)
	if ($mag_id eq $letter_id || $mag_id eq $bakyun_id || $mag_id eq $letter_html_id) {
		if ($mag_body =~ /\@cancelurl\@/) {
			&ins_cancle_url($SD_SEND_MAIL_ID, $mag_id);
		}
	}

	# 送信メールデータステータスを送信可
	$sql_flg = "update T_SEND_MAIL_DATA set SD_STATUS = 1 where SD_SEND_MAIL_ID = $SD_SEND_MAIL_ID";
	$sth23 = $dbh->prepare($sql_flg);
	$sth23->execute;
	$ErrFlg = $sth23->state;
	if ($ErrFlg != 0) {
		&Rollback;
		$logs .= "MAIL_DATA UPDATE Error MAGID:$mag_id ($log_body)\n";
		next;
	}
	$sth23->finish;

	# 配信予約済みにセット
	$sql = "update t_pub_reserve set resrv_flg = '1 ' where  mag_id = '$mag_id' and body_id = $body_id";
	$sth4 = $dbh->prepare($sql);
  	$sth4->execute;
	$ErrFlg = $sth4->state;
	if ($ErrFlg != 0) {
		$logs .= "t_pub_reserve Update Error MAGID:$mag_id BODYID:$body_id ($log_body)\n";
	}
	$sth4->finish;

	# マガジンマスタに配信数セット
	$sth4 = $dbh->prepare("update m_magazine set mag_volume = ? ,last_send_dt = current_timestamp where  mag_id = ? ");
  	$sth4->execute($mag_volume , $mag_id);
	$ErrFlg = $sth4->state;
	if ($ErrFlg != 0) {
		$logs .= "m_magazine Update Error MAGID:$mag_id ($log_body)\n";
	}
	$sth4->finish;

	# バックナンバーのＩＤ取得
#-- mod for postgres
#	$sql = "select max(index_id) maxid  from l_backnumber";
	$sql = "select max(index_id) as maxid  from l_backnumber";
#-------------------
 	$sth9 = $dbh->prepare($sql);
  	$sth9->execute;
	if ($maxid = $sth9->fetchrow_array) {
		$maxid++;
	} else {
		$maxid = 1;
	}

	# バックナンバー保存
	# mag_body_search 廃止 -- 2002.02.10
	$sql = "insert into l_backnumber (index_id, mag_id, body_id, mag_titl, mag_body, modefy_dt, mag_volume)"
		 . " values (?,?,?,?,?,?,?)";
 	$sth8 = $dbh->prepare($sql);
  	$sth8->execute($maxid, $mag_id, $body_id, $mag_title, $mag_body, $resrv_hour, $send_volume);
	$ErrFlg = $sth8->state;
	if ($ErrFlg != 0) {
		$logs .= "l_backnumber Insert Error MAGID:$mag_id BODYID:$body_id ($log_body)\n";
	}
	$sth8->finish;

	# 送信ログＩＤ取得
#-- mod for postgres
#	$sql = "select max(send_log_id) l_send_log_id  from  l_send";
	$sql = "select max(send_log_id) as l_send_log_id  from  l_send";
#-------------------
 	$sth4 = $dbh->prepare($sql);
  	$sth4->execute;
	if ($l_send_log_id = $sth4->fetchrow_array) {
		$l_send_log_id++;
	} else {
		$l_send_log_id = 1;
	}
	$sth4->finish;

	# 送信ログ保存
 	$sth4 = $dbh->prepare("insert into l_send (send_log_id, publisher_id, mag_id, mag_nm, send_num ,send_dt, send_volume, index_id ) values (?,?,?,?,?,?,?,?)");
  	$sth4->execute($l_send_log_id, $publisher_id, $mag_id, $mag_nm, $mag_volume, $resrv_hour, $send_volume, $maxid);
	$ErrFlg = $sth4->state;
	if ($ErrFlg != 0) {
		$logs .= "l_send Insert Error PUBLISHER_ID:$publisher_id MAGID:$mag_id ($log_body)\n";
	}

	if ($mag_riba_status_flg == 1) {
 		$sth10 = $dbh->prepare("insert into t_riba_send values(t_riba_send_seq.NEXTVAL,?,current_timestamp,?,$send_volume,0)");
		$sth10->execute($mag_id,$add_point);
		$ErrFlg = $sth10->state;
		if ($ErrFlg != 0) {
			$logs .= "t_riba_send Insert Error MAGID:$mag_id ($log_body)\n";
		}
		$sth10->finish;
	}

	$sth4->finish;

	$sth8->finish;

	$sth9->finish;

	$sth3->finish;
	$sth1->finish;

#-- mod for postgres
#	$sql = "delete t_body  where  mag_id = '$mag_id' and body_id = $body_id";
	$sql = "delete from t_body  where  mag_id = '$mag_id' and body_id = $body_id";
#-------------------
	$sth4 = $dbh->prepare($sql);
  	$sth4->execute;
	$ErrFlg = $sth4->state;
	if ($ErrFlg != 0) {
		$logs .= "t_body Delete Error MAGID:$mag_id BODYID:$body_id ($log_body)\n";
	}
	$sth4->finish;

	# オススメポイント加算
	if ($osusume_point_flg == "1") {
		# 選定から１週間後以降にポイント付与
		$sql = "select count(*) from T_OSUSUME_LIST where mag_id='$mag_id' and sel_date>date('now')-7";
		($sel_count) = $dbh->selectrow_array($sql);
		if ($sel_count == 0) {
			$sql = "update M_PUBLISHER set pt_melonpan=COALESCE(pt_melonpan,0)+$send_volume where publisher_id=$publisher_id";
			$dbh->do($sql);

			$sql = "update M_MAGAZINE set osusume_point_flg=NULL where mag_id='$mag_id'";
			$dbh->do($sql);

			&reg_point_detail($publisher_id, $mag_id, $send_volume);
		}
	}

#-- mod for postgres
#	$sql = "commit";
#	$sth4 = $dbh->prepare($sql);
# 	$sth4->execute;
#	$sth4->finish;
$dbh->commit;
#-------------------

	$logs .= "Insert OK $mag_id,$mag_title,$send_volume,$date_now \n";
	&outputlog;
}

$sth->finish;
$dbh->disconxxxt;


######### ロックファイル削除 #######
&unlockfile;

exit 0;

sub outputlog {

	# ログファイル名
	$logfile = $logs_dir . $filename;

	# ログの存在をチェック
	unless(-e $logfile) {
		if (open(LOG,">$logfile")) {
			flock(LOG,2);
			print LOG $logs;
			flock(LOG,8);
			close(LOG);

			# パーミッションを 666 へ
			chmod (0666,"$logfile");
		}
	} else {
		# 記録ファイルを更新する
		if (open(LOG,">>$logfile")) {
			flock(LOG,2);
			print LOG $logs;
			flock(LOG,8);
			close(LOG);
		}
	}

}
sub error {
	print 'Send Error';

	exit;
}
sub errorstep {
	if ($ErrFlg != 0) {
		$logs .= "DB Error($log_body)\n";
		&outputlog;
		$dbh->disconxxxt;
		######### ロックファイル削除 #######
		&unlockfile;
		exit 0;
	}
}
sub lockfile {
	$okrun = 0;
	# ロックファイル名
	$lokfile = $logs_dir . $lockfname;

	$locktime = time;
	$lock_time = sprintf("%d",$locktime);

	# ロックファイルの存在をチェック
	unless(-e $lokfile) {
		$okrun = 0;
		if (open(LOK,">$lokfile")) {
			flock(LOK,2);
			print LOK $lock_time;
			flock(LOK,8);
			close(LOK);

			# パーミッションを 666 へ
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
	# ロックファイル名
	$lokfile = $logs_dir . $lockfname;
	unlink "$lokfile";
}

sub Rollback {
	$sql = "rollback";
	$sth4 = $dbh->prepare($sql);
	$sth4->execute;
	$sth4->finish;
}

# 応募コード生成
sub encode_present {
	my($present_id, $mag_id, $keyword, $vol_no) = @_;
	my($offset, $code, $sum, $i);

	# ボリューム番号付加
	$offset = $vol_no % 100;
	$code = sprintf("%02d", $offset);

	# プレゼントID暗号化
	$code .= encode_str($present_id, $offset);

	# メルマガID暗号化
	$code .= encode_str($mag_id, $offset);

	# キーワード暗号化
	$code .= encode_str(encode_keyword($keyword), $offset);

	# チェックサム付加
	$sum = 0;
	for ($i = 0; $i < length($code); $i++) {
		$sum = ($sum + substr($code, $i, 1)) % 10;
	}
	$code .= chr((10 - $sum) % 10 + ord("0"));

	$code = substr($code, 0, 5) . "-" . substr($code, 5, 5) . "-" . substr($code, 10, 5) . "-" . substr($code, 15, 2);

	return $code;
}

# 文字列の暗号化
sub encode_str {
	my($str, $offset) = @_;
	my($code, $i);

	# 乱数列
	my $rand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416";

	$code = "";
	for ($i = 0; $i < length($str); $i++) {
		$code .= chr((substr($str, $i, 1) + substr($rand, $offset, 1)) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}
	return $code;
}

# キーワードのコード化
sub encode_keyword {
	my($keyword) = @_;
	my($sum, $i);

	$sum = 0;
	for ($i = 0; $i < length($keyword); $i++) {
		$sum = ($sum + ord(substr($keyword, $i, 1)) * ($i + 1)) % 1000;
	}
	return sprintf("%03d", $sum);
}

#ポイント詳細登録
sub reg_point_detail {
	local($publisher, $magazine, $point) = @_;
	local($sth, $sqlp, $melonpai_id);

	#選定しためろんぱいＩＤ取得
	$sql = "select melonpai_id from T_OSUSUME_LIST where MAG_ID = '$magazine' order by sel_date desc";
 	$sth = $dbh->prepare($sql);
  	$sth->execute;
	if ($melonpai_id = $sth->fetchrow_array) {
	} else {
		$melonpai_id = 0;
	}
	
	#trans_id 取得
	$trans_id = get_seq($dbh, '0601');
	if ($trans_id == '') {
		return 0;
	}

	#登録
	$sqlp = "insert into T_POINT_DETAIL "
		  . "(TRANS_ID, OWNER_KIND, OWNER_ID, ATTRIBUTION_CD, TRANS_DATE, TRANS_POINT, AVAILABLE_POINT,"
		  . " COUNTER_ID, MAG_ID ) "
		  . "values ( $trans_id, '2', $publisher, '01', CURRENT_TIMESTAMP, $point, $point, $melonpai_id, '$magazine')";
	$dbh->do($sqlp);
	return;
}

#解除ＵＲＬ埋め込み
sub ins_cancle_url {
	local($send_mail_id, $jmk_mag_id) = @_;
	local($sth61, $sth62, $sth63, $erflg, $sql6, $i);

	#
	$sql6 = "select SL_TO from T_SEND_MAIL_LIST where SL_SEND_MAIL_ID = '$send_mail_id' for update";
 	$sth61 = $dbh->prepare($sql6);
  	$sth61->execute;
	$sth61->bind_columns(undef, \($sl_to));

	for ($i=1 ; $sth61->fetch ; $i++) {
		#登録ＩＤ取得
		$sql6 = "select MAIL_ID, to_char(MODIFY_DT, 'HH24MI') from T_MAILADDR "
			 . " where MAG_ID = '$jmk_mag_id' and MAIL_ADDR = " . $dbh->quote($sl_to);
	 	$sth62 = $dbh->prepare($sql6);
  		$sth62->execute;
		$erflg = $sth62->state;
		if ($erflg != 0) {
			$cancel_url = " ";
		}else{
			$sth62->bind_columns(undef, \($mail_id, $keyword));
			$sth62->fetch;
			$cancel_url = "http://www.melonpan.net/mag-stopjmk.php?id=$mail_id$keyword";
		}
		$sth62->finish;

		#SUB_SEQ設定
		$sql6 = "update T_SEND_MAIL_LIST set SL_SUB_SEQ = $i"
		     . " where SL_SEND_MAIL_ID = '$send_mail_id' and SL_TO = " . $dbh->quote($sl_to);
		$dbh->do($sql6);

		#埋め込み変数登録
		$sql6 = "insert into T_SEND_MAIL_EMBED (SE_SEND_MAIL_ID, SE_SUB_SEQ, SE_VARIABLE_NAME, SE_EMBED_TEXT) "
			 . " values ($send_mail_id, $i, '\@cancelurl\@', '$cancel_url')";
		$dbh->do($sql6);
	}
	$sth61->finish;

	return;
}

