<?
$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

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
	exec('/home/dbmail/icp.sh');
	redirect('test.php');
	break;
case 'all_batch':
	$dir = dirname($_SERVER['PATH_TRANSLATED']);
	exec("cd $dir;/usr/local/bin/php job.php");
	exec("cd $dir;/usr/local/bin/php mail_recv.php");
	exec("cd $dir;/usr/local/bin/php mail_send.php");
	exec('/home/dbmail/icp.sh');
	exec("cd $dir;/usr/local/bin/php mail_err.php");
	redirect('test.php');
	break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>ICP Batch Test</title>
</head>
<body>

<h2>�Хå��ƥ���</h2>

<input type="button" value="�᡼���������" onclick="location.href='mail_recv.php'">
<input type="button" value="�᡼����������" onclick="location.href='mail_send.php'">
<input type="button" value="��ã�᡼���������" onclick="location.href='mail_err.php'">
<input type="button" value="����ֳ��Ͻ�λ" onclick="location.href='job.php'">
<br><br>
<input type="button" value="���Хå����¹�" onclick="location.href='test.php?action=all_batch'">
<hr>

<h2>�桼�ƥ���ƥ�</h2>
<input type="button" value="�᡼�������ơ��֥륯�ꥢ" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="�᡼���ۿ��ץ����¹�" onclick="location.href='test.php?action=mail_send'">
<hr>
</body>
</html>
