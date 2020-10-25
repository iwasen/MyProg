#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/mail.php");
include("$inc/macro.php");
include("$inc/decode.php");
include("$inc/csv.php");

// メール送信処理
function send_reserve_mail(&$macro_ary, $template_id) {
	// 送信メールテンプレート取得
	get_mail_template($template_id, $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

	// 差し込みマクロ処理
	replace_macro($subject, $macro_ary);
	replace_macro($from_name, $macro_ary);
	replace_macro($from_addr, $macro_ary);
	replace_macro($to_addr, $macro_ary);
	replace_macro($cc_addr, $macro_ary);
	replace_macro($bcc_addr, $macro_ary);
	replace_macro($body, $macro_ary);

	// メール送信
	send_mail($subject, $to_addr, $from_addr, $from_name, $body, $cc_addr, $bcc_addr);
}

// 予約申込みログに書き込み
function write_log(&$reserve, &$data) {
	$rec['rl_seq_no'] = sql_number($reserve->seq);
	$rec['rl_date'] = sql_date($reserve->date);
	$rec['rl_ip_addr'] = sql_char($_SERVER['REMOTE_ADDR']);
	$rec['rl_data'] = sql_char(str_rot13(base64_encode($data)));
	db_insert('l_reserve', $rec);
}

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('/index.html');

// 予約申し込み連番
$reserve->seq = get_next_seq('l_reserve', 'rl_seq_no');

// 予約日時
$reserve->date = date('Y/m/d H:i:s');

// 差し込みデータ取得
$macro_ary = get_macro($reserve);

// 予約申込みログに書き込み
write_log($reserve, $macro_ary['CSV']);

// 予約申込者と事務局にメール送信
send_reserve_mail($macro_ary, 'reserve_customer');
send_reserve_mail($macro_ary, 'reserve_center');

// セッション削除
session_destroy();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>
ご予約ありがとうございました。
</body>
</html>
