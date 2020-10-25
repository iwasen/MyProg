<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メールアドレス発行
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

// 通知メール送信
function mail_send($mail_addr, $name, $free_email, $password) {
	get_mail_template('eml_mk', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MARKETER_NAME%', $name, $body);
	$body = str_replace('%MAIL_ADDR%', $free_email, $body);
	$body = str_replace('%PASSWORD%', $password, $body);
	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// メールサーバにメールアカウント作成
function make_account($free_email, $password) {
	$fetch = get_system_info('sy_pop_server');
	$output = exec("rsh -l vpopmail $fetch->sy_pop_server bin/vadduser $free_email $password", $arg, $ret);
	if ($ret <> 0)
		$output = 'メールアカウント作成コマンドが実行できませんでした。';

	return $output;
}

// メイン処理
set_global('marketer', 'マーケター管理', 'メールアドレス発行', BACK_TOP);

// 希望メールドメイン取得
$fetch = get_system_info('sy_mail_domain');
$mail_domain = $fetch->sy_mail_domain;

// 発行メールアドレス取得
if ($account == '@')
	$free_email = $account2;
$free_email = (($account == '@') ? $account2 : $account) . '@' . $mail_domain;

// 使用済みチェック
$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_free_email=" . sql_char($free_email);
$result = db_exec($sql);
if (pg_numrows($result) == 0) {
	$sql = "SELECT mr_mail_addr,mr_name1,mr_password FROM t_marketer WHERE mr_marketer_id=$marketer_id AND mr_free_email IS NULL";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$output = make_account($free_email, $fetch->mr_password);
		if ($output == '') {
			$sql = "UPDATE t_marketer SET mr_free_email=" . sql_char($free_email) . " WHERE mr_marketer_id=$marketer_id";
			db_exec($sql);

			mail_send($fetch->mr_mail_addr, $fetch->mr_name1, $free_email, $fetch->mr_password);

			$msg = 'メールアドレスを発行しました。';
			$back = "location.href='list.php'";
		} else {
			$msg = "メールアカウント作成に失敗しました。[$output]";
			$back = 'history.back()';
		}
	} else
		redirect('list.php');
} else {
	$msg = '指定したメールアドレスは使用済みです。';
	$back = 'history.back()';
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
