<?
/******************************************************
' System :�֤�����Factory�ץ桼���ڡ���
' Content:���鳨�������߼��ս���
'******************************************************/

$inc = "../inc";
include_once("$inc/database.php");
include_once("$inc/check.php");
include_once("$inc/mail.php");
include_once("$inc/status.php");

// �᡼�륢�ɥ쥹���ϥ����å�
if ($mail_addr == '') {
	$msg = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	include('error2.php');
	exit;
}

// �᡼�륢�ɥ쥹�����������å�
if (!check_mail_addr($mail_addr)) {
	$msg = '�᡼�륢�ɥ쥹���������ʤ��褦�Ǥ�������ǧ�ξ塢�⤦�������Ϥ��Ƥ���������';
	include('error2.php');
	exit;
}

// �����᡼��ƥ�ץ졼�ȼ���
get_mail_template('user_acc', $subject, $from, $cc, $bcc, $repty_to, $body);

// From���ɥ쥹��ID���ղ�
$from = str_replace('@', "-$id@", $from);

// ���մ�λ�᡼������
send_mail($subject, $mail_addr, $from, $body);

// ���鳨�ǡ����ơ��֥빹��
$rec['nd_status'] = STATUS_USER_RECEIVED;
$rec['nd_appl_date'] = 'current_timestamp';
$rec['nd_mail_addr'] = sql_char($mail_addr);
$rec['nd_sex_code'] = sql_number($sex_code);
$rec['nd_age'] = sql_number($age);
db_update('t_nigaoe_data', $rec, "nd_random_id=" . sql_char($id));

// Shift_JIS�ǽ���
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>������Factory</title>
</head>
<body>
<h2 align="center"><font color="#FFCC00">��</font><font color="#dd0000">������</font><font color="#FFCC00">��</font></h2>
<hr color="#5555dd">
���������ߤ��꤬�Ȥ��������ޤ�����<br>
���ճ�ǧ�᡼������ꤷ�ޤ����Τǡ���̿���ź�դξ塢���ֿ�����������
<hr color="#5555dd">
</body>
</html>
