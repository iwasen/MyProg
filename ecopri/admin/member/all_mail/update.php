<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:全配信メール設定
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

function test_mail($from) {
	global $subject, $body;

	// 送信元アドレス取得
	$fetch = get_system_info('sy_mail_from,sy_mail_name');
	$from = "$fetch->sy_mail_name <$fetch->sy_mail_from>";

	// 送信先アドレス取得
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_COOKIE['admin_login_id']}";
	$to = db_fetch1($sql);

	send_mail($subject, $to, $from, $body);
}

// メイン処理
set_global('member', '会員情報管理', '全配信メール設定', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_all_mail (am_target,am_subject,am_body,am_send_date) VALUES (" .
				sql_number($target) . "," .
				sql_char($subject) . "," .
				sql_char($body) . "," .
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h) . ")";
	db_exec($sql);
	$msg = '全配信メールを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "UPDATE t_all_mail SET" .
				" am_target=" . sql_number($target) .
				", am_subject=" . sql_char($subject) .
				",am_body=" . sql_char($body) .
				",am_send_date=" . sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h) .
				" WHERE am_seq_no=$am_id";
	db_exec($sql);
	$msg = '全配信メールを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_all_mail WHERE am_seq_no=$am_id";
	db_exec($sql);
	$msg = '全配信メールを削除しました。';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail($from);
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
