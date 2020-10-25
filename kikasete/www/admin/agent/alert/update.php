<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アラートメール
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
	$sql = "SELECT ad_mail_addr,ad_name1 FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$body = str_replace('%AGENT_NAME%', $fetch->ad_name1, $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->ad_mail_addr, $body);

		send_mail($subject, $fetch->ad_mail_addr, $from, $body);
	}
}

// メイン処理
set_global('agent', 'エージェント管理', 'アラートメール', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO t_alert_mail (am_kind,am_agent_id,am_subject,am_body,am_send_date) VALUES (%s,%s,%s,%s,%s)",
				sql_number($kind),
				sql_char(get_multi_data($agent_id)),
				sql_char($subject),
				sql_char($body),
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h));
	db_exec($sql);
	$msg = 'アラートメールを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = sprintf("UPDATE t_alert_mail SET am_agent_id=%s,am_subject=%s,am_body=%s,am_send_date=%s WHERE am_seq_no=$seq_no",
				sql_char(get_multi_data($agent_id)),
				sql_char($subject),
				sql_char($body),
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h));
	db_exec($sql);
	$msg = 'アラートメールを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_alert_mail WHERE am_seq_no=$seq_no";
	db_exec($sql);
	$msg = 'アラートメールを削除しました。';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail();
	$msg = 'テストメールを登録しました。';
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
