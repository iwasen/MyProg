<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:システムマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'システムマスタ', BACK_TOP);

$sql = "UPDATE m_system SET" .
			" sy_system_name=" . sql_char($sy_system_name) .
			", sy_url_top=" . sql_char($sy_url_top) .
			", sy_url_monitor=" . sql_char($sy_url_monitor) .
			", sy_url_staff=" . sql_char($sy_url_staff) .
			", sy_url_admin=" . sql_char($sy_url_admin) .
			", sy_mail_from=" . sql_char($sy_mail_from) .
			", sy_mail_name=" . sql_char($sy_mail_name) .
			", sy_myenq_to=" . sql_char($sy_myenq_to) .
			", sy_pop_server=" . sql_char($sy_pop_server) .
			", sy_envelope_addr=" . sql_char($sy_envelope_addr) .
			", sy_envelope_pwd=" . sql_char($sy_envelope_pwd) .
			", sy_enq_domain=" . sql_char($sy_enq_domain) .
			", sy_enq_account=" . sql_char($sy_enq_account) .
//			", sy_enq_pwd=" . sql_char($sy_enq_pwd) .
			", sy_enq_mail=" . sql_char($sy_enq_mail) .
			", sy_enq_web=" . sql_char($sy_enq_web) .
			", sy_mail_notify_to=" . sql_char($sy_mail_notify_to) .
			" WHERE sy_system_id=$sys_id";
db_exec($sql);

$msg = 'システムマスタ情報を更新しました。';
$ret = 'location.href=\'list.php\'';
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
