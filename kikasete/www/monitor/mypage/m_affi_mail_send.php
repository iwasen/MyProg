<?
$top = './..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mn_mypage.php");

// モニター名とメールアドレス取得
$sql = "SELECT mn_mail_addr,mn_name1,mn_name2 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$monitor_name = "$fetch->mn_name1$fetch->mn_name2";
	$from = "$fetch->mn_name1 $fetch->mn_name2 <$fetch->mn_mail_addr}>";
}

db_begin_trans();

// 送信メール保存
$rec['afa_affiliate_id'] = sql_number($affiliate_id);
$rec['afa_mail_no'] = "(SELECT COALESCE(MAX(afa_mail_no),0)+1 FROM t_affiliate_mail WHERE afa_affiliate_id=$affiliate_id)";
$rec['afa_title'] = sql_char($subject);
$rec['afa_content'] = sql_char($content);
db_insert('t_affiliate_mail', $rec);

// メール送信数カウントアップ
$sql = "UPDATE t_affiliate_cnt SET afc_mail_count=afc_mail_count+1 WHERE afc_affiliate_id=$affiliate_id";
db_exec($sql);
$sql = "SELECT afc_mail_count FROM t_affiliate_cnt WHERE afc_affiliate_id=$affiliate_id";
$mail_count = db_fetch1($sql);

db_commit_trans();

// アフィリエイト管理ID取得
$sql = "SELECT af_affiliate_mgr_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id";
$affiliate_mgr_id = db_fetch1($sql);

// アフィリエイトURL取得
$fetch2 = get_system_info('sy_url_monitor');
$url_monitor = $fetch2->sy_url_monitor;
$affiliate_url = "{$url_monitor}affiliate.php?id=$affiliate_id";

// メールテンプレート取得
get_mail_template('aff_mn', $subject_dmy, $from_dmy, $cc, $bcc, $repty_to, $body);

// 差込変数
$body = str_replace('%CONTENTS%', $content, $body);
$body = str_replace('%MONITOR_NAME%', $monitor_name, $body);
$body = str_replace('%AFFILIATE_URL%', $affiliate_url, $body);
$body = str_replace('%INQ_NO%', "{$affiliate_mgr_id}_{$affiliate_id}_{$mail_count}", $body);

// メール送信
send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="default.css">
	<title>送信しました</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form>
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;">送信しました。</div></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<td>【<?=htmlspecialchars($subject)?>】を送信しました。</td>
		</tr>
		<tr class="line0">
			<td colspan="3" align="center"><input type="button" name="" value="閉じる" class="button" onclick="window.close()"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>

</form>
</body>

</html>
