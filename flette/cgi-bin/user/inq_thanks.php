#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/macro.php");

// �����᡼��ƥ�ץ졼�ȼ���
get_mail_template('inquiry', $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

// �����ޥ���
$macro_ary['INQUIRY_SUBJECT'] = $inq_subject;
$macro_ary['INQUIRY_CONTENT'] = $inq_content;
$macro_ary['INQUIRY_NAME'] = $name;
$macro_ary['MAIL_ADDR'] = $mail_addr;

// �����ޥ����ִ�
replace_macro($subject, $macro_ary);
replace_macro($from_name, $macro_ary);
replace_macro($from_addr, $macro_ary);
replace_macro($to_addr, $macro_ary);
replace_macro($cc_addr, $macro_ary);
replace_macro($bcc_addr, $macro_ary);
replace_macro($body, $macro_ary);

// �᡼������
send_mail($subject, $to_addr, $mail_addr, $from_name, $body, $cc_addr, $bcc_addr);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>
���䤤��碌���꤬�Ȥ��������ޤ�����
</body>
</html>
