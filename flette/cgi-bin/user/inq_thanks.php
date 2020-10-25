#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/macro.php");

// 送信メールテンプレート取得
get_mail_template('inquiry', $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

// 差込マクロ
$macro_ary['INQUIRY_SUBJECT'] = $inq_subject;
$macro_ary['INQUIRY_CONTENT'] = $inq_content;
$macro_ary['INQUIRY_NAME'] = $name;
$macro_ary['MAIL_ADDR'] = $mail_addr;

// 差込マクロ置換
replace_macro($subject, $macro_ary);
replace_macro($from_name, $macro_ary);
replace_macro($from_addr, $macro_ary);
replace_macro($to_addr, $macro_ary);
replace_macro($cc_addr, $macro_ary);
replace_macro($bcc_addr, $macro_ary);
replace_macro($body, $macro_ary);

// メール送信
send_mail($subject, $to_addr, $mail_addr, $from_name, $body, $cc_addr, $bcc_addr);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>
お問い合わせありがとうございました。
</body>
</html>
