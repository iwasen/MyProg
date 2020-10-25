<?
/******************************************************
' System :メール配信サービス
' Content:不正アドレス更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/check.php");

// メイン処理
set_global('不正アドレス更新', BACK_TOP);

db_begin_trans();

if (is_array($delete)) {
	foreach ($delete as $sub_seq) {
		$sql = "DELETE FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_sub_seq=$sub_seq";
		db_exec($sql);
	}
}

if (is_array($edit)) {
	foreach ($edit as $sub_seq => $mail_addr) {
		$sql = sprintf("UPDATE t_mail_list SET ml_mail_addr=%s,ml_status=%s WHERE ml_send_mail_id=$send_mail_id AND ml_sub_seq=$sub_seq AND ml_mail_addr<>%s",
				sql_char($mail_addr),
				sql_number(check_mail_addr($mail_addr) ? 0 : 1),
				sql_char($mail_addr));
		db_exec($sql);
	}
}

$sub0 = "SELECT COUNT(*) FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_status=0";
$sub1 = "SELECT COUNT(*) FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_status=1";
$sql = "UPDATE t_mail_count SET mc_count0=($sub0),mc_count1=($sub1) WHERE mc_send_mail_id=$send_mail_id";
db_exec($sql);

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">不正アドレスを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
