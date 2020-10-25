#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteͽ�����ϥڡ���
' Content:���ܾ������ϥ����å�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/check.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('timeout.php');

// ���顼�ꥻ�å�
$reserve->error = null;

// �ե����फ��ǡ�������
$reserve->name_1 = $name1;
$reserve->name_2 = $name2;
$reserve->kana_1 = $kana1;
$reserve->kana_2 = $kana2;
$reserve->mail_addr = $mail_addr;
$reserve->mail_addr2 = $mail_addr2;
$reserve->contact_flag = $contact_flag;
$reserve->contact_mail = $contact_mail;
$reserve->contact_keitai = $contact_keitai;
$reserve->contact_jitaku = $contact_jitaku;
$reserve->contact_time = $contact_time;
$reserve->contact_time_memo = $contact_time_memo;

// ̾�����ϥ����å�
if ($reserve->name_1 == '' || $reserve->name_2 == '')
	$reserve->error['name'] = '�㤪̾�������Ϥ��Ƥ���������';

// �դ꤬�����ϥ����å�
if ($reserve->kana_1 == '' || $reserve->kana_2 == '')
	$reserve->error['kana'] = '��եꥬ�ʤ����Ϥ��Ƥ���������';

// �᡼�륢�ɥ쥹���ϥ����å�
if ($reserve->mail_addr == '')
	$reserve->error['mail_addr'] = '��᡼�륢�ɥ쥹�����Ϥ��Ƥ���������';
elseif ($reserve->mail_addr2 == '')
	$reserve->error['mail_addr'] = '��᡼�륢�ɥ쥹�ʳ�ǧ�ѡˤ����Ϥ��Ƥ���������';
elseif ($reserve->mail_addr != $reserve->mail_addr2)
	$reserve->error['mail_addr'] = '��᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($reserve->mail_addr))
	$reserve->error['mail_addr'] = '��᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���';

// Ϣ�������ϥ����å�
if (!$reserve->contact_flag[0]
			&& (!$reserve->contact_flag[1] || $reserve->contact_mail == '')
			&& (!$reserve->contact_flag[2] || $reserve->contact_keitai == '')
			&& (!$reserve->contact_flag[3] || $reserve->contact_jitaku == ''))
	$reserve->error['contact'] = '��Ϣ��������Ϥ��Ƥ���������';
else {
	if ($reserve->contact_flag[1] && $reserve->contact_mail == '')
		$reserve->error['contact'] = '��᡼�륢�ɥ쥹�����Ϥ��Ƥ���������';
	elseif ($reserve->contact_flag[1] && !check_mail_addr($reserve->contact_mail))
		$reserve->error['contact'] = '��᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���';
	elseif ($reserve->contact_flag[2] && $reserve->contact_keitai == '')
		$reserve->error['contact'] = '����������ֹ�����Ϥ��Ƥ���������';
	elseif ($reserve->contact_flag[3] && $reserve->contact_jitaku == '')
		$reserve->error['contact'] = '�㼫�������ֹ�����Ϥ��Ƥ���������';
}

// ��˾Ϣ����������ϥ����å�
if (($reserve->contact_flag[2] && $reserve->contact_keitai != '')
		|| ($reserve->contact_flag[3] && $reserve->contact_jitaku != '')) {
	if ($reserve->contact_time == '')
		$reserve->error['contact_time'] = '������Ӥ����Ӥ���������';
}

// ���顼�����ä��������
if ($reserve->error)
	redirect('pro_form.php');

// ���ڡ�����
redirect('check.php');
?>