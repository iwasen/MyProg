<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

//$date = '2005-11-02 01:00:00';

switch ($_GET['action']) {
case 'mail_clear':
	$sql = "TRUNCATE TABLE t_send_mail_data";
	db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_list";
	db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_file";
	db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_embed";
	db_exec($sql);
	$sql = "SELECT setval('t_send_mail_data_sd_send_mail_id_seq',1,false)";
	db_exec($sql);
	break;
case 'mail_send':
	exec('/home/dbmail/hitobito.sh');
	redirect('test.php');
	break;
}

if ($date)
	$param = "?dt=$date";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>�ҤȤӤȡ�net Batch Test</title>
</head>
<body>

<h2>�ҤȤӤȡ�net �Хå��ƥ���</h2>

<input type="button" value="�ҤȤӤ��ؤ��ۿ�" onclick="location.href='hitobito_letter.php<?=$param?>'">
<input type="button" value="���ޥ��ۿ�" onclick="location.href='melmaga_send.php<?=$param?>'">
<input type="button" value="�磻�磻��ļ���" onclick="location.href='room_recv.php'">
<input type="button" value="�磻�磻����ۿ�" onclick="location.href='room_send.php'">
<input type="button" value="��ã�᡼���������" onclick="location.href='errmail_recv.php'">
<input type="button" value="���󥱡��ȳ��Ͻ���" onclick="location.href='enquete.php<?=$param?>'">
<br><br>
<input type="button" value="�����ȥץ�ե����뽸��" onclick="location.href='site_profile.php<?=$param?>'">
<input type="button" value="���̽���" onclick="location.href='daily_count.php<?=$param?>'">
<input type="button" value="�������塼�뽸��" onclick="location.href='schedule.php<?=$param?>'">
<input type="button" value="��Ͽ����" onclick="location.href='regist.php<?=$param?>'">
<input type="button" value="�����󥿹���" onclick="location.href='update_counter.php'">
<br><br>
<input type="button" value="�ʥӥ�󥭥󥰹���" onclick="location.href='ranking_navi.php<?=$param?>'">
<input type="button" value="�͵�������󥭥󥰹���" onclick="location.href='ranking_article.php<?=$param?>'">
<input type="button" value="���������ʥӹ���" onclick="location.href='ichioshi_navi.php<?=$param?>'">
<input type="button" value="�ǿ��������߾������" onclick="location.href='kuchikomi.php'">

<h2>�桼�ƥ���ƥ�</h2>
<input type="button" value="�᡼�������ơ��֥륯�ꥢ" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="�᡼���ۿ��ץ����¹�" onclick="location.href='test.php?action=mail_send'">
<input type="button" value="��ļ�����åɥ�ʥ��" onclick="location.href='thread_child.php'">
<hr>
</body>
</html>
