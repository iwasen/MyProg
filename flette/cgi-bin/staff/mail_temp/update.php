#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:送信メールテンプレート更新処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$rec['mt_from_name'] = sql_char($from_name);
$rec['mt_from_addr'] = sql_char($from_addr);
$rec['mt_to_addr'] = sql_char($to_addr);
$rec['mt_cc_addr'] = sql_char($cc_addr);
$rec['mt_bcc_addr'] = sql_char($bcc_addr);
$rec['mt_subject'] = sql_char($subject);
$rec['mt_body'] = sql_char($body);
db_update('m_mail_template', $rec, "mt_template_id=" . sql_char($template_id));
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('送信メールテンプレート更新完了', true, true) ?>

<div align="center">
<p class="msg">送信メールテンプレートを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>

</body>
</html>
