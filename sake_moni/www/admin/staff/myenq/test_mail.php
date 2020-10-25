<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:Ｍｙアンケートテストメール送信
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/enq_mail.php");
include("$inc/mail.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('staff', 'スタッフ管理｜Ｍｙアンケート管理', 'テストメール送信', BACK_TOP);

// セッション処理
$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;

// メールタイトルが設定されていない場合はアンケートタイトルを表示
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// メール分が設定されていない場合はテンプレートを表示
if ($myenq->mail_contents == '')
	get_enq_body($myenq, $header, $body, $footer);
else {
	$header = $myenq->mail_header;
	$body = $myenq->mail_contents;
	$footer = $myenq->mail_footer;
}

// メールアドレスとモニター名を差込
$sql = "SELECT st_mail_addr,st_name1 FROM t_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$header = str_replace('%MAIL_ADDR%', $fetch->st_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->st_name1, $body);
}

// 送信メール編集
$mail_text = make_enq_mail($enquete, $header, $body, $footer);

// ポイント差込
$mail_text = str_replace('%POINT%', number_format($enquete->point), $mail_text);

// 送信元アドレス取得
if ($enquete->enq_type == 1)
	$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
else
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
<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? page_footer() ?>
</body>
</html>
