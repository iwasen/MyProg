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
<title>ひとびと・net Batch Test</title>
</head>
<body>

<h2>ひとびと・net バッチテスト</h2>

<input type="button" value="ひとびと便り配信" onclick="location.href='hitobito_letter.php<?=$param?>'">
<input type="button" value="メルマガ配信" onclick="location.href='melmaga_send.php<?=$param?>'">
<input type="button" value="ワイワイ会議受信" onclick="location.href='room_recv.php'">
<input type="button" value="ワイワイ会議配信" onclick="location.href='room_send.php'">
<input type="button" value="不達メール受信処理" onclick="location.href='errmail_recv.php'">
<input type="button" value="アンケート開始処理" onclick="location.href='enquete.php<?=$param?>'">
<br><br>
<input type="button" value="サイトプロファイル集計" onclick="location.href='site_profile.php<?=$param?>'">
<input type="button" value="日別集計" onclick="location.href='daily_count.php<?=$param?>'">
<input type="button" value="スケジュール集計" onclick="location.href='schedule.php<?=$param?>'">
<input type="button" value="登録管理" onclick="location.href='regist.php<?=$param?>'">
<input type="button" value="カウンタ更新" onclick="location.href='update_counter.php'">
<br><br>
<input type="button" value="ナビランキング更新" onclick="location.href='ranking_navi.php<?=$param?>'">
<input type="button" value="人気記事ランキング更新" onclick="location.href='ranking_article.php<?=$param?>'">
<input type="button" value="イチオシナビ更新" onclick="location.href='ichioshi_navi.php<?=$param?>'">
<input type="button" value="最新クチコミ情報取得" onclick="location.href='kuchikomi.php'">

<h2>ユーティリティ</h2>
<input type="button" value="メール送信テーブルクリア" onclick="location.href='test.php?action=mail_clear'">
<input type="button" value="メール配信プログラム実行" onclick="location.href='test.php?action=mail_send'">
<input type="button" value="会議室スレッドリナンバ" onclick="location.href='thread_child.php'">
<hr>
</body>
</html>
