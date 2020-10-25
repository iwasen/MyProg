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

// �᡼����������
function send_reserve_mail(&$macro_ary, $template_id) {
	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template($template_id, $subject, $from_addr, $from_name, $cc_addr, $bcc_addr, $to_addr, $body);

	// �������ߥޥ������
	replace_macro($subject, $macro_ary);
	replace_macro($from_name, $macro_ary);
	replace_macro($from_addr, $macro_ary);
	replace_macro($to_addr, $macro_ary);
	replace_macro($cc_addr, $macro_ary);
	replace_macro($bcc_addr, $macro_ary);
	replace_macro($body, $macro_ary);

	// �᡼������
	send_mail($subject, $to_addr, $from_addr, $from_name, $body, $cc_addr, $bcc_addr);
}

// ͽ�󿽹��ߥ��˽񤭹���
function write_log(&$reserve, &$data) {
	$rec['rl_seq_no'] = sql_number($reserve->seq);
	$rec['rl_date'] = sql_date($reserve->date);
	$rec['rl_ip_addr'] = sql_char($_SERVER['REMOTE_ADDR']);
	$rec['rl_data'] = sql_char(str_rot13(base64_encode($data)));
	db_insert('l_reserve', $rec);
}

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('/index.html');

// ͽ�󿽤�����Ϣ��
$reserve->seq = get_next_seq('l_reserve', 'rl_seq_no');

// ͽ������
$reserve->date = date('Y/m/d H:i:s');

// �������ߥǡ�������
$macro_ary = get_macro($reserve);

// ͽ�󿽹��ߥ��˽񤭹���
write_log($reserve, $macro_ary['CSV']);

// ͽ�󿽹��ԤȻ�̳�ɤ˥᡼������
send_reserve_mail($macro_ary, 'reserve_customer');
send_reserve_mail($macro_ary, 'reserve_center');

// ���å������
session_destroy();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>
��ͽ�󤢤꤬�Ȥ��������ޤ�����
</body>
</html>
