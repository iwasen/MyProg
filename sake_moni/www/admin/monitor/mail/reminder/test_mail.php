<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:督促メール配信テストメール送信処理
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('monitor', '条件指定メール配信', '督促メール配信内容', BACK_TOP);

// セッション処理
if (!isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
$rm = &$_SESSION['ss_enq_rm'];
$rm->set_mail_data();

// メール情報
$mail_title = $rm->mail_title;
$header = $rm->mail_header;
$body = $rm->mail_body;
$footer = $rm->mail_footer;
$mail_text = trim($header) ."\n\n". trim($body) ."\n\n". trim($footer);

// 送信元アドレス取得(Webアンケート)
$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');

if ($fetch->from_name != '')
	$from = "$fetch->from_name <$fetch->sy_enq_account@$fetch->sy_enq_domain>";
else
	$from = "$fetch->sy_enq_account@$fetch->sy_enq_domain";

// メール送信
send_mail($mail_title, $_COOKIE['admin_mail_addr'], $from, $mail_text);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<p class="msg">テストメールを送信しました。</p>
<br>
<input type="button" value="　戻る　" onclick="location.href='edit.php'">
</div>

<? page_footer() ?>
</body>
</html>
