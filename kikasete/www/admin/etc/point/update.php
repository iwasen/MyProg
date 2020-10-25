<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント精算
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/mail.php");

// 通知メール送信
function notify_mail() {
	global $subject, $body;

	// 送信元アドレス取得
	$fetch = get_system_info('sy_mail_from,sy_mail_name,sy_mail_test');
	$from = "$fetch->sy_mail_name <$fetch->sy_mail_from>";

	// 送信先アドレス取得
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$to = db_fetch1($sql);

	send_mail($subject, $to, $from, $body);
}

// メイン処理
set_global('etc', 'その他管理', 'ポイント精算', BACK_TOP);

// 通知メールテンプレート取得
get_mail_template('seisanok', $subject, $from, $cc, $bcc, $repty_to, $body_org);

$seq_no = get_multi_data($seq_no);
$sql = "SELECT * FROM t_seisan WHERE si_seq_no IN ($seq_no)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->si_seq_no;

	// 申請者名取得
	if ($fetch->si_member_kind == 1)
		$sql = "SELECT mn_name1||' '||mn_name2 AS name FROM t_monitor WHERE mn_monitor_id=$fetch->si_member_id";
	else
		$sql = "SELECT mr_name1||' '||mr_name2 AS name FROM t_marketer WHERE mr_marketer_id=$fetch->si_member_id";
	$name = db_fetch1($sql);

	// WEBMONEYの場合は郵便番号、住所、電話番号を表示しない
	if ($fetch->si_item == 3) {
		$zip = '';
		$addr = '';
		$tel = '';
	} else {
		$zip = format_zip($fetch->si_zip);
		$addr = $fetch->si_addr;
		$tel = $fetch->si_tel;
	}

	// メール文作成
	$body = $body_org;
	$body = str_replace('%SHINSEI_DATE%', format_datetime($fetch->si_req_date), $body);
	$body = str_replace('%POINT%', number_format($fetch->si_point), $body);
	$body = str_replace('%SHOUHIN%', decode_seisan_item($fetch->si_item), $body);
	$body = str_replace('%MONITOR_NAME%', $name, $body);
	$body = str_replace('%ZIP%', $zip, $body);
	$body = str_replace('%ADDRESS%', $addr, $body);
	$body = str_replace('%TEL%', $tel, $body);
	$body = str_replace('%SEISAN_DATE%', date('Y/m/d'), $body);

	// 通知先メールアドレス取得
	switch ($fetch->si_member_kind) {
	case 1:		// モニター
		$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_monitor_id=$fetch->si_member_id";
		break;
	case 2:		// マーケター
		$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_marketer_id=$fetch->si_member_id";
		break;
	}
	$result2 = db_exec($sql);
	if (pg_numrows($result2)) {
		$fetch2 = pg_fetch_row($result2, 0);
		$to = $fetch2[0];

		send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
	}

	$sql = "UPDATE t_seisan SET si_status=1,si_seisan_date=CURRENT_TIMESTAMP WHERE si_seq_no=$seq_no";
	db_exec($sql);
}

$msg = 'ポイント精算処理を完了しました。';
$back = "location.href='list.php'";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
