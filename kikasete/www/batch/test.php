<?
$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

switch ($_GET['action']) {
case 'mail_clear':
	//$sql = "TRUNCATE TABLE t_send_mail_data";
	//db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_list";
	db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_file";
	db_exec($sql);
	$sql = "TRUNCATE TABLE t_send_mail_embed";
	db_exec($sql);
	//$sql = "SELECT setval('t_send_mail_data_sd_send_mail_id_seq',1,false)";
	//db_exec($sql);
	break;
case 'mail_send':
	exec('/home/dbmail/kikasete.sh');
	redirect('test.php');
	break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body>
<input type="button" value="�᡼��˥塼������" onclick="location.href='mail_news.php'">
<input type="button" value="���ۿ�" onclick="location.href='send_all.php'">
<input type="button" value="���顼�ȥ᡼������" onclick="location.href='alert_mail.php'">
<input type="button" value="�ץ�ե����뽸��" onclick="location.href='profile.php'">
<input type="button" value="��ã�᡼�����" onclick="location.href='err_mail.php'">
<input type="button" value="�ݥ���ȼ�������" onclick="location.href='point.php'">
<br><br>
<input type="button" value="���󥱡��ȳ��Ͻ�λ" onclick="location.href='enquete.php'">
<input type="button" value="���󥱡��ȥ᡼������" onclick="location.href='mail_send.php'">
<input type="button" value="���󥱡��ȥ᡼�����" onclick="location.href='mail_recv.php'">
<input type="button" value="�ͣ��ѡ��ȥʡ�����" onclick="location.href='my_partner.php'">
<input type="button" value="�������᡼������" onclick="location.href='birthday_mail.php'">
<br><br>
<input type="button" value="��Ͽ���󹹿�����᡼��" onclick="location.href='update_mail.php'">
<br><br>
<input type="button" value="�᡼�������ơ��֥륯�ꥢ" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="�᡼���ۿ��ץ����¹�" onclick="location.href='test.php?action=mail_send'">
</body>
<html>
