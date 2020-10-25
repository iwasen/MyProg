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
<input type="button" value="メールニュース送信" onclick="location.href='mail_news.php'">
<input type="button" value="全配信" onclick="location.href='send_all.php'">
<input type="button" value="アラートメール送信" onclick="location.href='alert_mail.php'">
<input type="button" value="プロファイル集計" onclick="location.href='profile.php'">
<input type="button" value="不達メール処理" onclick="location.href='err_mail.php'">
<input type="button" value="ポイント失効処理" onclick="location.href='point.php'">
<br><br>
<input type="button" value="アンケート開始終了" onclick="location.href='enquete.php'">
<input type="button" value="アンケートメール送信" onclick="location.href='mail_send.php'">
<input type="button" value="アンケートメール受信" onclick="location.href='mail_recv.php'">
<input type="button" value="Ｍｙパートナー処理" onclick="location.href='my_partner.php'">
<input type="button" value="誕生日メール送信" onclick="location.href='birthday_mail.php'">
<br><br>
<input type="button" value="登録情報更新依頼メール" onclick="location.href='update_mail.php'">
<br><br>
<input type="button" value="メール送信テーブルクリア" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="メール配信プログラム実行" onclick="location.href='test.php?action=mail_send'">
</body>
<html>
