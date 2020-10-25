<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_html;
$title_color = $TC_MASTER;

$mailfrom = 'magazine@melonpan.net';
$mailreplyto = 'letter@melonpan.net';
$mailto = 'info2@melonpan.net';

db_begin_trans();

$sql = sprintf("INSERT INTO T_SEND_MAIL_DATA (SD_SUBJECT,SD_FROM,SD_REPLY_TO,SD_BODY,SD_STATUS,SD_MAIL_TYPE) VALUES (%s,%s,%s,%s,1,1)",
		sql_char($subject), sql_char($mailfrom), sql_char($mailreplyto), sql_char($body));
db_exec($sql);

$sql = "select currval('t_send_mail_d_sd_send_mail__seq')";
$result = db_exec($sql);
$send_mail_id = pg_fetch_result($result, 0, 0);

$sql = sprintf("INSERT INTO T_SEND_MAIL_LIST (SL_SEND_MAIL_ID,SL_TO) VALUES (%s,%s)",
		sql_char($send_mail_id), sql_char($mailto));
db_exec($sql);

db_commit_trans();

$msg = "テスト送信を行いました。（配信されるまで最大１分かかります）";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
<center>

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="history.back()">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
