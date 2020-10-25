<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:メール送信
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");

set_global('charara', '', 'きゃららFactory メール返信', BACK_TOP);

// 送信先（ユーザ）メールアドレス取得
$sql = "SELECT nd_mail_addr FROM t_nigaoe_data WHERE nd_nigaoe_id=$nigaoe_id";
$to = db_fetch1($sql);

// メール送受信履歴に書き込み
$rec['lm_nigaoe_id'] = sql_number($nigaoe_id);
$rec['lm_send_recv'] = sql_char('S');
$rec['lm_from_addr'] = sql_char($from);
$rec['lm_to_addr'] = sql_char($to);
$rec['lm_subject'] = sql_char($subject);
$rec['lm_body'] = sql_char($body);
db_insert('l_mail', $rec);

// メール送信
send_mail($subject, $to, $from, $body);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>

<div align="center">
<p class="msg">メールを送信しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>

</body>
</html>
