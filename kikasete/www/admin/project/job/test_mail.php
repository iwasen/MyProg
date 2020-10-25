<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:テストメール送信
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/str_replace.php");

set_global2('project', 'プロジェクト管理｜メール文登録', 'テストメール送信', BACK_TOP);

$sql = "SELECT jb_subject,jb_body,jb_mail_format,jb_search_id FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$subject = $fetch->jb_subject;
	$body = $fetch->jb_body;
	$mail_format = $fetch->jb_mail_format;
	$search_id = $fetch->jb_search_id;

	replace_kyoumi_genre($mail_format, $search_id, $body);

	$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');
	$enq_domain = $fetch->sy_enq_domain;
	$enq_account = strtok($fetch->sy_enq_account, '%');
	$from = "$enq_account@$enq_domain";
	$from_name = $fetch->from_name;

	if (strpos(__FILE__, 'agent'))
		$sql = "SELECT ag_mail_addr AS mail_addr,ag_name1 AS name1 FROM t_agent WHERE ag_agent_id={$_SESSION['agent_login_id']}";
	else
		$sql = "SELECT ad_mail_addr AS mail_addr,ad_name1 AS name1 FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$to_addr = $fetch->mail_addr;
		$to_name = $fetch->name1;
	}

	$send_mail_id = send_mail_data($subject, $from, '', $body, '', '', $from_name, $mail_format);
	send_mail_list($send_mail_id, $to_addr, 1);

	send_mail_embed($send_mail_id, 1, '%MAIL_ADDR%', $to_addr);
	send_mail_embed($send_mail_id, 1, '%MONITOR_NAME%', $to_name);

	send_mail_ok($send_mail_id);
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
<p class="msg"><?=htmlspecialchars($to_addr)?> 宛てにテストメールを送信しました。<br>（届くまでに１分程度かかる場合があります）</p>
<p><input type="button" id="ok" value="メール文設定に戻る" onclick="location.href='enq_mail.php?job_id=<?=$job_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
