<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:全配信
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

// テストメール送信
function test_mail() {
	global $subject, $body;

	// 送信元アドレス取得
	$fetch = get_system_info('sy_mail_from,sy_mail_name,sy_mail_test');
	$from = "$fetch->sy_mail_name <$fetch->sy_mail_from>";

	// 送信先アドレス取得
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$to = db_fetch1($sql);

	send_mail($subject, $to, $from, $body);
}

// メイン処理
set_global('etc', 'その他管理', '全配信', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO t_send_all (sa_target,sa_subject,sa_body,sa_send_date) VALUES (%s,%s,%s,%s)",
				sql_char(get_multi_flag($target, 12)),
				sql_char($subject),
				sql_char($body),
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h));
	db_exec($sql);
	$msg = '全配信メールを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = sprintf("UPDATE t_send_all SET sa_target=%s,sa_subject=%s,sa_body=%s,sa_send_date=%s WHERE sa_send_id=$send_id",
				sql_char(get_multi_flag($target, 12)),
				sql_char($subject),
				sql_char($body),
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h));
	db_exec($sql);
	$msg = '全配信メールを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_send_all WHERE sa_send_id=$send_id";
	db_exec($sql);
	$msg = '全配信メールを削除しました。';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail();
	$msg = 'テストメールを送信しました。';
	$back = "history.back()";
	break;
default:
	redirect('list.php');
}
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
