#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('/index.html');

// �����󥻥顼�����̼���
for ($i = 0; $i < count($sex); $i++)
	$reserve->choice[$i]->sex = $sex[$i];

// ��˾��̤������ؤ�
for ($i = 0; $i < count($choice); $i++)
	$temp[$choice[$i]] = $reserve->choice[$i];
for ($i = 0; $i < count($choice); $i++)
	$reserve->choice[$i] = $temp[$i];

$reserve->name_1 = $name1;
$reserve->name_2 = $name2;
$reserve->kana_1 = $kana1;
$reserve->kana_2 = $kana2;
$reserve->mail_addr = $mail_addr;
$reserve->contact = $contact;
$reserve->tel = '';
$reserve->fax = '';
if ($reserve->contact == 2)
	$reserve->tel = $tel_fax;
elseif ($reserve->contact == 3)
	$reserve->fax = $tel_fax;
$reserve->counseling_id = $counseling_id;
$reserve->change = $change;
$reserve->comment = $comment;

redirect($next);
?>