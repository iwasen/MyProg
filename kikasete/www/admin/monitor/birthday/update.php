<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:誕生日メール配信
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

function test_mail() {
	global $subject, $body, $from_adr;

    $fetch = get_system_info('sy_url_monitor');
	$login_url = "{$fetch->sy_url_monitor}birthday.php?id=00000000000000";

	$sql = "SELECT ad_mail_addr,ad_name1 FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);

		// 差込処理
		$body = str_replace('%MONITOR_NAME%', $fetch->ad_name1, $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->ad_mail_addr, $body);
		$body = str_replace('%LOGIN_URL%', $login_url, $body);

		send_mail($subject, $fetch->ad_mail_addr, $from_adr, $body);
	}
}

// メイン処理
set_global('monitor', 'モニター管理', '誕生日メール配信', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = 'INSERT INTO t_birthday_mail (bm_priority,bm_sex,bm_age_from,bm_age_to,bm_point,bm_from_adr,bm_subject,bm_body,bm_void_flag) VALUES (' .
				sql_number($priority) . ',' .
				sql_number($sex) . ',' .
				sql_number($age_from) . ',' .
				sql_number($age_to) . ',' .
				sql_number($point) . ',' .
				sql_char($from_adr) . ',' .
				sql_char($subject) . ',' .
				sql_char($body) . ',' .
				sql_number($void_flag) . ')';
	db_exec($sql);
	$msg = '誕生日メールを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "UPDATE t_birthday_mail SET" .
				" bm_priority=" . sql_number($priority) .
				", bm_sex=" . sql_number($sex) .
				", bm_age_from=" . sql_number($age_from) .
				", bm_age_to=" . sql_number($age_to) .
				", bm_point=" . sql_number($point) .
				", bm_from_adr=" . sql_char($from_adr) .
				", bm_subject=" . sql_char($subject) .
				", bm_body=" . sql_char($body) .
				", bm_void_flag=" . sql_number($void_flag) .
				" WHERE bm_seq_no=$seq_no";
	db_exec($sql);
	$msg = '誕生日メールを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_birthday_mail WHERE bm_seq_no=$seq_no";
	db_exec($sql);
	$msg = '誕生日メールを削除しました。';
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
