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

<h2>バッチテスト</h2>

<input type="button" value="メール受信処理" onclick="location.href='mail_recv.php'">
<input type="button" value="メール送信処理" onclick="location.href='mail_send.php'">
<input type="button" value="不達メール受信処理" onclick="location.href='mail_err.php'">
<input type="button" value="ジョブ開始終了" onclick="location.href='job.php'">
<br><br>
<input type="button" value="全バッチ一括実行" onclick="location.href='test.php?action=all_batch'">
<hr>

<h2>ユーティリティ</h2>
<input type="button" value="メール送信テーブルクリア" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="メール配信プログラム実行" onclick="location.href='test.php?action=mail_send'">
<hr>
</body>
</html>
